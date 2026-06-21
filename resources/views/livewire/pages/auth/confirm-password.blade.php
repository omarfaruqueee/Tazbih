<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $password = '';

    /**
     * Confirm the current user's password.
     */
    public function confirmPassword(): void
    {
        $this->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-4">
    <!-- Header Title -->
    <div class="text-center space-y-1">
        <h2 class="font-outfit font-black text-xl text-gray-800 dark:text-gray-100 uppercase tracking-wide">
            {{ app()->getLocale() === 'bn' ? 'পাসওয়ার্ড নিশ্চিত করুন' : 'Confirm Password' }}
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ app()->getLocale() === 'bn' ? 'চলমান রাখার পূর্বে আপনার পাসওয়ার্ডটি নিশ্চিত করুন।' : 'Please confirm your password before continuing.' }}
        </p>
    </div>

    <form wire:submit="confirmPassword" class="space-y-4">
        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                {{ __('messages.password') }}
            </label>
            <input wire:model="password" id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white transition">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <div class="flex justify-end mt-2">
            <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl text-xs shadow active:scale-95 transition-all">
                {{ app()->getLocale() === 'bn' ? 'নিশ্চিত করুন' : 'Confirm' }}
            </button>
        </div>
    </form>
</div>
