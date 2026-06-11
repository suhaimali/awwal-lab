$(document).ready(function () {

    // ─────────────────────────────────────────────────────────────────
    // PASSWORD TOGGLE
    // ─────────────────────────────────────────────────────────────────
    $('#toggle-password').on('click', function () {
        const input = $('#password');
        const icon  = $('#toggle-icon');
        const isPassword = input.attr('type') === 'password';
        input.attr('type', isPassword ? 'text' : 'password');
        icon.toggleClass('fa-eye', !isPassword).toggleClass('fa-eye-slash', isPassword);
    });

    // ─────────────────────────────────────────────────────────────────
    // LOCKOUT COUNTDOWN (server-driven on page load)
    // ─────────────────────────────────────────────────────────────────
    const cfg = window._authConfig || {};
    if (cfg.lockedOut && cfg.retryAfter > 0) {
        startLockoutCountdown(cfg.retryAfter);
    }

    function startLockoutCountdown(seconds) {
        let remaining = seconds;
        const timerEl   = $('#lockout-timer');
        disableForm(true);

        const interval = setInterval(function () {
            remaining--;
            timerEl.text(remaining > 0 ? remaining + 's' : 'Unlocking…');

            if (remaining <= 0) {
                clearInterval(interval);
                disableForm(false);
                $('#lockout-alert').fadeOut(400);
                $('#btn-text').text('Sign In');
                $('#btn-icon').removeClass('fa-lock').addClass('fa-arrow-right-to-bracket');
            }
        }, 1000);
    }

    function disableForm(state) {
        $('#email, #password, #remember, #btn-submit, #toggle-password').prop('disabled', state);
    }

    // ─────────────────────────────────────────────────────────────────
    // FORM SUBMIT — AJAX with proper JSON Accept header
    // ─────────────────────────────────────────────────────────────────
    $('#login-form').on('submit', function (e) {
        e.preventDefault();

        const form          = $(this);
        const btn           = $('#btn-submit');
        const btnText       = $('#btn-text');
        const btnIcon       = $('#btn-icon');
        const errorAlert    = $('#error-alert');
        const attemptsAlert = $('#attempts-alert');

        // Reset state
        errorAlert.removeClass('show');
        attemptsAlert.hide();
        btn.prop('disabled', true);
        btnText.text('Authenticating…');
        btnIcon.removeClass('fa-arrow-right-to-bracket').addClass('fa-circle-notch fa-spin');

        // Collect form data — .serialize() correctly includes checked checkboxes
        const formData = form.serialize();

        $.ajax({
            url:      form.attr('action'),
            type:     'POST',
            data:     formData,
            dataType: 'json',
            // ✅ FIX: Must send Accept: application/json so Laravel's
            //    expectsJson() returns true and returns JSON (not a redirect).
            //    Without this, Auth::attempt() runs but returns a 302 redirect
            //    response which AJAX treats as an error — and remember-me cookie
            //    is never committed to the browser properly.
            headers: {
                'Accept':        'application/json',
                'X-CSRF-TOKEN':  $('meta[name="csrf-token"]').attr('content'),
            },

            success: function (res) {
                if (res.success) {
                    // ✅ Success — show feedback then redirect
                    btn.addClass('success');
                    btnText.text('Access Granted');
                    btnIcon.removeClass('fa-circle-notch fa-spin').addClass('fa-check');
                    // Small delay so user sees the green button before redirect
                    setTimeout(function () {
                        window.location.href = res.redirect;
                    }, 600);
                } else {
                    // Should not reach here on 200, but handle gracefully
                    resetButton();
                    showError(res.message || 'Login failed.');
                }
            },

            error: function (xhr) {
                const res = xhr.responseJSON || {};

                resetButton();

                // ── Rate limited / locked out (429) ──────────────────────
                if (xhr.status === 429 || res.locked_out) {
                    let secs = res.retry_after || 0;
                    if (!secs && xhr.getResponseHeader('Retry-After')) {
                        secs = parseInt(xhr.getResponseHeader('Retry-After'), 10);
                    }
                    secs = secs || 900;

                    btn.prop('disabled', true);
                    btnIcon.removeClass('fa-arrow-right-to-bracket').addClass('fa-lock');
                    btnText.text('Account Locked');

                    // Inject or update lockout banner
                    if ($('#lockout-alert').length === 0) {
                        const banner =
                            '<div class="lockout-alert" id="lockout-alert">' +
                                '<i class="fa-solid fa-shield-halved"></i>' +
                                '<div class="lockout-text">' +
                                    '<strong>Account Temporarily Locked</strong>' +
                                    '<span>Too many failed attempts. Try again in ' +
                                        '<span id="lockout-timer">' + secs + 's</span>.' +
                                    '</span>' +
                                '</div>' +
                            '</div>';
                        // ✅ FIX: use .form-heading instead of .brand-header
                        $('.form-heading').after(banner);
                    } else {
                        $('#lockout-alert').show();
                        $('#lockout-timer').text(secs + 's');
                    }

                    startLockoutCountdown(secs);
                    shakeForm();
                    return;
                }

                // ── Validation errors (422) ───────────────────────────────
                if (xhr.status === 422) {
                    const errors = res.errors || {};
                    const first  = errors.email || errors.password || [];
                    const msg    = Array.isArray(first) ? first[0] : (res.message || 'Validation failed.');
                    showError(msg);
                    shakeForm();
                    return;
                }

                // ── Other failures (401 etc.) ─────────────────────────────
                const msg = res.message || 'Invalid email or password.';
                showError(msg);

                // Show remaining attempts warning badge
                if (typeof res.attempts_left !== 'undefined' && res.attempts_left <= 3 && res.attempts_left > 0) {
                    $('#attempts-message').text(
                        '⚠ Warning: ' + res.attempts_left + ' attempt(s) left before account is locked for 15 minutes.'
                    );
                    attemptsAlert.show();
                }

                shakeForm();
            }
        });
    });

    // ─────────────────────────────────────────────────────────────────
    // HELPERS
    // ─────────────────────────────────────────────────────────────────
    function resetButton() {
        $('#btn-submit').prop('disabled', false);
        $('#btn-text').text('Sign In');
        $('#btn-icon').removeClass('fa-circle-notch fa-spin fa-lock').addClass('fa-arrow-right-to-bracket');
    }

    function showError(msg) {
        $('#error-message').text(msg);
        $('#error-alert').addClass('show');
    }

    // ✅ FIX: shake the right-panel inner card, not .login-card (old class)
    function shakeForm() {
        const card  = $('.auth-right-inner');
        const steps = [-10, 10, -8, 8, -4, 4, 0];
        steps.forEach(function (x, i) {
            setTimeout(function () {
                card.css('transform', 'translateX(' + x + 'px)');
            }, i * 50);
        });
        // Reset transform after shake
        setTimeout(function () { card.css('transform', ''); }, steps.length * 50 + 100);
    }

    // ─────────────────────────────────────────────────────────────────
    // HIDE ERROR ON INPUT CHANGE
    // ─────────────────────────────────────────────────────────────────
    $('.form-input').on('input', function () {
        $('#error-alert').removeClass('show');
        $('#attempts-alert').hide();
    });

});
