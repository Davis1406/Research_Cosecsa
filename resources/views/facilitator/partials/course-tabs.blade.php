{{-- Course switcher: toggles between the physical workshop and the online course.
     Expects: $courseType (current, e.g. 'physical'|'online'), $courseRoute (route name to link to). --}}
@php
    $courseTypes = config('courses.types');
@endphp
<div class="d-flex mb-3" style="gap:8px; border-bottom:2px solid #eee;">
    @foreach($courseTypes as $key => $meta)
    <a href="{{ route($courseRoute, ['course' => $key]) }}"
       class="d-inline-flex align-items-center"
       style="padding:8px 16px; font-size:13px; font-weight:700; text-decoration:none; border-bottom:2px solid {{ $courseType === $key ? '#C9A84C' : 'transparent' }}; margin-bottom:-2px; color:{{ $courseType === $key ? '#2d3748' : '#999' }};">
        <i class="fas {{ $meta['icon'] }} mr-1" style="{{ $courseType === $key ? 'color:#C9A84C;' : '' }}"></i> {{ $meta['label'] }}
    </a>
    @endforeach
</div>
