<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Welcome back</h1>
        <p class="text-gray-500 mt-2">Sign in to your Launchory account</p>
    </div>

    <!-- Session Status -->
    @if(session('status'))
        <div class="mb-6 p-4 rounded-xl bg-green-50 border border-green-100 text-sm text-green-700">
            {{ session('status') }}
        </div>
    @endif

    <!-- Google Login -->
    <a href="{{ route('auth.google') }}" class="w-full flex items-center justify-center gap-3 bg-white border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl transition-all text-sm">
        <svg class="w-5 h-5" viewBox="0 0 24 24"><path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 01-2.2 3.32v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.1z" fill="#4285F4"/><path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/><path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/><path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/></svg>
        Continue with Google
    </a>

    <!-- Divider -->
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
        <div class="relative flex justify-center text-xs"><span class="bg-white px-4 text-gray-400">or sign in with email</span></div>
    </div>

    <form wire:submit="login" class="space-y-5">
        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
            <input wire:model="form.email" id="email" type="email" name="email" required autofocus autocomplete="username"
                   class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all" placeholder="you@email.com" />
            @error('form.email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
            <div class="flex items-center justify-between mb-1.5">
                <label for="password" class="block text-sm font-semibold text-gray-700">Password</label>
                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" wire:navigate class="text-xs font-medium text-amber-600 hover:text-amber-700 transition-colors">Forgot password?</a>
                @endif
            </div>
            <input wire:model="form.password" id="password" type="password" name="password" required autocomplete="current-password"
                   class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all" placeholder="Enter your password" />
            @error('form.password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center gap-2">
            <input wire:model="form.remember" id="remember" type="checkbox" name="remember"
                   class="w-4 h-4 rounded-md border-gray-300 text-amber-500 focus:ring-amber-400 cursor-pointer" />
            <label for="remember" class="text-sm text-gray-600 cursor-pointer select-none">Remember me</label>
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full gradient-amber text-white font-bold py-3.5 rounded-xl shadow-lg shadow-amber-500/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 text-sm flex items-center justify-center gap-2">
            <svg wire:loading wire:target="login" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            <span wire:loading.remove wire:target="login">Sign In</span>
            <span wire:loading wire:target="login">Signing in...</span>
        </button>
    </form>

    <!-- Divider -->
    <div class="relative my-8">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
        <div class="relative flex justify-center text-xs"><span class="bg-white px-4 text-gray-400">New to Launchory?</span></div>
    </div>

    <!-- Register link -->
    <a href="{{ route('register') }}" wire:navigate
       class="block w-full text-center border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl transition-all text-sm">
        Create an account
    </a>
</div>
