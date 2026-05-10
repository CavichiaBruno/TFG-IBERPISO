@extends('layouts.app')
@section('title', 'Crear Publicación')

@push('styles')
<style>
    .create-page-bg {
        background-color: #f5f5f7;
        min-height: 100vh;
        padding-top: 80px;
        padding-bottom: 120px;
    }
    
    .create-container {
        max-width: 980px; /* Apple's max content width */
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .create-header {
        text-align: center;
        margin-bottom: 60px;
    }
    
    .create-title {
        font-family: 'SF Pro Display', -apple-system, sans-serif;
        font-size: 56px;
        font-weight: 600;
        letter-spacing: -0.28px;
        line-height: 1.07;
        color: #1d1d1f;
        margin-bottom: 16px;
    }
    
    .create-subtitle {
        font-family: 'SF Pro Text', -apple-system, sans-serif;
        font-size: 17px;
        line-height: 1.47;
        letter-spacing: -0.374px;
        color: rgba(0, 0, 0, 0.8);
        max-width: 600px;
        margin: 0 auto;
    }

    .form-card {
        background: #ffffff;
        border-radius: 12px;
        padding: 60px;
        box-shadow: rgba(0, 0, 0, 0.22) 3px 5px 30px 0px; /* Signature soft shadow */
        border: none;
        margin-bottom: 40px;
    }

    .form-section-title {
        font-family: 'SF Pro Display', -apple-system, sans-serif;
        font-size: 28px;
        font-weight: 400;
        line-height: 1.14;
        letter-spacing: 0.196px;
        color: #1d1d1f;
        margin-bottom: 40px;
        border: none;
        text-align: center;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    .form-grid-3 {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 24px;
    }

    @media(max-width: 768px) {
        .form-grid { grid-template-columns: 1fr; }
        .form-card { padding: 32px 24px; }
        .create-title { font-size: 36px; }
        .create-page-bg { padding-top: 48px; padding-bottom: 80px; }
        .create-header { margin-bottom: 40px; }
        .step-actions { flex-direction: column-reverse; gap: 12px; align-items: stretch; }
        .step-actions button, .step-actions .btn-ghost { width: 100%; text-align: center; }
        .checkbox-group { gap: 16px; }
    }

    @media(max-width: 480px) {
        .create-title { font-size: 28px; letter-spacing: -0.02em; }
        .create-subtitle { font-size: 15px; }
        .form-card { padding: 24px 16px; border-radius: 16px; }
        .form-section-title { font-size: 22px; margin-bottom: 24px; }
        .create-container { padding: 0 16px; }
        .create-page-bg { padding-top: 32px; padding-bottom: 64px; }
        .file-upload-box { padding: 40px 16px; }
        .step-progress-container { margin-bottom: 28px; }
        .preview-img { width: 90px; height: 90px; }
    }

    .form-group {
        margin-bottom: 24px;
    }
    
    .form-group.full-width {
        grid-column: 1 / -1;
    }

    .form-label {
        display: block;
        font-family: 'SF Pro Text', -apple-system, sans-serif;
        font-size: 14px;
        font-weight: 600;
        letter-spacing: -0.224px;
        color: #1d1d1f;
        margin-bottom: 10px;
    }

    .form-input, .form-select, .form-textarea {
        width: 100%;
        padding: 16px 20px;
        font-family: 'SF Pro Text', -apple-system, sans-serif;
        font-size: 17px;
        color: #1d1d1f;
        background-color: #f5f5f7;
        border: none;
        border-radius: 8px;
        transition: box-shadow 0.2s ease;
        box-sizing: border-box;
    }
    
    .form-textarea {
        resize: none;
    }

    .form-input:focus, .form-select:focus, .form-textarea:focus {
        outline: none;
        background-color: #ffffff;
        box-shadow: 0 0 0 2px #0071e3;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #0071e3;
        color: #ffffff;
        padding: 12px 24px;
        border-radius: 980px;
        font-family: 'SF Pro Text', -apple-system, sans-serif;
        font-size: 17px;
        font-weight: 400;
        border: none;
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .btn-primary:hover {
        background: #0077ED;
    }
    .btn-primary:active {
        background: #ededf2;
        color: #1d1d1f;
    }
    .btn-primary:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .btn-intelligence {
        background: linear-gradient(135deg, #0071e3 0%, #2997ff 100%);
        color: white;
        box-shadow: rgba(0, 113, 227, 0.3) 0px 4px 14px 0px;
    }
    .btn-intelligence:hover {
        background: linear-gradient(135deg, #0077ED 0%, #30A0FF 100%);
    }

    .file-upload-box {
        border-radius: 12px;
        padding: 60px 20px;
        text-align: center;
        background: #f5f5f7;
        cursor: pointer;
        transition: all 0.2s;
    }
    .file-upload-box:hover {
        background: #ededf2;
    }
    
    #preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 24px;
        justify-content: center;
    }
    .preview-img {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: rgba(0, 0, 0, 0.1) 0px 4px 12px;
    }

    .ai-result-box {
        background: #f5f5f7;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 40px;
        text-align: left;
    }
    
    .ai-rec-item {
        margin-bottom: 16px;
    }
    .ai-rec-item:last-child {
        margin-bottom: 0;
    }
    .ai-badge {
        font-family: 'SF Pro Text', sans-serif;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        margin-right: 8px;
    }
    .badge-alta { color: #ff3b30; }
    .badge-media { color: #ff9500; }
    .badge-baja { color: #0071e3; }

    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 24px;
    }
    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-family: 'SF Pro Text', -apple-system, sans-serif;
        font-size: 17px;
        cursor: pointer;
        color: #1d1d1f;
    }
    .checkbox-label input {
        width: 20px;
        height: 20px;
        accent-color: #0071e3;
    }
    
    /* GLOBAL FONTS - APPLE SYSTEM ENFORCED */
    * {
        font-family: 'SF Pro Text', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
    }
    
    h1, h2, h3, h4, h5, h6, .create-title, .form-section-title, .btn-primary, .btn-intelligence, .ai-result-box h4 {
        font-family: 'SF Pro Display', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
    }

    /* MULTI-STEP SLIDES UI */
    .form-step {
        display: none;
    }
    .form-step.active {
        display: block;
        animation: slideUpFade 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }
    @keyframes slideUpFade {
        0% { opacity: 0; transform: translateY(30px); }
        100% { opacity: 1; transform: translateY(0); }
    }

    .step-progress-container {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 12px;
        margin-bottom: 40px;
    }
    .step-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #d2d2d7;
        transition: background 0.4s ease, transform 0.4s ease;
    }
    .step-dot.active {
        background: #0071e3;
        transform: scale(1.3);
    }
    .step-dot.completed {
        background: #0071e3;
    }

    .step-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 40px;
        border-top: 1px solid #e5e5ea;
        padding-top: 30px;
    }
    
    .btn-ghost {
        background: transparent;
        color: #1d1d1f;
        border: none;
        font-family: 'SF Pro Text', -apple-system, sans-serif;
        font-size: 17px;
        font-weight: 400;
        cursor: pointer;
        padding: 12px 24px;
        transition: color 0.2s;
    }
    .btn-ghost:hover {
        color: #0071e3;
    }
</style>
@endpush

@section('content')
<div class="create-page-bg">
    <div class="create-container">
        
        <div class="create-header">
            <h1 class="create-title">Publica tu inmueble</h1>
            <p class="create-subtitle">Sube tus fotos, añade los detalles y pon tu propiedad a la vista de miles de compradores y arrendatarios en IberPiso.</p>
        </div>

        @if ($errors->any())
            <div style="background: #ffefef; border: 1px solid #ff3b30; color: #ff3b30; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('user.properties.store') }}" method="POST" enctype="multipart/form-data" id="property-form">
            @csrf
            
            <div class="step-progress-container" id="progress-indicator">
                <div class="step-dot active" id="dot-1"></div>
                <div class="step-dot" id="dot-2"></div>
                <div class="step-dot" id="dot-3"></div>
                <div class="step-dot" id="dot-4"></div>
            </div>

            {{-- STEP 1: DETALLES PRINCIPALES --}}
            <div class="form-step active" id="step-1">
                <div class="form-card" style="position: relative;">
                    <h2 class="form-section-title">Detalles Principales</h2>
                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">Operación *</label>
                            <select name="operation_type" class="form-select" required>
                                <option value="">Selecciona...</option>
                                <option value="venta" {{ old('operation_type') == 'venta' ? 'selected' : '' }}>Venta</option>
                                <option value="alquiler" {{ old('operation_type') == 'alquiler' ? 'selected' : '' }}>Alquiler</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Tipo de Inmueble *</label>
                            <select name="property_type" class="form-select" required>
                                <option value="">Selecciona...</option>
                                <option value="piso" {{ old('property_type') == 'piso' ? 'selected' : '' }}>Piso</option>
                                <option value="chalet" {{ old('property_type') == 'chalet' ? 'selected' : '' }}>Chalet</option>
                                <option value="duplex" {{ old('property_type') == 'duplex' ? 'selected' : '' }}>Dúplex</option>
                                <option value="atico" {{ old('property_type') == 'atico' ? 'selected' : '' }}>Ático</option>
                                <option value="estudio" {{ old('property_type') == 'estudio' ? 'selected' : '' }}>Estudio</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Precio (€) *</label>
                            <input type="number" name="price" class="form-input" value="{{ old('price') }}" required min="0" step="0.01" placeholder="Ej: 250000">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Superficie (m²) *</label>
                            <input type="number" name="surface_m2" class="form-input" value="{{ old('surface_m2') }}" required min="0" placeholder="Ej: 95">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Habitaciones *</label>
                            <input type="number" name="rooms" class="form-input" value="{{ old('rooms') }}" required min="0" placeholder="0">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Baños *</label>
                            <input type="number" name="bathrooms" class="form-input" value="{{ old('bathrooms') }}" required min="0" placeholder="0">
                        </div>
                    </div>
                    
                    <div class="step-actions" style="justify-content: flex-end;">
                        <button type="button" class="btn-primary" onclick="goToStep(2)">Continuar</button>
                    </div>
                </div>
            </div>

            {{-- STEP 2: UBICACIÓN Y EXTRAS --}}
            <div class="form-step" id="step-2">
                <div class="form-card" style="position: relative;">
                    <h2 class="form-section-title">Ubicación y Extras</h2>
                    <div class="form-grid" style="margin-bottom: 30px;">
                        <div class="form-group full-width">
                            <label class="form-label">Dirección Completa *</label>
                            <input type="text" name="address" class="form-input" value="{{ old('address') }}" required placeholder="Calle...">
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label">Ciudad *</label>
                            <input type="text" name="city" class="form-input" value="{{ old('city') }}" required>
                        </div>
                        
                        @php
                            $provinces = ["Álava","Albacete","Alicante","Almería","Asturias","Ávila","Badajoz","Baleares","Barcelona","Burgos","Cáceres","Cádiz","Cantabria","Castellón","Ciudad Real","Córdoba","Cuenca","Gerona","Granada","Guadalajara","Guipúzcoa","Huelva","Huesca","Jaén","La Coruña","La Rioja","Las Palmas","León","Lérida","Lugo","Madrid","Málaga","Murcia","Navarra","Orense","Palencia","Pontevedra","Salamanca","Santa Cruz de Tenerife","Segovia","Sevilla","Soria","Tarragona","Teruel","Toledo","Valencia","Valladolid","Vizcaya","Zamora","Zaragoza"];
                        @endphp
                        <div class="form-group">
                            <label class="form-label">Provincia *</label>
                            <select name="province" class="form-select" required>
                                <option value="">Seleccionar</option>
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov }}" {{ old('province') == $prov ? 'selected' : '' }}>{{ $prov }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Código Postal *</label>
                            <input type="text" name="postal_code" class="form-input" value="{{ old('postal_code') }}" required pattern="[0-9]{5}" maxlength="5" placeholder="00000">
                        </div>
                    </div>

                    <h3 style="font-size:20px; margin-bottom: 16px; color:#1d1d1f; font-weight: 600;">Características Extra</h3>
                    <div class="checkbox-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="ascensor" value="1" {{ old('ascensor') ? 'checked' : '' }}>
                            Ascensor
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="piscina" value="1" {{ old('piscina') ? 'checked' : '' }}>
                            Piscina
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="garaje" value="1" {{ old('garaje') ? 'checked' : '' }}>
                            Garaje
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="trastero" value="1" {{ old('trastero') ? 'checked' : '' }}>
                            Trastero
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="jardin" value="1" {{ old('jardin') ? 'checked' : '' }}>
                            Jardín
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="terraza" value="1" {{ old('terraza') ? 'checked' : '' }}>
                            Terraza
                        </label>
                    </div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn-ghost" onclick="goToStep(1)">Atrás</button>
                        <button type="button" class="btn-primary" onclick="goToStep(3)">Continuar</button>
                    </div>
                </div>
            </div>

            {{-- STEP 3: IMÁGENES --}}
            <div class="form-step" id="step-3">
                <div class="form-card" style="position: relative;">
                    <h2 class="form-section-title">Fotografías del Inmueble</h2>
                    <p style="font-family: 'SF Pro Text'; font-size: 15px; color: rgba(0,0,0,0.6); margin-bottom: 20px;">
                        Sube imágenes de buena calidad. La primera imagen será usada para nuestra Inteligencia Artificial en el siguiente paso.
                    </p>
                    <label for="images-input" class="file-upload-box" style="display: block;">
                        <svg viewBox="0 0 24 24" width="48" height="48" fill="none" stroke="#0071e3" stroke-width="1.5" style="margin-bottom: 16px;">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                        <div style="font-family: 'SF Pro Text'; font-size: 17px; font-weight: 600; color: #1d1d1f; margin-bottom: 4px;">Haz clic aquí para seleccionar fotos</div>
                        <div style="font-family: 'SF Pro Text'; font-size: 14px; color: #86868b;">JPG, PNG. Máximo 5MB por foto.</div>
                        <input type="file" name="images[]" id="images-input" multiple accept="image/*" style="display: none;">
                    </label>
                    
                    @error('images.*')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror

                    <div id="preview-container"></div>
                    
                    <div class="step-actions">
                        <button type="button" class="btn-ghost" onclick="goToStep(2)">Atrás</button>
                        <button type="button" class="btn-primary" onclick="goToStep(4)">Continuar</button>
                    </div>
                </div>
            </div>

            {{-- STEP 4: TÍTULO Y DESCRIPCIÓN CON IA --}}
            <div class="form-step" id="step-4">
                <div class="form-card" style="position: relative; text-align: center; overflow: hidden; min-height: 500px;">
                    
                    <h2 class="form-section-title">Presentación Inmobiliaria</h2>
                    <p style="font-size: 17px; color: rgba(0,0,0,0.8); margin: 0 auto 40px; max-width: 600px;">
                        Escribe el texto comercial para tu anuncio. Si ya has subido fotos, usa la Inteligencia Artificial para redactar un texto persuasivo de nivel experto.
                    </p>
                    
                    <div style="margin-bottom: 40px;" id="ai-actions">
                        <button type="button" class="btn-primary btn-intelligence" id="btn-analyze-ai" style="gap: 8px;">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
                            <span>Autocompletar con Inteligencia Artificial</span>
                        </button>
                    </div>

                    <div id="ai-result-box" class="ai-result-box" style="display: none;">
                        <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 16px;">
                            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="#0071e3" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <h4 style="font-size:21px; font-weight:600; margin:0; color:#1d1d1f;">Auditoría Fotográfica</h4>
                        </div>
                        <div id="ai-recommendations">
                            <!-- Dynamic content here -->
                        </div>
                    </div>

                    <div style="text-align: left;">
                        <div class="form-group full-width">
                            <label class="form-label">Título Comercial *</label>
                            <input type="text" name="title" id="input-title" class="form-input" value="{{ old('title') }}" required placeholder="Ej: Espectacular piso céntrico...">
                        </div>
                        
                        <div class="form-group full-width">
                            <label class="form-label">Descripción Persuasiva *</label>
                            <textarea name="description" id="input-description" class="form-textarea" rows="8" required minlength="100" placeholder="Describe los beneficios, el estilo de vida y las sensaciones..."></textarea>
                        </div>
                    </div>
                    
                    <div class="step-actions" style="margin-top: 60px;">
                        <button type="button" class="btn-ghost" onclick="goToStep(3)">Atrás</button>
                        <button type="submit" class="btn-primary" id="btn-submit" style="font-weight: 600;">Publicar Inmueble</button>
                    </div>
                </div>
            </div>
            
        </form>
    </div>
</div>

<!-- Lottie Global Overlay (Fixed to Viewport, Unaffected by Layout Shifts) -->
<div id="ai-loading-overlay" style="opacity: 0; pointer-events: none; position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: rgba(255,255,255,0.85); backdrop-filter: blur(40px) saturate(150%); -webkit-backdrop-filter: blur(40px) saturate(150%); z-index: 9999; flex-direction: column; align-items: center; justify-content: center; transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1); display: flex;">
    
    <div id="ai-loading-content" style="display: flex; flex-direction: column; align-items: center; justify-content: center; transform: scale(0.85); transition: transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275);">
        <!-- Lottie Element Provided by User -->
        <dotlottie-wc src="https://lottie.host/d469d6b2-6e5e-4df9-99e2-e45973cd59c4/61j44lYP6T.lottie" style="width: 300px; height: 300px;" autoplay loop worker="true"></dotlottie-wc>
        
        <div style="text-align: center; margin-top: 10px;">
            <h3 id="ai-loading-text" style="font-family: 'SF Pro Display', sans-serif; font-size: 32px; font-weight: 700; color: #1d1d1f; margin: 0; letter-spacing: -0.03em; transition: opacity 0.3s ease;">Analizando imágenes...</h3>
            <p id="ai-loading-subtext" style="font-family: 'SF Pro Text', sans-serif; font-size: 19px; color: #86868b; margin: 12px 0 0; font-weight: 400; transition: opacity 0.3s ease;">Nuestra IA está redactando el anuncio perfecto</p>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // --- MULTI-STEP LOGIC ---
    let currentStepIndex = 1;
    const totalSteps = 4;
    
    function goToStep(step) {
        if (step > currentStepIndex) {
            // Validate current step before moving forward
            const currentStepEl = document.getElementById('step-' + currentStepIndex);
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
        
        // Hide all
        for(let i=1; i<=totalSteps; i++) {
            document.getElementById('step-' + i).classList.remove('active');
            
            const dot = document.getElementById('dot-' + i);
            dot.classList.remove('active');
            if (i < step) {
                dot.classList.add('completed');
            } else {
                dot.classList.remove('completed');
            }
        }
        
        // Show target
        document.getElementById('step-' + step).classList.add('active');
        document.getElementById('dot-' + step).classList.add('active');
        
        // Scroll slightly to keep header visible without harsh jumps
        const formCard = document.querySelector('#step-' + step + ' .form-card');
        if (formCard) {
            formCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
        
        currentStepIndex = step;
    }

    // --- IMAGES & AI LOGIC ---
    const imageInput = document.getElementById('images-input');
    const previewContainer = document.getElementById('preview-container');
    const btnAnalyzeAi = document.getElementById('btn-analyze-ai');
    
    // Preview images
    imageInput.addEventListener('change', function() {
        previewContainer.innerHTML = '';
        if (this.files && this.files.length > 0) {
            Array.from(this.files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('preview-img');
                    previewContainer.appendChild(img);
                }
                reader.readAsDataURL(file);
            });
        }
    });

    // Analyze with Mistral AI
    btnAnalyzeAi.addEventListener('click', async function() {
        if (!imageInput.files || imageInput.files.length === 0) {
            alert('Por favor, sube al menos una imagen en el paso 3 para poder analizarla.');
            return;
        }
        
        // We send the first image for analysis
        const file = imageInput.files[0];
        const formData = new FormData();
        formData.append('image', file);
        
        const originalText = this.querySelector('span').innerText;
        
        const overlay = document.getElementById('ai-loading-overlay');
        const loadingContent = document.getElementById('ai-loading-content');
        const loadingText = document.getElementById('ai-loading-text');
        const loadingSubtext = document.getElementById('ai-loading-subtext');
        
        // Helper to update text with a small fade
        const updateLoadingText = (title, sub) => {
            loadingText.style.opacity = 0;
            loadingSubtext.style.opacity = 0;
            setTimeout(() => {
                loadingText.innerText = title;
                loadingSubtext.innerText = sub;
                loadingText.style.opacity = 1;
                loadingSubtext.style.opacity = 1;
            }, 300);
        };

        // Show overlay with animation
        overlay.style.opacity = 1;
        overlay.style.pointerEvents = 'all';
        loadingContent.style.transform = 'scale(1)';
        
        loadingText.innerText = 'Analizando imágenes...';
        loadingSubtext.innerText = 'Nuestra IA está redactando el anuncio perfecto';
        loadingText.style.opacity = 1;
        loadingSubtext.style.opacity = 1;
        
        // Fake progress states for better UX
        const stateTimer1 = setTimeout(() => {
            updateLoadingText('Redactando descripción...', 'Extrayendo puntos clave y beneficios');
        }, 3000);
        
        const stateTimer2 = setTimeout(() => {
            updateLoadingText('Optimizando título...', 'Aplicando técnicas de copywriting inmobiliario');
        }, 6000);
        
        const resultBox = document.getElementById('ai-result-box');
        const recommendationsBox = document.getElementById('ai-recommendations');
        
        try {
            const response = await fetch('{{ route("ai.analyzeImage") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });
            
            const data = await response.json();
            
            function extractText(obj) {
                if (typeof obj === 'string') return obj;
                if (Array.isArray(obj)) return obj.map(extractText).join('\\n');
                if (typeof obj === 'object' && obj !== null) {
                    return Object.values(obj).map(extractText).join('\\n\\n');
                }
                return String(obj);
            }
            
            if (response.ok) {
                if (data.title) {
                    document.getElementById('input-title').value = extractText(data.title).replace(/\\n/g, ' ');
                }
                if (data.description) {
                    document.getElementById('input-description').value = extractText(data.description);
                }
                
                // Render structured recommendations
                if (data.recommendations) {
                    recommendationsBox.innerHTML = '';
                    
                    let recs = data.recommendations;
                    if (typeof recs === 'string') {
                        recommendationsBox.innerHTML = '<div class="ai-rec-item"><p style="margin:0; font-family:\'SF Pro Text\'; font-size:17px; color:#1d1d1f; line-height: 1.47;">' + recs + '</p></div>';
                    } else if (Array.isArray(recs)) {
                        recs.forEach(rec => {
                            if (typeof rec === 'object' && rec.text) {
                                let badgeClass = 'badge-baja';
                                let prioLabel = rec.priority || 'Sugerencia';
                                if (prioLabel.toLowerCase().includes('alta')) badgeClass = 'badge-alta';
                                else if (prioLabel.toLowerCase().includes('media')) badgeClass = 'badge-media';
                                
                                recommendationsBox.innerHTML += `
                                    <div class="ai-rec-item">
                                        <p style="margin:0; font-family:'SF Pro Text'; font-size:17px; line-height:1.47; color:#1d1d1f;">
                                            <span class="ai-badge ${badgeClass}">[${prioLabel}]</span> ${rec.text}
                                        </p>
                                    </div>
                                `;
                            } else {
                                recommendationsBox.innerHTML += `
                                    <div class="ai-rec-item">
                                        <p style="margin:0; font-family:'SF Pro Text'; font-size:17px; line-height:1.47; color:#1d1d1f;">${extractText(rec)}</p>
                                    </div>
                                `;
                            }
                        });
                    }
                    resultBox.style.display = 'block';
                }
                
                // Show completion state in overlay before hiding
                clearTimeout(stateTimer1);
                clearTimeout(stateTimer2);
                updateLoadingText('¡Anuncio Completado!', 'Revisa los textos y recomendaciones generadas');
                
                setTimeout(() => {
                    overlay.style.opacity = 0;
                    overlay.style.pointerEvents = 'none';
                    loadingContent.style.transform = 'scale(0.9)';
                    setTimeout(() => { this.disabled = false; }, 500);
                }, 2000);
                
            } else {
                clearTimeout(stateTimer1);
                clearTimeout(stateTimer2);
                overlay.style.opacity = 0;
                overlay.style.pointerEvents = 'none';
                alert('Error de IA: ' + (data.error || 'Error desconocido'));
                this.disabled = false;
            }
        } catch (error) {
            console.error(error);
            clearTimeout(stateTimer1);
            clearTimeout(stateTimer2);
            overlay.style.opacity = 0;
            overlay.style.pointerEvents = 'none';
            alert('Hubo un error contactando con la IA.');
            this.disabled = false;
        }
    });
    // Submit form protection
    document.getElementById('property-form').addEventListener('submit', function() {
        document.getElementById('btn-submit').innerText = 'Publicando...';
        document.getElementById('btn-submit').disabled = true;
    });
</script>

@endpush
