@extends('layouts.admin')
@section('title', 'Nueva Propiedad')
@section('page-title', 'Nueva Propiedad')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/pages/admin.css') }}">
@endpush

@section('content')
<form method="POST" action="{{ route('admin.properties.store') }}" id="property-form" novalidate>
    @csrf
    @include('admin.properties._form', ['property' => null])

    <div class="form-actions">
        <button type="submit" name="is_active" value="0" class="btn btn-outline">Guardar borrador</button>
        <button type="submit" name="is_active" value="1" class="btn btn-primary">Publicar</button>
    </div>
</form>
@endsection
