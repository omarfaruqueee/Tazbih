<div class="space-y-6 pb-16" x-data="recordsApp()" x-init="init()">
    
    <!-- Top Date Picker Navigator -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-4 shadow-sm flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="p-2 rounded-xl bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
            </span>
            <span class="text-sm font-bold text-gray-700 dark:text-gray-200">{{ app()->getLocale() === 'bn' ? 'তারিখ নির্বাচন করুন' : 'Log Date' }}</span>
        </div>
        <input type="date" x-model="selectedDate" @change="onDateChanged()" class="bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
    </div>

    <!-- Zikir Breakdown & Stats Section -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100">
                {{ app()->getLocale() === 'bn' ? 'আমল ও জিকির পরিসংখ্যান' : 'Amal & Zikir Statistics' }}
            </h3>
            <span class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                </svg>
            </span>
        </div>

        <!-- Range Tabs -->
        <div class="flex flex-wrap gap-1.5 bg-gray-50 dark:bg-slate-800/60 p-1 rounded-xl">
            <button type="button" @click="changeZikirRange('today')" :class="zikirRange === 'today' ? 'bg-emerald-600 text-white font-bold' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="flex-1 text-[10px] sm:text-xs py-1.5 px-2 rounded-lg transition text-center">
                {{ app()->getLocale() === 'bn' ? 'আজ' : 'Today' }}
            </button>
            <button type="button" @click="changeZikirRange('yesterday')" :class="zikirRange === 'yesterday' ? 'bg-emerald-600 text-white font-bold' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="flex-1 text-[10px] sm:text-xs py-1.5 px-2 rounded-lg transition text-center">
                {{ app()->getLocale() === 'bn' ? 'গতকাল' : 'Yesterday' }}
            </button>
            <button type="button" @click="changeZikirRange('week')" :class="zikirRange === 'week' ? 'bg-emerald-600 text-white font-bold' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="flex-1 text-[10px] sm:text-xs py-1.5 px-2 rounded-lg transition text-center">
                {{ app()->getLocale() === 'bn' ? 'গত ৭ দিন' : 'Week' }}
            </button>
            <button type="button" @click="changeZikirRange('month')" :class="zikirRange === 'month' ? 'bg-emerald-600 text-white font-bold' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="flex-1 text-[10px] sm:text-xs py-1.5 px-2 rounded-lg transition text-center">
                {{ app()->getLocale() === 'bn' ? 'গত ৩০ দিন' : 'Month' }}
            </button>
            <button type="button" @click="changeZikirRange('year')" :class="zikirRange === 'year' ? 'bg-emerald-600 text-white font-bold' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="flex-1 text-[10px] sm:text-xs py-1.5 px-2 rounded-lg transition text-center">
                {{ app()->getLocale() === 'bn' ? 'গত ১ বছর' : 'Year' }}
            </button>
            <button type="button" @click="changeZikirRange('custom')" :class="zikirRange === 'custom' ? 'bg-emerald-600 text-white font-bold' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400'" class="flex-1 text-[10px] sm:text-xs py-1.5 px-2 rounded-lg transition text-center">
                {{ app()->getLocale() === 'bn' ? 'তারিখ' : 'Date' }}
            </button>
        </div>

        <!-- Custom Date Range Picker -->
        <div x-show="zikirRange === 'custom'" class="flex items-center gap-3 p-3 bg-gray-50/50 dark:bg-slate-800/40 rounded-xl border border-gray-100 dark:border-slate-800/60 animate-fadeIn">
            <div class="flex-1">
                <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1">
                    {{ app()->getLocale() === 'bn' ? 'শুরু তারিখ' : 'Start Date' }}
                </label>
                <input type="date" x-model="startDate" @change="onCustomRangeChanged()" class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
            </div>
            <div class="flex-1">
                <label class="block text-[10px] font-bold text-gray-400 dark:text-gray-500 mb-1">
                    {{ app()->getLocale() === 'bn' ? 'শেষ তারিখ' : 'End Date' }}
                </label>
                <input type="date" x-model="endDate" @change="onCustomRangeChanged()" class="w-full bg-white dark:bg-slate-900 border border-gray-200 dark:border-slate-700 rounded-lg px-2.5 py-1.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
            </div>
        </div>

        <!-- Statistics Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-2">
            
            <!-- Column 1: Salah & Quran Stats -->
            <div class="space-y-5">
                <!-- Salah Stats Card -->
                <div x-show="salahEnabled" class="bg-gray-50/50 dark:bg-slate-900/50 rounded-xl p-4 border border-gray-100 dark:border-slate-800/80 space-y-3">
                    <h4 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-emerald-500">
                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12.75 6a.75.75 0 0 0-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 0 0 0-1.5h-3.75V6Z" clip-rule="evenodd" />
                        </svg>
                        {{ app()->getLocale() === 'bn' ? 'সালাত / নামাজ আদায়' : 'Salah Performance' }}
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="p-2 bg-white dark:bg-slate-900 rounded-lg border border-gray-100 dark:border-slate-800">
                            <div class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">{{ __('messages.jamaat') }}</div>
                            <div class="text-lg font-black text-emerald-600 dark:text-emerald-400 font-outfit" x-text="salahStats.jamaat">0</div>
                        </div>
                        <div class="p-2 bg-white dark:bg-slate-900 rounded-lg border border-gray-100 dark:border-slate-800">
                            <div class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">{{ __('messages.alone') }}</div>
                            <div class="text-lg font-black text-amber-500 dark:text-amber-400 font-outfit" x-text="salahStats.alone">0</div>
                        </div>
                        <div class="p-2 bg-white dark:bg-slate-900 rounded-lg border border-gray-100 dark:border-slate-800">
                            <div class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">{{ __('messages.qaza') }}</div>
                            <div class="text-lg font-black text-blue-500 dark:text-blue-400 font-outfit" x-text="salahStats.qaza">0</div>
                        </div>
                        <div class="p-2 bg-white dark:bg-slate-900 rounded-lg border border-gray-100 dark:border-slate-800">
                            <div class="text-[10px] text-gray-500 dark:text-gray-400 font-bold">{{ __('messages.missed') }}</div>
                            <div class="text-lg font-black text-rose-500 dark:text-rose-400 font-outfit" x-text="salahStats.missed">0</div>
                        </div>
                    </div>
                </div>

                <!-- Quran Stats Card -->
                <div x-show="quranEnabled" class="bg-gray-50/50 dark:bg-slate-900/50 rounded-xl p-4 border border-gray-100 dark:border-slate-800/80 space-y-3">
                    <h4 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-amber-500">
                            <path d="M11.25 4.533A9.707 9.707 0 0 0 6 3a9.735 9.735 0 0 0-3.25.555.75.75 0 0 0-.5.707v14.25a.75.75 0 0 0 .95.723A8.24 8.24 0 0 1 6 18c2.205 0 4.25.866 5.25 2.407 1.004-1.54 3.045-2.407 5.25-2.407a8.24 8.24 0 0 1 2.8.53.75.75 0 0 0 .95-.723V4.262a.75.75 0 0 0-.5-.707A9.735 9.735 0 0 0 18 3a9.707 9.707 0 0 0-5.25 1.533Z" />
                        </svg>
                        {{ app()->getLocale() === 'bn' ? 'কুরআন তিলাওয়াত' : 'Quran Recitation' }}
                    </h4>
                    
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-gray-100 dark:border-slate-800 flex flex-col justify-center items-center">
                            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold mb-1">{{ app()->getLocale() === 'bn' ? 'পঠিত পৃষ্ঠা' : 'Pages Read' }}</span>
                            <span class="text-xl font-black text-amber-600 dark:text-amber-500 font-outfit" x-text="quranStats.pages">0</span>
                        </div>
                        <div class="p-3 bg-white dark:bg-slate-900 rounded-lg border border-gray-100 dark:border-slate-800 flex flex-col justify-center items-center">
                            <span class="text-[10px] text-gray-500 dark:text-gray-400 font-bold mb-1">{{ app()->getLocale() === 'bn' ? 'সর্বশেষ পারা' : 'Max Para' }}</span>
                            <span class="text-xl font-black text-amber-600 dark:text-amber-500 font-outfit" x-text="quranStats.paras || '-'">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Column 2: Habits & Zikir Stats -->
            <div class="space-y-5">
                <!-- Habits Stats Card -->
                <div x-show="habitEnabled" class="bg-gray-50/50 dark:bg-slate-900/50 rounded-xl p-4 border border-gray-100 dark:border-slate-800/80 space-y-3">
                    <h4 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-emerald-500">
                            <path fill-rule="evenodd" d="M19.902 4.098a3.75 3.75 0 0 0-5.304 0l-10.5 10.5a3.75 3.75 0 0 0 0 5.304 3.75 3.75 0 0 0 5.304 0l10.5-10.5a3.75 3.75 0 0 0 0-5.304ZM18.06 5.94a1.75 1.75 0 0 1 0 2.475l-1.06 1.06-2.475-2.475 1.06-1.06a1.75 1.75 0 0 1 2.475 0Zm-4.596 4.596 2.475 2.475-7.969 7.969a1.75 1.75 0 0 1-2.475 0 1.75 1.75 0 0 1 0-2.475l7.969-7.969Z" clip-rule="evenodd" />
                        </svg>
                        {{ app()->getLocale() === 'bn' ? 'সুন্নাত ও অভ্যাসসমূহ' : 'Sunnah & Habits' }}
                    </h4>
                    
                    <div class="divide-y divide-gray-100 dark:divide-slate-800/60 text-xs font-semibold">
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600 dark:text-gray-400">{{ app()->getLocale() === 'bn' ? 'তাহাজ্জুদ নামাজ' : 'Tahajjud' }}</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold" x-text="habitStats.tahajjud + (lang === 'bn' ? ' দিন' : ' days')">0 days</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600 dark:text-gray-400">{{ app()->getLocale() === 'bn' ? 'ইশরাক নামাজ' : 'Ishraq' }}</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold" x-text="habitStats.ishraq + (lang === 'bn' ? ' দিন' : ' days')">0 days</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600 dark:text-gray-400">{{ app()->getLocale() === 'bn' ? 'দান-সদকা' : 'Sadaqah' }}</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold" x-text="habitStats.sadaqah + (lang === 'bn' ? ' দিন' : ' days')">0 days</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600 dark:text-gray-400">{{ app()->getLocale() === 'bn' ? 'নফল রোজা' : 'Fasting' }}</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold" x-text="habitStats.fasting + (lang === 'bn' ? ' দিন' : ' days')">0 days</span>
                        </div>
                        <div class="flex justify-between py-2">
                            <span class="text-gray-600 dark:text-gray-400">{{ app()->getLocale() === 'bn' ? 'কুরআন তিলাওয়াত' : 'Quran Recitation' }}</span>
                            <span class="text-emerald-600 dark:text-emerald-400 font-bold" x-text="habitStats.quran_tilawah + (lang === 'bn' ? ' দিন' : ' days')">0 days</span>
                        </div>
                    </div>
                </div>

                <!-- Zikir Breakdown -->
                <div class="bg-gray-50/50 dark:bg-slate-900/50 rounded-xl p-4 border border-gray-100 dark:border-slate-800/80 space-y-3">
                    <h4 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider flex items-center gap-1.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-teal-500">
                            <path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12Zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 0 1-.984-1.14c1.44-1.257 3.773-1.257 5.213 0a.75.75 0 0 1-.974 1.14Zm-3.255 7.834c.89.777 2.366.777 3.255 0a.75.75 0 1 1 .984 1.14c-1.44 1.257-3.773 1.257-5.213 0a.75.75 0 1 1 .974-1.14Z" clip-rule="evenodd" />
                        </svg>
                        {{ app()->getLocale() === 'bn' ? 'জিকির ও তাসবিহ হিসাব' : 'Zikir & Tasbih' }}
                    </h4>
                    
                    <div class="space-y-3 max-h-60 overflow-y-auto pr-1">
                        <template x-for="item in zikirBreakdown" :key="item.id">
                            <div class="space-y-1">
                                <div class="flex justify-between items-center text-xs">
                                    <span class="font-bold text-gray-700 dark:text-gray-300" x-text="lang === 'bn' ? item.name_bn : item.name_en"></span>
                                    <span class="font-black text-emerald-600 dark:text-emerald-400 font-outfit" x-text="item.count + (lang === 'bn' ? ' বার' : ' times')"></span>
                                </div>
                                <div class="w-full bg-gray-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden shadow-inner">
                                    <div class="bg-gradient-to-r from-emerald-500 to-teal-500 h-2 rounded-full transition-all duration-500" 
                                         :style="'width: ' + Math.min(100, (item.count / (zikirRange === 'today' || zikirRange === 'yesterday' || zikirRange === 'custom' ? 33 : (zikirRange === 'week' ? 231 : (zikirRange === 'month' ? 990 : 12000)))) * 100) + '%'">
                                    </div>
                                </div>
                            </div>
                        </template>

                        <template x-if="zikirBreakdown.length === 0">
                            <div class="py-4 text-center text-xs text-gray-400 dark:text-gray-500 font-medium">
                                {{ app()->getLocale() === 'bn' ? 'এই সময়ে কোনো জিকির পাওয়া হয়নি।' : 'No zikir logs found for this period.' }}
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Salah/Namaz Tracker Section -->
    <div x-show="salahEnabled" class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100">{{ __('messages.salah_tracker') }}</h3>
            <span x-show="showSalahToast" class="text-xs text-emerald-600 font-bold animate-pulse">{{ __('messages.save') }}</span>
        </div>
        
        <div class="divide-y divide-gray-100 dark:divide-slate-800">
            <template x-for="prayer in prayers" :key="prayer.key">
                <div class="flex items-center justify-between py-3">
                    <span class="text-sm font-bold text-gray-700 dark:text-gray-300" x-text="lang === 'bn' ? prayer.labelBn : prayer.labelEn"></span>
                    <select :value="salahStatuses[prayer.key]" @change="onSalahChanged(prayer.key, $event.target.value)" class="bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-1.5 text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
                        <option value="jamaat">{{ __('messages.jamaat') }}</option>
                        <option value="alone">{{ __('messages.alone') }}</option>
                        <option value="qaza">{{ __('messages.qaza') }}</option>
                        <option value="missed">{{ __('messages.missed') }}</option>
                    </select>
                </div>
            </template>
        </div>
    </div>

    <!-- Quran Reading Tracker Section -->
    <div x-show="quranEnabled" class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-4">
        <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100">{{ __('messages.quran_tracker') }}</h3>
        
        <span x-show="showQuranToast" class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 px-3 py-2 rounded-xl text-xs font-bold block">
            {{ app()->getLocale() === 'bn' ? 'কুরআন প্রোগ্রেস সংরক্ষিত হয়েছে!' : 'Quran progress saved!' }}
        </span>

        <form @submit.prevent="saveQuranLog()" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.pages_read') }}</label>
                    <input type="number" x-model="quranPages" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.para_completed') }}</label>
                    <input type="number" x-model="quranPara" placeholder="1-30" min="1" max="30" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500 mb-1">{{ __('messages.monthly_target_pages') }}</label>
                <input type="number" x-model="quranMonthlyTarget" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white">
            </div>

            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-xs shadow transition">
                {{ app()->getLocale() === 'bn' ? 'কুরআন লগ সেভ করুন' : 'Save Quran Log' }}
            </button>
        </form>

        <hr class="border-gray-100 dark:border-slate-800 my-4">

        <!-- 30 Paras progress bar -->
        <div class="space-y-1.5">
            <div class="flex justify-between items-center text-xs font-semibold text-gray-500 dark:text-gray-400">
                <span>{{ __('messages.quran_progress_bar') }}</span>
                <span class="font-bold text-emerald-600 dark:text-emerald-400"><span x-text="quranParaPercent"></span>% (<span x-text="quranPara || 0"></span>/30)</span>
            </div>
            <div class="w-full bg-gray-100 dark:bg-slate-800 rounded-full h-3 overflow-hidden shadow-inner">
                <div class="bg-gradient-to-r from-amber-400 to-amber-500 h-3 rounded-full transition-all duration-500" :style="'width: ' + quranParaPercent + '%'"></div>
            </div>
            <div class="text-[10px] text-gray-400 dark:text-gray-500 text-right mt-1">
                <span x-text="lang === 'bn' ? 'সর্বমোট পঠিত পৃষ্ঠা: ' + quranTotalPagesRead : 'Total Pages Read: ' + quranTotalPagesRead"></span>
            </div>
        </div>
    </div>

    <!-- Islamic Habit Tracker Section -->
    <div x-show="habitEnabled" class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-4">
        <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100">{{ __('messages.habit_tracker') }}</h3>
        
        <div class="space-y-3">
            <template x-for="habit in habitTypes" :key="habit.key">
                <label class="flex items-center justify-between p-3 rounded-xl border border-gray-100 dark:border-slate-800 bg-gray-50/50 dark:bg-slate-900/50 cursor-pointer hover:bg-emerald-50/20 dark:hover:bg-emerald-950/10 transition">
                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300" x-text="lang === 'bn' ? habit.labelBn : habit.labelEn"></span>
                    <input type="checkbox" :checked="habits[habit.key]" @click.prevent="toggleHabitLog(habit.key)" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 w-5 h-5 dark:bg-slate-800 dark:border-slate-700">
                </label>
            </template>
        </div>
    </div>

    <!-- Heatmap Grid -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-4">
        <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100">{{ __('messages.habit_history') }}</h3>
        
        <div class="grid grid-cols-6 gap-2 pt-2">
            <template x-for="day in heatmapGrid" :key="day.date">
                <div class="flex flex-col items-center gap-1">
                    <div :class="day.color" class="w-8 h-8 rounded-lg flex items-center justify-center text-xs font-black text-white shadow-sm transition-transform active:scale-95" 
                         :title="day.date + ' (Score: ' + day.score + ')'"
                         x-text="day.day">
                    </div>
                </div>
            </template>
        </div>
        
        <!-- Legend -->
        <div class="flex items-center gap-4 pt-2 text-[10px] text-gray-400 dark:text-gray-500 font-semibold justify-center">
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-gray-100 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded"></span> {{ app()->getLocale() === 'bn' ? 'কোনো আমল নেই' : 'None' }}</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-emerald-200 dark:bg-emerald-700 rounded"></span> {{ app()->getLocale() === 'bn' ? 'স্বল্প আমল' : 'Some' }}</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-emerald-400 dark:bg-emerald-500 rounded"></span> {{ app()->getLocale() === 'bn' ? 'মধ্যম আমল' : 'Medium' }}</span>
            <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 bg-emerald-600 rounded"></span> {{ app()->getLocale() === 'bn' ? 'পূর্ণ আমল' : 'Full' }}</span>
        </div>
    </div>

    <!-- Daily Goals Configuration Section -->
    @if (Auth::check())
        @if (auth()->user()->isFeatureDisabled('goals'))
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400 font-bold">
                    {{ app()->getLocale() === 'bn' ? 'দৈনিক লক্ষ্যমাত্রা ফিচারটি অ্যাডমিনিস্ট্রেটর দ্বারা নিষ্ক্রিয় করা হয়েছে।' : 'Daily Goals feature has been disabled by the administrator.' }}
                </p>
            </div>
        @else
            <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-5">
                <div class="flex items-center justify-between">
                    <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100">
                        {{ app()->getLocale() === 'bn' ? 'দৈনিক লক্ষ্যমাত্রা কনফিগারেশন' : 'Daily Goals Configuration' }}
                    </h3>
                    <span class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                </div>

                @if (session()->has('goal_success'))
                    <div class="bg-emerald-500 text-white px-3 py-2 rounded-xl text-xs font-bold animate-pulse">
                        {{ session('goal_success') }}
                    </div>
                @endif

                <form wire:submit.prevent="saveGoal" class="space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <!-- Goal Type -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500">{{ app()->getLocale() === 'bn' ? 'লক্ষ্যের ধরন' : 'Goal Type' }}</label>
                            <select wire:model.live="goalType" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white font-bold">
                                <option value="salah">{{ app()->getLocale() === 'bn' ? 'নামাজ' : 'Salah' }}</option>
                                <option value="quran">{{ app()->getLocale() === 'bn' ? 'কুরআন' : 'Quran' }}</option>
                                <option value="zikir">{{ app()->getLocale() === 'bn' ? 'জিকির' : 'Zikir' }}</option>
                            </select>
                        </div>

                        <!-- Zikir Selector (Only visible if type is zikir) -->
                        @if ($goalType === 'zikir')
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500">{{ app()->getLocale() === 'bn' ? 'জিকির নির্বাচন' : 'Select Zikir' }}</label>
                            <select wire:model="goalZikirItem" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white font-bold">
                                <option value="">{{ app()->getLocale() === 'bn' ? '-- জিকির বেছে নিন --' : '-- Select Zikir --' }}</option>
                                @foreach ($zikirItems as $item)
                                    <option value="{{ $item['id'] }}">{{ app()->getLocale() === 'bn' ? $item['name_bn'] : $item['name_en'] }}</option>
                                @endforeach
                            </select>
                            @error('goalZikirItem') <span class="text-[10px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                        </div>
                        @endif

                        <!-- Target Value -->
                        <div class="space-y-1">
                            <label class="block text-xs font-bold text-gray-400 dark:text-gray-500">
                                {{ $goalType === 'salah' ? (app()->getLocale() === 'bn' ? 'ওয়াক্ত সংখ্যা' : 'No. of Prayers') : ($goalType === 'quran' ? (app()->getLocale() === 'bn' ? 'পৃষ্ঠা সংখ্যা' : 'No. of Pages') : (app()->getLocale() === 'bn' ? 'বার সংখ্যা' : 'Target Count')) }}
                            </label>
                            <input type="number" wire:model="goalTarget" min="1" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white font-bold">
                            @error('goalTarget') <span class="text-[10px] text-rose-500 font-bold">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-xs shadow-sm transition">
                        {{ app()->getLocale() === 'bn' ? 'লক্ষ্যমাত্রা সংরক্ষণ করুন' : 'Save Goal' }}
                    </button>
                </form>

                <!-- Active Goals List -->
                <div class="space-y-2.5 pt-2">
                    <h4 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                        {{ app()->getLocale() === 'bn' ? 'আজকের সক্রিয় লক্ষ্যসমূহ' : 'Active Goals for Today' }}
                    </h4>
                    <div class="space-y-2">
                        @forelse ($dailyGoals as $goal)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-slate-800/50 rounded-xl border border-gray-100 dark:border-slate-800 font-medium">
                                <div class="flex items-center gap-2">
                                    <span class="p-1 rounded bg-emerald-100 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 text-xs font-black">
                                        @if ($goal['goal_type'] === 'salah')
                                            🕌 {{ app()->getLocale() === 'bn' ? 'নামাজ' : 'Salah' }}
                                        @elseif ($goal['goal_type'] === 'quran')
                                            📖 {{ app()->getLocale() === 'bn' ? 'কুরআন' : 'Quran' }}
                                        @else
                                            📿 {{ app()->getLocale() === 'bn' ? 'জিকির' : 'Zikir' }}
                                        @endif
                                    </span>
                                    <span class="text-xs font-bold text-gray-700 dark:text-gray-300">
                                        @if ($goal['goal_type'] === 'zikir')
                                            {{ app()->getLocale() === 'bn' ? ($goal['zikir_item']['name_bn'] ?? 'কাস্টম জিকির') : ($goal['zikir_item']['name_en'] ?? 'Custom Zikir') }}
                                        @else
                                            {{ $goal['goal_type'] === 'salah' ? (app()->getLocale() === 'bn' ? '৫ ওয়াক্ত নামাজ আদায়' : '5 Daily Prayers') : (app()->getLocale() === 'bn' ? 'কুরআন তিলাওয়াত' : 'Quran Recitation') }}
                                        @endif
                                    </span>
                                    <span class="text-[10px] text-gray-400 dark:text-gray-500 font-bold">
                                        ({{ app()->getLocale() === 'bn' ? 'লক্ষ্য' : 'Target' }}: {{ $goal['target_value'] }})
                                    </span>
                                </div>
                                <button wire:click="deleteGoal({{ $goal['id'] }})" class="text-gray-400 hover:text-rose-500 transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        @empty
                            <p class="text-xs text-center text-gray-400 dark:text-gray-500 py-3">{{ app()->getLocale() === 'bn' ? 'আজকের কোনো লক্ষ্য সেট করা নেই।' : 'No goals set for today.' }}</p>
                        @endforelse
                    </div>
                </div>
            </div>
        @endif
    @else
    <!-- Guest Warning -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-3">
        <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100">
            {{ app()->getLocale() === 'bn' ? 'দৈনিক লক্ষ্যমাত্রা' : 'Daily Goals' }}
        </h3>
        <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed font-semibold">
            {{ app()->getLocale() === 'bn' ? 'কাস্টম দৈনিক লক্ষ্যমাত্রা সেট করতে এবং ট্র্যাকিং সংরক্ষণ করতে অনুগ্রহ করে অ্যাকাউন্ট খুলুন বা লগইন করুন।' : 'Please register or log in to configure custom daily goals and track your progress permanently!' }}
        </p>
        <div class="flex gap-2">
            <a href="{{ route('login') }}" wire:navigate class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-4 py-2 rounded-xl transition">
                {{ __('messages.login') }}
            </a>
            <a href="{{ route('register') }}" wire:navigate class="bg-gray-100 dark:bg-slate-800 text-gray-700 dark:text-gray-300 text-xs font-bold px-4 py-2 rounded-xl transition">
                {{ __('messages.register') }}
            </a>
        </div>
    </div>
    @endif

    <!-- Mood & Reflection Journal Section -->
    <div x-show="journalEnabled" class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-5">
        <div class="flex items-center justify-between">
            <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100">
                {{ app()->getLocale() === 'bn' ? 'অনুভূতি ও আধ্যাত্মিক প্রতিফলন ডায়েরি' : 'Spiritual Reflection Journal' }}
            </h3>
            <span class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.83 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                </svg>
            </span>
        </div>
        
        <span x-show="showJournalToast" class="bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 px-3 py-2 rounded-xl text-xs font-bold block animate-pulse">
            {{ app()->getLocale() === 'bn' ? 'ডায়েরি এন্ট্রি সফলভাবে সংরক্ষিত হয়েছে!' : 'Journal entry saved successfully!' }}
        </span>

        <form @submit.prevent="saveJournalEntry()" class="space-y-4">
            <!-- Mood Selection -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500">{{ app()->getLocale() === 'bn' ? 'আজকের অনুভূতি কেমন?' : 'How is your spiritual mood?' }}</label>
                <div class="grid grid-cols-5 gap-1.5 bg-gray-50 dark:bg-slate-800/50 rounded-2xl p-1.5 border border-gray-100 dark:border-slate-800">
                    
                    <!-- Happy -->
                    <button type="button" @click="journalMood = 'happy'; saveJournalEntry()" 
                            :class="journalMood === 'happy' ? 'border-emerald-200 dark:border-emerald-900/60 bg-emerald-50/50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 shadow-sm scale-105' : 'border-transparent text-gray-400 hover:scale-105'" 
                            class="flex flex-col items-center p-2 rounded-xl border transition duration-300">
                        <span class="text-xl">😊</span>
                        <span class="text-[8px] sm:text-[9px] font-bold mt-1">{{ app()->getLocale() === 'bn' ? 'প্রফুল্ল' : 'Vibrant' }}</span>
                    </button>

                    <!-- Peaceful -->
                    <button type="button" @click="journalMood = 'peaceful'; saveJournalEntry()" 
                            :class="journalMood === 'peaceful' ? 'border-sky-200 dark:border-sky-900/60 bg-sky-50/50 dark:bg-sky-950/20 text-sky-600 dark:text-sky-400 shadow-sm scale-105' : 'border-transparent text-gray-400 hover:scale-105'" 
                            class="flex flex-col items-center p-2 rounded-xl border transition duration-300">
                        <span class="text-xl">😌</span>
                        <span class="text-[8px] sm:text-[9px] font-bold mt-1">{{ app()->getLocale() === 'bn' ? 'শান্ত' : 'Peaceful' }}</span>
                    </button>

                    <!-- Grateful -->
                    <button type="button" @click="journalMood = 'grateful'; saveJournalEntry()" 
                            :class="journalMood === 'grateful' ? 'border-amber-200 dark:border-amber-900/60 bg-amber-50/50 dark:bg-amber-950/20 text-amber-600 dark:text-amber-400 shadow-sm scale-105' : 'border-transparent text-gray-400 hover:scale-105'" 
                            class="flex flex-col items-center p-2 rounded-xl border transition duration-300">
                        <span class="text-xl">🤲</span>
                        <span class="text-[8px] sm:text-[9px] font-bold mt-1">{{ app()->getLocale() === 'bn' ? 'কৃতজ্ঞ' : 'Grateful' }}</span>
                    </button>

                    <!-- Tired -->
                    <button type="button" @click="journalMood = 'tired'; saveJournalEntry()" 
                            :class="journalMood === 'tired' ? 'border-purple-200 dark:border-purple-900/60 bg-purple-50/50 dark:bg-purple-950/20 text-purple-600 dark:text-purple-400 shadow-sm scale-105' : 'border-transparent text-gray-400 hover:scale-105'" 
                            class="flex flex-col items-center p-2 rounded-xl border transition duration-300">
                        <span class="text-xl">😩</span>
                        <span class="text-[8px] sm:text-[9px] font-bold mt-1">{{ app()->getLocale() === 'bn' ? 'ক্লান্ত' : 'Tired' }}</span>
                    </button>

                    <!-- Sad -->
                    <button type="button" @click="journalMood = 'sad'; saveJournalEntry()" 
                            :class="journalMood === 'sad' ? 'border-rose-200 dark:border-rose-900/60 bg-rose-50/50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 shadow-sm scale-105' : 'border-transparent text-gray-400 hover:scale-105'" 
                            class="flex flex-col items-center p-2 rounded-xl border transition duration-300">
                        <span class="text-xl">😔</span>
                        <span class="text-[8px] sm:text-[9px] font-bold mt-1">{{ app()->getLocale() === 'bn' ? 'বিষণ্ণ' : 'Low' }}</span>
                    </button>
                    
                </div>
            </div>

            <!-- Quick Reflection Prompts -->
            <div class="space-y-1.5">
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500">
                    {{ app()->getLocale() === 'bn' ? 'সহায়ক প্রম্পট (ক্লিক করুন)' : 'Reflection Prompts (Click to Use)' }}
                </label>
                <div class="flex flex-wrap gap-1.5">
                    <button type="button" @click="insertPrompt(lang === 'bn' ? 'আজকে আমি আল্লাহর প্রতি কৃতজ্ঞ কারণ: ' : 'Today I am grateful to Allah for: '); saveJournalEntry()" class="text-[10px] py-1.5 px-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 hover:bg-emerald-50 dark:bg-slate-800 dark:hover:bg-slate-800/80 dark:text-gray-300 font-bold transition">
                        🤲 {{ app()->getLocale() === 'bn' ? 'কৃতজ্ঞতা' : 'Gratitude' }}
                    </button>
                    <button type="button" @click="insertPrompt(lang === 'bn' ? 'আজকে আমার আত্মিক প্রতিফলন ও উন্নতি: ' : 'My spiritual reflections & growth today: '); saveJournalEntry()" class="text-[10px] py-1.5 px-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 hover:bg-emerald-50 dark:bg-slate-800 dark:hover:bg-slate-800/80 dark:text-gray-300 font-bold transition">
                        📈 {{ app()->getLocale() === 'bn' ? 'আত্মিক বৃদ্ধি' : 'Growth' }}
                    </button>
                    <button type="button" @click="insertPrompt(lang === 'bn' ? 'আজকের দিনে যেসব ভুলের জন্য ক্ষমা চাই: ' : 'Mistakes made today and seeking repentance: '); saveJournalEntry()" class="text-[10px] py-1.5 px-3 rounded-xl border border-gray-200 dark:border-slate-700 bg-gray-50 hover:bg-emerald-50 dark:bg-slate-800 dark:hover:bg-slate-800/80 dark:text-gray-300 font-bold transition">
                        🌧️ {{ app()->getLocale() === 'bn' ? 'ভুল ও ইস্তিগফার' : 'Repentance' }}
                    </button>
                </div>
            </div>

            <!-- Reflection Text -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-gray-400 dark:text-gray-500">{{ __('messages.reflection_text') }}</label>
                <textarea x-model="journalReflection" @input.debounce.1000ms="saveJournalEntry()" @blur="saveJournalEntry()" rows="4" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white" placeholder="{{ app()->getLocale() === 'bn' ? 'আজকের দিনটি কেমন কাটলো? কোনো ভালো কাজ বা আত্মিক প্রতিফলন...' : 'How was your day? Any good deeds, spiritual milestones, or areas to improve...' }}"></textarea>
                
                <div class="flex justify-between items-center text-[10px] text-gray-400 dark:text-gray-500 mt-1 px-1">
                    <span x-text="(journalReflection ? journalReflection.length : 0) + '/500'"></span>
                    <span x-show="journalReflection && journalReflection.length > 500" class="text-rose-500 font-bold animate-pulse">
                        {{ app()->getLocale() === 'bn' ? '৫০০ অক্ষরের বেশি লিখেছেন!' : 'Exceeded 500 character limit!' }}
                    </span>
                </div>
            </div>

            <button type="submit" :disabled="journalReflection && journalReflection.length > 500" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-xl text-xs shadow-sm transition disabled:opacity-50 disabled:cursor-not-allowed">
                {{ app()->getLocale() === 'bn' ? 'ডায়েরি এন্ট্রি সেভ করুন' : 'Save Reflection' }}
            </button>
        </form>

        <hr class="border-gray-100 dark:border-slate-800/60 my-4">

        <!-- Past Entries Timeline -->
        <div class="space-y-3.5">
            <h4 class="text-xs font-black text-gray-400 dark:text-gray-500 uppercase tracking-wider">
                {{ app()->getLocale() === 'bn' ? 'অতীতের আত্মপ্রতিফলন' : 'Past Reflections' }}
            </h4>
            <input type="text" x-model="searchJournal" @input="filterPastJournals()" class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white" placeholder="{{ app()->getLocale() === 'bn' ? 'ডায়েরির লেখা খুঁজুন...' : 'Search past entries...' }}">
            
            <div class="relative border-l-2 border-emerald-100 dark:border-emerald-950/60 ml-3.5 pl-4 space-y-5 max-h-72 overflow-y-auto pr-1">
                <template x-for="entry in (Array.isArray(pastJournalEntries) ? pastJournalEntries : [])" :key="entry.date">
                    <div class="relative">
                        <!-- Timeline circle dot -->
                        <span class="absolute -left-[23px] top-1.5 flex h-2.5 w-2.5 items-center justify-center rounded-full bg-emerald-500 ring-4 ring-white dark:ring-slate-900"></span>
                        
                        <div class="bg-gray-50 dark:bg-slate-900 border border-gray-100 dark:border-slate-800/80 rounded-xl p-3.5 space-y-1.5 shadow-sm hover:shadow transition">
                            <div class="flex items-center justify-between text-[10px] font-black text-gray-400 dark:text-gray-500">
                                <span class="font-outfit" x-text="entry.date"></span>
                                <span class="px-2 py-0.5 rounded-full text-[9px] uppercase tracking-wider font-bold"
                                      :class="{
                                          'bg-emerald-50 dark:bg-emerald-950/30 text-emerald-600 dark:text-emerald-400': entry.mood === 'happy',
                                          'bg-sky-50 dark:bg-sky-950/30 text-sky-600 dark:text-sky-400': entry.mood === 'peaceful',
                                          'bg-amber-50 dark:bg-amber-950/30 text-amber-600 dark:text-amber-400': entry.mood === 'grateful',
                                          'bg-purple-50 dark:bg-purple-950/30 text-purple-600 dark:text-purple-400': entry.mood === 'tired',
                                          'bg-rose-50 dark:bg-rose-950/30 text-rose-600 dark:text-rose-400': entry.mood === 'sad',
                                          'bg-gray-50 dark:bg-gray-900 text-gray-500': !entry.mood || entry.mood === 'neutral'
                                      }"
                                      x-text="{
                                          'happy': lang === 'bn' ? '😊 প্রফুল্ল' : '😊 Vibrant',
                                          'peaceful': lang === 'bn' ? '😌 শান্ত' : '😌 Peaceful',
                                          'grateful': lang === 'bn' ? '🤲 কৃতজ্ঞ' : '🤲 Grateful',
                                          'tired': lang === 'bn' ? '😩 ক্লান্ত' : '😩 Tired',
                                          'sad': lang === 'bn' ? '😔 বিষণ্ণ' : '😔 Low',
                                          'neutral': lang === 'bn' ? '😐 সাধারণ' : '😐 Neutral'
                                      }[entry.mood || 'neutral']">
                                </span>
                            </div>
                            <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed font-medium whitespace-pre-line" x-text="entry.reflection_text"></p>
                        </div>
                    </div>
                </template>
                <template x-if="(Array.isArray(pastJournalEntries) ? pastJournalEntries : []).length === 0">
                    <p class="text-xs text-center text-gray-400 dark:text-gray-500 py-4">{{ __('messages.no_reflection') }}</p>
                </template>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="bg-white dark:bg-slate-900 border border-gray-100 dark:border-slate-800 rounded-2xl p-5 shadow-sm space-y-6">
        <h3 class="font-outfit font-extrabold text-base text-gray-800 dark:text-gray-100">{{ app()->getLocale() === 'bn' ? 'সাপ্তাহিক আমল পরিসংখ্যান' : 'Spiritual Analytics' }}</h3>
        
        <div class="space-y-2">
            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">{{ app()->getLocale() === 'bn' ? 'দৈনিক জিকির কাউন্ট (গত ৭ দিন)' : 'Daily Zikir Count (Last 7 Days)' }}</h4>
            <div class="h-48">
                <canvas id="zikirChart"></canvas>
            </div>
        </div>

        <hr x-show="salahEnabled" class="border-gray-100 dark:border-slate-800 my-4">

        <div x-show="salahEnabled" class="space-y-2">
            <h4 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider text-center">{{ app()->getLocale() === 'bn' ? 'দৈনিক নামাজ আদায় (গত ৭ দিন)' : 'Daily Salah Status (Last 7 Days)' }}</h4>
            <div class="h-48">
                <canvas id="salahChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN (Loaded globally in Layout Head) -->
    <script>
        function recordsApp() {
            return {
                isGuest: @json(!Auth::check()),
                getLocalDate(d = new Date()) {
                    let y = d.getFullYear();
                    let m = String(d.getMonth() + 1).padStart(2, '0');
                    let day = String(d.getDate()).padStart(2, '0');
                    return `${y}-${m}-${day}`;
                },
                lang: '{{ app()->getLocale() }}',
                selectedDate: '{{ $selectedDate }}',
                salahEnabled: @json($salahEnabled),
                quranEnabled: @json($quranEnabled),
                habitEnabled: @json($habitEnabled),
                journalEnabled: @json($journalEnabled),

                zikirRange: @entangle('zikirRange'),
                zikirBreakdown: @entangle('zikirBreakdown'),
                salahStats: @entangle('salahStats'),
                quranStats: @entangle('quranStats'),
                habitStats: @entangle('habitStats'),
                startDate: @entangle('startDate'),
                endDate: @entangle('endDate'),
                
                // Salah Checklists
                prayers: [
                    { key: 'fajr', labelBn: 'ফজর', labelEn: 'Fajr' },
                    { key: 'dhuhr', labelBn: 'যোহর', labelEn: 'Dhuhr' },
                    { key: 'asr', labelBn: 'আসর', labelEn: 'Asr' },
                    { key: 'maghrib', labelBn: 'মাগরিব', labelEn: 'Maghrib' },
                    { key: 'isha', labelBn: 'এশা', labelEn: 'Isha' }
                ],
                salahStatuses: @entangle('salahStatuses'),
                showSalahToast: false,

                // Quran tracking
                quranPages: @entangle('quranPages'),
                quranPara: @entangle('quranPara'),
                quranMonthlyTarget: @entangle('quranMonthlyTarget'),
                quranTotalPagesRead: @entangle('quranTotalPagesRead'),
                get quranParaPercent() { return this.quranPara ? Math.round((this.quranPara / 30) * 100) : 0; },
                showQuranToast: false,

                // Spiritual habits
                habitTypes: [
                    { key: 'tahajjud', labelBn: 'তাহাজ্জুদ নামাজ', labelEn: 'Tahajjud' },
                    { key: 'ishraq', labelBn: 'ইশরাক নামাজ', labelEn: 'Ishraq' },
                    { key: 'sadaqah', labelBn: 'দান-সদকা করা', labelEn: 'Sadaqah' },
                    { key: 'fasting', labelBn: 'নফল রোজা রাখা', labelEn: 'Fasting' },
                    { key: 'quran_tilawah', labelBn: 'কুরআন তিলাওয়াত', labelEn: 'Quran Recitation' }
                ],
                habits: @entangle('habits'),

                // Mood and reflection journal
                journalMood: @entangle('journalMood'),
                journalReflection: @entangle('journalReflection'),
                pastJournalEntries: @entangle('pastJournalEntries'),
                searchJournal: '',
                showJournalToast: false,
                journalToastTimeout: null,

                heatmapGrid: @entangle('heatmap'),
                zikirChart: null,
                salahChart: null,

                init() {
                    this.loadAllData();
                    this.renderCharts();
                },

                loadAllData() {
                    if (this.isGuest) {
                        this.salahEnabled = localStorage.getItem('guest_salah_tracker_enabled') !== 'false';
                        this.quranEnabled = localStorage.getItem('guest_quran_tracker_enabled') !== 'false';
                        this.habitEnabled = localStorage.getItem('guest_habit_tracker_enabled') !== 'false';
                        this.journalEnabled = localStorage.getItem('guest_journal_tracker_enabled') !== 'false';

                        this.selectedDate = this.getLocalDate();

                        this.loadLocalDayData();
                        this.loadLocalPastJournals();
                        this.generateLocalHeatmap();
                        this.loadLocalZikirBreakdown();
                    }
                },

                changeZikirRange(range) {
                    this.zikirRange = range;
                    if (this.isGuest) {
                        this.loadLocalZikirBreakdown();
                    } else {
                        @this.set('zikirRange', this.zikirRange);
                    }
                },

                loadLocalZikirBreakdown() {
                    let counts = JSON.parse(localStorage.getItem('guest_zikir_counts') || '[]');
                    let presets = @json(\App\Models\ZikirItem::whereNull('user_id')->get()->toArray());
                    let customLocal = JSON.parse(localStorage.getItem('guest_custom_zikir_items') || '[]');
                    let allItems = [...presets, ...customLocal];

                    let now = new Date();
                    let range = this.zikirRange;

                    // Helper to filter dates by range
                    let filterByRange = (dateStr) => {
                        let cDate = new Date(dateStr + 'T00:00:00');
                        let todayStr = this.getLocalDate(now);

                        if (range === 'today') {
                            return dateStr === todayStr;
                        } else if (range === 'yesterday') {
                            let yesterday = new Date();
                            yesterday.setDate(yesterday.getDate() - 1);
                            let yestStr = this.getLocalDate(yesterday);
                            return dateStr === yestStr;
                        } else if (range === 'week') {
                            let minDate = new Date();
                            minDate.setDate(minDate.getDate() - 7);
                            return cDate >= minDate;
                        } else if (range === 'month') {
                            let minDate = new Date();
                            minDate.setDate(minDate.getDate() - 30);
                            return cDate >= minDate;
                        } else if (range === 'year') {
                            let minDate = new Date();
                            minDate.setDate(minDate.getDate() - 365);
                            return cDate >= minDate;
                        } else if (range === 'custom') {
                            return dateStr >= this.startDate && dateStr <= this.endDate;
                        }
                        return false;
                    };

                    // 1. Zikir
                    let filteredCounts = counts.filter(c => filterByRange(c.counted_at));
                    let aggregated = {};
                    filteredCounts.forEach(c => {
                        let id = c.zikir_item_id;
                        aggregated[id] = (aggregated[id] || 0) + c.count;
                    });

                    let list = [];
                    Object.keys(aggregated).forEach(id => {
                        let item = allItems.find(z => z.id == id);
                        if (item) {
                            list.push({
                                id: id,
                                name_bn: item.name_bn,
                                name_en: item.name_en,
                                count: aggregated[id]
                            });
                        } else if (id.startsWith('guest_')) {
                            list.push({
                                id: id,
                                name_bn: 'কাস্টম আমল',
                                name_en: 'Custom Amal',
                                count: aggregated[id]
                            });
                        }
                    });
                    list.sort((a, b) => b.count - a.count);
                    this.zikirBreakdown = list;

                    // 2. Salah Stats
                    let localSalah = JSON.parse(localStorage.getItem('guest_salah_logs') || '[]');
                    let filteredSalah = localSalah.filter(s => filterByRange(s.date));
                    this.salahStats = { jamaat: 0, alone: 0, qaza: 0, missed: 0 };
                    filteredSalah.forEach(s => {
                        if (this.salahStats[s.status] !== undefined) {
                            this.salahStats[s.status]++;
                        }
                    });

                    // 3. Quran Stats
                    let localQuran = JSON.parse(localStorage.getItem('guest_quran_progress') || '[]');
                    let filteredQuran = localQuran.filter(q => filterByRange(q.date));
                    let totalPages = filteredQuran.reduce((sum, q) => sum + parseInt(q.pages_read || 0), 0);
                    let maxPara = filteredQuran.reduce((max, q) => Math.max(max, parseInt(q.para_completed || 0)), 0);
                    this.quranStats = { pages: totalPages, paras: maxPara };

                    // 4. Habits Stats
                    let localHabits = JSON.parse(localStorage.getItem('guest_habit_logs') || '[]');
                    let filteredHabits = localHabits.filter(h => filterByRange(h.date) && h.completed);
                    this.habitStats = { tahajjud: 0, ishraq: 0, sadaqah: 0, fasting: 0, quran_tilawah: 0 };
                    filteredHabits.forEach(h => {
                        if (this.habitStats[h.habit_type] !== undefined) {
                            this.habitStats[h.habit_type]++;
                        }
                    });
                },

                onDateChanged() {
                    if (this.isGuest) {
                        this.loadLocalDayData();
                        if (this.zikirRange === 'custom') {
                            this.loadLocalZikirBreakdown();
                        }
                    } else {
                        @this.set('selectedDate', this.selectedDate);
                    }
                },

                onCustomRangeChanged() {
                    if (this.isGuest) {
                        this.loadLocalZikirBreakdown();
                    } else {
                        @this.set('startDate', this.startDate);
                        @this.set('endDate', this.endDate);
                    }
                },

                insertPrompt(text) {
                    if (this.journalReflection) {
                        this.journalReflection += "\n" + text;
                    } else {
                        this.journalReflection = text;
                    }
                },

                loadLocalDayData() {
                    let date = this.selectedDate;

                    // 1. Salah
                    let localSalah = JSON.parse(localStorage.getItem('guest_salah_logs') || '[]');
                    this.prayers.forEach(p => {
                        let log = localSalah.find(s => s.prayer_name === p.key && s.date === date);
                        this.salahStatuses[p.key] = log ? log.status : 'missed';
                    });

                    // 2. Quran
                    let localQuran = JSON.parse(localStorage.getItem('guest_quran_progress') || '[]');
                    let quranLog = localQuran.find(q => q.date === date);
                    this.quranPages = quranLog ? quranLog.pages_read : 0;
                    this.quranPara = quranLog ? quranLog.para_completed : null;
                    this.quranMonthlyTarget = quranLog ? quranLog.monthly_target || 60 : 60;
                    
                    this.quranTotalPagesRead = localQuran.reduce((acc, curr) => acc + parseInt(curr.pages_read || 0), 0);

                    // 3. Habits
                    let localHabits = JSON.parse(localStorage.getItem('guest_habit_logs') || '[]');
                    this.habitTypes.forEach(h => {
                        let log = localHabits.find(hl => hl.habit_type === h.key && hl.date === date);
                        this.habits[h.key] = log ? !!log.completed : false;
                    });

                    // 4. Journal
                    let localJournal = JSON.parse(localStorage.getItem('guest_journal_entries') || '[]');
                    let journal = localJournal.find(j => j.date === date);
                    this.journalMood = journal ? journal.mood : 'neutral';
                    this.journalReflection = journal ? journal.reflection_text : '';
                },

                onSalahChanged(prayerName, status) {
                    if (this.isGuest) {
                        let localSalah = JSON.parse(localStorage.getItem('guest_salah_logs') || '[]');
                        let existingIdx = localSalah.findIndex(s => s.prayer_name === prayerName && s.date === this.selectedDate);
                        
                        if (existingIdx !== -1) {
                            localSalah[existingIdx].status = status;
                        } else {
                            localSalah.push({
                                prayer_name: prayerName,
                                status: status,
                                date: this.selectedDate
                            });
                        }
                        localStorage.setItem('guest_salah_logs', JSON.stringify(localSalah));
                        this.salahStatuses[prayerName] = status;
                        this.showSalahToast = true;
                        setTimeout(() => this.showSalahToast = false, 1500);

                        this.generateLocalHeatmap();
                        this.updateCharts();
                    } else {
                        @this.saveSalah(prayerName, status);
                    }
                },

                saveQuranLog() {
                    if (this.isGuest) {
                        let localQuran = JSON.parse(localStorage.getItem('guest_quran_progress') || '[]');
                        let existingIdx = localQuran.findIndex(q => q.date === this.selectedDate);

                        let quranPagesInt = parseInt(this.quranPages) || 0;
                        let quranParaInt = parseInt(this.quranPara) || null;

                        if (existingIdx !== -1) {
                            localQuran[existingIdx].pages_read = quranPagesInt;
                            localQuran[existingIdx].para_completed = quranParaInt;
                            localQuran[existingIdx].monthly_target = this.quranMonthlyTarget;
                        } else {
                            localQuran.push({
                                pages_read: quranPagesInt,
                                para_completed: quranParaInt,
                                monthly_target: this.quranMonthlyTarget,
                                date: this.selectedDate
                            });
                        }
                        localStorage.setItem('guest_quran_progress', JSON.stringify(localQuran));
                        this.quranTotalPagesRead = localQuran.reduce((acc, curr) => acc + parseInt(curr.pages_read || 0), 0);
                        
                        this.showQuranToast = true;
                        setTimeout(() => this.showQuranToast = false, 1500);
                    } else {
                        @this.saveQuran();
                    }
                },

                toggleHabitLog(habitType) {
                    let nextVal = !this.habits[habitType];
                    this.habits[habitType] = nextVal;

                    if (this.isGuest) {
                        let localHabits = JSON.parse(localStorage.getItem('guest_habit_logs') || '[]');
                        let existingIdx = localHabits.findIndex(h => h.habit_type === habitType && h.date === this.selectedDate);

                        if (existingIdx !== -1) {
                            localHabits[existingIdx].completed = nextVal;
                        } else {
                            localHabits.push({
                                habit_type: habitType,
                                completed: nextVal,
                                date: this.selectedDate
                            });
                        }
                        localStorage.setItem('guest_habit_logs', JSON.stringify(localHabits));
                        this.generateLocalHeatmap();
                    } else {
                        @this.toggleHabit(habitType);
                    }
                },

                saveJournalEntry() {
                    // Show instant success feedback toast immediately
                    this.showJournalToast = true;
                    if (this.journalToastTimeout) {
                        clearTimeout(this.journalToastTimeout);
                    }
                    this.journalToastTimeout = setTimeout(() => {
                        this.showJournalToast = false;
                    }, 1500);

                    if (this.isGuest) {
                        let localJournal = JSON.parse(localStorage.getItem('guest_journal_entries') || '[]');
                        let existingIdx = localJournal.findIndex(j => j.date === this.selectedDate);

                        if (existingIdx !== -1) {
                            localJournal[existingIdx].mood = this.journalMood;
                            localJournal[existingIdx].reflection_text = this.journalReflection;
                        } else {
                            localJournal.push({
                                mood: this.journalMood,
                                reflection_text: this.journalReflection,
                                date: this.selectedDate
                            });
                        }
                        localStorage.setItem('guest_journal_entries', JSON.stringify(localJournal));
                        this.loadLocalPastJournals();
                    } else {
                        @this.saveJournal();
                    }
                },

                loadLocalPastJournals() {
                    let localJournal = JSON.parse(localStorage.getItem('guest_journal_entries') || '[]');
                    // Sort by date desc
                    localJournal.sort((a, b) => new Date(b.date) - new Date(a.date));
                    
                    if (this.searchJournal) {
                        this.pastJournalEntries = localJournal.filter(j => j.reflection_text.toLowerCase().includes(this.searchJournal.toLowerCase())).slice(0, 5);
                    } else {
                        this.pastJournalEntries = localJournal.slice(0, 5);
                    }
                },

                filterPastJournals() {
                    if (this.isGuest) {
                        this.loadLocalPastJournals();
                    } else {
                        @this.set('searchJournal', this.searchJournal);
                    }
                },

                // Generate Heatmap calendar grids client side for guest users
                generateLocalHeatmap() {
                    let grid = [];
                    let counts = JSON.parse(localStorage.getItem('guest_zikir_counts') || '[]');
                    let salah = JSON.parse(localStorage.getItem('guest_salah_logs') || '[]');
                    let habits = JSON.parse(localStorage.getItem('guest_habit_logs') || '[]');

                    for (let i = 29; i >= 0; i--) {
                        let d = new Date();
                        d.setDate(d.getDate() - i);
                        let dateStr = this.getLocalDate(d);
                        
                        let zikirToday = counts.filter(c => c.counted_at === dateStr).reduce((acc, curr) => acc + curr.count, 0);
                        let salahToday = salah.filter(s => s.date === dateStr && (s.status === 'jamaat' || s.status === 'alone')).length;
                        let habitsToday = habits.filter(h => h.date === dateStr && h.completed === true).length;

                        let score = 0;
                        if (zikirToday > 100) score += 2;
                        else if (zikirToday > 0) score += 1;

                        if (salahToday >= 5) score += 2;
                        else if (salahToday > 0) score += 1;

                        if (habitsToday >= 3) score += 2;
                        else if (habitsToday > 0) score += 1;

                        let color = 'bg-gray-100 dark:bg-slate-800';
                        if (score >= 5) color = 'bg-emerald-600';
                        else if (score >= 3) color = 'bg-emerald-400 dark:bg-emerald-500';
                        else if (score >= 1) color = 'bg-emerald-200 dark:bg-emerald-700';

                        grid.push({
                            date: dateStr,
                            score: score,
                            color: color,
                            day: d.getDate()
                        });
                    }

                    this.heatmapGrid = grid;
                },

                renderCharts() {
                    // 1. Zikir Chart
                    let zikirLabels = [];
                    let zikirDataset = [];

                    if (this.isGuest) {
                        let guestCounts = JSON.parse(localStorage.getItem('guest_zikir_counts') || '[]');
                        for (let i = 6; i >= 0; i--) {
                            let d = new Date();
                            d.setDate(d.getDate() - i);
                            let dateStr = this.getLocalDate(d);
                            zikirLabels.push(d.toLocaleDateString(this.lang === 'bn' ? 'bn-BD' : 'en-US', { weekday: 'short' }));
                            
                            let sum = guestCounts.filter(c => c.counted_at === dateStr).reduce((acc, curr) => acc + curr.count, 0);
                            zikirDataset.push(sum);
                        }
                    } else {
                        let zikirData = @json($weeklyZikir);
                        zikirLabels = zikirData.labels;
                        zikirDataset = zikirData.data;
                    }

                    const zikirCtx = document.getElementById('zikirChart').getContext('2d');
                    this.zikirChart = new Chart(zikirCtx, {
                        type: 'line',
                        data: {
                            labels: zikirLabels,
                            datasets: [{
                                data: zikirDataset,
                                borderColor: '#059669',
                                backgroundColor: 'rgba(5, 150, 105, 0.1)',
                                borderWidth: 3,
                                tension: 0.3,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: { legend: { display: false } },
                            scales: {
                                y: { beginAtZero: true, grid: { color: 'rgba(128,128,128,0.1)' } },
                                x: { grid: { display: false } }
                            }
                        }
                    });

                    // 2. Salah Chart
                    if (this.salahEnabled) {
                        let salahLabels = [];
                        let jamaatData = [];
                        let aloneData = [];

                        if (this.isGuest) {
                            let guestSalah = JSON.parse(localStorage.getItem('guest_salah_logs') || '[]');
                            for (let i = 6; i >= 0; i--) {
                                let d = new Date();
                                d.setDate(d.getDate() - i);
                                let dateStr = this.getLocalDate(d);
                                salahLabels.push(d.toLocaleDateString(this.lang === 'bn' ? 'bn-BD' : 'en-US', { weekday: 'short' }));

                                let jamaat = guestSalah.filter(s => s.date === dateStr && s.status === 'jamaat').length;
                                let alone = guestSalah.filter(s => s.date === dateStr && s.status === 'alone').length;
                                jamaatData.push(jamaat);
                                aloneData.push(alone);
                            }
                        } else {
                            let salahData = @json($weeklySalah);
                            salahLabels = salahData.labels;
                            jamaatData = salahData.jamaat;
                            aloneData = salahData.alone;
                        }

                        const salahCtx = document.getElementById('salahChart').getContext('2d');
                        this.salahChart = new Chart(salahCtx, {
                            type: 'bar',
                            data: {
                                labels: salahLabels,
                                datasets: [
                                    { label: 'Jamaat', data: jamaatData, backgroundColor: '#059669', borderRadius: 4 },
                                    { label: 'Alone', data: aloneData, backgroundColor: '#fbbf24', borderRadius: 4 }
                                ]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: { legend: { position: 'bottom', labels: { boxWidth: 12 } } },
                                scales: {
                                    y: { beginAtZero: true, max: 5, ticks: { stepSize: 1 }, grid: { color: 'rgba(128,128,128,0.1)' } },
                                    x: { stacked: true, grid: { display: false } }
                                }
                            }
                        });
                    }
                },

                updateCharts() {
                    if (this.isGuest) {
                        // Destroy old charts and re-render
                        if (this.zikirChart) this.zikirChart.destroy();
                        if (this.salahChart) this.salahChart.destroy();
                        this.renderCharts();
                    }
                }
            }
        }
    </script>
</div>
