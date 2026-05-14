@extends('layouts.admin')
@section('title', 'Nueva Propiedad')
@section('page-title', 'Nueva Propiedad')



@section('content')
{{-- Loading Overlay con Lottie --}}
<div id="property-loading-overlay" style="opacity: 0; pointer-events: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(245, 245, 247, 0.8); backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: all 0.3s;">
    <dotlottie-wc src="https://lottie.host/d469d6b2-6e5e-4df9-99e2-e45973cd59c4/61j44lYP6T.lottie" style="width: 150px; height: 150px;" autoplay loop></dotlottie-wc>
    <h3 style="margin-top: 20px; font-family: var(--font-display); font-weight: 600; font-size: 28px; line-height: 1.14; letter-spacing: 0.196px; color: #1d1d1f;">Creando propiedad...</h3>
    <p style="font-family: var(--font-body); font-size: 17px; color: #86868b; margin: 8px 0 0; font-weight: 400; letter-spacing: -0.374px;">Por favor espera mientras procesamos tu solicitud</p>
</div>

{{-- Script de Lottie para Admin --}}
<script src="https://unpkg.com/@lottiefiles/dotlottie-wc@0.9.10/dist/dotlottie-wc.js" type="module" defer></script>

<script>
window.addEventListener('load', function() {
    setTimeout(function() {
        const form = document.getElementById('property-form');
        const loadingOverlay = document.getElementById('property-loading-overlay');
        
        if (form && loadingOverlay) {
            form.addEventListener('submit', function() {
                loadingOverlay.style.opacity = '1';
                loadingOverlay.style.pointerEvents = 'auto';
            });
        }
    }, 100);
});
</script>

<form method="POST" action="{{ route('admin.properties.store') }}" id="property-form" novalidate enctype="multipart/form-data">
    @csrf
    @include('admin.properties._form', ['property' => null])

    <div class="form-actions">
        <button type="submit" name="is_active" value="0" class="btn btn-outline">Guardar borrador</button>
        <button type="submit" name="is_active" value="1" class="btn btn-primary">Publicar</button>
    </div>
</form>
@endsection
