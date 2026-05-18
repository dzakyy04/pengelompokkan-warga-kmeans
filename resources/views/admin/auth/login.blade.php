<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Sistem Data Warga Desa</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .input-base {
            transition: border-color 200ms ease, box-shadow 200ms ease;
        }
        .input-base:focus {
            outline: none;
            border-color: #059669;
            box-shadow: 0 0 0 3px rgba(5,150,105,0.12);
        }
        @media (prefers-reduced-motion: reduce) {
            * { transition: none !important; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col lg:flex-row bg-white dark:bg-slate-900 transition-colors duration-200">

    {{-- ── Left Panel (Brand & Illustration) ── --}}
    <div class="hidden lg:flex lg:w-[50%] xl:w-[55%] bg-emerald-50 dark:bg-slate-900 flex-col justify-between p-12 xl:p-16 min-h-screen relative overflow-hidden border-r border-gray-100 dark:border-slate-800">
        
        {{-- Logo --}}
        <div class="flex items-center gap-3 relative z-10">
            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-200 dark:shadow-none">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                    <polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <div>
                <p class="text-emerald-900 dark:text-white font-bold text-lg leading-none tracking-tight">Data Warga Desa</p>
                <p class="text-emerald-600 dark:text-emerald-200 text-sm mt-0.5 font-medium">Sistem Informasi</p>
            </div>
        </div>

        {{-- Illustration --}}
        <div class="flex-1 flex items-center justify-center relative z-10 w-full mt-8 mb-8">
            <style>
                @keyframes float {
                    0% { transform: translateY(0px); }
                    50% { transform: translateY(-10px); }
                    100% { transform: translateY(0px); }
                }
            </style>
            <div class="relative w-full max-w-lg flex justify-center">
                {{-- Decorative background shape --}}
                <div class="absolute inset-0 bg-white dark:bg-emerald-900/30 rounded-full blur-3xl opacity-70"></div>
                
                <img src="{{ asset('images/login-3d.png') }}" alt="Ilustrasi Perangkat Desa" class="relative z-10 w-4/5 h-auto object-contain drop-shadow-2xl" style="animation: float 6s ease-in-out infinite;">
            </div>
        </div>

        {{-- Main copy (bottom) --}}
        <div class="relative z-10 text-center max-w-md mx-auto mb-4">
            <h1 class="text-2xl xl:text-3xl font-bold text-emerald-950 dark:text-emerald-50 leading-tight mb-4">
                Bantu desa kenali warganya lebih baik.
            </h1>
            <p class="text-emerald-700/90 dark:text-emerald-200/80 text-sm leading-relaxed">
                Sistem ini membantu perangkat desa mengelompokkan warga berdasarkan kondisi kehidupan mereka untuk penyaluran bantuan yang tepat sasaran.
            </p>
        </div>

        {{-- Background decorations --}}
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-200/40 dark:bg-emerald-900/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-200/40 dark:bg-teal-900/20 rounded-full blur-3xl pointer-events-none"></div>

    </div>

    {{-- ── Right Panel (Form) ── --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12 lg:py-0 bg-white dark:bg-slate-900">
        <div class="w-full max-w-md px-4 sm:px-10">

            {{-- Mobile: brand header --}}
            <div class="flex items-center justify-center gap-2.5 mb-10 lg:hidden">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-md shadow-emerald-200 dark:shadow-none">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </div>
                <div>
                    <span class="text-gray-900 dark:text-white font-bold text-lg leading-none tracking-tight block">Data Warga Desa</span>
                    <span class="text-emerald-600 dark:text-emerald-200 text-xs font-medium block">Sistem Informasi</span>
                </div>
            </div>

            {{-- Heading --}}
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">Selamat datang</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-10">Silakan masuk untuk mengelola data warga desa Anda.</p>

            {{-- Error alert --}}
            @if(session('error'))
            <div class="flex items-center gap-2.5 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 text-sm rounded-xl px-4 py-3 mb-8">
                <svg class="w-5 h-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                <span>{{ session('error') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.login.submit') }}" id="loginForm" novalidate>
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Alamat Email
                    </label>
                    <div class="relative">
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            autocomplete="email"
                            placeholder="nama@email.com"
                            class="input-base w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-emerald-500 dark:focus:border-emerald-500"
                        >
                    </div>
                    @error('email')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                            Password
                        </label>
                    </div>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Masukkan password"
                            class="input-base w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 pr-12 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-emerald-500 dark:focus:border-emerald-500"
                        >
                        <button
                            type="button"
                            id="pwToggle"
                            aria-label="Tampilkan atau sembunyikan password"
                            class="absolute inset-y-0 right-0 px-4 flex items-center cursor-pointer text-gray-400 hover:text-emerald-600 transition-colors duration-200"
                        >
                            <svg id="iconEyeOn" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg id="iconEyeOff" class="w-5 h-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/>
                                <line x1="1" y1="1" x2="23" y2="23"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                    <p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center mb-8">
                    <input
                        type="checkbox"
                        id="remember"
                        name="remember"
                        class="w-4.5 h-4.5 cursor-pointer rounded border-gray-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-emerald-600 focus:ring-emerald-500 focus:ring-offset-0 dark:focus:ring-offset-slate-900 transition-colors"
                    >
                    <label for="remember" class="ml-3 text-sm font-medium text-gray-600 dark:text-gray-400 cursor-pointer select-none hover:text-gray-900 dark:hover:text-gray-200 transition-colors">
                        Ingat saya
                    </label>
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    id="submitBtn"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 dark:bg-emerald-600 dark:hover:bg-emerald-500 text-white font-bold text-sm py-3.5 rounded-xl cursor-pointer transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 shadow-sm shadow-emerald-200 dark:shadow-none active:scale-[0.98]"
                >
                    <span id="btnText">Masuk</span>
                </button>

            </form>

        </div>
    </div>

    <script>
        // Password toggle
        const pwInput   = document.getElementById('password');
        const iconOn    = document.getElementById('iconEyeOn');
        const iconOff   = document.getElementById('iconEyeOff');
        document.getElementById('pwToggle').addEventListener('click', function () {
            const isPassword = pwInput.type === 'password';
            pwInput.type = isPassword ? 'text' : 'password';
            iconOn.classList.toggle('hidden', isPassword);
            iconOff.classList.toggle('hidden', !isPassword);
        });

        // Submit loading state
        document.getElementById('loginForm').addEventListener('submit', function () {
            const btn  = document.getElementById('submitBtn');
            const text = document.getElementById('btnText');
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.innerHTML = `<svg class="w-5 h-5 animate-spin text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg><span class="ml-2">Memproses...</span>`;
        });
    </script>
</body>
</html>
