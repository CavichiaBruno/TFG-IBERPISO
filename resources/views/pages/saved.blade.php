@extends('layouts.app')

@section('title', 'Mis Propiedades Guardadas')

@push('styles')
    @vite('resources/css/pages/saved.css')
@endpush

@section('content')
<div class="container section-pad">
    <div class="saved-page-header">
        <h1 class="section-title">Mis Guardados</h1>
        <p class="text-muted">Gestiona tus hogares favoritos</p>
    </div>

    @if($properties->count() > 0)
        <div class="saved-properties-list">
            @foreach($properties as $property)
                <x-property-card :property="$property">
                    <x-slot name="footer">
                        <form action="{{ route('favorites.remove', $property->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta propiedad de tus guardados?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-remove-favorite">
                                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                Quitar de Guardados
                            </button>
                        </form>
                    </x-slot>
                </x-property-card>
            @endforeach
        </div>
        <div class="pagination-wrapper" style="margin-top: 3rem">
            {{ $properties->links() }}
        </div>
    @else
        <div class="text-center" style="padding: 4rem 0">
            <div style="font-size: 4rem; opacity: 0.2; margin-bottom: 1.5rem">❤️</div>
            <h2>Aún no tienes propiedades guardadas</h2>
            <p class="text-secondary">Ve a la sección de Descubrir y desliza a la derecha para guardar tus favoritas.</p>
            <a href="{{ route('scroll') }}" class="btn btn-primary" style="margin-top: 1.5rem">Empezar a descubrir</a>
        </div>
    @endif
</div>
@endsection
