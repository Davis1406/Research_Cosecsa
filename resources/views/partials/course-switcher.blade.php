{{-- Global course switcher — a persistent, session/user-remembered toggle between
     the Physical Workshop and the Online Course. Any page under the current
     portal picks up the change automatically (no more per-page tabs).
     Expects: $accent (hex color for the active state, defaults to COSECSA burgundy). --}}
@php
    $courseTypes  = config('courses.types');
    $activeType   = course_type();
    $switcherId   = 'course-switcher-' . uniqid();
    $accentColor  = $accent ?? 'var(--cosecsa-burgundy, #a02626)';
@endphp
<div style="position:relative;" id="{{ $switcherId }}-wrapper">
    <button onclick="document.getElementById('{{ $switcherId }}-panel').classList.toggle('cs-open'); event.stopPropagation();"
            style="display:flex;align-items:center;gap:6px;background:#f4f4f4;border:1px solid #e0e0e0;border-radius:20px;padding:5px 12px;cursor:pointer;font-size:0.78rem;font-weight:700;color:#444;">
        <i class="fas {{ $courseTypes[$activeType]['icon'] ?? 'fa-graduation-cap' }}" style="color:{{ $accentColor }};"></i>
        {{ $courseTypes[$activeType]['short'] ?? ucfirst($activeType) }}
        <i class="fas fa-chevron-down" style="font-size:9px;color:#999;"></i>
    </button>
    <div id="{{ $switcherId }}-panel" class="cs-panel"
         style="display:none;position:absolute;right:0;top:38px;width:230px;background:#fff;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,0.14);z-index:9999;overflow:hidden;border:1px solid #e9ecef;">
        <div style="padding:8px 14px;border-bottom:1px solid #f0f0f0;background:#f8f9fa;font-size:0.72rem;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:0.4px;">
            Switch course
        </div>
        @foreach($courseTypes as $key => $meta)
        <a href="{{ route('course.switch', $key) }}"
           style="display:flex;align-items:center;gap:10px;padding:10px 14px;text-decoration:none;color:{{ $activeType === $key ? '#222' : '#666' }};{{ $activeType === $key ? 'background:#faf7ef;font-weight:700;' : '' }}">
            <i class="fas {{ $meta['icon'] }}" style="width:16px;color:{{ $accentColor }};"></i>
            <span>
                <span style="display:block;font-size:0.82rem;">{{ $meta['label'] }}</span>
                <span style="display:block;font-size:0.68rem;color:#aaa;">{{ $meta['subtitle'] }}</span>
            </span>
            @if($activeType === $key)
            <i class="fas fa-check" style="margin-left:auto;color:{{ $accentColor }};font-size:11px;"></i>
            @endif
        </a>
        @endforeach
    </div>
</div>
<style>
    .cs-panel.cs-open { display: block !important; }
</style>
<script>
    document.addEventListener('click', function (e) {
        document.querySelectorAll('.cs-panel.cs-open').forEach(function (panel) {
            if (!panel.parentElement.contains(e.target)) {
                panel.classList.remove('cs-open');
            }
        });
    });
</script>
