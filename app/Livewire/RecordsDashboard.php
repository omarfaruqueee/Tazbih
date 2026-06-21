<?php

namespace App\Livewire;

use App\Models\ZikirCount;
use App\Models\SalahLog;
use App\Models\QuranProgress;
use App\Models\HabitLog;
use App\Models\JournalEntry;
use App\Models\DailyGoal;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class RecordsDashboard extends Component
{
    public $selectedDate;
    public $startDate;
    public $endDate;
    public $zikirRange = 'today';
    public $zikirBreakdown = [];
    public $salahStats = [
        'jamaat' => 0,
        'alone' => 0,
        'qaza' => 0,
        'missed' => 0
    ];
    public $quranStats = [
        'pages' => 0,
        'paras' => 0
    ];
    public $habitStats = [
        'tahajjud' => 0,
        'ishraq' => 0,
        'sadaqah' => 0,
        'fasting' => 0,
        'quran_tilawah' => 0
    ];
    
    // Modules configuration states
    public $salahEnabled = true;
    public $quranEnabled = true;
    public $habitEnabled = true;
    public $journalEnabled = true;
    
    // Salah
    public $salahStatuses = []; // e.g. ['fajr' => 'jamaat', 'dhuhr' => 'alone', ...]
    
    // Quran
    public $quranPages = 0;
    public $quranPara = null;
    public $quranMonthlyTarget = 60;
    public $quranTotalPagesRead = 0;
    
    // Habits
    public $habits = []; // e.g. ['tahajjud' => true, 'ishraq' => false, ...]
    
    // Journal
    public $journalMood = 'neutral';
    public $journalReflection = '';
    public $pastJournalEntries = [];
    public $searchJournal = '';
    public $heatmap = [];

    // Daily Goals Form
    public $goalZikirItem = null;
    public $goalType = 'salah';
    public $goalTarget = 5;
    public $dailyGoals = [];
    public $zikirItems = [];
    
    public function mount()
    {
        $this->selectedDate = date('Y-m-d');
        $this->startDate = date('Y-m-d', strtotime('-7 days'));
        $this->endDate = date('Y-m-d');

        if (Auth::check()) {
            $user = Auth::user();
            $salahSet = \App\Models\Setting::where('user_id', $user->id)->where('key', 'salah_tracker_enabled')->first();
            $this->salahEnabled = $salahSet ? filter_var($salahSet->value, FILTER_VALIDATE_BOOLEAN) : true;

            $quranSet = \App\Models\Setting::where('user_id', $user->id)->where('key', 'quran_tracker_enabled')->first();
            $this->quranEnabled = $quranSet ? filter_var($quranSet->value, FILTER_VALIDATE_BOOLEAN) : true;

            $habitSet = \App\Models\Setting::where('user_id', $user->id)->where('key', 'habit_tracker_enabled')->first();
            $this->habitEnabled = $habitSet ? filter_var($habitSet->value, FILTER_VALIDATE_BOOLEAN) : true;

            $journalSet = \App\Models\Setting::where('user_id', $user->id)->where('key', 'journal_tracker_enabled')->first();
            $this->journalEnabled = $journalSet ? filter_var($journalSet->value, FILTER_VALIDATE_BOOLEAN) : true;

            // Admin overrides
            if ($user->isFeatureDisabled('salah')) $this->salahEnabled = false;
            if ($user->isFeatureDisabled('quran')) $this->quranEnabled = false;
            if ($user->isFeatureDisabled('habits')) $this->habitEnabled = false;
            if ($user->isFeatureDisabled('journal')) $this->journalEnabled = false;
        } else {
            $this->salahEnabled = session()->get('salah_tracker_enabled', true);
            $this->quranEnabled = session()->get('quran_tracker_enabled', true);
            $this->habitEnabled = session()->get('habit_tracker_enabled', true);
            $this->journalEnabled = session()->get('journal_tracker_enabled', true);
        }

        $this->zikirItems = \App\Models\ZikirItem::whereNull('user_id')
            ->orWhere('user_id', Auth::id())
            ->get()
            ->toArray();

        $this->loadDayData();
        $this->loadDailyGoals();
        $this->loadPastJournalEntries();
        $this->loadZikirBreakdown();
        $this->refreshHeatmap();
    }

    public function updatedSelectedDate()
    {
        $this->loadDayData();
        $this->loadDailyGoals();
        if ($this->zikirRange === 'custom') {
            $this->loadZikirBreakdown();
        }
    }

    public function updatedSearchJournal()
    {
        $this->loadPastJournalEntries();
    }

    public function updatedZikirRange()
    {
        $this->loadZikirBreakdown();
    }

    public function updatedStartDate()
    {
        $this->loadZikirBreakdown();
    }

    public function updatedEndDate()
    {
        $this->loadZikirBreakdown();
    }

    public function loadZikirBreakdown()
    {
        if (!Auth::check()) {
            $this->zikirBreakdown = [];
            $this->salahStats = [
                'jamaat' => 0,
                'alone' => 0,
                'qaza' => 0,
                'missed' => 0
            ];
            $this->quranStats = [
                'pages' => 0,
                'paras' => 0
            ];
            $this->habitStats = [
                'tahajjud' => 0,
                'ishraq' => 0,
                'sadaqah' => 0,
                'fasting' => 0,
                'quran_tilawah' => 0
            ];
            return;
        }

        $userId = Auth::id();
        $start = date('Y-m-d');
        $end = date('Y-m-d');

        if ($this->zikirRange === 'today') {
            $start = date('Y-m-d');
            $end = date('Y-m-d');
        } elseif ($this->zikirRange === 'yesterday') {
            $start = date('Y-m-d', strtotime('-1 day'));
            $end = date('Y-m-d', strtotime('-1 day'));
        } elseif ($this->zikirRange === 'week') {
            $start = date('Y-m-d', strtotime('-7 days'));
            $end = date('Y-m-d');
        } elseif ($this->zikirRange === 'month') {
            $start = date('Y-m-d', strtotime('-30 days'));
            $end = date('Y-m-d');
        } elseif ($this->zikirRange === 'year') {
            $start = date('Y-m-d', strtotime('-365 days'));
            $end = date('Y-m-d');
        } elseif ($this->zikirRange === 'custom') {
            $start = $this->startDate ? $this->startDate : date('Y-m-d');
            $end = $this->endDate ? $this->endDate : date('Y-m-d');
        }

        // 1. Zikir Breakdown
        $query = ZikirCount::where('user_id', $userId)
            ->whereBetween('counted_at', [$start, $end])
            ->with('zikirItem');

        $results = $query->select('zikir_item_id', \DB::raw('SUM(count) as total_count'))
            ->groupBy('zikir_item_id')
            ->get();

        $this->zikirBreakdown = $results->map(function ($row) {
            return [
                'id' => $row->zikir_item_id,
                'name_bn' => $row->zikirItem->name_bn ?? 'কাস্টম আমল',
                'name_en' => $row->zikirItem->name_en ?? 'Custom Amal',
                'count' => (int)$row->total_count
            ];
        })->toArray();

        // 2. Salah Stats
        $salahStatsQuery = SalahLog::where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->select('status', \DB::raw('count(*) as cnt'))
            ->groupBy('status')
            ->get();

        $this->salahStats = [
            'jamaat' => 0,
            'alone' => 0,
            'qaza' => 0,
            'missed' => 0
        ];
        foreach ($salahStatsQuery as $row) {
            if (array_key_exists($row->status, $this->salahStats)) {
                $this->salahStats[$row->status] = (int)$row->cnt;
            }
        }

        // 3. Quran Stats
        $quranStatsQuery = QuranProgress::where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->select(\DB::raw('SUM(pages_read) as total_pages'), \DB::raw('MAX(para_completed) as max_para'))
            ->first();

        $this->quranStats = [
            'pages' => $quranStatsQuery ? (int)$quranStatsQuery->total_pages : 0,
            'paras' => $quranStatsQuery && $quranStatsQuery->max_para ? (int)$quranStatsQuery->max_para : 0
        ];

        // 4. Habits Stats
        $habitStatsQuery = HabitLog::where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->where('completed', true)
            ->select('habit_type', \DB::raw('count(*) as cnt'))
            ->groupBy('habit_type')
            ->get();

        $this->habitStats = [
            'tahajjud' => 0,
            'ishraq' => 0,
            'sadaqah' => 0,
            'fasting' => 0,
            'quran_tilawah' => 0
        ];
        foreach ($habitStatsQuery as $row) {
            if (array_key_exists($row->habit_type, $this->habitStats)) {
                $this->habitStats[$row->habit_type] = (int)$row->cnt;
            }
        }
    }

    public function loadDayData()
    {
        $userId = Auth::id();
        $date = $this->selectedDate;

        // 1. Load Salah Logs
        $prayers = ['fajr', 'dhuhr', 'asr', 'maghrib', 'isha'];
        $this->salahStatuses = [];
        foreach ($prayers as $p) {
            $log = SalahLog::where('user_id', $userId)
                ->where('prayer_name', $p)
                ->where('date', $date)
                ->first();
            $this->salahStatuses[$p] = $log ? $log->status : 'missed';
        }

        // 2. Load Quran Logs
        $quranLog = QuranProgress::where('user_id', $userId)
            ->where('date', $date)
            ->first();
        $this->quranPages = $quranLog ? $quranLog->pages_read : 0;
        $this->quranPara = $quranLog ? $quranLog->para_completed : null;
        $this->quranMonthlyTarget = $quranLog ? $quranLog->monthly_target ?? 60 : 60;

        // Load total pages read by user ever
        $this->quranTotalPagesRead = QuranProgress::where('user_id', $userId)->sum('pages_read');

        // 3. Load Habits
        $habitTypes = ['tahajjud', 'ishraq', 'sadaqah', 'fasting', 'quran_tilawah'];
        $this->habits = [];
        foreach ($habitTypes as $h) {
            $log = HabitLog::where('user_id', $userId)
                ->where('habit_type', $h)
                ->where('date', $date)
                ->first();
            $this->habits[$h] = $log ? (bool)$log->completed : false;
        }

        // 4. Load Journal Entry
        $journal = JournalEntry::where('user_id', $userId)
            ->where('date', $date)
            ->first();
        $this->journalMood = $journal ? $journal->mood : 'neutral';
        $this->journalReflection = $journal ? $journal->reflection_text : '';
    }

    public function saveSalah($prayerName, $status)
    {
        SalahLog::updateOrCreate([
            'user_id' => Auth::id(),
            'prayer_name' => $prayerName,
            'date' => $this->selectedDate,
        ], [
            'status' => $status
        ]);
        
        $this->salahStatuses[$prayerName] = $status;
        $this->refreshHeatmap();
        $this->dispatch('records-updated');
        session()->flash('salah_success', __('messages.save'));
    }

    public function saveQuran()
    {
        QuranProgress::updateOrCreate([
            'user_id' => Auth::id(),
            'date' => $this->selectedDate,
        ], [
            'pages_read' => $this->quranPages,
            'para_completed' => $this->quranPara,
            'monthly_target' => $this->quranMonthlyTarget
        ]);

        $this->quranTotalPagesRead = QuranProgress::where('user_id', Auth::id())->sum('pages_read');
        $this->refreshHeatmap();
        $this->dispatch('records-updated');
        session()->flash('quran_success', app()->getLocale() === 'bn' ? 'কুরআন প্রোগ্রেস সংরক্ষিত হয়েছে!' : 'Quran progress saved!');
    }

    public function toggleHabit($habitType)
    {
        $currentVal = $this->habits[$habitType];
        $newVal = !$currentVal;

        HabitLog::updateOrCreate([
            'user_id' => Auth::id(),
            'habit_type' => $habitType,
            'date' => $this->selectedDate,
        ], [
            'completed' => $newVal
        ]);

        $this->habits[$habitType] = $newVal;
        $this->refreshHeatmap();
        $this->dispatch('records-updated');
    }

    public function saveJournal()
    {
        JournalEntry::updateOrCreate([
            'user_id' => Auth::id(),
            'date' => $this->selectedDate,
        ], [
            'mood' => $this->journalMood,
            'reflection_text' => $this->journalReflection
        ]);

        $this->loadPastJournalEntries();
        session()->flash('journal_success', app()->getLocale() === 'bn' ? 'ডায়েরি এন্ট্রি সফলভাবে সংরক্ষিত হয়েছে!' : 'Journal entry saved successfully!');
    }

    public function saveGoal()
    {
        $this->validate([
            'goalType' => 'required|string|in:salah,quran,zikir',
            'goalTarget' => 'required|integer|min:1',
            'goalZikirItem' => 'required_if:goalType,zikir|nullable|exists:zikir_items,id',
        ]);

        DailyGoal::updateOrCreate([
            'user_id' => Auth::id(),
            'date' => $this->selectedDate,
            'goal_type' => $this->goalType,
            'zikir_item_id' => $this->goalType === 'zikir' ? $this->goalZikirItem : null,
        ], [
            'target_value' => $this->goalTarget
        ]);

        $this->loadDailyGoals();
        $this->dispatch('records-updated');
        session()->flash('goal_success', app()->getLocale() === 'bn' ? 'আজকের লক্ষ্যমাত্রা সেট করা হয়েছে!' : 'Daily goal configured!');
    }

    public function loadDailyGoals()
    {
        if (Auth::check()) {
            $this->dailyGoals = DailyGoal::where('user_id', Auth::id())
                ->where('date', $this->selectedDate)
                ->with('zikirItem')
                ->get()
                ->toArray();
        } else {
            $this->dailyGoals = [];
        }
    }

    public function deleteGoal($goalId)
    {
        if (Auth::check()) {
            DailyGoal::where('user_id', Auth::id())
                ->where('id', $goalId)
                ->delete();
            $this->loadDailyGoals();
            $this->dispatch('records-updated');
            session()->flash('goal_success', app()->getLocale() === 'bn' ? 'লক্ষ্যমাত্রাটি মুছে ফেলা হয়েছে!' : 'Goal deleted successfully!');
        }
    }

    public function loadPastJournalEntries()
    {
        $query = JournalEntry::where('user_id', Auth::id())
            ->orderBy('date', 'desc');

        if ($this->searchJournal) {
            $query->where('reflection_text', 'like', '%' . $this->searchJournal . '%');
        }

        $this->pastJournalEntries = array_values($query->take(5)->get()->toArray());
    }

    // Chart.js data generation
    public function getWeeklyZikirChartData()
    {
        $userId = Auth::id();
        $days = [];
        $counts = [];

        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i);
            $days[] = $d->format('D');
            
            $sum = ZikirCount::where('user_id', $userId)
                ->where('counted_at', $d->format('Y-m-d'))
                ->sum('count');
            $counts[] = (int)$sum;
        }

        return [
            'labels' => $days,
            'data' => $counts
        ];
    }

    public function getWeeklySalahChartData()
    {
        $userId = Auth::id();
        $days = [];
        $jamaatCounts = [];
        $aloneCounts = [];

        for ($i = 6; $i >= 0; $i--) {
            $d = Carbon::now()->subDays($i);
            $days[] = $d->format('D');
            
            $jamaat = SalahLog::where('user_id', $userId)
                ->where('date', $d->format('Y-m-d'))
                ->where('status', 'jamaat')
                ->count();
            
            $alone = SalahLog::where('user_id', $userId)
                ->where('date', $d->format('Y-m-d'))
                ->where('status', 'alone')
                ->count();

            $jamaatCounts[] = $jamaat;
            $aloneCounts[] = $alone;
        }

        return [
            'labels' => $days,
            'jamaat' => $jamaatCounts,
            'alone' => $aloneCounts
        ];
    }

    // GitHub Contribution Style Heatmap (last 30 days)
    public function getHeatmapGrid()
    {
        $userId = Auth::id();
        $grid = [];

        for ($i = 29; $i >= 0; $i--) {
            $dateObj = Carbon::now()->subDays($i);
            $dateStr = $dateObj->format('Y-m-d');
            
            // Check completed items
            $zikirCount = ZikirCount::where('user_id', $userId)->where('counted_at', $dateStr)->sum('count');
            $salahCount = SalahLog::where('user_id', $userId)->where('date', $dateStr)->whereIn('status', ['jamaat', 'alone'])->count();
            $habitCount = HabitLog::where('user_id', $userId)->where('date', $dateStr)->where('completed', true)->count();
            
            $score = 0;
            if ($zikirCount > 100) $score += 2;
            elseif ($zikirCount > 0) $score += 1;
            
            if ($salahCount >= 5) $score += 2;
            elseif ($salahCount > 0) $score += 1;

            if ($habitCount >= 3) $score += 2;
            elseif ($habitCount > 0) $score += 1;

            // score max 6. Level color code class mapping
            $colorClass = 'bg-gray-100 dark:bg-slate-800'; // level 0
            if ($score >= 5) {
                $colorClass = 'bg-emerald-600'; // level 3
            } elseif ($score >= 3) {
                $colorClass = 'bg-emerald-400 dark:bg-emerald-500'; // level 2
            } elseif ($score >= 1) {
                $colorClass = 'bg-emerald-200 dark:bg-emerald-700'; // level 1
            }

            $grid[] = [
                'date' => $dateStr,
                'score' => $score,
                'color' => $colorClass,
                'day' => $dateObj->format('j')
            ];
        }

        return $grid;
    }

    public function refreshHeatmap()
    {
        $this->heatmap = $this->getHeatmapGrid();
    }

    public function render()
    {
        $weeklyZikir = $this->getWeeklyZikirChartData();
        $weeklySalah = $this->getWeeklySalahChartData();
        $heatmap = $this->getHeatmapGrid();

        return view('livewire.records-dashboard', [
            'weeklyZikir' => $weeklyZikir,
            'weeklySalah' => $weeklySalah,
            'heatmap' => $heatmap
        ])->layout('layouts.app');
    }
}
