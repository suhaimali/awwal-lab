<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SUHAIM SOFT LAB')</title>
    <meta name="description" content="Sign in to your account on {{ config('app.name', 'SUHAIM SOFT LAB') }}.">
    <meta name="keywords" content="suhaim soft lab login, lims sign in, laboratory software login">
    <meta name="robots" content="noindex, follow">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        /* ── CSS Variables ───────────────────────────────────── */
        :root {
            --primary:       #4f46e5;
            --primary-hover: #4338ca;
            --bg-color:      #f3f4f6;
            --card-bg:       #ffffff;
            --text-dark:     #111827;
            --text-muted:    #6b7280;
            --border-color:  #e5e7eb;
            --input-bg:      #f9fafb;
            --error:         #ef4444;
            --success:       #10b981;
        }

        /* ── Hard Reset ──────────────────────────────────────── */
        *, *::before, *::after {
            margin: 0; padding: 0;
            box-sizing: border-box;
        }

        html, body {
            width: 100%; height: 100%;
            font-family: 'Inter', sans-serif;
        }

        /* ── Auth Body — full-screen flex container ───────────── */
        body.auth-body {
            display: flex;
            flex-direction: row;
            align-items: stretch;
            min-height: 100vh;
            background: #0f172a;
        }

        /* ── Split wrapper ───────────────────────────────────── */
        .auth-split {
            display: flex;
            flex-direction: row;
            width: 100%;
            min-height: 100vh;
        }

        /* ══ LEFT PANEL ══════════════════════════════════════ */
        .auth-left {
            flex: 1;
            min-width: 0;
            background: linear-gradient(145deg, #1e1b4b 0%, #312e81 50%, #4338ca 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 48px;
            position: relative;
            overflow: hidden;
        }

        .auth-left::before {
            content: '';
            position: absolute;
            width: 400px; height: 400px;
            background: rgba(129,140,248,.15);
            border-radius: 50%;
            top: -100px; left: -100px;
            pointer-events: none;
        }
        .auth-left::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            background: rgba(99,102,241,.12);
            border-radius: 50%;
            bottom: -80px; right: -80px;
            pointer-events: none;
        }

        .auth-left-inner {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 380px;
            width: 100%;
        }

        .auth-logo-icon {
            width: 80px; height: 80px;
            background: rgba(255,255,255,.12);
            border: 2px solid rgba(255,255,255,.2);
            border-radius: 22px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            font-size: 34px;
            color: #fff;
            backdrop-filter: blur(8px);
            box-shadow: 0 8px 32px rgba(0,0,0,.3);
        }

        .auth-left-inner h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 30px;
            font-weight: 800;
            color: #fff;
            margin-bottom: 10px;
            letter-spacing: -0.3px;
        }

        .auth-tagline {
            font-size: 14px;
            color: rgba(255,255,255,.65);
            line-height: 1.65;
            margin-bottom: 40px;
        }

        .auth-features {
            display: flex;
            flex-direction: column;
            gap: 12px;
            text-align: left;
        }

        .auth-feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.12);
            border-radius: 12px;
            padding: 12px 16px;
        }

        .auth-feature-item .feat-icon {
            width: 32px; height: 32px;
            background: rgba(99,102,241,.35);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            color: #a5b4fc;
            flex-shrink: 0;
        }

        .auth-feature-item span {
            font-size: 13px;
            font-weight: 500;
            color: rgba(255,255,255,.85);
        }

        /* ══ RIGHT PANEL ═════════════════════════════════════ */
        .auth-right {
            width: 460px;
            flex-shrink: 0;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 40px;
            position: relative;
        }

        .auth-right-inner {
            width: 100%;
            max-width: 360px;
        }

        /* Back button */
        .back-btn {
            position: absolute;
            top: 24px; left: 24px;
            width: 34px; height: 34px;
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            background: #f9fafb;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #6b7280;
            text-decoration: none;
            font-size: 13px;
            transition: all .2s;
            z-index: 10;
        }
        .back-btn:hover {
            background: #f1f5f9;
            color: var(--primary);
            border-color: #c7d2fe;
            transform: translateX(-2px);
        }

        .form-heading { margin-bottom: 28px; }
        .form-heading h2 {
            font-family: 'Outfit', sans-serif;
            font-size: 26px;
            font-weight: 700;
            color: #111827;
            margin-bottom: 4px;
        }
        .form-heading p { font-size: 13.5px; color: #6b7280; }

        /* ── Alerts ──────────────────────────────────────────── */
        .lockout-alert {
            display: flex; align-items: flex-start; gap: 10px;
            background: #fff7ed; border-left: 4px solid #f97316;
            border-radius: 8px; padding: 12px 14px;
            margin-bottom: 18px; font-size: 13px; color: #9a3412;
            animation: slideDown .3s ease;
        }
        .lockout-alert > i { color: #f97316; flex-shrink: 0; margin-top: 2px; }
        .lockout-text { display: flex; flex-direction: column; gap: 2px; }
        .lockout-text strong { font-size: 12.5px; font-weight: 700; color: #7c2d12; }
        .lockout-text span   { font-size: 12.5px; color: #9a3412; }
        #lockout-timer { font-weight: 700; font-variant-numeric: tabular-nums; }

        .error-alert {
            display: none; gap: 8px; align-items: flex-start;
            background: #fef2f2; border-left: 4px solid #ef4444;
            border-radius: 8px; padding: 10px 14px;
            margin-bottom: 18px; font-size: 13px; color: #991b1b;
            animation: slideDown .25s ease;
        }
        .error-alert.show { display: flex; }

        .attempts-alert {
            display: flex; align-items: center; gap: 8px;
            background: #fffbeb; border-left: 4px solid #f59e0b;
            border-radius: 8px; padding: 9px 13px;
            margin-bottom: 18px; font-size: 13px; color: #92400e;
            font-weight: 500; animation: slideDown .25s ease;
        }
        .attempts-alert > i { color: #f59e0b; flex-shrink: 0; }

        /* ── Form ────────────────────────────────────────────── */
        .form-group { margin-bottom: 18px; }

        .form-label {
            display: block; font-size: 12.5px; font-weight: 600;
            color: #374151; margin-bottom: 6px; letter-spacing: .01em;
        }

        .input-group { position: relative; }

        .input-icon {
            position: absolute; left: 14px; top: 50%;
            transform: translateY(-50%);
            color: #9ca3af; font-size: 15px;
            pointer-events: none; transition: color .25s;
        }

        .form-input {
            width: 100%;
            padding: 12px 14px 12px 40px;
            background: #f9fafb;
            border: 1.5px solid #e5e7eb;
            border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 14.5px; color: #111827;
            outline: none; transition: all .25s;
        }
        #password { padding-right: 44px; }

        .form-input:focus {
            background: #fff;
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.12);
        }
        .form-input:disabled {
            opacity: .5; cursor: not-allowed; background: #f3f4f6;
        }
        .input-group:focus-within .input-icon { color: #6366f1; }

        .btn-toggle-password {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%);
            background: none; border: none;
            color: #9ca3af; cursor: pointer;
            font-size: 15px; outline: none; transition: color .2s;
        }
        .btn-toggle-password:hover   { color: #374151; }
        .btn-toggle-password:disabled { opacity: .4; cursor: not-allowed; }

        /* ── Options row ─────────────────────────────────────── */
        .form-options {
            display: flex; align-items: center;
            justify-content: space-between;
            margin-bottom: 22px;
        }
        .checkbox-wrapper {
            display: flex; align-items: center; gap: 7px; cursor: pointer;
        }
        .checkbox-wrapper input[type="checkbox"] {
            width: 15px; height: 15px; border-radius: 4px;
            accent-color: #6366f1; cursor: pointer;
        }
        .checkbox-wrapper span {
            font-size: 13px; color: #6b7280; font-weight: 500;
        }
        .security-badge {
            display: flex; align-items: center; gap: 4px;
            font-size: 11.5px; font-weight: 600;
            color: #059669; background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 999px; padding: 3px 10px;
        }
        .security-badge i { font-size: 10px; }

        /* ── Submit ──────────────────────────────────────────── */
        .btn-submit {
            width: 100%; padding: 13px;
            background: linear-gradient(135deg, #4f46e5, #6366f1);
            color: #fff; border: none; border-radius: 10px;
            font-family: 'Inter', sans-serif;
            font-size: 15px; font-weight: 600; cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 9px;
            transition: all .2s;
            box-shadow: 0 4px 14px rgba(79,70,229,.25);
        }
        .btn-submit:hover:not(:disabled) {
            background: linear-gradient(135deg, #4338ca, #4f46e5);
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(79,70,229,.35);
        }
        .btn-submit:active:not(:disabled) { transform: translateY(0); }
        .btn-submit:disabled {
            opacity: .6; cursor: not-allowed;
            transform: none; box-shadow: none; background: #6b7280;
        }
        .btn-submit.success { background: #10b981; cursor: default; }

        /* ── Footer ──────────────────────────────────────────── */
        .footer-text {
            text-align: center; margin-top: 24px;
            font-size: 12px; color: #9ca3af;
        }
        .security-note {
            margin-top: 6px; font-size: 11px;
            display: flex; align-items: center;
            justify-content: center; gap: 4px; color: #9ca3af;
        }
        .security-note i { color: #059669; }

        /* ── Animation ───────────────────────────────────────── */
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ══ RESPONSIVE ══════════════════════════════════════ */
        @media (max-width: 900px) {
            body.auth-body {
                background: #f3f4f6;
            }
            .auth-split {
                flex-direction: column;
            }
            .auth-left {
                display: none;
            }
            .auth-right {
                width: 100%;
                min-height: 100vh;
                background: #f3f4f6;
                padding: 40px 20px;
                justify-content: flex-start;
                padding-top: 60px;
            }
            .auth-right-inner {
                background: #fff;
                border-radius: 16px;
                padding: 36px 28px;
                box-shadow: 0 8px 32px rgba(0,0,0,.08);
                max-width: 440px;
                width: 100%;
                margin: 0 auto;
            }
        }

        @media (max-width: 480px) {
            .auth-right { padding: 20px 12px; padding-top: 50px; }
            .auth-right-inner { padding: 28px 18px; }
            .back-btn { top: 16px; left: 16px; }
        }
    </style>
    @yield('styles')
</head>
<body class="auth-body">

    @yield('content')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
    </script>
    @yield('scripts')
</body>
</html>
