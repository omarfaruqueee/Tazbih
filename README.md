# Hasanat Tracker (Tazbih) - Spiritual Habit & Amal Tracker

Hasanat Tracker is a premium, feature-rich Islamic Habit Tracker and Tasbih Counter web application designed to help Muslims monitor and improve their daily worship, Quran recitations, habits, and reflections. Built using the modern TALL stack (Tailwind, Alpine.js, Laravel, Livewire), it offers a fast, fluid SPA experience with offline-first capabilities for guests and secure database storage for authenticated users.

---

## 🌟 Key Features

### 1. Daily Goals Dashboard
* Set customized target numbers for daily **Salah (Prayers)**, **Quran Recitation (Pages)**, and **Zikirs** (custom or preset).
* Real-time fractional progress indicators (e.g., completing 3 out of 5 prayers shows 60% completion).
* Dynamic fallback system that estimates progress based on recorded activities if no custom goals are set.

### 2. Comprehensive Worship Analytics & Statistics
* **GitHub-Style Contribution Heatmap**: Visualizes daily spiritual activity levels.
* **Range-Based Analytics**: Filter stats across predefined ranges (Today, Yesterday, Last 7 Days, Last 30 Days, Last Year) or set a custom start/end date span.
* Integrated metrics tracking Salah statuses, Quran pages read, habits logged, and total zikirs performed.

### 3. Spiritual Diary & Reflection Journal
* Premium mood selector with 5 distinct states: Vibrant, Peaceful, Grateful, Tired, and Low.
* Instant background auto-save triggered debounced as you type (1 second delay), on textarea blur, mood changes, and quick prompt selections.
* **Quick Prompts Templates**: Outlines for Gratitude, Growth, and Repentance.
* Vertical reflections timeline showing history logs.

### 4. Interactive Tasbih Counter
* Audio feedback using synthesized frequencies.
* Haptic feedback vibrations (for mobile devices).
* Target completion rings and loop targets.
* Add custom Zikirs with custom Bangla/English names, Arabic script, targets, and icons.

### 5. Amal Alert Reminders
* Offline-first alerts using the Web Audio API (synthesizes chime, echo, and arpeggio ringtones locally).
* Customized timers for Morning, Afternoon, and Evening slots.
* Volume controls and test audio trigger button.
* **Snooze Support**: Postpone reminders by 10 minutes.
* Inspiring Quranic verses and Hadith citations displayed inside notifications.

### 6. Premium Admin Control Center (Dashboard)
* **High-Security Gatekeeping**: Access restricted to the admin email `omarfaruuuk@gmail.com` using strict middleware validation.
* **Live Traffic Analytics**: Graph plotting page views and visitor trends for the last 7 days using Chart.js.
* **User Accounts Board**: Searchable and paginated table showing online status, registration dates, and worship statistics.
* **Spiritual Profile Auditor**: Detailed modal showing a user's logged history across all activities.
* **Feature Access Permissions**: Turn off/on specific modules (Salah Tracker, Quran Tracker, Tasbih Counter, Habit Tracker, Journal, Daily Goals) for any user directly from the dashboard with instant cache invalidation.
* Account deletion controls to remove inactive users.

### 7. Guest Mode & Local Syncing
* Full tracking capability for guest users using local browser storage (`localStorage`).
* One-click migration system: Automatically uploads guest progress and merges it with the database when registering or logging in.

### 8. Premium UI/UX & Aesthetics
* Glassmorphic designs, vibrant gradients, and micro-animations.
* Instant Dark Mode with class-based toggling and an inline script that prevents "light flashes" on page load.
* Single Page Application (SPA) transitions powered by Livewire's `wire:navigate`.

---

## 🛠️ Technology Stack

* **Backend Framework**: Laravel 11.x
* **Frontend Reactive Layer**: Livewire 3.x & Alpine.js
* **Styles**: Tailwind CSS (DarkMode: class)
* **Charts & Analytics**: Chart.js
* **Database**: MySQL / SQLite (Database driver-agnostic query design)
