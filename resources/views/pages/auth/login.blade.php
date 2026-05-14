@extends('layouts.app')
@section('title', 'Iniciar sesión — IberPiso')

@push('styles')
    @vite('resources/css/pages/auth.css')
@endpush

@section('content')
    <div class="auth-split-container">
        <!-- Sección Izquierda: Formulario de Login -->
        <div class="auth-left">
            <div class="auth-card-compact">
                <div class="auth-header">
                    <h1 class="auth-title">Inicia sesión.</h1>
                    <p class="auth-subtitle">Gestiona tus propiedades y favoritos.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.post') }}" class="auth-form">
                    @csrf
                    <div class="form-group">
                        <label for="correo" class="form-label">Email</label>
                        <input type="email" id="correo" name="correo" class="form-input" value="{{ old('correo') }}"
                            placeholder="tu@email.com" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-input" placeholder="••••••••"
                            required>
                    </div>

                    <div class="form-row">
                        <label class="checkbox-option">
                            <input type="checkbox" name="remember"> Recordarme
                        </label>
                        <a href="#" class="link-muted">¿Olvidaste tu contraseña?</a>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Entrar</button>
                </form>

                <p class="auth-footer">¿No tienes cuenta? <a href="{{ route('register') }}" class="link-primary">Regístrate</a></p>
            </div>
        </div>

        <!-- Sección Derecha: Animación Lottie -->
        <div class="auth-right">
            <div class="lottie-container">
                <dotlottie-wc src="https://lottie.host/9bd978b5-cdca-4594-a9d5-90fcb6b3b56f/N8m2OwxN67.lottie" 
                    style="width: 500px; height: 500px" 
                    autoplay loop worker="true">
                </dotlottie-wc>
            </div>
        </div>
    </div>
@endsection