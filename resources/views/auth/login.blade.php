<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Awwal Lab') }} - Sign In</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0052cc">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-pwa.png') }}">

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('css/vendors_css.css') }}?v=2">
    
    <style>
        :root {
            --primary: #4318FF;
            --primary-hover: #3311db;
            --bg-body: #F4F7FE;
            --text-main: #1B2559;
            --text-secondary: #A3AED0;
            --white: #FFFFFF;
            --border: #E0E5F2;
            --error: #EE5D50;
        }

        body {
            background-color: var(--bg-body);
            font-family: 'Plus Jakarta Sans', sans-serif;
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            display: flex;
            width: 100%;
            max-width: 1200px;
            min-height: 90vh;
            background: var(--white);
            border-radius: 30px;
            overflow: hidden;
            box-shadow: 0 20px 80px rgba(0, 0, 0, 0.08);
            margin: 20px;
            flex-direction: column;
        }

        @media (min-width: 992px) {
            .login-container { flex-direction: row; }
        }

        @media (max-width: 1200px) {
            .login-container { margin: 10px; border-radius: 20px; }
        }

        @media (max-width: 991px) {
            .login-container { max-width: 550px; min-height: auto; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05); }
        }

        @media (max-width: 576px) {
            .login-container { margin: 0; border-radius: 0; height: auto; min-height: 100vh; }
        }

        .login-side-image {
            flex: 1.2;
            background: linear-gradient(135deg, var(--primary) 0%, #707EAE 100%);
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: var(--white);
            padding: 40px 20px;
            text-align: center;
        }

        @media (min-width: 992px) {
            .login-side-image { padding: 60px; min-height: 100%; }
        }

        .login-side-image h1 {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 15px;
            line-height: 1.2;
        }

        @media (min-width: 576px) {
            .login-side-image h1 { font-size: 34px; }
        }

        @media (min-width: 992px) {
            .login-side-image h1 { font-size: 42px; margin-bottom: 20px; }
        }

        .login-side-image p {
            font-size: 15px;
            opacity: 0.9;
            max-width: 400px;
            margin: 0 auto;
        }

        @media (min-width: 992px) {
            .login-side-image p { font-size: 18px; }
        }

        .login-side-image .branding-icon {
            margin-top: 20px;
            font-size: 50px;
            opacity: 0.2;
        }

        @media (min-width: 992px) {
            .login-side-image .branding-icon { margin-top: 40px; font-size: 80px; }
        }

        .login-form-side {
            flex: 1;
            padding: 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow-y: auto;
        }

        @media (max-width: 768px) {
            .login-form-side { padding: 40px; }
            .header-content h2 { font-size: 28px; }
        }

        @media (max-width: 576px) {
            .login-form-side { padding: 30px 20px; }
            .brand-logo { margin-bottom: 30px; }
            .header-content h2 { font-size: 24px; }
            .header-content p { font-size: 14px; margin-bottom: 30px; }
            .form-control-custom { padding: 15px 20px; }
            .btn-primary-custom { padding: 15px; }
        }

        .brand-logo img { height: 50px; border-radius: 12px; }

        .header-content h2 {
            font-size: 36px;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .header-content p { color: var(--text-secondary); font-size: 16px; margin-bottom: 40px; }

        .form-group { margin-bottom: 24px; }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-main);
            margin-bottom: 10px;
            margin-left: 5px;
        }

        .form-control-custom {
            width: 100%;
            padding: 18px 24px;
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 20px;
            font-size: 15px;
            color: var(--text-main);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-sizing: border-box;
        }

        .form-control-custom:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 10px 20px rgba(67, 24, 255, 0.08);
        }

        .options-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            font-size: 14px;
            padding: 0 5px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            color: var(--text-main);
            font-weight: 500;
            cursor: pointer;
        }

        .remember-me input {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            border-radius: 6px;
            border: 1px solid var(--border);
            cursor: pointer;
        }

        .btn-primary-custom {
            width: 100%;
            padding: 18px;
            background: var(--primary);
            color: var(--white);
            border: none;
            border-radius: 20px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 25px rgba(67, 24, 255, 0.2);
        }

        .btn-primary-custom:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(67, 24, 255, 0.25);
        }

        .btn-primary-custom:disabled { background: #9fa8da; cursor: not-allowed; transform: none; }

        .alert-error {
            background: #FFF5F5;
            color: var(--error);
            padding: 15px 20px;
            border-radius: 16px;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 25px;
            border-left: 4px solid var(--error);
            display: none;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }
        .shake { animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade { animation: fadeIn 0.6s ease forwards; }
    </style>
</head>
<body class="hold-transition light-skin theme-primary">
    <!-- Loader -->
    <div id="loader"></div>

    <div class="wrapper">
        <div class="login-container animate-fade">
            <div class="login-form-side">
                <div class="brand-logo">
                    <img src="{{ asset('images/logo-pwa.png') }}" alt="{{ config('app.name') }}">
                </div>

                <div class="header-content">
                    <h2>{{ __('Sign In') }}</h2>
                    <p>{{ __('Enter your credentials to access the laboratory panel') }}</p>
                </div>

                <form id="login-form" autocomplete="off">
                    @csrf
                    <div class="alert-error" id="error-message"></div>

                    <div class="form-group">
                        <label class="form-label" for="email">{{ __('Email Address') }}*</label>
                        <input type="email" id="email" name="email" class="form-control-custom" 
                               placeholder="mail@example.com" value="{{ old('email') }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="password">{{ __('Password') }}*</label>
                        <input type="password" id="password" name="password" class="form-control-custom" 
                               placeholder="{{ __('Min. 8 characters') }}" required>
                    </div>

                    <div class="options-row">
                        <label class="remember-me">
                            <input type="checkbox" name="remember" id="remember">
                            <span>{{ __('Keep me logged in') }}</span>
                        </label>
                    </div>

                    <button type="submit" class="btn-primary-custom" id="btn-submit">
                        {{ __('Sign In') }}
                    </button>
                </form>
            </div>

            <div class="login-side-image">
                <div class="animate-fade" style="animation-delay: 0.2s;">
                    <h1>Advanced Clinical Laboratory Management</h1>
                    <p>Digitalizing diagnostics with precision, speed, and professional reporting standards.</p>
                    <div class="branding-icon">
                        <i class="fas fa-microscope"></i>
                    </div>
                </div>
            </div>
        </div>
        <!-- Placeholder for theme JS to prevent initialization errors -->
        <div class="multinav-scroll" style="display: none;"></div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
    <script src="{{ asset('js/template.js') }}"></script>
    
    <script>
        // Register PWA Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register("{{ asset('sw.js') }}").then(function(registration) {
                    console.log('ServiceWorker registration successful with scope: ', registration.scope);
                }, function(err) {
                    console.log('ServiceWorker registration failed: ', err);
                });
            });
        }

        $(document).ready(function() {
            $.ajaxSetup({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });

            $('#login-form').on('submit', function(e) {
                e.preventDefault();
                let btn = $('#btn-submit');
                let errorBox = $('#error-message');
                
                btn.html('<i class="fas fa-circle-notch fa-spin"></i> {{ __("Authenticating...") }}').prop('disabled', true);
                errorBox.fadeOut();

                $.ajax({
                    url: "{{ route('login.post') }}",
                    type: "POST",
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) {
                            btn.html('<i class="fas fa-check"></i> {{ __("Success!") }}');
                            setTimeout(() => {
                                window.location.href = response.redirect;
                            }, 500);
                        }
                    },
                    error: function(xhr) {
                        btn.html('{{ __("Sign In") }}').prop('disabled', false);
                        let msg = xhr.responseJSON?.message || '{{ __("Login failed. Please check your credentials.") }}';
                        errorBox.text(msg).fadeIn();
                        
                        $('.login-form-side').addClass('shake');
                        setTimeout(() => $('.login-form-side').removeClass('shake'), 500);
                    }
                });
            });
        });
    </script>
</body>
</html>
