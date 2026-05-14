@extends('layouts.admin')
@section('title', 'Crear Usuario')
@section('page-title', 'Crear Nuevo Usuario')

@section('content')
@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 24px; padding: 16px; background: #fff0f0; border-radius: 12px; font-family: var(--font-body); font-size: 14px; letter-spacing: -0.224px; color: #ff453a; border: none;">
        <ul style="margin: 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Loading Overlay con Lottie --}}
<div id="user-loading-overlay" style="opacity: 0; pointer-events: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(245, 245, 247, 0.8); backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: all 0.3s;">
    <dotlottie-wc src="https://lottie.host/d469d6b2-6e5e-4df9-99e2-e45973cd59c4/61j44lYP6T.lottie" style="width: 150px; height: 150px;" autoplay loop></dotlottie-wc>
    <h3 style="margin-top: 20px; font-family: var(--font-display); font-weight: 600; font-size: 28px; line-height: 1.14; letter-spacing: 0.196px; color: #1d1d1f;">Creando usuario...</h3>
    <p style="font-family: var(--font-body); font-size: 17px; color: #86868b; margin: 8px 0 0; font-weight: 400; letter-spacing: -0.374px;">Por favor espera mientras procesamos tu solicitud</p>
</div>

{{-- Script de Lottie para Admin --}}
<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.9.10/dist/dotlottie-wc.js" type="module" defer></script>

<script>
window.addEventListener('load', function() {
    setTimeout(function() {
        const form = document.getElementById('user-create-form');
        const loadingOverlay = document.getElementById('user-loading-overlay');
        
        if (form && loadingOverlay) {
            form.addEventListener('submit', function() {
                loadingOverlay.style.opacity = '1';
                loadingOverlay.style.pointerEvents = 'auto';
            });
        }
    }, 100);
});
</script>

<div class="form-container" style="max-width: 680px; margin: 0 auto; background: var(--white); padding: 32px; border-radius: 12px; box-shadow: var(--shadow-sm);">
    <form id="user-create-form" action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        
        <div class="form-grid-2">
            <div class="form-group">
                <label class="form-label">Nombre</label>
                <input type="text" name="nombre" class="form-input" value="{{ old('nombre') }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" name="correo" class="form-input" value="{{ old('correo') }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <input type="tel" name="telefono" class="form-input" value="{{ old('telefono') }}">
            </div>
            
            <div class="form-group">
                <label class="form-label">Rol</label>
                <select name="rol" class="form-select">
                    <option value="usuario" {{ old('rol') == 'usuario' ? 'selected' : '' }}>Usuario</option>
                    <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>
            
            <div class="form-group form-col-2">
                <label class="form-label">Contraseña</label>
                <input type="password" name="contrasena" class="form-input" minlength="8" required>
            </div>
        </div>
        
        <div class="form-actions" style="margin-top: 32px; padding-top: 24px; border-top: 1px solid var(--gray-subtle);">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Crear Usuario</button>
        </div>
    </form>
</div>
@endsection
