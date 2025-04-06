<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediQuick - Your Emergency Health Companion</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    Navigation
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="#">
                <i class="fas fa-heartbeat me-2"></i>
                <h1 class="m-0 d-inline">MediQuick</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="first-aid.php">First Aid</a></li>
                    <li class="nav-item"><a class="nav-link" href="pharmacy.php">Pharmacy</a></li>
                    <li class="nav-item"><a class="nav-link" href="emergency.php">Emergency</a></li>
                    <li class="nav-item"><a class="nav-link" href="find-doctor.php">Find Doctor</a></li>
                    <li class="nav-item"><a class="nav-link" href="consultation.php">Consultation</a></li>
                    <li class="nav-item">
                        <a class="nav-link" href="profile.php">
                            <i class="fas fa-user-circle fa-lg"></i>
                            <span class="ms-2 d-none d-md-inline">Profile</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero py-5 mt-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero-content">
                        <h1 class="display-4 fw-bold mb-4">Your Emergency Health Companion</h1>
                        <p class="lead mb-4">Quick access to medical information, emergency contacts, and healthcare services when you need them most.</p>
                        <div class="cta-buttons">
                            <a href="first-aid.php" class="btn btn-primary btn-lg me-2">First Aid Guide</a>
                            <a href="emergency.php" class="btn btn-outline-primary btn-lg">Emergency Contacts</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-image">
                        <img src="assets\images/hero.png" alt="Medical Illustration" class="img-fluid rounded-3 shadow">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- AI Assistant Section -->
    <section class="ai-assistant py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="ai-assistant-image">
                        <img src="assets\images\aibot.png" alt="AI Health Assistant" class="img-fluid rounded-3 shadow">
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="ai-assistant-text p-4">
                        <h2 class="display-5 fw-bold mb-4">AI Health Assistant</h2>
                        <p class="lead mb-4">Get instant answers to your health-related questions from our AI-powered assistant. Available 24/7 to provide reliable medical information and guidance.</p>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    <span>Instant responses to health queries</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    <span>Evidence-based medical information</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    <span>Symptom analysis and guidance</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-check-circle text-warning me-2"></i>
                                    <span>Medication information</span>
                                </div>
                            </div>
                        </div>
                        <a href="chatbot.php" class="btn btn-light btn-lg">Chat with AI Assistant</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features py-5">
        <div class="container">
            <h2 class="text-center mb-5">Key Features</h2>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <a  class="feature-card card h-100 border-0 shadow-sm text-decoration-none">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-first-aid fa-3x mb-3 text-primary"></i>
                            <h3 class="h5">First Aid Guide</h3>
                            <p class="text-muted">Quick access to step-by-step first aid instructions for various medical emergencies.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3">
                    <a href="pharmacy.php" class="feature-card card h-100 border-0 shadow-sm text-decoration-none">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-map-marker-alt fa-3x mb-3 text-primary"></i>
                            <h3 class="h5">Pharmacy Locator</h3>
                            <p class="text-muted">Find nearby pharmacies and check medicine availability in real-time.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3">
                    <a href="emergency.php" class="feature-card card h-100 border-0 shadow-sm text-decoration-none">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-phone-alt fa-3x mb-3 text-primary"></i>
                            <h3 class="h5">Emergency Contacts</h3>
                            <p class="text-muted">One-tap access to emergency services and your saved contacts.</p>
                        </div>
                    </a>
                </div>
                <div class="col-md-6 col-lg-3">
                    <a href="find-doctor.php" class="feature-card card h-100 border-0 shadow-sm text-decoration-none">
                        <div class="card-body text-center p-4">
                            <i class="fas fa-user-md fa-3x mb-3 text-primary"></i>
                            <h3 class="h5">Find Doctor</h3>
                            <p class="text-muted">Get recommendations for the right type of doctor based on your symptoms.</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <style>
        /* Header Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 0.5rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .navbar-brand i {
            color: #3a5bd9;
            font-size: 1.6rem;
            transition: transform 0.3s ease;
        }

        .navbar-brand:hover i {
            transform: scale(1.1);
            color: #6a8aff;
        }

        .navbar-brand h1 {
            color: #3a5bd9;
            font-size: 1.8rem;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
            transition: color 0.3s ease;
        }

        .navbar-brand:hover h1 {
            color: #6a8aff;
        }

        .navbar-nav .nav-link {
            color: #4a4a4a;
            font-weight: 500;
            padding: 0.4rem 1.2rem;
            transition: all 0.3s ease;
            border-radius: 6px;
            font-size: 0.95rem;
            margin: 0 0.3rem;
        }

        .navbar-nav {
            gap: 0.5rem;
        }

        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            color: #3a5bd9;
            background: rgba(58, 91, 217, 0.1);
            transform: translateY(-1px);
        }

        .navbar-nav .nav-link i {
            font-size: 1.1rem;
            transition: transform 0.3s;
        }

        .navbar-nav .nav-link:hover i {
            transform: scale(1.1);
        }

        /* Footer Styles */
        footer {
            background: linear-gradient(135deg, #6a8aff 0%, #3a5bd9 100%);
            color: white;
            padding: 1.5rem 0 0.8rem;
            margin-top: 2rem;
            position: relative;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .footer-section h3 {
            color: white;
            font-size: 1.1rem;
            margin-bottom: 0.8rem;
            font-weight: 600;
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .footer-section p {
            color: rgba(255, 255, 255, 0.9);
            line-height: 1.4;
            margin-bottom: 0.6rem;
            font-size: 0.85rem;
        }

        .footer-section ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-section ul li {
            margin-bottom: 0.4rem;
        }

        .footer-section ul li a {
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            font-size: 0.85rem;
        }

        .footer-section ul li a:hover {
            color: white;
            transform: translateX(3px);
        }

        .footer-bottom {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            color: rgba(255, 255, 255, 0.9);
            font-size: 0.8rem;
        }

        @media (max-width: 768px) {
            footer {
                padding: 1.2rem 0 0.6rem;
                margin-top: 1.5rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 0.8rem;
            }

            .footer-section ul li a:hover {
                transform: none;
            }
        }

        .ai-assistant {
            background: linear-gradient(135deg, #6a8aff 0%, #3a5bd9 100%);
            color: white;
            padding: 4rem 0;
            position: relative;
            overflow: hidden;
            margin: 2rem 0;
            border-radius: 30px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .ai-assistant::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('assets/images/pattern.png') repeat;
            opacity: 0.05;
            pointer-events: none;
        }

        .ai-assistant::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.15) 0%, transparent 50%);
            pointer-events: none;
        }

        .ai-assistant-content {
            display: flex;
            align-items: center;
            gap: 4rem;
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            padding: 0 2rem;
        }

        .ai-assistant-text {
            flex: 1;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 25px;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .ai-assistant-text:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        }

        .ai-assistant-text h2 {
            font-size: 2.8rem;
            margin-bottom: 1.5rem;
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            font-weight: 700;
            line-height: 1.2;
        }

        .ai-assistant-text p {
            font-size: 1.2rem;
            margin-bottom: 2.5rem;
            line-height: 1.6;
            color: rgba(255, 255, 255, 0.9);
            opacity: 0.9;
        }

        .ai-features {
            list-style: none;
            padding: 0;
            margin-bottom: 2.5rem;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.2rem;
        }

        .ai-features li {
            margin-bottom: 0;
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.2rem;
            background: rgba(255, 255, 255, 0.15);
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .ai-features li:hover {
            transform: translateY(-3px);
            background: rgba(255, 255, 255, 0.2);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .ai-features i {
            color: #ffd700;
            font-size: 1.4rem;
            background: rgba(255, 255, 255, 0.15);
            padding: 0.8rem;
            border-radius: 12px;
            transition: transform 0.3s;
        }

        .ai-features li:hover i {
            transform: scale(1.1);
        }

        .ai-assistant-image {
            flex: 1;
            text-align: center;
            position: relative;
            perspective: 1000px;
            padding: 0 1.5rem;
            max-width: 450px;
            margin: 0 auto;
        }

        .ai-assistant-image img {
            max-width: 90%;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
            transform: perspective(1000px) rotateY(5deg);
            transition: all 0.5s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .ai-assistant-image:hover img {
            transform: perspective(1000px) rotateY(-5deg) translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
        }

        .ai-assistant-image::after {
            content: '';
            position: absolute;
            top: -15px;
            left: -15px;
            right: -15px;
            bottom: -15px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 30px;
            z-index: -1;
            transition: all 0.3s ease;
            transform: scale(1.02);
        }

        .ai-assistant-image:hover::after {
            transform: scale(1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .btn.primary {
            display: inline-flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1.2rem 2.5rem;
            background: rgba(255, 255, 255, 0.95);
            color: #3a5bd9;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            position: relative;
            overflow: hidden;
            border: none;
            cursor: pointer;
            font-size: 1.1rem;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .btn.primary i {
            font-size: 1.2rem;
            transition: transform 0.3s;
        }

        .btn.primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .btn.primary:hover i {
            transform: translateX(5px);
        }

        .btn.primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transform: translateX(-100%);
            transition: transform 0.6s;
        }

        .btn.primary:hover::before {
            transform: translateX(100%);
        }

        @media (max-width: 1024px) {
            .ai-assistant-content {
                gap: 3rem;
            }
        }

        @media (max-width: 768px) {
            .ai-assistant {
                padding: 3rem 0;
                margin: 1.5rem 0;
            }

            .ai-assistant-content {
                gap: 2rem;
            }

            .ai-assistant-text, .ai-assistant-image {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .ai-assistant {
                padding: 3rem 0;
            }

            .ai-assistant-text h2 {
                font-size: 2rem;
            }

            .ai-assistant-text p {
                font-size: 1rem;
            }

            .btn.primary {
                padding: 1rem 2rem;
                font-size: 1rem;
            }

            .ai-features li {
                padding: 0.8rem 1rem;
            }
        }

        .hero-image {
            flex: 1;
            text-align: center;
            position: relative;
            perspective: 1000px;
        }

        .hero-image img {
            max-width: 80%;
            height: auto;
            border-radius: 25px;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            transform: perspective(1000px) rotateY(-5deg);
            transition: all 0.5s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-image:hover img {
            transform: perspective(1000px) rotateY(0deg) translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
        }

        .hero-image::after {
            content: '';
            position: absolute;
            top: -15px;
            left: -15px;
            right: -15px;
            bottom: -15px;
            border: 2px solid rgba(255, 255, 255, 0.1);
            border-radius: 35px;
            z-index: -1;
            transition: all 0.3s ease;
        }

        .hero-image:hover::after {
            transform: scale(1.02);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .hero-image, .ai-assistant-image {
            flex: 1;
            text-align: center;
            position: relative;
            perspective: 1000px;
            padding: 0 1.5rem;
            max-width: 500px;
            margin: 0 auto;
        }

        @media (max-width: 1024px) {
            .hero-image img, .ai-assistant-image img {
                max-width: 90%;
            }
        }

        @media (max-width: 768px) {
            .hero-image, .ai-assistant-image {
                max-width: 400px;
                padding: 0 1rem;
            }
        }

        @media (max-width: 480px) {
            .hero-image, .ai-assistant-image {
                max-width: 300px;
            }
        }

        /* Profile Navigation Styles */
        .navbar-nav .nav-link[href="profile.php"] {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            background: rgba(58, 91, 217, 0.1);
            border-radius: 8px;
            margin-left: 0.5rem;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link[href="profile.php"] i {
            font-size: 1.4rem;
            color: #3a5bd9;
            transition: transform 0.3s ease;
        }

        .navbar-nav .nav-link[href="profile.php"] span {
            font-weight: 600;
            color: #3a5bd9;
        }

        .navbar-nav .nav-link[href="profile.php"]:hover {
            background: rgba(58, 91, 217, 0.2);
            transform: translateY(-2px);
        }

        .navbar-nav .nav-link[href="profile.php"]:hover i {
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .navbar-nav .nav-link[href="profile.php"] {
                padding: 0.4rem 0.8rem;
                margin-left: 0.3rem;
            }

            .navbar-nav .nav-link[href="profile.php"] i {
                font-size: 1.3rem;
            }
        }
    </style>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>MediQuick</h3>
                    <p>Your trusted emergency health companion, providing quick access to medical information and services.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="first-aid.php">First Aid Guide</a></li>
                        <li><a href="pharmacy.php">Pharmacy Locator</a></li>
                        <li><a href="emergency.php">Emergency Contacts</a></li>
                        <li><a href="find-doctor.php">Find Doctor</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Email: support@mediquick.com</p>
                    <p>Phone: +1 (555) 123-4567</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 MediQuick. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
