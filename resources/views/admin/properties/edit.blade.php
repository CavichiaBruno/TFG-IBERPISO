@extends('layouts.admin')
@section('title', 'Editar: ' . $property->title)
@section('page-title', 'Editar Propiedad')

@section('topbar-actions')
    <a href="{{ route('properties.show', [$property->id, $property->slug]) }}" target="_blank" class="btn btn-outline">Ver
        en portal</a>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.properties.update', $property->id) }}" id="property-form" novalidate>
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
                    @foreach($property->media->where('file_type', 'image') as $img)
                        <div class="media-item" data-id="{{ $img->id }}">
                            <img src="{{ $img->url }}" alt="Imagen" loading="lazy">
                            @if($img->is_cover)<span class="cover-badge">Portada</span>@endif
                            <div class="media-actions">
                                <button type="button" class="set-cover-btn {{ $img->is_cover ? 'cover-active' : '' }}"
                                    data-id="{{ $img->id }}" title="Establecer como portada">★</button>
                                <button type="button" class="delete-media-btn" data-id="{{ $img->id }}"
                                    title="Eliminar">✕</button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- PDF UPLOADER --}}
            <div class="media-section">
                <h4>Documentos (PDF)</h4>
                <div class="upload-zone upload-zone-sm" id="pdf-upload-zone" data-type="pdf"
                    data-property="{{ $property->id }}">
                    <svg viewBox="0 0 24 24" width="32" height="32">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor"
                            fill="none" stroke-width="1.5" />
                        <polyline points="14 2 14 8 20 8" stroke="currentColor" fill="none" stroke-width="1.5" />
                    </svg>
                    <p><label for="pdf-input" class="upload-link">Subir PDF</label></p>
                    <small>PDF, DOCX — máx. 10 MB</small>
                    <input type="file" id="pdf-input" accept=".pdf,.docx,application/pdf" class="upload-input"
                        style="display:none">
                </div>
                <div id="docs-list">
                    @foreach($property->media->where('file_type', 'pdf') as $doc)
                        <div class="doc-item-admin" data-id="{{ $doc->id }}">
                            <svg viewBox="0 0 24 24" width="18" height="18">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" stroke="currentColor"
                                    fill="none" stroke-width="2" />
                            </svg>
                            <span>{{ $doc->original_name }}</span>
                            <small>({{ $doc->file_size_kb }} KB)</small>
                            <button type="button" class="delete-media-btn" data-id="{{ $doc->id }}">✕</button>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.properties.index') }}" class="btn btn-outline">Cancelar</a>
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
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