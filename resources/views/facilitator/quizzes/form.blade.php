@extends('layouts.facilitator')

@section('page-title', isset($quiz) ? 'Edit Quiz' : 'Create Quiz')

@section('styles')
<style>
.question-block {
    background:#fafcff;
    border:1px solid #e9ecef;
    border-left:3px solid #C9A84C;
    border-radius:8px;
    padding:16px;
    margin-bottom:12px;
}
.option-row {
    display:flex;
    align-items:center;
    gap:8px;
    margin-bottom:8px;
}
.option-row input[type=text] {
    flex:1;
}
.type-mc .options-area { display:block; }
.type-tf .options-area { display:block; }
.type-sa .options-area { display:none; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-question-circle mr-2" style="color:#C9A84C;"></i>
        {{ isset($quiz) ? 'Edit Quiz' : 'Create Quiz' }}
    </h5>
    <a href="{{ route('facilitator.quizzes.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

<form action="{{ isset($quiz) ? route('facilitator.quizzes.update', $quiz) : route('facilitator.quizzes.store') }}" method="POST" id="quizForm">
    @csrf
    @if(isset($quiz)) @method('PUT') @endif

    <div class="card shadow-sm mb-3" style="border-radius:8px;">
        <div class="card-header" style="background:#fff; border-bottom:2px solid #C9A84C; font-weight:700; font-size:13px; color:#2d3748;">
            Quiz Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Quiz Topic *</label>
                    <select name="title" class="form-control" required onchange="syncQuizSession(this)">
                        <option value="">— Choose a session topic —</option>
                        @foreach($sessions->groupBy('day_number') as $day => $daySessions)
                        <optgroup label="Day {{ $day }}">
                            @foreach($daySessions as $s)
                            <option value="{{ $s->title }}"
                                    data-id="{{ $s->id }}"
                                    {{ old('title', $quiz->title ?? '') === $s->title ? 'selected' : '' }}>
                                {{ $s->title }}
                            </option>
                            @endforeach
                        </optgroup>
                        @endforeach
                        <option value="General Quiz" data-id=""
                            {{ old('title', $quiz->title ?? '') === 'General Quiz' ? 'selected' : '' }}>
                            General Quiz
                        </option>
                    </select>
                    <input type="hidden" name="schedule_id" id="quiz_schedule_id" value="{{ old('schedule_id', $quiz->schedule_id ?? '') }}">
                    <small class="text-muted">The quiz will be linked to this session automatically.</small>
                </div>
                <div class="col-md-12 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Description</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Optional description...">{{ old('description', $quiz->description ?? '') }}</textarea>
                </div>
                <div class="col-md-4 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Time Limit (minutes)</label>
                    <input type="number" name="time_limit" class="form-control" value="{{ old('time_limit', $quiz->time_limit ?? '') }}" placeholder="Leave blank = unlimited">
                </div>
                <div class="col-md-4 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Pass Score (%)</label>
                    <input type="number" name="pass_score" class="form-control" value="{{ old('pass_score', $quiz->pass_score ?? 60) }}" min="0" max="100">
                </div>
                <div class="col-md-4 mb-3 d-flex align-items-end">
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="is_published" name="is_published" {{ old('is_published', $quiz->is_published ?? false) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="is_published" style="font-size:13px; font-weight:600;">Publish Quiz</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3" style="border-radius:8px;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#fff; border-bottom:2px solid #C9A84C;">
            <span style="font-weight:700; font-size:13px; color:#2d3748;">Questions</span>
            <button type="button" onclick="addQuestion()" class="btn btn-sm" style="background:#C9A84C; color:#fff; font-size:12px; font-weight:700;">
                <i class="fas fa-plus mr-1"></i> Add Question
            </button>
        </div>
        <div class="card-body" id="questions-container">
            @if(isset($quiz) && $quiz->questions->count() > 0)
                @foreach($quiz->questions as $qi => $question)
                <div class="question-block" id="q-block-{{ $qi }}">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span style="font-size:12px; font-weight:700; color:#C9A84C;">Question {{ $qi + 1 }}</span>
                        <button type="button" onclick="removeQuestion({{ $qi }})" style="background:none; border:none; color:#e53e3e; cursor:pointer;"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 mb-2">
                            <label style="font-size:11px; font-weight:700; color:#888;">Question Text *</label>
                            <textarea name="questions[{{ $qi }}][text]" class="form-control" rows="2" required>{{ $question->question_text }}</textarea>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label style="font-size:11px; font-weight:700; color:#888;">Type</label>
                            <select name="questions[{{ $qi }}][type]" class="form-control q-type-select" data-qidx="{{ $qi }}" onchange="handleTypeChange(this, {{ $qi }})">
                                <option value="multiple_choice" {{ $question->type === 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="true_false" {{ $question->type === 'true_false' ? 'selected' : '' }}>True / False</option>
                                <option value="short_answer" {{ $question->type === 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-2">
                            <label style="font-size:11px; font-weight:700; color:#888;">Points</label>
                            <input type="number" name="questions[{{ $qi }}][points]" class="form-control" value="{{ $question->points }}" min="1">
                        </div>
                    </div>
                    <div class="options-area" id="options-{{ $qi }}" style="{{ $question->type === 'short_answer' ? 'display:none' : '' }}">
                        <div style="font-size:11px; font-weight:700; color:#888; margin-bottom:6px;">Options (tick the correct one)</div>
                        <div id="option-rows-{{ $qi }}">
                            @foreach($question->options as $oi => $opt)
                            <div class="option-row">
                                <input type="radio" name="questions[{{ $qi }}][options][correct]" value="{{ $oi }}"
                                    onchange="markCorrectOption({{ $qi }}, {{ $oi }})"
                                    {{ $opt->is_correct ? 'checked' : '' }}>
                                <input type="text" name="questions[{{ $qi }}][options][{{ $oi }}][text]" class="form-control form-control-sm" value="{{ $opt->option_text }}" placeholder="Option text">
                                <input type="hidden" name="questions[{{ $qi }}][options][{{ $oi }}][is_correct]" value="{{ $opt->is_correct ? '1' : '0' }}" id="is-correct-{{ $qi }}-{{ $oi }}">
                                <button type="button" onclick="removeOption(this)" style="background:none;border:none;color:#bbb;cursor:pointer;"><i class="fas fa-times"></i></button>
                            </div>
                            @endforeach
                        </div>
                        <button type="button" onclick="addOption({{ $qi }})" style="background:none;border:none;color:#C9A84C;font-size:12px;font-weight:700;cursor:pointer;padding:0;">
                            <i class="fas fa-plus mr-1"></i> Add Option
                        </button>
                    </div>
                </div>
                @endforeach
            @else
            <p class="text-muted text-center py-3" style="font-size:13px;" id="no-questions-msg">No questions yet. Click "Add Question" to begin.</p>
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-end" style="gap:8px;">
        <a href="{{ route('facilitator.quizzes.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">Cancel</a>
        <button type="submit" class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; padding:8px 20px;">
            <i class="fas fa-save mr-1"></i> {{ isset($quiz) ? 'Update Quiz' : 'Create Quiz' }}
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
function syncQuizSession(select) {
    var opt = select.options[select.selectedIndex];
    document.getElementById('quiz_schedule_id').value = opt.dataset.id || '';
}
(function(){
    var sel = document.querySelector('select[name="title"]');
    if (sel) syncQuizSession(sel);
})();

var qCount = {{ isset($quiz) ? $quiz->questions->count() : 0 }};

function addQuestion() {
    document.getElementById('no-questions-msg') && (document.getElementById('no-questions-msg').style.display = 'none');
    var idx = qCount++;
    var html = `
    <div class="question-block" id="q-block-${idx}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span style="font-size:12px; font-weight:700; color:#C9A84C;">Question ${idx + 1}</span>
            <button type="button" onclick="removeQuestion(${idx})" style="background:none;border:none;color:#e53e3e;cursor:pointer;"><i class="fas fa-times"></i></button>
        </div>
        <div class="row mb-2">
            <div class="col-md-6 mb-2">
                <label style="font-size:11px;font-weight:700;color:#888;">Question Text *</label>
                <textarea name="questions[${idx}][text]" class="form-control" rows="2" required placeholder="Enter question..."></textarea>
            </div>
            <div class="col-md-3 mb-2">
                <label style="font-size:11px;font-weight:700;color:#888;">Type</label>
                <select name="questions[${idx}][type]" class="form-control q-type-select" data-qidx="${idx}" onchange="handleTypeChange(this, ${idx})">
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="true_false">True / False</option>
                    <option value="short_answer">Short Answer</option>
                </select>
            </div>
            <div class="col-md-3 mb-2">
                <label style="font-size:11px;font-weight:700;color:#888;">Points</label>
                <input type="number" name="questions[${idx}][points]" class="form-control" value="1" min="1">
            </div>
        </div>
        <div class="options-area" id="options-${idx}">
            <div style="font-size:11px;font-weight:700;color:#888;margin-bottom:6px;">Options (tick the correct one)</div>
            <div id="option-rows-${idx}">
                ${makeOptionRow(idx, 0, '')}
                ${makeOptionRow(idx, 1, '')}
            </div>
            <button type="button" onclick="addOption(${idx})" style="background:none;border:none;color:#C9A84C;font-size:12px;font-weight:700;cursor:pointer;padding:0;">
                <i class="fas fa-plus mr-1"></i> Add Option
            </button>
        </div>
    </div>`;
    document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);
}

function makeOptionRow(qIdx, oIdx, val) {
    return `<div class="option-row">
        <input type="radio" name="questions[${qIdx}][options][correct]" value="${oIdx}"
            onchange="markCorrectOption(${qIdx}, ${oIdx})">
        <input type="text" name="questions[${qIdx}][options][${oIdx}][text]" class="form-control form-control-sm" value="${val}" placeholder="Option text">
        <input type="hidden" name="questions[${qIdx}][options][${oIdx}][is_correct]" value="0" id="is-correct-${qIdx}-${oIdx}">
        <button type="button" onclick="removeOption(this)" style="background:none;border:none;color:#bbb;cursor:pointer;"><i class="fas fa-times"></i></button>
    </div>`;
}

function addOption(qIdx) {
    var container = document.getElementById('option-rows-' + qIdx);
    var oIdx = container.querySelectorAll('.option-row').length;
    container.insertAdjacentHTML('beforeend', makeOptionRow(qIdx, oIdx, ''));
}

function removeOption(btn) {
    btn.closest('.option-row').remove();
}

function removeQuestion(idx) {
    var el = document.getElementById('q-block-' + idx);
    if (el) el.remove();
}

function handleTypeChange(sel, qIdx) {
    var type = sel.value;
    var optArea = document.getElementById('options-' + qIdx);
    var optRows = document.getElementById('option-rows-' + qIdx);
    if (type === 'short_answer') {
        optArea.style.display = 'none';
    } else if (type === 'true_false') {
        optArea.style.display = 'block';
        optRows.innerHTML = makeOptionRow(qIdx, 0, 'True') + makeOptionRow(qIdx, 1, 'False');
    } else {
        optArea.style.display = 'block';
    }
}

function markCorrectOption(qIdx, oIdx) {
    // Set all is_correct to 0, then the selected one to 1
    var container = document.getElementById('option-rows-' + qIdx);
    var hiddens = container.querySelectorAll('input[type=hidden]');
    hiddens.forEach(function(h) { h.value = '0'; });
    var target = document.getElementById('is-correct-' + qIdx + '-' + oIdx);
    if (target) target.value = '1';
}
</script>
@endsection
