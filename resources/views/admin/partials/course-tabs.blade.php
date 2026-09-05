{{-- Course switcher: toggles between the physical workshop and the online course.
     Expects: $courseType (current, e.g. 'physical'|'online'), $courseRoute (route name to link to). --}}
@php
    $courseTypes = config('courses.types');
@endphp
<ul class="nav nav-tabs mb-3" style="border-bottom:2px solid #eee;">
    @foreach($courseTypes as $key => $meta)
    <li class="nav-item">
        <a class="nav-link {{ $courseType === $key ? 'active' : '' }}"
           href="{{ route($courseRoute, ['course' => $key]) }}"
           style="{{ $courseType === $key ? 'font-weight:700; color:#a02626; border-color:#eee #eee #fff;' : 'color:#888;' }}">
            <i class="fas {{ $meta['icon'] }} mr-1"></i> {{ $meta['label'] }}
        </a>
    </li>
    @endforeach
</ul>
