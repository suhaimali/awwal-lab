<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="SUHAIM SOFT LAB - Advanced Laboratory Management System">
    <meta name="keywords" content="lims dashboard, patient records, diagnostic reports, laboratory software, clinical results, suhaim soft lab">
    <meta name="robots" content="noindex, nofollow">
    <meta name="author" content="SUHAIM SOFT LAB">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <title>{{ config('app.name', 'SUHAIM SOFT LAB') }} @yield('title')</title>

    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0284c7">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <!-- Select2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <!-- jsPDF -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>

    @stack('styles')

    @include('inc.styles')
</head>
<body class="{{ auth()->check() && auth()->user()->sidebar_collapsed ? 'sidebar-collapsed' : '' }}">
    <!-- Loader -->
    <div id="aw-loader"><div class="aw-spinner"></div></div>
    <!-- Mobile overlay -->
    <div id="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- ── SIDEBAR ── -->
    @include('inc.sidebar')

    <!-- ── HEADER ── -->
    @include('inc.header')

    <!-- ── MAIN CONTENT ── -->
    <main id="awlab-main">
        @yield('content')
    </main>

    <!-- ── FOOTER ── -->
    @include('inc.footer')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @include('inc.scripts')

    <!-- Custom Scripts -->
    <script>
        $(document).ready(function() {
            // Global CSRF setup for AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

        // Global fix for Enter key form submission in modals/cards using AJAX buttons
        document.addEventListener('submit', function(e) {
            let form = e.target;
            let container = form.closest('.modal') || form.closest('.aw-card');
            if (container) {
                let btn = container.querySelector('button.btn-aw-primary, button.btn-primary');
                if (btn && btn.type === 'button') {
                    e.preventDefault(); // Stop native submission
                    if (form.checkValidity()) {
                        btn.click();
                    } else {
                        form.reportValidity();
                    }
                }
            }
        }, true);

        // Global fix for HTML5 validation on AJAX save/update buttons
        document.addEventListener('click', function(e) {
            let target = e.target.closest('button[type="button"]');
            if (target && target.id) {
                let formId = null;
                if (target.id.startsWith('btn-save-')) {
                    formId = target.id.replace('btn-save-', 'form-add-');
                } else if (target.id.startsWith('btn-update-')) {
                    formId = target.id.replace('btn-update-', 'form-edit-');
                }
                
                if (formId) {
                    let form = document.getElementById(formId);
                    if (form) {
                        if (!form.checkValidity()) {
                            e.preventDefault();
                            e.stopPropagation();
                            form.reportValidity();
                        }
                    }
                }
            }
        }, true);
    </script>
</body>
</html>
