<?php

namespace App\Livewire\Admin;

use App\Models\User;
use App\Models\ZikirCount;
use App\Models\SalahLog;
use App\Models\QuranProgress;
use App\Models\HabitLog;
use App\Models\JournalEntry;
use App\Models\DailyGoal;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminDashboard extends Component
{
    use WithPagination;

    public $activeTab = 'overview';
    public $searchUser = '';
    public $selectedUserId = null;
    
    // Stats Cache
    public $stats = [];
    public $trafficTrend = [];
    public $selectedUserDetail = null;

    public function mount()
    {
        // Enforce admin permission
        if (!Auth::check() || Auth::user()->email !== 'omarfaruuuk@gmail.com') {
            abort(403, 'Unauthorized access.');
        }

        $this->loadOverviewStats();
    }

    public function updatedActiveTab()
    {
        if ($this->activeTab === 'overview') {
            $this->loadOverviewStats();
        }
        $this->resetPage();
    }

    public function updatedSearchUser()
    {
        $this->resetPage();
    }

    public function loadOverviewStats()
    {
        $this->stats = [
            'total_users' => User::count(),
            'active_sessions' => DB::table('sessions')->where('last_activity', '>=', time() - 900)->count(),
            'total_zikirs' => (int)ZikirCount::sum('count'),
            'total_salah' => SalahLog::count(),
            'total_quran_pages' => (int)QuranProgress::sum('pages_read'),
            'total_habits' => HabitLog::where('completed', true)->count(),
            'total_journals' => JournalEntry::count(),
            'total_goals' => DailyGoal::count(),
        ];

        // Fetch last 7 days page views from traffic logs
        $driver = DB::getDriverName();
        $dateExpression = $driver === 'sqlite'
            ? "strftime('%Y-%m-%d', created_at)"
            : "DATE_FORMAT(created_at, '%Y-%m-%d')";

        $traffic = DB::table('traffic_logs')
            ->select(DB::raw("$dateExpression as date"), DB::raw('count(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(7)
            ->get();

        $dates = [];
        $counts = [];

        // Reverse to show chronological order
        foreach ($traffic->reverse() as $row) {
            $dates[] = $row->date;
            $counts[] = (int)$row->count;
        }

        $this->trafficTrend = [
            'labels' => $dates,
            'data' => $counts,
        ];
    }

    public function selectUser($userId)
    {
        $this->selectedUserId = $userId;
        $user = User::findOrFail($userId);

        // Fetch user stats summary
        $zikirCount = ZikirCount::where('user_id', $userId)->sum('count');
        $salahCount = SalahLog::where('user_id', $userId)->count();
        $quranPages = QuranProgress::where('user_id', $userId)->sum('pages_read');
        $habitCount = HabitLog::where('user_id', $userId)->where('completed', true)->count();
        $journalCount = JournalEntry::where('user_id', $userId)->count();

        // Fetch recent logs
        $recentZikir = ZikirCount::where('user_id', $userId)
            ->with('zikirItem')
            ->orderBy('counted_at', 'desc')
            ->take(5)
            ->get()
            ->toArray();

        $recentSalah = SalahLog::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->orderBy('prayer_name', 'asc')
            ->take(5)
            ->get()
            ->toArray();

        $recentQuran = QuranProgress::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get()
            ->toArray();

        $recentHabits = HabitLog::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get()
            ->toArray();

        $recentJournal = JournalEntry::where('user_id', $userId)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get()
            ->toArray();

        // Fetch disabled features list
        $disabledFeaturesSetting = $user->settings()->where('key', 'disabled_features')->first();
        $disabledFeatures = [];
        if ($disabledFeaturesSetting && $disabledFeaturesSetting->value) {
            $disabledFeatures = json_decode($disabledFeaturesSetting->value, true) ?: [];
        }

        $this->selectedUserDetail = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => $user->created_at->format('Y-m-d H:i'),
            'zikir_sum' => (int)$zikirCount,
            'salah_count' => $salahCount,
            'quran_pages' => (int)$quranPages,
            'habit_count' => $habitCount,
            'journal_count' => $journalCount,
            'recent_zikir' => $recentZikir,
            'recent_salah' => $recentSalah,
            'recent_quran' => $recentQuran,
            'recent_habits' => $recentHabits,
            'recent_journal' => $recentJournal,
            'disabled_features' => $disabledFeatures,
        ];
    }

    public function toggleFeature($featureName)
    {
        if (!$this->selectedUserId) return;

        $user = User::findOrFail($this->selectedUserId);

        // Fetch current setting
        $setting = $user->settings()->where('key', 'disabled_features')->first();
        $current = [];
        if ($setting && $setting->value) {
            $current = json_decode($setting->value, true) ?: [];
        }

        if (in_array($featureName, $current)) {
            $current = array_values(array_diff($current, [$featureName]));
        } else {
            $current[] = $featureName;
        }

        // Save back
        $user->settings()->updateOrCreate(
            ['key' => 'disabled_features'],
            ['value' => json_encode($current)]
        );

        // Invalidate Cache
        \Illuminate\Support\Facades\Cache::forget("user_{$this->selectedUserId}_disabled_features");

        // Reload details
        $this->selectUser($this->selectedUserId);

        session()->flash('admin_success', 'User features access levels updated successfully!');
    }

    public function closeUserModal()
    {
        $this->selectedUserId = null;
        $this->selectedUserDetail = null;
    }

    public function deleteUser($userId)
    {
        // Protect admin from deleting themselves
        if (User::findOrFail($userId)->email === 'omarfaruuuk@gmail.com') {
            session()->flash('admin_error', 'Cannot delete the admin account!');
            return;
        }

        $user = User::findOrFail($userId);
        $user->delete();

        $this->closeUserModal();
        $this->loadOverviewStats();
        session()->flash('admin_success', 'User account permanently deleted successfully!');
    }

    public function render()
    {
        $usersQuery = User::query();

        if ($this->searchUser) {
            $usersQuery->where(function($q) {
                $q->where('name', 'like', '%' . $this->searchUser . '%')
                  ->orWhere('email', 'like', '%' . $this->searchUser . '%');
            });
        }

        // Paginate users with custom stats joins
        $users = $usersQuery->orderBy('created_at', 'desc')->paginate(10);

        // Add dynamically compiled stats
        $usersList = $users->map(function ($u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'created_at' => $u->created_at->format('Y-m-d'),
                'is_online' => DB::table('sessions')->where('user_id', $u->id)->exists(),
                'zikir_count' => ZikirCount::where('user_id', $u->id)->sum('count'),
                'salah_count' => SalahLog::where('user_id', $u->id)->count(),
                'quran_pages' => QuranProgress::where('user_id', $u->id)->sum('pages_read'),
                'journal_count' => JournalEntry::where('user_id', $u->id)->count(),
            ];
        });

        return view('livewire.admin.admin-dashboard', [
            'users' => $users,
            'usersList' => $usersList
        ])->layout('layouts.admin');
    }
}
