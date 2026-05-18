@extends('admin.layout.app')
@section('title', 'Tambah Aset')
@section('page-title', 'Tambah Aset')
@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.master-aset.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">&larr; Kembali</a>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mt-4">
        <form method="POST" action="{{ route('admin.master-aset.store') }}">@csrf
            <div class="mb-4"><label class="block text-sm font-semibold text-gray-700 mb-1">Nama Aset <span class="text-red-500">*</span></label>
            <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none">@error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <div class="mb-4"><label class="block text-sm font-semibold text-gray-700 mb-1">Estimasi Nilai (Rp) <span class="text-red-500">*</span></label>
            <input type="number" name="estimasi_nilai" value="{{ old('estimasi_nilai') }}" required min="0" class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none">@error('estimasi_nilai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition">Simpan</button>
        </form>
    </div>
</div>
@endsection
