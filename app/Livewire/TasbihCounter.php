<?php

namespace App\Livewire;

use App\Models\ZikirItem;
use App\Models\ZikirCount;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TasbihCounter extends Component
{
    public $zikirItemId;
    public $count = 0;
    public $totalSaved = 0;
    public $soundOn = true;
    public $vibeOn = true;
    public $autoReset = true;
    
    public $zikirItems = [];
    public $activeZikir;

    public function mount()
    {
        if (Auth::check() && Auth::user()->isFeatureDisabled('tasbih')) {
            abort(403, 'This feature has been disabled by the administrator.');
        }

        // Public presets (user_id is null)
        $this->zikirItems = ZikirItem::whereNull('user_id')->get();

        // Get item_id from request query, otherwise default to first zikir
        $itemId = request()->query('item_id');
        if ($itemId) {
            $this->zikirItemId = $itemId;
        } else {
            $this->zikirItemId = $this->zikirItems->first() ? $this->zikirItems->first()->id : null;
        }

        $this->loadZikirItemDetails();
    }

    public function loadZikirItemDetails()
    {
        if ($this->zikirItemId) {
            $this->activeZikir = ZikirItem::find($this->zikirItemId);
            
            // If it is a guest zikir item (starts with guest_)
            if (!$this->activeZikir && str_starts_with($this->zikirItemId, 'guest_')) {
                $this->activeZikir = new ZikirItem([
                    'name_bn' => 'কাস্টম আমল',
                    'name_en' => 'Custom Amal',
                    'default_target' => 33,
                    'icon' => 'sparkles',
                    'is_custom' => true
                ]);
                $this->activeZikir->id = $this->zikirItemId;
            }

            if (Auth::check()) {
                // Fetch total saved for today in DB for authenticated user
                $today = date('Y-m-d');
                $this->totalSaved = ZikirCount::where('user_id', Auth::id())
                    ->where('zikir_item_id', $this->zikirItemId)
                    ->where('counted_at', $today)
                    ->sum('count');
            } else {
                $this->totalSaved = 0; // Will be handled on client side for guest
            }
        }
    }

    public function updatedZikirItemId()
    {
        $this->count = 0; // Reset session count on change
        $this->loadZikirItemDetails();
        
        // Dispatch browser event to let Alpine update its local target and count
        $this->dispatch('zikir-changed', [
            'id' => $this->zikirItemId,
            'name_bn' => $this->activeZikir->name_bn ?? 'কাস্টম আমল',
            'name_en' => $this->activeZikir->name_en ?? 'Custom Amal',
            'default_target' => $this->activeZikir->default_target ?? 33
        ]);
    }

    public function increment()
    {
        if (!Auth::check() || !$this->zikirItemId) return;

        // Increment session count
        $this->count++;
        
        // Save to Database (Cloud Sync in real-time)
        $today = date('Y-m-d');
        $zikirLog = ZikirCount::firstOrCreate([
            'user_id' => Auth::id(),
            'zikir_item_id' => $this->zikirItemId,
            'counted_at' => $today,
        ], [
            'count' => 0
        ]);
        $zikirLog->increment('count', 1);
        $this->totalSaved = $zikirLog->count;

        $target = $this->activeZikir->default_target;

        // Trigger vibration feedback
        if ($this->vibeOn) {
            $this->dispatch('trigger-vibration', duration: 50);
        }

        // Trigger sound feedback or vibration on target reach
        if ($this->count >= $target) {
            if ($this->soundOn) {
                $this->dispatch('trigger-target-sound');
            }
            if ($this->vibeOn) {
                $this->dispatch('trigger-vibration', duration: 300);
            }
            
            if ($this->autoReset) {
                $this->count = 0;
            }
        }
    }

    public function resetCounter()
    {
        $this->count = 0;
    }

    public function render()
    {
        return view('livewire.tasbih-counter')->layout('layouts.app');
    }
}
