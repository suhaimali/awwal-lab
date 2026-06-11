    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Select2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Moment -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>

    <script>
        // ── Hide loader on page load
        window.addEventListener('load', () => {
            setTimeout(() => {
                document.getElementById('aw-loader').classList.add('hidden');
            }, 100); // 1 second loading delay
        });

        // ── Sidebar toggle (desktop collapse)
        function toggleSidebar() {
            if (window.innerWidth <= 991) {
                document.body.classList.toggle('sidebar-open');
            } else {
                document.body.classList.toggle('sidebar-collapsed');
                $.ajax({
                    url: '/sidebar-toggle',
                    type: 'POST',
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });
            }
        }
        function closeSidebar() {
            document.body.classList.remove('sidebar-open');
        }

        // ── Live clock
        function updateClock() {
            const now = new Date();
            const t = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
            const d = now.toLocaleDateString('en-US', { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric' });
            const te = document.getElementById('header-live-time');
            const de = document.getElementById('header-live-date');
            if (te) { te.textContent = t; de.textContent = d; }
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ── PWA Service Worker
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("{{ asset('sw.js') }}").catch(() => {});
            });
        }

        // ── CSRF setup for AJAX
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        // ── Session Timeout Warning
        // Session lifetime = 120 minutes. Warn at 118 min, expire at 120 min.
        (function() {
            const SESSION_MINUTES = {{ config('session.lifetime', 120) }};
            const WARN_BEFORE_MS  = 2 * 60 * 1000;  // warn 2 minutes before expiry
            const SESSION_MS      = SESSION_MINUTES * 60 * 1000;
            let warningShown = false;
            let sessionTimer, expireTimer;

            function resetTimers() {
                clearTimeout(sessionTimer);
                clearTimeout(expireTimer);
                warningShown = false;

                // Show warning 2 minutes before session expires
                sessionTimer = setTimeout(function() {
                    if (warningShown) return;
                    warningShown = true;
                    Swal.fire({
                        title: '<i class="fa fa-clock" style="color:#f59e0b;"></i> Session Expiring Soon',
                        html: 'Your session will expire in <strong>2 minutes</strong>.<br>Do you want to stay logged in?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: '<i class="fa fa-refresh"></i> Stay Logged In',
                        cancelButtonText: 'Logout Now',
                        confirmButtonColor: '#1a56db',
                        cancelButtonColor: '#dc2626',
                        timer: 120000,
                        timerProgressBar: true,
                        allowOutsideClick: false,
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            // Ping server to keep session alive
                            $.get('/dashboard-stats').done(function() {
                                resetTimers();
                                Swal.fire({
                                    title: 'Session Extended',
                                    text: 'You are still logged in.',
                                    icon: 'success',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }).fail(function() {
                                window.location.href = '/login';
                            });
                        } else {
                            window.location.href = '/logout';
                        }
                    });
                }, SESSION_MS - WARN_BEFORE_MS);

                // Hard redirect when session expires
                expireTimer = setTimeout(function() {
                    window.location.href = '/login';
                }, SESSION_MS);
            }

            // Start timers on page load
            resetTimers();

            // Reset timers on any user activity (clicks, key presses)
            $(document).on('click keydown', function() {
                if (!warningShown) resetTimers();
            });
        })();
    </script>

    @stack('scripts')
