<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\ZikirCount;
use App\Models\SalahLog;
use App\Models\HabitLog;
use App\Models\Streak;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CalculateStreaks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'streaks:calculate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculate user daily streaks based on spiritual activity logs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::all();
        $todayStr = date('Y-m-d');
        $yesterdayStr = Carbon::yesterday()->format('Y-m-d');

        foreach ($users as $user) {
            // Find or create daily streak record for this user
            $streak = Streak::firstOrCreate([
                'user_id' => $user->id,
                'streak_type' => 'daily'
            ]);

            // Get all unique dates when the user performed any spiritual activity
            $zikirDates = ZikirCount::where('user_id', $user->id)->pluck('counted_at')->map(fn($d) => $d instanceof Carbon ? $d->format('Y-m-d') : (string)$d)->toArray();
            $salahDates = SalahLog::where('user_id', $user->id)->whereIn('status', ['jamaat', 'alone'])->pluck('date')->map(fn($d) => $d instanceof Carbon ? $d->format('Y-m-d') : (string)$d)->toArray();
            $habitDates = HabitLog::where('user_id', $user->id)->where('completed', true)->pluck('date')->map(fn($d) => $d instanceof Carbon ? $d->format('Y-m-d') : (string)$d)->toArray();

            $allDates = array_unique(array_merge($zikirDates, $salahDates, $habitDates));
            rsort($allDates); // Sort descending (newest first)

            $currentStreak = 0;
            $longestStreak = $streak->longest_count;

            $hasActivityToday = in_array($todayStr, $allDates);
            $hasActivityYesterday = in_array($yesterdayStr, $allDates);

            if ($hasActivityToday || $hasActivityYesterday) {
                // If active today, we count backwards starting today.
                // If only active yesterday (and not yet today), we count backwards starting yesterday.
                $checkDate = $hasActivityToday ? Carbon::today() : Carbon::yesterday();
                
                while (true) {
                    $dateStr = $checkDate->format('Y-m-d');
                    if (in_array($dateStr, $allDates)) {
                        $currentStreak++;
                        $checkDate->subDay();
                    } else {
                        break;
                    }
                }
            }

            if ($currentStreak > $longestStreak) {
                $longestStreak = $currentStreak;
            }

            $streak->update([
                'current_count' => $currentStreak,
                'longest_count' => $longestStreak,
                'last_active_date' => $currentStreak > 0 ? ($hasActivityToday ? $todayStr : $yesterdayStr) : $streak->last_active_date
            ]);
        }

        $this->info('Daily streaks calculated successfully!');
    }
}
