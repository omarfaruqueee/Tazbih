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
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ __('messages.app_name') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Amiri:ital@0;1&family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Livewire Styles -->
        @livewireStyles

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

        <!-- Chart.js CDN (Loaded globally in head for wire:navigate compatibility) -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <style>
            .font-arabic {
                font-family: 'Amiri', serif;
            }
            .font-outfit {
                font-family: 'Outfit', sans-serif;
            }
            body {
                font-family: 'Inter', sans-serif;
                -webkit-tap-highlight-color: transparent;
            }
        </style>
    </head>
    <body class="bg-gradient-to-br from-emerald-950 via-slate-900 to-teal-950 text-gray-100 antialiased min-h-screen flex items-center justify-center p-4">
        
        <div class="w-full max-w-md mx-auto">
            <!-- App Logo & Branding -->
            <div class="flex flex-col items-center mb-6 text-center">
                <a href="/" wire:navigate class="inline-block relative">
                    <!-- Subtle glowing background for the logo -->
                    <div class="absolute inset-0 bg-amber-400/20 rounded-2xl blur-xl transition-all duration-1000 animate-pulse"></div>
                    <div class="relative bg-gradient-to-br from-emerald-800 to-teal-900 border-2 border-amber-400/30 p-3 rounded-2xl shadow-lg hover:scale-105 active:scale-95 transition-all">
                        <img src="/icon-192.png" alt="Hasanat Logo" class="w-12 h-12 rounded-lg">
                    </div>
                </a>
                <h1 class="font-outfit font-black text-2xl text-emerald-400 mt-3 tracking-tight">
                    {{ __('messages.app_name') }}
                </h1>
                <p class="text-[10px] font-medium text-emerald-200/60 uppercase tracking-widest mt-1">
                    {{ __('messages.tagline') }}
                </p>
            </div>

            <!-- Glassmorphic Card Container -->
            <div class="bg-white/95 dark:bg-slate-900/90 backdrop-blur-md border border-emerald-500/10 dark:border-emerald-500/20 rounded-3xl shadow-2xl p-6 text-gray-900 dark:text-gray-100 space-y-6">
                {{ $slot }}
            </div>
            
            <!-- Quick Link back to Home -->
            <div class="text-center mt-6">
                <a href="{{ route('home') }}" wire:navigate class="inline-flex items-center gap-1.5 text-xs text-emerald-400 hover:text-emerald-300 font-bold transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <span>{{ app()->getLocale() === 'bn' ? 'হোমে ফিরে যান' : 'Back to Home' }}</span>
                </a>
            </div>
        </div>

        <!-- Global In-App Alert Popup Modal -->
        <div x-data="globalNotificationApp()" 
             x-show="open" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100"
             x-transition:leave-end="opacity-0 scale-95"
             @notify.window="triggerNotification($event.detail)"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             style="display: none;">
             
            <div class="relative bg-white dark:bg-slate-900 border border-emerald-500/20 dark:border-emerald-500/30 rounded-3xl shadow-2xl p-6 w-full max-w-sm text-center space-y-4">
                <!-- Close icon in the top right -->
                <button @click="closeNotification()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>

                <!-- Icon -->
                <div class="mx-auto w-12 h-12 rounded-full bg-emerald-50 dark:bg-emerald-950/50 text-emerald-600 dark:text-emerald-400 flex items-center justify-center shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>

                <!-- Title / Message -->
                <div class="space-y-1">
                    <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100" x-text="title"></h3>
                    <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 leading-relaxed" x-text="message"></p>
                </div>

                <!-- Action Button -->
                <button @click="closeNotification()" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-2.5 rounded-xl text-xs shadow transition active:scale-95">
                    {{ app()->getLocale() === 'bn' ? 'ঠিক আছে' : 'OK' }}
                </button>
            </div>
        </div>

        <script>
            function globalNotificationApp() {
                return {
                    open: false,
                    title: '',
                    message: '',
                    triggerNotification(detail) {
                        this.message = detail.message || '';
                        this.title = detail.title || (document.documentElement.lang === 'bn' ? 'বিজ্ঞপ্তি' : 'Notification');
                        this.open = true;
                    },
                    closeNotification() {
                        this.open = false;
                    }
                }
            }

            window.showAlert = (msg, title) => {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: msg,
                        title: title
                    }
                }));
            };
        </script>

        @livewireScripts
    </body>
</html>
