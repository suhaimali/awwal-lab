@extends('layouts.auth')

@section('title', 'Login - Awwal Lab')

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
            <h1>Awwal Lab</h1>
            <p>Advanced Laboratory Management System</p>
        </div>

        <div class="error-alert" id="error-alert">
            <i class="fa-solid fa-circle-exclamation" style="margin-top: 2px;"></i>
            <span id="error-message">Invalid credentials provided.</span>
        </div>

        <form id="login-form" action="{{ route('login.post') }}" autocomplete="off" novalidate>
            @csrf
            <div class="form-group">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <input type="email" name="email" id="email" class="form-input" placeholder="Enter your email" required autofocus>
                    <i class="fa-regular fa-envelope input-icon"></i>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" name="password" id="password" class="form-input" placeholder="••••••••" required>
                    <i class="fa-solid fa-lock input-icon"></i>
                    <button type="button" class="btn-toggle-password" id="toggle-password">
                        <i class="fa-regular fa-eye" id="toggle-icon"></i>
                    </button>
                </div>
            </div>

            <div class="form-options">
                <label class="checkbox-wrapper">
                    <input type="checkbox" name="remember" id="remember">
                    <span>Remember me</span>
                </label>
            </div>

            <button type="submit" class="btn-submit" id="btn-submit">
                <span id="btn-text">Sign In</span>
                <i class="fa-solid fa-arrow-right-to-bracket" id="btn-icon"></i>
            </button>
        </form>

        <div class="footer-text">
            &copy; {{ date('Y') }} Awwal Lab. All rights reserved.
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ asset('js/auth.js') }}"></script>
@endsection
