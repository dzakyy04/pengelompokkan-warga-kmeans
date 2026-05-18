<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Pengelompokan Warga</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50 font-[Inter]">
    <div class="flex h-screen overflow-hidden">
        @include('admin.partials.sidebar')

        <div class="flex-1 flex flex-col overflow-hidden">
            @include('admin.partials.header')

            <main class="flex-1 overflow-y-auto p-6 lg:p-8">
                {{-- Flash Messages --}}
                @if(session('success'))
                <div class="mb-4 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm font-medium">
                    {{ session('success') }}
                </div>
                @endif
                @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-medium">
                    {{ session('error') }}
                </div>
                @endif

                @yield('content')
            </main>

            @include('admin.partials.footer')
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.getElementById('toggleSidebar');
            const overlay = document.getElementById('overlay');

            function toggleSidebar() {
                sidebar.classList.toggle('sidebar-collapsed');
                sidebar.classList.toggle('sidebar-expanded');
                if (window.innerWidth < 1024) {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                }
            }

            if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if (overlay) overlay.addEventListener('click', toggleSidebar);
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 1024) overlay.classList.add('hidden');
            });
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
