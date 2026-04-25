@extends('layouts.app')
@section('title', 'Crear cuenta — IberPiso')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/auth.css') }}">
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span class="auth-brand">IberPiso</span>
        </div>
        <h1 class="auth-title">Crea tu cuenta.</h1>

        @if($errors->any())
            <div class="alert alert-error" style="background: #fff1f0; color: #cf1322; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('register.post') }}" class="auth-form">
            @csrf

            <div class="form-group">
                <label for="name" class="form-label">Nombre completo</label>
                <input type="text" id="name" name="name" class="form-input"
                    value="{{ old('name') }}" placeholder="Tu nombre" required>
            </div>

            <div class="form-group">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-input"
                    value="{{ old('email') }}" placeholder="tu@email.com" required>
            </div>

            <div class="form-group">
                <label for="phone" class="form-label">Teléfono</label>
                <input type="tel" id="phone" name="phone" class="form-input"
                    value="{{ old('phone') }}" placeholder="+34 600 000 000">
            </div>

            <div class="form-group">
                <label for="password" class="form-label">Contraseña</label>
                <div class="password-wrap">
                    <input type="password" id="password" name="password" class="form-input"
                        placeholder="Mínimo 8 caracteres" required minlength="8">
                </div>
            </div>

            <div class="form-group" style="margin-bottom: 10px;">
                <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                    class="form-input" placeholder="Repite tu contraseña" required>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Crear cuenta</button>
        </form>

        <p class="auth-footer">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="link-primary">Inicia sesión</a></p>
    </div>
</div>
@endsection
