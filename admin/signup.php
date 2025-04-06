<?php
session_start();
require_once 'includes/connection.php';
require_once '../includes/functions.php';
require_once 'includes/header.php';

// Check if admin is already logged in
if (isset($_SESSION['admin_id'])) {
    // header("Location: dashboard.php");
    // exit();
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate input
    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "Please fill in all fields";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match";
    } else {
        // Check if email already exists
        $sql = "SELECT id FROM admins WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Email is already registered";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new admin
            $sql = "INSERT INTO admins (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $name, $email, $hashed_password);

            if ($stmt->execute()) {
                $message = "Admin account created successfully. You can now login.";
                header("refresh:2;url=index.php");
            } else {
                $error = "Error creating admin account";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Signup - MediQuick</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        /* Header Styles */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            padding: 0.3rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .navbar-brand h1 {
            font-size: 1.5rem;
        }

        /* Form Container Styles */
        .form-container {
            max-width: 500px;
            margin: 1rem auto;
            padding: 1.5rem;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-container h2 {
            color: #3a5bd9;
            text-align: center;
            margin-bottom: 1.5rem;
            font-weight: 700;
            font-size: 1.5rem;
        }

        .form-container .form-group {
            margin-bottom: 1rem;
        }

        .form-container .form-control {
            padding: 0.6rem 0.8rem;
            border-radius: 8px;
            border: 1px solid #e0e0e0;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-container .btn-primary {
            width: 100%;
            padding: 0.6rem;
            font-size: 1rem;
            border-radius: 8px;
            background: #3a5bd9;
            border: none;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        .form-container .alert {
            border-radius: 8px;
            margin-bottom: 1rem;
            padding: 0.5rem 1rem;
            font-size: 0.9rem;
        }

        /* Footer Styles */
        footer {
            background: linear-gradient(135deg, #6a8aff 0%, #3a5bd9 100%);
            color: white;
            padding: 0.8rem 0 0.4rem;
            margin-top: auto;
            position: relative;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .footer-section h3 {
            font-size: 1rem;
            margin-bottom: 0.4rem;
        }

        .footer-section p {
            font-size: 0.8rem;
            margin-bottom: 0.3rem;
        }

        .footer-section ul li {
            margin-bottom: 0.2rem;
        }

        .footer-section ul li a {
            font-size: 0.8rem;
        }

        .footer-bottom {
            padding-top: 0.5rem;
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .form-container {
                margin: 0.5rem;
                padding: 1rem;
            }

            .navbar-brand h1 {
                font-size: 1.3rem;
            }

            .form-container h2 {
                font-size: 1.3rem;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body class="bg-light">
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
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Log in</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mt-4">
        <div class="form-container">
            <h2>Admin Signup</h2>
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                </div>
                <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>
            <div class="text-center mt-2">
                <p class="mb-0">Already have an account? <a href="index.php">Login here</a></p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>MediQuick</h3>
                    <p>Your trusted emergency health companion.</p>
                </div>
                <div class="footer-section">
                    <h3>Quick Links</h3>
                    <ul>
                        <li><a href="first-aid.php">First Aid Guide</a></li>
                        <li><a href="pharmacy.php">Pharmacy Locator</a></li>
                        <li><a href="emergency.php">Emergency Contacts</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <p>Email: support@mediquick.com</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2024 MediQuick. All rights reserved.</p>
            </div>
        </div>
    </footer>

<?php require_once 'includes/footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
