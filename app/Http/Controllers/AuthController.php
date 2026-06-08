<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Max failed attempts before lockout.
     */
    protected int $maxAttempts = 5;

    /**
     * Lockout duration in seconds (15 minutes).
     */
    protected int $decaySeconds = 900;

    // ─────────────────────────────────────────────────────────────────────────
    // SHOW LOGIN FORM
    // ─────────────────────────────────────────────────────────────────────────

    public function showLogin(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        // Pass any lockout info to the view
        $throttleKey = $this->throttleKey($request);
        $lockedOut   = RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts);
        $retryAfter  = $lockedOut ? RateLimiter::availableIn($throttleKey) : 0;

        return view('auth.login', compact('lockedOut', 'retryAfter'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HANDLE LOGIN
    // ─────────────────────────────────────────────────────────────────────────

    public function login(Request $request)
    {
        // 1. Basic input validation
        $request->validate([
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:1'],
        ]);

        $throttleKey = $this->throttleKey($request);

        // 2. Check rate limiter – block if too many attempts
        if (RateLimiter::tooManyAttempts($throttleKey, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);

            $this->logSecurityEvent($request, 'login_locked_out', [
                'email'       => $request->email,
                'retry_after' => $seconds,
            ]);

            $message = "Too many login attempts. Please try again in {$minutes} minute(s).";

            if ($request->expectsJson()) {
                return response()->json([
                    'success'     => false,
                    'message'     => $message,
                    'locked_out'  => true,
                    'retry_after' => $seconds,
                ], 429);
            }

            throw ValidationException::withMessages([
                'email' => $message,
            ]);
        }

        // 3. Attempt authentication
        $credentials = $request->only('email', 'password');
        $remember    = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            // ✅ Success – regenerate session to prevent session fixation
            $request->session()->regenerate();

            // Clear any failed attempts
            RateLimiter::clear($throttleKey);

            // Log successful login
            $this->logSecurityEvent($request, 'login_success', [
                'email'   => $request->email,
                'user_id' => Auth::id(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success'  => true,
                    'redirect' => route('dashboard'),
                ]);
            }

            return redirect()->intended(route('dashboard'));
        }

        // 4. ❌ Failed – increment rate limiter
        RateLimiter::hit($throttleKey, $this->decaySeconds);
        $remaining = $this->maxAttempts - RateLimiter::attempts($throttleKey);

        $this->logSecurityEvent($request, 'login_failed', [
            'email'          => $request->email,
            'attempts_left'  => max(0, $remaining),
        ]);

        // Build a message that reveals remaining attempts (helpful UX, still safe)
        if ($remaining > 0) {
            $message = "Invalid email or password. {$remaining} attempt(s) remaining.";
        } else {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            $message = "Too many login attempts. Account locked for {$minutes} minute(s).";
        }

        if ($request->expectsJson()) {
            return response()->json([
                'success'        => false,
                'message'        => $message,
                'locked_out'     => $remaining <= 0,
                'retry_after'    => $remaining <= 0 ? RateLimiter::availableIn($throttleKey) : 0,
                'attempts_left'  => max(0, $remaining),
            ], 401);
        }

        return back()
            ->withErrors(['email' => $message])
            ->withInput($request->only('email', 'remember'));
    }

    // ─────────────────────────────────────────────────────────────────────────
    // LOGOUT
    // ─────────────────────────────────────────────────────────────────────────

    public function logout(Request $request)
    {
        $userId = Auth::id();
        $email  = Auth::user()?->email;

        Auth::logout();

        // Invalidate session and regenerate CSRF token
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $this->logSecurityEvent($request, 'logout', [
            'user_id' => $userId,
            'email'   => $email,
        ]);

        return redirect('/login');
    }

    // ─────────────────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    /**
     * Build a unique throttle key per email + IP combination.
     * Using both prevents username enumeration attacks and IP spoofing.
     */
    protected function throttleKey(Request $request): string
    {
        return 'login|' . Str::lower($request->input('email', '')) . '|' . $request->ip();
    }

    /**
     * Log security-relevant events to the Laravel log.
     * Useful for intrusion detection.
     */
    protected function logSecurityEvent(Request $request, string $event, array $context = []): void
    {
        Log::channel('stack')->info("[SECURITY] {$event}", array_merge([
            'ip'         => $request->ip(),
            'user_agent' => $request->userAgent(),
            'timestamp'  => now()->toIso8601String(),
        ], $context));
    }
}
