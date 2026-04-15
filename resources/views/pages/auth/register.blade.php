@extends('layouts.app')
@section('title', 'Crear cuenta')

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <svg width="32" height="32" viewBox="0 0 28 28" fill="none"><path d="M14 3L2 12h3v13h7v-8h4v8h7V12h3L14 3z" fill="var(--primary)"/></svg>
            <span class="auth-brand">IberPiso</span>
        </div>
        <h1 class="auth-title">Crear cuenta</h1>

        @if($errors->any())
            <x-alert type="error" :message="$errors->first()" />
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nombre completo</label>
                <input type="text" id="name" name="name" class="form-input {{ $errors->has('name') ? 'input-error' : '' }}"
                    value="{{ old('name') }}" placeholder="Tu nombre" required>
                @error('name') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'input-error' : '' }}"
                    value="{{ old('email') }}" placeholder="tu@email.com" required>
                @error('email') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Teléfono <span class="optional">(opcional)</span></label>
                <input type="tel" id="phone" name="phone" class="form-input"
                    value="{{ old('phone') }}" placeholder="+34 600 000 000">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <div class="password-wrap">
                    <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'input-error' : '' }}"
                        placeholder="Mínimo 8 caracteres" required minlength="8">
                    <button type="button" class="toggle-password" aria-label="Mostrar contraseña">
                        <svg viewBox="0 0 24 24" width="18" height="18"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" stroke="currentColor" fill="none" stroke-width="2"/><circle cx="12" cy="12" r="3" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                    </button>
                </div>
                @error('password') <span class="form-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="form-input" placeholder="Repite tu contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-lg">Crear cuenta</button>
        </form>

        <p class="auth-footer">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="link-primary">Inicia sesión</a></p>
    </div>
</div>
@endsection
