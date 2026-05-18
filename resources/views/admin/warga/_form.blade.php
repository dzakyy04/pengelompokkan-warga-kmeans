@php $w = $warga ?? null; @endphp

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-4">
    <h3 class="text-base font-bold text-gray-900 mb-4">Data Pribadi</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
            <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $w->nama_lengkap ?? '') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            @error('nama_lengkap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">NIK <span class="text-red-500">*</span></label>
            <input type="text" name="nik" value="{{ old('nik', $w->nik ?? '') }}" required maxlength="16" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none font-mono">
            @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">RT / RW</label>
            <input type="text" name="rt_rw" value="{{ old('rt_rw', $w->rt_rw ?? '') }}" placeholder="RT 01 / RW 02" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mb-4">
    <h3 class="text-base font-bold text-gray-900 mb-4">Data Ekonomi</h3>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Pekerjaan <span class="text-red-500">*</span></label>
            <select name="pekerjaan_id" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
                <option value="">Pilih Pekerjaan</option>
                @foreach($pekerjaans as $p)
                <option value="{{ $p->id }}" {{ old('pekerjaan_id', $w->pekerjaan_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nama }}</option>
                @endforeach
            </select>
            @error('pekerjaan_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Pendapatan per Bulan <span class="text-red-500">*</span></label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-500">Rp</span>
                <input type="text" name="pendapatan" value="{{ old('pendapatan', $w->pendapatan ?? '') }}" required class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            </div>
            @error('pendapatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-semibold text-gray-700 mb-1">Jumlah Tanggungan <span class="text-red-500">*</span></label>
            <input type="number" name="jumlah_tanggungan" value="{{ old('jumlah_tanggungan', $w->jumlah_tanggungan ?? '') }}" required min="0" max="20" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 outline-none">
            @error('jumlah_tanggungan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6">
    <h3 class="text-base font-bold text-gray-900 mb-4">Kondisi Rumah & Aset</h3>
    <div class="mb-4">
        <label class="block text-sm font-semibold text-gray-700 mb-1">Kondisi Rumah <span class="text-red-500">*</span></label>
        <select name="kondisi_rumah_id" required class="w-full md:w-1/2 px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none">
            <option value="">Pilih Kondisi Rumah</option>
            @foreach($kondisiRumahs as $kr)
            <option value="{{ $kr->id }}" {{ old('kondisi_rumah_id', $w->kondisi_rumah_id ?? '') == $kr->id ? 'selected' : '' }}>{{ $kr->nama }} (Skor: {{ $kr->skor }})</option>
            @endforeach
        </select>
        @error('kondisi_rumah_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-2">Aset yang Dimiliki</label>
        @php $selectedAsets = old('asets', $w ? $w->asets->pluck('id')->toArray() : []); @endphp
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
            @foreach($asets as $a)
            <label class="flex items-center gap-2 px-3 py-2.5 bg-gray-50 rounded-xl hover:bg-emerald-50 transition cursor-pointer border border-gray-200">
                <input type="checkbox" name="asets[]" value="{{ $a->id }}" {{ in_array($a->id, $selectedAsets) ? 'checked' : '' }} class="rounded text-emerald-600 focus:ring-emerald-500">
                <span class="text-sm text-gray-700">{{ $a->nama }}</span>
            </label>
            @endforeach
        </div>
    </div>
</div>
