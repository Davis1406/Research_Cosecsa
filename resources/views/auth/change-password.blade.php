@extends('layouts.app')
@section('content')

<div style="min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:30px 16px;">

    <div style="background:#fff; border-radius:10px; box-shadow:0 4px 24px rgba(0,0,0,0.13); width:100%; max-width:420px; overflow:hidden;">

        {{-- Card Top --}}
        <div style="background:#a02626; padding:24px 24px 20px; text-align:center;">
            <img src="{{ asset('img/cosecsa-logo.png') }}" alt="COSECSA"
                 style="width:72px; height:72px; border-radius:50%; object-fit:cover; border:3px solid #C9A84C; box-shadow:0 2px 10px rgba(0,0,0,0.3); margin-bottom:12px;">
            <div style="color:#fff; font-size:17px; font-weight:700;">COSECSA</div>
            <div style="color:rgba(255,255,255,0.75); font-size:11px; margin-top:3px; text-transform:uppercase; letter-spacing:0.5px;">
                Research Training System
            </div>
        </div>

        <div style="padding:28px 28px 24px;">
            <h5 style="color:#333; margin:0 0 6px; font-weight:700;">Set Your Password</h5>
            <p style="color:#888; font-size:13px; margin:0 0 22px;">
                For your security, please set a new personal password before continuing.
                Your new password must be at least 8 characters.
            </p>

            @if(session('warning'))
                <div class="alert alert-warning py-2" style="font-size:13px;">{{ session('warning') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger py-2" style="font-size:13px;">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form action="{{ route('change-password.update') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label style="font-size:13px; color:#555; font-weight:600;">New Password</label>
                    <div style="position:relative;">
                        <input type="password" id="pw1" name="password" class="form-control"
                               required placeholder="At least 8 characters" style="padding-right:42px;">
                        <button type="button" onclick="togglePw('pw1','ico1')" tabindex="-1"
                                style="position:absolute;top:50%;right:12px;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#aaa;padding:0;">
                            <i id="ico1" class="fas fa-eye" style="font-size:15px;"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group">
                    <label style="font-size:13px; color:#555; font-weight:600;">Confirm New Password</label>
                    <div style="position:relative;">
                        <input type="password" id="pw2" name="password_confirmation" class="form-control"
                               required placeholder="Repeat your new password" style="padding-right:42px;">
                        <button type="button" onclick="togglePw('pw2','ico2')" tabindex="-1"
                                style="position:absolute;top:50%;right:12px;transform:translateY(-50%);background:none;border:none;cursor:pointer;color:#aaa;padding:0;">
                            <i id="ico2" class="fas fa-eye" style="font-size:15px;"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-block mt-2"
                        style="background:#a02626; color:#fff; border:none; padding:10px; font-weight:600; border-radius:6px;">
                    Set Password &amp; Continue
                </button>
            </form>
        </div>

        <div style="border-top:1px solid #f0f0f0; padding:12px 28px; text-align:center; background:#fafafa;">
            <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                @csrf
                <button type="submit" style="background:none;border:none;color:#a02626;font-size:12px;cursor:pointer;padding:0;">
                    Sign out instead
                </button>
            </form>
        </div>
    </div>
</div>

@section('scripts')
<script>
function togglePw(inputId, iconId) {
    var inp = document.getElementById(inputId);
    var ico = document.getElementById(iconId);
    if (inp.type === 'password') {
        inp.type = 'text';
        ico.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        inp.type = 'password';
        ico.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
@endsection

@endsection
