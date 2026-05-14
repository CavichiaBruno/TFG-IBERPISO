@extends('layouts.app')
@section('title', 'Crear Publicación')

@push('styles')
<style>
    :root {
        --apple-blue: #0071e3;
        --apple-gray: #f5f5f7;
        --apple-near-black: #1d1d1f;
        --apple-silver: #d2d2d7;
    }

    .create-page-bg {
        background-color: #ffffff;
        min-height: 100vh;
        padding-top: 60px;
        padding-bottom: 120px;
    }
    
    .create-container {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 40px;
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 60px;
        align-items: start;
    }
    
    @media(max-width: 1024px) {
        .create-container {
            grid-template-columns: 1fr;
            max-width: 700px;
            gap: 40px;
        }
        .preview-sticky { display: none; }
    }

    .create-header {
        grid-column: 1 / -1;
        text-align: left;
        margin-bottom: 40px;
    }
    
    .create-title {
        font-size: 48px;
        font-weight: 600;
        letter-spacing: -0.015em;
        line-height: 1.07;
        color: var(--apple-near-black);
        margin-bottom: 12px;
    }
    
    .create-subtitle {
        font-size: 19px;
        line-height: 1.47;
        letter-spacing: -0.022em;
        color: #86868b;
        max-width: 600px;
    }

    /* Selection Cards (The 'Enjoyable' Part) */
    .selection-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
        gap: 16px;
        margin-bottom: 32px;
    }

    .selection-card {
        background: var(--apple-gray);
        border: 2px solid transparent;
        border-radius: 18px;
        padding: 24px;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }

    .selection-card:hover {
        background: #ededf2;
        transform: translateY(-2px);
    }

    .selection-card.active {
        background: #ffffff;
        border-color: var(--apple-blue);
        box-shadow: 0 8px 24px rgba(0, 113, 227, 0.15);
    }

    .selection-card svg {
        width: 32px;
        height: 32px;
        color: #86868b;
        transition: color 0.3s;
    }

    .selection-card.active svg {
        color: var(--apple-blue);
    }

    .selection-card span {
        font-size: 15px;
        font-weight: 500;
        color: var(--apple-near-black);
    }

    /* Form Layout */
    .form-card {
        background: #ffffff;
        padding: 0;
        border: none;
        box-shadow: none;
    }

    .form-section-title {
        font-size: 24px;
        font-weight: 600;
        color: var(--apple-near-black);
        margin-bottom: 24px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 32px;
    }

    .form-group { margin-bottom: 0; }
    .full-width { grid-column: 1 / -1; }

    .form-label {
        font-size: 12px;
        font-weight: 600;
        color: #86868b;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-bottom: 8px;
        display: block;
    }

    .form-input, .form-textarea {
        background: var(--apple-gray);
        border-radius: 12px;
        padding: 14px 18px;
        font-size: 17px;
        border: 2px solid transparent;
        transition: all 0.2s;
        width: 100%;
    }

    .form-input:focus, .form-textarea:focus {
        background: #ffffff;
        border-color: var(--apple-blue);
        box-shadow: 0 0 0 4px rgba(0, 113, 227, 0.1);
    }

    .form-textarea:invalid, .form-textarea:required:invalid {
        border-color: #fca5a5;
    }

    /* Steps Progress Bar */
    .steps-nav {
        display: flex;
        gap: 8px;
        margin-bottom: 48px;
    }

    .step-indicator {
        flex: 1;
        height: 4px;
        background: var(--apple-gray);
        border-radius: 2px;
        position: relative;
        overflow: hidden;
    }

    .step-indicator::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 0;
        background: var(--apple-blue);
        transition: width 0.6s cubic-bezier(0.65, 0, 0.35, 1);
    }

    .step-indicator.active::after { width: 100%; }
    .step-indicator.completed::after { width: 100%; }

    /* Sticky Preview */
    .preview-sticky {
        position: sticky;
        top: 100px;
        background: #ffffff;
        border-radius: 24px;
        padding: 32px;
        border: 1px solid #e5e5ea;
        box-shadow: 0 20px 40px rgba(0,0,0,0.04);
    }

    .preview-tag {
        font-size: 12px;
        font-weight: 600;
        color: var(--apple-blue);
        text-transform: uppercase;
        margin-bottom: 24px;
        display: block;
    }

    .preview-card-mock {
        border-radius: 16px;
        overflow: hidden;
        background: var(--apple-gray);
    }

    .preview-img-placeholder {
        width: 100%;
        aspect-ratio: 4/3;
        background: #e5e5ea;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #86868b;
    }

    .preview-content {
        padding: 20px;
    }

    .preview-title {
        font-size: 19px;
        font-weight: 600;
        margin-bottom: 8px;
        min-height: 1.2em;
    }

    .preview-price {
        font-size: 21px;
        font-weight: 700;
        color: var(--apple-near-black);
    }

    /* Multi-step slides */
    .form-step { display: none; }
    .form-step.active { display: block; animation: appleSlideIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

    @keyframes appleSlideIn {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .step-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 48px;
    }

    .btn-apple {
        font-size: 17px;
        font-weight: 500;
        padding: 14px 28px;
        border-radius: 980px;
        transition: all 0.3s;
        cursor: pointer;
        border: none;
    }

    .btn-apple-primary {
        background: var(--apple-blue);
        color: #ffffff;
    }

    .btn-apple-primary:hover { transform: scale(1.02); background: #0077ED; }
    .btn-apple-secondary { background: var(--apple-gray); color: var(--apple-near-black); }
    .btn-apple-secondary:hover { background: #e5e5ea; }

    /* Custom Checkbox Pills */
    .extra-pills {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .extra-pill {
        cursor: pointer;
    }

    .extra-pill input { display: none; }

    .extra-pill span {
        display: inline-block;
        padding: 10px 20px;
        background: var(--apple-gray);
        border-radius: 980px;
        font-size: 15px;
        color: var(--apple-near-black);
        transition: all 0.2s;
    }

    .extra-pill input:checked + span {
        background: var(--apple-near-black);
        color: #ffffff;
    }

</style>
@endpush

@section('content')
<div class="create-page-bg">
    <div class="create-container">
        
        <div class="create-header">
            <h1 class="create-title">Vende o alquila tu propiedad.</h1>
            <p class="create-subtitle">Un proceso sencillo, minimalista e impulsado por inteligencia artificial.</p>
        </div>

        <div class="form-area">
            <div class="steps-nav">
                <div class="step-indicator active" id="dot-1"></div>
                <div class="step-indicator" id="dot-2"></div>
                <div class="step-indicator" id="dot-3"></div>
                <div class="step-indicator" id="dot-4"></div>
            </div>

            <form action="{{ route('user.properties.store') }}" method="POST" enctype="multipart/form-data" id="property-form">
                @csrf
                
                @if($errors->any())
                <div style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 12px; padding: 16px; margin-bottom: 32px; color: #991b1b;">
                    <h3 style="font-weight: 600; margin-bottom: 8px;">Error en el formulario</h3>
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                
                {{-- STEP 1: TIPO Y PRECIO --}}
                <div class="form-step active" id="step-1">
                    <h2 class="form-section-title">¿Qué quieres publicar?</h2>
                    
                    <label class="form-label">Tipo de operación</label>
                    <div class="selection-grid">
                        <div class="selection-card" onclick="selectOption('tipo_operacion', 'venta', this)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            <span>Venta</span>
                        </div>
                        <div class="selection-card" onclick="selectOption('tipo_operacion', 'alquiler', this)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10V4a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v6h18zM3 10v10a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V10H3z"/></svg>
                            <span>Alquiler</span>
                        </div>
                    </div>
                    <input type="hidden" name="tipo_operacion" id="input-tipo_operacion" required>

                    <label class="form-label">Tipo de inmueble</label>
                    <div class="selection-grid">
                        <div class="selection-card" onclick="selectOption('tipo_propiedad', 'piso', this)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 21h18M3 7h18M3 3h18M3 11h18M3 15h18M3 19h18M3 3v18M21 3v18"/></svg>
                            <span>Piso</span>
                        </div>
                        <div class="selection-card" onclick="selectOption('tipo_propiedad', 'casa', this)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                            <span>Casa</span>
                        </div>
                        <div class="selection-card" onclick="selectOption('tipo_propiedad', 'chalet', this)">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 3 2 12h3v8h14v-8h3L12 3z"/></svg>
                            <span>Chalet</span>
                        </div>
                    </div>
                    <input type="hidden" name="tipo_propiedad" id="input-tipo_propiedad" required>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Precio (€)</label>
                            <input type="number" name="precio" id="input-precio" class="form-input" placeholder="0" value="{{ old('precio') }}" required oninput="updatePreview()">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Superficie (m²)</label>
                            <input type="number" name="superficie_m2" class="form-input" placeholder="0" value="{{ old('superficie_m2') }}" required>
                        </div>
                    </div>

                    <div class="step-actions">
                        <div></div>
                        <button type="button" class="btn-apple btn-apple-primary" onclick="goToStep(2)">Continuar</button>
                    </div>
                </div>

                {{-- STEP 2: CARACTERÍSTICAS --}}
                <div class="form-step" id="step-2">
                    <h2 class="form-section-title">Habitaciones y Extras</h2>
                    
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Habitaciones</label>
                            <input type="number" name="habitaciones" class="form-input" placeholder="0" value="{{ old('habitaciones') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Baños</label>
                            <input type="number" name="banos" class="form-input" placeholder="0" value="{{ old('banos') }}" required>
                        </div>
                        <div class="form-group full-width">
                            <label class="form-label">Dirección</label>
                            <input type="text" name="direccion" id="input-direccion" class="form-input" placeholder="Ej: Calle Principal, 123" value="{{ old('direccion') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Ciudad</label>
                            <input type="text" name="ciudad" id="input-ciudad" class="form-input" placeholder="Madrid, Barcelona..." value="{{ old('ciudad') }}" required oninput="updatePreview()">
                        </div>
                        <div class="form-group">
                            <label class="form-label">Provincia</label>
                            <input type="text" name="provincia" id="input-provincia" class="form-input" placeholder="Madrid, Barcelona..." value="{{ old('provincia') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Código Postal</label>
                            <input type="text" name="codigo_postal" id="input-codigo-postal" class="form-input" placeholder="28001" value="{{ old('codigo_postal') }}" required pattern="[0-9]{5}" maxlength="5">
                        </div>
                    </div>

                    <label class="form-label">Características adicionales</label>
                    <div class="extra-pills">
                        <label class="extra-pill"><input type="checkbox" name="tiene_ascensor"><span>Ascensor</span></label>
                        <label class="extra-pill"><input type="checkbox" name="tiene_parking"><span>Garaje</span></label>
                        <label class="extra-pill"><input type="checkbox" name="tiene_terraza"><span>Terraza</span></label>
                        <label class="extra-pill"><input type="checkbox" name="tiene_piscina"><span>Piscina</span></label>
                        <label class="extra-pill"><input type="checkbox" name="aire_acondicionado"><span>Aire Acond.</span></label>
                    </div>

                    <div class="form-group full-width" style="margin-top: 32px;">
                        <label class="form-label">Certificado Energético (PDF)</label>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <input type="file" name="certificado_energetico_archivo" id="cert-input" accept="application/pdf" style="display: none;">
                            <button type="button" class="btn-apple btn-apple-secondary" onclick="document.getElementById('cert-input').click()" id="btn-cert">
                                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" style="margin-right: 8px; vertical-align: middle;"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                                Adjuntar certificado
                            </button>
                            <span id="cert-filename" style="font-size: 14px; color: #86868b;">Ningún archivo seleccionado</span>
                        </div>
                        <p style="font-size: 13px; color: #86868b; margin-top: 8px;">Sube el documento oficial en formato PDF para que los compradores puedan descargarlo.</p>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn-apple btn-apple-secondary" onclick="goToStep(1)">Atrás</button>
                        <button type="button" class="btn-apple btn-apple-primary" onclick="goToStep(3)">Continuar</button>
                    </div>
                </div>

                {{-- STEP 3: IMÁGENES --}}
                <div class="form-step" id="step-3">
                    <h2 class="form-section-title">Fotos del inmueble</h2>
                    <p style="color: #86868b; margin-bottom: 24px;">Sube al menos una foto para que nuestra IA pueda analizar el espacio.</p>
                    
                    <div class="file-upload-area" style="border: 2px dashed #d2d2d7; border-radius: 20px; padding: 60px; text-align: center;">
                        <input type="file" name="images[]" id="images-input" multiple accept="image/*" style="display: none;">
                        <button type="button" class="btn-apple btn-apple-secondary" onclick="document.getElementById('images-input').click()">Seleccionar archivos</button>
                        <div id="preview-container" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(80px, 1fr)); gap: 12px; margin-top: 32px;"></div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn-apple btn-apple-secondary" onclick="goToStep(2)">Atrás</button>
                        <button type="button" class="btn-apple btn-apple-primary" onclick="goToStep(4)">Continuar</button>
                    </div>
                </div>

                {{-- STEP 4: IA Y DESCRIPCIÓN --}}
                <div class="form-step" id="step-4">
                    <h2 class="form-section-title">Título y Descripción</h2>
                    
                    <div style="background: var(--apple-near-black); color: white; padding: 24px; border-radius: 20px; margin-bottom: 32px; display: flex; align-items: center; justify-content: space-between;">
                        <div style="display: flex; align-items: center; gap: 16px;">
                            <div style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 12px;">
                                <svg viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                            </div>
                            <div>
                                <div style="font-weight: 600;">IberIA Magic</div>
                                <div style="font-size: 13px; opacity: 0.7;">Redacción automática profesional</div>
                            </div>
                        </div>
                        <button type="button" class="btn-apple" id="btn-analyze-ai" style="background: white; color: black; font-size: 14px; font-weight: 600;">Generar ahora</button>
                    </div>

                    <div class="form-group full-width" style="margin-bottom: 24px;">
                        <label class="form-label">Título comercial</label>
                        <input type="text" name="titulo" id="input-title" class="form-input" placeholder="Ej: Ático de lujo en el centro" value="{{ old('titulo') }}" required oninput="updatePreview()">
                    </div>
                    <div class="form-group full-width">
                        <label class="form-label">Descripción</label>
                        <textarea name="descripcion" id="input-description" class="form-textarea" rows="6" placeholder="Cuéntanos más sobre la propiedad..." required minlength="100" oninput="validateDescription()">{{ old('descripcion') }}</textarea>
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 8px;">
                            <p style="font-size: 13px; color: #86868b; margin: 0;">Mínimo 100 caracteres requeridos</p>
                            <span id="char-count" style="font-size: 13px; font-weight: 600; color: #86868b;">0/100</span>
                        </div>
                        <div id="description-error" style="display: none; background: #fee2e2; border: 1px solid #fecaca; border-radius: 8px; padding: 8px 12px; margin-top: 8px; color: #991b1b; font-size: 13px;"></div>
                    </div>

                    <div class="step-actions">
                        <button type="button" class="btn-apple btn-apple-secondary" onclick="goToStep(3)">Atrás</button>
                        <button type="submit" class="btn-apple btn-apple-primary" id="btn-submit">Publicar anuncio</button>
                    </div>
                </div>
            </form>
        </div>

        {{-- PREVIEW SIDEBAR --}}
        <div class="preview-sticky">
            <span class="preview-tag">Vista previa en tiempo real</span>
            <div class="preview-card-mock">
                <div class="preview-img-placeholder" id="preview-image-box">
                    <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="currentColor" stroke-width="1"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                </div>
                <div class="preview-content">
                    <div class="preview-title" id="preview-title-text">Título de tu anuncio</div>
                    <div style="font-size: 14px; color: #86868b; margin-bottom: 12px;" id="preview-location-text">Ubicación</div>
                    <div class="preview-price" id="preview-price-text">0 €</div>
                </div>
            </div>
            
            <div style="margin-top: 32px; border-top: 1px solid #e5e5ea; padding-top: 32px;">
                <p style="font-size: 13px; color: #86868b; line-height: 1.5;">Tu anuncio se publicará al instante tras la revisión de seguridad.</p>
            </div>
        </div>

    </div>
</div>

<div id="ai-loading-overlay" style="opacity: 0; pointer-events: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(255,255,255,0.9); backdrop-filter: blur(20px); z-index: 9999; display: flex; flex-direction: column; align-items: center; justify-content: center; transition: all 0.5s;">
    <dotlottie-wc src="https://lottie.host/d469d6b2-6e5e-4df9-99e2-e45973cd59c4/61j44lYP6T.lottie" style="width: 200px; height: 200px;" autoplay loop></dotlottie-wc>
    <h3 style="margin-top: 20px; font-weight: 600;" id="ai-loading-text">Analizando tu propiedad...</h3>
    <p style="font-size: 19px; color: #86868b; margin: 12px 0 0; font-weight: 400;">Redactando el anuncio perfecto</p>
</div>
@endsection

@push('scripts')
<script>
    // --- SELECTION LOGIC ---
    function selectOption(field, value, element) {
        document.getElementById('input-' + field).value = value;
        
        // Update UI
        const grid = element.parentElement;
        grid.querySelectorAll('.selection-card').forEach(card => card.classList.remove('active'));
        element.classList.add('active');
        
        updatePreview();
    }

    // --- PREVIEW LOGIC ---
    function updatePreview() {
        const title = document.getElementById('input-title').value || 'Título de tu anuncio';
        const price = document.getElementById('input-precio').value || '0';
        const city = document.getElementById('input-ciudad').value || 'Ubicación';
        const operacion = document.getElementById('input-tipo_operacion').value;
        
        document.getElementById('preview-title-text').innerText = title;
        document.getElementById('preview-price-text').innerText = price + ' €' + (operacion === 'alquiler' ? '/mes' : '');
        document.getElementById('preview-location-text').innerText = city;
    }

    // --- MULTI-STEP LOGIC ---
    let currentStepIndex = 1;
    const totalSteps = 4;
    
    function goToStep(step) {
        if (step > currentStepIndex) {
            // Validate current step before moving forward
            const currentStepEl = document.getElementById('step-' + currentStepIndex);
            
            // Check required inputs
            const inputs = currentStepEl.querySelectorAll('input[required], select[required], textarea[required]');
            let valid = true;
            
            inputs.forEach(input => {
                if (!input.checkValidity()) {
                    input.reportValidity();
                    valid = false;
                }
            });
            
            if (!valid) return;
        }
        
        // Update Steps Nav
        for(let i=1; i<=totalSteps; i++) {
            const indicator = document.getElementById('dot-' + i);
            indicator.classList.remove('active', 'completed');
            if (i === step) indicator.classList.add('active');
            else if (i < step) indicator.classList.add('completed');
        }
        
        // Show target step
        document.querySelectorAll('.form-step').forEach(s => s.classList.remove('active'));
        document.getElementById('step-' + step).classList.add('active');
        
        currentStepIndex = step;
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    // --- IMAGES LOGIC ---
    const imageInput = document.getElementById('images-input');
    const previewContainer = document.getElementById('preview-container');
    const previewImageBox = document.getElementById('preview-image-box');
    
    imageInput.addEventListener('change', function() {
        previewContainer.innerHTML = '';
        if (this.files && this.files.length > 0) {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImageBox.innerHTML = `<img src="${e.target.result}" style="width:100%; height:100%; object-fit:cover;">`;
            }
            reader.readAsDataURL(file);

            Array.from(this.files).forEach(file => {
                const r = new FileReader();
                r.onload = function(e) {
                    previewContainer.innerHTML += `<img src="${e.target.result}" style="width:100%; aspect-ratio:1/1; object-fit:cover; border-radius:8px;">`;
                }
                r.readAsDataURL(file);
            });
        }
    });

    // --- CERTIFICATE LOGIC ---
    document.getElementById('cert-input').addEventListener('change', function() {
        const filename = this.files.length > 0 ? this.files[0].name : 'Ningún archivo seleccionado';
        document.getElementById('cert-filename').innerText = filename;
        if (this.files.length > 0) {
            document.getElementById('btn-cert').style.borderColor = 'var(--apple-blue)';
            document.getElementById('btn-cert').style.color = 'var(--apple-blue)';
        }
    });

    // --- AI LOGIC ---
    const btnAnalyzeAi = document.getElementById('btn-analyze-ai');
    btnAnalyzeAi.addEventListener('click', async function() {
        if (!imageInput.files || imageInput.files.length === 0) {
            alert('Sube al menos una foto en el paso anterior.');
            return;
        }
        
        const overlay = document.getElementById('ai-loading-overlay');
        overlay.style.opacity = 1;
        overlay.style.pointerEvents = 'all';
        
        const formData = new FormData();
        formData.append('image', imageInput.files[0]);
        
        try {
            const response = await fetch('{{ route("ai.analyzeImage") }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
            });
            
            const data = await response.json();
            if (response.ok) {
                document.getElementById('input-title').value = data.title;
                document.getElementById('input-description').value = data.description;
                updatePreview();
                validateDescription(); // Actualizar contador y estado del botón
            } else {
                alert('Error al conectar con la IA');
            }
        } catch (e) {
            alert('Error de red');
        } finally {
            overlay.style.opacity = 0;
            overlay.style.pointerEvents = 'none';
        }
    });

    // Submit form protection with loading overlay
    document.getElementById('property-form').addEventListener('submit', function(e) {
        e.preventDefault(); // Detener envío inmediato
        
        const form = this;
        const overlay = document.getElementById('submit-loading-overlay');
        const spinnerContainer = document.getElementById('spinner-container');
        const successContainer = document.getElementById('success-container');
        
        // Resetear containers
        spinnerContainer.style.display = 'flex';
        successContainer.style.display = 'none';
        
        // Mostrar el overlay
        overlay.style.opacity = 1;
        overlay.style.pointerEvents = 'all';
        
        // Esperar un poco para simular procesamiento y mostrar check
        setTimeout(() => {
            spinnerContainer.style.display = 'none';
            successContainer.style.display = 'block';
        }, 2500);
        
        // Después de 4 segundos, enviar el formulario
        setTimeout(() => {
            form.submit();
        }, 4000);
    });

    // --- DESCRIPTION VALIDATION ---
    function validateDescription() {
        const textarea = document.getElementById('input-description');
        const charCount = document.getElementById('char-count');
        const errorDiv = document.getElementById('description-error');
        const submitBtn = document.getElementById('btn-submit');
        const length = textarea.value.length;
        const minLength = 100;
        
        charCount.innerText = length + '/' + minLength;
        
        if (length < minLength) {
            charCount.style.color = '#dc2626';
            errorDiv.style.display = 'block';
            errorDiv.innerText = `Necesitas ${minLength - length} caracteres más`;
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
        } else {
            charCount.style.color = '#16a34a';
            errorDiv.style.display = 'none';
            submitBtn.disabled = false;
            submitBtn.style.opacity = '1';
            submitBtn.style.cursor = 'pointer';
        }
    }

    // Initialize description validation
    const descriptionField = document.getElementById('input-description');
    if (descriptionField) {
        validateDescription();
        descriptionField.addEventListener('input', validateDescription);
    }

    // --- RESTORE PREVIOUS VALUES ON ERROR ---
    @if(old('tipo_operacion'))
    (function() {
        const oldType = '{{ old("tipo_operacion") }}';
        const cards = document.querySelectorAll('[onclick*="tipo_operacion"]');
        cards.forEach(card => {
            if (card.onclick.toString().includes(`'${oldType}'`)) {
                card.click();
            }
        });
    })();
    @endif

    @if(old('tipo_propiedad'))
    (function() {
        const oldType = '{{ old("tipo_propiedad") }}';
        const cards = document.querySelectorAll('[onclick*="tipo_propiedad"]');
        cards.forEach(card => {
            if (card.onclick.toString().includes(`'${oldType}'`)) {
                card.click();
            }
        });
    })();
    @endif

    // --- IF THERE ARE ERRORS, GO TO THE CORRECT STEP ---
    @if($errors->any())
    (function() {
        // Check which field has errors and go to corresponding step
        const errorMessages = @json($errors->keys());
        let targetStep = 1;
        
        if (errorMessages.includes('titulo') || errorMessages.includes('descripcion')) {
            targetStep = 4; // Title and description are in step 4
        } else if (errorMessages.includes('habitaciones') || errorMessages.includes('banos') || 
                   errorMessages.includes('ciudad') || errorMessages.includes('provincia') || 
                   errorMessages.includes('codigo_postal') || errorMessages.includes('direccion')) {
            targetStep = 2; // Location fields are in step 2
        } else if (errorMessages.includes('images')) {
            targetStep = 3; // Images are in step 3
        } else if (errorMessages.includes('precio') || errorMessages.includes('superficie_m2') || 
                   errorMessages.includes('tipo_operacion') || errorMessages.includes('tipo_propiedad')) {
            targetStep = 1; // Basic info is in step 1
        }
        
        setTimeout(() => goToStep(targetStep), 100);
    })();
    @endif

    // --- INITIALIZATION ---
    updatePreview();
</script>
@endpush
