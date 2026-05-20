<div id="overlay" class="fixed inset-0 backdrop-brightness-50 backdrop-blur-md bg-opacity-50 z-20 lg:hidden hidden"></div>

<aside id="sidebar"
    class="fixed lg:static inset-y-0 left-0 z-30 sidebar-expanded bg-white dark:bg-slate-800 border-r border-gray-200 dark:border-slate-700 transform -translate-x-full lg:translate-x-0 transition-all duration-300 ease-in-out flex flex-col shadow-sm">
    <div class="flex items-center px-6 h-16 border-b border-gray-200 dark:border-slate-700 flex-shrink-0 transition-colors duration-200">
        <div class="flex items-center">
            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
            <h1 class="text-sm font-bold text-emerald-700 dark:text-emerald-400 sidebar-text ml-3">PENGELOMPOKAN<br>WARGA</h1>
        </div>
    </div>

    <div class="px-6 py-3 flex-shrink-0 sidebar-text">
        <p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Main Menu</p>
    </div>

    <nav class="flex-1 overflow-y-auto px-4 scrollbar-hide">
        @php $user = auth()->user(); @endphp

        {{-- Dashboard --}}
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-sm sidebar-text">Dashboard</span>
        </a>

        @if($user->isAdmin())
        {{-- Data Warga --}}
        <div class="px-3 pt-4 pb-1 sidebar-text"><p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Data Warga</p></div>
        <a href="{{ route('admin.warga.index') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.warga.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.warga.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            <span class="text-sm sidebar-text">Daftar Warga</span>
        </a>

        {{-- Master Data --}}
        <div class="px-3 pt-4 pb-1 sidebar-text"><p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Master Data</p></div>
        <a href="{{ route('admin.master-pendidikan.index') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.master-pendidikan.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.master-pendidikan.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
            </svg>
            <span class="text-sm sidebar-text">Pendidikan</span>
        </a>
        <a href="{{ route('admin.master-kondisi-rumah.index') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.master-kondisi-rumah.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.master-kondisi-rumah.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-sm sidebar-text">Kondisi Rumah</span>
        </a>
        <a href="{{ route('admin.master-bansos.index') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.master-bansos.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.master-bansos.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
            </svg>
            <span class="text-sm sidebar-text">Bansos</span>
        </a>
        @endif

        {{-- Clustering --}}
        <div class="px-3 pt-4 pb-1 sidebar-text"><p class="text-xs font-semibold text-gray-400 dark:text-gray-500 uppercase tracking-wider">Pengelompokan</p></div>
        @if($user->isAdmin())
        <a href="{{ route('admin.clustering.index') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.clustering.index') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.clustering.index') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm sidebar-text">Proses Warga</span>
        </a>
        @endif
        <a href="{{ route('admin.clustering.history') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.clustering.history') || request()->routeIs('admin.clustering.show') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.clustering.history') || request()->routeIs('admin.clustering.show') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
            </svg>
            <span class="text-sm sidebar-text">Riwayat Hasil</span>
        </a>
        @if($user->isKepalaDesa())
        @php $pendingValidationCount = \App\Models\WargaClassificationQueue::where('status', 'pending')->count(); @endphp
        <a href="{{ route('admin.clustering.pending-classifications') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.clustering.pending-classifications') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.clustering.pending-classifications') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="text-sm sidebar-text">Validasi Warga Baru</span>
            @if($pendingValidationCount > 0)
            <span class="ml-auto bg-rose-500 text-white text-xs font-bold px-2 py-0.5 rounded-full sidebar-text">{{ $pendingValidationCount }}</span>
            @endif
        </a>
        @endif
    </nav>

</aside>

