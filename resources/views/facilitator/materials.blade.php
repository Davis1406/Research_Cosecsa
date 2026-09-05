@extends('layouts.facilitator')

@section('page-title', 'Materials')

@section('content')
@include('facilitator.partials.course-tabs', ['courseRoute' => 'facilitator.materials'])

<div class="d-flex justify-content-between align-items-center mb-4" style="flex-wrap:wrap; gap:10px;">
    <h5 class="mb-0" style="font-weight:700; color:#252525; font-size:1.1rem;">
        <i class="fas fa-book mr-2" style="color:#C9A84C;"></i> {{ config("courses.types.$courseType.label") }} &mdash; Training Materials
    </h5>
    <div class="d-flex align-items-center" style="gap:10px;">
        <div class="d-flex align-items-center" style="gap:6px; flex-shrink:0;">
            <span style="font-size:0.8rem; color:#888; margin-right:2px;">View:</span>
            <button id="btn-all" onclick="filterMaterials('all')"
                class="btn btn-sm"
                style="font-size:0.8rem; font-weight:700; border-radius:20px; padding:4px 14px; background:#2d3748; color:#fff; border:1px solid #2d3748;">
                All
            </button>
            <button id="btn-mine" onclick="filterMaterials('mine')"
                class="btn btn-sm"
                style="font-size:0.8rem; font-weight:700; border-radius:20px; padding:4px 14px; background:#fff; color:#C9A84C; border:1px solid #C9A84C;">
                Mine
            </button>
        </div>
        <span id="mat-count" class="badge" style="background:#252525; color:#C9A84C; font-size:0.8rem; padding:6px 12px;">
            {{ $materials->count() }} materials
        </span>
    </div>
</div>

@if($materials->isEmpty())
    <div class="alert alert-info">No materials have been added yet.</div>
@else
    @php $grouped = $materials->groupBy('category'); @endphp
    @foreach($grouped as $category => $items)
        <div class="category-section card shadow-sm mb-4" style="border-radius:8px; overflow:hidden;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; font-size:0.9rem; padding:10px 20px; letter-spacing:0.5px;">
                <i class="fas fa-folder mr-2"></i> {{ $category ?: 'General' }}
                <span class="cat-count float-right" style="color:rgba(255,255,255,0.5); font-weight:400;">{{ $items->count() }} item{{ $items->count() !== 1 ? 's' : '' }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th style="font-size:0.8rem; font-weight:700; color:#555; border-top:none; padding:10px 16px;">Title</th>
                            <th style="font-size:0.8rem; font-weight:700; color:#555; border-top:none;">Type</th>
                            <th style="font-size:0.8rem; font-weight:700; color:#555; border-top:none;">Facilitator</th>
                            <th style="font-size:0.8rem; font-weight:700; color:#555; border-top:none; text-align:right; padding-right:16px;">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $material)
                        <tr class="material-row" data-speaker="{{ $material->speaker_id ?? '' }}">
                            <td style="padding:10px 16px; vertical-align:middle;">
                                <div style="font-weight:600; font-size:0.9rem; color:#252525;">{{ $material->title }}</div>
                                @if($material->description)
                                    <div style="font-size:0.8rem; color:#888; margin-top:2px;">{{ Str::limit($material->description, 80) }}</div>
                                @endif
                            </td>
                            <td style="vertical-align:middle;">
                                @php
                                    $typeColors = [
                                        'pdf'          => ['bg'=>'#fde8e8','color'=>'#a02626'],
                                        'presentation' => ['bg'=>'#fff3cd','color'=>'#856404'],
                                        'video'        => ['bg'=>'#e8f4fd','color'=>'#0d6efd'],
                                        'document'     => ['bg'=>'#e8fdf0','color'=>'#155724'],
                                        'link'         => ['bg'=>'#f0e8fd','color'=>'#5a0a8e'],
                                    ];
                                    $tc = $typeColors[strtolower($material->type ?? '')] ?? ['bg'=>'#f0f0f0','color'=>'#333'];
                                @endphp
                                <span class="badge" style="background:{{ $tc['bg'] }}; color:{{ $tc['color'] }}; border:1px solid {{ $tc['color'] }}33; font-size:0.75rem; padding:3px 8px; text-transform:capitalize;">
                                    {{ $material->type ?? 'File' }}
                                </span>
                            </td>
                            <td style="vertical-align:middle; font-size:0.875rem; color:#555;">
                                {{ $material->facilitator?->name ?? '—' }}
                            </td>
                            <td style="vertical-align:middle; text-align:right; padding-right:16px;">
                                <a href="{{ route('material.view', $material->id) }}"
                                   target="_blank"
                                   class="btn btn-sm"
                                   style="background:#252525; color:#C9A84C; font-size:0.8rem; border:1px solid #C9A84C; padding:4px 12px; border-radius:4px;">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endforeach
@endif
@endsection

@section('scripts')
<script>
var mySpeakerId = '{{ $mySpeakerId ?? '' }}';

function filterMaterials(mode) {
    var btnAll  = document.getElementById('btn-all');
    var btnMine = document.getElementById('btn-mine');

    if (mode === 'mine') {
        btnMine.style.background = '#C9A84C'; btnMine.style.color = '#fff'; btnMine.style.borderColor = '#C9A84C';
        btnAll.style.background  = '#fff';    btnAll.style.color  = '#2d3748'; btnAll.style.borderColor = '#dee2e6';
    } else {
        btnAll.style.background  = '#2d3748'; btnAll.style.color  = '#fff'; btnAll.style.borderColor = '#2d3748';
        btnMine.style.background = '#fff';    btnMine.style.color = '#C9A84C'; btnMine.style.borderColor = '#C9A84C';
    }

    var totalVisible = 0;

    document.querySelectorAll('.category-section').forEach(function(section) {
        var rows = section.querySelectorAll('.material-row');
        var visibleCount = 0;

        rows.forEach(function(row) {
            var speakerId = row.dataset.speaker;
            var show = (mode === 'all') || (speakerId && speakerId === mySpeakerId.toString());
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Hide the whole category section if empty
        section.style.display = (visibleCount === 0) ? 'none' : '';

        // Update per-category item count
        var catCount = section.querySelector('.cat-count');
        if (catCount) {
            catCount.textContent = visibleCount + ' item' + (visibleCount !== 1 ? 's' : '');
        }

        totalVisible += visibleCount;
    });

    // Update total badge
    var badge = document.getElementById('mat-count');
    if (badge) badge.textContent = totalVisible + ' material' + (totalVisible !== 1 ? 's' : '');
}
</script>
@endsection
