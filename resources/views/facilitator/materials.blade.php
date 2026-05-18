@extends('layouts.facilitator')

@section('page-title', 'Materials')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#252525;">
        <i class="fas fa-book mr-2" style="color:#C9A84C;"></i>
        {{ $isLead ? 'All Training Materials' : 'My Materials' }}
    </h5>
    <span class="badge" style="background:#252525; color:#C9A84C; font-size:12px; padding:6px 12px;">
        {{ $materials->count() }} materials
    </span>
</div>

@if(!$isLead)
    <div class="alert" style="background:#fff3cd; border:1px solid #ffc107; color:#856404; font-size:13px; border-radius:6px;">
        <i class="fas fa-info-circle mr-2"></i> Showing materials assigned to you. Contact the lead facilitator to add or modify materials.
    </div>
@endif

@if($materials->isEmpty())
    <div class="alert alert-info">
        @if($isLead) No materials have been added yet. @else You have no materials assigned. @endif
    </div>
@else
    @php $grouped = $materials->groupBy('category'); @endphp
    @foreach($grouped as $category => $items)
        <div class="card shadow-sm mb-4" style="border-radius:8px; overflow:hidden;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; font-size:13px; padding:10px 20px; letter-spacing:0.5px;">
                <i class="fas fa-folder mr-2"></i> {{ $category ?: 'General' }}
                <span class="float-right" style="color:rgba(255,255,255,0.5); font-weight:400;">{{ $items->count() }} item{{ $items->count() !== 1 ? 's' : '' }}</span>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background:#f8f9fa;">
                        <tr>
                            <th style="font-size:12px; font-weight:700; color:#555; border-top:none; padding:10px 16px;">Title</th>
                            <th style="font-size:12px; font-weight:700; color:#555; border-top:none;">Type</th>
                            @if($isLead)
                            <th style="font-size:12px; font-weight:700; color:#555; border-top:none;">Facilitator</th>
                            @endif
                            <th style="font-size:12px; font-weight:700; color:#555; border-top:none; text-align:right; padding-right:16px;">View</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($items as $material)
                        <tr>
                            <td style="padding:10px 16px; vertical-align:middle;">
                                <div style="font-weight:600; font-size:13.5px; color:#252525;">{{ $material->title }}</div>
                                @if($material->description)
                                    <div style="font-size:11.5px; color:#888; margin-top:2px;">{{ Str::limit($material->description, 80) }}</div>
                                @endif
                            </td>
                            <td style="vertical-align:middle;">
                                @php
                                    $typeColors = [
                                        'PDF'          => ['bg'=>'#fde8e8','color'=>'#a02626'],
                                        'Presentation' => ['bg'=>'#fff3cd','color'=>'#856404'],
                                        'Video'        => ['bg'=>'#e8f4fd','color'=>'#0d6efd'],
                                        'Document'     => ['bg'=>'#e8fdf0','color'=>'#155724'],
                                        'Link'         => ['bg'=>'#f0e8fd','color'=>'#5a0a8e'],
                                    ];
                                    $tc = $typeColors[$material->type] ?? ['bg'=>'#f0f0f0','color'=>'#333'];
                                @endphp
                                <span class="badge" style="background:{{ $tc['bg'] }}; color:{{ $tc['color'] }}; border:1px solid {{ $tc['color'] }}33; font-size:11px; padding:3px 8px;">
                                    {{ $material->type ?? 'File' }}
                                </span>
                            </td>
                            @if($isLead)
                            <td style="vertical-align:middle; font-size:13px; color:#555;">
                                {{ $material->facilitator ? $material->facilitator->name : '—' }}
                            </td>
                            @endif
                            <td style="vertical-align:middle; text-align:right; padding-right:16px;">
                                <a href="{{ route('material.view', $material->id) }}"
                                   target="_blank"
                                   class="btn btn-sm"
                                   style="background:#252525; color:#C9A84C; font-size:12px; border:1px solid #C9A84C; padding:4px 12px; border-radius:4px;">
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
