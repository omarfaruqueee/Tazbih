<?php

namespace App\Livewire;

use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PrayerLockOverlay extends Component
{
    public $isLocked = false;
    public $prayerName = '';
    public $secondsRemaining = 0;
    public $lockStartTime = '';

    public function checkLockStatus()
    {
        if (!Auth::check()) {
            $this->isLocked = false;
            return;
        }

        $userId = Auth::id();
        $today = date('Y-m-d');
        
        // 1. Get coordinates & method from Settings
        $lat = Setting::where('user_id', $userId)->where('key', 'location_latitude')->value('value') ?? '23.8103';
        $lon = Setting::where('user_id', $userId)->where('key', 'location_longitude')->value('value') ?? '90.4125';
        $method = Setting::where('user_id', $userId)->where('key', 'prayer_method')->value('value') ?? '3';

        // 2. Fetch timings from cache or Aladhan API
        $cacheKey = "prayer_timings_{$userId}_{$today}";
        $timings = Cache::remember($cacheKey, 86400, function () use ($lat, $lon, $method) {
            try {
                $dateStr = date('d-m-Y');
                $response = Http::get("https://api.aladhan.com/v1/timings/{$dateStr}", [
                    'latitude' => $lat,
                    'longitude' => $lon,
                    'method' => $method
                ]);
                
                if ($response->successful()) {
                    return $response->json()['data']['timings'];
                }
            } catch (\Exception $e) {
                // Fallback logged below
            }
            // Fallback default timings
            return [
                'Fajr' => '04:15',
                'Dhuhr' => '12:02',
                'Asr' => '15:45',
                'Maghrib' => '18:50',
                'Isha' => '20:15'
            ];
        });

        // 3. Evaluate if we are inside a 15-minute lock window
        $now = Carbon::now();
        $prayers = ['Fajr', 'Dhuhr', 'Asr', 'Maghrib', 'Isha'];
        
        foreach ($prayers as $p) {
            if (!isset($timings[$p])) continue;
            
            // Clean time string (sometimes contains timezone like "04:15 (EEST)")
            $timeStr = explode(' ', $timings[$p])[0];
            
            $prayerTime = Carbon::createFromFormat('Y-m-d H:i', "{$today} {$timeStr}");
            $lockEndTime = (clone $prayerTime)->addMinutes(15);
            
            if ($now->greaterThanOrEqualTo($prayerTime) && $now->lessThanOrEqualTo($lockEndTime)) {
                $this->isLocked = true;
                $this->prayerName = $p;
                $this->secondsRemaining = $now->diffInSeconds($lockEndTime);
                $this->lockStartTime = $prayerTime->format('H:i');
                return;
            }
        }

        $this->isLocked = false;
    }

    public function render()
    {
        $this->checkLockStatus();
        return view('livewire.prayer-lock-overlay');
    }
}
