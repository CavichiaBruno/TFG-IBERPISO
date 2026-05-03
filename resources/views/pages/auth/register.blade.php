@extends('layouts.app')
@section('title', 'Crear cuenta — IberPiso')

@push('styles')
    @vite('resources/css/pages/auth.css')
@endpush

@section('content')
    <div class="auth-split-container">
        <!-- Left Section: Register Form -->
        <div class="auth-left">
            <div class="auth-card-compact">
                <div class="auth-header">
                    <h1 class="auth-title">Únete a nosotros.</h1>
                    <p class="auth-subtitle">Crea tu cuenta para empezar a explorar.</p>
                </div>

                @if($errors->any())
                    <div class="alert alert-error">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.post') }}" class="auth-form">
                    @csrf

                    <div class="form-group">
                        <label for="name" class="form-label">Nombre completo</label>
                        <input type="text" id="name" name="name" class="form-input" value="{{ old('name') }}"
                            placeholder="Tu nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" id="email" name="email" class="form-input" value="{{ old('email') }}"
                            placeholder="tu@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="form-label">Teléfono</label>
                        <input type="tel" id="phone" name="phone" class="form-input" value="{{ old('phone') }}"
                            placeholder="+34 600 000 000">
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" id="password" name="password" class="form-input"
                            placeholder="Mínimo 8 caracteres" required minlength="8">
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
                            placeholder="Repite tu contraseña" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Crear cuenta</button>
                </form>

                <p class="auth-footer">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="link-primary">Inicia sesión</a></p>
            </div>
        </div>

        <!-- Right Section: Lottie Animation -->
        <div class="auth-right">
            <div class="lottie-container">
                <dotlottie-wc src="https://lottie.host/1b6983e6-a524-409e-afc6-ca0f723dd4a1/J2UV87FS0W.lottie" 
                    style="width: 500px; height: 500px" 
                    autoplay loop worker="true">
                </dotlottie-wc>
            </div>
        </div>
    </div>
@endsection