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
        <a href="{{ route('admin.master-pekerjaan.index') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.master-pekerjaan.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.master-pekerjaan.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span class="text-sm sidebar-text">Pekerjaan</span>
        </a>
        <a href="{{ route('admin.master-kondisi-rumah.index') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.master-kondisi-rumah.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.master-kondisi-rumah.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="text-sm sidebar-text">Kondisi Rumah</span>
        </a>
        <a href="{{ route('admin.master-aset.index') }}"
            class="flex items-center px-3 py-2.5 mb-1 rounded-lg hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors duration-200 group {{ request()->routeIs('admin.master-aset.*') ? 'bg-emerald-50 dark:bg-emerald-900/30 text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
            <svg class="w-5 h-5 mr-3 flex-shrink-0 {{ request()->routeIs('admin.master-aset.*') ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-gray-600 dark:group-hover:text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            <span class="text-sm sidebar-text">Aset</span>
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
    </nav>

    {{-- User Profile + Dropdown --}}
    <div id="profileSection" class="flex-shrink-0 relative border-t border-gray-200 dark:border-slate-700 bg-white dark:bg-slate-800 transition-colors duration-200">

        {{-- Dropdown Popup (appears above) --}}
        <div id="profileDropdown" class="hidden absolute z-50 bottom-full left-2 right-2 mb-1 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 overflow-hidden">
            {{-- User info header --}}
            <div class="px-4 py-3 border-b border-gray-100 dark:border-slate-700">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ auth()->user()->email ?? ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
            </div>

            {{-- Menu items --}}
            <div class="py-1">
                <button type="button" id="themeBtn" onclick="toggleTheme(); document.getElementById('profileDropdown').classList.add('hidden');"
                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-slate-700/60 transition-colors">
                    {{-- Moon icon (shown in light mode) --}}
                    <svg class="w-4 h-4 text-gray-400 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    {{-- Sun icon (shown in dark mode) --}}
                    <svg class="w-4 h-4 text-gray-400 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="dark:hidden">Mode Gelap</span>
                    <span class="hidden dark:inline">Mode Terang</span>
                </button>
            </div>

            {{-- Divider + Logout --}}
            <div class="border-t border-gray-100 dark:border-slate-700 py-1">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        Keluar
                    </button>
                </form>
            </div>
        </div>

        {{-- Profile trigger button --}}
        <button id="profileBtn" type="button"
            class="w-full px-4 py-3.5 flex items-center gap-3 focus:outline-none group hover:bg-gray-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-600 to-green-700 flex items-center justify-center ring-2 ring-white dark:ring-slate-700 flex-shrink-0 group-hover:ring-emerald-200 dark:group-hover:ring-emerald-900/60 transition-all">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div class="sidebar-text flex-1 min-w-0 text-left">
                <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
            </div>
            <svg class="w-4 h-4 text-gray-400 sidebar-text flex-shrink-0 transition-transform duration-200" id="profileChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
            </svg>
        </button>
    </div>
</aside>

