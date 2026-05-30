<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Ulang Password — Sistem Data Warga Desa</title>
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
        .input-base { transition: border-color 200ms ease, box-shadow 200ms ease; }
        .input-base:focus { outline: none; border-color: #059669; box-shadow: 0 0 0 3px rgba(5,150,105,0.12); }
    </style>
</head>
<body class="min-h-screen flex flex-col lg:flex-row bg-white dark:bg-slate-900 transition-colors duration-200">

    {{-- ── Left Panel (Brand & Illustration) ── --}}
    <div class="hidden lg:flex lg:w-[50%] xl:w-[55%] bg-emerald-50 dark:bg-slate-900 flex-col justify-between p-12 xl:p-16 min-h-screen relative overflow-hidden border-r border-gray-100 dark:border-slate-800">
        
        {{-- Logo --}}
        <div class="flex items-center gap-3 relative z-10">
            <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-emerald-200 dark:shadow-none">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                </svg>
            </div>
            <div>
                <p class="text-emerald-900 dark:text-white font-bold text-lg leading-none tracking-tight">Data Warga Desa</p>
                <p class="text-emerald-600 dark:text-emerald-200 text-sm mt-0.5 font-medium">Sistem Informasi</p>
            </div>
        </div>

        {{-- Illustration --}}
        <div class="flex-1 flex items-center justify-center relative z-10 w-full mt-8 mb-8">
            <style>@keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }</style>
            <div class="relative w-full max-w-lg flex justify-center">
                <div class="absolute inset-0 bg-white dark:bg-emerald-900/30 rounded-full blur-3xl opacity-70"></div>
                <img src="{{ asset('images/login-3d.png') }}" alt="Ilustrasi" class="relative z-10 w-4/5 h-auto object-contain drop-shadow-2xl" style="animation: float 6s ease-in-out infinite;">
            </div>
        </div>

        {{-- Main copy (bottom) --}}
        <div class="relative z-10 text-center max-w-md mx-auto mb-4">
            <h1 class="text-2xl xl:text-3xl font-bold text-emerald-950 dark:text-emerald-50 leading-tight mb-4">Set Kata Sandi Baru</h1>
            <p class="text-emerald-700/90 dark:text-emerald-200/80 text-sm leading-relaxed">Buat kata sandi baru yang kuat dan aman untuk akun Anda.</p>
        </div>
        
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-200/40 dark:bg-emerald-900/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-200/40 dark:bg-teal-900/20 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    {{-- ── Right Panel (Form) ── --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12 lg:py-0 bg-white dark:bg-slate-900">
        <div class="w-full max-w-md px-4 sm:px-10">

            {{-- Mobile: brand header --}}
            <div class="flex items-center justify-center gap-2.5 mb-10 lg:hidden">
                <div class="w-10 h-10 bg-emerald-600 rounded-xl flex items-center justify-center shadow-md shadow-emerald-200 dark:shadow-none">
                    <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </div>
                <div>
                    <span class="text-gray-900 dark:text-white font-bold text-lg leading-none tracking-tight block">Data Warga Desa</span>
                    <span class="text-emerald-600 dark:text-emerald-200 text-xs font-medium block">Sistem Informasi</span>
                </div>
            </div>

            <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">Set Ulang Password</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">Silakan masukkan password baru Anda.</p>

            <form method="POST" action="{{ route('admin.password.update') }}" id="resetForm" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ $email ?? old('email') }}" required readonly class="input-base w-full bg-gray-50 dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-500 dark:text-gray-400 cursor-not-allowed">
                    @error('email')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Password Baru --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Password Baru</label>
                    <div class="relative">
                        <input type="password" id="password" name="password" required autofocus placeholder="Minimal 8 karakter" class="input-base w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:border-emerald-500 dark:focus:border-emerald-500">
                    </div>
                    @error('password')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="mb-8">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Konfirmasi Password Baru</label>
                    <div class="relative">
                        <input type="password" id="password_confirmation" name="password_confirmation" required placeholder="Ulangi password baru" class="input-base w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white focus:border-emerald-500 dark:focus:border-emerald-500">
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" id="submitBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm py-3.5 rounded-xl cursor-pointer transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 shadow-sm">
                    <span id="btnText">Simpan Password Baru</span>
                </button>
            </form>

        </div>
    </div>

    <script>
        document.getElementById('resetForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.innerHTML = `<svg class="w-5 h-5 animate-spin text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg><span class="ml-2">Memproses...</span>`;
        });
    </script>
</body>
</html>
