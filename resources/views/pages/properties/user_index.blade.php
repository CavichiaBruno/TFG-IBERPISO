@extends('layouts.app')
@section('title', 'Mis Publicaciones')

@push('styles')
<style>
    .user-properties-page {
        background-color: #f5f5f7;
        min-height: 100vh;
        padding: 80px 0;
    }
    .user-header {
        max-width: 1100px;
        margin: 0 auto 40px;
        padding: 0 20px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }
    .user-title {
        font-family: 'SF Pro Display', sans-serif;
        font-size: 40px;
        font-weight: 600;
        letter-spacing: -0.02em;
        color: #1d1d1f;
        margin: 0;
    }
    .user-subtitle {
        font-size: 17px;
        color: #86868b;
        margin-top: 8px;
    }
    .properties-grid {
        max-width: 1100px;
        margin: 0 auto;
        padding: 0 20px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
        gap: 30px;
    }
    .property-admin-card {
        background: #fff;
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        transition: transform 0.3s ease;
        display: flex;
        flex-direction: column;
    }
    .property-admin-card:hover {
        transform: translateY(-5px);
    }
    .card-img-wrapper {
        position: relative;
        height: 200px;
    }
    .card-img-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .status-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 6px 12px;
        border-radius: 980px;
        font-size: 12px;
        font-weight: 600;
        backdrop-filter: blur(10px);
    }
    .status-active { background: rgba(52, 199, 89, 0.9); color: #fff; }
    .status-inactive { background: rgba(142, 142, 147, 0.9); color: #fff; }
    
    .card-content {
        padding: 20px;
        flex-grow: 1;
    }
    .card-price {
        font-size: 20px;
        font-weight: 600;
        color: #1d1d1f;
        margin-bottom: 5px;
    }
    .card-title {
        font-size: 17px;
        color: #1d1d1f;
        margin-bottom: 15px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        height: 44px;
    }
    .card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        padding: 20px;
        border-top: 1px solid #f5f5f7;
    }
    .btn-action {
        padding: 10px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        text-decoration: none;
    }
    .btn-edit { background: #f5f5f7; color: #1d1d1f; }
    .btn-toggle { background: #0071e3; color: #fff; }
    .btn-delete { background: #fff; color: #ff3b30; border: 1px solid #ff3b30; grid-column: span 2; }
    
    .empty-state {
        text-align: center;
        padding: 100px 20px;
    }
    .empty-icon { font-size: 64px; margin-bottom: 20px; }

    @media (max-width: 768px) {
        .user-properties-page { padding: 48px 0; }
        .user-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
            margin-bottom: 28px;
        }
        .user-title { font-size: 28px; }
        .properties-grid {
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }
    }

    @media (max-width: 480px) {
        .user-properties-page { padding: 32px 0; }
        .user-header { padding: 0 16px; margin-bottom: 20px; }
        .user-title { font-size: 24px; }
        .user-subtitle { font-size: 15px; }
        .properties-grid { padding: 0 16px; grid-template-columns: 1fr; gap: 16px; }
        .card-actions { grid-template-columns: 1fr; }
        .btn-delete { grid-column: span 1; }
        .empty-state { padding: 60px 16px; }
        .empty-icon { font-size: 48px; }
    }
</style>
@endpush

@section('content')
<div class="user-properties-page">
    <div class="user-header">
        <div>
            <h1 class="user-title">Mis <span class="marker-white">Publicaciones</span></h1>
            <p class="user-subtitle">Gestiona tus anuncios activos e inactivos</p>
        </div>
        <a href="{{ route('user.properties.create') }}" class="btn-primary" style="text-decoration: none;">Nueva Publicación</a>
    </div>

    @if($properties->count() > 0)
        <div class="properties-grid">
            @foreach($properties as $property)
                <div class="property-admin-card">
                    <div class="card-img-wrapper">
                        @php $cover = $property->medios->first(); @endphp
                        <img src="{{ $cover ? $cover->url : asset('images/placeholder.jpg') }}" alt="{{ $property->titulo }}">
                        <span class="status-badge {{ $property->activa ? 'status-active' : 'status-inactive' }}">
                            {{ $property->activa ? 'ACTIVO' : 'INACTIVO' }}
                        </span>
                    </div>
                    <div class="card-content">
                        <div class="card-price">€{{ number_format((float) $property->precio, 0, ',', '.') }}</div>
                        <div class="card-title">{{ $property->titulo }}</div>
                        <div style="font-size: 13px; color: #86868b;">
                            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align: middle; margin-right: 4px;"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $property->ciudad }}, {{ $property->provincia }}
                        </div>
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('properties.show', [$property->id, $property->slug]) }}" class="btn-action btn-edit">
                            Ver Anuncio
                        </a>
                        <form action="{{ route('user.properties.toggle', $property->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn-action btn-toggle" style="width: 100%;">
                                {{ $property->activa ? 'Desactivar' : 'Activar' }}
                            </button>
                        </form>
                        <form action="{{ route('user.properties.destroy', $property->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar este anuncio? Esta acción no se puede deshacer.')" style="grid-column: span 2;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-action btn-delete" style="width: 100%;">
                                Eliminar Anuncio
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div style="margin-top: 40px;">
            {{ $properties->links('components.pagination') }}
        </div>
    @else
        <div class="empty-state">
            <div class="empty-icon">🏠</div>
            <h2 class="user-title" style="font-size: 24px;">No tienes publicaciones aún</h2>
            <p class="user-subtitle">¡Crea tu primer anuncio y empieza a vender o alquilar!</p>
            <a href="{{ route('user.properties.create') }}" class="btn-primary" style="text-decoration: none; margin-top: 20px; display: inline-block;">Publicar ahora</a>
        </div>
    @endif
</div>
@endsection
