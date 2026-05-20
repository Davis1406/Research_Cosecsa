@extends('layouts.facilitator')
@section('page-title', 'Trainee Presentations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" style="flex-wrap:wrap;gap:10px;">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748; font-size:1.1rem;">
        <i class="fas fa-file-powerpoint mr-2" style="color:#C9A84C;"></i> Trainee Presentations
    </h5>
    <div class="d-flex align-items-center" style="gap:8px;">
        @if(!$isLead)
        <div class="d-flex" style="gap:6px;">
            <span style="font-size:0.8rem;color:#888;">View:</span>
            <button id="btn-all" onclick="filterPresentations('all')"
                class="btn btn-sm" style="font-size:0.8rem;font-weight:700;border-radius:20px;padding:4px 14px;background:#2d3748;color:#fff;border:1px solid #2d3748;">All</button>
            <button id="btn-mine" onclick="filterPresentations('mine')"
                class="btn btn-sm" style="font-size:0.8rem;font-weight:700;border-radius:20px;padding:4px 14px;background:#fff;color:#C9A84C;border:1px solid #C9A84C;">Assigned to Me</button>
        </div>
        @endif
        <span class="badge" style="background:#f8f9fa;color:#555;border:1px solid #dee2e6;font-size:0.8rem;padding:5px 12px;">
            {{ $trainees->count() }} trainee{{ $trainees->count()!==1?'s':'' }}
        </span>
    </div>
</div>

@if($trainees->isEmpty())
    <div class="card shadow-sm" style="border-radius:8px;">
        <div class="card-body text-center py-5">
            <i class="fas fa-file-powerpoint fa-3x mb-3" style="color:#ddd;"></i>
            <p style="color:#aaa;font-size:0.9rem;">No presentations uploaded yet.</p>
        </div>
    </div>
@else
    @foreach($trainees as $trainee)
    <div class="trainee-card card shadow-sm mb-4" style="border-radius:10px;overflow:hidden;border:1px solid #e9ecef;">
        <div class="card-header d-flex align-items-center" style="background:#f8f9fa;border-bottom:2px solid #C9A84C;padding:14px 20px;">
            <div style="width:36px;height:36px;border-radius:50%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;flex-shrink:0;margin-right:12px;">
                {{ strtoupper(substr($trainee->name, 0, 1)) }}
            </div>
            <div>
                <div style="font-weight:700;font-size:0.95rem;color:#2d3748;">{{ $trainee->name }}</div>
                <div style="font-size:0.8rem;color:#888;">
                    {{ $trainee->institution ?: 'No institution' }}
                    @if($trainee->specialty) &bull; {{ $trainee->specialty }} @endif
                </div>
            </div>
            <span class="ml-auto badge" style="background:#C9A84C22;color:#9a7d2c;border:1px solid #C9A84C55;font-size:0.75rem;padding:4px 10px;">
                {{ $trainee->documents->count() }} presentation{{ $trainee->documents->count()!==1?'s':'' }}
            </span>
        </div>
        <div class="card-body" style="padding:16px 20px;">
            <div class="row">
                @foreach($trainee->documents as $doc)
                @php
                    $commentCount  = $doc->comments->count();
                    $ext           = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
                    $docIcon       = $ext === 'pdf' ? 'fa-file-pdf' : (in_array($ext,['pptx','ppt']) ? 'fa-file-powerpoint' : 'fa-file');
                    $docColor      = $ext === 'pdf' ? '#e53e3e' : '#C9A84C';
                    $reviewerIds   = $doc->reviewers->pluck('id')->toArray();
                    $isAssignedToMe = in_array(auth()->id(), $reviewerIds);
                @endphp
                <div class="col-md-6 mb-3 doc-card"
                     data-assigned="{{ $isAssignedToMe ? '1' : '0' }}">
                    <div class="card h-100" style="border-radius:8px;border:{{ $isAssignedToMe ? '2px solid #C9A84C' : '1px solid #e9ecef' }};overflow:hidden;">
                        <div class="card-body" style="padding:14px;">
                            <div style="font-weight:700;font-size:0.9rem;color:#2d3748;margin-bottom:4px;">
                                <i class="fas {{ $docIcon }} mr-1" style="color:{{ $docColor }};"></i>
                                {{ $doc->title ?: $doc->original_name }}
                            </div>
                            <div style="font-size:0.75rem;color:#aaa;margin-bottom:10px;">
                                <span class="badge" style="background:{{ $docColor }}18;color:{{ $docColor }};border:1px solid {{ $docColor }}44;font-size:0.65rem;padding:1px 6px;text-transform:uppercase;margin-right:4px;">{{ strtoupper($ext) }}</span>
                                {{ $doc->created_at->format('M j, Y') }}
                            </div>

                            {{-- Reviewers --}}
                            @if($doc->reviewers->isNotEmpty())
                            <div style="margin-bottom:8px;display:flex;flex-wrap:wrap;gap:4px;align-items:center;">
                                <span style="font-size:0.7rem;color:#888;"><i class="fas fa-user-check mr-1" style="color:#2c7a4b;"></i>Reviewers:</span>
                                @foreach($doc->reviewers as $rv)
                                <span style="font-size:0.7rem;font-weight:600;background:rgba(44,122,75,0.1);color:#2c7a4b;border-radius:10px;padding:1px 8px;">
                                    {{ $rv->name }}
                                    @if($rv->id === auth()->id()) <em style="opacity:0.7;">(you)</em> @endif
                                </span>
                                @endforeach
                            </div>
                            @endif

                            {{-- Comment count --}}
                            <div class="d-flex align-items-center" style="gap:8px;margin-bottom:12px;">
                                @if($commentCount > 0)
                                <span class="badge" style="background:#d4edda;color:#155724;border:1px solid #c3e6cb;font-size:0.75rem;padding:3px 8px;">
                                    <i class="fas fa-comment-alt mr-1"></i>{{ $commentCount }} comment{{ $commentCount!==1?'s':'' }}
                                </span>
                                @else
                                <span class="badge" style="background:#fff3cd;color:#856404;border:1px solid #ffc10744;font-size:0.75rem;padding:3px 8px;">
                                    <i class="fas fa-comment-slash mr-1"></i>No feedback yet
                                </span>
                                @endif
                                @if($isAssignedToMe)
                                <span class="badge" style="background:rgba(201,168,76,0.2);color:#9a7d2c;border:1px solid #C9A84C55;font-size:0.7rem;padding:3px 8px;">
                                    <i class="fas fa-star mr-1"></i>Assigned to me
                                </span>
                                @endif
                            </div>

                            <a href="{{ route('facilitator.presentations.view', $doc->id) }}"
                               class="btn btn-sm btn-block" style="background:#C9A84C;color:#fff;font-weight:700;font-size:0.8rem;">
                                <i class="fas fa-eye mr-1"></i> Review & Comment
                            </a>

                            {{-- Assign Reviewers (Lead only) --}}
                            @if($isLead)
                            <div style="margin-top:10px;padding-top:10px;border-top:1px solid #f0f0f0;">
                                <form action="{{ route('facilitator.presentations.assign-reviewers', $doc->id) }}" method="POST">
                                    @csrf
                                    <label style="font-size:0.72rem;font-weight:700;color:#888;text-transform:uppercase;letter-spacing:0.4px;margin-bottom:4px;display:block;">
                                        <i class="fas fa-user-plus mr-1" style="color:#C9A84C;"></i> Assign Reviewers (max 2)
                                    </label>
                                    <select name="reviewer_ids[]" multiple
                                        style="width:100%;border:1px solid #dee2e6;border-radius:5px;padding:5px 8px;font-size:0.8rem;color:#2d3748;background:#fff;min-height:60px;">
                                        @foreach($facilitatorUsers as $fu)
                                        <option value="{{ $fu->id }}"
                                            {{ in_array($fu->id, $reviewerIds) ? 'selected' : '' }}>
                                            {{ $fu->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <small style="font-size:0.7rem;color:#aaa;display:block;margin-top:2px;">Hold Ctrl/Cmd to select multiple.</small>
                                    <button type="submit" class="btn btn-sm btn-block mt-2"
                                        style="background:#f8f9fa;color:#2c7a4b;border:1px solid #c3e6cb;font-size:0.8rem;font-weight:600;">
                                        <i class="fas fa-save mr-1"></i> Save Reviewers
                                    </button>
                                </form>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection

@section('scripts')
<script>
var myUserId = {{ auth()->id() }};

function filterPresentations(mode) {
    var btnAll  = document.getElementById('btn-all');
    var btnMine = document.getElementById('btn-mine');
    if (mode === 'mine') {
        btnMine.style.background = '#C9A84C'; btnMine.style.color = '#fff'; btnMine.style.borderColor = '#C9A84C';
        btnAll.style.background  = '#fff';    btnAll.style.color  = '#2d3748'; btnAll.style.borderColor = '#dee2e6';
    } else {
        btnAll.style.background  = '#2d3748'; btnAll.style.color  = '#fff'; btnAll.style.borderColor = '#2d3748';
        btnMine.style.background = '#fff';    btnMine.style.color = '#C9A84C'; btnMine.style.borderColor = '#C9A84C';
    }

    document.querySelectorAll('.trainee-card').forEach(function(card) {
        var docs = card.querySelectorAll('.doc-card');
        var visible = 0;
        docs.forEach(function(d) {
            var show = mode === 'all' || d.dataset.assigned === '1';
            d.style.display = show ? '' : 'none';
            if (show) visible++;
        });
        card.style.display = visible === 0 ? 'none' : '';
    });
}
</script>
@endsection
