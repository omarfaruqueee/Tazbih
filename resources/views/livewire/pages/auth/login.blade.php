<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }
}; ?>

<div class="space-y-4">
    <!-- Header Title -->
    <div class="text-center space-y-1">
        <h2 class="font-outfit font-black text-xl text-gray-800 dark:text-gray-100 uppercase tracking-wide">
            {{ __('messages.login') }}
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ app()->getLocale() === 'bn' ? 'আপনার অ্যাকাউন্টে সাইন ইন করুন' : 'Sign in to your account' }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-2" :status="session('status')" />

    <form wire:submit="login" class="space-y-4">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                {{ __('messages.email') }}
            </label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                   class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white transition">
            <x-input-error :messages="$errors->get('form.email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <div class="flex justify-between items-center mb-1.5">
                <label for="password" class="block text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    {{ __('messages.password') }}
                </label>
                @if (Route::has('password.request'))
                    <a class="text-[10px] font-bold text-emerald-600 dark:text-emerald-400 hover:underline" href="{{ route('password.request') }}" wire:navigate>
                        {{ __('messages.forgot_password') }}
                    </a>
                @endif
            </div>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white transition">
            <x-input-error :messages="$errors->get('form.password')" class="mt-1" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center">
            <label for="remember" class="inline-flex items-center cursor-pointer">
                <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                       class="rounded border-gray-300 dark:border-gray-700 text-emerald-600 focus:ring-emerald-500 w-4 h-4 dark:bg-slate-800 transition">
                <span class="ms-2 text-xs font-semibold text-gray-600 dark:text-gray-400">{{ __('messages.remember_me') }}</span>
            </label>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl text-xs shadow active:scale-95 transition-all mt-2">
            {{ __('messages.login') }}
        </button>
    </form>

    <!-- Divider & Link to Register -->
    <div class="text-center pt-3 border-t border-gray-100 dark:border-slate-800/80">
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ app()->getLocale() === 'bn' ? 'অ্যাকাউন্ট নেই?' : "Don't have an account?" }}
            <a href="{{ route('register') }}" class="font-bold text-emerald-600 dark:text-emerald-400 hover:underline ml-1" wire:navigate>
                {{ __('messages.register') }}
            </a>
        </p>
    </div>
</div>
