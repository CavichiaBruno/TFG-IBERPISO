@php
    $provinces = ["Álava","Albacete","Alicante","Almería","Asturias","Ávila","Badajoz","Baleares","Barcelona","Burgos","Cáceres","Cádiz","Cantabria","Castellón","Ciudad Real","Córdoba","Cuenca","Gerona","Granada","Guadalajara","Guipúzcoa","Huelva","Huesca","Jaén","La Coruña","La Rioja","Las Palmas","León","Lérida","Lugo","Madrid","Málaga","Murcia","Navarra","Orense","Palencia","Pontevedra","Salamanca","Santa Cruz de Tenerife","Segovia","Sevilla","Soria","Tarragona","Teruel","Toledo","Valencia","Valladolid","Vizcaya","Zamora","Zaragoza"];
@endphp

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
