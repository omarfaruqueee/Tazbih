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
        <meta name="theme-color" content="#064e3b">
        <link rel="manifest" href="/manifest.json">
        <link rel="apple-touch-icon" href="/icon-192.png">

        <title>{{ __('messages.app_name') }}</title>

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

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Amiri:ital@0;1&family=Inter:wght@300;400;500;600;700&family=Outfit:wght@400;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        
        @php
            $alertSettings = [
                'authenticated' => auth()->check(),
                'morning' => true,
                'afternoon' => true,
                'evening' => true,
                'push' => true,
                'vibration' => true,
                'sound' => true,
                'morning_time' => '07:00',
                'afternoon_time' => '13:30',
                'evening_time' => '18:30',
                'ringtone' => 'chime',
                'volume' => 50,
            ];
            if (auth()->check()) {
                $user = auth()->user();
                foreach (['morning', 'afternoon', 'evening', 'push', 'vibration', 'sound'] as $key) {
                    $setting = \App\Models\Setting::where('user_id', $user->id)->where('key', 'alert_' . $key . '_enabled')->first();
                    if ($setting) {
                        $alertSettings[$key] = filter_var($setting->value, FILTER_VALIDATE_BOOLEAN);
                    }
                }
                foreach (['morning_time', 'afternoon_time', 'evening_time', 'ringtone'] as $key) {
                    $setting = \App\Models\Setting::where('user_id', $user->id)->where('key', 'alert_' . $key)->first();
                    if ($setting) {
                        $alertSettings[$key] = $setting->value;
                    }
                }
                $volSetting = \App\Models\Setting::where('user_id', $user->id)->where('key', 'alert_volume')->first();
                if ($volSetting) {
                    $alertSettings['volume'] = (int)$volSetting->value;
                }
            } else {
                foreach (['morning', 'afternoon', 'evening', 'push', 'vibration', 'sound'] as $key) {
                    $alertSettings[$key] = session()->get('alert_' . $key . '_enabled', true);
                }
                $alertSettings['morning_time'] = session()->get('alert_morning_time', '07:00');
                $alertSettings['afternoon_time'] = session()->get('alert_afternoon_time', '13:30');
                $alertSettings['evening_time'] = session()->get('alert_evening_time', '18:30');
                $alertSettings['ringtone'] = session()->get('alert_ringtone', 'chime');
                $alertSettings['volume'] = (int)session()->get('alert_volume', 50);
            }
        @endphp
        <script>
            window.hasanatAlertConfig = @json($alertSettings);
        </script>
        
        <style>
            .font-arabic {
                font-family: 'Amiri', 'Scheherazade New', serif;
            }
            .font-outfit {
                font-family: 'Outfit', sans-serif;
            }
            body {
                font-family: 'Inter', sans-serif;
                -webkit-tap-highlight-color: transparent;
            }
            /* Safe area padding for mobile bottom bar */
            .pb-safe {
                padding-bottom: env(safe-area-inset-bottom, 0);
            }
        </style>
    </head>
    <body class="bg-gray-50 dark:bg-slate-950 text-gray-900 dark:text-gray-100 antialiased pb-24">
        <div class="min-h-screen flex flex-col">
            
            <!-- Top Navbar (Bilingual Quick Toggle & User Welcome) -->
            <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border-b border-gray-200 dark:border-slate-800 shadow-sm">
                <div class="max-w-md mx-auto px-4 py-3 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <img src="/icon-192.png" alt="Icon" class="w-8 h-8 rounded-lg shadow-inner">
                        <span class="font-outfit font-extrabold text-lg text-emerald-700 dark:text-emerald-400 tracking-tight">
                            {{ __('messages.app_name') }}
                        </span>
                    </div>
                    
                    <!-- Language Quick Switcher -->
                    <div class="flex items-center gap-2">
                        @auth
                            <form action="{{ route('settings') }}" method="GET" class="inline">
                                <a href="{{ route('settings') }}" wire:navigate class="text-xs font-semibold px-2 py-1 rounded bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800 hover:bg-emerald-100 transition">
                                    {{ app()->getLocale() === 'bn' ? 'EN' : 'বাং' }}
                                </a>
                            </form>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Main Scrollable Area -->
            <main class="flex-1 w-full max-w-md mx-auto px-4 py-6">
                {{ $slot }}
            </main>

            <!-- Bottom Navigation Bar (4 Buttons) -->
            <nav class="fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-lg border-t border-gray-200 dark:border-slate-800 shadow-[0_-4px_12px_rgba(0,0,0,0.05)] pb-safe">
                <div class="max-w-md mx-auto px-6 py-2 flex justify-between items-center text-center">
                    
                    <!-- Home -->
                    <a href="{{ route('home') }}" wire:navigate class="flex flex-col items-center gap-1 group w-16 transition {{ request()->routeIs('home') ? 'text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-400 dark:text-gray-500 hover:text-emerald-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 transition-transform group-hover:scale-110">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21.75h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                        </svg>
                        <span class="text-[10px] tracking-tight">{{ __('messages.nav_home') }}</span>
                    </a>

                    <!-- Tasbih -->
                    <a href="{{ route('tasbih') }}" wire:navigate class="flex flex-col items-center gap-1 group w-16 transition {{ request()->routeIs('tasbih') ? 'text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-400 dark:text-gray-500 hover:text-emerald-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 transition-transform group-hover:scale-110">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        <span class="text-[10px] tracking-tight">{{ __('messages.nav_tasbih') }}</span>
                    </a>

                    <!-- Records -->
                    <a href="{{ route('records') }}" wire:navigate class="flex flex-col items-center gap-1 group w-16 transition {{ request()->routeIs('records') ? 'text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-400 dark:text-gray-500 hover:text-emerald-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 transition-transform group-hover:scale-110">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                        <span class="text-[10px] tracking-tight">{{ __('messages.nav_records') }}</span>
                    </a>

                    <!-- Settings -->
                    <a href="{{ route('settings') }}" wire:navigate class="flex flex-col items-center gap-1 group w-16 transition {{ request()->routeIs('settings') ? 'text-emerald-600 dark:text-emerald-400 font-semibold' : 'text-gray-400 dark:text-gray-500 hover:text-emerald-500' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 transition-transform group-hover:scale-110">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 0 1 1.37.49l1.296 2.247a1.125 1.125 0 0 1-.26 1.43l-1.003.828c-.293.241-.438.613-.43.992a7.723 7.723 0 0 1 0 .255c-.008.378.137.75.43.991l1.004.827c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 0 1-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.47 6.47 0 0 1-.22.128c-.331.183-.581.495-.644.869l-.213 1.281c-.09.543-.56.94-1.11.94h-2.594c-.55 0-1.019-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 0 1-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 0 1-1.369-.49l-1.297-2.247a1.125 1.125 0 0 1 .26-1.43l1.004-.827c.292-.24.437-.613.43-.991a6.932 6.932 0 0 1 0-.255c.007-.38-.138-.751-.43-.992l-1.004-.827a1.125 1.125 0 0 1-.26-1.43l1.297-2.247a1.125 1.125 0 0 1 1.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.086.22-.128.332-.183.582-.495.644-.869l.214-1.28Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <span class="text-[10px] tracking-tight">{{ __('messages.nav_settings') }}</span>
                    </a>

                </div>
            </nav>
            
        </div>

        @livewireScripts


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

                <!-- Action Buttons -->
                <div class="flex gap-2 w-full">
                    <template x-if="slot">
                        <button @click="snoozeAlert()" class="flex-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-extrabold py-2.5 rounded-xl text-xs shadow transition active:scale-95">
                            {{ app()->getLocale() === 'bn' ? '১০ মিনিট স্নুজ' : 'Snooze 10m' }}
                        </button>
                    </template>
                    <button @click="closeNotification()" :class="slot ? 'flex-1' : 'w-full'" class="bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-2.5 rounded-xl text-xs shadow transition active:scale-95">
                        {{ app()->getLocale() === 'bn' ? 'ঠিক আছে' : 'OK' }}
                    </button>
                </div>
            </div>
        </div>

        <script>
            function globalNotificationApp() {
                return {
                    open: false,
                    title: '',
                    message: '',
                    slot: null,
                    triggerNotification(detail) {
                        this.message = detail.message || '';
                        this.title = detail.title || (document.documentElement.lang === 'bn' ? 'বিজ্ঞপ্তি' : 'Notification');
                        this.slot = detail.slot || null;
                        this.open = true;
                    },
                    snoozeAlert() {
                        if (this.slot) {
                            let snoozeTime = new Date(Date.now() + 10 * 60 * 1000);
                            let currentHour = snoozeTime.getHours();
                            let currentMin = snoozeTime.getMinutes();
                            let timeStr = (currentHour < 10 ? '0' : '') + currentHour + ':' + (currentMin < 10 ? '0' : '') + currentMin;
                            localStorage.setItem('snooze_time_' + this.slot, timeStr);
                            localStorage.removeItem('snooze_triggered_' + this.slot);
                            
                            showAlert(
                                document.documentElement.lang === 'bn' 
                                    ? 'রিমাইন্ডারটি ১০ মিনিটের জন্য স্নুজ করা হয়েছে।' 
                                    : 'Reminder has been snoozed for 10 minutes.',
                                document.documentElement.lang === 'bn' ? 'স্নুজ সক্রিয়' : 'Snoozed'
                            );
                        }
                        this.open = false;
                    },
                    closeNotification() {
                        this.open = false;
                    }
                }
            }

            window.showAlert = (msg, title, slot) => {
                window.dispatchEvent(new CustomEvent('notify', {
                    detail: {
                        message: msg,
                        title: title,
                        slot: slot
                    }
                }));
            };
        </script>

        <!-- Amal Alerts Checker Script -->
        <script src="/alert-checker.js" defer></script>

        <!-- Service Worker Registration -->
        <script>
            if ('serviceWorker' in navigator) {
                window.addEventListener('load', () => {
                    navigator.serviceWorker.register('/service-worker.js')
                        .then(reg => console.log('Service Worker registered!'))
                        .catch(err => console.error('Service Worker registration failed:', err));
                });
            }
        </script>
    </body>
</html>
