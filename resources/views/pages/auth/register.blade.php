@extends('layouts.app')
@section('title', 'Crear cuenta — IberPiso')

@push('styles')
    @vite('resources/css/pages/auth.css')
@endpush

@section('content')
    <div class="auth-split-container">
        <!-- Sección Izquierda: Formulario de Registro -->
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
                        <label for="nombre" class="form-label">Nombre completo</label>
                        <input type="text" id="nombre" name="nombre" class="form-input" value="{{ old('nombre') }}"
                            placeholder="Tu nombre" required>
                    </div>

                    <div class="form-group">
                        <label for="correo" class="form-label">Email</label>
                        <input type="email" id="correo" name="correo" class="form-input" value="{{ old('correo') }}"
                            placeholder="tu@email.com" required>
                    </div>

                    <div class="form-group">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" class="form-input" value="{{ old('telefono') }}"
                            placeholder="+34 600 000 000">
                    </div>

                    <div class="form-group">
                        <label for="contrasena" class="form-label">Contraseña</label>
                        <input type="password" id="contrasena" name="contrasena" class="form-input"
                            placeholder="Mínimo 8 caracteres" required minlength="8">
                    </div>

                    <div class="form-group">
                        <label for="contrasena_confirmation" class="form-label">Confirmar contraseña</label>
                        <input type="password" id="contrasena_confirmation" name="contrasena_confirmation" class="form-input"
                            placeholder="Repite tu contraseña" required>
                    </div>

                    <button type="submit" class="btn btn-primary btn-full">Crear cuenta</button>
                </form>

                <p class="auth-footer">¿Ya tienes cuenta? <a href="{{ route('login') }}" class="link-primary">Inicia sesión</a></p>
            </div>
        </div>

        <!-- Sección Derecha: Animación Lottie -->
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