<div class="space-y-6 pb-12" x-data="homeApp()" x-init="init()">
    
    <!-- Toast Message -->
    @if (session()->has('success'))
        <div class="fixed top-4 left-4 right-4 z-50 bg-emerald-500 text-white px-4 py-3 rounded-xl shadow-lg flex items-center justify-between text-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
            <span>{{ session('success') }}</span>
            <button @click="show = false" class="text-white font-bold">&times;</button>
        </div>
    @endif

    <!-- Daily Quran & Hadith Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <!-- Verse of the Day -->
        @if($this->verse)
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="p-1.5 rounded-lg bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                            </svg>
                        </span>
                        <h3 class="font-outfit font-bold text-sm text-gray-700 dark:text-gray-300">{{ __('messages.verse_of_the_day') }}</h3>
                    </div>
                    <div class="space-y-3">
                        <p class="font-arabic text-xl leading-loose text-emerald-800 dark:text-emerald-300 text-right dir-rtl font-semibold">
                            {{ $this->verse->text_ar }}
                        </p>
                        <p class="text-sm italic text-gray-600 dark:text-gray-400">
                            "{{ app()->getLocale() === 'bn' ? $this->verse->text_bn : $this->verse->text_en }}"
                        </p>
                    </div>
                </div>
                <div class="text-xs text-gray-400 dark:text-gray-500 font-semibold flex justify-end mt-4">
                    — Surah {{ $this->verse->surah_name }}, Verse {{ $this->verse->verse_no }}
                </div>
            </div>
        @endif

        <!-- Hadith of the Day -->
        @if($this->hadith)
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5h1.5m-1.5 3h1.5m-7.5 3h7.5m-7.5 3h7.5m3-9h3.75c.621 0 1.125.504 1.125 1.125V18a2.25 2.25 0 0 1-2.25 2.25h-15A2.25 2.25 0 0 1 3 18V6.75C3 6.129 3.504 5.625 4.125 5.625h12.75c.621 0 1.125.504 1.125 1.125V7.5Z" />
                            </svg>
                        </span>
                        <h3 class="font-outfit font-bold text-sm text-gray-700 dark:text-gray-300">{{ __('messages.hadith_of_the_day') }}</h3>
                    </div>
                    <div class="space-y-3">
                        @if($this->hadith->text_ar)
                            <p class="font-arabic text-lg leading-loose text-emerald-800 dark:text-emerald-300 text-right dir-rtl">
                                {{ $this->hadith->text_ar }}
                            </p>
                        @endif
                        <p class="text-sm italic text-gray-600 dark:text-gray-400">
                            "{{ app()->getLocale() === 'bn' ? $this->hadith->text_bn : $this->hadith->text_en }}"
                        </p>
                    </div>
                </div>
                <div class="text-xs text-gray-400 dark:text-gray-500 font-semibold flex justify-end mt-4">
                    — {{ $this->hadith->reference }}
                </div>
            </div>
        @endif
    </div>

    <!-- Daily Goals Tracker -->
    @if (!auth()->check() || !auth()->user()->isFeatureDisabled('goals'))
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-outfit font-extrabold text-sm text-gray-800 dark:text-gray-200 uppercase tracking-wider">{{ __('messages.daily_goals') }}</h3>
            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/30 px-2 py-0.5 rounded-full" x-text="goalsProgress + '%'"></span>
        </div>
        <div class="w-full bg-gray-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden shadow-inner">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-3 rounded-full transition-all duration-500" :style="'width: ' + goalsProgress + '%'"></div>
        </div>
        <div class="flex justify-between items-center mt-3">
            <span class="text-xs text-gray-400 dark:text-gray-500 font-semibold">{{ __('messages.goals_progress') }}</span>
            <a href="{{ route('records') }}" wire:navigate class="text-xs text-emerald-600 dark:text-emerald-400 hover:underline font-bold">
                {{ app()->getLocale() === 'bn' ? 'লক্ষ্য পরিবর্তন করুন' : 'Manage Goals' }} &rarr;
            </a>
        </div>
    </div>
    @endif

    <!-- Zikir/Amal Section Header -->
    <div class="flex items-center justify-between mt-8">
        <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-200">
            {{ app()->getLocale() === 'bn' ? 'আমলের তালিকা' : 'List of Amals' }}
        </h3>
        <button @click="openAddModal = true" class="flex items-center gap-1 text-xs font-bold bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-xl shadow-md transition">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            {{ __('messages.add_custom_zikir') }}
        </button>
    </div>

    <!-- Zikir Items Grid (Rendered dynamically via Alpine.js to support Guest Mode Custom Zikirs) -->
    <div class="grid grid-cols-2 gap-4">
        <template x-for="zikir in zikirItems" :key="zikir.id">
            <a :href="'{{ route('tasbih') }}?item_id=' + zikir.id" wire:navigate class="relative overflow-hidden group bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm hover:shadow-md hover:border-emerald-200 dark:hover:border-emerald-800 transition flex flex-col justify-between h-36">
                <!-- Icon and Tag -->
                <div class="flex justify-between items-start">
                    <span class="p-2.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400">
                        <template x-if="zikir.icon === 'sparkles'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 21l-.813-5.096L3.096 15l5.096-.813L9 9l.813 5.096 5.096.813-5.096.813Z" /></svg>
                        </template>
                        <template x-if="zikir.icon === 'heart'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" /></svg>
                        </template>
                        <template x-if="zikir.icon === 'sun'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m0 13.5V21m8.94-8.94h-2.25M4.125 12h-2.25m14.54-6.42-1.59 1.59m-9.19 9.19-1.59 1.59m12.72 0-1.59-1.59m-9.19-9.19-1.59-1.59M12 7.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Z" /></svg>
                        </template>
                        <template x-if="zikir.icon === 'shield-check'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 3.296 3.745 3.745 0 0 1-3.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 0 1-3.296-1.043 3.745 3.745 0 0 1-1.043-3.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-3.296 3.746 3.746 0 0 1 3.296-1.043A3.746 3.746 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 0 1 3.296 1.043 3.746 3.746 0 0 1 1.043 3.296A3.745 3.745 0 0 1 21 12Z" /></svg>
                        </template>
                        <template x-if="zikir.icon === 'arrow-path'">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                        </template>
                    </span>
                    <template x-if="zikir.is_custom">
                        <span class="text-[9px] uppercase tracking-wider font-extrabold px-1.5 py-0.5 bg-gray-100 dark:bg-slate-800 text-gray-500 rounded">
                            {{ app()->getLocale() === 'bn' ? 'কাস্টম' : 'Custom' }}
                        </span>
                    </template>
                </div>
                
                <!-- Text and Target -->
                <div class="mt-3">
                    <h4 class="font-bold text-sm text-gray-800 dark:text-gray-100 group-hover:text-emerald-600 dark:group-hover:text-emerald-400 line-clamp-1 transition" x-text="lang === 'bn' ? zikir.name_bn : zikir.name_en"></h4>
                    <p class="font-arabic text-xs text-gray-400 dark:text-gray-500 line-clamp-1 mt-0.5" x-text="zikir.arabic_text"></p>
                    <div class="flex items-center gap-1 mt-2 text-xs text-gray-400 dark:text-gray-500 font-semibold">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span x-text="zikir.default_target"></span>
                    </div>
                </div>
            </a>
        </template>
    </div>

    <!-- Custom Zikir Form Modal -->
    <div class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-slate-950/60 backdrop-blur-sm" x-show="openAddModal" x-transition x-cloak>
        <div class="bg-white dark:bg-slate-900 rounded-2xl w-full max-w-sm border border-gray-100 dark:border-slate-800 p-6 space-y-4 shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100">
                    {{ __('messages.add_custom_zikir') }}
                </h3>
                <button @click="openAddModal = false" class="text-gray-400 dark:text-gray-500 hover:text-gray-600 text-2xl font-bold">&times;</button>
            </div>
            
            <form @submit.prevent="submitZikirForm()" class="space-y-4">
                
                <!-- Bangla Name -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.zikir_name_bn') }}</label>
                    <input type="text" x-model="customNameBn" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white" placeholder="যেমন: সুবহানাল্লাহ">
                </div>

                <!-- English Name -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.zikir_name_en') }}</label>
                    <input type="text" x-model="customNameEn" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white" placeholder="e.g., Subhanallah">
                </div>

                <!-- Arabic script -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.arabic_text') }}</label>
                    <input type="text" x-model="customArabic" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm text-right font-arabic focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white" placeholder="سُبْحَانَ ٱللَّهِ">
                </div>

                <!-- Target Count -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.target_count') }}</label>
                    <input type="number" x-model="customTarget" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
                </div>

                <!-- Icon Selector -->
                <div>
                    <label class="block text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">{{ __('messages.select_icon') }}</label>
                    <select x-model="customIcon" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
                        <option value="sparkles">✨ Sparkles / স্পার্কলস</option>
                        <option value="heart">❤️ Heart / হৃদয়</option>
                        <option value="sun">☀️ Sun / সূর্য</option>
                        <option value="shield-check">🛡️ Shield / ঢাল</option>
                        <option value="arrow-path">🔄 Loop / লুপ</option>
                    </select>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 mt-6">
                    <button type="button" @click="openAddModal = false" class="flex-1 bg-gray-100 dark:bg-slate-800 dark:text-white py-2.5 rounded-xl font-bold text-xs hover:bg-gray-200 transition">
                        {{ __('messages.cancel') }}
                    </button>
                    <button type="submit" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl font-bold text-xs shadow-md transition">
                        {{ __('messages.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function homeApp() {
        return {
            openAddModal: false,
            isGuest: @json(!Auth::check()),
            lang: '{{ app()->getLocale() }}',
            goalsProgress: @json($goalsProgress),
            
            // Form properties
            customNameBn: '',
            customNameEn: '',
            customArabic: '',
            customTarget: 33,
            customIcon: 'sparkles',
            
            zikirItems: [],

            init() {
                this.loadZikirItems();
                this.loadGoalsProgress();
                
                // If the user just registered/logged in (auth is true), check for guest data in localStorage
                if (!this.isGuest) {
                    this.migrateGuestData();
                }
            },

            loadZikirItems() {
                let presets = @json($zikirs);
                if (this.isGuest) {
                    let customLocal = JSON.parse(localStorage.getItem('guest_custom_zikir_items') || '[]');
                    this.zikirItems = [...presets, ...customLocal];
                } else {
                    this.zikirItems = presets;
                }
            },

            loadGoalsProgress() {
                if (this.isGuest) {
                    let now = new Date();
                    let y = now.getFullYear();
                    let m = String(now.getMonth() + 1).padStart(2, '0');
                    let d = String(now.getDate()).padStart(2, '0');
                    let today = `${y}-${m}-${d}`;
                    let counts = JSON.parse(localStorage.getItem('guest_zikir_counts') || '[]');
                    let salah = JSON.parse(localStorage.getItem('guest_salah_logs') || '[]');
                    
                    let activeZikirs = counts.filter(c => c.counted_at === today).length;
                    let activeSalah = salah.filter(s => s.date === today && (s.status === 'jamaat' || s.status === 'alone')).length;
                    
                    let totalActions = activeZikirs + activeSalah;
                    this.goalsProgress = totalActions > 0 ? Math.min(100, totalActions * 20) : 0;
                }
            },

            submitZikirForm() {
                if (this.isGuest) {
                    let customLocal = JSON.parse(localStorage.getItem('guest_custom_zikir_items') || '[]');
                    let newItem = {
                        id: 'guest_' + Date.now(),
                        name_bn: this.customNameBn,
                        name_en: this.customNameEn,
                        arabic_text: this.customArabic,
                        default_target: parseInt(this.customTarget),
                        icon: this.customIcon,
                        is_custom: true
                    };
                    customLocal.push(newItem);
                    localStorage.setItem('guest_custom_zikir_items', JSON.stringify(customLocal));
                    
                    // Clear inputs and close
                    this.customNameBn = '';
                    this.customNameEn = '';
                    this.customArabic = '';
                    this.customTarget = 33;
                    this.customIcon = 'sparkles';
                    this.openAddModal = false;

                    this.loadZikirItems();
                    showAlert(this.lang === 'bn' ? "কাস্টম আমলটি আপনার ফোনে সংরক্ষিত হয়েছে!" : "Custom Amal saved to your local storage!");
                } else {
                    // Authenticated: let Livewire save via MySQL
                    @this.set('customNameBn', this.customNameBn);
                    @this.set('customNameEn', this.customNameEn);
                    @this.set('customArabic', this.customArabic);
                    @this.set('customTarget', this.customTarget);
                    @this.set('customIcon', this.customIcon);
                    @this.saveCustomZikir();
                }
            },

            async migrateGuestData() {
                // Read local storage guest keys
                let customZikirs = JSON.parse(localStorage.getItem('guest_custom_zikir_items') || '[]');
                let zikirCounts = JSON.parse(localStorage.getItem('guest_zikir_counts') || '[]');
                let salahLogs = JSON.parse(localStorage.getItem('guest_salah_logs') || '[]');
                let quranProgress = JSON.parse(localStorage.getItem('guest_quran_progress') || '[]');
                let habitLogs = JSON.parse(localStorage.getItem('guest_habit_logs') || '[]');
                let journalEntries = JSON.parse(localStorage.getItem('guest_journal_entries') || '[]');

                // Read settings
                let settings = {
                    salah_tracker_enabled: localStorage.getItem('guest_salah_tracker_enabled'),
                    quran_tracker_enabled: localStorage.getItem('guest_quran_tracker_enabled'),
                    habit_tracker_enabled: localStorage.getItem('guest_habit_tracker_enabled'),
                    location_latitude: localStorage.getItem('guest_latitude'),
                    location_longitude: localStorage.getItem('guest_longitude'),
                    prayer_method: localStorage.getItem('guest_prayer_method'),
                    theme: localStorage.getItem('guest_theme'),
                    locale: localStorage.getItem('guest_locale'),
                    alert_morning_enabled: localStorage.getItem('guest_alert_morning_enabled'),
                    alert_afternoon_enabled: localStorage.getItem('guest_alert_afternoon_enabled'),
                    alert_evening_enabled: localStorage.getItem('guest_alert_evening_enabled'),
                    alert_push_enabled: localStorage.getItem('guest_alert_push_enabled'),
                    alert_vibration_enabled: localStorage.getItem('guest_alert_vibration_enabled'),
                    alert_sound_enabled: localStorage.getItem('guest_alert_sound_enabled'),
                    alert_morning_time: localStorage.getItem('guest_alert_morning_time'),
                    alert_afternoon_time: localStorage.getItem('guest_alert_afternoon_time'),
                    alert_evening_time: localStorage.getItem('guest_alert_evening_time'),
                    alert_ringtone: localStorage.getItem('guest_alert_ringtone'),
                    alert_volume: localStorage.getItem('guest_alert_volume')
                };

                // Check if there is any data or settings to migrate
                let hasSettings = Object.values(settings).some(val => val !== null);
                if (customZikirs.length === 0 && zikirCounts.length === 0 && salahLogs.length === 0 && quranProgress.length === 0 && habitLogs.length === 0 && journalEntries.length === 0 && !hasSettings) {
                    return;
                }

                try {
                    let response = await fetch('/api/migrate-guest-data', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            custom_zikirs: customZikirs,
                            zikir_counts: zikirCounts,
                            salah_logs: salahLogs,
                            quran_progress: quranProgress,
                            habit_logs: habitLogs,
                            journal_entries: journalEntries,
                            settings: settings
                        })
                    });

                    if (response.ok) {
                        // Clear guest keys in localstorage
                        localStorage.removeItem('guest_custom_zikir_items');
                        localStorage.removeItem('guest_zikir_counts');
                        localStorage.removeItem('guest_salah_logs');
                        localStorage.removeItem('guest_quran_progress');
                        localStorage.removeItem('guest_habit_logs');
                        localStorage.removeItem('guest_journal_entries');
                        
                        localStorage.removeItem('guest_salah_tracker_enabled');
                        localStorage.removeItem('guest_quran_tracker_enabled');
                        localStorage.removeItem('guest_habit_tracker_enabled');
                        localStorage.removeItem('guest_latitude');
                        localStorage.removeItem('guest_longitude');
                        localStorage.removeItem('guest_prayer_method');
                        localStorage.removeItem('guest_theme');
                        localStorage.removeItem('guest_locale');

                        localStorage.removeItem('guest_alert_morning_enabled');
                        localStorage.removeItem('guest_alert_afternoon_enabled');
                        localStorage.removeItem('guest_alert_evening_enabled');
                        localStorage.removeItem('guest_alert_push_enabled');
                        localStorage.removeItem('guest_alert_vibration_enabled');
                        localStorage.removeItem('guest_alert_sound_enabled');

                        localStorage.removeItem('guest_alert_morning_time');
                        localStorage.removeItem('guest_alert_afternoon_time');
                        localStorage.removeItem('guest_alert_evening_time');
                        localStorage.removeItem('guest_alert_ringtone');
                        localStorage.removeItem('guest_alert_volume');

                        showAlert(this.lang === 'bn' ? "আপনার পূর্বের গেস্ট ডেটা সফলভাবে অ্যাকাউন্টে সিঙ্ক করা হয়েছে!" : "Your local guest progress has been successfully merged into your database!");
                        setTimeout(() => window.location.reload(), 2000);
                    }
                } catch (e) {
                    console.error("Migration error:", e);
                }
            }
        }
    }
</script>
