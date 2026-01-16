<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public LoginForm $form;

    /**
     * Handle an incoming authentication request.
     */
    public function login(): void
    {
        $this->validate();

        $this->form->authenticate();

        Session::regenerate();

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<!-- Note: Layout needs to be empty or compatible with this full page design -->
<div class="login-layout">

    <div class="login-left">
        <i class="bi bi-laptop-fill illustration-icon"></i>

        <h1 class="login-welcome-title">Bienvenido</h1>
        <p class="login-welcome-text">
            Gestión inteligente de inventario TI. <br>
            Controla tus activos de forma rápida y segura.
        </p>
    </div>

    <div class="login-right">
        <div class="login-form-container">
            <h2 class="login-title">Iniciar Sesión</h2>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit="login">

                <!-- Email Address -->
                <div class="input-group-custom">
                    <input wire:model="form.email" id="email" type="email"
                        class="form-control-custom @error('form.email') is-invalid @enderror"
                        placeholder="admin@correo.com" required autofocus autocomplete="username">
                    <div class="text-danger mt-1 small">
                        @error('form.email') <span>{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Password -->
                <div class="input-group-custom mt-3">
                    <input wire:model="form.password" id="password" type="password"
                        class="form-control-custom @error('form.password') is-invalid @enderror"
                        placeholder="Contraseña" required autocomplete="current-password">
                    <div class="text-danger mt-1 small">
                        @error('form.password') <span>{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Remember Me (Optional - visually hidden in legacy but logic kept) -->
                <!-- <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input wire:model="form.remember" id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                        <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                    </label>
                </div> -->

                <button type="submit" class="btn-login-blue">
                    INGRESAR
                </button>
            </form>
        </div>
    </div>

</div>