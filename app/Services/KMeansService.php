<?php

namespace App\Services;

use App\Models\ClusterCentroid;
use App\Models\ClusteringResult;
use App\Models\ClusteringSession;
use App\Models\Warga;
use App\Models\WargaClassificationQueue;
use Phpml\Clustering\KMeans;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class KMeansService
{
    public function process(int $numClusters = 3, int $maxIterations = 100): ClusteringSession
    {
        $wargas = Warga::with(['pendidikan', 'kondisiRumah', 'bansos'])->get();

        if ($wargas->count() < $numClusters) {
            throw new \Exception("Jumlah data warga ({$wargas->count()}) kurang dari jumlah cluster ({$numClusters}).");
        }

        // 1. Build raw feature matrix
        // Fitur: pendapatan, tanggungan, pendidikan kepala keluarga (skor), kondisi rumah (skor), bansos (skor)
        $rawSamples = [];
        $wargaList = [];
        foreach ($wargas as $warga) {
            $rawSamples[] = [
                (float) $warga->pendapatan,
                (float) $warga->jumlah_tanggungan,
                (float) ($warga->pendidikan->skor ?? 0),
                (float) ($warga->kondisiRumah->skor ?? 0),
                (float) ($warga->bansos->skor ?? 0),
            ];
            $wargaList[] = $warga;
        }

        // 2. Min-Max Normalization
        $numFeatures = 5;
        $mins = $maxs = [];
        for ($i = 0; $i < $numFeatures; $i++) {
            $col = array_column($rawSamples, $i);
            $mins[$i] = min($col);
            $maxs[$i] = max($col);
        }

        $normalizationParams = [];
        for ($i = 0; $i < $numFeatures; $i++) {
            $normalizationParams[$i] = ['min' => $mins[$i], 'max' => $maxs[$i]];
        }

        $normalized = [];
        foreach ($rawSamples as $sample) {
            $row = [];
            for ($i = 0; $i < $numFeatures; $i++) {
                $range = $maxs[$i] - $mins[$i];
                $row[] = $range > 0 ? ($sample[$i] - $mins[$i]) / $range : 0.0;
            }
            $normalized[] = $row;
        }

        // 3. Run K-Means via php-ml
        $kmeans = new KMeans($numClusters);
        $clusterResults = $kmeans->cluster($normalized);

        // 4. Map results back using nearest-neighbor matching (robust against float precision)
        $usedIndices = [];
        $wargaClusterMap = [];
        $clusterCentroidData = [];

        foreach ($clusterResults as $clusterIdx => $clusterSamples) {
            $centroid = $this->calculateCentroid($clusterSamples);
            $clusterCentroidData[$clusterIdx] = $centroid;

            foreach ($clusterSamples as $clusterSample) {
                // Find the closest original sample by Euclidean distance
                $bestIdx = null;
                $bestDist = PHP_FLOAT_MAX;

                for ($j = 0; $j < count($normalized); $j++) {
                    if (in_array($j, $usedIndices)) continue;
                    $dist = $this->euclideanDistance($clusterSample, $normalized[$j]);
                    if ($dist < $bestDist) {
                        $bestDist = $dist;
                        $bestIdx = $j;
                    }
                }

                if ($bestIdx !== null) {
                    $usedIndices[] = $bestIdx;
                    $distToCentroid = $this->euclideanDistance($clusterSample, $centroid);
                    $wargaClusterMap[] = [
                        'warga_id' => $wargaList[$bestIdx]->id,
                        'cluster' => $clusterIdx,
                        'distance' => $distToCentroid,
                    ];
                }
            }
        }

        // 5. Auto-label clusters by average pendapatan (Rendah < Sedang < Tinggi)
        $grouped = collect($wargaClusterMap)->groupBy('cluster');
        $clusterAvg = [];
        foreach ($grouped as $ci => $members) {
            $ids = $members->pluck('warga_id');
            $clusterAvg[$ci] = $wargas->whereIn('id', $ids)->avg('pendapatan');
        }
        asort($clusterAvg);

        $labels = ['Rendah', 'Sedang', 'Tinggi'];
        $labelMap = [];
        $i = 0;
        foreach (array_keys($clusterAvg) as $ci) {
            $labelMap[$ci] = $labels[$i] ?? "Cluster " . ($i + 1);
            $i++;
        }

        // 6. Save to database
        return DB::transaction(function () use ($wargaClusterMap, $clusterCentroidData, $grouped, $labelMap, $numClusters, $maxIterations, $normalizationParams) {
            $session = ClusteringSession::create([
                'user_id' => Auth::id(),
                'jumlah_cluster' => $numClusters,
                'max_iterasi' => $maxIterations,
                'status' => 'completed',
                'normalization_params' => $normalizationParams,
            ]);

            foreach ($wargaClusterMap as $item) {
                ClusteringResult::create([
                    'session_id' => $session->id,
                    'warga_id' => $item['warga_id'],
                    'cluster' => $item['cluster'],
                    'label' => $labelMap[$item['cluster']],
                    'jarak_ke_centroid' => $item['distance'],
                ]);
            }

            foreach ($clusterCentroidData as $ci => $centroid) {
                ClusterCentroid::create([
                    'session_id' => $session->id,
                    'cluster' => $ci,
                    'label' => $labelMap[$ci],
                    'centroid_pendapatan' => $centroid[0] ?? 0,
                    'centroid_tanggungan' => $centroid[1] ?? 0,
                    'centroid_pendidikan' => $centroid[2] ?? 0,
                    'centroid_kondisi_rumah' => $centroid[3] ?? 0,
                    'centroid_bansos' => $centroid[4] ?? 0,
                    'jumlah_anggota' => ($grouped[$ci] ?? collect())->count(),
                ]);
            }

            return $session;
        });
    }

    private function calculateCentroid(array $samples): array
    {
        $samples = array_values($samples); // Re-index since php-ml preserves original keys
        if (empty($samples)) return [0, 0, 0, 0, 0];
        $n = count($samples[0]);
        $centroid = array_fill(0, $n, 0.0);
        foreach ($samples as $s) {
            for ($i = 0; $i < $n; $i++) {
                $centroid[$i] += $s[$i];
            }
        }
        $count = count($samples);
        for ($i = 0; $i < $n; $i++) {
            $centroid[$i] /= $count;
        }
        return $centroid;
    }

    private function euclideanDistance(array $a, array $b): float
    {
        $sum = 0.0;
        $n = min(count($a), count($b));
        for ($i = 0; $i < $n; $i++) {
            $sum += ($a[$i] - $b[$i]) ** 2;
        }
        return sqrt($sum);
    }

    public function classifyNewWarga(Warga $warga): WargaClassificationQueue
    {
        $baseModel = ClusteringSession::getActiveBaseModel();
        if (!$baseModel) {
            throw new \Exception('Tidak ada base model aktif. Silakan lakukan training K-Means terlebih dahulu dan aktifkan sebagai base model.');
        }

        $warga->load(['pendidikan', 'kondisiRumah', 'bansos']);
        $rawFeatures = [
            (float) $warga->pendapatan,
            (float) $warga->jumlah_tanggungan,
            (float) ($warga->pendidikan->skor ?? 0),
            (float) ($warga->kondisiRumah->skor ?? 0),
            (float) ($warga->bansos->skor ?? 0),
        ];

        $params = $baseModel->normalization_params;
        $normalized = [];
        for ($i = 0; $i < count($rawFeatures); $i++) {
            $min = $params[$i]['min'];
            $max = $params[$i]['max'];
            $range = $max - $min;
            $normalized[] = $range > 0 ? ($rawFeatures[$i] - $min) / $range : 0.0;
        }

        $centroids = $baseModel->centroids()->orderBy('cluster')->get();
        $distances = [];
        foreach ($centroids as $centroid) {
            $centroidValues = [
                (float) $centroid->centroid_pendapatan,
                (float) $centroid->centroid_tanggungan,
                (float) $centroid->centroid_pendidikan,
                (float) $centroid->centroid_kondisi_rumah,
                (float) $centroid->centroid_bansos,
            ];
            $distances[$centroid->cluster] = [
                'distance' => $this->euclideanDistance($normalized, $centroidValues),
                'label' => $centroid->label,
            ];
        }

        $nearestCluster = collect($distances)->sortBy('distance')->keys()->first();
        $nearest = $distances[$nearestCluster];

        return WargaClassificationQueue::create([
            'warga_id' => $warga->id,
            'base_model_session_id' => $baseModel->id,
            'assigned_cluster' => $nearestCluster,
            'assigned_label' => $nearest['label'],
            'distance_to_centroid' => $nearest['distance'],
            'distances_to_all_centroids' => $distances,
            'feature_values' => $normalized,
            'status' => 'pending',
        ]);
    }

    public function activateAsBaseModel(ClusteringSession $session): void
    {
        if ($session->status !== 'validated') {
            throw new \Exception('Hanya session yang sudah divalidasi yang bisa dijadikan base model.');
        }
        if (!$session->normalization_params) {
            throw new \Exception('Session ini tidak memiliki parameter normalisasi. Silakan lakukan training ulang.');
        }

        DB::transaction(function () use ($session) {
            ClusteringSession::where('is_base_model', true)->update(['is_base_model' => false]);
            $session->update(['is_base_model' => true]);
        });
    }
}
