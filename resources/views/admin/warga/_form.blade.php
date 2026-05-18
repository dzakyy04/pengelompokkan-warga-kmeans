@php $w = $warga ?? null; @endphp

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
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Data Ekonomi</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Pekerjaan <span class="text-red-500">*</span></label>
            @php
                $selectedPekerjaanId = old('pekerjaan_id', $w->pekerjaan_id ?? '');
                $selectedPekerjaanNama = '';
                if ($selectedPekerjaanId && $selectedPekerjaanId !== 'lainnya') {
                    $found = $pekerjaans->firstWhere('id', $selectedPekerjaanId);
                    $selectedPekerjaanNama = $found ? $found->nama : '';
                } elseif ($selectedPekerjaanId === 'lainnya') {
                    $selectedPekerjaanNama = 'Lainnya...';
                }
                $uniqueId = 'pekerjaanSearch_' . uniqid();
            @endphp

            {{-- Hidden select (submitted to server) --}}
            <input type="hidden" name="pekerjaan_id" id="{{ $uniqueId }}_value" value="{{ $selectedPekerjaanId }}" required>

            {{-- Searchable dropdown trigger --}}
            <div class="relative w-full" id="{{ $uniqueId }}_wrapper">
                <input type="text" id="{{ $uniqueId }}_input" autocomplete="off"
                    value="{{ $selectedPekerjaanNama }}"
                    placeholder="Cari atau pilih pekerjaan..."
                    class="w-full px-4 py-2.5 pr-9 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">
                <span class="absolute inset-y-0 right-3 flex items-center text-gray-400 pointer-events-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </span>
                {{-- Dropdown list --}}
                <ul id="{{ $uniqueId }}_list"
                    class="hidden absolute z-[100] mt-1 w-full bg-white dark:bg-slate-800 border border-gray-200 dark:border-slate-600 rounded-xl shadow-lg max-h-52 overflow-y-auto text-sm">
                    <li data-value="" class="px-4 py-2.5 text-gray-400 dark:text-gray-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 cursor-pointer">-- Pilih Pekerjaan --</li>
                    @foreach($pekerjaans as $p)
                    <li data-value="{{ $p->id }}" class="px-4 py-2.5 text-gray-800 dark:text-gray-200 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 cursor-pointer">{{ $p->nama }}</li>
                    @endforeach
                    <li data-value="lainnya" class="px-4 py-2.5 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-50 dark:hover:bg-emerald-900/30 cursor-pointer font-medium">Lainnya...</li>
                </ul>
            </div>

            {{-- Input manual jika "Lainnya" dipilih --}}
            <input type="text" name="pekerjaan_baru" value="{{ old('pekerjaan_baru') }}" placeholder="Ketik nama pekerjaan..."
                id="{{ $uniqueId }}_baru"
                class="{{ old('pekerjaan_id') == 'lainnya' ? '' : 'hidden' }} mt-2 w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 outline-none">

            @error('pekerjaan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            @error('pekerjaan_baru')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror

            <script>
            (function() {
                const uid       = '{{ $uniqueId }}';
                const input     = document.getElementById(uid + '_input');
                const list      = document.getElementById(uid + '_list');
                const hidden    = document.getElementById(uid + '_value');
                const baruInput = document.getElementById(uid + '_baru');
                const items     = list.querySelectorAll('li[data-value]');

                function showList() { list.classList.remove('hidden'); }
                function hideList() { list.classList.add('hidden'); }

                function filterList(q) {
                    const lower = q.toLowerCase();
                    items.forEach(li => {
                        li.style.display = li.textContent.toLowerCase().includes(lower) ? '' : 'none';
                    });
                }

                function selectItem(val, label) {
                    hidden.value = val;
                    input.value = label;
                    baruInput.classList.toggle('hidden', val !== 'lainnya');
                    if (val === 'lainnya') baruInput.focus();
                    hideList();
                }

                input.addEventListener('focus', function() {
                    filterList('');
                    showList();
                });

                input.addEventListener('input', function() {
                    filterList(this.value);
                    showList();
                    // Reset hidden value while typing
                    hidden.value = '';
                    baruInput.classList.add('hidden');
                });

                list.addEventListener('mousedown', function(e) {
                    const li = e.target.closest('li[data-value]');
                    if (!li) return;
                    e.preventDefault();
                    selectItem(li.dataset.value, li.textContent.trim());
                });

                document.addEventListener('click', function(e) {
                    if (!document.getElementById(uid + '_wrapper').contains(e.target)) {
                        hideList();
                    }
                });
            })();
            </script>
        </div>

        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Pendapatan per Bulan <span class="text-red-500">*</span></label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-500">Rp</span>
                @php
                    $pendapatanVal = old('pendapatan', $w->pendapatan ?? '');
                    $pendapatanFormatted = $pendapatanVal ? number_format((int)str_replace('.', '', $pendapatanVal), 0, ',', '.') : '';
                @endphp
                <input type="text" name="pendapatan" value="{{ $pendapatanFormatted }}" required oninput="this.value = this.value.replace(/[^0-9]/g, '').replace(/\B(?=(\d{3})+(?!\d))/g, '.')" class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>
            @error('pendapatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Jumlah Tanggungan <span class="text-red-500">*</span></label>
            <input type="number" name="jumlah_tanggungan" value="{{ old('jumlah_tanggungan', $w->jumlah_tanggungan ?? '') }}" required min="0" max="20" class="w-full px-4 py-2.5 border border-gray-300 dark:border-slate-600 rounded-xl text-sm bg-white dark:bg-slate-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            @error('jumlah_tanggungan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="bg-white dark:bg-slate-800/50 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6">
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Kondisi Rumah & Aset</h3>
    <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-1">Kondisi Rumah <span class="text-red-500">*</span></label>
        <div class="relative w-full md:w-1/2">
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
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Aset yang Dimiliki</label>
        @php $selectedAsets = old('asets', $w ? $w->asets->pluck('id')->toArray() : []); @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
            @foreach($asets as $a)
            <label class="flex items-center gap-2 px-3 py-2.5 bg-gray-50 dark:bg-slate-900 rounded-xl hover:bg-emerald-50 dark:hover:bg-emerald-900/30 transition cursor-pointer border border-gray-200 dark:border-slate-700">
                <input type="checkbox" name="asets[]" value="{{ $a->id }}" {{ in_array($a->id, $selectedAsets) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $a->nama }}</span>
            </label>
            @endforeach
        </div>
    </div>
</div>
