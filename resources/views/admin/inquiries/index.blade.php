@extends('layouts.admin')
@section('title', 'Consultas')

@section('content')
<div class="page-header">
    <h1>Bandeja de Consultas</h1>
</div>

<div class="admin-toolbar">
    <div class="toolbar-filters">
        @foreach(['todas'=>'Todas','pendiente'=>'Pendientes','leida'=>'Leídas','respondida'=>'Respondidas'] as $val=>$label)
            <a href="{{ route('admin.inquiries.index', ['estado'=>$val]) }}"
               class="filter-tab {{ request('estado','todas')===$val?'active':'' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="admin-card">
    <div class="admin-table-container">
        <table class="admin-table">
            <thead>
                <tr>
                    <th>Propiedad</th>
                    <th>Remitente</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th style="text-align: right; padding-right: 24px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($inquiries as $inq)
                <tr style="{{ !$inq->leida ? 'background: rgba(0,113,227,0.03); font-weight: 500;' : '' }}" data-id="{{ $inq->id }}">
                    <td>
                        <div class="prop-info">
                            <span class="prop-title" style="font-size: 13px;">{{ $inq->propiedad ? Str::limit($inq->propiedad->titulo, 35) : '—' }}</span>
                            <span class="prop-meta">ID Prop: #{{ $inq->propiedad_id }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="prop-info">
                            <span class="prop-title">{{ $inq->nombre_visitante }}</span>
                            <span class="prop-meta">{{ $inq->correo_visitante }}</span>
                            @if($inq->telefono_visitante)<span class="prop-meta">{{ $inq->telefono_visitante }}</span>@endif
                        </div>
                    </td>
                    <td>
                        <div style="max-width: 300px; font-size: 13px; color: var(--admin-text-secondary); line-height: 1.4;">
                            {{ Str::limit($inq->mensaje, 100) }}
                        </div>
                    </td>
                    <td>
                        <span class="prop-meta">{{ $inq->created_at->format('d M, Y') }}</span>
                        <span class="prop-meta">{{ $inq->created_at->format('H:i') }}</span>
                    </td>
                    <td>
                        <select class="admin-input status-select" data-id="{{ $inq->id }}" style="padding: 4px 8px; font-size: 12px; width: auto; min-width: 120px;">
                            <option value="pendiente" {{ $inq->estado==='pendiente'?'selected':'' }}>Pendiente</option>
                            <option value="leida"    {{ $inq->estado==='leida'?'selected':'' }}>Leída</option>
                            <option value="respondida"{{ $inq->estado==='respondida'?'selected':'' }}>Respondida</option>
                        </select>
                    </td>
                    <td style="text-align: right; padding-right: 24px;">
                        <button class="icon-btn delete-inquiry-btn" data-id="{{ $inq->id }}" style="color: #ff453a;">
                            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"></path></svg>
                        </button>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" style="padding: 64px; text-align: center; color: var(--admin-text-secondary);">No hay consultas que mostrar</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 24px;">
    {{ $inquiries->links('components.pagination') }}
</div>
@endsection
