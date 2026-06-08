$(document).ready(function() {
    // Password Toggle
    $('#toggle-password').on('click', function() {
        const passwordInput = $('#password');
        const icon = $('#toggle-icon');
        
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            icon.removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            passwordInput.attr('type', 'password');
            icon.removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });

    // Form Submit
    $('#login-form').on('submit', function(e) {
        e.preventDefault();
        
        const form = $(this);
        const btn = $('#btn-submit');
        const btnText = $('#btn-text');
        const btnIcon = $('#btn-icon');
        const errorAlert = $('#error-alert');
        
        // Reset state
        errorAlert.removeClass('show');
        btn.prop('disabled', true);
        btnText.text('Authenticating...');
        btnIcon.removeClass('fa-arrow-right-to-bracket').addClass('fa-circle-notch fa-spin');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function(res) {
                if (res.success) {
                    btn.addClass('success');
                    btnText.text('Access Granted');
                    btnIcon.removeClass('fa-circle-notch fa-spin').addClass('fa-check');
                    setTimeout(() => {
                        window.location.href = res.redirect;
                    }, 600);
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                btnText.text('Sign In');
                btnIcon.removeClass('fa-circle-notch fa-spin').addClass('fa-arrow-right-to-bracket');
                
                let msg = xhr.responseJSON?.message || 'Invalid email or password.';
                $('#error-message').text(msg);
                errorAlert.addClass('show');
                
                // Shake animation
                const card = $('.login-card');
                card.css('transform', 'translateX(-10px)');
                setTimeout(() => card.css('transform', 'translateX(10px)'), 50);
                setTimeout(() => card.css('transform', 'translateX(-10px)'), 100);
                setTimeout(() => card.css('transform', 'translateX(10px)'), 150);
                setTimeout(() => card.css('transform', 'translateX(0)'), 200);
            }
        });
    });

    // Hide error on input
    $('.form-input').on('input', function() {
        $('#error-alert').removeClass('show');
    });
});
