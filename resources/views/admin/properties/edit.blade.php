@extends('layouts.admin')
@section('title', 'Editar: ' . $property->titulo)
@section('page-title', 'Editar Propiedad')

@section('topbar-actions')
    <a href="{{ route('properties.show', [$property->id, $property->slug]) }}" target="_blank" class="btn btn-outline">Ver
        en portal</a>
@endsection

@section('content')
@if($errors->any())
    <div class="alert alert-danger" style="margin-bottom: 24px; padding: 16px; background: #fff0f0; border-radius: 12px; font-family: var(--font-body); font-size: 14px; letter-spacing: -0.224px; color: #ff453a; border: none;">
        <p><strong>Por favor, corrige los siguientes errores:</strong></p>
        <ul style="margin: 8px 0 0; padding-left: 20px;">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Loading Overlay con Lottie --}}
<div id="property-loading-overlay" style="opacity: 0; pointer-events: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(245, 245, 247, 0.8); backdrop-filter: saturate(180%) blur(20px); -webkit-backdrop-filter: saturate(180%) blur(20px); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: all 0.3s;">
    <dotlottie-wc src="https://lottie.host/d469d6b2-6e5e-4df9-99e2-e45973cd59c4/61j44lYP6T.lottie" style="width: 150px; height: 150px;" autoplay loop></dotlottie-wc>
    <h3 style="margin-top: 20px; font-family: var(--font-display); font-weight: 600; font-size: 28px; line-height: 1.14; letter-spacing: 0.196px; color: #1d1d1f;">Actualizando propiedad...</h3>
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

    <form method="POST" action="{{ route('admin.properties.update', $property->id) }}" id="property-form" novalidate enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.properties._form', ['property' => $property])

        {{-- MEDIA SECTION --}}
        <div class="form-section">
            <h3 class="form-section-title">Multimedia</h3>
            <hr>

            {{-- IMAGE UPLOADER --}}
            <div class="media-section">
                <h4>Imágenes</h4>
                <div class="upload-zone" id="image-upload-zone" data-type="image" data-property="{{ $property->id }}">
                    <svg viewBox="0 0 24 24" width="40" height="40">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" fill="none"
                            stroke-width="1.5" />
                        <polyline points="17 8 12 3 7 8" stroke="currentColor" fill="none" stroke-width="1.5" />
                        <line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="1.5" />
                    </svg>
                    <p>Arrastra imágenes aquí o <label for="image-input" class="upload-link">selecciona archivos</label></p>
                    <small>JPG, PNG, WEBP — máx. 5 MB por imagen</small>
                    <input type="file" id="image-input" multiple accept="image/jpeg,image/png,image/webp"
                        class="upload-input" style="display:none">
                </div>
                <div class="media-grid" id="images-grid">
                    @foreach($property->medios->where('tipo_archivo', 'imagen') as $img)
                        <div class="media-item" data-id="{{ $img->id }}">
                            <img src="{{ $img->url }}" alt="Imagen" loading="lazy">
                            @if($img->is_cover)<span class="cover-badge">Portada</span>@endif
                            <div class="media-actions">
                                <button type="button" class="set-cover-btn {{ $img->is_cover ? 'cover-active' : '' }}"
                                    data-id="{{ $img->id }}" title="Establecer como portada">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                </button>
                                <button type="button" class="delete-media-btn" data-id="{{ $img->id }}"
                                    title="Eliminar">
                                    <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

        <div class="form-actions">
            <a href="{{ route('admin.properties.index') }}" class="btn-admin btn-admin-outline">Cancelar</a>
            <button type="submit" class="btn-admin btn-admin-primary">Guardar cambios</button>
        </div>
    </form>
@endsection

@push('scripts')
    <script>
        window.PROPERTY_ID = {{ $property->id }};
        window.MEDIA_STORE_URL = '{{ route("admin.media.store", $property->id) }}';
        window.MEDIA_BASE_URL = '{{ route("admin.media.destroy", ["id" => "__ID__"]) }}';
        window.COVER_BASE_URL = '{{ route("admin.media.cover", ["id" => "__ID__"]) }}';
    </script>
    <script src="{{ asset('js/media-uploader.js') }}"></script>
@endpush