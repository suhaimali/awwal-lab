<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo-pwa.png') }}">

    <title>{{ config('app.name', 'Awwal Lab') }} @yield('title')</title>
    
    <!-- PWA -->
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <meta name="theme-color" content="#0052cc">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-pwa.png') }}">
    
	<!-- Vendors Style-->
	<link rel="stylesheet" href="{{ asset('css/vendors_css.css') }}?v=2">
	  
		<!-- Style-->
		<link rel="stylesheet" href="{{ asset('css/style.css') }}?v=2">
		<link rel="stylesheet" href="{{ asset('css/skin_color.css') }}?v=2">
		<link rel="stylesheet" href="{{ asset('css/custom.css') }}?v=3">
		@stack('styles')
    
    <!-- Icon CDNs to fix 404/CORS errors -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.2.0/css/material-design-iconic-font.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@2.0.46/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
     
  </head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	<div class="wrapper">
	<div id="loader"></div>
    @include('inc.header')
    @include('inc.sidebar')
    @yield('content')
    @include('inc.footer')
    <script src="{{ asset('js/vendors.min.js') }}"></script>
	<script src="{{ asset('js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('assets/icons/feather-icons/feather.min.js') }}"></script>
	
	<script src="{{ asset('assets/vendor_components/apexcharts-bundle/dist/apexcharts.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/1.0.2/Chart.min.js"></script>
	
	<!-- Doclinic App -->
	<script src="{{ asset('js/template.js') }}"></script>
	
    <script>
        function updateClock() {
            const now = new Date();
            const timeOptions = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true };
            const dateOptions = { weekday: 'long', year: 'numeric', month: 'short', day: 'numeric' };
            
            const timeString = now.toLocaleTimeString('en-US', timeOptions);
            const dateString = now.toLocaleDateString('en-US', dateOptions);
            
            if(document.getElementById('header-live-time')) {
                document.getElementById('header-live-time').textContent = timeString;
                document.getElementById('header-live-date').textContent = dateString;
            }


        }
        setInterval(updateClock, 1000);
        updateClock();

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
    </script>
    
    @stack('scripts')
</body>
</html>
