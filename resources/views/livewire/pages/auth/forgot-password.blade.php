<?php

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public string $email = '';

    /**
     * Send a password reset link to the provided email address.
     */
    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $status = Password::sendResetLink(
            $this->only('email')
        );

        if ($status != Password::RESET_LINK_SENT) {
            $this->addError('email', __($status));

            return;
        }

        $this->reset('email');

        session()->flash('status', __($status));
    }
}; ?>

<div class="space-y-4">
    <!-- Header Title -->
    <div class="text-center space-y-1">
        <h2 class="font-outfit font-black text-xl text-gray-800 dark:text-gray-100 uppercase tracking-wide">
            {{ app()->getLocale() === 'bn' ? 'পাসওয়ার্ড রিসেট' : 'Reset Password' }}
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ app()->getLocale() === 'bn' ? 'পাসওয়ার্ড ভুলে গেছেন? আপনার ইমেইল দিন, আমরা একটি পাসওয়ার্ড রিসেট লিঙ্ক পাঠাব।' : 'Forgot your password? Enter your email address and we will mail you a reset link.' }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-2" :status="session('status')" />

    <form wire:submit="sendPasswordResetLink" class="space-y-4">
        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-500 dark:text-gray-400 mb-1.5 uppercase tracking-wider">
                {{ __('messages.email') }}
            </label>
            <input wire:model="email" id="email" type="email" name="email" required autofocus
                   class="w-full bg-gray-50 dark:bg-slate-800 border border-gray-200 dark:border-slate-700 rounded-xl px-3 py-2.5 text-sm font-semibold focus:outline-none focus:ring-2 focus:ring-emerald-500 dark:text-white transition">
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl text-xs shadow active:scale-95 transition-all mt-2">
            {{ app()->getLocale() === 'bn' ? 'পাসওয়ার্ড রিসেট লিঙ্ক পাঠান' : 'Email Reset Link' }}
        </button>
    </form>
</div>
