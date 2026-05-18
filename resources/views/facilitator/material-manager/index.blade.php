@extends('layouts.facilitator')
@section('page-title', 'Manage Materials')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-book-open mr-2" style="color:#C9A84C;"></i> Training Materials
    </h5>
    <a href="{{ route('facilitator.material-manager.create') }}"
       class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px; border-radius:5px;">
        <i class="fas fa-plus mr-1"></i> Add Material
    </a>
</div>

@php
    $grouped = $materials->groupBy('type');
    $typeLabels = ['presentation'=>'Presentations','document'=>'Documents','video'=>'Videos'];
    $typeIcons  = ['presentation'=>'fa-file-powerpoint','document'=>'fa-file-pdf','video'=>'fa-video'];
    $typeColors = ['presentation'=>'#C9A84C','document'=>'#e53e3e','video'=>'#0d6efd'];
@endphp

@foreach(['presentation','document','video'] as $type)
@if($grouped->has($type))
<div class="card shadow-sm mb-4" style="border-radius:10px; overflow:hidden;">
    <div class="card-header d-flex align-items-center" style="background:#f8f9fa; border-bottom:2px solid {{ $typeColors[$type] }}; padding:12px 20px;">
        <i class="fas {{ $typeIcons[$type] }} mr-2" style="color:{{ $typeColors[$type] }};"></i>
        <strong style="font-size:14px; color:#2d3748;">{{ $typeLabels[$type] }}</strong>
        <span class="ml-auto badge" style="background:{{ $typeColors[$type] }}22; color:{{ $typeColors[$type] }}; border:1px solid {{ $typeColors[$type] }}55; font-size:11px; padding:3px 10px;">
            {{ $grouped[$type]->count() }}
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#fafafa;">
                <tr>
                    <th class="th-sm">Title</th>
                    <th class="th-sm">Category</th>
                    <th class="th-sm">Facilitator</th>
                    <th class="th-sm">File</th>
                    <th class="th-sm" style="text-align:right; padding-right:16px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grouped[$type] as $mat)
                <tr>
                    <td style="vertical-align:middle; padding:10px 16px;">
                        <div style="font-weight:600; font-size:13.5px; color:#2d3748;">{{ $mat->title }}</div>
                        @if($mat->description)
                            <div style="font-size:11.5px; color:#888; margin-top:1px;">{{ Str::limit($mat->description, 60) }}</div>
                        @endif
                    </td>
                    <td style="vertical-align:middle; font-size:13px; color:#555;">
                        @if($mat->category)
                            <span style="background:#fef3cd; color:#856404; border-radius:4px; padding:2px 8px; font-size:11px; font-weight:600;">{{ $mat->category }}</span>
                        @else
                            <span style="color:#ccc;">—</span>
                        @endif
                    </td>
                    <td style="vertical-align:middle; font-size:13px; color:#555;">{{ $mat->facilitator?->name ?? '—' }}</td>
                    <td style="vertical-align:middle;">
                        @if($mat->external_url)
                            <a href="{{ route('material.view', $mat->id) }}" target="_blank"
                               style="font-size:12px; color:#C9A84C; text-decoration:none;">
                                <i class="fas fa-eye mr-1"></i>Preview
                            </a>
                        @else
                            <span style="font-size:12px; color:#ccc;">No file</span>
                        @endif
                    </td>
                    <td style="vertical-align:middle; text-align:right; padding-right:16px;">
                        <a href="{{ route('facilitator.material-manager.edit', $mat->id) }}"
                           class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px; margin-right:4px;">
                            <i class="fas fa-edit"></i>
                        </a>
                        @if(auth()->user()->roles->pluck('title')->contains('Lead Facilitator'))
                        <form action="{{ route('facilitator.material-manager.destroy', $mat->id) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Delete \'{{ addslashes($mat->title) }}\'?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:12px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endforeach
@endsection

@section('styles')
<style>.th-sm { font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px; padding:9px 16px; }</style>
@endsection
