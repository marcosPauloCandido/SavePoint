<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirectIntended(route('home', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 dark:bg-zinc-900 p-4">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-80 h-32  mb-6">
    <div class="w-full max-w-md bg-white dark:bg-zinc-800 rounded-lg shadow p-8 space-y-6">
        <h2 class="text-2xl font-bold text-center text-gray-800 dark:text-zinc-100">
            {{ __('Cria sua conta!') }}
        </h2>
        

        <x-auth-session-status class="text-center" :status="session('status')" />

        <form wire:submit="register" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    {{ __('Nome completo') }}
                </label>
                <input
                    id="name"
                    wire:model="name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    placeholder="Escreva seu nome"
                    class="mt-1 pl-4 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                >
                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    {{ __('Email') }}
                </label>
                <input
                    id="email"
                    wire:model="email"
                    type="email"
                    required
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
                    autocomplete="new-password"
                    placeholder="Escreva sua senha"
                    class="mt-1 pl-4 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                >
                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-zinc-300">
                    {{ __('Confirme sua senha') }}
                </label>
                <input
                    id="password_confirmation"
                    wire:model="password_confirmation"
                    type="password"
                    required
                    autocomplete="new-password"
                    placeholder="Repita sua senha"
                    class="mt-1 pl-4 block w-full rounded-md border-gray-300 dark:border-zinc-600 dark:bg-zinc-700 dark:text-zinc-100 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"
                >
                @error('password_confirmation') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>

            <button
                type="submit"
                class="w-full flex justify-center py-2 px-4 rounded-md bg-indigo-600 text-white font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                {{ __('Criar conta') }}
            </button>
        </form>

        <div class="text-center text-sm text-gray-600 dark:text-zinc-400">
            {{ __('Já tem uma conta?') }}
            <a href="{{ route('login') }}" class="text-indigo-600 hover:text-indigo-500">
                {{ __('Entre') }}
            </a>
        </div>
    </div>
</div>
