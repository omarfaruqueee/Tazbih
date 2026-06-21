<?php

namespace App\Http\Controllers;

use App\Models\ZikirItem;
use App\Models\ZikirCount;
use App\Models\SalahLog;
use App\Models\QuranProgress;
use App\Models\HabitLog;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GuestMigrationController extends Controller
{
    public function migrate(Request $request)
    {
        $userId = Auth::id();
        $payload = $request->json()->all();

        if (empty($payload)) {
            return response()->json(['status' => 'empty']);
        }

        $zikirIdMap = []; // Maps local storage string IDs to database integer IDs

        // 1. Migrate custom Zikir items
        if (isset($payload['custom_zikirs']) && is_array($payload['custom_zikirs'])) {
            foreach ($payload['custom_zikirs'] as $item) {
                $dbItem = ZikirItem::create([
                    'user_id' => $userId,
                    'name_bn' => $item['name_bn'],
                    'name_en' => $item['name_en'],
                    'arabic_text' => $item['arabic_text'] ?? null,
                    'default_target' => $item['default_target'] ?? 33,
                    'icon' => $item['icon'] ?? 'sparkles',
                    'is_custom' => true
                ]);
                $zikirIdMap[$item['id']] = $dbItem->id;
            }
        }

        // 2. Migrate Zikir counts
        if (isset($payload['zikir_counts']) && is_array($payload['zikir_counts'])) {
            foreach ($payload['zikir_counts'] as $countLog) {
                $itemId = $countLog['zikir_item_id'];
                
                // If it is a guest custom item, map it to the database ID
                if (isset($zikirIdMap[$itemId])) {
                    $itemId = $zikirIdMap[$itemId];
                }

                if (is_numeric($itemId)) {
                    $log = ZikirCount::firstOrCreate([
                        'user_id' => $userId,
                        'zikir_item_id' => (int)$itemId,
                        'counted_at' => $countLog['counted_at'],
                    ], [
                        'count' => 0
                    ]);
                    $log->increment('count', (int)$countLog['count']);
                }
            }
        }

        // 3. Migrate Salah logs
        if (isset($payload['salah_logs']) && is_array($payload['salah_logs'])) {
            foreach ($payload['salah_logs'] as $salah) {
                if (isset($salah['prayer_name']) && isset($salah['status']) && isset($salah['date'])) {
                    SalahLog::updateOrCreate([
                        'user_id' => $userId,
                        'prayer_name' => $salah['prayer_name'],
                        'date' => $salah['date']
                    ], [
                        'status' => $salah['status']
                    ]);
                }
            }
        }

        // 4. Migrate Quran progress
        if (isset($payload['quran_progress']) && is_array($payload['quran_progress'])) {
            foreach ($payload['quran_progress'] as $quran) {
                if (isset($quran['pages_read']) && isset($quran['date'])) {
                    QuranProgress::updateOrCreate([
                        'user_id' => $userId,
                        'date' => $quran['date']
                    ], [
                        'pages_read' => $quran['pages_read'],
                        'para_completed' => $quran['para_completed'] ?? null
                    ]);
                }
            }
        }

        // 5. Migrate Habit logs
        if (isset($payload['habit_logs']) && is_array($payload['habit_logs'])) {
            foreach ($payload['habit_logs'] as $habit) {
                if (isset($habit['habit_type']) && isset($habit['completed']) && isset($habit['date'])) {
                    HabitLog::updateOrCreate([
                        'user_id' => $userId,
                        'habit_type' => $habit['habit_type'],
                        'date' => $habit['date']
                    ], [
                        'completed' => (bool)$habit['completed']
                    ]);
                }
            }
        }

        // 6. Migrate Journal entries
        if (isset($payload['journal_entries']) && is_array($payload['journal_entries'])) {
            foreach ($payload['journal_entries'] as $entry) {
                if (isset($entry['mood']) && isset($entry['reflection_text']) && isset($entry['date'])) {
                    JournalEntry::updateOrCreate([
                        'user_id' => $userId,
                        'date' => $entry['date']
                    ], [
                        'mood' => $entry['mood'],
                        'reflection_text' => $entry['reflection_text']
                    ]);
                }
            }
        }

        // 7. Migrate settings
        if (isset($payload['settings']) && is_array($payload['settings'])) {
            foreach ($payload['settings'] as $key => $value) {
                if ($value !== null) {
                    if ($key === 'theme' || $key === 'locale') {
                        $user = Auth::user();
                        if ($key === 'theme') {
                            $user->update(['theme' => $value]);
                        } elseif ($key === 'locale') {
                            $user->update(['language_preference' => $value]);
                        }
                    } else {
                        \App\Models\Setting::updateOrCreate([
                            'user_id' => $userId,
                            'key' => $key
                        ], [
                            'value' => $value
                        ]);
                    }
                }
            }
        }

        return response()->json(['status' => 'success', 'mapped_items' => count($zikirIdMap)]);
    }
}
