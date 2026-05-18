<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="fi-wi-stats-overview-stat bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-users class="w-6 h-6 text-primary-500" />
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Warga</span>
                </div>
                <p class="text-3xl font-bold text-gray-950 dark:text-white mt-2">{{ $totalWarga }}</p>
            </div>

            @if($latestSession)
            <div class="fi-wi-stats-overview-stat bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-chart-pie class="w-6 h-6 text-blue-500" />
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Session Terakhir</span>
                </div>
                <p class="text-3xl font-bold text-gray-950 dark:text-white mt-2">#{{ $latestSession->id }}</p>
                <p class="text-sm text-gray-500 mt-1">Status: <span class="font-semibold text-{{ match($latestSession->status){'validated'=>'success','completed'=>'blue','rejected'=>'danger',default=>'warning'} }}-500">{{ ucfirst($latestSession->status) }}</span></p>
            </div>

            <div class="fi-wi-stats-overview-stat bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-clock class="w-6 h-6 text-amber-500" />
                    <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Proses</span>
                </div>
                <p class="text-lg font-bold text-gray-950 dark:text-white mt-2">{{ $latestSession->created_at->format('d M Y H:i') }}</p>
            </div>
            @else
            <div class="col-span-2 bg-yellow-50 dark:bg-yellow-900/20 rounded-xl p-6 ring-1 ring-yellow-200 dark:ring-yellow-800">
                <p class="text-yellow-800 dark:text-yellow-200">Belum ada hasil clustering. Klik tombol <strong>"Proses K-Means"</strong> untuk memulai.</p>
            </div>
            @endif
        </div>

        {{-- Results Table --}}
        @if($latestSession && $latestSession->centroids->count() > 0)
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm ring-1 ring-gray-950/5 dark:ring-white/10 overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Centroid Cluster (Normalisasi)</h3>
            </div>
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-gray-600 dark:text-gray-300">Cluster</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Anggota</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Pendapatan</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Pekerjaan</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Tanggungan</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Kond. Rumah</th>
                        <th class="px-4 py-3 text-center font-semibold text-gray-600 dark:text-gray-300">Aset</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($latestSession->centroids->sortBy('cluster') as $c)
                    <tr>
                        <td class="px-4 py-3">
                            <span @class([
                                'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium',
                                'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' => $c->label === 'Rendah',
                                'bg-amber-100 text-amber-800 dark:bg-amber-900/20 dark:text-amber-400' => $c->label === 'Sedang',
                                'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/20 dark:text-emerald-400' => $c->label === 'Tinggi',
                            ])>{{ $c->label }}</span>
                        </td>
                        <td class="px-4 py-3 text-center font-semibold text-gray-900 dark:text-white">{{ $c->jumlah_anggota }}</td>
                        <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ number_format($c->centroid_pendapatan, 4) }}</td>
                        <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ number_format($c->centroid_pekerjaan, 4) }}</td>
                        <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ number_format($c->centroid_tanggungan, 4) }}</td>
                        <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ number_format($c->centroid_kondisi_rumah, 4) }}</td>
                        <td class="px-4 py-3 text-center text-gray-600 dark:text-gray-300">{{ number_format($c->centroid_aset, 4) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</x-filament-panels::page>
