<header class="bg-white dark:bg-slate-800 border-b border-gray-200 dark:border-slate-700 h-16 flex items-center px-6 sticky top-0 z-10 transition-colors duration-200">
    <div class="flex items-center justify-between w-full">
        <div class="flex items-center space-x-4">
            <button id="toggleSidebar" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white focus:outline-none p-2 hover:bg-gray-100 dark:hover:bg-slate-700 rounded-lg transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <h2 class="text-xl font-bold text-gray-800 dark:text-white">@yield('page-title', 'Dashboard')</h2>
            </div>
        </div>
        <div class="flex items-center space-x-3">
            {{-- User Profile + Dropdown --}}
            <div id="profileSection" class="relative">
                {{-- Profile trigger button --}}
                <button id="profileBtn" type="button" class="flex items-center gap-2 focus:outline-none group hover:bg-gray-50 dark:hover:bg-slate-700/50 px-2 py-1.5 rounded-xl transition-colors cursor-pointer">
                    <div class="hidden md:block text-right min-w-0 mr-1">
                        <p class="text-sm font-semibold text-gray-800 dark:text-gray-200 truncate leading-none">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate mt-1">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                    </div>
                    <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-600 to-green-700 flex items-center justify-center ring-2 ring-white dark:ring-slate-800 flex-shrink-0 group-hover:ring-emerald-200 dark:group-hover:ring-emerald-900/60 transition-all">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <svg class="w-4 h-4 text-gray-400 flex-shrink-0 transition-transform duration-200" id="profileChevron" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown Popup (appears below) --}}
                <div id="profileDropdown" class="hidden absolute z-50 top-full right-0 mt-2 w-62 bg-white dark:bg-slate-800 rounded-xl shadow-2xl border border-gray-200 dark:border-slate-700 overflow-hidden">
                    {{-- User info header (mobile only) --}}
                    <div class="md:hidden px-4 py-3 border-b border-gray-100 dark:border-slate-700">
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
            </div>
        </div>
    </div>
</header>
