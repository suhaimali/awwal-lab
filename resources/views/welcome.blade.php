<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUHAIM LAB | Digital Healthcare Transformation</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-blue: #0d6efd;
            --light-blue: #e0f2fe;
            --dark-blue: #082f49;
            --accent-blue: #38bdf8;
            --white: #ffffff;
            --bg-gray: #f8fafc;
            --text-dark: #334155;
            --text-light: #64748b;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            background-color: var(--bg-gray);
            overflow-x: hidden;
        }

        /* 3D Elements & Shadows */
        .card-3d {
            background: var(--white);
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(13, 110, 253, 0.1), inset 0 1px 0 rgba(255, 255, 255, 0.8);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            position: relative;
            overflow: hidden;
        }

        .card-3d:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 20px 40px rgba(13, 110, 253, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.8);
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 1rem 0;
            transition: all 0.3s ease;
        }
        
        .navbar-brand {
            font-weight: 800;
            color: var(--primary-blue) !important;
            font-size: 1.5rem;
            letter-spacing: 1px;
        }

        .nav-link {
            font-weight: 600;
            color: var(--text-dark) !important;
            margin: 0 10px;
            position: relative;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-blue) !important;
        }

        .btn-3d {
            background: linear-gradient(135deg, #38bdf8 0%, #0d6efd 100%);
            color: white !important;
            font-weight: 700;
            border: none;
            border-radius: 50px;
            padding: 10px 30px;
            box-shadow: 0 8px 20px rgba(13, 110, 253, 0.3), inset 0 -3px 0 rgba(0,0,0,0.1);
            transition: all 0.2s ease;
        }

        .btn-3d:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(13, 110, 253, 0.4), inset 0 -3px 0 rgba(0,0,0,0.1);
        }

        .btn-3d:active {
            transform: translateY(2px);
            box-shadow: 0 2px 10px rgba(13, 110, 253, 0.2), inset 0 -1px 0 rgba(0,0,0,0.1);
        }

        /* Hero Section */
        .hero {
            padding: 220px 0 160px 0;
            background: linear-gradient(-45deg, var(--light-blue), #ffffff, #e0f2fe, var(--accent-blue));
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            position: relative;
            overflow: hidden;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .hero::before {
            content: '';
            position: absolute;
            top: -150px;
            right: -100px;
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, rgba(255, 255, 255, 0.4) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
            animation: floatCircle 8s ease-in-out infinite;
        }

        .hero::after {
            content: '';
            position: absolute;
            bottom: -150px;
            left: -100px;
            width: 400px;
            height: 400px;
            background: radial-gradient(circle, rgba(13, 110, 253, 0.1) 0%, transparent 70%);
            border-radius: 50%;
            z-index: 0;
            animation: floatCircle 10s ease-in-out infinite reverse;
        }

        @keyframes floatCircle {
            0% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-30px) scale(1.05); }
            100% { transform: translateY(0px) scale(1); }
        }

        .hero-content {
            position: relative;
            z-index: 1;
            text-align: center;
        }

        .hero h1, .hero-title {
            font-weight: 800;
            font-size: 5rem;
            line-height: 1.2;
            color: var(--dark-blue);
            margin-bottom: 30px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
        }

        .hero p, .hero-subtitle {
            font-size: 1.5rem;
            color: var(--text-light);
            margin-bottom: 3rem;
            line-height: 1.8;
            max-width: 900px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Welcome Section Animation */
        .welcome-section {
            background: linear-gradient(-45deg, #020617, #1e40af, #0f172a, #0369a1);
            background-size: 400% 400%;
            animation: welcomeDarkGradient 15s ease infinite;
            position: relative;
            overflow: hidden;
            color: white;
        }

        .welcome-section::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: url('data:image/svg+xml;utf8,<svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg"><filter id="noiseFilter"><feTurbulence type="fractalNoise" baseFrequency="0.65" numOctaves="3" stitchTiles="stitch"/></filter><rect width="100%" height="100%" filter="url(%23noiseFilter)"/></svg>');
            opacity: 0.05;
            pointer-events: none;
            z-index: 1;
        }

        @keyframes welcomeDarkGradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }



        /* Section Titles */
        .section-title {
            text-align: center;
            margin-bottom: 60px;
        }

        .section-title h2 {
            font-weight: 800;
            color: var(--dark-blue);
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .section-title p {
            color: var(--text-light);
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Process Section */
        .process-section {
            padding: 100px 0;
            background: white;
        }

        .process-step {
            text-align: center;
            padding: 40px 20px;
        }

        .step-number {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #0d6efd, #38bdf8);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            font-weight: 800;
            margin: 0 auto 20px auto;
            box-shadow: 0 10px 20px rgba(13, 110, 253, 0.3);
            border: 5px solid var(--light-blue);
        }

        /* Features Section */
        .features-section {
            padding: 100px 0;
            background: var(--bg-gray);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            background: var(--light-blue);
            color: var(--primary-blue);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 20px;
            transition: all 0.3s ease;
        }

        .card-3d:hover .feature-icon {
            background: var(--primary-blue);
            color: white;
            transform: rotateY(180deg);
        }

        /* Benefits Section */
        .benefits-section {
            padding: 100px 0;
            background: linear-gradient(135deg, var(--dark-blue) 0%, var(--primary-blue) 100%);
            color: white;
        }

        .benefits-section .section-title h2,
        .benefits-section .section-title p {
            color: white;
        }

        .benefit-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
            transition: all 0.3s ease;
        }

        .benefit-card:hover {
            transform: translateY(-10px);
            background: rgba(255, 255, 255, 0.2);
        }

        .benefit-value {
            font-size: 4rem;
            font-weight: 800;
            color: var(--accent-blue);
            margin-bottom: 10px;
            text-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }

        .footer-brand i {
            color: var(--accent-blue);
        }

        /* Floating WhatsApp */
        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 30px;
            right: 30px;
            background-color: #25d366;
            color: white;
            border-radius: 50px;
            font-size: 35px;
            box-shadow: 0px 4px 10px rgba(0,0,0,0.2);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
            border: none;
        }

        .whatsapp-float:hover {
            background-color: #128c7e;
            color: white;
            transform: scale(1.1);
        }

        /* WhatsApp Popup Widget */
        .wa-popup {
            position: fixed;
            bottom: 100px;
            right: 30px;
            width: 320px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            z-index: 1000;
            display: none; /* Hidden by default */
            flex-direction: column;
            overflow: hidden;
            animation: slideUp 0.3s ease forwards;
        }

        .wa-popup-header {
            background: var(--primary-blue);
            color: white;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .wa-popup-header h5 {
            margin: 0;
            font-size: 1.1rem;
            font-weight: bold;
        }

        .wa-popup-body {
            padding: 20px;
            background: #f8fafc;
        }

        .wa-chat-bubble {
            background: white;
            padding: 15px;
            border-radius: 0 15px 15px 15px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            font-size: 0.95rem;
            color: var(--dark-blue);
            font-weight: 500;
            position: relative;
        }

        .wa-chat-bubble::before {
            content: '';
            position: absolute;
            top: 0;
            left: -10px;
            border-width: 0 10px 10px 0;
            border-style: solid;
            border-color: transparent white transparent transparent;
        }

        .wa-popup-footer {
            padding: 15px 20px;
            background: white;
            text-align: center;
            border-top: 1px solid #eee;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Footer */
        .footer {
            background: var(--dark-blue);
            color: white;
            padding: 60px 0 20px 0;
            position: relative;
        }

        .footer .container {
            position: relative;
            z-index: 1;
        }

        .footer-brand {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--accent-blue);
            margin-bottom: 20px;
            display: inline-block;
        }

        .footer p {
            color: rgba(255, 255, 255, 0.7);
            line-height: 1.8;
        }

        .footer-links h5 {
            font-weight: 700;
            margin-bottom: 20px;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links ul li {
            margin-bottom: 10px;
        }

        .footer-links ul li a {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links ul li a:hover {
            color: var(--accent-blue);
            padding-left: 5px;
        }

        .social-icons a {
            color: white;
            background: rgba(255,255,255,0.1);
            width: 40px;
            height: 40px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin-right: 10px;
            transition: all 0.3s ease;
        }

        .social-icons a:hover {
            background: var(--accent-blue);
            transform: translateY(-3px);
        }

        .footer-bottom {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.1);
            text-align: center;
            color: rgba(255,255,255,0.5);
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .hero {
                padding: 120px 0 60px 0;
                text-align: center;
            }
            .hero h1, .hero-title {
                font-size: 2.5rem;
            }
            .hero p, .hero-subtitle {
                font-size: 1.15rem;
            }
            .hero-img {
                margin-top: 40px;
                transform: none;
            }
            .process-arrow {
                display: none !important;
            }
            .process-step {
                margin-bottom: 2rem;
            }
            .section-title h2 {
                font-size: 2rem !important;
            }
            .whatsapp-float {
                width: 50px;
                height: 50px;
                bottom: 20px;
                right: 20px;
                font-size: 28px;
            }
            .wa-popup {
                right: 20px;
                bottom: 80px;
                width: calc(100% - 40px);
            }
            .footer {
                padding: 50px 0 20px 0;
                text-align: center;
            }
            .footer-links {
                margin-top: 30px;
            }
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target=".navbar" data-bs-offset="100">

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fa-solid fa-laptop-medical me-2"></i>SUHAIM LAB</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#welcome">Welcome</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#process">Process</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#features">Features</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#benefits">Benefits</a>
                    </li>
                </ul>
                <div class="d-flex gap-2 mt-3 mt-lg-0 align-items-center">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#enrollModal" class="nav-link fw-bold" style="color: var(--primary-blue) !important;">Enroll Now</a>
                    <a href="{{ route('login') }}" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background: var(--primary-blue); border: none;">Login <i class="fa-solid fa-right-to-bracket ms-1"></i></a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Home) -->
    <section id="home" class="hero">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-10 hero-content">
                    <h1 class="hero-title">Smart, Secure & Efficient<br><span class="text-primary" style="text-shadow: none;">Lab Management</span></h1>
                    <p class="hero-subtitle">
                        Empower your diagnostic center with our cutting-edge Laboratory Information System designed for modern healthcare.
                    </p>
                    <div class="d-flex gap-3 mt-4 justify-content-center flex-wrap">
                        <a href="#welcome" class="btn btn-3d">Learn More <i class="fa fa-arrow-down ms-2"></i></a>
                        <a href="#" data-bs-toggle="modal" data-bs-target="#enrollModal" class="btn btn-outline-primary" style="border-radius: 50px; padding: 10px 30px; font-weight: 700; background: rgba(255,255,255,0.8); backdrop-filter: blur(5px);">Enroll Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section id="welcome" class="py-5 welcome-section">
        <div class="container py-5 text-center" style="position: relative; z-index: 2;">
            <div class="section-title mb-5">
                <h2 style="font-size: 2.5rem; text-transform: uppercase; color: white;">Welcome to Suhaim Lab</h2>
                <p class="fw-bold" style="font-size: 1.25rem; color: var(--accent-blue);">Your Partner in Digital Healthcare Transformation.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <p style="color: rgba(255,255,255,0.85); line-height: 1.8; font-size: 1.15rem; margin-bottom: 20px; font-weight: 300;">
                        In today's fast-paced medical environment, the most valuable resource is time. Administrative tasks and cumbersome paperwork can divert focus from what truly matters: patient care. <strong class="text-white fw-bold">SUHAIM LAB</strong> was founded on a simple principle: to give that time back to healthcare professionals.
                    </p>
                    <p style="color: rgba(255,255,255,0.85); line-height: 1.8; font-size: 1.15rem; font-weight: 300;">
                        Our intelligent Laboratory Information System (LIS) is more than just a digital filing cabinet. It is a powerful, integrated platform designed to streamline your entire workflow, from patient registration to test reports.
                    </p>

                    <div class="mt-5">
                        <div class="d-inline-block px-5 py-4" style="background: rgba(255,255,255,0.1); border-radius: 20px; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(10px);">
                            <h1 class="display-3 fw-bold text-white mb-0"><span id="welcomeCounter">1</span>+</h1>
                            <p class="mb-0 fw-bold text-uppercase mt-2" style="color: var(--accent-blue); letter-spacing: 2px; font-size: 0.9rem;">Diagnostic Centers Powered</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>



    <!-- Process Section -->
    <section id="process" class="process-section">
        <div class="container">
            <div class="section-title mb-5">
                <h2 style="font-size: 2.5rem; text-transform: uppercase;">How It Works</h2>
                <p class="text-muted">A streamlined workflow designed for maximum efficiency.</p>
            </div>
            <div class="row text-center mt-5">
                <div class="col-md-3 mb-4 mb-md-0 position-relative process-step">
                    <div class="process-icon mx-auto">
                        <i class="fa-solid fa-user-plus text-white"></i>
                    </div>
                    <h5 class="fw-bold mt-4">1. Registration</h5>
                    <p class="text-muted">Quick and easy patient entry.</p>
                    <i class="fa-solid fa-arrow-right process-arrow d-none d-md-block"></i>
                </div>
                <div class="col-md-3 mb-4 mb-md-0 position-relative process-step">
                    <div class="process-icon mx-auto">
                        <i class="fa-solid fa-vial text-white"></i>
                    </div>
                    <h5 class="fw-bold mt-4">2. Sample Collection</h5>
                    <p class="text-muted">Barcode tracking for accuracy.</p>
                    <i class="fa-solid fa-arrow-right process-arrow d-none d-md-block"></i>
                </div>
                <div class="col-md-3 mb-4 mb-md-0 position-relative process-step">
                    <div class="process-icon mx-auto">
                        <i class="fa-solid fa-microscope text-white"></i>
                    </div>
                    <h5 class="fw-bold mt-4">3. Processing</h5>
                    <p class="text-muted">Automated machine interfacing.</p>
                    <i class="fa-solid fa-arrow-right process-arrow d-none d-md-block"></i>
                </div>
                <div class="col-md-3 mb-4 mb-md-0 position-relative process-step">
                    <div class="process-icon mx-auto">
                        <i class="fa-solid fa-file-medical text-white"></i>
                    </div>
                    <h5 class="fw-bold mt-4">4. Reporting</h5>
                    <p class="text-muted">Instant delivery via SMS/WhatsApp.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="section-title">
                <h2>Key Features</h2>
                <p>Explore the core functionalities that set our EMR system apart.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card-3d p-4 h-100">
                        <div class="feature-icon"><i class="fa-solid fa-stopwatch"></i></div>
                        <h4>30-Second Test Reports</h4>
                        <p class="text-muted">Generate and finalize complete test reports in under 30 seconds with intelligent templates and normal ranges.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-3d p-4 h-100">
                        <div class="feature-icon"><i class="fa-solid fa-paper-plane"></i></div>
                        <h4>Automated Report Delivery</h4>
                        <p class="text-muted">Instantly and securely send finalized reports directly to the patient's email or WhatsApp, eliminating paper and wait times.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-3d p-4 h-100">
                        <div class="feature-icon"><i class="fa-solid fa-chart-line"></i></div>
                        <h4>Live Dashboard</h4>
                        <p class="text-muted">Staff can manage patient bookings, while doctors see all appointment data in real-time on their dashboard, ensuring perfect sync.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-3d p-4 h-100">
                        <div class="feature-icon"><i class="fa-solid fa-video"></i></div>
                        <h4>Digital Test Tracking</h4>
                        <p class="text-muted">Conduct secure and efficient patient test tracking from anywhere. Our easy-to-use digital LIS provides all the tools you need.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-3d p-4 h-100">
                        <div class="feature-icon"><i class="fa-solid fa-bolt"></i></div>
                        <h4>Fast & Easy to Use</h4>
                        <p class="text-muted">Our system is designed for speed and simplicity, allowing your staff to manage clinic operations with minimal training.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card-3d p-4 h-100">
                        <div class="feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                        <h4>Secure Platform</h4>
                        <p class="text-muted">Built with multiple layers of security to protect your data and ensure strict compliance at all times.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section id="benefits" class="benefits-section">
        <div class="container">
            <div class="section-title">
                <h2>Tangible Results</h2>
                <p>Our platform isn't just about features; it's about delivering real-world benefits that impact your bottom line and patient satisfaction.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-3 col-6">
                    <div class="benefit-card h-100">
                        <div class="benefit-value">100%</div>
                        <h5 class="fw-bold">Time Savings</h5>
                        <p class="mb-0 text-white-50 small">Reduce administrative overhead, allowing more time for what matters most: patient diagnoses.</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="benefit-card h-100">
                        <div class="benefit-value">100%</div>
                        <h5 class="fw-bold">Data Accuracy</h5>
                        <p class="mb-0 text-white-50 small">Our system minimizes data entry errors, ensuring highly accurate and reliable patient records.</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="benefit-card h-100">
                        <div class="benefit-value">100%</div>
                        <h5 class="fw-bold">Revenue Boost</h5>
                        <p class="mb-0 text-white-50 small">Streamline billing and coding to increase revenue collection by an average of 100%.</p>
                    </div>
                </div>
                <div class="col-md-3 col-6">
                    <div class="benefit-card h-100">
                        <div class="benefit-value">100%</div>
                        <h5 class="fw-bold">Patient Satisfaction</h5>
                        <p class="mb-0 text-white-50 small">Faster check-ins and better data access lead to a significant increase in patient satisfaction.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <a href="#" class="footer-brand text-decoration-none"><i class="fa-solid fa-laptop-medical me-2"></i>SUHAIM LAB</a>
                    <p>By automating repetitive tasks, providing actionable insights, and ensuring rock-solid security, we empower you to practice medicine more efficiently and effectively. Join us in building a smarter, more connected future for healthcare.</p>
                    <div class="social-icons mt-4">
                        <a href="#"><i class="fab fa-facebook-f"></i></a>
                        <a href="#"><i class="fab fa-linkedin-in"></i></a>
                        <a href="#"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 offset-lg-2 col-md-4 mb-4 mb-md-0 footer-links">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#process">Process</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#benefits">Benefits</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 col-md-8 footer-links">
                    <h5>Contact Us</h5>
                    <ul class="list-unstyled text-white-50">
                        <li class="mb-3"><i class="fa-solid fa-location-dot me-2" style="color: var(--accent-blue);"></i> Pathappiriyam, Edavanna, Malappuram, Kerala</li>
                        <li class="mb-3"><i class="fa-brands fa-whatsapp me-2" style="color: #25d366;"></i> <a href="https://wa.me/918891479505" target="_blank" class="text-white-50 text-decoration-none">+91 8891 479 505</a></li>
                        <li class="mb-3"><i class="fa-solid fa-envelope me-2" style="color: var(--accent-blue);"></i> info@suhaimsoft.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">&copy; {{ date('Y') }} Awwal Lab. Designed by <a href="https://suhaimsoft.com" target="_blank" style="color: var(--accent-blue); text-decoration: none; font-weight: bold;">Suhaim Soft</a>.</p>
            </div>
        </div>
    </footer>

    <!-- Enroll Now Modal -->
    <div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 15px; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--light-blue), #ffffff); border-radius: 15px 15px 0 0; border-bottom: 1px solid rgba(0,0,0,0.05);">
                    <h5 class="modal-title fw-bold text-dark" id="enrollModalLabel">Enroll in SUHAIM LAB</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted mb-4">Please fill out the form below to request enrollment, and our team will get back to you shortly.</p>
                    <form>
                        <div class="mb-3">
                            <label for="enrollName" class="form-label fw-bold">Full Name</label>
                            <input type="text" class="form-control" id="enrollName" placeholder="Enter your full name" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-3">
                            <label for="enrollPhone" class="form-label fw-bold">Phone Number</label>
                            <input type="tel" class="form-control" id="enrollPhone" placeholder="Enter your phone number" required style="border-radius: 10px;">
                        </div>
                        <div class="mb-4">
                            <label for="enrollMsg" class="form-label fw-bold">Message</label>
                            <textarea class="form-control" id="enrollMsg" rows="3" placeholder="How can we help your lab?" required style="border-radius: 10px;"></textarea>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 fw-bold" style="border-radius: 50px;">Submit Request</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Floating WhatsApp Button -->
    <button onclick="toggleWaPopup()" class="whatsapp-float">
        <i class="fa-brands fa-whatsapp"></i>
    </button>

    <!-- WhatsApp Popup Widget -->
    <div class="wa-popup" id="waPopup">
        <div class="wa-popup-header">
            <div class="d-flex align-items-center">
                <i class="fa-brands fa-whatsapp fs-4 me-2"></i>
                <h5>Suhaim Soft</h5>
            </div>
            <button onclick="toggleWaPopup()" style="background: none; border: none; color: white; font-size: 1.2rem;"><i class="fa-solid fa-xmark"></i></button>
        </div>
        <div class="wa-popup-body">
            <div class="wa-chat-bubble">
                WELCOME TO SUHAIM SOFT<br>HOW CAN I HELP YOU?
            </div>
        </div>
        <div class="wa-popup-footer">
            <a href="https://wa.me/918891479505" target="_blank" class="btn w-100 fw-bold" style="background: #25d366; color: white; border-radius: 50px;">
                <i class="fa-brands fa-whatsapp me-2"></i> Start Chat
            </a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // WhatsApp Popup Toggle
        function toggleWaPopup() {
            var popup = document.getElementById("waPopup");
            if (popup.style.display === "flex") {
                popup.style.display = "none";
            } else {
                popup.style.display = "flex";
            }
        }

        // Animated Counter for Welcome Section
        document.addEventListener("DOMContentLoaded", () => {
            const counter = document.getElementById("welcomeCounter");
            if(counter) {
                let count = 1;
                const target = 100;
                const duration = 2000; // 2 seconds
                const increment = target / (duration / 16); 

                const updateCounter = () => {
                    count += increment;
                    if (count < target) {
                        counter.innerText = Math.ceil(count);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.innerText = target;
                    }
                };
                
                // Start animation with a slight delay
                setTimeout(() => {
                    requestAnimationFrame(updateCounter);
                }, 800);
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>
</html>
