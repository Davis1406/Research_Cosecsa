@extends('layouts.app')
@section('content')

<div style="min-height:100vh; display:flex; flex-direction:column; align-items:center; justify-content:center; padding:30px 16px;">

    <div style="background:#fff; border-radius:10px; box-shadow:0 4px 24px rgba(0,0,0,0.13); width:100%; max-width:460px; overflow:hidden;">

        {{-- Card Top --}}
        <div style="background:#a02626; padding:24px 24px 20px; text-align:center;">
            <img src="{{ asset('img/cosecsa-logo.png') }}" alt="COSECSA"
                 style="width:72px; height:72px; border-radius:50%; object-fit:cover; border:3px solid #C9A84C; box-shadow:0 2px 10px rgba(0,0,0,0.3); margin-bottom:12px;">
            <div style="color:#fff; font-size:17px; font-weight:700; line-height:1.2;">
                <i class="fas fa-laptop mr-1"></i> Online Research Methodology Course
            </div>
            <div style="color:rgba(255,255,255,0.75); font-size:11px; margin-top:3px; text-transform:uppercase; letter-spacing:0.5px;">
                COSECSA &mdash; Trainee Registration
            </div>
        </div>

        <div style="padding:28px 28px 24px;">
            <h5 style="color:#333; margin:0 0 6px; font-weight:700;">Register for the online course</h5>
            <p style="color:#888; font-size:13px; margin:0 0 22px;">
                Create your account below to join the course and access materials, quizzes, and your programme timetable.
            </p>

            @if($errors->any())
                <div class="alert alert-danger py-2">
                    <ul class="mb-0 pl-3" style="font-size:13px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.online.store') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label style="font-size:13px; color:#555; font-weight:600;">Full name *</label>
                    <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}"
                           name="name" value="{{ old('name') }}" required autofocus placeholder="Your full name">
                </div>

                <div class="form-group">
                    <label style="font-size:13px; color:#555; font-weight:600;">Email address *</label>
                    <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}"
                           name="email" value="{{ old('email') }}" required placeholder="you@example.com">
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label style="font-size:13px; color:#555; font-weight:600;">Phone</label>
                        <input type="text" class="form-control" name="phone" value="{{ old('phone') }}" placeholder="Optional">
                    </div>
                    <div class="form-group col-md-6">
                        <label style="font-size:13px; color:#555; font-weight:600;">Country</label>
                        <select class="form-control" name="country">
                            <option value="">-- Select country (optional) --</option>
                            @foreach($countries as $country)
                                <option value="{{ $country }}" @selected(old('country') === $country)>{{ $country }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                @include('auth._pick-or-add', [
                    'field'       => 'institution',
                    'label'       => 'Institution',
                    'options'     => $hospitals,
                    'placeholder' => '-- Select hospital / institution (optional) --',
                    'addLabel'    => 'Hospital not listed — add it',
                    'otherPlaceholder' => 'Enter hospital / institution name',
                ])

                <div class="form-row">
                    <div class="form-group col-md-6">
                        @include('auth._pick-or-add', [
                            'field'       => 'specialty',
                            'label'       => 'Programme',
                            'options'     => $programmes,
                            'placeholder' => '-- Select programme (optional) --',
                            'addLabel'    => 'Programme not listed — add it',
                            'otherPlaceholder' => 'Enter programme name',
                        ])
                    </div>
                    <div class="form-group col-md-6">
                        <label style="font-size:13px; color:#555; font-weight:600;">Registration No.</label>
                        <input type="text" class="form-control" name="registration_number" value="{{ old('registration_number') }}" placeholder="Optional">
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label style="font-size:13px; color:#555; font-weight:600;">Password *</label>
                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}"
                               name="password" required placeholder="At least 8 characters">
                    </div>
                    <div class="form-group col-md-6">
                        <label style="font-size:13px; color:#555; font-weight:600;">Confirm password *</label>
                        <input type="password" class="form-control" name="password_confirmation" required placeholder="Repeat password">
                    </div>
                </div>

                <button type="submit" class="btn btn-block mt-2"
                        style="background:#a02626; color:#fff; border:none; padding:10px; font-weight:600; border-radius:6px;">
                    <i class="fas fa-user-plus mr-1"></i> Create my account
                </button>
            </form>

            <p style="text-align:center; font-size:13px; color:#888; margin:16px 0 0;">
                Already registered? <a href="{{ route('login') }}" style="color:#a02626; font-weight:600;">Sign in</a>
            </p>
        </div>
    </div>

</div>

@endsection
