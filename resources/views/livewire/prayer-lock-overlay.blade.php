<div wire:poll.15s="checkLockStatus">
    @if($isLocked)
        <!-- Full-screen Lockout Overlay -->
        <div class="fixed inset-0 z-50 bg-slate-950/98 dark:bg-slate-950/99 backdrop-blur-xl flex flex-col justify-center items-center text-center p-6 text-white overflow-hidden"
             x-data="prayerLockTimer({{ $secondsRemaining }})"
             x-init="startTimer()"
             x-cloak>
            
            <!-- Geometric Islamic Decorative Element -->
            <div class="w-24 h-24 rounded-full border-4 border-dashed border-amber-400/40 animate-spin-slow flex items-center justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-amber-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25M12 18.75V21m8.94-8.94h-2.25M4.125 12h-2.25m14.54-6.42-1.59 1.59m-9.19 9.19-1.59 1.59m12.72 0-1.59-1.59m-9.19-9.19-1.59-1.59M12 7.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9Z" />
                </svg>
            </div>

            <!-- Title -->
            <h1 class="font-outfit font-extrabold text-2xl text-amber-400 tracking-tight mb-2">
                {{ __('messages.prayer_time_lock') }}
            </h1>
            
            <p class="text-sm text-emerald-200 max-w-xs mb-8">
                {{ __('messages.prayer_lock_desc') }}
            </p>

            <!-- Active Prayer Details -->
            <div class="bg-white/5 border border-white/10 rounded-2xl px-6 py-3 mb-8 shadow-inner">
                <span class="text-xs uppercase tracking-widest text-emerald-300 font-semibold">
                    {{ app()->getLocale() === 'bn' ? 'চলতি ওয়াক্ত' : 'Current Salah' }}
                </span>
                <h2 class="font-outfit font-black text-2xl text-white mt-0.5">
                    {{ app()->getLocale() === 'bn' ? ['Fajr'=>'ফজর', 'Dhuhr'=>'যোহর', 'Asr'=>'আসর', 'Maghrib'=>'মাগরিব', 'Isha'=>'এশা'][$prayerName] ?? $prayerName : $prayerName }}
                </h2>
                <p class="text-[10px] text-gray-400 mt-0.5">
                    {{ app()->getLocale() === 'bn' ? 'ওয়াক্ত শুরু হয়েছে' : 'Start Time' }}: {{ $lockStartTime }}
                </p>
            </div>

            <!-- Countdown Timer Display -->
            <div class="space-y-1 mb-8">
                <span class="text-xs uppercase tracking-widest text-gray-400 font-bold">
                    {{ __('messages.countdown_remaining') }}
                </span>
                <div class="font-outfit font-black text-5xl text-white tracking-tight flex gap-1 justify-center items-baseline">
                    <span x-text="formattedTime"></span>
                </div>
            </div>

            <!-- Lockout Quran Verse/Dua -->
            <div class="max-w-xs border-t border-white/10 pt-6 mt-4 space-y-3">
                <p class="font-arabic text-lg leading-loose text-amber-300/90 font-semibold dir-rtl">
                    إِنَّ الصَّلَاةَ كَانَتْ عَلَى الْمُؤْمِنِينَ كِتَابًا مَّوْقُوتًا
                </p>
                <p class="text-xs italic text-gray-400 leading-relaxed">
                    "{{ app()->getLocale() === 'bn' ? 'নিশ্চয়ই নামাজ মুমিনদের ওপর নির্দিষ্ট সময়ে ফরজ করা হয়েছে।' : 'Indeed, prayer has been decreed upon the believers a decree of specified times.' }}"
                </p>
                <p class="text-[10px] font-bold text-gray-500">— Surah An-Nisa: 103</p>
            </div>

        </div>

        <!-- Inline spin-slow utility style -->
        <style>
            .animate-spin-slow {
                animation: spin 12s linear infinite;
            }
            @keyframes spin {
                from { transform: rotate(0deg); }
                to { transform: rotate(360deg); }
            }
        </style>

        <script>
            function prayerLockTimer(seconds) {
                return {
                    secondsRemaining: seconds,
                    interval: null,

                    startTimer() {
                        this.interval = setInterval(() => {
                            if (this.secondsRemaining > 0) {
                                this.secondsRemaining--;
                            } else {
                                clearInterval(this.interval);
                                // Reload page when timer finishes to unlock UI
                                window.location.reload();
                            }
                        }, 1000);
                    },

                    get formattedTime() {
                        let mins = Math.floor(this.secondsRemaining / 60);
                        let secs = this.secondsRemaining % 60;
                        return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                    }
                }
            }
        </script>
    @endif
</div>
