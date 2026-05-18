@extends('admin.layout.app')
@section('title', 'Edit Pekerjaan')
@section('page-title', 'Edit Pekerjaan')
@section('content')
<div class="max-w-lg">
    <a href="{{ route('admin.master-pekerjaan.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">&larr; Kembali</a>
    <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-6 mt-4">
        <form method="POST" action="{{ route('admin.master-pekerjaan.update', $item) }}">@csrf @method('PUT')
            <div class="mb-4"><label class="block text-sm font-semibold text-gray-700 mb-1">Nama Pekerjaan <span class="text-red-500">*</span></label>
            <input type="text" name="nama" value="{{ old('nama', $item->nama) }}" required class="w-full px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-emerald-500 outline-none">@error('nama')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror</div>
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition">Perbarui</button>
        </form>
    </div>
</div>
@endsection
