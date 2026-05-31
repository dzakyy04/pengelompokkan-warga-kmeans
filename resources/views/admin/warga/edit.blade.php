@extends('admin.layout.app')
@section('title', 'Edit Warga')
@section('page-title', 'Edit Warga')

@section('content')
<div class="max-w-4xl">
    <div class="mb-6">
        <a href="{{ route('admin.warga.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">&larr; Kembali ke Daftar Warga</a>
    </div>

    <form method="POST" action="{{ route('admin.warga.update', $warga) }}">
        @csrf @method('PUT')
        @include('admin.warga._form', ['warga' => $warga])
        <div class="flex gap-3 mt-6">
            <button type="submit" class="px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">Perbarui</button>
            <a href="{{ route('admin.warga.index') }}" class="px-6 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">Batal</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
@include('admin.warga._form_scripts')
</script>
@endpush
