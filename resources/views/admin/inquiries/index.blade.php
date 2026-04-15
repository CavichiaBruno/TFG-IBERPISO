@extends('layouts.admin')
@section('title', 'Consultas')
@section('page-title', 'Gestión de Consultas')

@section('content')
<div class="admin-toolbar">
    <div class="toolbar-filters">
        @foreach(['todas'=>'Todas','pending'=>'Pendientes','read'=>'Leídas','answered'=>'Respondidas'] as $val=>$label)
            <a href="{{ route('admin.inquiries.index', ['estado'=>$val]) }}"
               class="filter-tab {{ request('estado','todas')===$val?'active':'' }}">{{ $label }}</a>
        @endforeach
    </div>
</div>

<div class="table-wrapper">
    <table class="admin-table inquiries-table">
        <thead>
            <tr>
                <th>Propiedad</th>
                <th>Remitente</th>
                <th>Teléfono</th>
                <th>Mensaje</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($inquiries as $inq)
            <tr class="{{ !$inq->is_read ? 'row-unread' : '' }}" data-id="{{ $inq->id }}">
                <td>{{ $inq->property ? Str::limit($inq->property->title, 30) : '—' }}</td>
                <td>
                    <strong>{{ $inq->sender_name }}</strong><br>
                    <small class="text-muted">{{ $inq->sender_email }}</small>
                </td>
                <td>{{ $inq->guest_phone ?? ($inq->user?->phone ?? '—') }}</td>
                <td>
                    <span class="message-preview" title="{{ $inq->message }}">{{ Str::limit($inq->message, 50) }}</span>
                </td>
                <td>{{ $inq->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <select class="status-select" data-id="{{ $inq->id }}">
                        <option value="pending" {{ $inq->status==='pending'?'selected':'' }}>Pendiente</option>
                        <option value="read"    {{ $inq->status==='read'?'selected':'' }}>Leída</option>
                        <option value="answered"{{ $inq->status==='answered'?'selected':'' }}>Respondida</option>
                    </select>
                </td>
                <td>
                    <button class="action-btn action-delete delete-inquiry-btn" data-id="{{ $inq->id }}" title="Eliminar">
                        <svg viewBox="0 0 24 24" width="15" height="15"><polyline points="3 6 5 6 21 6" stroke="currentColor" fill="none" stroke-width="2"/><path d="M19 6v14H5V6m3 0V4h8v2" stroke="currentColor" fill="none" stroke-width="2"/></svg>
                    </button>
                </td>
            </tr>
        @empty
            <tr><td colspan="7" class="empty-row">No hay consultas</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

{{ $inquiries->links('components.pagination') }}
@endsection
