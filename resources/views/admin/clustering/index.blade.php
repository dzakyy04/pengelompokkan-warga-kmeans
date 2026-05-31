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
                <strong>Perhatian:</strong> Proses ulang akan menghasilkan pengelompokan baru yang perlu diverifikasi oleh Kepala Desa sebelum diaktifkan sebagai acuan.
            </p>
        </div>
        <form method="POST" action="{{ route('admin.clustering.process') }}">
            @csrf
            <button type="submit" class="w-full px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-xl transition shadow-sm" onclick="if(confirm('Apakah Anda yakin ingin memproses ulang pengelompokan? Acuan pengelompokan saat ini akan digantikan.')){this.disabled=true;this.innerText='Sedang memproses...';this.form.submit();}else{return false;}">
                Proses Ulang Pengelompokan
            </button>
        </form>
    </div>
    @endif
    </div>

    @else
    {{-- Belum ada acuan — tampilkan form pertama kali atau status menunggu verifikasi --}}
    @if(isset($pendingVerificationSession) && $pendingVerificationSession)
    {{-- Ada session yang menunggu verifikasi kades --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Card status menunggu --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-amber-200 dark:border-amber-800/50 p-6 transition-colors duration-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Menunggu Verifikasi Kepala Desa</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Diproses pada {{ $pendingVerificationSession->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>

            <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800/50 rounded-xl p-4 mb-4">
                <p class="text-sm text-amber-700 dark:text-amber-300">
                    Pengelompokan telah selesai diproses. <strong>{{ $pendingVerificationCount }} data warga</strong> menunggu verifikasi oleh Kepala Desa sebelum acuan pengelompokan diaktifkan.
                </p>
            </div>

            @if(auth()->user()->isKepalaDesa())
            <a href="{{ route('admin.clustering.pending-classifications') }}" class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl transition shadow-sm">
                Verifikasi {{ $pendingVerificationCount }} Data Warga
            </a>
            @else
            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                <svg class="w-5 h-5 text-gray-400 dark:text-gray-500 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                <p class="text-sm text-gray-500 dark:text-gray-400">Menunggu Kepala Desa memverifikasi data...</p>
            </div>
            @endif
        </div>

        {{-- Card progress --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Detail Proses</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Informasi pengelompokan terakhir</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Total Warga Diproses</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $pendingVerificationCount }} orang</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Jumlah Kelompok</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $pendingVerificationSession->jumlah_cluster }} kelompok</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Menunggu Verifikasi</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Metode</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">K-Means Clustering</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="text-sm text-gray-500 dark:text-gray-400">Normalisasi</span>
                    <span class="text-sm font-bold text-gray-900 dark:text-white">Min-Max (0–1)</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Alur proses --}}
    <div class="mt-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
        <h3 class="font-bold text-gray-900 dark:text-white mb-4">Alur Proses Pengelompokan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-200 dark:border-emerald-800/30">
                <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center mx-auto mb-2">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                <p class="text-sm font-semibold text-emerald-700 dark:text-emerald-400">Admin Memproses</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Selesai ✓</p>
            </div>
            <div class="text-center p-4 bg-amber-50 dark:bg-amber-900/10 rounded-xl border-2 border-amber-300 dark:border-amber-700">
                <div class="w-10 h-10 bg-amber-500 rounded-full flex items-center justify-center mx-auto mb-2 animate-pulse">
                    <span class="text-sm font-bold text-white">2</span>
                </div>
                <p class="text-sm font-semibold text-amber-700 dark:text-amber-400">Kades Verifikasi</p>
                <p class="text-xs text-amber-600 dark:text-amber-400 mt-1 font-medium">Sedang berlangsung...</p>
            </div>
            <div class="text-center p-4 bg-gray-50 dark:bg-slate-700/30 rounded-xl border border-gray-200 dark:border-slate-600">
                <div class="w-10 h-10 bg-gray-300 dark:bg-slate-600 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="text-sm font-bold text-white">3</span>
                </div>
                <p class="text-sm font-semibold text-gray-400 dark:text-gray-500">Acuan Aktif</p>
                <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Menunggu</p>
            </div>
        </div>
    </div>
    @else
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Card utama: Mulai Pengelompokan --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
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
                    <strong class="dark:text-blue-200">Cara kerja:</strong> Sistem mengelompokkan warga secara otomatis berdasarkan 5 parameter ekonomi. Hasilnya perlu diverifikasi oleh Kepala Desa sebelum diaktifkan.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.clustering.process') }}">
                @csrf
                <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-emerald-600 to-emerald-700 hover:from-emerald-700 hover:to-emerald-800 text-white font-semibold rounded-xl transition shadow-lg hover:shadow-xl" onclick="this.disabled=true;this.innerText='Sedang memproses...';this.form.submit();">
                    Mulai Pengelompokan Warga
                </button>
            </form>
        </div>

        {{-- Card info: Parameter yang digunakan --}}
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900 dark:text-white">Parameter Pengelompokan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">5 parameter yang digunakan untuk pengelompokan</p>
                </div>
            </div>
            <div class="space-y-3">
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="w-7 h-7 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center text-xs font-bold text-emerald-700 dark:text-emerald-400">1</span>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Pekerjaan & Status Produktivitas</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="w-7 h-7 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center text-xs font-bold text-emerald-700 dark:text-emerald-400">2</span>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Jumlah Tanggungan</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="w-7 h-7 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center text-xs font-bold text-emerald-700 dark:text-emerald-400">3</span>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Pendidikan Kepala Keluarga</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="w-7 h-7 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center text-xs font-bold text-emerald-700 dark:text-emerald-400">4</span>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Kondisi Rumah</span>
                </div>
                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-slate-700/50 rounded-xl">
                    <span class="w-7 h-7 bg-emerald-100 dark:bg-emerald-900/30 rounded-lg flex items-center justify-center text-xs font-bold text-emerald-700 dark:text-emerald-400">5</span>
                    <span class="text-sm text-gray-700 dark:text-gray-300">Bantuan Sosial yang Diterima</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Info alur proses --}}
    <div class="mt-6 bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-gray-200 dark:border-slate-700 p-6 transition-colors duration-200">
        <h3 class="font-bold text-gray-900 dark:text-white mb-4">Alur Proses Pengelompokan</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="text-center p-4 bg-emerald-50 dark:bg-emerald-900/10 rounded-xl border border-emerald-100 dark:border-emerald-800/30">
                <div class="w-10 h-10 bg-emerald-100 dark:bg-emerald-900/30 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="text-sm font-bold text-emerald-700 dark:text-emerald-400">1</span>
                </div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Admin Memproses</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Jalankan pengelompokan pada data warga</p>
            </div>
            <div class="text-center p-4 bg-amber-50 dark:bg-amber-900/10 rounded-xl border border-amber-100 dark:border-amber-800/30">
                <div class="w-10 h-10 bg-amber-100 dark:bg-amber-900/30 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="text-sm font-bold text-amber-700 dark:text-amber-400">2</span>
                </div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Kades Verifikasi</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Kepala Desa meninjau setiap data warga</p>
            </div>
            <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/10 rounded-xl border border-blue-100 dark:border-blue-800/30">
                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center mx-auto mb-2">
                    <span class="text-sm font-bold text-blue-700 dark:text-blue-400">3</span>
                </div>
                <p class="text-sm font-semibold text-gray-900 dark:text-white">Acuan Aktif</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Warga baru otomatis terkelompokkan</p>
            </div>
        </div>
    </div>
    @endif
    @endif
</div>
@endsection
