<div class="space-y-6 pb-16" x-data="settingsApp()" x-init="init()">
    
    <!-- Toast Message -->
    @if (session()->has('settings_success'))
        <div class="bg-emerald-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-between text-sm">
            <span>{{ session('settings_success') }}</span>
        </div>
    @endif

    <!-- Guest Mode Banner -->
    <template x-if="isGuest">
        <div class="bg-gradient-to-r from-amber-500 to-amber-600 text-slate-950 rounded-2xl p-4 shadow-md space-y-2">
            <h4 class="font-outfit font-black text-sm flex items-center gap-1.5">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                <span>{{ app()->getLocale() === 'bn' ? 'মেহমান মুড সক্রিয়' : 'Guest Mode Active' }}</span>
            </h4>
            <p class="text-xs font-semibold leading-relaxed">
                {{ app()->getLocale() === 'bn' ? 'আপনার ডেটা সাময়িকভাবে ফোনে সেভ হচ্ছে। যেকোনো ডিভাইস থেকে অ্যাক্সেস করতে এবং চিরতরে সংরক্ষণ করতে এখনই অ্যাকাউন্ট খুলুন!' : 'Your data is temporarily saved on this phone. Register or log in to sync and protect your progress across all devices!' }}
            </p>
            <div class="flex gap-2 pt-1.5">
                <a href="{{ route('register') }}" wire:navigate class="bg-slate-900 text-white text-[10px] font-extrabold px-3 py-1.5 rounded-lg shadow-sm hover:bg-slate-800 transition">
                    {{ __('messages.register') }}
                </a>
                <a href="{{ route('login') }}" wire:navigate class="bg-white text-slate-900 text-[10px] font-extrabold px-3 py-1.5 rounded-lg shadow-sm hover:bg-gray-100 transition">
                    {{ __('messages.login') }}
                </a>
            </div>
        </div>
    </template>

    <!-- Profile Header Card -->
    <div class="bg-gradient-to-br from-emerald-800 to-teal-900 text-white rounded-2xl p-5 shadow-md flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-emerald-700 border-2 border-amber-400/40 flex items-center justify-center font-outfit text-xl font-bold uppercase shadow-inner">
            <span x-text="name[0]"></span>
        </div>
        <div>
            <h3 class="font-outfit font-extrabold text-base leading-tight" x-text="name"></h3>
            <p class="text-xs text-emerald-200 mt-0.5" x-text="email"></p>
        </div>
    </div>

    <!-- Preferences Form -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-5">
        <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100 uppercase tracking-wider">{{ __('messages.settings_panel') }}</h3>
        
        <form @submit.prevent="savePreferences()" class="space-y-4">
            
            <!-- Language Selection -->
            <div>
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5">{{ __('messages.language_preference') }}</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="lang = 'bn'" :class="lang === 'bn' ? 'bg-emerald-600 text-white font-extrabold shadow' : 'bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400 hover:bg-gray-100'" class="py-2.5 rounded-xl text-xs transition">
                        বাংলা (Bangla)
                    </button>
                    <button type="button" @click="lang = 'en'" :class="lang === 'en' ? 'bg-emerald-600 text-white font-extrabold shadow' : 'bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400 hover:bg-gray-100'" class="py-2.5 rounded-xl text-xs transition">
                        English
                    </button>
                </div>
            </div>

            <!-- Theme Selection -->
            <div>
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5">{{ __('messages.theme_preference') }}</label>
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" @click="theme = 'light'" :class="theme === 'light' ? 'bg-emerald-600 text-white font-extrabold shadow' : 'bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400 hover:bg-gray-100'" class="py-2.5 rounded-xl text-xs transition">
                        {{ __('messages.light') }}
                    </button>
                    <button type="button" @click="theme = 'dark'" :class="theme === 'dark' ? 'bg-emerald-600 text-white font-extrabold shadow' : 'bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400 hover:bg-gray-100'" class="py-2.5 rounded-xl text-xs transition">
                        {{ __('messages.dark') }}
                    </button>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-slate-800 my-4">

            <!-- Tracker Configuration Toggles -->
            <div class="space-y-3">
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wider">
                    {{ app()->getLocale() === 'bn' ? 'অ্যাক্টিভ ট্র্যাকার মডিউল' : 'Active Tracker Modules' }}
                </label>
                
                <!-- Salah Tracker Toggle -->
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-800">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        {{ app()->getLocale() === 'bn' ? 'নামাজ ট্র্যাকার' : 'Salah Tracker' }}
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="salahEnabled" class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Quran Tracker Toggle -->
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-800">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        {{ app()->getLocale() === 'bn' ? 'কুরআন ট্র্যাকার' : 'Quran Tracker' }}
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="quranEnabled" class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Habit Tracker Toggle -->
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-800">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        {{ app()->getLocale() === 'bn' ? 'অভ্যাস ট্র্যাকার' : 'Habit Tracker' }}
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="habitEnabled" class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Journal Tracker Toggle -->
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-800">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        {{ app()->getLocale() === 'bn' ? 'ডায়েরি ও প্রতিফলন ট্র্যাকার' : 'Journal & Reflection Tracker' }}
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="journalEnabled" class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                    </label>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-slate-800 my-4">

            <!-- Amal Alert Reminders Configuration -->
            <div class="space-y-3">
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1.5 uppercase tracking-wider">
                    {{ app()->getLocale() === 'bn' ? 'আমল এলার্ট রিমাইন্ডার' : 'Amal Alert Reminders' }}
                </label>
                
                <!-- Morning Reminder Card -->
                <div class="p-3.5 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-800 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            {{ app()->getLocale() === 'bn' ? 'সকালের আমল এলার্ট' : 'Morning Alert' }}
                        </span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="alertMorningEnabled" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                    <div x-show="alertMorningEnabled" class="flex items-center gap-2 pt-1 border-t border-gray-100/50 dark:border-slate-700/50">
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase">{{ app()->getLocale() === 'bn' ? 'এলার্টের সময়:' : 'Alert Time:' }}</span>
                        <input type="time" x-model="alertMorningTime" class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg px-2 py-1 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:text-white">
                    </div>
                </div>

                <!-- Afternoon Reminder Card -->
                <div class="p-3.5 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-800 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            {{ app()->getLocale() === 'bn' ? 'বিকালের আমল এলার্ট' : 'Afternoon Alert' }}
                        </span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="alertAfternoonEnabled" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                    <div x-show="alertAfternoonEnabled" class="flex items-center gap-2 pt-1 border-t border-gray-100/50 dark:border-slate-700/50">
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase">{{ app()->getLocale() === 'bn' ? 'এলার্টের সময়:' : 'Alert Time:' }}</span>
                        <input type="time" x-model="alertAfternoonTime" class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg px-2 py-1 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:text-white">
                    </div>
                </div>

                <!-- Evening Reminder Card -->
                <div class="p-3.5 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-800 space-y-2">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            {{ app()->getLocale() === 'bn' ? 'সন্ধ্যার আমল এলার্ট' : 'Evening Alert' }}
                        </span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="alertEveningEnabled" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>
                    <div x-show="alertEveningEnabled" class="flex items-center gap-2 pt-1 border-t border-gray-100/50 dark:border-slate-700/50">
                        <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase">{{ app()->getLocale() === 'bn' ? 'এলার্টের সময়:' : 'Alert Time:' }}</span>
                        <input type="time" x-model="alertEveningTime" class="bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg px-2 py-1 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:text-white">
                    </div>
                </div>

                <hr class="border-gray-100 dark:border-slate-800/80 my-2">

                <!-- Push Notifications Switch -->
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-800">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        {{ app()->getLocale() === 'bn' ? 'পুশ নোটিফিকেশন এলার্ট' : 'Push Notifications' }}
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="alertPushEnabled" @change="requestPushPermission()" class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Vibration Alert Switch -->
                <div class="flex justify-between items-center p-3 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-800">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                        {{ app()->getLocale() === 'bn' ? 'ভাইব্রেশন এলার্ট' : 'Vibration Alert' }}
                    </span>
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" x-model="alertVibrationEnabled" class="sr-only peer">
                        <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                    </label>
                </div>

                <!-- Ringtone and Volume Controls Card -->
                <div class="p-3.5 bg-gray-50 dark:bg-slate-800 rounded-xl border border-gray-100 dark:border-slate-800 space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                            {{ app()->getLocale() === 'bn' ? 'রিংটোন শব্দ (সাউন্ড)' : 'Sound Ringtone' }}
                        </span>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" x-model="alertSoundEnabled" class="sr-only peer">
                            <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
                        </label>
                    </div>

                    <div x-show="alertSoundEnabled" class="space-y-3 pt-2 border-t border-gray-100/50 dark:border-slate-700/50">
                        <!-- Dropdown Select -->
                        <div>
                            <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1 uppercase">{{ app()->getLocale() === 'bn' ? 'টোন নির্বাচন করুন:' : 'Select Ringtone:' }}</label>
                            <div class="flex gap-2">
                                <select x-model="alertRingtone" class="flex-1 bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg px-2 py-1.5 text-xs font-semibold focus:outline-none focus:ring-1 focus:ring-emerald-500 dark:text-white">
                                    <option value="chime">{{ app()->getLocale() === 'bn' ? 'সফট চিম (Soft Chime)' : 'Soft Chime' }}</option>
                                    <option value="echo">{{ app()->getLocale() === 'bn' ? 'ডন ইকো (Dawn Echo)' : 'Dawn Echo' }}</option>
                                    <option value="arpeggio">{{ app()->getLocale() === 'bn' ? 'সিরিন আরপেজিও (Serene Arpeggio)' : 'Serene Arpeggio' }}</option>
                                </select>
                                <button type="button" @click="previewRingtone()" class="px-3 py-1 bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-900/50 rounded-lg text-xs font-bold flex items-center justify-center hover:bg-emerald-100 active:scale-95 transition">
                                    🔊 {{ app()->getLocale() === 'bn' ? 'টেস্ট' : 'Test' }}
                                </button>
                            </div>
                        </div>

                        <!-- Volume Slider -->
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <label class="text-[10px] font-bold text-gray-400 dark:text-gray-500 uppercase">{{ app()->getLocale() === 'bn' ? 'ভলিউম:' : 'Volume:' }}</label>
                                <span class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400" x-text="alertVolume + '%'"></span>
                            </div>
                            <input type="range" min="0" max="100" x-model="alertVolume" class="w-full h-1.5 bg-gray-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-emerald-600">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="border-gray-100 dark:border-slate-800 my-4">

            <!-- Location Coordinates Setup -->
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500">{{ app()->getLocale() === 'bn' ? 'নামাজের সময় লোকেশন সেটিংস' : 'Location for Prayer Times' }}</label>
                    
                    <button type="button" @click="detectLocation()" class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 border border-emerald-100 dark:border-emerald-900/30 px-2 py-0.5 rounded-lg flex items-center gap-1 active:scale-95 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                        </svg>
                        <span x-text="locating ? 'Detecting...' : 'Auto Detect'"></span>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 mb-0.5">{{ app()->getLocale() === 'bn' ? 'অক্ষাংশ (Latitude)' : 'Latitude' }}</label>
                        <input type="text" x-model="latitude" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-gray-400 dark:text-gray-500 mb-0.5">{{ app()->getLocale() === 'bn' ? 'দ্রাঘিমাংশ (Longitude)' : 'Longitude' }}</label>
                        <input type="text" x-model="longitude" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
                    </div>
                </div>

                <!-- Calculation Method Selector -->
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1">{{ app()->getLocale() === 'bn' ? 'হিসাব পদ্ধতি' : 'Calculation Method' }}</label>
                    <select x-model="prayerMethod" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
                        <option value="1">University of Islamic Sciences, Karachi</option>
                        <option value="2">Islamic Society of North America (ISNA)</option>
                        <option value="3">Muslim World League (MWL)</option>
                        <option value="4">Umm Al-Qura University, Makkah</option>
                        <option value="5">Egyptian General Authority of Survey</option>
                        <option value="7">Institute of Geophysics, University of Tehran</option>
                        <option value="8">Gulf Region</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl text-xs shadow transition mt-6">
                {{ __('messages.save') }}
            </button>
        </form>

    </div>

    <!-- Data Backup & Exports -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-4">
        <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100 uppercase tracking-wider">{{ __('messages.backup_export') }}</h3>
        
        <div class="grid grid-cols-2 gap-4">
            <button @click="triggerJsonExport()" class="flex flex-col items-center justify-center p-4 bg-emerald-50/50 dark:bg-emerald-950/20 border border-emerald-100/50 dark:border-emerald-900/30 rounded-2xl text-center hover:bg-emerald-100/30 active:scale-95 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-emerald-600 dark:text-emerald-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75v6.75m0 0-3-3m3 3 3-3m-8.25 6a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
                </svg>
                <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300 mt-2">{{ __('messages.export_json') }}</span>
            </button>

            <button @click="triggerCsvExport()" class="flex flex-col items-center justify-center p-4 bg-amber-50/50 dark:bg-amber-950/20 border border-amber-100/50 dark:border-amber-900/30 rounded-2xl text-center hover:bg-amber-100/30 active:scale-95 transition">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-amber-500 dark:text-amber-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m6.75 12-3-3m0 0-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                </svg>
                <span class="text-xs font-bold text-amber-800 dark:text-amber-300 mt-2">{{ __('messages.export_csv') }}</span>
            </button>
        </div>
    </div>

    <!-- Developer Info -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-4">
        <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100 uppercase tracking-wider">
            {{ app()->getLocale() === 'bn' ? 'ডেভেলপার পরিচিতি' : 'Developer Info' }}
        </h3>
        
        <div class="flex flex-col sm:flex-row items-center gap-4">
            <img src="/developer.jpg" alt="Omar Faruk" class="w-20 h-20 rounded-full border-2 border-emerald-500/30 dark:border-emerald-500/50 shadow-md object-cover">
            
            <div class="flex-1 text-center sm:text-left space-y-1">
                <h4 class="font-outfit font-extrabold text-lg text-gray-800 dark:text-gray-100">Omar Faruk</h4>
                <p class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">Web Developer & Agency Founder</p>
                
                <div class="flex flex-wrap justify-center sm:justify-start gap-x-4 gap-y-1 text-xs text-gray-500 dark:text-gray-400">
                    <a href="https://developerbhai.com" target="_blank" class="hover:text-emerald-500 font-bold flex items-center gap-1 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9s2.015-9 4.5-9m0 0a9.003 9.003 0 0 1 8.716 2.253M12 3a9.003 9.003 0 0 0-8.716 2.253" />
                        </svg>
                        <span>DeveloperBhai.com</span>
                    </a>
                    
                    <a href="mailto:omarfaruuuk@gmail.com" class="hover:text-emerald-500 font-bold flex items-center gap-1 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                        </svg>
                        <span>omarfaruuuk@gmail.com</span>
                    </a>
                </div>
            </div>
        </div>
        
        <p class="text-xs text-gray-600 dark:text-gray-400 leading-relaxed pt-2 border-t border-gray-50 dark:border-slate-800/60">
            {{ app()->getLocale() === 'bn' 
                ? 'ওমর ফারুক ঢাকা, বাংলাদেশ থেকে একজন রেজাল্ট-ওরিয়েন্টেড ওয়েব ডেভেলপার। ফাইভারে আন্তর্জাতিক ক্লায়েন্টদের জন্য কাস্টম ওয়েব সলিউশন প্রদানের মাধ্যমে তার কাজের অভিজ্ঞতা রয়েছে। তিনি ওয়ার্ডপ্রেস, পিএইচপি, লারাভেল এবং এইচটিএমএল/সিএসএস নিয়ে কাজ করেন। বর্তমানে ইনডিপেনডেন্ট ইউনিভার্সিটি বাংলাদেশে এলএলবি পড়ার পাশাপাশি "ডেভেলপার ভাই" এজেন্সির মাধ্যমে ডিজিটাল প্রোডাক্ট তৈরি করছেন।' 
                : 'Results-driven Web Developer from Dhaka, Bangladesh with hands-on experience in WordPress development, PHP, HTML/CSS, and Laravel. Proven track record on Fiverr delivering custom web solutions for international clients. Currently pursuing LLB at Independent University Bangladesh while actively building digital products through Developer Bhai agency. Passionate about clean code, modern design, and client satisfaction.' 
            }}
        </p>
    </div>

    <!-- Auth Button / Sign Out -->
    <div class="w-full max-w-sm">
        <template x-if="!isGuest">
            <button @click="logout()" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-extrabold py-3.5 rounded-xl text-xs shadow transition">
                {{ __('messages.logout') }}
            </button>
        </template>
        <template x-if="isGuest">
            <div class="flex gap-4">
                <a href="{{ route('login') }}" wire:navigate class="flex-1 text-center bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3.5 rounded-xl text-xs shadow transition">
                    {{ __('messages.login') }}
                </a>
                <a href="{{ route('register') }}" wire:navigate class="flex-1 text-center bg-teal-600 hover:bg-teal-700 text-white font-extrabold py-3.5 rounded-xl text-xs shadow transition">
                    {{ __('messages.register') }}
                </a>
            </div>
        </template>
    </div>

    <!-- Alpine settings controller -->
    <script>
        function settingsApp() {
            return {
                isGuest: @json(!Auth::check()),
                name: @json($name),
                email: @json($email),
                lang: @json($languagePreference),
                theme: @json($themePreference),
                
                latitude: '{{ $latitude }}',
                longitude: '{{ $longitude }}',
                prayerMethod: '{{ $prayerMethod }}',
                locating: false,

                // Entangle tracker variables
                salahEnabled: @entangle('salahEnabled'),
                quranEnabled: @entangle('quranEnabled'),
                habitEnabled: @entangle('habitEnabled'),
                journalEnabled: @entangle('journalEnabled'),

                alertMorningEnabled: @entangle('alertMorningEnabled'),
                alertAfternoonEnabled: @entangle('alertAfternoonEnabled'),
                alertEveningEnabled: @entangle('alertEveningEnabled'),
                alertPushEnabled: @entangle('alertPushEnabled'),
                alertVibrationEnabled: @entangle('alertVibrationEnabled'),
                alertSoundEnabled: @entangle('alertSoundEnabled'),

                alertMorningTime: @entangle('alertMorningTime'),
                alertAfternoonTime: @entangle('alertAfternoonTime'),
                alertEveningTime: @entangle('alertEveningTime'),
                alertRingtone: @entangle('alertRingtone'),
                alertVolume: @entangle('alertVolume'),

                init() {
                    // If guest, load settings and toggles from localStorage
                    if (this.isGuest) {
                        this.latitude = localStorage.getItem('guest_latitude') || '23.8103';
                        this.longitude = localStorage.getItem('guest_longitude') || '90.4125';
                        this.prayerMethod = localStorage.getItem('guest_prayer_method') || '3';
                        
                        this.salahEnabled = localStorage.getItem('guest_salah_tracker_enabled') !== 'false';
                        this.quranEnabled = localStorage.getItem('guest_quran_tracker_enabled') !== 'false';
                        this.habitEnabled = localStorage.getItem('guest_habit_tracker_enabled') !== 'false';
                        this.journalEnabled = localStorage.getItem('guest_journal_tracker_enabled') !== 'false';

                        this.alertMorningEnabled = localStorage.getItem('guest_alert_morning_enabled') !== 'false';
                        this.alertAfternoonEnabled = localStorage.getItem('guest_alert_afternoon_enabled') !== 'false';
                        this.alertEveningEnabled = localStorage.getItem('guest_alert_evening_enabled') !== 'false';
                        this.alertPushEnabled = localStorage.getItem('guest_alert_push_enabled') !== 'false';
                        this.alertVibrationEnabled = localStorage.getItem('guest_alert_vibration_enabled') !== 'false';
                        this.alertSoundEnabled = localStorage.getItem('guest_alert_sound_enabled') !== 'false';

                        this.alertMorningTime = localStorage.getItem('guest_alert_morning_time') || '07:00';
                        this.alertAfternoonTime = localStorage.getItem('guest_alert_afternoon_time') || '13:30';
                        this.alertEveningTime = localStorage.getItem('guest_alert_evening_time') || '18:30';
                        this.alertRingtone = localStorage.getItem('guest_alert_ringtone') || 'chime';
                        this.alertVolume = parseInt(localStorage.getItem('guest_alert_volume') || '50');
                    }
                },

                requestPushPermission() {
                    if (this.alertPushEnabled) {
                        if (!("Notification" in window)) {
                            showAlert(this.lang === 'bn' ? "এই ব্রাউজারে নোটিফিকেশন সাপোর্ট করে না।" : "Notifications are not supported in this browser.");
                            this.alertPushEnabled = false;
                            return;
                        }
                        Notification.requestPermission().then(permission => {
                            if (permission === "granted") {
                                showAlert(this.lang === 'bn' ? "নোটিফিকেশন সফলভাবে সক্রিয় হয়েছে!" : "Notifications successfully enabled!");
                            } else {
                                showAlert(this.lang === 'bn' ? "নোটিফিকেশন অনুমতি ব্লক করা হয়েছে। অনুগ্রহ করে ব্রাউজার সেটিংস চেক করুন।" : "Notification permission denied. Please check browser settings.");
                                this.alertPushEnabled = false;
                            }
                        });
                    }
                },

                detectLocation() {
                    if (!navigator.geolocation) {
                        showAlert("Geolocation is not supported by your browser.");
                        return;
                    }
                    this.locating = true;
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            this.latitude = position.coords.latitude.toFixed(4);
                            this.longitude = position.coords.longitude.toFixed(4);
                            this.locating = false;
                            
                            if (this.isGuest) {
                                localStorage.setItem('guest_latitude', this.latitude);
                                localStorage.setItem('guest_longitude', this.longitude);
                            }
                            showAlert(this.lang === 'bn' ? "লোকেশন অক্ষাংশ ও দ্রাঘিমাংশ সফলভাবে সনাক্ত হয়েছে!" : "Coordinates detected successfully!");
                        },
                        () => {
                            this.locating = false;
                            showAlert("Unable to fetch location. Please check browser permissions.");
                        }
                    );
                },

                savePreferences() {
                    if (this.isGuest) {
                        // Save locales, themes, coordinates and toggles in localStorage
                        localStorage.setItem('guest_latitude', this.latitude);
                        localStorage.setItem('guest_longitude', this.longitude);
                        localStorage.setItem('guest_prayer_method', this.prayerMethod);
                        localStorage.setItem('guest_theme', this.theme);
                        localStorage.setItem('guest_locale', this.lang);
                        
                        localStorage.setItem('guest_salah_tracker_enabled', this.salahEnabled);
                        localStorage.setItem('guest_quran_tracker_enabled', this.quranEnabled);
                        localStorage.setItem('guest_habit_tracker_enabled', this.habitEnabled);
                        localStorage.setItem('guest_journal_tracker_enabled', this.journalEnabled);

                        localStorage.setItem('guest_alert_morning_enabled', this.alertMorningEnabled);
                        localStorage.setItem('guest_alert_afternoon_enabled', this.alertAfternoonEnabled);
                        localStorage.setItem('guest_alert_evening_enabled', this.alertEveningEnabled);
                        localStorage.setItem('guest_alert_push_enabled', this.alertPushEnabled);
                        localStorage.setItem('guest_alert_vibration_enabled', this.alertVibrationEnabled);
                        localStorage.setItem('guest_alert_sound_enabled', this.alertSoundEnabled);

                        localStorage.setItem('guest_alert_morning_time', this.alertMorningTime);
                        localStorage.setItem('guest_alert_afternoon_time', this.alertAfternoonTime);
                        localStorage.setItem('guest_alert_evening_time', this.alertEveningTime);
                        localStorage.setItem('guest_alert_ringtone', this.alertRingtone);
                        localStorage.setItem('guest_alert_volume', this.alertVolume);
                    }
                    
                    // Trigger Livewire saving
                    @this.set('languagePreference', this.lang);
                    @this.set('themePreference', this.theme);
                    @this.set('latitude', this.latitude);
                    @this.set('longitude', this.longitude);
                    @this.set('prayerMethod', this.prayerMethod);
                    @this.set('salahEnabled', this.salahEnabled);
                    @this.set('quranEnabled', this.quranEnabled);
                    @this.set('habitEnabled', this.habitEnabled);
                    @this.set('journalEnabled', this.journalEnabled);

                    @this.set('alertMorningEnabled', this.alertMorningEnabled);
                    @this.set('alertAfternoonEnabled', this.alertAfternoonEnabled);
                    @this.set('alertEveningEnabled', this.alertEveningEnabled);
                    @this.set('alertPushEnabled', this.alertPushEnabled);
                    @this.set('alertVibrationEnabled', this.alertVibrationEnabled);
                    @this.set('alertSoundEnabled', this.alertSoundEnabled);

                    @this.set('alertMorningTime', this.alertMorningTime);
                    @this.set('alertAfternoonTime', this.alertAfternoonTime);
                    @this.set('alertEveningTime', this.alertEveningTime);
                    @this.set('alertRingtone', this.alertRingtone);
                    @this.set('alertVolume', this.alertVolume);

                    @this.savePreferences();
                },

                previewRingtone() {
                    try {
                        let AudioContext = window.AudioContext || window.webkitAudioContext;
                        if (!AudioContext) return;
                        let ctx = new AudioContext();
                        let vol = parseInt(this.alertVolume) / 100;
                        let now = ctx.currentTime;
                        
                        if (this.alertRingtone === 'chime') {
                            let notes = [523.25, 659.25, 783.99, 1046.50];
                            notes.forEach((freq, index) => {
                                let osc = ctx.createOscillator();
                                let gain = ctx.createGain();
                                osc.type = 'sine';
                                osc.frequency.setValueAtTime(freq, now + index * 0.15);
                                gain.gain.setValueAtTime(0, now + index * 0.15);
                                gain.gain.linearRampToValueAtTime(vol * 0.3, now + index * 0.15 + 0.05);
                                gain.gain.exponentialRampToValueAtTime(0.0001, now + index * 0.15 + 0.6);
                                osc.connect(gain);
                                gain.connect(ctx.destination);
                                osc.start(now + index * 0.15);
                                osc.stop(now + index * 0.15 + 0.6);
                            });
                        } else if (this.alertRingtone === 'echo') {
                            let notes = [293.66, 349.23, 440.00, 587.33];
                            notes.forEach((freq, index) => {
                                let osc = ctx.createOscillator();
                                let gain = ctx.createGain();
                                let filter = ctx.createBiquadFilter();
                                
                                osc.type = 'square';
                                osc.frequency.setValueAtTime(freq, now + index * 0.25);
                                
                                filter.type = 'lowpass';
                                filter.frequency.setValueAtTime(600, now);
                                
                                gain.gain.setValueAtTime(0, now + index * 0.25);
                                gain.gain.linearRampToValueAtTime(vol * 0.15, now + index * 0.25 + 0.05);
                                gain.gain.exponentialRampToValueAtTime(0.0001, now + index * 0.25 + 0.8);
                                
                                osc.connect(filter);
                                filter.connect(gain);
                                gain.connect(ctx.destination);
                                
                                osc.start(now + index * 0.25);
                                osc.stop(now + index * 0.25 + 0.8);
                            });
                        } else if (this.alertRingtone === 'arpeggio') {
                            let notes = [349.23, 440.00, 523.25, 659.25, 880.00];
                            notes.forEach((freq, index) => {
                                let osc = ctx.createOscillator();
                                let gain = ctx.createGain();
                                osc.type = 'triangle';
                                osc.frequency.setValueAtTime(freq, now + index * 0.08);
                                gain.gain.setValueAtTime(0, now + index * 0.08);
                                gain.gain.linearRampToValueAtTime(vol * 0.25, now + index * 0.08 + 0.03);
                                gain.gain.exponentialRampToValueAtTime(0.0001, now + index * 0.08 + 0.4);
                                osc.connect(gain);
                                gain.connect(ctx.destination);
                                osc.start(now + index * 0.08);
                                osc.stop(now + index * 0.08 + 0.4);
                            });
                        }
                    } catch (e) {
                        console.error("Preview sound error:", e);
                    }
                },

                triggerJsonExport() {
                    if (this.isGuest) {
                        let data = {
                            custom_zikirs: JSON.parse(localStorage.getItem('guest_custom_zikir_items') || '[]'),
                            zikir_counts: JSON.parse(localStorage.getItem('guest_zikir_counts') || '[]'),
                            salah_logs: JSON.parse(localStorage.getItem('guest_salah_logs') || '[]'),
                            quran_progress: JSON.parse(localStorage.getItem('guest_quran_progress') || '[]'),
                            habit_logs: JSON.parse(localStorage.getItem('guest_habit_logs') || '[]'),
                            journal_entries: JSON.parse(localStorage.getItem('guest_journal_entries') || '[]')
                        };

                        let blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                        let url = URL.createObjectURL(blob);
                        let a = document.createElement('a');
                        a.href = url;
                        a.download = "hasanat_guest_backup_" + new Date().toLocaleDateString('sv-SE') + ".json";
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                    } else {
                        @this.exportJson();
                    }
                },

                triggerCsvExport() {
                    if (this.isGuest) {
                        let counts = JSON.parse(localStorage.getItem('guest_zikir_counts') || '[]');
                        let csvContent = "\uFEFFDate,Zikir ID,Count\n";
                        counts.forEach(c => {
                            csvContent += `${c.counted_at},${c.zikir_item_id},${c.count}\n`;
                        });

                        let blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                        let url = URL.createObjectURL(blob);
                        let a = document.createElement('a');
                        a.href = url;
                        a.download = "guest_zikir_counts_" + new Date().toLocaleDateString('sv-SE') + ".csv";
                        document.body.appendChild(a);
                        a.click();
                        document.body.removeChild(a);
                        URL.revokeObjectURL(url);
                    } else {
                        @this.exportCsv();
                    }
                },

                logout() {
                    @this.logout();
                }
            }
        }
    </script>
</div>
