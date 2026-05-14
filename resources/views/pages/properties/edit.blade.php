@extends('layouts.app')
@section('title', 'Editar: ' . $property->titulo)

@push('styles')
<style>
    .edit-page {
        background-color: #f5f5f7;
        min-height: 100vh;
        padding: 80px 0;
    }
    .edit-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 0 20px;
    }
    .edit-header {
        margin-bottom: 40px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    .edit-title {
        font-family: var(--font-display);
        font-size: 32px;
        font-weight: 600;
        color: #1d1d1f;
        margin: 0;
    }
    .form-card {
        background: #fff;
        border-radius: 24px;
        padding: 40px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        margin-bottom: 32px;
    }
    .form-section-title {
        font-size: 20px;
        font-weight: 600;
        color: #1d1d1f;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
        margin-bottom: 40px;
    }
    .full-width { grid-column: 1 / -1; }
    
    .form-label {
        font-size: 13px;
        font-weight: 600;
        color: #86868b;
        margin-bottom: 8px;
        display: block;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 14px 18px;
        border-radius: 12px;
        border: 1px solid #d2d2d7;
        background: #fff;
        font-size: 16px;
        transition: all 0.2s;
        color: #1d1d1f;
    }
    .form-input:focus {
        border-color: #0071e3;
        box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.1);
        outline: none;
    }
    
    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }
    .feature-checkbox {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 16px;
        background: #f5f5f7;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .feature-checkbox:hover {
        background: #e8e8ed;
    }
    .feature-checkbox input {
        width: 18px;
        height: 18px;
        accent-color: #0071e3;
    }
    
    .media-manager {
        margin-top: 40px;
    }
    .media-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 16px;
        margin-top: 16px;
    }
    .media-item {
        position: relative;
        aspect-ratio: 1/1;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid #d2d2d7;
    }
    .media-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .media-actions {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.3);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        opacity: 0;
        transition: opacity 0.2s;
    }
    .media-item:hover .media-actions {
        opacity: 1;
    }
    .btn-media {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: #fff;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #1d1d1f;
    }
    .btn-delete { color: #ff3b30; }
    .btn-cover.active { background: #0071e3; color: #fff; }

    .edit-actions {
        display: flex;
        justify-content: flex-end;
        gap: 16px;
        margin-top: 40px;
    }
    .btn-apple {
        padding: 14px 32px;
        border-radius: 980px;
        font-size: 17px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-apple-primary { background: #0071e3; color: #fff; border: none; }
    .btn-apple-primary:hover { background: #0077ed; transform: scale(1.02); }
    .btn-apple-secondary { background: #e8e8ed; color: #1d1d1f; border: none; }
    .btn-apple-secondary:hover { background: #d2d2d7; }

    .upload-box {
        border: 2px dashed #d2d2d7;
        border-radius: 16px;
        padding: 32px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .upload-box:hover {
        border-color: #0071e3;
        background: rgba(0, 113, 227, 0.02);
    }
</style>
@endpush

@section('content')
<div class="edit-page">
    <div class="edit-container">
        <div class="edit-header">
            <div>
                <h1 class="edit-title">Editar <span class="marker-white">Propiedad</span></h1>
                <p style="color: #86868b; margin-top: 8px;">Realiza cambios en tu anuncio</p>
            </div>
            <a href="{{ route('user.properties.index') }}" class="btn-apple btn-apple-secondary">Cancelar</a>
        </div>

        <form action="{{ route('user.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" id="edit-form">
            @csrf
            @method('PUT')

            <div class="form-card">
                <h2 class="form-section-title">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
                    Información Básica
                </h2>
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Título del anuncio</label>
                        <input type="text" name="titulo" class="form-input" value="{{ old('titulo', $property->titulo) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tipo de propiedad</label>
                        <select name="tipo_propiedad" class="form-select" required>
                            <option value="piso" {{ $property->tipo_propiedad == 'piso' ? 'selected' : '' }}>Piso</option>
                            <option value="casa" {{ $property->tipo_propiedad == 'casa' ? 'selected' : '' }}>Casa</option>
                            <option value="chalet" {{ $property->tipo_propiedad == 'chalet' ? 'selected' : '' }}>Chalet</option>
                            <option value="local" {{ $property->tipo_propiedad == 'local' ? 'selected' : '' }}>Local</option>
                            <option value="oficina" {{ $property->tipo_propiedad == 'oficina' ? 'selected' : '' }}>Oficina</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Operación</label>
                        <select name="tipo_operacion" class="form-select" required>
                            <option value="venta" {{ $property->tipo_operacion == 'venta' ? 'selected' : '' }}>Venta</option>
                            <option value="alquiler" {{ $property->tipo_operacion == 'alquiler' ? 'selected' : '' }}>Alquiler</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Precio (€)</label>
                        <input type="number" name="precio" class="form-input" value="{{ old('precio', $property->precio) }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Superficie (m²)</label>
                        <input type="number" name="superficie_m2" class="form-input" value="{{ old('superficie_m2', $property->superficie_m2) }}" required>
                    </div>
                </div>

                <h2 class="form-section-title">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    Ubicación
                </h2>
                
                <div class="form-grid">
                    <div class="form-group full-width">
                        <label class="form-label">Dirección</label>
                        <input type="text" name="direccion" class="form-input" value="{{ old('direccion', $property->direccion) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Ciudad</label>
                        <input type="text" name="ciudad" class="form-input" value="{{ old('ciudad', $property->ciudad) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Código Postal</label>
                        <input type="text" name="codigo_postal" class="form-input" value="{{ old('codigo_postal', $property->codigo_postal) }}" required>
                    </div>
                </div>
            </div>

            <div class="form-card">
                <h2 class="form-section-title">Detalles y Extras</h2>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Habitaciones</label>
                        <input type="number" name="habitaciones" class="form-input" value="{{ old('habitaciones', $property->habitaciones) }}" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Baños</label>
                        <input type="number" name="banos" class="form-input" value="{{ old('banos', $property->banos) }}" required>
                    </div>
                </div>
                
                <div class="features-grid">
                    @php
                        $features = [
                            'tiene_ascensor' => 'Ascensor',
                            'tiene_parking' => 'Garaje',
                            'tiene_terraza' => 'Terraza',
                            'tiene_jardin' => 'Jardín',
                            'tiene_piscina' => 'Piscina',
                            'aire_acondicionado' => 'Aire Acondicionado'
                        ];
                    @endphp
                    @foreach($features as $field => $label)
                        <label class="feature-checkbox">
                            <input type="checkbox" name="{{ $field }}" {{ $property->$field ? 'checked' : '' }}>
                            <span>{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="form-card">
                <h2 class="form-section-title">Descripción</h2>
                <textarea name="descripcion" class="form-textarea" rows="8" required minlength="100">{{ old('descripcion', $property->descripcion) }}</textarea>
                <p style="font-size: 13px; color: #86868b; margin-top: 8px;">Mínimo 100 caracteres. Una buena descripción ayuda a vender más rápido.</p>
            </div>

            <div class="form-card">
                <h2 class="form-section-title">Multimedia</h2>
                
                <div class="media-manager">
                    <label class="form-label">Imágenes actuales</label>
                    <div class="media-grid" id="images-grid">
                        @foreach($property->medios->where('tipo_archivo', 'imagen') as $img)
                            <div class="media-item" id="media-{{ $img->id }}">
                                <img src="{{ $img->url }}" alt="Propiedad">
                                <div class="media-actions">
                                    <button type="button" class="btn-media btn-cover {{ $img->es_portada ? 'active' : '' }}" onclick="setCover({{ $img->id }})" title="Portada">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="currentColor"><path d="M12 17.27L18.18 21l-1.64-7.03L22 9.24l-7.19-.61L12 2 9.19 8.63 2 9.24l5.46 4.73L5.82 21z"/></svg>
                                    </button>
                                    <button type="button" class="btn-media btn-delete" onclick="deleteMedia({{ $img->id }})" title="Eliminar">
                                        <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div style="margin-top: 32px;">
                    <label class="form-label">Añadir más fotos</label>
                    <div class="upload-box" onclick="document.getElementById('new-images').click()">
                        <svg viewBox="0 0 24 24" width="32" height="32" fill="none" stroke="currentColor" stroke-width="1.5" style="margin-bottom: 8px;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                        <p style="font-size: 15px; color: #1d1d1f; font-weight: 500;">Haz clic para subir nuevas fotos</p>
                        <input type="file" id="new-images" name="new_images[]" multiple accept="image/*" style="display: none;" onchange="handleNewImages(this)">
                    </div>
                    <div id="new-previews" class="media-grid"></div>
                </div>
            </div>

            <div class="edit-actions">
                <button type="submit" class="btn-apple btn-apple-primary">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<div id="edit-loading-overlay" style="opacity: 0; pointer-events: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: all 0.3s;">
    <div style="width: 60px; height: 60px; border: 4px solid #f5f5f7; border-top-color: #0071e3; border-radius: 50%; animation: spin 1s linear infinite;"></div>
    <p style="margin-top: 20px; font-weight: 600; color: #1d1d1f;">Guardando cambios...</p>
</div>

<style>
@keyframes spin { to { transform: rotate(360deg); } }
</style>
@endsection

@push('scripts')
<script>
    // --- MEDIA MANAGEMENT ---
    function setCover(id) {
        fetch(`/admin/media/${id}/cover`, {
            method: 'PATCH',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.querySelectorAll('.btn-cover').forEach(btn => btn.classList.remove('active'));
                const btn = document.querySelector(`#media-${id} .btn-cover`);
                if (btn) btn.classList.add('active');
            }
        });
    }

    function deleteMedia(id) {
        if (!confirm('¿Seguro que quieres eliminar esta imagen?')) return;
        
        fetch(`/admin/media/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`media-${id}`).remove();
            }
        });
    }

    function handleNewImages(input) {
        const container = document.getElementById('new-previews');
        container.innerHTML = '';
        if (input.files) {
            Array.from(input.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'media-item';
                    div.innerHTML = `<img src="${e.target.result}">`;
                    container.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }

    // --- FORM SUBMISSION ---
    document.getElementById('edit-form').addEventListener('submit', function() {
        const overlay = document.getElementById('edit-loading-overlay');
        overlay.style.opacity = 1;
        overlay.style.pointerEvents = 'all';
    });
</script>
@endpush
