<?php

namespace App\Http\Controllers;

use App\Models\ZikirCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfflineSyncController extends Controller
{
    public function sync(Request $request)
    {
        $userId = Auth::id();
        $payload = $request->json()->all();

        if (empty($payload)) {
            return response()->json(['status' => 'empty']);
        }

        foreach ($payload as $item) {
            $zikirItemId = $item['zikir_item_id'] ?? null;
            $count = (int)($item['count'] ?? 0);
            $date = $item['counted_at'] ?? date('Y-m-d');

            if ($zikirItemId && $count > 0) {
                // Upsert daily log in MySQL
                $log = ZikirCount::firstOrCreate([
                    'user_id' => $userId,
                    'zikir_item_id' => $zikirItemId,
                    'counted_at' => $date,
                ], [
                    'count' => 0
                ]);
                $log->increment('count', $count);
            }
        }

        return response()->json(['status' => 'success', 'synced' => count($payload)]);
    }
}
