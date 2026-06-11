@extends('layouts.auth')

@section('title', 'Login - SUHAIM SOFT LAB')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
@endsection

@section('content')
<div class="login-container">
    <div class="login-card">
        <a href="/" class="back-btn" title="Back to Home">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div class="brand-header">
            <div class="brand-icon">
                <i class="fa-solid fa-flask"></i>
            </div>
            <h1>SUHAIM SOFT LAB</h1>
            <p>Advanced Laboratory Management System</p>
        </div>

        {{-- Lockout Banner --}}
        @if($lockedOut)
        <div class="lockout-alert" id="lockout-alert">
            <i class="fa-solid fa-shield-halved"></i>
            <div class="lockout-text">
                <strong>Account Temporarily Locked</strong>
                <span>Too many failed attempts. Try again in <span id="lockout-timer">{{ $retryAfter }}</span>s.</span>
            </div>
        </div>
        @endif

        {{-- Validation / Error Alert --}}
        <div class="error-alert {{ $errors->any() || session('error') ? 'show' : '' }}" id="error-alert">
            <i class="fa-solid fa-circle-exclamation" style="margin-top: 2px;"></i>
            <span id="error-message">
                {{ $errors->first('email') ?? session('error') ?? 'Invalid credentials provided.' }}
            </span>
        </div>

        {{-- Attempts Warning --}}
        <div class="attempts-alert" id="attempts-alert" style="display:none;">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span id="attempts-message"></span>
        </div>

        <form id="login-form" method="POST" action="{{ route('login.post') }}" autocomplete="off" novalidate>
            @csrf
            <div class="form-group">
                <label class="form-label" for="email">Email Address</label>
                <div class="input-group">
                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-input"
                        placeholder="Enter your email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        {{ $lockedOut ? 'disabled' : '' }}
                     autocomplete="off">
                    <i class="fa-regular fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label" for="password">Password</label>
                <div class="input-group">
                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-input"
                        placeholder="••••••••"
                        required
                        {{ $lockedOut ? 'disabled' : '' }}
                     autocomplete="off">
                    <i class="fa-solid fa-lock input-icon"></i>
                    <button type="button" class="btn-toggle-password" id="toggle-password" {{ $lockedOut ? 'disabled' : '' }}>
                        <i class="fa-regular fa-eye" id="toggle-icon"></i>
                    </button>
                </div>
            </div>

            <div class="form-options">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="remember" id="remember" {{ $lockedOut ? 'disabled' : '' }}>
                    <span>Remember me</span>
                </label>
                <div class="security-badge" title="Login is protected against brute-force attacks">
                    <i class="fa-solid fa-shield-check"></i> Secured
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btn-submit" {{ $lockedOut ? 'disabled' : '' }}>
                <span id="btn-text">{{ $lockedOut ? 'Account Locked' : 'Sign In' }}</span>
                <i class="fa-solid {{ $lockedOut ? 'fa-lock' : 'fa-arrow-right-to-bracket' }}" id="btn-icon"></i>
            </button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} SUHAIM SOFT. All rights reserved.
            <div class="security-note">
                <i class="fa-solid fa-shield-halved"></i> Protected &bull; Max {{ env('LOGIN_MAX_ATTEMPTS', 5) }} attempts
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        // Pass server-side lockout state to JS
        window._authConfig = {
            lockedOut:   {{ $lockedOut ? 'true' : 'false' }},
            retryAfter:  {{ $retryAfter }},
        };
    </script>
    <script src="{{ asset('js/auth.js') }}"></script>
@endsection
