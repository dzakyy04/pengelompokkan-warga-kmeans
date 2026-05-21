<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Pengelompokan Warga</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.8/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
    <style>
        /* DataTables custom styling */
        .dataTables_wrapper .dataTables_length select,
        .dataTables_wrapper .dataTables_filter input {
            padding: 0.5rem 1rem;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            background-color: white;
            outline: none;
            transition: all 0.2s;
        }
        .dark .dataTables_wrapper .dataTables_length select,
        .dark .dataTables_wrapper .dataTables_filter input {
            background-color: #0f172a;
            border-color: #475569;
            color: #e2e8f0;
        }
        .dataTables_wrapper .dataTables_length select:focus,
        .dataTables_wrapper .dataTables_filter input:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
        }
        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_info {
            font-size: 0.875rem;
            color: #6b7280;
        }
        .dark .dataTables_wrapper .dataTables_filter label,
        .dark .dataTables_wrapper .dataTables_length label,
        .dark .dataTables_wrapper .dataTables_info {
            color: #9ca3af;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button {
            padding: 0.4rem 0.8rem;
            margin: 0 0.15rem;
            border-radius: 0.5rem;
            font-size: 0.8rem;
            font-weight: 500;
            border: 1px solid #e5e7eb !important;
            background: white !important;
            color: #374151 !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-color: #475569 !important;
            background: #1e293b !important;
            color: #e2e8f0 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #10b981 !important;
            border-color: #10b981 !important;
            color: white !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #f0fdf4 !important;
            border-color: #10b981 !important;
            color: #10b981 !important;
        }
        .dark .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
            background: #064e3b !important;
            color: #6ee7b7 !important;
        }
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }
        /* Layout: length di kiri, filter/search di kanan */
        .dataTables_wrapper .dataTables_length {
            float: left;
            padding: 1rem 1.5rem;
        }
        .dataTables_wrapper .dataTables_filter {
            float: right;
            padding: 1rem 1.5rem;
        }
        .dataTables_wrapper .dataTables_info {
            float: left;
            padding: 1rem 1.5rem;
        }
        .dataTables_wrapper .dataTables_paginate {
            float: right;
            padding: 1rem 1.5rem;
        }
        .dataTables_wrapper::after {
            content: "";
            display: table;
            clear: both;
        }
        table.dataTable thead th {
            border-bottom: none !important;
        }
        table.dataTable.no-footer {
            border-bottom: none !important;
        }
        /* Prevent DataTables from overflowing the container */
        .dataTables_wrapper {
            overflow: hidden;
            width: 100% !important;
        }
        div.dataTables_scrollBody {
            overflow-x: auto !important;
        }
    </style>
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    @stack('styles')
</head>
<body class="bg-gray-50 dark:bg-slate-900 font-[Inter] transition-colors duration-200">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden min-w-0">
            @include('admin.partials.header')

            <main class="flex-1 overflow-y-auto flex flex-col">
                <div class="flex-1 p-6 lg:p-8">
                    {{-- Flash Messages --}}
                    @if(session('success'))
                    <div class="mb-4 p-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl text-emerald-700 dark:text-emerald-400 text-sm font-medium">
                        {{ session('success') }}
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="mb-4 p-4 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 rounded-xl text-red-700 dark:text-red-400 text-sm font-medium">
                        {{ session('error') }}
                    </div>
                    @endif

                    @yield('content')
                </div>

                @include('admin.partials.footer')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const overlay = document.getElementById('overlay');

            function toggleSidebar() {
                if (window.innerWidth < 1024) {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                    // Always ensure it is expanded when sliding in on mobile
                    sidebar.classList.add('sidebar-expanded');
                    sidebar.classList.remove('sidebar-collapsed');
                } else {
                    sidebar.classList.toggle('sidebar-collapsed');
                    sidebar.classList.toggle('sidebar-expanded');
                }
            }

            if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) overlay.classList.add('hidden');
            });

            // Profile Dropdown logic
            const profileBtn = document.getElementById('profileBtn');
            const profileDropdown = document.getElementById('profileDropdown');
            const profileChevron = document.getElementById('profileChevron');

            if (profileBtn && profileDropdown) {
                profileBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isHidden = profileDropdown.classList.toggle('hidden');
                    if (profileChevron) {
                        profileChevron.style.transform = isHidden ? '' : 'rotate(180deg)';
                    }
                });

                document.addEventListener('click', function(event) {
                    if (!profileBtn.contains(event.target) && !profileDropdown.contains(event.target)) {
                        profileDropdown.classList.add('hidden');
                        if (profileChevron) profileChevron.style.transform = '';
                    }
                });
            }

            window.toggleTheme = function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            };
        });
    </script>

    <style>
        .sidebar-expanded { width: 18rem; }
        .sidebar-collapsed { width: 5rem; }
        .sidebar-collapsed .sidebar-text { display: none !important; }
        .sidebar-collapsed .flex.items-center.px-6 { justify-content: center; padding-left: 1rem; padding-right: 1rem; }
        .sidebar-collapsed nav a, .sidebar-collapsed nav button { justify-content: center; padding-left: 0.75rem; padding-right: 0.75rem; }
        .sidebar-collapsed nav a svg, .sidebar-collapsed nav button svg { margin-right: 0; }
        @media (max-width: 1023px) {
            #sidebar { transform: translateX(-100%); }
            #sidebar:not(.-translate-x-full) { transform: translateX(0); }
        }
        @media (min-width: 1024px) { #sidebar { transform: translateX(0) !important; } }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        #sidebar * { transition: all 0.3s ease-in-out; }
    </style>

    @stack('scripts')
</body>
</html>
