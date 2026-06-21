<?php

namespace App\Livewire;

use App\Models\Hadith;
use App\Models\QuranVerse;
use App\Models\ZikirItem;
use App\Models\ZikirCount;
use App\Models\DailyGoal;
use App\Models\SalahLog;
use App\Models\QuranProgress;
use App\Models\IslamicEvent;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class HomeGrid extends Component
{
    public $hadith;
    public $verse;
    public $zikirs = [];
    public $customNameBn = '';
    public $customNameEn = '';
    public $customArabic = '';
    public $customTarget = 33;
    public $customIcon = 'sparkles';
    public $showModal = false;
    
    public $hijriDateStr = '';
    public $ramadanDaysLeft = 0;
    public $goalsProgress = 0;
    
    protected $rules = [
        'customNameBn' => 'required|string|max:100',
        'customNameEn' => 'required|string|max:100',
        'customArabic' => 'nullable|string|max:255',
        'customTarget' => 'required|integer|min:1|max:100000',
        'customIcon' => 'required|string',
    ];

    public function mount()
    {
        // 1. Fetch Hadith & Verse of the Day (rotates based on day of year)
        $dayOfYear = date('z');
        
        $hadithCount = Hadith::count();
        if ($hadithCount > 0) {
            $this->hadith = Hadith::skip($dayOfYear % $hadithCount)->first();
        }
        
        $verseCount = QuranVerse::count();
        if ($verseCount > 0) {
            $this->verse = QuranVerse::skip($dayOfYear % $verseCount)->first();
        }

        // 2. Fetch Hijri date and cache it
        $this->loadHijriDateAndRamadanCountdown();

        // 3. Load user zikirs & goals progress
        $this->loadZikirs();
        $this->calculateGoalsProgress();
    }

    public function loadZikirs()
    {
        // Global presets (user_id is null) + User custom items
        $this->zikirs = ZikirItem::whereNull('user_id')
            ->orWhere('user_id', Auth::id())
            ->get();
    }

    public function loadHijriDateAndRamadanCountdown()
    {
        $today = date('Y-m-d');
        $cacheKey = "hijri_data_{$today}";

        $hijriData = Cache::remember($cacheKey, 86400, function () {
            try {
                // Try fetching timings for Dhaka as default
                $response = Http::get("https://api.aladhan.com/v1/timingsByCity/" . date('d-m-Y') . "?city=Dhaka&country=Bangladesh");
                if ($response->successful()) {
                    $data = $response->json()['data']['date']['hijri'];
                    return [
                        'day' => $data['day'],
                        'month_ar' => $data['month']['ar'],
                        'month_en' => $data['month']['en'],
                        'year' => $data['year'],
                        'date' => $data['date']
                    ];
                }
            } catch (\Exception $e) {
                // Fallback handled below
            }
            return null;
        });

        if ($hijriData) {
            $lang = app()->getLocale();
            if ($lang === 'bn') {
                $monthsBn = [
                    'Muharram' => 'মুহাররাম', 'Safar' => 'সফর', 'Rabi\' al-awwal' => 'রবিউল আউয়াল',
                    'Rabi\' al-thani' => 'রবিউস সানি', 'Jumada al-ula' => 'জুমাদাল উলা', 'Jumada al-akhirah' => 'জুমাদাল আখিরাহ',
                    'Rajab' => 'রজব', 'Sha\'ban' => 'শাবান', 'Ramadan' => 'রমজান', 'Shawwal' => 'শাওয়াল',
                    'Dhu al-qa\'dah' => 'জিলকদ', 'Dhu al-hijjah' => 'জিলহজ্জ'
                ];
                $monthName = $monthsBn[$hijriData['month_en']] ?? $hijriData['month_en'];
                // Convert numbers to Bengali
                $engNum = ['0','1','2','3','4','5','6','7','8','9'];
                $bangNum = ['০','১','২','৩','৪','৫','৬','৭','৮','৯'];
                $dayBn = str_replace($engNum, $bangNum, $hijriData['day']);
                $yearBn = str_replace($engNum, $bangNum, $hijriData['year']);
                $this->hijriDateStr = "{$dayBn} {$monthName}, {$yearBn} হিজরি";
            } else {
                $this->hijriDateStr = "{$hijriData['day']} {$hijriData['month_en']}, {$hijriData['year']} AH";
            }
            
            // Calculate approximate Ramadan countdown
            // Ramadan is month 9.
            $currentMonth = (int)date('m', strtotime($hijriData['date'])); // Note: Aladhan date format might vary, let's parse safely
            // For countdown, we approximate since exact starts depend on moon sighting:
            // Average Hijri year is 354.36 days. Average Hijri month is 29.53 days.
            // Let's compute based on current Hijri month & day:
            $currentHijriMonth = 0;
            $monthsList = ['Muharram', 'Safar', 'Rabi\' al-awwal', 'Rabi\' al-thani', 'Jumada al-ula', 'Jumada al-akhirah', 'Rajab', 'Sha\'ban', 'Ramadan', 'Shawwal', 'Dhu al-qa\'dah', 'Dhu al-hijjah'];
            $monthIndex = array_search($hijriData['month_en'], $monthsList);
            if ($monthIndex !== false) {
                $currentHijriMonth = $monthIndex + 1;
            }

            $currentHijriDay = (int)$hijriData['day'];
            
            if ($currentHijriMonth < 9) {
                // Ramadan in current Hijri year
                $monthsDiff = 9 - $currentHijriMonth;
                $this->ramadanDaysLeft = (int)($monthsDiff * 29.5) - $currentHijriDay + 1;
            } elseif ($currentHijriMonth == 9) {
                $this->ramadanDaysLeft = 0; // It is currently Ramadan!
            } else {
                // Ramadan in next Hijri year
                $monthsDiff = (12 - $currentHijriMonth) + 9;
                $this->ramadanDaysLeft = (int)($monthsDiff * 29.5) - $currentHijriDay + 1;
            }
        } else {
            // Static Fallback
            $this->hijriDateStr = app()->getLocale() === 'bn' ? '১২ রবিউল আউয়াল, ১৪৪৮ হিজরি' : '12 Rabi\' al-awwal, 1448 AH';
            $this->ramadanDaysLeft = 240; // Default approximation
        }
    }

    public function calculateGoalsProgress()
    {
        $userId = Auth::id();
        $today = date('Y-m-d');
        
        // Find if there are goals for today
        $goals = DailyGoal::where('user_id', $userId)->where('date', $today)->get();
        
        if ($goals->isEmpty()) {
            // Fallback: calculate progress dynamically based on completed actions (similar to guest mode)
            $activeZikirs = ZikirCount::where('user_id', $userId)
                ->where('counted_at', $today)
                ->where('count', '>', 0)
                ->distinct()
                ->count('zikir_item_id');
            
            $activeSalah = SalahLog::where('user_id', $userId)
                ->where('date', $today)
                ->whereIn('status', ['jamaat', 'alone'])
                ->count();
                
            $totalActions = $activeZikirs + $activeSalah;
            $this->goalsProgress = $totalActions > 0 ? min(100, $totalActions * 20) : 0;
            return;
        }

        $totalProgressSum = 0;
        foreach ($goals as $goal) {
            $actual = 0;
            if ($goal->goal_type === 'zikir') {
                $actual = ZikirCount::where('user_id', $userId)
                    ->where('zikir_item_id', $goal->zikir_item_id)
                    ->where('counted_at', $today)
                    ->sum('count');
            } elseif ($goal->goal_type === 'salah') {
                $actual = SalahLog::where('user_id', $userId)
                    ->where('date', $today)
                    ->whereIn('status', ['jamaat', 'alone'])
                    ->count();
            } elseif ($goal->goal_type === 'quran') {
                $actual = QuranProgress::where('user_id', $userId)
                    ->where('date', $today)
                    ->sum('pages_read');
            }

            $target = $goal->target_value;
            if ($target > 0) {
                $totalProgressSum += min(100, ($actual / $target) * 100);
            }
        }

        $this->goalsProgress = $goals->count() > 0 ? (int)($totalProgressSum / $goals->count()) : 0;
    }

    public function saveCustomZikir()
    {
        $this->validate();

        ZikirItem::create([
            'user_id' => Auth::id(),
            'name_bn' => $this->customNameBn,
            'name_en' => $this->customNameEn,
            'arabic_text' => $this->customArabic,
            'default_target' => $this->customTarget,
            'icon' => $this->customIcon,
            'is_custom' => true
        ]);

        $this->reset(['customNameBn', 'customNameEn', 'customArabic', 'customTarget', 'customIcon', 'showModal']);
        $this->loadZikirs();
        
        session()->flash('success', app()->getLocale() === 'bn' ? 'নতুন আমল সফলভাবে যোগ করা হয়েছে!' : 'Custom Amal added successfully!');
    }

    public function render()
    {
        return view('livewire.home-grid')->layout('layouts.app');
    }
}
