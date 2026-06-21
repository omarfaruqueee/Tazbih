/**
 * Hasanat Tracker - Background Amal Alert Checker (Enhanced)
 */

(function () {
    // Prevent multiple initializations
    if (window.hasanatAlertCheckerLoaded) return;
    window.hasanatAlertCheckerLoaded = true;

    // Helper to get configuration
    function getAlertConfig() {
        let config = window.hasanatAlertConfig || {
            authenticated: false,
            morning: true,
            afternoon: true,
            evening: true,
            push: true,
            vibration: true,
            sound: true,
            morning_time: '07:00',
            afternoon_time: '13:30',
            evening_time: '18:30',
            ringtone: 'chime',
            volume: 50
        };

        // If guest (or not authenticated backend payload), read overrides from localStorage
        if (!config.authenticated) {
            if (localStorage.getItem('guest_alert_morning_enabled') !== null) {
                config.morning = localStorage.getItem('guest_alert_morning_enabled') !== 'false';
            }
            if (localStorage.getItem('guest_alert_afternoon_enabled') !== null) {
                config.afternoon = localStorage.getItem('guest_alert_afternoon_enabled') !== 'false';
            }
            if (localStorage.getItem('guest_alert_evening_enabled') !== null) {
                config.evening = localStorage.getItem('guest_alert_evening_enabled') !== 'false';
            }
            if (localStorage.getItem('guest_alert_push_enabled') !== null) {
                config.push = localStorage.getItem('guest_alert_push_enabled') !== 'false';
            }
            if (localStorage.getItem('guest_alert_vibration_enabled') !== null) {
                config.vibration = localStorage.getItem('guest_alert_vibration_enabled') !== 'false';
            }
            if (localStorage.getItem('guest_alert_sound_enabled') !== null) {
                config.sound = localStorage.getItem('guest_alert_sound_enabled') !== 'false';
            }
            if (localStorage.getItem('guest_alert_morning_time') !== null) {
                config.morning_time = localStorage.getItem('guest_alert_morning_time');
            }
            if (localStorage.getItem('guest_alert_afternoon_time') !== null) {
                config.afternoon_time = localStorage.getItem('guest_alert_afternoon_time');
            }
            if (localStorage.getItem('guest_alert_evening_time') !== null) {
                config.evening_time = localStorage.getItem('guest_alert_evening_time');
            }
            if (localStorage.getItem('guest_alert_ringtone') !== null) {
                config.ringtone = localStorage.getItem('guest_alert_ringtone');
            }
            if (localStorage.getItem('guest_alert_volume') !== null) {
                config.volume = parseInt(localStorage.getItem('guest_alert_volume') || '50');
            }
        }
        return config;
    }

    // Synthesize beautiful Islamic ringtones using Web Audio API (Offline & Premium)
    function playRingtone(ringtoneName, volumePercentage) {
        try {
            let AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;
            let ctx = new AudioContext();
            let vol = (parseInt(volumePercentage) || 50) / 100;
            let now = ctx.currentTime;
            
            if (ringtoneName === 'chime') {
                // Soft Chime: C5, E5, G5, C6 (harmonic chime arpeggio)
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
            } else if (ringtoneName === 'echo') {
                // Dawn Echo: Square waves with a low-pass filter to sound resonant & mellow
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
            } else if (ringtoneName === 'arpeggio') {
                // Serene Arpeggio: Rapid ascending series of frequencies using triangle waves
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
            console.error("Synthesizer error:", e);
        }
    }

    // Trigger physical vibration
    function triggerVibration() {
        if (navigator.vibrate) {
            navigator.vibrate([200, 100, 200, 100, 300]);
        }
    }

    // Trigger the actual alert
    function triggerAlert(slotKey, title, body) {
        let config = getAlertConfig();

        // 1. Play selected ringtone with configured volume
        if (config.sound) {
            playRingtone(config.ringtone, config.volume);
        }

        // 2. Trigger vibration
        if (config.vibration) {
            triggerVibration();
        }

        // 3. Show In-App Custom Modal Alert (Pass slot for Snooze functionality)
        if (typeof window.showAlert === 'function') {
            window.showAlert(body, title, slotKey);
        }

        // 4. Send Web Push Notification
        if (config.push && "Notification" in window && Notification.permission === "granted") {
            try {
                new Notification(title, {
                    body: body,
                    icon: '/icon-192.png',
                    badge: '/icon-192.png'
                });
            } catch (e) {
                // Service worker context fallback
                console.warn("Native Notification failed, falling back to Service Worker", e);
                if (navigator.serviceWorker && navigator.serviceWorker.ready) {
                    navigator.serviceWorker.ready.then(reg => {
                        reg.showNotification(title, {
                            body: body,
                            icon: '/icon-192.png',
                            badge: '/icon-192.png'
                        });
                    });
                }
            }
        }
    }

    // Main polling check loop
    function checkAlarmClock() {
        let config = getAlertConfig();
        
        let now = new Date();
        let currentHour = now.getHours();
        let currentMin = now.getMinutes();
        let y = now.getFullYear();
        let m = String(now.getMonth() + 1).padStart(2, '0');
        let d = String(now.getDate()).padStart(2, '0');
        let todayStr = `${y}-${m}-${d}`;

        // Format current time to HH:MM
        let timeStr = (currentHour < 10 ? '0' : '') + currentHour + ':' + (currentMin < 10 ? '0' : '') + currentMin;

        // Alarm times slots definition
        const slots = [
            {
                key: 'morning',
                time: config.morning_time || '07:00',
                titleBn: 'সকালের আমল ও জিকির',
                titleEn: 'Morning Dhikr Reminder',
                bodyBn: 'আসসালামু আলাইকুম। সকালের আদকার ও তাসবিহ আদায়ের উত্তম সময়। "হে ঈমানদারগণ! তোমরা আল্লাহকে অধিক স্মরণ কর এবং সকাল-সন্ধ্যা তাঁর পবিত্রতা ঘোষণা কর।" (সূরা আহযাব: ৪১-৪২)',
                bodyEn: 'Assalamu Alaikum. It is time for your morning Adhkar. "O you who believe! Remember Allah with much remembrance, and glorify Him morning and evening." (Quran 33:41-42)'
            },
            {
                key: 'afternoon',
                time: config.afternoon_time || '13:30',
                titleBn: 'দুপুর ও বিকালের আমল',
                titleEn: 'Afternoon Dhikr & Salah',
                bodyBn: 'আসসালামু আলাইকুম। যোহরের সালাত আদায় করেছেন তো? আপনার নামাজ ও প্রতিদিনের কুরআন পড়ার অগ্রগতি ট্র্যাকার চেক করুন।',
                bodyEn: 'Assalamu Alaikum. Have you prayed Dhuhr? Take a moment to log your prayers and check your daily Quran progress.'
            },
            {
                key: 'evening',
                time: config.evening_time || '18:30',
                titleBn: 'সন্ধ্যার জিকির ও আত্মপ্রতিফলন',
                titleEn: 'Evening Dhikr & Reflection',
                bodyBn: 'আসসালামু আলাইকুম। সন্ধ্যার আদকার ও আজকের দিনের ভালো মন্দের হিসাব (ডায়েরি এন্ট্রি) সম্পন্ন করুন।',
                bodyEn: 'Assalamu Alaikum. It is time for evening Adhkar and reflection journal logging.'
            }
        ];

        slots.forEach(slot => {
            // 1. Check normal schedule
            if (timeStr === slot.time && config[slot.key]) {
                let triggeredKey = todayStr + '-' + slot.key;
                let lastTriggered = localStorage.getItem('last_alert_triggered');

                if (lastTriggered !== triggeredKey) {
                    localStorage.setItem('last_alert_triggered', triggeredKey);
                    
                    let lang = document.documentElement.lang === 'bn' ? 'bn' : 'en';
                    let title = lang === 'bn' ? slot.titleBn : slot.titleEn;
                    let body = lang === 'bn' ? slot.bodyBn : slot.bodyEn;

                    triggerAlert(slot.key, title, body);
                }
            }

            // 2. Check snooze schedule
            let snoozeTime = localStorage.getItem('snooze_time_' + slot.key);
            if (snoozeTime === timeStr && config[slot.key]) {
                let snoozeTriggeredKey = todayStr + '-' + slot.key + '-snooze';
                let lastSnoozeTriggered = localStorage.getItem('last_snooze_triggered');

                if (lastSnoozeTriggered !== snoozeTriggeredKey) {
                    localStorage.setItem('last_snooze_triggered', snoozeTriggeredKey);
                    localStorage.removeItem('snooze_time_' + slot.key); // Clear snooze
                    
                    let lang = document.documentElement.lang === 'bn' ? 'bn' : 'en';
                    let title = (lang === 'bn' ? '[স্নুজ] ' : '[Snooze] ') + (lang === 'bn' ? slot.titleBn : slot.titleEn);
                    let body = lang === 'bn' ? slot.bodyBn : slot.bodyEn;

                    triggerAlert(slot.key, title, body);
                }
            }
        });
    }

    // Check alarm every 30 seconds
    setInterval(checkAlarmClock, 30000);

    // Initial check on load (delayed by 3 seconds)
    setTimeout(checkAlarmClock, 3000);
})();
