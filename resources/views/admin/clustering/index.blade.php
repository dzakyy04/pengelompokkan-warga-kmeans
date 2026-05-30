@extends('admin.layout.app')
@section('title', 'Pengelompokan Warga')
@section('page-title', 'Pengelompokan Warga')

@section('content')
<div class="w-full">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Pengelompokan Warga</h1>
        <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">Kelompokkan warga berdasarkan kondisi ekonomi secara otomatis</p>
    </div>

    @if($activeSession)
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Sudah ada acuan aktif --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-emerald-200 dark:border-emerald-800/50 p-6 transition-colors duration-200">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Pengelompokan Aktif</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Diproses pada {{ $activeSession->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
        <div class="bg-emerald-50 dark:bg-emerald-900/20 border border-emerald-200 dark:border-emerald-800/50 rounded-xl p-4 mb-4">
            <p class="text-sm text-emerald-700 dark:text-emerald-300">
                Warga baru yang ditambahkan akan <strong>otomatis terkelompokkan</strong> berdasarkan acuan ini. Kepala Desa dapat memverifikasi hasilnya di menu <strong>Verifikasi Warga Baru</strong>.
            </p>
        </div>
        <div class="flex flex-col gap-3">
            <a href="{{ route('admin.clustering.show', $activeSession->id) }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                Lihat Hasil Pengelompokan
            </a>
            @php $pendingCount = \App\Models\WargaClassificationQueue::where('status', 'pending')->count(); @endphp
            @if($pendingCount > 0 && auth()->user()->isKepalaDesa())
            <a href="{{ route('admin.clustering.pending-classifications') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                {{ $pendingCount }} Warga Menunggu Verifikasi
            </a>
            @endif
        </div>
    </div>

    {{-- Proses ulang (opsional) --}}
    @if(auth()->user()->isAdmin())
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-gray-100 dark:bg-slate-700 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Proses Ulang Pengelompokan</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">Gunakan jika data warga berubah secara signifikan</p>
            </div>
        </div>
        <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-xl p-4 mb-4">
            <p class="text-sm text-amber-700 dark:text-amber-300">
                <strong>Perhatian:</strong> Proses ulang akan mengganti acuan pengelompokan saat ini. Semua warga akan dikelompokkan ulang berdasarkan data terbaru.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.clustering.process') }}">
            @csrf
            <button type="submit" class="w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl transition shadow-sm" onclick="return confirm('Apakah Anda yakin ingin memproses ulang pengelompokan? Acuan pengelompokan saat ini akan digantikan.') && (this.disabled=true, this.innerText='Sedang memproses...', true)">
                Proses Ulang Pengelompokan
            </button>
        </form>
    </div>
    @endif
    </div>

    @else
    {{-- Belum ada acuan — tampilkan form pertama kali --}}
    <div class="max-w-3xl">
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 mb-6 transition-colors duration-200">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h3 class="font-bold text-gray-900 dark:text-white">Siap Memproses</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $totalWarga }} warga tersedia untuk dikelompokkan</p>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800/50 rounded-xl p-4 mb-4">
            <p class="text-sm text-blue-700 dark:text-blue-300">
                <strong class="dark:text-blue-200">Cara kerja:</strong> Sistem akan mengelompokkan warga secara otomatis berdasarkan pekerjaan, jumlah tanggungan, pendidikan kepala keluarga, kondisi rumah, dan bantuan sosial yang diterima. Hasilnya akan langsung menjadi acuan untuk mengelompokkan warga baru yang ditambahkan di kemudian hari.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.clustering.process') }}">
            @csrf
            <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl" onclick="this.disabled=true;this.innerText='Sedang memproses...';this.form.submit();">
                Mulai Pengelompokan Warga
            </button>
        </form>
        </div>
    </div>
    @endif
</div>
@endsection
