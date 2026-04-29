@extends('layouts.app')
@section('title', 'Iniciar sesión — IberPiso')

@push('styles')
    @vite('resources/css/pages/auth.css')
@endpush

@section('content')
    <div class="auth-page">
        <div class="auth-card">
            <div class="auth-logo">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"
                    stroke-linecap="round" stroke-linejoin="round">
                    <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                    <polyline points="9 22 9 12 15 12 15 22" />
                </svg>
                <span class="auth-brand">IberPiso</span>
            </div>
            <h1 class="auth-title">Inicia sesión.</h1>

            @if($errors->any())
                <div class="alert alert-error"
                    style="background: #fff1f0; color: #cf1322; padding: 12px; border-radius: 8px; font-size: 14px; margin-bottom: 20px;">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="auth-form">
                @csrf
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}"
                        placeholder="tu@email.com" required autofocus>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="password-wrap">
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••"
                            required>
                    </div>
                </div>

                <div class="form-row">
                    <label class="checkbox-option">
                        <input type="checkbox" name="remember"> Recordarme
                    </label>
                    <a href="#" class="link-muted">¿Olvidaste tu contraseña?</a>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 10px;">Entrar</button>
            </form>

            <p class="auth-footer">¿No tienes cuenta? <a href="{{ route('register') }}" class="link-primary">Regístrate</a>
            </p>
        </div>
    </div>
@endsection