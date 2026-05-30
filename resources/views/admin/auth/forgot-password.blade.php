<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Desa Sungai Rebo</title>
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
            <img src="{{ asset('images/logo-banyasin.png') }}" alt="Logo Desa Sungai Rebo" class="w-12 h-12 object-contain flex-shrink-0">
            <div>
                <p class="text-emerald-900 dark:text-white font-bold text-lg leading-none tracking-tight">Desa Sungai Rebo</p>
                <p class="text-emerald-600 dark:text-emerald-200 text-sm mt-0.5 font-medium">Sistem Pengelompokan Warga</p>
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
            <h1 class="text-2xl xl:text-3xl font-bold text-emerald-950 dark:text-emerald-50 leading-tight mb-4">Lupa Password?</h1>
            <p class="text-emerald-700/90 dark:text-emerald-200/80 text-sm leading-relaxed">Jangan khawatir, masukkan email Anda dan kami akan mengirimkan tautan untuk mereset kata sandi Anda.</p>
        </div>
        
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-96 h-96 bg-emerald-200/40 dark:bg-emerald-900/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-80 h-80 bg-teal-200/40 dark:bg-teal-900/20 rounded-full blur-3xl pointer-events-none"></div>
    </div>

    {{-- ── Right Panel (Form) ── --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12 lg:py-0 bg-white dark:bg-slate-900">
        <div class="w-full max-w-md px-4 sm:px-10">

            {{-- Mobile: brand header --}}
            <div class="flex items-center justify-center gap-2.5 mb-10 lg:hidden">
                <img src="{{ asset('images/logo-banyasin.png') }}" alt="Logo Desa Sungai Rebo" class="w-10 h-10 object-contain">
                <div>
                    <span class="text-gray-900 dark:text-white font-bold text-lg leading-none tracking-tight block">Desa Sungai Rebo</span>
                    <span class="text-emerald-600 dark:text-emerald-200 text-xs font-medium block">Sistem Pengelompokan Warga</span>
                </div>
            </div>

            <a href="{{ route('admin.login') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-emerald-600 dark:text-gray-400 dark:hover:text-emerald-400 mb-6 transition-colors">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali ke Login
            </a>

            <h2 class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight mb-2">Reset Password</h2>
            <p class="text-gray-500 dark:text-gray-400 text-sm mb-8">Masukkan alamat email yang terdaftar untuk menerima link reset password.</p>

            {{-- Alerts --}}
            @if(session('status') || session('success'))
            <div class="flex gap-2.5 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-400 text-sm rounded-xl px-4 py-3 mb-8">
                <svg class="w-5 h-5 flex-shrink-0 mt-0.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <span>{{ session('status') ?? session('success') }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.password.email') }}" id="resetForm" novalidate>
                @csrf

                {{-- Email --}}
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nama@email.com" class="input-base w-full bg-white dark:bg-slate-800 border border-gray-300 dark:border-slate-700 rounded-xl px-4 py-3 text-sm text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-emerald-500 dark:focus:border-emerald-500">
                    @error('email')<p class="text-red-500 text-xs mt-1.5 font-medium">{{ $message }}</p>@enderror
                </div>

                {{-- Submit --}}
                <button type="submit" id="submitBtn" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm py-3.5 rounded-xl cursor-pointer transition-all duration-200 flex items-center justify-center gap-2 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900 shadow-sm">
                    <span id="btnText">Kirim Link Reset Password</span>
                </button>
            </form>

        </div>
    </div>

    <script>
        document.getElementById('resetForm').addEventListener('submit', function () {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.classList.add('opacity-70', 'cursor-not-allowed');
            btn.innerHTML = `<svg class="w-5 h-5 animate-spin text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 12a9 9 0 1 1-6.219-8.56"/></svg><span class="ml-2">Mengirim...</span>`;
        });
    </script>
</body>
</html>
