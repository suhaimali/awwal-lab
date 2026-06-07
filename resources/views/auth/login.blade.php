<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Awwal Lab') }} — Sign In</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
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
            background-color: #f3f7fc;
        }

        /* ── Card ── */
        .card {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 40px 36px;
            box-shadow: 
                0 10px 25px rgba(37, 99, 235, 0.04),
                0 18px 48px rgba(37, 99, 235, 0.08);
            animation: slideUp 0.4s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* Brand */
        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
        }
        .brand-icon {
            width: 42px; height: 42px;
            border-radius: 10px;
            background: #2563eb;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; color: #fff;
            flex-shrink: 0;
        }
        .brand-logo-img {
            width: 42px; height: 42px;
            border-radius: 10px;
            object-fit: cover;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }
        .brand-text .name {
            font-size: 16px; font-weight: 700; color: #1e293b;
            letter-spacing: -0.3px; line-height: 1.1;
        }
        .brand-text .tagline {
            font-size: 11px; color: #64748b;
            font-weight: 500;
        }

        /* Heading */
        .heading { margin-bottom: 24px; }
        .heading h1 {
            font-size: 24px; font-weight: 800;
            color: #1e293b;
            letter-spacing: -0.5px;
            margin-bottom: 4px;
            line-height: 1.2;
        }
        .heading p {
            font-size: 14px;
            color: #64748b;
        }

        /* Pill badge */
        .live-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 20px;
            padding: 4px 10px;
            font-size: 11px;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 12px;
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
            margin-bottom: 24px;
        }

        /* Error */
        .error-box {
            display: none;
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 10px;
            padding: 10px 12px;
            color: #ef4444;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 16px;
            gap: 8px;
            align-items: center;
        }
        .error-box.show { display: flex; }

        /* Fields */
        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute;
            left: 14px; top: 50%;
            transform: translateY(-50%);
            font-size: 13px;
            color: #94a3b8;
            pointer-events: none;
            transition: color 0.2s;
        }
        .field-input {
            width: 100%;
            padding: 12px 14px 12px 38px;
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            border-radius: 10px;
            font-size: 14px;
            font-family: 'Inter', sans-serif;
            color: #1e293b;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .field-input::placeholder { color: #94a3b8; }
        .field-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .field-input:focus ~ .input-icon,
        .input-wrap:focus-within .input-icon {
            color: #2563eb;
        }
        .field-input.err {
            border-color: #f87171;
            background: #fef2f2;
        }

        /* Password toggle */
        .pw-toggle {
            position: absolute;
            right: 12px; top: 50%;
            transform: translateY(-50%);
            border: none; background: none;
            color: #94a3b8;
            cursor: pointer; font-size: 13px;
            padding: 4px;
            transition: color 0.15s;
        }
        .pw-toggle:hover { color: #2563eb; }

        /* Remember */
        .remember-row {
            display: flex; align-items: center;
            gap: 8px; margin-bottom: 24px;
            cursor: pointer;
            font-size: 13px; font-weight: 500;
            color: #475569;
        }
        .remember-row input[type="checkbox"] {
            width: 15px; height: 15px;
            accent-color: #2563eb;
            cursor: pointer;
        }

        /* Submit Button */
        .btn-login {
            width: 100%;
            padding: 12px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
            position: relative;
        }
        .btn-login:hover:not(:disabled) { background: #1d4ed8; box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3); }
        .btn-login:disabled { opacity: 0.6; cursor: not-allowed; box-shadow: none; }
        .btn-login.success { background: #10b981 !important; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2); }

        /* Footer text */
        .card-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
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
            .card { padding: 30px 20px; border-radius: 12px; }
            .heading h1 { font-size: 20px; }
        }
    </style>
</head>
<body>

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
