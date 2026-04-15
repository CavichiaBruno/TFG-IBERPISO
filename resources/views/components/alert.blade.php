@props(['type' => 'success', 'message' => ''])
<div class="alert alert-{{ $type }}" role="alert">
    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true">
        @if($type === 'success')
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" stroke="currentColor" fill="none" stroke-width="2"/>
            <polyline points="22 4 12 14.01 9 11.01" stroke="currentColor" fill="none" stroke-width="2"/>
        @else
            <circle cx="12" cy="12" r="10" stroke="currentColor" fill="none" stroke-width="2"/>
            <line x1="12" y1="8" x2="12" y2="12" stroke="currentColor" stroke-width="2"/>
            <line x1="12" y1="16" x2="12.01" y2="16" stroke="currentColor" stroke-width="2"/>
        @endif
    </svg>
    <span>{{ $message }}</span>
</div>
