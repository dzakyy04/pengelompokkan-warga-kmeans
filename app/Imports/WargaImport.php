<?php

namespace App\Imports;

use App\Models\Warga;
use App\Models\MasterPendidikan;
use App\Models\MasterKondisiRumah;
use App\Models\MasterBansos;
use App\Models\MasterPekerjaan;
use App\Models\ClusteringSession;
use App\Services\KMeansService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Importable;

class WargaImport implements WithMultipleSheets
{
    use Importable;

    private $sheetImport;

    public function __construct()
    {
        $this->sheetImport = new WargaSheetImport();
    }

    public function sheets(): array
    {
        // Hanya proses sheet pertama (index 0), abaikan sheet lainnya
        return [
            0 => $this->sheetImport,
        ];
    }

    public function getImportedCount(): int
    {
        return $this->sheetImport->getImportedCount();
    }

    public function getSkippedCount(): int
    {
        return $this->sheetImport->getSkippedCount();
    }

    public function getFailureCount(): int
    {
        return $this->sheetImport->getFailureCount();
    }
}

class WargaSheetImport implements ToModel, WithHeadingRow, SkipsEmptyRows
{
    private $pendidikanMap;
    private $kondisiRumahMap;
    private $bansosMap;
    private $pekerjaanMap;
    private $importedCount = 0;
    private $skippedCount = 0;
    private $failureCount = 0;
    private $baseModel;
    private $kmeansService;

    public function __construct()
    {
        $this->pendidikanMap = MasterPendidikan::all()->mapWithKeys(fn($p) => [strtolower(trim($p->nama)) => $p->id]);
        $this->kondisiRumahMap = MasterKondisiRumah::all()->mapWithKeys(fn($k) => [strtolower(trim($k->nama)) => $k->id]);
        $this->bansosMap = MasterBansos::all()->mapWithKeys(fn($b) => [strtolower(trim($b->nama)) => $b->id]);
        $this->pekerjaanMap = MasterPekerjaan::all()->mapWithKeys(fn($p) => [
            strtolower(trim($p->nama)) => [
                'status_produktivitas' => $p->status_produktivitas,
                'skor' => $p->skor,
            ]
        ]);
        $this->baseModel = ClusteringSession::getActiveBaseModel();
        $this->kmeansService = new KMeansService();
    }

    public function model(array $row)
    {
        // Skip baris yang tidak punya kolom wajib
        $namaLengkap = trim($row['nama_lengkap'] ?? '');
        $nikRaw = trim($row['nik'] ?? '');
        $pekerjaan = trim($row['pekerjaan'] ?? '');

        if (empty($namaLengkap) || empty($nikRaw) || empty($pekerjaan)) {
            if (!empty($namaLengkap) || !empty($nikRaw)) {
                $this->failureCount++;
            }
            return null;
        }

        // Handle NIK yang dibaca sebagai angka/scientific notation oleh Excel
        $nik = $nikRaw;
        if (is_numeric($nik)) {
            $nik = number_format((float) $nik, 0, '', '');
        }
        $nik = str_pad($nik, 16, '0', STR_PAD_LEFT);

        // Skip jika NIK sudah ada
        if (Warga::where('nik', $nik)->exists()) {
            $this->skippedCount++;
            return null;
        }

        // Tentukan status produktivitas
        $pekerjaanKey = strtolower($pekerjaan);
        $statusManual = trim($row['status_produktivitas'] ?? '');

        if ($statusManual !== '' && in_array($statusManual, ['Stabil', 'Cukup Stabil', 'Tidak Stabil', 'Tidak Produktif'])) {
            $skorMap = ['Tidak Produktif' => 1, 'Tidak Stabil' => 2, 'Cukup Stabil' => 3, 'Stabil' => 4];
            $statusProduktivitas = $statusManual;
            $skorProduktivitas = $skorMap[$statusManual] ?? 1;
        } elseif (isset($this->pekerjaanMap[$pekerjaanKey])) {
            $statusProduktivitas = $this->pekerjaanMap[$pekerjaanKey]['status_produktivitas'];
            $skorProduktivitas = $this->pekerjaanMap[$pekerjaanKey]['skor'];
        } else {
            $statusProduktivitas = 'Tidak Produktif';
            $skorProduktivitas = 1;
        }

        // Lookup master data
        $pendidikanId = $this->pendidikanMap[strtolower(trim($row['pendidikan'] ?? ''))] ?? null;
        $kondisiRumahId = $this->kondisiRumahMap[strtolower(trim($row['kondisi_rumah'] ?? ''))] ?? null;
        $bansosId = $this->bansosMap[strtolower(trim($row['bansos'] ?? ''))] ?? null;

        if (!$pendidikanId || !$kondisiRumahId || !$bansosId) {
            $this->failureCount++;
            return null;
        }

        $this->importedCount++;

        $warga = new Warga([
            'nama_lengkap' => $namaLengkap,
            'nik' => $nik,
            'rt_rw' => trim($row['rt_rw'] ?? ''),
            'pekerjaan' => $pekerjaan,
            'status_produktivitas' => $statusProduktivitas,
            'skor_produktivitas' => $skorProduktivitas,
            'jumlah_tanggungan' => (int) ($row['jumlah_tanggungan'] ?? 0),
            'pendidikan_id' => $pendidikanId,
            'kondisi_rumah_id' => $kondisiRumahId,
            'bansos_id' => $bansosId,
        ]);

        // Save dulu agar punya ID, lalu auto-classify
        $warga->save();

        if ($this->baseModel) {
            try {
                $this->kmeansService->classifyNewWarga($warga);
            } catch (\Exception $e) {
                // Gagal classify tidak menggagalkan import
            }
        }

        return null; // Sudah di-save manual di atas
    }

    public function getImportedCount(): int { return $this->importedCount; }
    public function getSkippedCount(): int { return $this->skippedCount; }
    public function getFailureCount(): int { return $this->failureCount; }
}
