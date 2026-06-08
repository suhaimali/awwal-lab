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
    // LOCKOUT COUNTDOWN (server-driven)
    // ─────────────────────────────────────────────────────────────────
    const cfg = window._authConfig || {};
    if (cfg.lockedOut && cfg.retryAfter > 0) {
        startLockoutCountdown(cfg.retryAfter);
    }

    function startLockoutCountdown(seconds) {
        let remaining = seconds;
        const timerEl = $('#lockout-timer');
        const submitBtn = $('#btn-submit');

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
    // FORM SUBMIT
    // ─────────────────────────────────────────────────────────────────
    $('#login-form').on('submit', function (e) {
        e.preventDefault();

        const form        = $(this);
        const btn         = $('#btn-submit');
        const btnText     = $('#btn-text');
        const btnIcon     = $('#btn-icon');
        const errorAlert  = $('#error-alert');
        const attemptsAlert = $('#attempts-alert');

        // Reset state
        errorAlert.removeClass('show');
        attemptsAlert.hide();
        btn.prop('disabled', true);
        btnText.text('Authenticating…');
        btnIcon.removeClass('fa-arrow-right-to-bracket').addClass('fa-circle-notch fa-spin');

        $.ajax({
            url:  form.attr('action'),
            type: 'POST',
            data: form.serialize(),

            success: function (res) {
                if (res.success) {
                    // ✅ Success
                    btn.addClass('success');
                    btnText.text('Access Granted');
                    btnIcon.removeClass('fa-circle-notch fa-spin').addClass('fa-check');
                    setTimeout(() => { window.location.href = res.redirect; }, 600);
                }
            },

            error: function (xhr) {
                const res = xhr.responseJSON || {};

                btnText.text('Sign In');
                btnIcon.removeClass('fa-circle-notch fa-spin').addClass('fa-arrow-right-to-bracket');

                // ── Locked out (429 or locked_out flag) ──────────────────
                if (xhr.status === 429 || res.locked_out) {
                    const secs = res.retry_after || 900;
                    btn.prop('disabled', true);
                    btnIcon.removeClass('fa-arrow-right-to-bracket').addClass('fa-lock');
                    btnText.text('Account Locked');

                    // Show lockout banner
                    if ($('#lockout-alert').length === 0) {
                        const banner = `
                            <div class="lockout-alert" id="lockout-alert">
                                <i class="fa-solid fa-shield-halved"></i>
                                <div class="lockout-text">
                                    <strong>Account Temporarily Locked</strong>
                                    <span>Too many failed attempts. Try again in <span id="lockout-timer">${secs}s</span>.</span>
                                </div>
                            </div>`;
                        $('.brand-header').after(banner);
                    } else {
                        $('#lockout-alert').show();
                        $('#lockout-timer').text(secs + 's');
                    }

                    startLockoutCountdown(secs);
                    shakeCard();
                    return;
                }

                // ── Normal failed attempt ────────────────────────────────
                btn.prop('disabled', false);

                const msg = res.message || 'Invalid email or password.';
                $('#error-message').text(msg);
                errorAlert.addClass('show');

                // Show remaining attempts warning
                if (typeof res.attempts_left !== 'undefined' && res.attempts_left <= 3 && res.attempts_left > 0) {
                    $('#attempts-message').text(
                        `⚠ Warning: ${res.attempts_left} attempt(s) left before account is locked for 15 minutes.`
                    );
                    attemptsAlert.show();
                }

                shakeCard();
            }
        });
    });

    // ─────────────────────────────────────────────────────────────────
    // SHAKE ANIMATION
    // ─────────────────────────────────────────────────────────────────
    function shakeCard() {
        const card = $('.login-card');
        const steps = [-10, 10, -8, 8, -4, 4, 0];
        steps.forEach((x, i) => {
            setTimeout(() => card.css('transform', `translateX(${x}px)`), i * 50);
        });
    }

    // ─────────────────────────────────────────────────────────────────
    // HIDE ERROR ON INPUT CHANGE
    // ─────────────────────────────────────────────────────────────────
    $('.form-input').on('input', function () {
        $('#error-alert').removeClass('show');
        $('#attempts-alert').hide();
    });

});
