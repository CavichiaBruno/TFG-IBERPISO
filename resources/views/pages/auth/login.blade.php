@extends('layouts.app')
@section('title', 'Iniciar sesión')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <svg width="32" height="32" viewBox="0 0 28 28" fill="none"><path d="M14 3L2 12h3v13h7v-8h4v8h7V12h3L14 3z" fill="var(--primary)"/></svg>
            <span class="auth-brand">IberPiso</span>
        </div>
        <h1 class="auth-title">Iniciar sesión</h1>

        @if($errors->any())
            <x-alert type="error" :message="$errors->first()" />
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="auth-form">
            @csrf
            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'input-error' : '' }}"
                    value="{{ old('email') }}" placeholder="tu@email.com" required autofocus>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <div class="password-wrap">
                    <input type="password" id="password" name="password" class="form-input" placeholder="••••••••" required>
                    <button type="button" class="toggle-password" aria-label="Mostrar contraseña">
                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" fill="none" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                    </button>
                </div>
            </div>

            <div class="form-row">
                <label class="checkbox-option">
                    <input type="checkbox" name="remember"> Recordarme
                </label>
                <a href="#" class="link-muted">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Entrar</button>
        </form>

        <p class="auth-footer">¿No tienes cuenta? <a href="{{ route('register') }}" class="link-primary">Regístrate</a></p>
    </div>
</div>
@endsection
