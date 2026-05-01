@php
    $provinces = ["Álava","Albacete","Alicante","Almería","Asturias","Ávila","Badajoz","Baleares","Barcelona","Burgos","Cáceres","Cádiz","Cantabria","Castellón","Ciudad Real","Córdoba","Cuenca","Gerona","Granada","Guadalajara","Guipúzcoa","Huelva","Huesca","Jaén","La Coruña","La Rioja","Las Palmas","León","Lérida","Lugo","Madrid","Málaga","Murcia","Navarra","Orense","Palencia","Pontevedra","Salamanca","Santa Cruz de Tenerife","Segovia","Sevilla","Soria","Tarragona","Teruel","Toledo","Valencia","Valladolid","Vizcaya","Zamora","Zaragoza"];
@endphp

{{-- SECTION AI AUTO-FILL --}}
<div class="form-section ai-section" style="background: linear-gradient(145deg, #f8f9fa, #e9ecef); border: 1px solid #dee2e6; border-radius: 12px; padding: 20px; margin-bottom: 30px;">
    <h3 class="form-section-title" style="display: flex; align-items: center; gap: 8px; color: #1d1d1f; font-size: 1.1rem; margin-top:0;">
        <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2"><path d="M12 2v4M12 18v4M4.93 4.93l2.83 2.83M16.24 16.24l2.83 2.83M2 12h4M18 12h4M4.93 19.07l2.83-2.83M16.24 7.76l2.83-2.83"/></svg>
        Asistente de IA Mistral
    </h3>
    <p style="font-size: 0.9rem; color: #86868b; margin-bottom: 15px;">Sube una foto de la propiedad y nuestra IA analizará la imagen para sugerirte un título, una descripción y recomendaciones de mejora.</p>
    
    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
        <input type="file" id="ai-image-input" accept="image/jpeg,image/png,image/webp" style="display: none;">
        <button type="button" class="btn btn-outline" onclick="document.getElementById('ai-image-input').click()" style="display: flex; align-items: center; gap: 8px;">
            <svg viewBox="0 0 24 24" width="16" height="16"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" stroke="currentColor" fill="none" stroke-width="1.5"/><polyline points="17 8 12 3 7 8" stroke="currentColor" fill="none" stroke-width="1.5"/><line x1="12" y1="3" x2="12" y2="15" stroke="currentColor" stroke-width="1.5"/></svg>
            Seleccionar foto para análisis
        </button>
        <div id="ai-loading" style="display: none; align-items: center; gap: 8px; color: #0066cc; font-size: 0.9rem;">
            <svg width="20" height="20" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><style>.spinner_ajYA{transform-origin:center;animation:spinner_AtaB .75s infinite linear}@keyframes spinner_AtaB{100%{transform:rotate(360deg)}}</style><path d="M12,1A11,11,0,1,0,23,12,11,11,0,0,0,12,1Zm0,19a8,8,0,1,1,8-8A8,8,0,0,1,12,20Z" opacity=".25"/><path d="M10.14,1.16a11,11,0,0,0-9,8.92A1.59,1.59,0,0,0,2.46,12,1.52,1.52,0,0,0,4.11,10.7a8,8,0,0,1,6.66-6.61A1.42,1.42,0,0,0,12,2.69h0A1.57,1.57,0,0,0,10.14,1.16Z" class="spinner_ajYA" fill="currentColor"/></svg>
            Analizando con Mistral...
        </div>
    </div>
    <div id="ai-recommendations" style="display: none; margin-top: 15px; padding: 12px; background: #fff; border-left: 4px solid #0066cc; border-radius: 4px; font-size: 0.9rem; color: #1d1d1f;">
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
            <input type="text" name="title" class="form-input" value="{{ old('title', $property?->title) }}" required maxlength="200" placeholder="Ej: Luminoso piso en el centro de Madrid">
            @error('title') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group form-col-2">
            <label class="form-label">Descripción * <small>(mín. 100 caracteres)</small></label>
            <textarea name="description" class="form-textarea" rows="5" required minlength="100" placeholder="Describe la propiedad en detalle…">{{ old('description', $property?->description) }}</textarea>
            @error('description') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <label class="form-label">Tipo de propiedad *</label>
            <select name="property_type" class="form-select" required>
                @foreach(['piso','casa','chalet','local','garaje','oficina'] as $t)
                    <option value="{{ $t }}" {{ old('property_type', $property?->property_type) === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Operación *</label>
            <div class="radio-group">
                <label class="radio-option">
                    <input type="radio" name="operation_type" value="venta" {{ old('operation_type', $property?->operation_type ?? 'venta') === 'venta' ? 'checked' : '' }}> Venta
                </label>
                <label class="radio-option">
                    <input type="radio" name="operation_type" value="alquiler" {{ old('operation_type', $property?->operation_type) === 'alquiler' ? 'checked' : '' }}> Alquiler
                </label>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">Precio (€) *</label>
            <input type="number" name="price" class="form-input" value="{{ old('price', $property?->price) }}" min="0" step="1000" required placeholder="285000">
            @error('price') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="form-group">
            <div class="form-checkboxes">
                <label class="toggle-option">
                    <input type="checkbox" name="is_featured" value="1" {{ old('is_featured', $property?->is_featured) ? 'checked' : '' }}> Propiedad destacada
                </label>
                <label class="toggle-option">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $property?->is_active ?? true) ? 'checked' : '' }}> Publicada (activa)
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
            <input type="number" name="surface_m2" class="form-input" value="{{ old('surface_m2', $property?->surface_m2) }}" min="0" step="0.5" required>
        </div>
        <div class="form-group">
            <label class="form-label">Habitaciones *</label>
            <input type="number" name="rooms" class="form-input" value="{{ old('rooms', $property?->rooms ?? 0) }}" min="0" max="20" required>
        </div>
        <div class="form-group">
            <label class="form-label">Baños *</label>
            <input type="number" name="bathrooms" class="form-input" value="{{ old('bathrooms', $property?->bathrooms ?? 0) }}" min="0" max="10" required>
        </div>
        <div class="form-group">
            <label class="form-label">Planta</label>
            <input type="number" name="floor" class="form-input" value="{{ old('floor', $property?->floor) }}" min="0">
        </div>
        <div class="form-group">
            <label class="form-label">Certificado energético</label>
            <select name="energy_certificate" class="form-select">
                <option value="">Seleccionar</option>
                @foreach(['A','B','C','D','E','F','G'] as $c)
                    <option value="{{ $c }}" {{ old('energy_certificate', $property?->energy_certificate) === $c ? 'selected' : '' }}>{{ $c }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">URL Tour virtual</label>
            <input type="url" name="virtual_tour_url" class="form-input" value="{{ old('virtual_tour_url', $property?->virtual_tour_url) }}" placeholder="https://...">
        </div>
    </div>
    <div class="amenities-checkboxes">
        @foreach([
            ['has_elevator','Ascensor'],['has_parking','Parking'],['has_terrace','Terraza'],
            ['has_garden','Jardín'],['has_pool','Piscina'],['air_conditioning','Aire acond.']
        ] as [$key,$label])
            <label class="checkbox-option">
                <input type="checkbox" name="{{ $key }}" value="1" {{ old($key, $property?->$key) ? 'checked' : '' }}>
                {{ $label }}
            </label>
        @endforeach
    </div>
</div>

{{-- SECTION 3: UBICACIÓN --}}
<div class="form-section">
    <h3 class="form-section-title">3. Ubicación</h3>
    <hr>
    <div class="form-grid-2">
        <div class="form-group form-col-2">
            <label class="form-label">Dirección *</label>
            <input type="text" name="address" class="form-input" value="{{ old('address', $property?->address) }}" required placeholder="Calle Gran Vía 45, Piso 3B">
        </div>
        <div class="form-group">
            <label class="form-label">Ciudad *</label>
            <input type="text" name="city" class="form-input" value="{{ old('city', $property?->city) }}" required>
        </div>
        <div class="form-group">
            <label class="form-label">Provincia *</label>
            <select name="province" class="form-select" required>
                <option value="">Seleccionar provincia</option>
                @foreach($provinces as $prov)
                    <option value="{{ $prov }}" {{ old('province', $property?->province) === $prov ? 'selected' : '' }}>{{ $prov }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Código postal *</label>
            <input type="text" name="postal_code" class="form-input" value="{{ old('postal_code', $property?->postal_code) }}" required pattern="[0-9]{5}" maxlength="5" placeholder="28001">
        </div>
        <div class="form-group">
            <label class="form-label">Latitud <small>(opcional)</small></label>
            <input type="number" name="latitude" class="form-input" value="{{ old('latitude', $property?->latitude) }}" step="any" min="-90" max="90" placeholder="40.4168">
        </div>
        <div class="form-group">
            <label class="form-label">Longitud <small>(opcional)</small></label>
            <input type="number" name="longitude" class="form-input" value="{{ old('longitude', $property?->longitude) }}" step="any" min="-180" max="180" placeholder="-3.7038">
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
</script>
@endpush
