@php
    $w = $warga ?? null;
    $formId = $w ? $w->id : 'new';
@endphp

<div class="bg-white dark:bg-slate-800/50 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 mb-4">
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Data Pribadi</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $w->nama_lengkap ?? '') }}" required class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            @error('nama_lengkap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">NIK <span class="text-red-500">*</span></label>
            <input type="text" name="nik" value="{{ old('nik', $w->nik ?? '') }}" required maxlength="16" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none font-mono">
            @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">RT / RW</label>
            <input type="text" name="rt_rw" value="{{ old('rt_rw', $w->rt_rw ?? '') }}" placeholder="RT 01 / RW 02" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800/50 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 mb-4">
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Pekerjaan & Status Produktivitas</h3>

    {{-- Hidden inputs yang dikirim ke server --}}
    <input type="hidden" name="pekerjaan" id="input_pekerjaan_{{ $formId }}" value="{{ old('pekerjaan', $w->pekerjaan ?? '') }}">
    <input type="hidden" name="status_produktivitas" id="input_status_{{ $formId }}" value="{{ old('status_produktivitas', $w->status_produktivitas ?? '') }}">
    <input type="hidden" name="skor_produktivitas" id="input_skor_{{ $formId }}" value="{{ old('skor_produktivitas', $w->skor_produktivitas ?? '') }}">

    @php
        $presetPekerjaanList = $presetPekerjaan ?? \App\Models\Warga::presetPekerjaan();
        $statusProdList = $statusProduktivitas ?? \App\Models\Warga::statusProduktivitas();
        $currentPekerjaan = old('pekerjaan', $w->pekerjaan ?? '');
        $currentStatus = old('status_produktivitas', $w->status_produktivitas ?? '');
        $currentSkor = old('skor_produktivitas', $w->skor_produktivitas ?? '');
        $isPreset = collect($presetPekerjaanList)->pluck('pekerjaan')->contains($currentPekerjaan);
        $isLainnya = $currentPekerjaan !== '' && !$isPreset;
    @endphp

    {{-- Tabel pilihan pekerjaan preset --}}
    <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Pilih Pekerjaan <span class="text-red-500">*</span></label>
        <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-slate-600">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 dark:bg-slate-700">
                    <tr>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-8"></th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Pekerjaan</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status Produktivitas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 dark:divide-slate-700">
                    @foreach($presetPekerjaanList as $preset)
                    <tr class="hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 cursor-pointer pekerjaan-row-{{ $formId }} transition-colors {{ ($currentPekerjaan === $preset['pekerjaan']) ? 'bg-emerald-50 dark:bg-emerald-900/20' : '' }}"
                        data-pekerjaan="{{ $preset['pekerjaan'] }}"
                        data-status="{{ $preset['status'] }}"
                        data-skor="{{ $preset['skor'] }}"
                        onclick="selectPekerjaan(this, '{{ $formId }}')">
                        <td class="px-4 py-2.5 text-center">
                            <div class="w-4 h-4 rounded-full border-2 border-gray-300 dark:border-slate-500 mx-auto pekerjaan-radio-{{ $formId }} {{ ($currentPekerjaan === $preset['pekerjaan']) ? 'border-emerald-500 bg-emerald-500' : '' }}"></div>
                        </td>
                        <td class="px-4 py-2.5 font-medium text-gray-800 dark:text-gray-200">{{ $preset['pekerjaan'] }}</td>
                        <td class="px-4 py-2.5">
                            @php
                                $badgeClass = match($preset['status']) {
                                    'Stabil'         => 'bg-teal-100 text-teal-700 dark:bg-teal-900/30 dark:text-teal-400',
                                    'Cukup Stabil'   => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                    'Tidak Stabil'   => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                    'Tidak Produktif'=> 'bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400',
                                    default          => 'bg-gray-100 text-gray-700 dark:bg-slate-700 dark:text-gray-300',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold {{ $badgeClass }}">{{ $preset['status'] }}</span>
                        </td>
                    </tr>
                    @endforeach
                    {{-- Baris Lainnya --}}
                    <tr class="hover:bg-emerald-50/50 dark:hover:bg-emerald-900/10 cursor-pointer pekerjaan-row-{{ $formId }} transition-colors {{ $isLainnya ? 'bg-emerald-50 dark:bg-emerald-900/20' : '' }}"
                        data-pekerjaan="__lainnya__"
                        onclick="selectPekerjaan(this, '{{ $formId }}')">
                        <td class="px-4 py-2.5 text-center">
                            <div class="w-4 h-4 rounded-full border-2 border-gray-300 dark:border-slate-500 mx-auto pekerjaan-radio-{{ $formId }} {{ $isLainnya ? 'border-emerald-500 bg-emerald-500' : '' }}"></div>
                        </td>
                        <td class="px-4 py-2.5 font-medium text-gray-800 dark:text-gray-200 italic">Lainnya...</td>
                        <td class="px-4 py-2.5 text-gray-400 dark:text-gray-500 text-xs">Isi manual di bawah</td>
                    </tr>
                </tbody>
            </table>
        </div>
        @error('pekerjaan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        @error('status_produktivitas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        @error('skor_produktivitas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>

    {{-- Panel Lainnya --}}
    <div id="panel-lainnya-{{ $formId }}" class="{{ $isLainnya ? '' : 'hidden' }} bg-gray-50 dark:bg-slate-900/50 rounded-xl border border-gray-200 dark:border-slate-600 p-4">
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">Isi nama pekerjaan dan pilih status produktivitasnya:</p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Pekerjaan</label>
                <input type="text" id="input_pekerjaan_lainnya_{{ $formId }}" value="{{ $isLainnya ? $currentPekerjaan : '' }}"
                    placeholder="Contoh: Sopir, Tukang, dll."
                    oninput="updateLainnyaPekerjaan(this.value, '{{ $formId }}')"
                    class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Status Produktivitas</label>
                <div class="relative w-full">
                    <select id="select_status_lainnya_{{ $formId }}"
                        onchange="updateLainnyaStatus(this, '{{ $formId }}')"
                        class="appearance-none w-full pl-4 pr-9 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                        <option value="">-- Pilih Status --</option>
                        @foreach($statusProdList as $sp)
                        <option value="{{ $sp['status'] }}" data-skor="{{ $sp['skor'] }}"
                            {{ $isLainnya && $currentStatus === $sp['status'] ? 'selected' : '' }}>
                            {{ $sp['status'] }}
                        </option>
                        @endforeach
                    </select>
                    <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan --}}
    <div id="status-summary-{{ $formId }}" class="{{ ($currentPekerjaan !== '' && !$isLainnya) || ($isLainnya && $currentStatus !== '') ? '' : 'hidden' }} mt-3 flex items-center gap-3 p-3 bg-emerald-50 dark:bg-emerald-900/20 rounded-xl border border-emerald-200 dark:border-emerald-800">
        <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div class="text-sm">
            <span class="text-gray-600 dark:text-gray-400">Pekerjaan: </span>
            <strong id="summary-pekerjaan-{{ $formId }}" class="text-gray-900 dark:text-white">{{ $currentPekerjaan ?: '-' }}</strong>
            <span class="mx-2 text-gray-400">•</span>
            <span class="text-gray-600 dark:text-gray-400">Status: </span>
            <strong id="summary-status-{{ $formId }}" class="text-emerald-700 dark:text-emerald-400">
                @if(!$isLainnya && $currentPekerjaan !== '')
                    @php $found = collect($presetPekerjaanList)->firstWhere('pekerjaan', $currentPekerjaan); @endphp
                    {{ $found ? $found['status'] : '-' }}
                @elseif($isLainnya && $currentStatus !== '')
                    {{ $currentStatus }}
                @else
                    -
                @endif
            </strong>
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800/50 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 mb-4">
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Data Pendidikan & Tanggungan</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Pendidikan Kepala Keluarga <span class="text-red-500">*</span></label>
            <div class="relative w-full">
                <select name="pendidikan_id" required class="appearance-none w-full pl-4 pr-9 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">-- Pilih Pendidikan --</option>
                    @foreach($pendidikans as $p)
                    <option value="{{ $p->id }}" {{ old('pendidikan_id', $w->pendidikan_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                    @endforeach
                </select>
                <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
            </div>
            @error('pendidikan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Jumlah Tanggungan <span class="text-red-500">*</span></label>
            <input type="number" name="jumlah_tanggungan" value="{{ old('jumlah_tanggungan', $w->jumlah_tanggungan ?? '') }}" required min="0" max="20" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            @error('jumlah_tanggungan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800/50 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Kondisi Rumah & Penerima Bansos</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Kondisi Rumah <span class="text-red-500">*</span></label>
            <div class="relative w-full">
                <select name="kondisi_rumah_id" required class="appearance-none w-full pl-4 pr-9 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Pilih Kondisi Rumah</option>
                    @foreach($kondisiRumahs as $kr)
                    <option value="{{ $kr->id }}" {{ old('kondisi_rumah_id', $w->kondisi_rumah_id ?? '') == $kr->id ? 'selected' : '' }}>{{ $kr->nama }}</option>
                    @endforeach
                </select>
                <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
            </div>
            @error('kondisi_rumah_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Bansos yang Diterima <span class="text-red-500">*</span></label>
            <div class="relative w-full">
                <select name="bansos_id" required class="appearance-none w-full pl-4 pr-9 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Pilih Status Bansos</option>
                    @foreach($bansos as $b)
                    <option value="{{ $b->id }}" {{ old('bansos_id', $w->bansos_id ?? '') == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                    @endforeach
                </select>
                <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
            </div>
            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Pilih "Tidak Menerima" jika warga tidak menerima bansos.</p>
            @error('bansos_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>
