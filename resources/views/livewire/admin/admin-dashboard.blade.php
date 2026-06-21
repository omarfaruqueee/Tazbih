<div class="space-y-6" x-data="{ activeTab: @entangle('activeTab') }">
    <!-- Success/Error Toast Notifications -->
    @if (session()->has('admin_success'))
        <div class="p-4 mb-4 text-sm text-emerald-800 rounded-2xl bg-emerald-50 dark:bg-emerald-950/30 dark:text-emerald-400 border border-emerald-500/20 shadow-sm flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-emerald-600 dark:text-emerald-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span class="font-semibold">{{ session('admin_success') }}</span>
            </div>
        </div>
    @endif

    @if (session()->has('admin_error'))
        <div class="p-4 mb-4 text-sm text-rose-800 rounded-2xl bg-rose-50 dark:bg-rose-950/30 dark:text-rose-400 border border-rose-500/20 shadow-sm flex items-center justify-between" role="alert">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5 text-rose-600 dark:text-rose-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                </svg>
                <span class="font-semibold">{{ session('admin_error') }}</span>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Sidebar Navigation -->
        <div class="lg:col-span-1 space-y-4">
            <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm backdrop-blur-md sticky top-24">
                <h3 class="text-xs font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest px-3 mb-4">Control Panels</h3>
                <nav class="space-y-1.5">
                    <button wire:click="$set('activeTab', 'overview')" 
                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all text-left {{ $activeTab === 'overview' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-950 dark:hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                        <span>Overview & Analytics</span>
                    </button>
                    <button wire:click="$set('activeTab', 'users')" 
                            class="w-full flex items-center gap-3 px-4 py-3.5 rounded-2xl text-sm font-bold transition-all text-left {{ $activeTab === 'users' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/50 hover:text-slate-950 dark:hover:text-white' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.109A11.978 11.978 0 0 1 12 20.25a11.98 11.98 0 0 1-3-1.013V19.12c0-1.113.285-2.16.786-3.07M12 15.75a6.002 6.002 0 0 0-4.121 1.608 4.125 4.125 0 0 0 7.533 2.493M12 15.75c.346 0 .684-.029 1.013-.086M12 12a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm0 0a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" />
                        </svg>
                        <span>User Accounts Board</span>
                    </button>
                </nav>

                <div class="mt-8 pt-6 border-t border-slate-200 dark:border-slate-800 text-center">
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 font-extrabold text-[10px] uppercase tracking-wider">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping"></span>
                        Admin Mode Active
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Panel Content -->
        <div class="lg:col-span-3 space-y-8">
            <!-- TAB 1: OVERVIEW & ANALYTICS -->
            @if ($activeTab === 'overview')
                <!-- Stats Matrix Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Users -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total Users</span>
                            <div class="p-2 rounded-xl bg-blue-500/10 text-blue-600 dark:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-outfit font-black mt-2 text-slate-900 dark:text-white">{{ number_format($stats['total_users'] ?? 0) }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">Registered Accounts</p>
                    </div>

                    <!-- Active Sessions -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Active Now</span>
                            <div class="p-2 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-outfit font-black mt-2 text-slate-900 dark:text-white">{{ number_format($stats['active_sessions'] ?? 0) }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">Active in last 15 min</p>
                    </div>

                    <!-- Total Zikirs -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Total Zikirs</span>
                            <div class="p-2 rounded-xl bg-amber-500/10 text-amber-600 dark:text-amber-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-outfit font-black mt-2 text-slate-900 dark:text-white">{{ number_format($stats['total_zikirs'] ?? 0) }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">Zikir Counts Summed</p>
                    </div>

                    <!-- Total Salah -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Salah Logs</span>
                            <div class="p-2 rounded-xl bg-purple-500/10 text-purple-600 dark:text-purple-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a9.008 9.008 0 0 1-11.749 0A9.007 9.007 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a9.008 9.008 0 0 1 11.749 0A9.007 9.007 0 0 1 21 12Z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-outfit font-black mt-2 text-slate-900 dark:text-white">{{ number_format($stats['total_salah'] ?? 0) }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">Total Prayers Tracked</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Total Quran Pages -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Quran Progress</span>
                            <div class="p-2 rounded-xl bg-teal-500/10 text-teal-600 dark:text-teal-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-16.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-16.25v16.25" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-outfit font-black mt-2 text-slate-900 dark:text-white">{{ number_format($stats['total_quran_pages'] ?? 0) }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">Total Pages Read</p>
                    </div>

                    <!-- Total Habits Completed -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Habit Logs</span>
                            <div class="p-2 rounded-xl bg-pink-500/10 text-pink-600 dark:text-pink-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-outfit font-black mt-2 text-slate-900 dark:text-white">{{ number_format($stats['total_habits'] ?? 0) }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">Completed Habits</p>
                    </div>

                    <!-- Total Journal Entries -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Journals</span>
                            <div class="p-2 rounded-xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-16.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-16.25v16.25" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-outfit font-black mt-2 text-slate-900 dark:text-white">{{ number_format($stats['total_journals'] ?? 0) }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">Total Diary Entries</p>
                    </div>

                    <!-- Total Daily Goals -->
                    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-5 shadow-sm hover:shadow-md transition">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Daily Goals</span>
                            <div class="p-2 rounded-xl bg-cyan-500/10 text-cyan-600 dark:text-cyan-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                </svg>
                            </div>
                        </div>
                        <p class="text-3xl font-outfit font-black mt-2 text-slate-900 dark:text-white">{{ number_format($stats['total_goals'] ?? 0) }}</p>
                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-semibold">User Daily Goals Configured</p>
                    </div>
                </div>

                <!-- Page Traffic Analytics Chart -->
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl p-6 shadow-sm backdrop-blur-md"
                     x-data="{
                         chart: null,
                         initChart() {
                             const ctx = document.getElementById('trafficChart');
                             if (!ctx) return;
                             if (this.chart) {
                                 this.chart.destroy();
                             }
                             const data = @js($trafficTrend);
                             this.chart = new Chart(ctx, {
                                 type: 'line',
                                 data: {
                                     labels: data.labels || [],
                                     datasets: [{
                                         label: 'Daily Page Views',
                                         data: data.data || [],
                                         borderColor: '#10b981',
                                         backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                         borderWidth: 3,
                                         fill: true,
                                         tension: 0.35,
                                         pointBackgroundColor: '#10b981',
                                         pointHoverRadius: 7,
                                         pointRadius: 4
                                     }]
                                 },
                                 options: {
                                     responsive: true,
                                     maintainAspectRatio: false,
                                     plugins: {
                                         legend: {
                                             display: false
                                         },
                                         tooltip: {
                                             backgroundColor: '#0f172a',
                                             titleColor: '#fff',
                                             bodyColor: '#10b981',
                                             cornerRadius: 12,
                                             padding: 12
                                         }
                                     },
                                     scales: {
                                         y: {
                                             beginAtZero: true,
                                             grid: {
                                                 color: document.documentElement.classList.contains('dark') ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)'
                                             },
                                             ticks: {
                                                 color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b',
                                                 font: {
                                                     weight: 'bold'
                                                 }
                                             }
                                         },
                                         x: {
                                             grid: {
                                                 display: false
                                             },
                                             ticks: {
                                                 color: document.documentElement.classList.contains('dark') ? '#94a3b8' : '#64748b',
                                                 font: {
                                                     weight: 'bold'
                                                 }
                                             }
                                         }
                                     }
                                 }
                             });
                         }
                     }" 
                     x-init="$nextTick(() => initChart()); $watch('activeTab', () => $nextTick(() => initChart()))"
                     @chart-update.window="initChart()">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="font-outfit font-extrabold text-lg text-slate-900 dark:text-white">Live Application Traffic</h3>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Visitor session and page view analytics for the last 7 days</p>
                        </div>
                        <div class="px-3 py-1 bg-emerald-500/10 rounded-xl text-xs font-extrabold text-emerald-600 dark:text-emerald-400">
                            Chart.js Enabled
                        </div>
                    </div>
                    
                    <div class="relative h-80 w-full">
                        <canvas id="trafficChart"></canvas>
                    </div>
                </div>
            @endif

            <!-- TAB 2: USER ACCOUNTS BOARD -->
            @if ($activeTab === 'users')
                <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-3xl shadow-sm backdrop-blur-md overflow-hidden">
                    <div class="p-6 border-b border-slate-200 dark:border-slate-800 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <div>
                            <h3 class="font-outfit font-extrabold text-lg text-slate-900 dark:text-white">User Accounts Manager</h3>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400">Manage registered users, inspect histories, and remove inactive accounts</p>
                        </div>
                        
                        <!-- Search Form -->
                        <div class="relative max-w-xs w-full">
                            <input wire:model.live.debounce.300ms="searchUser" 
                                   type="text" 
                                   placeholder="Search by name or email..." 
                                   class="w-full pl-9 pr-4 py-2 text-xs font-semibold rounded-xl bg-slate-100 hover:bg-slate-200/70 focus:bg-white dark:bg-slate-800 dark:hover:bg-slate-700/70 dark:focus:bg-slate-950 text-slate-900 dark:text-white border border-transparent focus:border-emerald-500 transition outline-none" />
                            <div class="absolute left-3 top-2.5 text-slate-400 pointer-events-none">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.637 10.637Z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- User Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-800/40 text-slate-400 dark:text-slate-500 text-[10px] font-black uppercase tracking-wider border-b border-slate-200 dark:border-slate-800">
                                    <th class="px-6 py-4">User Info</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4">Registered At</th>
                                    <th class="px-6 py-4 text-center">Zikirs</th>
                                    <th class="px-6 py-4 text-center">Salahs</th>
                                    <th class="px-6 py-4 text-center">Quran Pages</th>
                                    <th class="px-6 py-4 text-center">Journals</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800/40 text-xs font-semibold text-slate-700 dark:text-slate-300">
                                @forelse($usersList as $userItem)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/20 transition-all">
                                        <td class="px-6 py-4">
                                            <div>
                                                <p class="font-bold text-slate-900 dark:text-white">{{ $userItem['name'] }}</p>
                                                <p class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold">{{ $userItem['email'] }}</p>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            @if ($userItem['is_online'])
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[10px] font-extrabold uppercase">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    Online
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-slate-500/10 text-slate-500 dark:text-slate-400 text-[10px] font-extrabold uppercase">
                                                    Offline
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-slate-500 dark:text-slate-400">
                                            {{ $userItem['created_at'] }}
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-slate-900 dark:text-white">
                                            {{ number_format($userItem['zikir_count']) }}
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-slate-900 dark:text-white">
                                            {{ number_format($userItem['salah_count']) }}
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-slate-900 dark:text-white">
                                            {{ number_format($userItem['quran_pages']) }}
                                        </td>
                                        <td class="px-6 py-4 text-center font-bold text-slate-900 dark:text-white">
                                            {{ number_format($userItem['journal_count']) }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <button wire:click="selectUser({{ $userItem['id'] }})"
                                                        class="p-2 text-emerald-600 hover:text-emerald-800 dark:text-emerald-400 dark:hover:text-emerald-200 hover:bg-emerald-500/10 rounded-xl transition shadow-inner">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                </button>
                                                
                                                @if ($userItem['email'] !== 'omarfaruuuk@gmail.com')
                                                    <button onclick="confirm('Are you sure you want to permanently delete this user and all associated records? This cannot be undone.') || event.stopImmediatePropagation()"
                                                            wire:click="deleteUser({{ $userItem['id'] }})"
                                                            class="p-2 text-rose-600 hover:text-rose-800 dark:text-rose-400 dark:hover:text-rose-200 hover:bg-rose-500/10 rounded-xl transition shadow-inner">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-6 py-12 text-center text-slate-400 dark:text-slate-500 font-bold">
                                            No user accounts match your search filters.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if ($users->hasPages())
                        <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-800">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>

    <!-- User History & Audit Details Modal -->
    @if ($selectedUserDetail)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" x-data="{ auditTab: 'zikir' }">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-slate-950/70 backdrop-blur-sm transition-opacity" wire:click="closeUserModal"></div>

            <!-- Modal Content Wrapper -->
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-3xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl p-6 space-y-6">
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-4">
                        <div>
                            <div class="flex items-center gap-3">
                                <h3 class="font-outfit font-black text-xl text-slate-900 dark:text-white" id="modal-title">
                                    {{ $selectedUserDetail['name'] }}
                                </h3>
                                <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] text-slate-500 dark:text-slate-400 font-bold">
                                    UID: {{ $selectedUserDetail['id'] }}
                                </span>
                            </div>
                            <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 mt-0.5">
                                {{ $selectedUserDetail['email'] }} &bull; Account Created: {{ $selectedUserDetail['created_at'] }}
                            </p>
                        </div>
                        <button wire:click="closeUserModal" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- User Lifetime Statistics Grid -->
                    <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                        <div class="p-3 bg-slate-50 dark:bg-slate-850 rounded-2xl text-center border border-slate-100 dark:border-slate-800/40">
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-wider">Zikirs</p>
                            <p class="text-base font-outfit font-black text-amber-600 dark:text-amber-400 mt-1">{{ number_format($selectedUserDetail['zikir_sum']) }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-850 rounded-2xl text-center border border-slate-100 dark:border-slate-800/40">
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-wider">Salahs</p>
                            <p class="text-base font-outfit font-black text-purple-600 dark:text-purple-400 mt-1">{{ number_format($selectedUserDetail['salah_count']) }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-850 rounded-2xl text-center border border-slate-100 dark:border-slate-800/40">
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-wider">Quran Pages</p>
                            <p class="text-base font-outfit font-black text-teal-600 dark:text-teal-400 mt-1">{{ number_format($selectedUserDetail['quran_pages']) }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-850 rounded-2xl text-center border border-slate-100 dark:border-slate-800/40">
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-wider">Habits</p>
                            <p class="text-base font-outfit font-black text-pink-600 dark:text-pink-400 mt-1">{{ number_format($selectedUserDetail['habit_count']) }}</p>
                        </div>
                        <div class="p-3 bg-slate-50 dark:bg-slate-850 rounded-2xl text-center border border-slate-100 dark:border-slate-800/40 col-span-2 md:col-span-1">
                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-black uppercase tracking-wider">Diary logs</p>
                            <p class="text-base font-outfit font-black text-indigo-600 dark:text-indigo-400 mt-1">{{ number_format($selectedUserDetail['journal_count']) }}</p>
                        </div>
                    </div>

                    <!-- Feature Access Control Panel -->
                    <div class="bg-slate-50 dark:bg-slate-800/40 border border-slate-200 dark:border-slate-800/75 rounded-3xl p-5 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200 dark:border-slate-850 pb-2">
                            <h4 class="font-outfit font-extrabold text-xs uppercase text-slate-500 dark:text-slate-400 tracking-wider">Feature Access Permissions</h4>
                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold">Toggled features will be active for user</span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 pt-1">
                            <!-- Salah Tracker -->
                            <label class="relative flex items-center justify-between p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/60 cursor-pointer hover:border-emerald-500/30 transition">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Salah Tracker</span>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold">Daily Prayers logging</span>
                                </div>
                                <input type="checkbox" 
                                       wire:click="toggleFeature('salah')" 
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800" 
                                       {{ !in_array('salah', $selectedUserDetail['disabled_features'] ?? []) ? 'checked' : '' }} />
                            </label>

                            <!-- Quran Tracker -->
                            <label class="relative flex items-center justify-between p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/60 cursor-pointer hover:border-emerald-500/30 transition">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Quran Tracker</span>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold">Reading progress</span>
                                </div>
                                <input type="checkbox" 
                                       wire:click="toggleFeature('quran')" 
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800" 
                                       {{ !in_array('quran', $selectedUserDetail['disabled_features'] ?? []) ? 'checked' : '' }} />
                            </label>

                            <!-- Tasbih Counter -->
                            <label class="relative flex items-center justify-between p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/60 cursor-pointer hover:border-emerald-500/30 transition">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Tasbih Counter</span>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold">Zikir counting engine</span>
                                </div>
                                <input type="checkbox" 
                                       wire:click="toggleFeature('tasbih')" 
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800" 
                                       {{ !in_array('tasbih', $selectedUserDetail['disabled_features'] ?? []) ? 'checked' : '' }} />
                            </label>

                            <!-- Habit Tracker -->
                            <label class="relative flex items-center justify-between p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/60 cursor-pointer hover:border-emerald-500/30 transition">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Habit Tracker</span>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold">Islamic daily habits</span>
                                </div>
                                <input type="checkbox" 
                                       wire:click="toggleFeature('habits')" 
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800" 
                                       {{ !in_array('habits', $selectedUserDetail['disabled_features'] ?? []) ? 'checked' : '' }} />
                            </label>

                            <!-- Reflection Journal -->
                            <label class="relative flex items-center justify-between p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/60 cursor-pointer hover:border-emerald-500/30 transition">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Reflection Journal</span>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold">Daily spiritual diary</span>
                                </div>
                                <input type="checkbox" 
                                       wire:click="toggleFeature('journal')" 
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800" 
                                       {{ !in_array('journal', $selectedUserDetail['disabled_features'] ?? []) ? 'checked' : '' }} />
                            </label>

                            <!-- Daily Goals -->
                            <label class="relative flex items-center justify-between p-3 rounded-2xl bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/60 cursor-pointer hover:border-emerald-500/30 transition">
                                <div class="flex flex-col">
                                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200">Daily Goals</span>
                                    <span class="text-[9px] text-slate-400 dark:text-slate-500 font-semibold">Goal configuration</span>
                                </div>
                                <input type="checkbox" 
                                       wire:click="toggleFeature('goals')" 
                                       class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300 dark:border-slate-700 bg-slate-100 dark:bg-slate-800" 
                                       {{ !in_array('goals', $selectedUserDetail['disabled_features'] ?? []) ? 'checked' : '' }} />
                            </label>
                        </div>
                    </div>

                    <!-- Tabbed Auditing Section -->
                    <div class="space-y-4">
                        <!-- Tabs Header -->
                        <div class="flex border-b border-slate-200 dark:border-slate-800">
                            <button @click="auditTab = 'zikir'" :class="auditTab === 'zikir' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-350'" class="flex-1 py-2 text-xs font-bold border-b-2 text-center transition">
                                Recent Zikirs
                            </button>
                            <button @click="auditTab = 'salah'" :class="auditTab === 'salah' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-350'" class="flex-1 py-2 text-xs font-bold border-b-2 text-center transition">
                                Recent Salah
                            </button>
                            <button @click="auditTab = 'quran'" :class="auditTab === 'quran' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-350'" class="flex-1 py-2 text-xs font-bold border-b-2 text-center transition">
                                Recent Quran
                            </button>
                            <button @click="auditTab = 'habits'" :class="auditTab === 'habits' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-350'" class="flex-1 py-2 text-xs font-bold border-b-2 text-center transition">
                                Recent Habits
                            </button>
                            <button @click="auditTab = 'journal'" :class="auditTab === 'journal' ? 'border-emerald-500 text-emerald-600 dark:text-emerald-400' : 'border-transparent text-slate-500 hover:text-slate-700 dark:hover:text-slate-350'" class="flex-1 py-2 text-xs font-bold border-b-2 text-center transition">
                                Diary Entries
                            </button>
                        </div>

                        <!-- Tabs Content Area -->
                        <div class="h-64 overflow-y-auto text-xs">
                            <!-- TAB: ZIKIR -->
                            <div x-show="auditTab === 'zikir'" class="space-y-2">
                                @forelse($selectedUserDetail['recent_zikir'] as $z)
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/35 rounded-xl border border-slate-100 dark:border-slate-800 flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white">
                                                {{ $z['zikir_item']['name_bn'] ?? '' }} ({{ $z['zikir_item']['name_en'] ?? '' }})
                                            </p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold mt-0.5">
                                                {{ $z['counted_at'] }}
                                            </p>
                                        </div>
                                        <p class="text-sm font-outfit font-black text-emerald-600 dark:text-emerald-400">+{{ $z['count'] }}</p>
                                    </div>
                                @empty
                                    <p class="text-center py-12 text-slate-400 dark:text-slate-500 font-bold">No recent zikir counts found.</p>
                                @endforelse
                            </div>

                            <!-- TAB: SALAH -->
                            <div x-show="auditTab === 'salah'" class="space-y-2">
                                @forelse($selectedUserDetail['recent_salah'] as $s)
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/35 rounded-xl border border-slate-100 dark:border-slate-800 flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white uppercase tracking-wider text-[10px]">
                                                {{ $s['prayer_name'] }}
                                            </p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold mt-0.5">
                                                {{ $s['date'] }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $s['status'] === 'jamaat' ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : ($s['status'] === 'alone' ? 'bg-blue-500/10 text-blue-600 dark:text-blue-400' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400') }}">
                                                {{ $s['status'] }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center py-12 text-slate-400 dark:text-slate-500 font-bold">No recent salah logs found.</p>
                                @endforelse
                            </div>

                            <!-- TAB: QURAN -->
                            <div x-show="auditTab === 'quran'" class="space-y-2">
                                @forelse($selectedUserDetail['recent_quran'] as $q)
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/35 rounded-xl border border-slate-100 dark:border-slate-800 flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white">
                                                {{ $q['para_completed'] ? 'Para Completed: ' . $q['para_completed'] : 'Quran Progress' }}
                                            </p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold mt-0.5">
                                                {{ $q['date'] }}
                                            </p>
                                        </div>
                                        <p class="text-sm font-outfit font-black text-teal-600 dark:text-teal-400">{{ $q['pages_read'] }} Pages</p>
                                    </div>
                                @empty
                                    <p class="text-center py-12 text-slate-400 dark:text-slate-500 font-bold">No recent Quran progress found.</p>
                                @endforelse
                            </div>

                            <!-- TAB: HABITS -->
                            <div x-show="auditTab === 'habits'" class="space-y-2">
                                @forelse($selectedUserDetail['recent_habits'] as $h)
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/35 rounded-xl border border-slate-100 dark:border-slate-800 flex justify-between items-center">
                                        <div>
                                            <p class="font-bold text-slate-900 dark:text-white">
                                                {{ ucfirst(str_replace('_', ' ', $h['habit_type'] ?? 'Habit Log')) }}
                                            </p>
                                            <p class="text-[10px] text-slate-400 dark:text-slate-500 font-semibold mt-0.5">
                                                {{ $h['date'] }}
                                            </p>
                                        </div>
                                        <div>
                                            <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase {{ $h['completed'] ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-rose-500/10 text-rose-600 dark:text-rose-400' }}">
                                                {{ $h['completed'] ? 'Completed' : 'Missed' }}
                                            </span>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-center py-12 text-slate-400 dark:text-slate-500 font-bold">No recent habit history found.</p>
                                @endforelse
                            </div>

                            <!-- TAB: DIARY JOURNAL -->
                            <div x-show="auditTab === 'journal'" class="space-y-3">
                                @forelse($selectedUserDetail['recent_journal'] as $j)
                                    <div class="p-3 bg-slate-50 dark:bg-slate-800/35 rounded-xl border border-slate-100 dark:border-slate-800 space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] text-slate-400 dark:text-slate-500 font-black">
                                                {{ $j['date'] }}
                                            </span>
                                            @if ($j['mood'])
                                                <span class="px-2 py-0.5 rounded-full bg-slate-100 dark:bg-slate-800 text-[10px] uppercase font-bold text-slate-600 dark:text-slate-350">
                                                    Mood: {{ $j['mood'] }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-slate-800 dark:text-slate-200 leading-relaxed">
                                            {{ $j['reflection_text'] }}
                                        </p>
                                    </div>
                                @empty
                                    <p class="text-center py-12 text-slate-400 dark:text-slate-500 font-bold">No diary entries found.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Modal Actions Footer -->
                    <div class="flex flex-col sm:flex-row justify-between items-center gap-3 border-t border-slate-100 dark:border-slate-800 pt-4">
                        @if ($selectedUserDetail['email'] !== 'omarfaruuuk@gmail.com')
                            <button onclick="confirm('Are you absolutely sure you want to permanently delete this user and all their records? This cannot be undone!') || event.stopImmediatePropagation()"
                                    wire:click="deleteUser({{ $selectedUserDetail['id'] }})"
                                    class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs shadow-md shadow-rose-600/10 transition active:scale-95 flex items-center justify-center gap-1.5">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                                <span>Delete User Account</span>
                            </button>
                        @else
                            <span class="text-[10px] font-extrabold text-amber-500 uppercase tracking-widest bg-amber-500/10 px-3 py-1 rounded-lg">
                                Admin Account Protected
                            </span>
                        @endif

                        <button wire:click="closeUserModal"
                                class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 border border-slate-200 dark:border-slate-750 font-bold text-xs transition active:scale-95">
                            Close Auditor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
