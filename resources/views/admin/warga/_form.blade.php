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
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Data Ekonomi & Pendidikan</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
    <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Kondisi Rumah & Penerima Bansos</h3>
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
        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Bansos yang Diterima <span class="text-red-500">*</span></label>
        <div class="relative w-full md:w-1/2">
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
        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Pilih satu jenis bansos yang diterima. Pilih "Tidak Menerima" jika warga tidak menerima bansos apa pun.</p>
        @error('bansos_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
</div>
