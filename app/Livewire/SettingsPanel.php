<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\ZikirCount;
use App\Models\SalahLog;
use App\Models\HabitLog;
use App\Models\JournalEntry;
use App\Models\Setting;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class SettingsPanel extends Component
{
    public $name;
    public $email;
    public $languagePreference;
    public $themePreference;
    
    // Geolocation / calculation
    public $latitude = '23.8103'; // Default Dhaka
    public $longitude = '90.4125';
    public $prayerMethod = '3';

    // Tracker module states
    public $salahEnabled = true;
    public $quranEnabled = true;
    public $habitEnabled = true;
    public $journalEnabled = true;

    // Amal Alert configuration states
    public $alertMorningEnabled = true;
    public $alertAfternoonEnabled = true;
    public $alertEveningEnabled = true;
    public $alertPushEnabled = true;
    public $alertVibrationEnabled = true;
    public $alertSoundEnabled = true;
    public $alertMorningTime = '07:00';
    public $alertAfternoonTime = '13:30';
    public $alertEveningTime = '18:30';
    public $alertRingtone = 'chime';
    public $alertVolume = 50;

    public function mount()
    {
        if (Auth::check()) {
            $user = Auth::user();
            $this->name = $user->name;
            $this->email = $user->email;
            $this->languagePreference = $user->language_preference ?? 'bn';
            $this->themePreference = $user->theme ?? 'light';

            // Load settings
            $latSetting = Setting::where('user_id', $user->id)->where('key', 'location_latitude')->first();
            if ($latSetting) $this->latitude = $latSetting->value;

            $lonSetting = Setting::where('user_id', $user->id)->where('key', 'location_longitude')->first();
            if ($lonSetting) $this->longitude = $lonSetting->value;

            $methodSetting = Setting::where('user_id', $user->id)->where('key', 'prayer_method')->first();
            if ($methodSetting) $this->prayerMethod = $methodSetting->value;

            // Load module settings (default to 'true' if not set)
            $salahSet = Setting::where('user_id', $user->id)->where('key', 'salah_tracker_enabled')->first();
            $this->salahEnabled = $salahSet ? filter_var($salahSet->value, FILTER_VALIDATE_BOOLEAN) : true;

            $quranSet = Setting::where('user_id', $user->id)->where('key', 'quran_tracker_enabled')->first();
            $this->quranEnabled = $quranSet ? filter_var($quranSet->value, FILTER_VALIDATE_BOOLEAN) : true;

            $habitSet = Setting::where('user_id', $user->id)->where('key', 'habit_tracker_enabled')->first();
            $this->habitEnabled = $habitSet ? filter_var($habitSet->value, FILTER_VALIDATE_BOOLEAN) : true;

            $journalSet = Setting::where('user_id', $user->id)->where('key', 'journal_tracker_enabled')->first();
            $this->journalEnabled = $journalSet ? filter_var($journalSet->value, FILTER_VALIDATE_BOOLEAN) : true;

            // Load Amal Alert settings (default to 'true' if not set)
            $mornAlert = Setting::where('user_id', $user->id)->where('key', 'alert_morning_enabled')->first();
            $this->alertMorningEnabled = $mornAlert ? filter_var($mornAlert->value, FILTER_VALIDATE_BOOLEAN) : true;

            $aftAlert = Setting::where('user_id', $user->id)->where('key', 'alert_afternoon_enabled')->first();
            $this->alertAfternoonEnabled = $aftAlert ? filter_var($aftAlert->value, FILTER_VALIDATE_BOOLEAN) : true;

            $eveAlert = Setting::where('user_id', $user->id)->where('key', 'alert_evening_enabled')->first();
            $this->alertEveningEnabled = $eveAlert ? filter_var($eveAlert->value, FILTER_VALIDATE_BOOLEAN) : true;

            $pushAlert = Setting::where('user_id', $user->id)->where('key', 'alert_push_enabled')->first();
            $this->alertPushEnabled = $pushAlert ? filter_var($pushAlert->value, FILTER_VALIDATE_BOOLEAN) : true;

            $vibAlert = Setting::where('user_id', $user->id)->where('key', 'alert_vibration_enabled')->first();
            $this->alertVibrationEnabled = $vibAlert ? filter_var($vibAlert->value, FILTER_VALIDATE_BOOLEAN) : true;

            $soundAlert = Setting::where('user_id', $user->id)->where('key', 'alert_sound_enabled')->first();
            $this->alertSoundEnabled = $soundAlert ? filter_var($soundAlert->value, FILTER_VALIDATE_BOOLEAN) : true;

            $mornTime = Setting::where('user_id', $user->id)->where('key', 'alert_morning_time')->first();
            $this->alertMorningTime = $mornTime ? $mornTime->value : '07:00';

            $aftTime = Setting::where('user_id', $user->id)->where('key', 'alert_afternoon_time')->first();
            $this->alertAfternoonTime = $aftTime ? $aftTime->value : '13:30';

            $eveTime = Setting::where('user_id', $user->id)->where('key', 'alert_evening_time')->first();
            $this->alertEveningTime = $eveTime ? $eveTime->value : '18:30';

            $ringtone = Setting::where('user_id', $user->id)->where('key', 'alert_ringtone')->first();
            $this->alertRingtone = $ringtone ? $ringtone->value : 'chime';

            $volumeSetting = Setting::where('user_id', $user->id)->where('key', 'alert_volume')->first();
            $this->alertVolume = $volumeSetting ? (int)$volumeSetting->value : 50;

            // Admin overrides
            if ($user->isFeatureDisabled('salah')) $this->salahEnabled = false;
            if ($user->isFeatureDisabled('quran')) $this->quranEnabled = false;
            if ($user->isFeatureDisabled('habits')) $this->habitEnabled = false;
            if ($user->isFeatureDisabled('journal')) $this->journalEnabled = false;
        } else {
            $this->name = app()->getLocale() === 'bn' ? 'মেহমান ব্যবহারকারী' : 'Guest User';
            $this->email = 'guest@hasanattracker.local';
            $this->languagePreference = session()->get('locale', 'bn');
            $this->themePreference = session()->get('theme', 'light');

            $this->salahEnabled = session()->get('salah_tracker_enabled', true);
            $this->quranEnabled = session()->get('quran_tracker_enabled', true);
            $this->habitEnabled = session()->get('habit_tracker_enabled', true);
            $this->journalEnabled = session()->get('journal_tracker_enabled', true);

            $this->alertMorningEnabled = session()->get('alert_morning_enabled', true);
            $this->alertAfternoonEnabled = session()->get('alert_afternoon_enabled', true);
            $this->alertEveningEnabled = session()->get('alert_evening_enabled', true);
            $this->alertPushEnabled = session()->get('alert_push_enabled', true);
            $this->alertVibrationEnabled = session()->get('alert_vibration_enabled', true);
            $this->alertSoundEnabled = session()->get('alert_sound_enabled', true);

            $this->alertMorningTime = session()->get('alert_morning_time', '07:00');
            $this->alertAfternoonTime = session()->get('alert_afternoon_time', '13:30');
            $this->alertEveningTime = session()->get('alert_evening_time', '18:30');
            $this->alertRingtone = session()->get('alert_ringtone', 'chime');
            $this->alertVolume = (int)session()->get('alert_volume', 50);
        }
    }

    public function savePreferences()
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Update user table
            $user->update([
                'language_preference' => $this->languagePreference,
                'theme' => $this->themePreference
            ]);

            // Update settings table
            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'location_latitude'],
                ['value' => $this->latitude]
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'location_longitude'],
                ['value' => $this->longitude]
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'prayer_method'],
                ['value' => $this->prayerMethod]
            );

            $salahVal = $user->isFeatureDisabled('salah') ? 'false' : ($this->salahEnabled ? 'true' : 'false');
            $quranVal = $user->isFeatureDisabled('quran') ? 'false' : ($this->quranEnabled ? 'true' : 'false');
            $habitVal = $user->isFeatureDisabled('habits') ? 'false' : ($this->habitEnabled ? 'true' : 'false');
            $journalVal = $user->isFeatureDisabled('journal') ? 'false' : ($this->journalEnabled ? 'true' : 'false');

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'salah_tracker_enabled'],
                ['value' => $salahVal]
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'quran_tracker_enabled'],
                ['value' => $quranVal]
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'habit_tracker_enabled'],
                ['value' => $habitVal]
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'journal_tracker_enabled'],
                ['value' => $journalVal]
            );

            if ($user->isFeatureDisabled('salah')) $this->salahEnabled = false;
            if ($user->isFeatureDisabled('quran')) $this->quranEnabled = false;
            if ($user->isFeatureDisabled('habits')) $this->habitEnabled = false;
            if ($user->isFeatureDisabled('journal')) $this->journalEnabled = false;

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_morning_enabled'],
                ['value' => $this->alertMorningEnabled ? 'true' : 'false']
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_afternoon_enabled'],
                ['value' => $this->alertAfternoonEnabled ? 'true' : 'false']
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_evening_enabled'],
                ['value' => $this->alertEveningEnabled ? 'true' : 'false']
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_push_enabled'],
                ['value' => $this->alertPushEnabled ? 'true' : 'false']
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_vibration_enabled'],
                ['value' => $this->alertVibrationEnabled ? 'true' : 'false']
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_sound_enabled'],
                ['value' => $this->alertSoundEnabled ? 'true' : 'false']
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_morning_time'],
                ['value' => $this->alertMorningTime]
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_afternoon_time'],
                ['value' => $this->alertAfternoonTime]
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_evening_time'],
                ['value' => $this->alertEveningTime]
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_ringtone'],
                ['value' => $this->alertRingtone]
            );

            Setting::updateOrCreate(
                ['user_id' => $user->id, 'key' => 'alert_volume'],
                ['value' => (string)$this->alertVolume]
            );
        } else {
            // Save in session for guest
            session()->put('theme', $this->themePreference);
            session()->put('salah_tracker_enabled', (bool)$this->salahEnabled);
            session()->put('quran_tracker_enabled', (bool)$this->quranEnabled);
            session()->put('habit_tracker_enabled', (bool)$this->habitEnabled);
            session()->put('journal_tracker_enabled', (bool)$this->journalEnabled);

            session()->put('alert_morning_enabled', (bool)$this->alertMorningEnabled);
            session()->put('alert_afternoon_enabled', (bool)$this->alertAfternoonEnabled);
            session()->put('alert_evening_enabled', (bool)$this->alertEveningEnabled);
            session()->put('alert_push_enabled', (bool)$this->alertPushEnabled);
            session()->put('alert_vibration_enabled', (bool)$this->alertVibrationEnabled);
            session()->put('alert_sound_enabled', (bool)$this->alertSoundEnabled);

            session()->put('alert_morning_time', $this->alertMorningTime);
            session()->put('alert_afternoon_time', $this->alertAfternoonTime);
            session()->put('alert_evening_time', $this->alertEveningTime);
            session()->put('alert_ringtone', $this->alertRingtone);
            session()->put('alert_volume', (int)$this->alertVolume);
        }

        session()->put('locale', $this->languagePreference);
        app()->setLocale($this->languagePreference);

        session()->flash('settings_success', app()->getLocale() === 'bn' ? 'সেটিংস সফলভাবে সংরক্ষিত হয়েছে!' : 'Settings saved successfully!');
        
        return redirect()->route('settings');
    }

    public function exportJson()
    {
        if (!Auth::check()) return null;

        $userId = Auth::id();
        $data = [
            'user' => Auth::user()->toArray(),
            'zikir_counts' => ZikirCount::where('user_id', $userId)->get()->toArray(),
            'salah_logs' => SalahLog::where('user_id', $userId)->get()->toArray(),
            'habit_logs' => HabitLog::where('user_id', $userId)->get()->toArray(),
            'journal_entries' => JournalEntry::where('user_id', $userId)->get()->toArray(),
            'settings' => Setting::where('user_id', $userId)->get()->toArray(),
        ];

        $filename = "hasanat_backup_" . date('Y-m-d') . ".json";
        
        return response()->streamDownload(function () use ($data) {
            echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }, $filename, [
            'Content-Type' => 'application/json',
        ]);
    }

    public function exportCsv()
    {
        if (!Auth::check()) return null;

        $userId = Auth::id();
        $logs = ZikirCount::where('user_id', $userId)
            ->with('zikirItem')
            ->orderBy('counted_at', 'desc')
            ->get();

        $filename = "zikir_counts_" . date('Y-m-d') . ".csv";

        return response()->streamDownload(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, ['Date/তারিখ', 'Zikir (EN)', 'জিকির (বাংলা)', 'Count/সংখ্যা']);

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->counted_at,
                    $log->zikirItem->name_en ?? 'N/A',
                    $log->zikirItem->name_bn ?? 'N/A',
                    $log->count
                ]);
            }
            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect()->route('login');
    }

    public function render()
    {
        return view('livewire.settings-panel')->layout('layouts.app');
    }
}
