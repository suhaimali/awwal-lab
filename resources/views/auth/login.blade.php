<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Awwal Lab') }} — Sign In</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#2563eb">

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px 16px;
            position: relative;
            background: radial-gradient(circle at 5% 5%, rgba(37, 99, 235, 0.15) 0%, transparent 45%),
                        radial-gradient(circle at 95% 95%, rgba(6, 182, 212, 0.12) 0%, transparent 45%),
                        linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            overflow: hidden;
        }

        /* ── Floating Glow Blobs ── */
        .glow-blob {
            position: absolute;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.45;
            pointer-events: none;
            z-index: 1;
            animation: float-blob 14s infinite ease-in-out alternate;
        }
        .glow-blob-1 {
            width: 450px; height: 450px;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.4) 0%, rgba(37, 99, 235, 0.15) 100%);
            top: -150px; left: -100px;
        }
        .glow-blob-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(6, 182, 212, 0.3) 0%, rgba(8, 145, 178, 0.1) 100%);
            bottom: -100px; right: -50px;
            animation-duration: 18s;
            animation-delay: -3s;
        }
        .glow-blob-3 {
            width: 350px; height: 350px;
            background: radial-gradient(circle, rgba(99, 102, 241, 0.25) 0%, rgba(79, 70, 229, 0.08) 100%);
            top: 50%; left: 50%;
            transform: translate(-50%, -50%);
            animation-duration: 20s;
            animation-delay: -6s;
        }
        @keyframes float-blob {
            0% { transform: translate(0, 0) scale(1) rotate(0deg); }
            50% { transform: translate(50px, 30px) scale(1.1) rotate(90deg); }
            100% { transform: translate(-30px, -50px) scale(0.95) rotate(180deg); }
        }

        /* ── Card ── */
        .card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.6);
            border-radius: 24px;
            padding: 48px 40px;
            box-shadow: 
                0 10px 40px rgba(37, 99, 235, 0.04),
                0 20px 50px rgba(37, 99, 235, 0.08),
                inset 0 1px 0 rgba(255, 255, 255, 0.8);
            animation: slideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 14px;
            margin-bottom: 32px;
        }
        .brand-icon {
            width: 46px; height: 46px;
            border-radius: 12px;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            display: flex; align-items: center; justify-content: center;
            font-size: 20px; color: #fff;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }
        .brand-logo-img {
            width: 46px; height: 46px;
            border-radius: 12px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.8);
        }
        .brand-text .name {
            font-family: 'Outfit', sans-serif;
            font-size: 20px; font-weight: 800; color: #1e3a8a;
            letter-spacing: -0.5px; line-height: 1.1;
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .brand-text .tagline {
            font-size: 11px; color: #64748b;
            font-weight: 500;
            margin-top: 1px;
        }

        /* Heading */
        .heading { margin-bottom: 28px; }
        .heading h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 28px; font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.8px;
            margin-bottom: 6px;
            line-height: 1.2;
        }
        .heading p {
            font-size: 14px;
            color: #475569;
        }

        /* Pill badge */
        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 14px;
        }
        .live-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #2563eb;
            animation: pulse-dot 2s infinite;
        }
        @keyframes pulse-dot {
            0%,100% { box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4); }
            50%      { box-shadow: 0 0 0 5px rgba(37, 99, 235, 0); }
        }

        /* Divider */
        .divider {
            height: 1px;
            background: #e2e8f0;
            margin-bottom: 28px;
        }

        /* Error */
        .error-box {
            display: none;
            background: #fef2f2;
            border: 1px solid #fca5a5;
            border-radius: 12px;
            padding: 12px 14px;
            color: #b91c1c;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            gap: 8px;
            align-items: center;
        }
        .error-box.show { display: flex; }

        /* Fields */
        .field { margin-bottom: 20px; }
        .field label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 16px; top: 50%;
            transform: translateY(-50%);
            font-size: 14px;
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.3s;
        }
        .field-input {
            width: 100%;
            padding: 14px 16px 14px 44px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 12px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .field-input::placeholder { color: #94a3b8; }
        .field-input:focus {
            border-color: #2563eb;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.15);
        }
        .field-input:focus ~ .input-icon,
        .input-wrap:focus-within .input-icon {
            color: #2563eb;
        }
        .field-input.err {
            border-color: #fca5a5;
            background: #fef2f2;
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 14px; top: 50%;
            transform: translateY(-50%);
            border: none; background: none;
            color: #94a3b8;
            cursor: pointer; font-size: 14px;
            padding: 6px;
            transition: color 0.2s;
            outline: none;
        }
        .pw-toggle:hover { color: #2563eb; }

        /* Remember */
        .remember-row {
            display: flex; align-items: center;
            gap: 8px; margin-bottom: 28px;
            cursor: pointer;
            font-size: 13px; font-weight: 500;
            color: #475569;
            user-select: none;
        }
        .remember-row input[type="checkbox"] {
            width: 16px; height: 16px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
            position: relative;
            overflow: hidden;
        }
        .btn-login::before {
            content: '';
            position: absolute;
            top: 0; left: -100%;
            width: 100%; height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: all 0.6s ease;
        }
        .btn-login:hover:not(:disabled)::before {
            left: 100%;
        }
        .btn-login:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.45);
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        }
        .btn-login:active:not(:disabled) {
            transform: translateY(0);
        }
        .btn-login:disabled { opacity: 0.6; cursor: not-allowed; box-shadow: none; }
        .btn-login.success { background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; box-shadow: 0 4px 15px rgba(16, 185, 129, 0.35); }

        /* Footer text */
        .card-footer {
            margin-top: 28px;
            text-align: center;
            font-size: 12px;
            color: #64748b;
        }

        /* Shake */
        @keyframes shake {
            0%,100%{transform:translateX(0)}
            20%,60%{transform:translateX(-4px)}
            40%,80%{transform:translateX(4px)}
        }
        .shake { animation: shake 0.3s ease; }

        /* Responsive */
        @media (max-width: 480px) {
            .card { padding: 36px 24px; border-radius: 20px; }
            .heading h1 { font-size: 24px; }
        }
    </style>
</head>
<body>

<!-- Background Blobs -->
<div class="glow-blob glow-blob-1"></div>
<div class="glow-blob glow-blob-2"></div>
<div class="glow-blob glow-blob-3"></div>

<!-- Card -->
<div class="card" id="login-card">

    <!-- Brand -->
    <div class="brand">
        <img src="{{ asset('images/logo-pwa.png') }}" alt="{{ config('app.name') }}" class="brand-logo-img"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <div class="brand-icon" style="display:none;"><i class="fa fa-flask"></i></div>
        <div class="brand-text">
            <div class="name">{{ config('app.name', 'Awwal Lab') }}</div>
            <div class="tagline">Laboratory Management System</div>
        </div>
    </div>

    <!-- Live badge + Heading -->
    <div class="heading">
        <div class="live-badge">
            <span class="live-dot"></span>
            System Online
        </div>
        <h1>Welcome Back</h1>
        <p>Sign in to access your lab dashboard</p>
    </div>

    <div class="divider"></div>

    <!-- Error -->
    <div class="error-box" id="error-box">
        <i class="fa fa-circle-exclamation"></i>
        <span id="error-text">Invalid email or password.</span>
    </div>

    <form id="login-form" autocomplete="off" novalidate>
        @csrf

        <!-- Email -->
        <div class="field">
            <label for="email">Email Address</label>
            <div class="input-wrap">
                <i class="fa fa-envelope input-icon"></i>
                <input type="email" id="email" name="email" class="field-input"
                       placeholder="you@example.com"
                       value="{{ old('email') }}"
                       required autofocus autocomplete="email">
            </div>
        </div>

        <!-- Password -->
        <div class="field">
            <label for="password">Password</label>
            <div class="input-wrap">
                <i class="fa fa-lock input-icon"></i>
                <input type="password" id="password" name="password" class="field-input"
                       placeholder="Enter your password"
                       required autocomplete="current-password">
                <button type="button" class="pw-toggle" id="pw-toggle" tabindex="-1" aria-label="Toggle">
                    <i class="fa fa-eye" id="pw-icon"></i>
                </button>
            </div>
        </div>

        <!-- Remember -->
        <label class="remember-row">
            <input type="checkbox" name="remember" id="remember">
            <span>Keep me signed in</span>
        </label>

        <!-- Button -->
        <button type="submit" class="btn-login" id="btn-submit">
            <span id="btn-text">Sign In</span>
            <i class="fa fa-arrow-right" id="btn-icon"></i>
        </button>
    </form>

    <div class="card-footer">
        © {{ date('Y') }} {{ config('app.name') }} · All rights reserved
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // Password toggle
    $('#pw-toggle').on('click', function () {
        let inp  = $('#password');
        let show = inp.attr('type') === 'password';
        inp.attr('type', show ? 'text' : 'password');
        $('#pw-icon').toggleClass('fa-eye', !show).toggleClass('fa-eye-slash', show);
    });

    // Form submit
    $('#login-form').on('submit', function (e) {
        e.preventDefault();
        let btn    = $('#btn-submit');
        let errBox = $('#error-box');

        errBox.removeClass('show');
        $('.field-input').removeClass('err');
        btn.prop('disabled', true);
        $('#btn-text').text('Authenticating…');
        $('#btn-icon').removeClass('fa-arrow-right fa-check').addClass('fa-circle-notch fa-spin');

        $.ajax({
            url: "{{ route('login.post') }}",
            type: 'POST',
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    btn.addClass('success');
                    $('#btn-text').text('Access Granted!');
                    $('#btn-icon').removeClass('fa-circle-notch fa-spin').addClass('fa-check');
                    setTimeout(() => { window.location.href = res.redirect; }, 600);
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).removeClass('success');
                $('#btn-text').text('Sign In');
                $('#btn-icon').removeClass('fa-circle-notch fa-spin fa-check').addClass('fa-arrow-right');

                let msg = xhr.responseJSON?.message || 'Invalid email or password.';
                $('#error-text').text(msg);
                errBox.addClass('show');
                $('#login-card').addClass('shake');
                setTimeout(() => $('#login-card').removeClass('shake'), 400);
                $('.field-input').addClass('err');
            }
        });
    });

    // Clear errors on input
    $('.field-input').on('input', function () {
        $(this).removeClass('err');
        $('#error-box').removeClass('show');
    });

    // PWA
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register("{{ asset('sw.js') }}").catch(() => {});
    }
</script>
</body>
</html>
