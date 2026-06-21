<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomeGrid;
use App\Livewire\TasbihCounter;
use App\Livewire\RecordsDashboard;
use App\Livewire\SettingsPanel;

Route::get('/', function () {
    return redirect()->route('home');
});

// Publicly accessible routes for both guests and registered users
Route::get('/home', HomeGrid::class)->name('home');
Route::get('/tasbih', TasbihCounter::class)->name('tasbih');
Route::get('/records', RecordsDashboard::class)->name('records');
Route::get('/settings', SettingsPanel::class)->name('settings');

// Authenticated API endpoints
Route::middleware(['auth'])->group(function () {
    Route::post('/api/sync-offline-counts', [App\Http\Controllers\OfflineSyncController::class, 'sync'])->name('api.sync');
    Route::post('/api/migrate-guest-data', [App\Http\Controllers\GuestMigrationController::class, 'migrate'])->name('api.migrate');
});

// Admin Control Center Route
Route::get('/admin/dashboard', \App\Livewire\Admin\AdminDashboard::class)
    ->middleware(['auth', 'admin'])
    ->name('admin.dashboard');

require __DIR__.'/auth.php';
