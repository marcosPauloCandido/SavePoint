<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        if (! Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->redirectIntended(default: route('home', absolute: false), navigate: true);
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    protected function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->email).'|'.request()->ip());
    }
}; ?>

<div class="min-h-screen flex flex-col justify-start items-center bg-gray-100 dark:bg-zinc-900 p-4">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-80 h-32 mb-6">
    <div class="w-full max-w-md bg-white dark:bg-zinc-800 rounded-lg shadow p-8 space-y-6">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-zinc-100">
            {{ __('Entre na sua conta') }}
        </h2>

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="login" class="space-y-4">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    {{ __('Email') }}
                </label>
                <input
                    id="email"
                    wire:model="email"
                    type="email"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="email@exemplo.com"
                    class="mt-1 pl-4 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                >
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    {{ __('Senha') }}
                </label>
                <input
                    id="password"
                    wire:model="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    placeholder="Digite sua senha"
                    class="mt-1 pl-4 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                >
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div class="flex items-center justify-between">
                <label class="flex items-center space-x-2">
                    <input
                        wire:model="remember"
                        type="checkbox"
                        class="rounded border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 text-indigo-600 focus:ring-indigo-500"
                    >
                    <span class="text-sm text-gray-700 dark:text-zinc-300">{{ __('Lembrar de mim') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500">
                        {{ __('Esqueceu a senha?') }}
                    </a>
                @endif
            </div>

            <button
                type="submit"
                class="w-full flex justify-center py-2 px-4 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                {{ __('Entrar') }}
            </button>
        </form>

        @if (Route::has('register'))
            <div class="text-center text-sm text-gray-600 dark:text-zinc-400">
                {{ __('Não tem uma conta?') }}
                <a href="{{ route('register') }}" class="text-indigo-600 hover:text-indigo-500">
                    {{ __('Cadastre-se') }}
                </a>
            </div>
        @endif
    </div>
</div>
