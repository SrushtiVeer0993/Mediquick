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
            .navbar {
                padding: 0.4rem 0;
            }

            .navbar-brand h1 {
                font-size: 1.5rem;
            }

            .navbar-nav .nav-link[href="profile.php"] {
                padding: 0.4rem 0.8rem;
                margin-left: 0.3rem;
            }

            .navbar-nav .nav-link[href="profile.php"] i {
                font-size: 1.3rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top shadow-sm">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-heartbeat me-2"></i>
                <h1 class="m-0 d-inline">MediQuick</h1>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
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
    <div style="margin-top: 80px;"></div>
</body>
</html> 