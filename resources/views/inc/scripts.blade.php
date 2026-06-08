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
            }, 3000); // 3 seconds loading delay
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
    </script>

    @stack('scripts')
    <script>
        // Security: Force clear ALL local storage to ensure 100% MySQL security and clean out any old data.
        if (localStorage.length > 0) {
            localStorage.clear();
            console.log('Completely cleared all local storage keys.');
        }
    </script>
