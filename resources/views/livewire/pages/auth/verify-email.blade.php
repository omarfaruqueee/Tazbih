<?php

use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    /**
     * Send an email verification notification to the user.
     */
    public function sendVerification(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirectIntended(default: route('home', absolute: false), navigate: true);

            return;
        }

        Auth::user()->sendEmailVerificationNotification();

        Session::flash('status', 'verification-link-sent');
    }

    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<div class="space-y-4">
    <!-- Header Title -->
    <div class="text-center space-y-1">
        <h2 class="font-outfit font-black text-xl text-gray-800 dark:text-gray-100 uppercase tracking-wide">
            {{ app()->getLocale() === 'bn' ? 'ইমেইল ভেরিফাই করুন' : 'Verify Email' }}
        </h2>
        <p class="text-xs text-gray-500 dark:text-gray-400">
            {{ app()->getLocale() === 'bn' ? 'নিবন্ধন করার জন্য ধন্যবাদ! শুরু করার আগে, অনুগ্রহ করে আপনার ইমেইলে পাঠানো লিঙ্কে ক্লিক করে ভেরিফাই করুন।' : 'Thanks for signing up! Before getting started, please verify your email address by clicking the link we just emailed to you.' }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="p-3 bg-emerald-50 dark:bg-emerald-950/20 text-emerald-600 dark:text-emerald-400 rounded-xl text-xs font-bold leading-relaxed">
            {{ app()->getLocale() === 'bn' ? 'নিবন্ধনের সময় দেওয়া ইমেইলে একটি নতুন ভেরিফিকেশন লিঙ্ক পাঠানো হয়েছে।' : 'A new verification link has been sent to the email address you provided during registration.' }}
        </div>
    @endif

    <div class="flex flex-col gap-3 pt-2">
        <button wire:click="sendVerification" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold py-3 rounded-xl text-xs shadow active:scale-95 transition-all">
            {{ app()->getLocale() === 'bn' ? 'ভেরিফিকেশন ইমেইল পুনরায় পাঠান' : 'Resend Verification Email' }}
        </button>

        <button wire:click="logout" type="button" class="w-full bg-rose-600 hover:bg-rose-700 text-white font-extrabold py-3 rounded-xl text-xs shadow active:scale-95 transition-all">
            {{ __('messages.logout') }}
        </button>
    </div>
</div>
