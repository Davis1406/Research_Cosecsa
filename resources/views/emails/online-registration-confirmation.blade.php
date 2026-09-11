<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registration confirmed</title>
</head>
<body style="margin:0; padding:0; background:#f4f4f4; font-family:'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background:#f4f4f4; padding:30px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width:460px; background:#fff; border-radius:10px; overflow:hidden; box-shadow:0 4px 24px rgba(0,0,0,0.08);">

                    {{-- Header band --}}
                    <tr>
                        <td style="background:#a02626; padding:24px; text-align:center;">
                            <img src="{{ $message->embed(public_path('img/cosecsa-logo.png')) }}" alt="COSECSA"
                                 width="64" height="64"
                                 style="width:64px; height:64px; border-radius:50%; object-fit:cover; border:3px solid #C9A84C; box-shadow:0 2px 10px rgba(0,0,0,0.3); margin-bottom:10px;">
                            <div style="color:#fff; font-size:17px; font-weight:700;">COSECSA</div>
                            <div style="color:rgba(255,255,255,0.75); font-size:11px; margin-top:3px; text-transform:uppercase; letter-spacing:0.5px;">
                                Online Research Methodology Course
                            </div>
                        </td>
                    </tr>

                    {{-- Body --}}
                    <tr>
                        <td style="padding:28px;">
                            <h2 style="color:#2d2d2d; font-size:19px; margin:0 0 12px;">You're registered, {{ $name }}! 🎉</h2>
                            <p style="color:#555; font-size:14px; line-height:1.6; margin:0 0 18px;">
                                Thank you for registering for the <strong>COSECSA Online Research Methodology Course</strong>.
                                Your account has been created successfully and you're all set to get started.
                            </p>

                            <div style="background:#faf7ef; border:1px solid #f0e2b8; border-radius:8px; padding:16px 18px; margin-bottom:20px;">
                                <p style="margin:0 0 10px; font-size:13px; font-weight:700; color:#8a6d1f; text-transform:uppercase; letter-spacing:0.4px;">
                                    How to access the portal
                                </p>
                                <ol style="margin:0; padding-left:18px; color:#555; font-size:13.5px; line-height:1.9;">
                                    <li>Go to <a href="{{ $loginUrl }}" style="color:#a02626; font-weight:600;">{{ $loginUrl }}</a></li>
                                    <li>Under <strong>"Which course?"</strong> on the login page, select <strong>Online</strong></li>
                                    <li>Sign in with the email and password you created: <strong>{{ $email }}</strong></li>
                                </ol>
                            </div>

                            <div style="text-align:center; margin-bottom:20px;">
                                <a href="{{ $loginUrl }}"
                                   style="display:inline-block; background:#a02626; color:#fff; text-decoration:none; font-weight:600; font-size:14px; padding:12px 28px; border-radius:6px;">
                                    Go to the Portal
                                </a>
                            </div>

                            <p style="color:#999; font-size:12.5px; line-height:1.6; margin:0;">
                                Once inside, you'll find your session timetable, training materials, and quizzes. If
                                you didn't request this registration, you can safely ignore this email.
                            </p>
                        </td>
                    </tr>

                    {{-- Footer --}}
                    <tr>
                        <td style="border-top:1px solid #f0f0f0; padding:16px 28px; text-align:center; background:#fafafa;">
                            <p style="font-size:11px; color:#bbb; margin:0;">
                                College of Surgeons of East, Central and Southern Africa &mdash; Research Training System
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
