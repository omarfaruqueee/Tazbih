<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered($user = User::create($validated)));

        Auth::login($user);

        $this->redirect(route('home', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-4">
    <!-- Header Title -->
    <div class="text-center space-y-1">
        <h2 class="font-outfit font-black text-xl text-gray-800 dark:text-gray-100 uppercase tracking-wide">
            {{ __('messages.register') }}
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ app()->getLocale() === 'bn' ? 'নতুন অ্যাকাউন্ট তৈরি করুন' : 'Create a new account' }}
        </p>
    </div>

    <form wire:submit="register" class="space-y-4">
        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                {{ __('messages.name') }}
            </label>
            <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                   class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white transition">
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                {{ __('messages.email') }}
            </label>
            <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                   class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                {{ __('messages.password') }}
            </label>
            <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white transition">
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                {{ __('messages.confirm_password') }}
            </label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white transition">
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl text-xs shadow active:scale-95 transition-all mt-2">
            {{ __('messages.register') }}
        </button>
    </form>

    <!-- Divider & Link to Login -->
    <div class="text-center pt-3 border-t border-gray-100 dark:border-slate-800/80">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ __('messages.already_registered') }}
            <a href="{{ route('login') }}" class="font-bold text-emerald-600 dark:text-emerald-400 hover:underline ml-1" wire:navigate>
                {{ __('messages.login') }}
            </a>
        </p>
    </div>
</div>
