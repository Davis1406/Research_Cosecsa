@extends('layouts.facilitator')
@section('page-title', $user->name)

@section('styles')
<style>
/* ── Chat layout ── */
.chat-wrap {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 130px);
    min-height: 500px;
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    overflow: hidden;
}

/* ── Header ── */
.chat-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 18px;
    border-bottom: 1.5px solid #e2e8f0;
    background: #fff;
    flex-shrink: 0;
}
.chat-header-avatar {
    width: 40px; height: 40px; border-radius: 50%;
    background: linear-gradient(135deg, #C9A84C, #9a7d2c);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 800; color: #fff;
}
.chat-header-name { font-weight: 800; font-size: 15px; color: #1a202c; }
.chat-header-sub  { font-size: 12px; color: #a0aec0; }

/* ── Message area ── */
.chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 18px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    background: #f8f9fc;
}

/* ── Date divider ── */
.chat-date-divider {
    text-align: center;
    font-size: 11px;
    color: #a0aec0;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin: 10px 0 4px;
}

/* ── Bubble ── */
.bubble-row {
    display: flex;
    align-items: flex-end;
    gap: 8px;
}
.bubble-row.me { flex-direction: row-reverse; }

.bubble {
    max-width: 68%;
    padding: 10px 14px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.55;
    word-break: break-word;
    position: relative;
}
.bubble.them {
    background: #fff;
    color: #1a202c;
    border: 1.5px solid #e8ecf0;
    border-bottom-left-radius: 4px;
}
.bubble.me {
    background: linear-gradient(135deg, #C9A84C, #a88838);
    color: #fff;
    border-bottom-right-radius: 4px;
}
.bubble-time {
    font-size: 10.5px;
    margin-top: 4px;
    text-align: right;
    opacity: .65;
}
.bubble.them .bubble-time { text-align: left; }

.bubble-mini-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    background: linear-gradient(135deg, #2d3748, #1a202c);
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; color: #fff;
    flex-shrink: 0; margin-bottom: 2px;
}

/* ── Attachment / material preview in bubble ── */
.bubble-attachment {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    padding: 8px 10px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
}
.bubble.them .bubble-attachment { background: #f0f2f5; color: #2d3748; }
.bubble.me .bubble-attachment   { background: rgba(255,255,255,.2); color: #fff; }
.bubble-attachment i             { font-size: 16px; }

/* ── Input bar ── */
.chat-footer {
    padding: 12px 16px;
    border-top: 1.5px solid #e2e8f0;
    background: #fff;
    flex-shrink: 0;
}
.chat-input-row {
    display: flex;
    align-items: flex-end;
    gap: 8px;
}
.chat-textarea {
    flex: 1;
    border: 1.5px solid #d1d5db;
    border-radius: 22px;
    padding: 10px 16px;
    font-size: 14px;
    resize: none;
    min-height: 44px;
    max-height: 130px;
    overflow-y: auto;
    font-family: inherit;
    line-height: 1.4;
    outline: none;
    transition: border-color .15s;
}
.chat-textarea:focus { border-color: #C9A84C; }
.chat-send-btn {
    width: 44px; height: 44px; border-radius: 50%;
    background: #C9A84C; border: none; color: #fff;
    font-size: 16px; display: flex; align-items: center; justify-content: center;
    flex-shrink: 0; cursor: pointer; transition: background .15s;
}
.chat-send-btn:hover { background: #a88838; }
.chat-send-btn:disabled { background: #d1d5db; cursor: not-allowed; }

/* Attachment extras row */
.chat-extras {
    display: flex;
    align-items: center;
    gap: 6px;
    margin-bottom: 8px;
    flex-wrap: wrap;
}
.attach-pill {
    display: flex; align-items: center; gap: 6px;
    background: #f8f9fc; border: 1.5px solid #e2e8f0;
    border-radius: 20px; padding: 4px 12px;
    font-size: 12.5px; color: #2d3748; font-weight: 600;
    cursor: pointer; transition: border-color .15s;
}
.attach-pill:hover { border-color: #C9A84C; color: #C9A84C; }
.attach-pill i { font-size: 13px; color: #C9A84C; }
.attach-chip {
    display: flex; align-items: center; gap: 6px;
    background: #fff8e6; border: 1.5px solid #C9A84C55;
    border-radius: 20px; padding: 4px 10px;
    font-size: 12px; color: #9a7d2c; font-weight: 700;
}
.attach-chip button {
    background: none; border: none; color: #9a7d2c;
    font-size: 13px; line-height: 1; cursor: pointer; padding: 0; margin-left: 2px;
}

/* attachment panel hidden by default */
.attach-panel { display: none; margin-bottom: 8px; }
.attach-panel.open { display: block; }

/* responsive */
@media (max-width: 576px) {
    .bubble { max-width: 88%; }
    .chat-wrap { height: calc(100vh - 110px); }
}
</style>
@endsection

@section('content')
<div style="margin-bottom:10px;">
    <a href="{{ route('facilitator.messages.index') }}" style="font-size:13px; color:#718096; text-decoration:none; font-weight:600;">
        <i class="fas fa-arrow-left mr-1"></i> Back to Messages
    </a>
</div>

<div class="chat-wrap">

    {{-- Header --}}
    <div class="chat-header">
        <div class="chat-header-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div style="flex:1;">
            <div class="chat-header-name">{{ $user->name }}</div>
            <div class="chat-header-sub">{{ $user->roles->first()?->title ?? 'User' }}</div>
        </div>
        <a href="{{ route('facilitator.directory.index') }}" style="font-size:12px; color:#a0aec0; text-decoration:none;" title="Directory">
            <i class="fas fa-address-book"></i>
        </a>
    </div>

    {{-- Messages --}}
    <div class="chat-body" id="chatBody">
        @php $lastDate = null; @endphp
        @forelse($messages as $msg)
            @php
                $isMe    = $msg->sender_id === auth()->id();
                $msgDate = $msg->created_at->format('Y-m-d');
            @endphp

            @if($msgDate !== $lastDate)
            <div class="chat-date-divider">{{ $msg->created_at->isToday() ? 'Today' : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('M j, Y')) }}</div>
            @php $lastDate = $msgDate; @endphp
            @endif

            <div class="bubble-row {{ $isMe ? 'me' : '' }}" data-id="{{ $msg->id }}">
                @if(!$isMe)
                <div class="bubble-mini-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                @endif
                <div class="bubble {{ $isMe ? 'me' : 'them' }}">
                    <div>{{ $msg->body }}</div>

                    @if($msg->material)
                    <div class="bubble-attachment">
                        <i class="fas fa-paperclip"></i>
                        <a href="{{ route('material.view', $msg->material) }}"
                           target="_blank"
                           style="color:inherit; text-decoration:underline;">
                            {{ $msg->material->title }}
                            <span style="opacity:.7; font-weight:400;">({{ ucfirst($msg->material->type) }})</span>
                        </a>
                    </div>
                    @endif

                    @if($msg->attachment_path)
                    @php $mime = $msg->attachment_mime ?? ''; @endphp
                    <div class="bubble-attachment">
                        <i class="fas fa-{{ str_contains($mime,'image') ? 'image' : (str_contains($mime,'pdf') ? 'file-pdf' : (str_contains($mime,'video') ? 'film' : (str_contains($mime,'audio') ? 'music' : 'file'))) }}"></i>
                        <a href="{{ asset('storage/'.$msg->attachment_path) }}" target="_blank" download="{{ $msg->attachment_name }}"
                           style="color:inherit; text-decoration:underline; word-break:break-all;">
                            {{ $msg->attachment_name }}
                        </a>
                    </div>
                    @if($msg->attachment_mime && str_contains($msg->attachment_mime, 'image'))
                    <img src="{{ asset('storage/'.$msg->attachment_path) }}" alt="{{ $msg->attachment_name }}"
                         style="max-width:220px; border-radius:8px; margin-top:6px; display:block;">
                    @endif
                    @endif

                    <div class="bubble-time">{{ $msg->created_at->format('g:i A') }}</div>
                </div>
            </div>
        @empty
            <div style="text-align:center; padding:60px 20px;">
                <i class="fas fa-comment" style="font-size:40px; color:#e2e8f0; display:block; margin-bottom:12px;"></i>
                <p style="color:#a0aec0; font-size:14px;">No messages yet. Say hello!</p>
            </div>
        @endforelse
    </div>

    {{-- Input footer --}}
    <div class="chat-footer">
        <form id="chatForm" enctype="multipart/form-data">
            @csrf
            {{-- Extras row --}}
            <div class="chat-extras">
                <div class="attach-pill" onclick="toggleAttachPanel('material')">
                    <i class="fas fa-paperclip"></i> Material
                </div>
                <div class="attach-pill" onclick="document.getElementById('fileInput').click()">
                    <i class="fas fa-upload"></i> File
                </div>
                <div id="materialChip" class="attach-chip" style="display:none;">
                    <i class="fas fa-paperclip"></i>
                    <span id="materialChipLabel">—</span>
                    <button type="button" onclick="clearMaterial()" title="Remove">&times;</button>
                </div>
                <div id="fileChip" class="attach-chip" style="display:none;">
                    <i class="fas fa-file"></i>
                    <span id="fileChipLabel">—</span>
                    <button type="button" onclick="clearFile()" title="Remove">&times;</button>
                </div>
            </div>

            {{-- Material picker --}}
            <div class="attach-panel" id="materialPanel">
                <input type="text" id="matSearch" class="form-control form-control-sm" placeholder="Search materials..." style="margin-bottom:6px; border-radius:8px;">
                <div id="matList" style="max-height:180px; overflow-y:auto; border:1.5px solid #e2e8f0; border-radius:8px; background:#fff;">
                    @foreach($materials as $mat)
                    <div class="mat-item" data-id="{{ $mat->id }}" data-title="{{ $mat->title }}" data-name="{{ strtolower($mat->title) }}"
                         style="padding:8px 12px; cursor:pointer; font-size:13px; border-bottom:1px solid #f0f2f5; transition:background .1s;"
                         onmouseover="this.style.background='#f8f9fc'" onmouseout="this.style.background=''"
                         onclick="selectMaterial(this)">
                        <i class="fas fa-{{ $mat->type === 'video' ? 'video' : ($mat->type === 'youtube' ? 'youtube' : ($mat->type === 'audio' ? 'music' : 'file-alt')) }} mr-2" style="color:#C9A84C; width:14px;"></i>
                        {{ $mat->title }} <span style="color:#aaa; font-size:11px;">({{ ucfirst($mat->type) }})</span>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="material_id" id="selectedMaterialId">
            </div>

            {{-- Hidden file input --}}
            <input type="file" id="fileInput" name="attachment" style="display:none;"
                   accept="image/*,application/pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.mp3,.mp4,.zip"
                   onchange="onFileChosen(this)">

            {{-- Message row --}}
            <div class="chat-input-row">
                <textarea class="chat-textarea" id="chatInput" name="body" rows="1" placeholder="Type a message…"
                          onkeydown="handleKey(event)" oninput="autoResize(this)"></textarea>
                <button type="button" class="chat-send-btn" id="sendBtn" onclick="sendMessage()" title="Send">
                    <i class="fas fa-paper-plane" style="margin-left:2px;"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const SEND_URL   = "{{ route('facilitator.messages.send', $user) }}";
const POLL_URL   = "{{ route('facilitator.messages.poll', $user) }}";
const CSRF_TOKEN = "{{ csrf_token() }}";
const MY_NAME    = "{{ auth()->user()->name }}";
const OTHER_INITIAL = "{{ strtoupper(substr($user->name, 0, 1)) }}";

// Track last message id for polling
let lastMsgId = {{ $messages->isNotEmpty() ? $messages->last()->id : 0 }};
let pollTimer;

// ── Scroll to bottom ──────────────────────────────────────────────
function scrollToBottom(smooth) {
    const body = document.getElementById('chatBody');
    body.scrollTo({ top: body.scrollHeight, behavior: smooth ? 'smooth' : 'auto' });
}
scrollToBottom(false);

// ── Auto-resize textarea ──────────────────────────────────────────
function autoResize(el) {
    el.style.height = 'auto';
    el.style.height = Math.min(el.scrollHeight, 130) + 'px';
}

// ── Enter to send (Shift+Enter = new line) ─────────────────────────
function handleKey(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        sendMessage();
    }
}

// ── Attach panel toggle ───────────────────────────────────────────
function toggleAttachPanel(type) {
    const panel = document.getElementById(type + 'Panel');
    panel.classList.toggle('open');
    if (panel.classList.contains('open') && type === 'material') {
        document.getElementById('matSearch').focus();
    }
}

// Material search filter
document.getElementById('matSearch').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('.mat-item').forEach(function(el) {
        el.style.display = el.dataset.name.includes(q) ? '' : 'none';
    });
});

// Select a material
function selectMaterial(el) {
    document.getElementById('selectedMaterialId').value = el.dataset.id;
    document.getElementById('materialChipLabel').textContent = el.dataset.title;
    document.getElementById('materialChip').style.display = 'flex';
    document.getElementById('materialPanel').classList.remove('open');
}
function clearMaterial() {
    document.getElementById('selectedMaterialId').value = '';
    document.getElementById('materialChip').style.display = 'none';
}

// File attachment
function onFileChosen(input) {
    if (input.files && input.files[0]) {
        document.getElementById('fileChipLabel').textContent = input.files[0].name;
        document.getElementById('fileChip').style.display = 'flex';
    }
}
function clearFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('fileChip').style.display = 'none';
    document.getElementById('fileChipLabel').textContent = '—';
}

// ── Send message via AJAX ─────────────────────────────────────────
function sendMessage() {
    const body = document.getElementById('chatInput').value.trim();
    if (!body) return;

    const btn = document.getElementById('sendBtn');
    btn.disabled = true;

    const fd = new FormData(document.getElementById('chatForm'));

    fetch(SEND_URL, {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
        body: fd,
    })
    .then(r => r.json())
    .then(msg => {
        appendBubble(msg, true);
        document.getElementById('chatInput').value = '';
        document.getElementById('chatInput').style.height = 'auto';
        clearMaterial();
        clearFile();
        lastMsgId = msg.id;
        btn.disabled = false;
    })
    .catch(() => { btn.disabled = false; alert('Failed to send. Please try again.'); });
}

// ── Render a bubble ────────────────────────────────────────────────
function mimeIcon(mime) {
    if (!mime) return 'file';
    if (mime.includes('image')) return 'image';
    if (mime.includes('pdf')) return 'file-pdf';
    if (mime.includes('video')) return 'film';
    if (mime.includes('audio')) return 'music';
    return 'file';
}

function appendBubble(msg, isMe) {
    const body = document.getElementById('chatBody');

    const row = document.createElement('div');
    row.className = 'bubble-row' + (isMe ? ' me' : '');
    row.dataset.id = msg.id;

    let attachHtml = '';
    if (msg.material_id && msg.material_title) {
        const cls = isMe ? 'me' : 'them';
        attachHtml += `<div class="bubble-attachment"><i class="fas fa-paperclip"></i><span>${escHtml(msg.material_title)}</span></div>`;
    }
    if (msg.attachment_path) {
        const icon = mimeIcon(msg.attachment_mime);
        attachHtml += `<div class="bubble-attachment"><i class="fas fa-${icon}"></i><a href="${msg.attachment_path}" target="_blank" download="${escHtml(msg.attachment_name)}" style="color:inherit;text-decoration:underline;">${escHtml(msg.attachment_name)}</a></div>`;
        if (msg.attachment_mime && msg.attachment_mime.includes('image')) {
            attachHtml += `<img src="${msg.attachment_path}" style="max-width:220px;border-radius:8px;margin-top:6px;display:block;">`;
        }
    }

    const avatarHtml = isMe ? '' : `<div class="bubble-mini-avatar">${OTHER_INITIAL}</div>`;

    row.innerHTML = `
        ${avatarHtml}
        <div class="bubble ${isMe ? 'me' : 'them'}">
            <div>${escHtml(msg.body)}</div>
            ${attachHtml}
            <div class="bubble-time">${msg.created_at}</div>
        </div>
    `;
    body.appendChild(row);
    scrollToBottom(true);
}

function escHtml(str) {
    if (!str) return '';
    return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
}

// ── Polling for new messages ──────────────────────────────────────
function poll() {
    fetch(POLL_URL + '?since=' + lastMsgId, {
        headers: { 'X-CSRF-TOKEN': CSRF_TOKEN, 'Accept': 'application/json' },
    })
    .then(r => r.json())
    .then(msgs => {
        msgs.forEach(msg => {
            // Avoid duplicate if we just sent
            if (msg.id > lastMsgId) {
                appendBubble(msg, false);
                lastMsgId = msg.id;
            }
        });
    })
    .catch(() => {});
    pollTimer = setTimeout(poll, 5000);
}

// Start polling after 5s
pollTimer = setTimeout(poll, 5000);

// Stop polling when tab is hidden
document.addEventListener('visibilitychange', function() {
    if (document.hidden) {
        clearTimeout(pollTimer);
    } else {
        pollTimer = setTimeout(poll, 1000);
    }
});
</script>
@endsection
