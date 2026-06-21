<!DOCTYPE html>
@php
    $themeClass = '';
    if (auth()->check()) {
        $themeClass = auth()->user()->theme === 'dark' ? 'dark' : '';
    } else {
        $themeClass = session()->get('theme') === 'dark' ? 'dark' : '';
    }
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $themeClass }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Hasanat Tracker - Admin Control Center</title>

        <!-- Inline Theme Script to prevent flash of light theme -->
        <script>
            (function () {
                function applyTheme() {
                    const isAuth = {{ auth()->check() ? 'true' : 'false' }};
                    const userTheme = '{{ auth()->check() ? auth()->user()->theme : '' }}';
                    const sessionTheme = '{{ session()->get('theme', '') }}';
                    const guestTheme = localStorage.getItem('guest_theme');
                    
                    let isDark = false;
                    if (isAuth) {
                        isDark = (userTheme === 'dark');
                    } else {
                        isDark = (guestTheme === 'dark' || (guestTheme === null && sessionTheme === 'dark'));
                    }
                    
                    if (isDark) {
                        document.documentElement.classList.add('dark');
                    } else {
                        document.documentElement.classList.remove('dark');
                    }
                }
                
                applyTheme();
                document.addEventListener('livewire:navigated', applyTheme);
            })();
        </script>

        <!-- Chart.js CDN -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&family=Amiri&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        <style>
            .font-arabic {
                font-family: 'Amiri', serif;
            }
            .font-outfit {
                font-family: 'Outfit', sans-serif;
            }
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>
    <body class="bg-slate-50 dark:bg-slate-950 text-slate-900 dark:text-slate-100 antialiased min-h-screen">
        <div class="min-h-screen flex flex-col">
            <!-- Header Bar -->
            <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-slate-200 dark:border-slate-800 shadow-sm">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="bg-gradient-to-br from-emerald-600 to-teal-700 p-2 rounded-xl shadow-md">
                            <img src="/icon-192.png" alt="Icon" class="w-7 h-7">
                        </div>
                        <div>
                            <span class="font-outfit font-extrabold text-lg text-slate-900 dark:text-white tracking-tight block">
                                Hasanat Control Center
                            </span>
                            <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold tracking-wider uppercase block -mt-1">
                                Secure Admin Dashboard
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Logged in as</p>
                            <p class="text-xs font-bold text-slate-800 dark:text-slate-200">{{ auth()->user()->email }}</p>
                        </div>
                        
                        <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-700 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                            </svg>
                            <span>Back to App</span>
                        </a>
                    </div>
                </div>
            </header>

            <!-- Main Layout (Fluid Container) -->
            <main class="flex-1 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                {{ $slot }}
            </main>
        </div>

        @livewireScripts
    </body>
</html>
