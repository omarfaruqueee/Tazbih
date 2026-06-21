<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'email', 'password', 'language_preference', 'theme'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function zikirItems(): HasMany
    {
        return $this->hasMany(ZikirItem::class);
    }

    public function zikirCounts(): HasMany
    {
        return $this->hasMany(ZikirCount::class);
    }

    public function dailyGoals(): HasMany
    {
        return $this->hasMany(DailyGoal::class);
    }

    public function quranProgress(): HasMany
    {
        return $this->hasMany(QuranProgress::class);
    }

    public function salahLogs(): HasMany
    {
        return $this->hasMany(SalahLog::class);
    }

    public function habitLogs(): HasMany
    {
        return $this->hasMany(HabitLog::class);
    }

    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    public function streaks(): HasMany
    {
        return $this->hasMany(Streak::class);
    }

    public function settings(): HasMany
    {
        return $this->hasMany(Setting::class);
    }

    public function isFeatureDisabled(string $feature): bool
    {
        $disabled = \Illuminate\Support\Facades\Cache::rememberForever("user_{$this->id}_disabled_features", function() {
            $setting = $this->settings()->where('key', 'disabled_features')->first();
            if ($setting && $setting->value) {
                return json_decode($setting->value, true) ?: [];
            }
            return [];
        });
        
        return in_array($feature, $disabled);
    }
}
