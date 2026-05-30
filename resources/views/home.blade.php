<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem pengelompokan warga Desa Sungai Rebo berdasarkan kondisi ekonomi untuk penyaluran bantuan sosial tepat sasaran.">
    <title>Pengelompokan Warga — Desa Sungai Rebo</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="bg-zinc-50 dark:bg-slate-950 font-[Outfit] transition-colors duration-200 min-h-[100dvh] flex flex-col antialiased">

    {{-- ═══════════════════════════════════════════════════════════════════
         NAVBAR — Liquid Glass with inner refraction
    ═══════════════════════════════════════════════════════════════════ --}}
    <nav class="glass-refraction bg-white/70 dark:bg-slate-900/70 border-b border-zinc-200/60 dark:border-slate-800/60 sticky top-0 z-40">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-banyasin.png') }}" alt="Logo Desa Sungai Rebo" class="w-9 h-9 object-contain">
                    <span class="text-base font-semibold text-zinc-800 dark:text-zinc-100 tracking-tight">Desa Sungai Rebo</span>
                </div>
                <div class="flex items-center gap-2">
                    <button id="theme-toggle" onclick="toggleTheme()" class="p-2 rounded-xl text-zinc-500 dark:text-zinc-400 hover:bg-zinc-100 dark:hover:bg-slate-800 transition-all duration-200 btn-tactile" aria-label="Toggle dark mode">
                        <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z"/>
                        </svg>
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                        </svg>
                    </button>
                    <a href="{{ route('admin.login') }}" id="nav-login-btn" class="inline-flex items-center px-5 py-2 bg-emerald-600 dark:bg-emerald-600 text-white text-sm font-medium rounded-xl transition-all duration-200 hover:opacity-90 btn-tactile">
                        Masuk
                    </a>
                </div>
            </div>
        </div>
    </nav>

    {{-- ═══════════════════════════════════════════════════════════════════
         HERO — Asymmetric Split Screen (Left text / Right visual)
         DESIGN_VARIANCE: 8 → No centered layout
    ═══════════════════════════════════════════════════════════════════ --}}
    <section class="relative overflow-hidden min-h-[100dvh] flex items-center" id="hero">
        {{-- Background ambient blobs --}}
        <div class="absolute inset-0 bg-zinc-50 dark:bg-slate-950"></div>
        <div class="absolute inset-0 pointer-events-none select-none" aria-hidden="true">
            <div class="absolute top-[10%] right-[15%] w-[500px] h-[500px] bg-emerald-200/40 dark:bg-emerald-900/20 rounded-full blur-[120px] animate-subtle-pulse"></div>
            <div class="absolute bottom-[20%] left-[5%] w-[400px] h-[400px] bg-teal-200/30 dark:bg-teal-900/15 rounded-full blur-[100px] animate-subtle-pulse" style="animation-delay: 3s;"></div>
        </div>

        <div class="relative max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8 py-16 lg:py-0 w-full">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                {{-- Left: Content --}}
                <div class="max-w-xl">
                    <div class="animate-fade-up">
                        <span class="inline-flex items-center gap-2 px-3 py-1.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 rounded-lg text-xs font-medium tracking-wide uppercase border border-emerald-200/60 dark:border-emerald-800/40">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-[subtlePulse_2s_ease-in-out_infinite]"></span>
                            Pengelompokan Otomatis
                        </span>
                    </div>

                    <h1 class="mt-6 text-4xl md:text-5xl xl:text-6xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tighter leading-[1.08] animate-fade-up delay-100">
                        Kelompokkan Warga,
                        <br>
                        <span class="text-emerald-600 dark:text-emerald-400">Salurkan Tepat.</span>
                    </h1>

                    <p class="mt-6 text-base md:text-lg text-zinc-500 dark:text-zinc-400 leading-relaxed max-w-[52ch] animate-fade-up delay-200">
                        Sistem yang mengelompokkan warga berdasarkan kondisi ekonomi, sehingga penyaluran bantuan sosial lebih akurat dan terukur.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3 animate-fade-up delay-300">
                        <a href="{{ route('admin.login') }}" id="hero-login-btn" class="inline-flex items-center justify-center gap-2 px-7 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition-all duration-200 shadow-[0_4px_16px_rgba(5,150,105,0.25)] hover:shadow-[0_8px_24px_rgba(5,150,105,0.3)] btn-tactile">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                            </svg>
                            Masuk ke Sistem
                        </a>
                        <a href="#fitur" id="hero-learn-btn" class="inline-flex items-center justify-center gap-2 px-7 py-3 bg-white dark:bg-slate-900 text-zinc-700 dark:text-zinc-300 font-medium rounded-xl border border-zinc-200 dark:border-slate-700 hover:border-zinc-300 dark:hover:border-slate-600 transition-all duration-200 btn-tactile">
                            Pelajari Sistem
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 13.5L12 21m0 0l-7.5-7.5M12 21V3"/>
                            </svg>
                        </a>
                    </div>

                    {{-- Micro stats --}}
                    <div class="mt-12 flex items-center gap-8 animate-fade-up delay-400">
                        <div>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 tracking-tight font-[JetBrains_Mono]">3</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">Kelompok Ekonomi</p>
                        </div>
                        <div class="w-px h-8 bg-zinc-200 dark:bg-slate-700"></div>
                        <div>
                            <p class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 tracking-tight font-[JetBrains_Mono]">5</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">Parameter Analisis</p>
                        </div>
                        <div class="w-px h-8 bg-zinc-200 dark:bg-slate-700"></div>
                        <div>
                            <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400 tracking-tight font-[JetBrains_Mono]">100%</p>
                            <p class="text-xs text-zinc-400 dark:text-zinc-500 mt-0.5">Otomatis</p>
                        </div>
                    </div>
                </div>

                {{-- Right: Abstract clustering visualization --}}
                <div class="hidden lg:flex items-center justify-center animate-fade-right delay-300">
                    <div class="relative w-full max-w-lg aspect-square">
                        {{-- Cluster visualization using SVG --}}
                        <svg viewBox="0 0 400 400" class="w-full h-full" aria-hidden="true">
                            {{-- Grid dots background --}}
                            <defs>
                                <pattern id="grid-dots" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                    <circle cx="2" cy="2" r="1" class="fill-zinc-200 dark:fill-slate-800"/>
                                </pattern>
                            </defs>
                            <rect width="400" height="400" fill="url(#grid-dots)" rx="24"/>

                            {{-- Cluster 1: Emerald --}}
                            <circle cx="120" cy="140" r="48" class="fill-emerald-100/80 dark:fill-emerald-900/30 stroke-emerald-300 dark:stroke-emerald-700" stroke-width="1" stroke-dasharray="4 3"/>
                            <circle cx="105" cy="125" r="5" class="fill-emerald-500 animate-float"/>
                            <circle cx="130" cy="135" r="6" class="fill-emerald-600 animate-float" style="animation-delay: 0.5s"/>
                            <circle cx="115" cy="155" r="4" class="fill-emerald-400 animate-float" style="animation-delay: 1s"/>
                            <circle cx="140" cy="150" r="5" class="fill-emerald-500 animate-float" style="animation-delay: 1.5s"/>
                            <circle cx="100" cy="148" r="3.5" class="fill-emerald-500 animate-float" style="animation-delay: 0.8s"/>

                            {{-- Cluster 2: Rose / Deep Rose --}}
                            <circle cx="280" cy="120" r="52" class="fill-rose-100/60 dark:fill-rose-900/20 stroke-rose-300 dark:stroke-rose-800" stroke-width="1" stroke-dasharray="4 3"/>
                            <circle cx="265" cy="108" r="5" class="fill-rose-500 animate-float" style="animation-delay: 0.3s"/>
                            <circle cx="290" cy="118" r="6.5" class="fill-rose-600 animate-float" style="animation-delay: 0.7s"/>
                            <circle cx="275" cy="138" r="4.5" class="fill-rose-400 animate-float" style="animation-delay: 1.2s"/>
                            <circle cx="300" cy="130" r="4" class="fill-rose-500 animate-float" style="animation-delay: 0.2s"/>
                            <circle cx="260" cy="130" r="3.5" class="fill-rose-400 animate-float" style="animation-delay: 1.8s"/>

                            {{-- Cluster 3: Amber --}}
                            <circle cx="200" cy="290" r="56" class="fill-amber-100/60 dark:fill-amber-900/20 stroke-amber-300 dark:stroke-amber-800" stroke-width="1" stroke-dasharray="4 3"/>
                            <circle cx="185" cy="275" r="5.5" class="fill-amber-500 animate-float" style="animation-delay: 0.4s"/>
                            <circle cx="210" cy="285" r="6" class="fill-amber-600 animate-float" style="animation-delay: 0.9s"/>
                            <circle cx="195" cy="305" r="4" class="fill-amber-400 animate-float" style="animation-delay: 1.4s"/>
                            <circle cx="220" cy="298" r="5" class="fill-amber-500 animate-float" style="animation-delay: 0.6s"/>
                            <circle cx="180" cy="298" r="3.5" class="fill-amber-500 animate-float" style="animation-delay: 1.1s"/>

                            {{-- Connection lines (centroids) --}}
                            <line x1="120" y1="140" x2="280" y2="120" class="stroke-zinc-300 dark:stroke-slate-700" stroke-width="0.5" stroke-dasharray="6 4"/>
                            <line x1="120" y1="140" x2="200" y2="290" class="stroke-zinc-300 dark:stroke-slate-700" stroke-width="0.5" stroke-dasharray="6 4"/>
                            <line x1="280" y1="120" x2="200" y2="290" class="stroke-zinc-300 dark:stroke-slate-700" stroke-width="0.5" stroke-dasharray="6 4"/>

                            {{-- Centroid markers --}}
                            <circle cx="120" cy="140" r="3" class="fill-emerald-600 dark:fill-emerald-400"/>
                            <circle cx="280" cy="120" r="3" class="fill-rose-600 dark:fill-rose-400"/>
                            <circle cx="200" cy="290" r="3" class="fill-amber-600 dark:fill-amber-400"/>

                            {{-- Labels --}}
                            <text x="120" y="100" text-anchor="middle" class="fill-zinc-500 dark:fill-zinc-400 text-[10px] font-medium" font-family="Outfit">Mampu</text>
                            <text x="280" y="78" text-anchor="middle" class="fill-zinc-500 dark:fill-zinc-400 text-[10px] font-medium" font-family="Outfit">Kurang Mampu</text>
                            <text x="200" y="355" text-anchor="middle" class="fill-zinc-500 dark:fill-zinc-400 text-[10px] font-medium" font-family="Outfit">Menengah</text>
                        </svg>

                        {{-- Floating badge --}}
                        <div class="absolute top-8 right-4 bg-white dark:bg-slate-900 border border-zinc-200 dark:border-slate-700 rounded-xl px-4 py-3 shadow-diffusion animate-float" style="animation-delay: 2s;">
                            <p class="text-[10px] text-zinc-400 dark:text-zinc-500 uppercase tracking-wider font-medium">Status</p>
                            <p class="text-lg font-bold text-zinc-900 dark:text-zinc-100 font-[JetBrains_Mono] tracking-tight">Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         FEATURES — Asymmetric zig-zag layout (no 3-column equal cards)
    ═══════════════════════════════════════════════════════════════════ --}}
    <section id="fitur" class="py-24 lg:py-32 bg-white dark:bg-slate-900/50">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Section header: Left-aligned --}}
            <div class="max-w-xl mb-16 lg:mb-20 reveal-on-scroll">
                <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 tracking-wider uppercase">Kemampuan Sistem</span>
                <h2 class="mt-3 text-3xl md:text-4xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tighter leading-tight">
                    Dirancang untuk ketepatan penyaluran bantuan
                </h2>
                <p class="mt-4 text-base text-zinc-500 dark:text-zinc-400 leading-relaxed max-w-[55ch]">
                    Setiap fitur dibangun untuk memastikan proses pengelompokan berjalan akurat, transparan, dan mudah divalidasi.
                </p>
            </div>

            {{-- Feature grid: 2-column asymmetric --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
                {{-- Feature 1 — Large span --}}
                <div class="md:col-span-2 reveal-on-scroll">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 lg:gap-5">
                        <div class="lg:col-span-3 group rounded-2xl bg-zinc-50 dark:bg-slate-800/50 border border-zinc-100 dark:border-slate-800 p-8 lg:p-10 transition-all duration-300 hover:border-emerald-200 dark:hover:border-emerald-800/60">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center mb-6">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight mb-3">Pengelompokan Otomatis</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">
                                Sistem pengelompokan otomatis yang membagi warga ke dalam kelompok berdasarkan kesamaan karakteristik ekonomi untuk memastikan bantuan diterima oleh yang tepat sasaran.
                            </p>
                        </div>
                        <div class="lg:col-span-2 group rounded-2xl bg-zinc-50 dark:bg-slate-800/50 border border-zinc-100 dark:border-slate-800 p-8 lg:p-10 transition-all duration-300 hover:border-emerald-200 dark:hover:border-emerald-800/60">
                            <div class="w-10 h-10 rounded-xl bg-rose-500 flex items-center justify-center mb-6">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight mb-3">Manajemen Data Warga</h3>
                            <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">
                                Kelola data pendapatan, pendidikan, kondisi rumah, dan status bantuan sosial secara lengkap.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Feature 2 + 3 row --}}
                <div class="group rounded-2xl bg-zinc-50 dark:bg-slate-800/50 border border-zinc-100 dark:border-slate-800 p-8 lg:p-10 transition-all duration-300 hover:border-emerald-200 dark:hover:border-emerald-800/60 reveal-on-scroll">
                    <div class="w-10 h-10 rounded-xl bg-amber-500 flex items-center justify-center mb-6">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight mb-3">Visualisasi Hasil</h3>
                    <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">
                        Grafik dan chart interaktif menampilkan sebaran cluster, centroid, serta distribusi parameter tiap kelompok ekonomi warga.
                    </p>
                </div>

                <div class="group rounded-2xl bg-zinc-50 dark:bg-slate-800/50 border border-zinc-100 dark:border-slate-800 p-8 lg:p-10 transition-all duration-300 hover:border-emerald-200 dark:hover:border-emerald-800/60 reveal-on-scroll">
                    <div class="w-10 h-10 rounded-xl bg-zinc-800 dark:bg-zinc-200 flex items-center justify-center mb-6">
                        <svg class="w-5 h-5 text-white dark:text-zinc-800" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.745 3.745 0 011.043 3.296A3.745 3.745 0 0121 12z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight mb-3">Validasi Kepala Desa</h3>
                    <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">
                        Hasil pengelompokan memerlukan persetujuan Kepala Desa sebelum digunakan sebagai dasar penyaluran bantuan.
                    </p>
                </div>

                {{-- Feature 4 + 5 row: reversed asymmetry --}}
                <div class="md:col-span-2 reveal-on-scroll">
                    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 lg:gap-5">
                        <div class="lg:col-span-2 group rounded-2xl bg-zinc-50 dark:bg-slate-800/50 border border-zinc-100 dark:border-slate-800 p-8 lg:p-10 transition-all duration-300 hover:border-emerald-200 dark:hover:border-emerald-800/60">
                            <div class="w-10 h-10 rounded-xl bg-teal-600 flex items-center justify-center mb-6">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight mb-3">Export Laporan</h3>
                            <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed">
                                Unduh hasil dalam format PDF dan Excel untuk dokumentasi dan pelaporan resmi.
                            </p>
                        </div>
                        <div class="lg:col-span-3 group rounded-2xl bg-zinc-50 dark:bg-slate-800/50 border border-zinc-100 dark:border-slate-800 p-8 lg:p-10 transition-all duration-300 hover:border-emerald-200 dark:hover:border-emerald-800/60">
                            <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center mb-6">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6h9.75M10.5 6a1.5 1.5 0 11-3 0m3 0a1.5 1.5 0 10-3 0M3.75 6H7.5m3 12h9.75m-9.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-3.75 0H7.5m9-6h3.75m-3.75 0a1.5 1.5 0 01-3 0m3 0a1.5 1.5 0 00-3 0m-9.75 0h9.75"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tight mb-3">Klasifikasi Otomatis</h3>
                            <p class="text-zinc-500 dark:text-zinc-400 leading-relaxed max-w-[50ch]">
                                Warga baru yang ditambahkan akan otomatis dikelompokkan berdasarkan acuan yang sudah ada. Tidak perlu menjalankan ulang proses pengelompokan secara manual.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         HOW IT WORKS — Horizontal timeline with connecting lines
    ═══════════════════════════════════════════════════════════════════ --}}
    <section class="py-24 lg:py-32 bg-zinc-50 dark:bg-slate-950" id="cara-kerja">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-xl mb-16 lg:mb-20 reveal-on-scroll">
                <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400 tracking-wider uppercase">Alur Proses</span>
                <h2 class="mt-3 text-3xl md:text-4xl font-bold text-zinc-900 dark:text-zinc-50 tracking-tighter leading-tight">
                    Empat tahap menuju penyaluran yang akurat
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-6 reveal-on-scroll">
                {{-- Step 1 --}}
                <div class="relative group">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-zinc-900 dark:bg-zinc-100 flex items-center justify-center shrink-0">
                            <span class="text-sm font-bold text-white dark:text-zinc-900 font-[JetBrains_Mono]">01</span>
                        </div>
                        <div class="hidden lg:block flex-1 h-px bg-zinc-200 dark:bg-slate-800"></div>
                    </div>
                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-2 tracking-tight">Input Data Warga</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Admin memasukkan data warga beserta parameter ekonomi seperti pendapatan, pendidikan, dan kondisi rumah.</p>
                </div>

                {{-- Step 2 --}}
                <div class="relative group">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-zinc-900 dark:bg-zinc-100 flex items-center justify-center shrink-0">
                            <span class="text-sm font-bold text-white dark:text-zinc-900 font-[JetBrains_Mono]">02</span>
                        </div>
                        <div class="hidden lg:block flex-1 h-px bg-zinc-200 dark:bg-slate-800"></div>
                    </div>
                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-2 tracking-tight">Proses Pengelompokan</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Sistem menjalankan proses pengelompokan secara otomatis untuk mengelompokkan data warga berdasarkan kesamaan kondisi ekonomi.</p>
                </div>

                {{-- Step 3 --}}
                <div class="relative group">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-zinc-900 dark:bg-zinc-100 flex items-center justify-center shrink-0">
                            <span class="text-sm font-bold text-white dark:text-zinc-900 font-[JetBrains_Mono]">03</span>
                        </div>
                        <div class="hidden lg:block flex-1 h-px bg-zinc-200 dark:bg-slate-800"></div>
                    </div>
                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-2 tracking-tight">Validasi Hasil</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Kepala Desa meninjau dan memvalidasi hasil pengelompokan sebelum digunakan sebagai acuan.</p>
                </div>

                {{-- Step 4 --}}
                <div class="relative group">
                    <div class="flex items-center gap-4 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-emerald-600 flex items-center justify-center shrink-0">
                            <span class="text-sm font-bold text-white font-[JetBrains_Mono]">04</span>
                        </div>
                    </div>
                    <h3 class="font-semibold text-zinc-900 dark:text-zinc-100 mb-2 tracking-tight">Penyaluran Bantuan</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 leading-relaxed">Bantuan sosial disalurkan sesuai kelompok ekonomi warga yang telah tervalidasi.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         CTA — Clean, no over-designed gradients
    ═══════════════════════════════════════════════════════════════════ --}}
    <section class="py-24 lg:py-32 bg-white dark:bg-slate-900/50" id="cta">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="reveal-on-scroll">
                <div class="relative rounded-[2rem] bg-zinc-900 dark:bg-slate-800 p-12 lg:p-16 overflow-hidden">
                    {{-- Subtle background texture --}}
                    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" aria-hidden="true">
                        <svg width="100%" height="100%">
                            <defs>
                                <pattern id="cta-grid" x="0" y="0" width="32" height="32" patternUnits="userSpaceOnUse">
                                    <circle cx="1" cy="1" r="1" fill="white"/>
                                </pattern>
                            </defs>
                            <rect width="100%" height="100%" fill="url(#cta-grid)"/>
                        </svg>
                    </div>

                    <div class="relative grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
                        <div>
                            <h2 class="text-3xl md:text-4xl font-bold text-white tracking-tighter leading-tight">
                                Siap mengelola data warga desa Anda?
                            </h2>
                            <p class="mt-4 text-zinc-400 leading-relaxed max-w-[48ch]">
                                Masuk ke sistem untuk mulai menjalankan proses pengelompokan dan menghasilkan laporan yang akurat.
                            </p>
                        </div>
                        <div class="flex lg:justify-end">
                            <a href="{{ route('admin.login') }}" id="cta-login-btn" class="inline-flex items-center gap-2 px-8 py-4 bg-white text-zinc-900 font-semibold rounded-xl hover:bg-zinc-100 transition-all duration-200 btn-tactile">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                                </svg>
                                Masuk Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════════
         FOOTER — Clean, minimal
    ═══════════════════════════════════════════════════════════════════ --}}
    <footer class="border-t border-zinc-200 dark:border-slate-800 py-8 mt-auto bg-zinc-50 dark:bg-slate-950">
        <div class="max-w-[1400px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-banyasin.png') }}" alt="Logo Desa Sungai Rebo" class="w-7 h-7 object-contain">
                    <span class="text-sm font-medium text-zinc-600 dark:text-zinc-400">Desa Sungai Rebo</span>
                </div>
                <p class="text-sm text-zinc-400 dark:text-zinc-500">&copy; {{ date('Y') }} Sistem Pengelompokan Warga Desa Sungai Rebo</p>
            </div>
        </div>
    </footer>

    <script>
        // Theme toggle
        function toggleTheme() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }

        // Intersection Observer for scroll reveals
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver(
                (entries) => {
                    entries.forEach((entry) => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('revealed');
                            observer.unobserve(entry.target);
                        }
                    });
                },
                { threshold: 0.15, rootMargin: '0px 0px -40px 0px' }
            );

            document.querySelectorAll('.reveal-on-scroll').forEach((el) => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>
