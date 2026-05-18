@extends('layouts.app')
@section('content')

<div style="min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:30px 16px;">

    {{-- Login Card --}}
    <div style="background:#fff; border-radius:10px; box-shadow:0 4px 24px rgba(0,0,0,0.13); width:100%; max-width:420px; overflow:hidden;">

        {{-- Card Top: burgundy band with logos --}}
        <div style="background:#a02626; padding:24px 24px 20px; text-align:center;">
            <img src="{{ asset('img/cosecsa-logo.png') }}" alt="COSECSA"
                 style="width:72px; height:72px; border-radius:50%; object-fit:cover; border:3px solid #C9A84C; box-shadow:0 2px 10px rgba(0,0,0,0.3); margin-bottom:12px;">
            <div style="color:#fff; font-size:17px; font-weight:700; line-height:1.2;">COSECSA</div>
            <div style="color:rgba(255,255,255,0.75); font-size:11px; margin-top:3px; text-transform:uppercase; letter-spacing:0.5px;">
                Research Training System
            </div>
        </div>

        {{-- Form body --}}
        <div style="padding:28px 28px 24px;">
            <h5 style="color:#333; margin:0 0 6px; font-weight:700;">Welcome back</h5>
            <p style="color:#888; font-size:13px; margin:0 0 22px;">Sign in to continue to your account</p>

            @if(\Session::has('message'))
                <div class="alert alert-info py-2">{{ \Session::get('message') }}</div>
            @endif

            <form action="{{ route('login') }}" method="POST">
                {{ csrf_field() }}

                <div class="form-group">
                    <label style="font-size:13px; color:#555; font-weight:600;">Email address</label>
                    <input type="email"
                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           name="email" value="{{ old('email') }}" required autofocus
                           placeholder="Enter your email">
                    @if($errors->has('email'))
                        <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label style="font-size:13px; color:#555; font-weight:600;">Password</label>
                    <input type="password"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                           name="password" required placeholder="Enter your password">
                    @if($errors->has('password'))
                        <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember" style="font-size:13px;">{{ trans('global.remember_me') }}</label>
                    </div>
                    <a href="{{ route('password.request') }}" style="font-size:13px; color:#a02626;">
                        {{ trans('global.forgot_password') }}
                    </a>
                </div>

                <button type="submit" class="btn btn-block"
                        style="background:#a02626; color:#fff; border:none; padding:10px; font-weight:600; border-radius:6px;">
                    Sign In
                </button>
            </form>
        </div>

        {{-- Footer: Intuitive Foundation logo --}}
        <div style="border-top:1px solid #f0f0f0; padding:16px 28px 20px; text-align:center; background:#fafafa;">
            <p style="font-size:11px; color:#bbb; margin:0 0 10px;">Supported by</p>
            @if(file_exists(public_path('img/intuitive-foundation-logo.png')))
                <img src="{{ asset('img/intuitive-foundation-logo.png') }}" alt="Intuitive Foundation" style="height:40px; width:auto; opacity:0.85;">
            @else
                <img src="{{ asset('img/intuitive-foundation-logo.svg') }}" alt="Intuitive Foundation" style="height:44px; width:auto; opacity:0.85;">
            @endif
        </div>
    </div>

</div>

@endsection
