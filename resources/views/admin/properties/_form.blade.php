@php
    $provinces = ["Álava","Albacete","Alicante","Almería","Asturias","Ávila","Badajoz","Baleares","Barcelona","Burgos","Cáceres","Cádiz","Cantabria","Castellón","Ciudad Real","Córdoba","Cuenca","Gerona","Granada","Guadalajara","Guipúzcoa","Huelva","Huesca","Jaén","La Coruña","La Rioja","Las Palmas","León","Lérida","Lugo","Madrid","Málaga","Murcia","Navarra","Orense","Palencia","Pontevedra","Salamanca","Santa Cruz de Tenerife","Segovia","Sevilla","Soria","Tarragona","Teruel","Toledo","Valencia","Valladolid","Vizcaya","Zamora","Zaragoza"];
@endphp

{{-- SECTION AI AUTO-FILL --}}
<div class="form-section ai-section" style="background: #f5f5f7; border: none; border-radius: 12px; padding: 24px; margin-bottom: 32px; box-shadow: none;">
    <h3 class="form-section-title" style="display: flex; align-items: center; gap: 8px; color: #1d1d1f; font-size: 1.1rem; margin-top:0;">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
        Asistente de IA Mistral
    </h3>
    <p style="font-size: 0.9rem; color: #86868b; margin-bottom: 15px;">Sube una foto de la propiedad y nuestra IA analizará la imagen para sugerirte un título, una descripción y recomendaciones de mejora.</p>
    
    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
        <input type="file" id="ai-image-input" accept="image/jpeg,image/png,image/webp" style="display: none;">
        <button type="button" class="btn-admin btn-admin-outline" onclick="document.getElementById('ai-image-input').click()" style="display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="16" height="16"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" fill="none" stroke-width="1.5"/><polyline points="17 8 12 3 7 8" stroke="currentColor" fill="none" stroke-width="1.5"/><line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="1.5"/></svg>
            Seleccionar foto para análisis
        </button>
        <div id="ai-loading" style="display: none; align-items: center; gap: 8px; color: #0066cc; font-size: 0.9rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_ajYA{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25"/><path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" class="spinner_ajYA" fill="currentColor"/></svg>
            Analizando con Mistral...
        </div>
    </div>
    <div id="ai-recommendations" style="display: none; margin-top: 15px; padding: 16px; background: #ffffff; border-radius: 12px; box-shadow: var(--shadow-sm); font-size: 14px; letter-spacing: -0.224px; color: #1d1d1f;">
        <strong>Recomendaciones de la IA:</strong> <span id="ai-recommendations-text"></span>
    </div>
</div>

{{-- SECTION 1: INFORMACIÓN BÁSICA --}}
<div class="form-section">
    <h3 class="form-section-title">1. Información básica</h3>
    <hr>
    <div class="form-grid-2">
        <div class="form-group form-col-2">
            <label class="form-label">Título *</label>
            <input type="text" name="titulo" class="form-input" value="{{ old('titulo', $property?->titulo) }}" required maxlength="200" placeholder="Ej: Luminoso piso en el centro de Madrid">
            @error('titulo') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group form-col-2">
            <label class="form-label">Descripción * <small>(mín. 100 caracteres)</small></label>
            <textarea name="descripcion" class="form-textarea" rows="5" required minlength="100" placeholder="Describe la propiedad en detalle…">{{ old('descripcion', $property?->descripcion) }}</textarea>
            @error('descripcion') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Tipo de propiedad *</label>
            <select name="tipo_propiedad" class="form-select" required>
                @foreach(['piso','casa','chalet','local','garaje','oficina'] as $t)
                    <option value="{{ $t }}" {{ old('tipo_propiedad', $property?->tipo_propiedad) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Operación *</label>
            <div class="radio-group">
                <label class="radio-option">
                    <input type="radio" name="tipo_operacion" value="venta" {{ old('tipo_operacion', $property?->tipo_operacion ?? 'venta') === 'venta' ? 'checked' : '' }}> Venta
                </label>
                <label class="radio-option">
                    <input type="radio" name="tipo_operacion" value="alquiler" {{ old('tipo_operacion', $property?->tipo_operacion) === 'alquiler' ? 'checked' : '' }}> Alquiler
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Precio (€) *</label>
            <input type="number" name="precio" class="form-input" value="{{ old('precio', $property?->precio) }}" min="0" step="1000" required placeholder="285000">
            @error('precio') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <div class="form-checkboxes">
                <label class="toggle-option">
                    <input type="checkbox" name="destacada" value="1" {{ old('destacada', $property?->destacada) ? 'checked' : '' }}> Propiedad destacada
                </label>
                <label class="toggle-option">
                    <input type="checkbox" name="activa" value="1" {{ old('activa', $property?->activa ?? true) ? 'checked' : '' }}> Publicada (activa)
                </label>
            </div>
        </div>
    </div>
</div>

{{-- SECTION 2: CARACTERÍSTICAS --}}
<div class="form-section">
    <h3 class="form-section-title">2. Características</h3>
    <hr>
    <div class="form-grid-3">
        <div class="form-group">
            <label class="form-label">Superficie (m²) *</label>
            <input type="number" name="superficie_m2" class="form-input" value="{{ old('superficie_m2', $property?->superficie_m2) }}" min="0" step="0.5" required>
        </div>
        <div class="form-group">
            <label class="form-label">Habitaciones *</label>
            <input type="number" name="habitaciones" class="form-input" value="{{ old('habitaciones', $property?->habitaciones ?? 0) }}" min="0" max="20" required>
        </div>
        <div class="form-group">
            <label class="form-label">Baños *</label>
            <input type="number" name="banos" class="form-input" value="{{ old('banos', $property?->banos ?? 0) }}" min="0" max="10" required>
        </div>
        <div class="form-group">
            <label class="form-label">Planta</label>
            <input type="number" name="piso" class="form-input" value="{{ old('piso', $property?->piso) }}" min="0">
        </div>
        <div class="form-group">
            <label class="form-label">Certificado energético</label>
            <select name="certificado_energetico" class="form-select">
                <option value="">Seleccionar</option>
                @foreach(['A','B','C','D','E','F','G'] as $c)
                    <option value="{{ $c }}" {{ old('certificado_energetico', $property?->certificado_energetico) === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        {{-- Tour virtual ocultado para futura feature --}}
        {{-- 
        <div class="form-group">
            <label class="form-label">URL Tour virtual</label>
            <input type="url" name="url_tour_virtual" class="form-input" value="{{ old('url_tour_virtual', $property?->url_tour_virtual) }}" placeholder="https://...">
        </div>
        --}}
    </div>
    <div class="amenities-checkboxes">
        @foreach([
            ['tiene_ascensor','Ascensor'],['tiene_parking','Parking'],['tiene_terraza','Terraza'],
            ['tiene_jardin','Jardín'],['tiene_piscina','Piscina'],['aire_acondicionado','Aire acond.']
        ] as [$key,$label])
            <label class="checkbox-option">
                <input type="checkbox" name="{{ $key }}" value="1" {{ old($key, $property?->$key) ? 'checked' : '' }}>
                {{ $label }}
            </label>
        @endforeach
    </div>
</div>

{{-- SECTION 2b: CERTIFICADO ENERGÉTICO (PDF) --}}
<div class="form-section">
    <h3 class="form-section-title">2b. Certificado Energético (PDF)</h3>
    <hr>

    @if(isset($property) && $property->certificado_energetico_archivo)
        {{-- CERTIFICADO EXISTENTE --}}
        <div style="display: flex; align-items: center; gap: 16px; padding: 16px; background: #f5f5f7; border: none; border-radius: 12px; margin-bottom: 24px;">
            <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="#0066cc" stroke-width="2">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                <polyline points="14 2 14 8 20 8"/>
                <line x1="16" y1="13" x2="8" y2="13"/>
                <line x1="16" y1="17" x2="8" y2="17"/>
            </svg>
            <div style="flex: 1;">
                <div style="font-weight: 600; color: #1d1d1f; font-size: 14px;">Certificado adjunto</div>
                <a href="{{ Storage::disk('public')->url($property->certificado_energetico_archivo) }}"
                   target="_blank"
                   style="font-size: 13px; color: #0066cc; text-decoration: none;"
                   download>
                   Ver / Descargar PDF
                </a>
            </div>
            {{-- Toggle de eliminación --}}
            <label style="display: flex; align-items: center; gap: 8px; cursor: pointer; font-size: 13px; color: #c0392b; font-weight: 600;">
                <input type="checkbox" name="eliminar_certificado" value="1"
                       id="eliminar-cert-checkbox"
                       onchange="document.getElementById('cert-delete-warning').style.display = this.checked ? 'block' : 'none'">
                Eliminar certificado
            </label>
        </div>
        <div id="cert-delete-warning" style="display: none; padding: 12px 16px; background: #fff0f0; border: none; border-radius: 8px; font-size: 14px; font-family: var(--font-body); letter-spacing: -0.224px; color: #ff453a; margin-bottom: 16px;">
            <strong>Importante:</strong> El certificado será eliminado al guardar los cambios. Si subes un nuevo PDF, se usará éste en su lugar.
        </div>
    @endif

    {{-- ZONA DE SUBIDA / REEMPLAZO --}}
    <div style="display: flex; align-items: center; gap: 16px; flex-wrap: wrap;">
        <input type="file" name="certificado_energetico_archivo" id="admin-cert-input"
               accept="application/pdf" style="display: none;">
        <button type="button" class="btn-admin btn-admin-outline"
                onclick="document.getElementById('admin-cert-input').click()"
                id="admin-cert-btn"
                style="display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                <polyline points="17 8 12 3 7 8"/>
                <line x1="12" y1="3" x2="12" y2="15"/>
            </svg>
            {{ isset($property) && $property->certificado_energetico_archivo ? 'Reemplazar certificado (PDF)' : 'Subir certificado (PDF)' }}
        </button>
        <span id="admin-cert-filename" style="font-size: 13px; color: #86868b;">Ningún archivo seleccionado</span>
    </div>
    <p style="font-size: 12px; color: #86868b; margin-top: 8px;">Máx. 5 MB · Solo formato PDF · Visible y descargable en la ficha pública de la propiedad.</p>
</div>

{{-- SECTION 3: UBICACIÓN --}}
<div class="form-section">
    <h3 class="form-section-title">3. Ubicación</h3>
    <hr>
    <div class="form-grid-2">
        <div class="form-group form-col-2">
            <label class="form-label">Dirección *</label>
            <input type="text" name="direccion" class="form-input" value="{{ old('direccion', $property?->direccion) }}" required placeholder="Calle Gran Vía 45, Piso 3B">
        </div>
        <div class="form-group">
            <label class="form-label">Ciudad *</label>
            <input type="text" name="ciudad" class="form-input" value="{{ old('ciudad', $property?->ciudad) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Provincia *</label>
            <select name="provincia" class="form-select" required>
                <option value="">Seleccionar provincia</option>
                @foreach($provinces as $prov)
                    <option value="{{ $prov }}" {{ old('provincia', $property?->provincia) === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Código postal *</label>
            <input type="text" name="codigo_postal" class="form-input" value="{{ old('codigo_postal', $property?->codigo_postal) }}" required pattern="[0-9]{5}" maxlength="5" placeholder="28001">
        </div>
        <div class="form-group">
            <label class="form-label">Latitud <small>(opcional)</small></label>
            <input type="number" name="latitud" class="form-input" value="{{ old('latitud', $property?->latitud) }}" step="any" min="-90" max="90" placeholder="40.4168">
        </div>
        <div class="form-group">
            <label class="form-label">Longitud <small>(opcional)</small></label>
            <input type="number" name="longitud" class="form-input" value="{{ old('longitud', $property?->longitud) }}" step="any" min="-180" max="180" placeholder="-3.7038">
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('ai-image-input').addEventListener('change', async function(e) {
    if (!this.files || !this.files[0]) return;
    
    const file = this.files[0];
    const formData = new FormData();
    formData.append('image', file);
    
    const loadingEl = document.getElementById('ai-loading');
    const recEl = document.getElementById('ai-recommendations');
    const recTextEl = document.getElementById('ai-recommendations-text');
    
    loadingEl.style.display = 'flex';
    recEl.style.display = 'none';
    
    try {
        const response = await fetch('{{ route("admin.ai.analyzeImage") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        
        const data = await response.json();
        
        function extractText(obj) {
            if (typeof obj === 'string') return obj;
            if (Array.isArray(obj)) return obj.map(extractText).join('\n');
            if (typeof obj === 'object' && obj !== null) {
                return Object.values(obj).map(extractText).join('\n\n');
            }
            return String(obj);
        }
        
        if (response.ok) {
            if (data.title) {
                document.querySelector('input[name="title"]').value = extractText(data.title).replace(/\n/g, ' ');
            }
            if (data.description) {
                document.querySelector('textarea[name="description"]').value = extractText(data.description);
            }
            if (data.recommendations) {
                recTextEl.innerHTML = '';
                
                let recs = data.recommendations;
                if (typeof recs === 'string') {
                    recTextEl.innerHTML = '<p>' + recs + '</p>';
                } else if (Array.isArray(recs)) {
                    recs.forEach(rec => {
                        if (typeof rec === 'object' && rec.text) {
                            let priority = rec.priority || 'Nota';
                            recTextEl.innerHTML += `<div style="margin-bottom: 10px; padding: 10px; background: #fff; border-radius: 6px; border: 1px solid #e5e5ea;"><strong>[${priority}]</strong> ${rec.text}</div>`;
                        } else {
                            recTextEl.innerHTML += `<div style="margin-bottom: 10px;">${extractText(rec)}</div>`;
                        }
                    });
                }
                
                recEl.style.display = 'block';
            }
        } else {
            alert('Error al analizar la imagen: ' + (data.error || 'Error desconocido'));
        }
    } catch (error) {
        console.error(error);
        alert('Hubo un problema de conexión con el servidor IA.');
    } finally {
        loadingEl.style.display = 'none';
        this.value = '';
    }
});

// --- CERTIFICADO ENERGÉTICO (admin) ---
const adminCertInput = document.getElementById('admin-cert-input');
if (adminCertInput) {
    adminCertInput.addEventListener('change', function() {
        const label = document.getElementById('admin-cert-filename');
        const btn   = document.getElementById('admin-cert-btn');
        if (this.files.length > 0) {
            label.innerText = this.files[0].name;
            btn.style.borderColor = '#0066cc';
            btn.style.color = '#0066cc';
        } else {
            label.innerText = 'Ningún archivo seleccionado';
        }
    });
}
</script>
@endpush
