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

        .hero h1 {
            font-weight: 800;
            font-size: 4rem;
            line-height: 1.2;
            color: var(--dark-blue);
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0,0,0,0.05);
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 40px;
            line-height: 1.8;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
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

        /* Footer */
        .footer {
            background: var(--dark-blue);
            color: white;
            padding: 60px 0 20px 0;
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
            .hero h1 {
                font-size: 2.5rem;
            }
            .hero-img {
                margin-top: 40px;
                transform: none;
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
                        <a class="nav-link" href="#about">About</a>
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
                <div class="d-flex">
                    <a href="{{ route('login') }}" class="btn btn-3d">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section (Home) -->
    <section id="home" class="hero">
        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-10 hero-content">
                    <h1 style="font-size: 5rem; margin-bottom: 30px;">Smart, Secure & Efficient<br><span class="text-primary" style="text-shadow: none;">Lab Management</span></h1>
                    <p style="font-size: 1.5rem; max-width: 900px;" class="mx-auto mb-5">
                        Empower your diagnostic center with our cutting-edge Laboratory Information System designed for modern healthcare.
                    </p>
                    <div class="d-flex gap-3 mt-4 justify-content-center">
                        <a href="#welcome" class="btn btn-3d">Learn More <i class="fa fa-arrow-down ms-2"></i></a>
                        <a href="{{ route('login') }}" class="btn btn-outline-primary" style="border-radius: 50px; padding: 10px 30px; font-weight: 700; background: rgba(255,255,255,0.8); backdrop-filter: blur(5px);">Enroll Now</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Welcome Section -->
    <section id="welcome" class="py-5 bg-light">
        <div class="container py-5 text-center">
            <div class="section-title mb-5">
                <h2 style="font-size: 2.5rem; text-transform: uppercase;">Welcome to Suhaim Lab</h2>
                <p class="text-primary fw-bold" style="font-size: 1.25rem;">Your Partner in Digital Healthcare Transformation.</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <p class="text-muted" style="line-height: 1.8; font-size: 1.15rem; margin-bottom: 20px;">
                        In today's fast-paced medical environment, the most valuable resource is time. Administrative tasks and cumbersome paperwork can divert focus from what truly matters: patient care. <strong>SUHAIM LAB</strong> was founded on a simple principle: to give that time back to healthcare professionals.
                    </p>
                    <p class="text-muted" style="line-height: 1.8; font-size: 1.15rem;">
                        Our intelligent Laboratory Information System (LIS) is more than just a digital filing cabinet. It is a powerful, integrated platform designed to streamline your entire workflow, from patient registration to test reports.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5 bg-white">
        <div class="container py-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <h2 class="fw-bold text-dark mb-4" style="font-size: 2.5rem;">About SUHAIM LAB</h2>
                    <p class="text-muted" style="line-height: 1.8; font-size: 1.1rem;">
                        We are a leading provider of innovative laboratory management solutions. Our core mission is to empower diagnostic centers and laboratories with cutting-edge technology that simplifies daily operations, ensures uncompromising data accuracy, and significantly reduces turnaround times.
                    </p>
                    <p class="text-muted" style="line-height: 1.8; font-size: 1.1rem;">
                        With a deep understanding of the healthcare industry, SUHAIM LAB was built by professionals, for professionals. We handle the complexity of lab management so you can focus entirely on delivering the highest quality patient diagnostics.
                    </p>
                </div>
                <div class="col-lg-6">
                    <div class="card-3d p-4 text-center bg-light">
                        <i class="fa-solid fa-microscope text-primary" style="font-size: 5rem; margin-bottom: 20px;"></i>
                        <h4 class="fw-bold">Precision & Care</h4>
                        <p class="text-muted mb-0">Delivering state-of-the-art diagnostic reporting and seamless lab integrations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Process Section -->
    <section id="process" class="process-section">
        <div class="container">
            <div class="section-title">
                <h2>Our Simple Onboarding Process</h2>
                <p>Get started with SUHAIM LAB in three easy steps. Streamline your practice with our intuitive platform.</p>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="process-step card-3d h-100">
                        <div class="step-number">1</div>
                        <h4>Consult & Demo</h4>
                        <p class="text-muted mt-3">Request a demo, and our specialists will showcase the platform's power, tailored to your clinic's needs.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="process-step card-3d h-100">
                        <div class="step-number">2</div>
                        <h4>Seamless Integration</h4>
                        <p class="text-muted mt-3">Our team handles the heavy lifting, migrating your existing data and integrating our EMR into your workflow.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="process-step card-3d h-100">
                        <div class="step-number">3</div>
                        <h4>Training & Support</h4>
                        <p class="text-muted mt-3">We provide comprehensive training and dedicated support to ensure your team is confident and successful.</p>
                    </div>
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
                        <a href="#"><i class="fab fa-twitter"></i></a>
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
                        <li class="mb-3"><i class="fa fa-map-marker-alt me-2 text-primary"></i> 123 Healthcare Avenue, Tech District</li>
                        <li class="mb-3"><i class="fa fa-phone-alt me-2 text-primary"></i> +1 (800) 123-4567</li>
                        <li class="mb-3"><i class="fa fa-envelope me-2 text-primary"></i> contact@suhaimsoft.com</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p class="mb-0">&copy; {{ date('Y') }} SUHAIM LAB. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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
