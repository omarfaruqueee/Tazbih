<div class="space-y-6 pb-12 flex flex-col items-center select-none" 
     x-data="tasbihApp()"
     x-init="init()"
     @trigger-vibration.window="vibrate($event.detail.duration)"
     @trigger-target-sound.window="playTargetSound()"
     @zikir-changed.window="updateZikirDetails($event.detail)">
    
    <!-- Zikir Selector -->
    <div class="w-full max-w-sm">
        <label class="block text-xs font-semibold text-gray-400 dark:text-gray-500 mb-1 uppercase tracking-wider">{{ app()->getLocale() === 'bn' ? 'আমল নির্বাচন করুন' : 'Select Zikir' }}</label>
        <select x-model="zikirItemId" @change="onZikirChanged()" class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-800 rounded-xl px-4 py-3 text-sm font-semibold shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
            <template x-for="item in zikirItems" :key="item.id">
                <option :value="item.id" x-text="lang === 'bn' ? item.name_bn : item.name_en" :selected="item.id == zikirItemId"></option>
            </template>
        </select>
    </div>

    <!-- Active Zikir Display Card -->
    <div class="text-center space-y-1">
        <h1 class="font-arabic text-3xl font-extrabold text-emerald-800 dark:text-emerald-300 leading-normal" x-text="activeArabic"></h1>
        <h2 class="font-outfit font-extrabold text-lg text-gray-700 dark:text-gray-200" x-text="activeName"></h2>
        <p class="text-xs text-gray-400 dark:text-gray-500 font-bold">
            {{ app()->getLocale() === 'bn' ? 'আজকের লক্ষ্য' : 'Today\'s Target' }}: <span x-text="target"></span>
        </p>
    </div>

    <!-- Large Tapping Circle Counter Button -->
    <div class="relative flex items-center justify-center my-6">
        <!-- Decorative Glow Outer Ring -->
        <div class="absolute w-72 h-72 rounded-full bg-emerald-500/5 dark:bg-emerald-400/5 animate-pulse"></div>
        <div class="absolute w-64 h-64 rounded-full bg-emerald-500/10 dark:bg-emerald-400/10"></div>
        
        <!-- Main Circular Button -->
        <button @click="handleCounterClick()" class="relative z-10 w-56 h-56 rounded-full bg-gradient-to-br from-emerald-600 to-teal-800 dark:from-emerald-700 dark:to-teal-900 text-white shadow-2xl border-4 border-amber-400/30 flex flex-col items-center justify-center active:scale-95 transition-transform duration-100 focus:outline-none">
            <span class="text-xs text-emerald-200 tracking-widest font-semibold uppercase">{{ app()->getLocale() === 'bn' ? 'জিকির' : 'Count' }}</span>
            <span class="font-outfit font-black text-6xl my-1 select-none tracking-tight" x-text="count"></span>
            <span class="text-xs text-amber-300 font-bold">{{ app()->getLocale() === 'bn' ? 'টার্গেট' : 'Target' }}: <span x-text="target"></span></span>
        </button>
    </div>

    <!-- Daily Log Total -->
    <div class="bg-emerald-50 dark:bg-emerald-950/20 border border-emerald-100 dark:border-emerald-900/30 rounded-2xl px-6 py-3 text-center shadow-inner">
        <span class="text-xs text-emerald-700 dark:text-emerald-400 font-semibold">{{ app()->getLocale() === 'bn' ? 'আজকের মোট কাউন্ট (ডাটাবেজ সিঙ্ক)' : 'Today\'s Total Count (Synced)' }}</span>
        <h3 class="font-outfit font-black text-2xl text-emerald-800 dark:text-emerald-300 mt-1" x-text="totalSaved"></h3>
    </div>

    <!-- Interactive Toggles (Sound, Vibration, Auto-Reset) -->
    <div class="w-full max-w-sm bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm space-y-4">
        
        <!-- Auto Reset -->
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" /></svg>
                </span>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('messages.auto_reset') }}</span>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.live="autoReset" class="sr-only peer">
                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
            </label>
        </div>

        <!-- Haptics -->
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /></svg>
                </span>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('messages.vibration') }}</span>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.live="vibeOn" class="sr-only peer">
                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
            </label>
        </div>

        <!-- Sound -->
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-2">
                <span class="p-1.5 rounded-lg bg-gray-50 dark:bg-slate-800 text-gray-500 dark:text-gray-400">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" /></svg>
                </span>
                <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('messages.sound') }}</span>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" wire:model.live="soundOn" class="sr-only peer">
                <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-slate-600 peer-checked:bg-emerald-600"></div>
            </label>
        </div>

        <!-- Manual Reset Button -->
        <button @click="resetLocalCounter()" class="w-full bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 dark:text-white py-3 rounded-xl font-bold text-xs transition">
            {{ __('messages.reset') }}
        </button>

    </div>

    <!-- Swipe-to-Count Area -->
    <div class="w-full max-w-sm bg-gradient-to-r from-emerald-900/10 to-teal-900/10 dark:from-emerald-950/20 dark:to-teal-950/20 border border-dashed border-emerald-500/30 rounded-2xl p-5 text-center cursor-pointer"
         @touchstart="handleTouchStart($event)"
         @touchend="handleTouchEnd($event)">
        <span class="text-xs font-bold text-emerald-700 dark:text-emerald-400 block mb-1">{{ __('messages.swipe_mode') }}</span>
        <p class="text-[10px] text-gray-400 dark:text-gray-500">{{ app()->getLocale() === 'bn' ? 'বাম থেকে ডানে অথবা নিচ থেকে উপরে সোয়াইপ করুন' : 'Swipe left-to-right or bottom-to-top to count' }}</p>
    </div>

    <!-- Experimental Voice Tasbih Panel -->
    <div class="w-full max-w-sm bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm flex flex-col gap-3">
        <div class="flex justify-between items-center">
            <span class="text-xs font-bold text-gray-700 dark:text-gray-300">{{ __('messages.voice_mode') }}</span>
            <button @click="toggleVoice()" :class="voiceActive ? 'bg-rose-500 text-white' : 'bg-emerald-600 text-white'" class="text-xs font-bold px-3 py-1.5 rounded-xl shadow transition">
                <span x-text="voiceActive ? 'Stop' : 'Start'"></span>
            </button>
        </div>
        <p class="text-[10px] text-gray-400 dark:text-gray-500">{{ __('messages.voice_instruction') }}</p>
        <div x-show="voiceActive" class="text-xs text-rose-500 font-semibold animate-pulse flex items-center gap-1">
            <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
            <span>Listening / কথা শুনছে...</span>
        </div>
    </div>

    <!-- Web Audio & Speech Recognition Script -->
    <script>
        function tasbihApp() {
            return {
                audioCtx: null,
                voiceActive: false,
                recognition: null,
                touchStartX: 0,
                touchStartY: 0,
                
                // Entangled properties
                count: @entangle('count'),
                totalSaved: @entangle('totalSaved'),
                zikirItemId: @entangle('zikirItemId'),
                target: 33,

                isGuest: @json(!Auth::check()),
                lang: '{{ app()->getLocale() }}',
                
                zikirItems: [],
                activeName: '',
                activeArabic: '',

                init() {
                    this.audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                    this.loadZikirItems();
                    this.updateActiveDetails();

                    // Load guest values on boot
                    if (this.isGuest) {
                        this.loadLocalTotals();
                    }

                    // Online listener for background sync
                    if (!this.isGuest) {
                        this.syncOfflineQueue();
                        window.addEventListener('online', () => {
                            this.syncOfflineQueue();
                        });
                    }
                },

                loadZikirItems() {
                    let presets = @json($zikirItems);
                    if (this.isGuest) {
                        let customLocal = JSON.parse(localStorage.getItem('guest_custom_zikir_items') || '[]');
                        this.zikirItems = [...presets, ...customLocal];
                    } else {
                        this.zikirItems = presets;
                    }
                },

                updateActiveDetails() {
                    let active = this.zikirItems.find(z => z.id == this.zikirItemId);
                    if (active) {
                        this.activeName = this.lang === 'bn' ? active.name_bn : active.name_en;
                        this.activeArabic = active.arabic_text || '';
                        this.target = active.default_target || 33;
                    }
                },

                updateZikirDetails(detail) {
                    this.activeName = this.lang === 'bn' ? detail.name_bn : detail.name_en;
                    this.activeArabic = detail.arabic_text || '';
                    this.target = detail.default_target;
                },

                onZikirChanged() {
                    this.count = 0;
                    this.updateActiveDetails();
                    if (this.isGuest) {
                        this.loadLocalTotals();
                    }
                },

                getLocalDate() {
                    let now = new Date();
                    let y = now.getFullYear();
                    let m = String(now.getMonth() + 1).padStart(2, '0');
                    let d = String(now.getDate()).padStart(2, '0');
                    return `${y}-${m}-${d}`;
                },

                loadLocalTotals() {
                    let today = this.getLocalDate();
                    let counts = JSON.parse(localStorage.getItem('guest_zikir_counts') || '[]');
                    let log = counts.find(c => c.zikir_item_id == this.zikirItemId && c.counted_at === today);
                    this.totalSaved = log ? log.count : 0;
                },

                handleCounterClick() {
                    this.playTick();
                    
                    if (this.isGuest) {
                        // Guest mode: Save locally
                        this.count++;
                        this.totalSaved++;
                        this.queueGuestIncrement();

                        if (this.count >= this.target) {
                            this.playTargetSound();
                            this.vibrate(300);
                            if (@json($autoReset)) {
                                this.count = 0;
                            }
                        } else {
                            if (@json($vibeOn)) {
                                this.vibrate(50);
                            }
                        }
                    } else {
                        // Registered mode: Call Livewire/MySQL or offline queue
                        if (navigator.onLine) {
                            @this.increment();
                        } else {
                            this.count++;
                            this.totalSaved++;
                            this.queueOfflineIncrement(); // Sync queue

                            if (this.count >= this.target) {
                                this.playTargetSound();
                                this.vibrate(300);
                                if (@json($autoReset)) {
                                    this.count = 0;
                                }
                            } else {
                                if (@json($vibeOn)) {
                                    this.vibrate(50);
                                }
                            }
                        }
                    }
                },

                queueGuestIncrement() {
                    let queue = JSON.parse(localStorage.getItem('guest_zikir_counts') || '[]');
                    let today = this.getLocalDate();
                    
                    let existing = queue.find(item => item.zikir_item_id == this.zikirItemId && item.counted_at === today);
                    if (existing) {
                        existing.count++;
                    } else {
                        queue.push({
                            zikir_item_id: this.zikirItemId,
                            count: 1,
                            counted_at: today
                        });
                    }
                    localStorage.setItem('guest_zikir_counts', JSON.stringify(queue));
                },

                resetLocalCounter() {
                    this.count = 0;
                    @this.resetCounter();
                },

                queueOfflineIncrement() {
                    let queue = JSON.parse(localStorage.getItem('offline_tasbih_queue') || '[]');
                    let today = this.getLocalDate();
                    
                    let existing = queue.find(item => item.zikir_item_id == this.zikirItemId && item.counted_at === today);
                    if (existing) {
                        existing.count++;
                    } else {
                        queue.push({
                            zikir_item_id: this.zikirItemId,
                            count: 1,
                            counted_at: today
                        });
                    }
                    localStorage.setItem('offline_tasbih_queue', JSON.stringify(queue));
                },

                async syncOfflineQueue() {
                    let queue = JSON.parse(localStorage.getItem('offline_tasbih_queue') || '[]');
                    if (queue.length === 0) return;
                    
                    try {
                        let response = await fetch('/api/sync-offline-counts', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify(queue)
                        });
                        
                        if (response.ok) {
                            localStorage.removeItem('offline_tasbih_queue');
                            @this.loadZikirItemDetails();
                            console.log("Offline counts synced!");
                        }
                    } catch (e) {
                        console.error("Failed to sync offline counts:", e);
                    }
                },

                playTick() {
                    if (!@json($soundOn)) return;
                    try {
                        let osc = this.audioCtx.createOscillator();
                        let gain = this.audioCtx.createGain();
                        osc.type = 'sine';
                        osc.frequency.setValueAtTime(800, this.audioCtx.currentTime);
                        gain.gain.setValueAtTime(0.02, this.audioCtx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.001, this.audioCtx.currentTime + 0.05);
                        osc.connect(gain);
                        gain.connect(this.audioCtx.destination);
                        osc.start();
                        osc.stop(this.audioCtx.currentTime + 0.06);
                    } catch (e) {}
                },

                playTargetSound() {
                    try {
                        let osc = this.audioCtx.createOscillator();
                        let gain = this.audioCtx.createGain();
                        osc.type = 'triangle';
                        osc.frequency.setValueAtTime(523.25, this.audioCtx.currentTime);
                        gain.gain.setValueAtTime(0.1, this.audioCtx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.001, this.audioCtx.currentTime + 0.5);
                        osc.connect(gain);
                        gain.connect(this.audioCtx.destination);
                        osc.start();
                        osc.stop(this.audioCtx.currentTime + 0.6);
                    } catch (e) {}
                },

                vibrate(duration) {
                    if (navigator.vibrate) {
                        navigator.vibrate(duration);
                    }
                },

                handleTouchStart(e) {
                    this.touchStartX = e.changedTouches[0].screenX;
                    this.touchStartY = e.changedTouches[0].screenY;
                },

                handleTouchEnd(e) {
                    let diffX = e.changedTouches[0].screenX - this.touchStartX;
                    let diffY = e.changedTouches[0].screenY - this.touchStartY;
                    
                    if (diffX > 30 || diffY < -30) {
                        this.handleCounterClick();
                    }
                },

                toggleVoice() {
                    if (this.voiceActive) {
                        this.stopVoice();
                    } else {
                        this.startVoice();
                    }
                },

                startVoice() {
                    window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                    if (!window.SpeechRecognition) {
                        showAlert("Speech recognition is not supported in this browser. Please use Chrome/Edge.");
                        return;
                    }
                    
                    this.recognition = new window.SpeechRecognition();
                    this.recognition.continuous = true;
                    this.recognition.interimResults = false;
                    this.recognition.lang = 'en-US';

                    this.recognition.onstart = () => {
                        this.voiceActive = true;
                    };

                    this.recognition.onresult = (event) => {
                        let resultText = event.results[event.results.length - 1][0].transcript.toLowerCase();
                        if (resultText.includes('subhanallah') || resultText.includes('alhamdulillah') || resultText.includes('allahu akbar') || resultText.includes('allah') || resultText.includes('akbar')) {
                            this.handleCounterClick();
                        }
                    };

                    this.recognition.onerror = () => {
                        this.stopVoice();
                    };

                    this.recognition.onend = () => {
                        if (this.voiceActive) {
                            this.recognition.start();
                        }
                    };

                    this.recognition.start();
                },

                stopVoice() {
                    this.voiceActive = false;
                    if (this.recognition) {
                        this.recognition.stop();
                    }
                }
            }
        }
    </script>
</div>
