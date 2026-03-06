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

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div>
    <div class="mb-8">
        <h1 class="text-3xl font-extrabold text-gray-900">Create your account</h1>
        <p class="text-gray-500 mt-2">Start launching and discovering products</p>
    </div>

    <form wire:submit="register" class="space-y-5">
        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-semibold text-gray-700 mb-1.5">Full Name</label>
            <input wire:model="name" id="name" type="text" name="name" required autofocus autocomplete="name"
                   class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all" placeholder="John Doe" />
            @error('name') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        <!-- Email -->
        <div>
            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Email</label>
            <input wire:model="email" id="email" type="email" name="email" required autocomplete="username"
                   class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all" placeholder="you@email.com" />
            @error('email') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
            <input wire:model="password" id="password" type="password" name="password" required autocomplete="new-password"
                   class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all" placeholder="Min. 8 characters" />
            @error('password') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Confirm Password</label>
            <input wire:model="password_confirmation" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                   class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm bg-gray-50/50 focus:outline-none focus:ring-2 focus:ring-amber-400 focus:border-transparent focus:bg-white transition-all" placeholder="Re-enter your password" />
            @error('password_confirmation') <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p> @enderror
        </div>

        <!-- Submit -->
        <button type="submit" class="w-full gradient-amber text-white font-bold py-3.5 rounded-xl shadow-lg shadow-amber-500/20 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300 text-sm">
            <span wire:loading.remove wire:target="register">Create Account</span>
            <span wire:loading wire:target="register" class="flex items-center justify-center gap-2">
                <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                Creating...
            </span>
        </button>

        <p class="text-xs text-gray-400 text-center">By creating an account, you agree to our Terms of Service</p>
    </form>

    <!-- Divider -->
    <div class="relative my-8">
        <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
        <div class="relative flex justify-center text-xs"><span class="bg-white px-4 text-gray-400">Already have an account?</span></div>
    </div>

    <!-- Login link -->
    <a href="{{ route('login') }}" wire:navigate
       class="block w-full text-center border-2 border-gray-200 hover:border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-3 rounded-xl transition-all text-sm">
        Sign in instead
    </a>
</div>
