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
            <span class="text-sm text-gray-500 dark:text-gray-400 hidden md:block">{{ now()->format('d M Y') }}</span>
        </div>
    </div>
</header>
