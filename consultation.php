<?php
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Create database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mediquick";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get available doctors with their schedules
$sql = "SELECT * FROM doctors ORDER BY name ASC";
$result = $conn->query($sql);
$doctors = $result->fetch_all(MYSQLI_ASSOC);

// Close connection
$conn->close();
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor Consultation - MediQuick</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #007bff;
            --secondary-color: #6c757d;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --danger-color: #dc3545;
            --light-color: #f8f9fa;
            --dark-color: #343a40;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            background-color: #f4f6f9;
        }

        .consultation-container {
            padding: 2rem;
            max-width: 1200px;
            margin: 0 200px;
        }

        .search-section {
            text-align: center;
            margin-bottom: 2rem;
            padding: 2rem;
            background: linear-gradient(135deg, var(--primary-color), #4a90e2);;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .search-section h1 {
            color: white;;
            margin-bottom: 0.5rem;
            font-size: 2.5rem;
        }

        .search-section p {
            color:  white;;
            font-size: 1.1rem;
        }

        .doctors-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .doctor-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }

        .doctor-card:hover {
            transform: translateY(-5px);
        }

        .doctor-card h3 {
            color: var(--dark-color);
            margin-bottom: 1rem;
            font-size: 1.5rem;
        }

        .specialty {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }

        .qualification {
            color: var(--secondary-color);
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .doctor-info {
            background: var(--light-color);
            padding: 1rem;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .doctor-info p {
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .doctor-info strong {
            color: var(--dark-color);
        }

        .actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-consult {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            flex: 1;
            justify-content: center;
            text-decoration: none;
            background: var(--primary-color);
            color: white;
        }

        .btn-consult:hover {
            background: #0056b3;
            color: white;
        }

        .no-doctors {
            text-align: center;
            grid-column: 1 / -1;
            padding: 2rem;
            color: var(--secondary-color);
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .consultation-container {
                padding: 1rem;
            }

            .search-section h1 {
                font-size: 2rem;
            }

            .doctors-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
            }
        }

        /* Navigation Styles */
        .navbar {
            background: white;
            padding: 1rem 0;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo h1 {
            color: var(--primary-color);
            font-size: 1.5rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark-color);
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .nav-links a.active {
            color: var(--primary-color);
        }

        .mobile-menu {
            display: none;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }

            .mobile-menu {
                display: block;
            }
        }
    </style>
</head>
<body>

    <!-- Consultation Content -->
    <div class="consultation-container">
        <div class="search-section">
            <h1>Doctor Consultation</h1>
            <p>Connect with qualified doctors for online consultation</p>
        </div>

        <div class="doctors-grid">
            <?php if(!empty($doctors)): ?>
                <?php foreach ($doctors as $doctor): ?>
                    <div class="doctor-card">
                        <h3><?php echo htmlspecialchars($doctor['name']); ?></h3>
                        <p class="specialty"><?php echo htmlspecialchars($doctor['specialization']); ?></p>
                        
                        <div class="doctor-info">
                            <p><strong>Experience:</strong> <?php echo $doctor['experience']; ?> years</p>
                            <p><strong>Email:</strong> <?php echo htmlspecialchars($doctor['email']); ?></p>
                            <p><strong>Phone:</strong> <?php echo htmlspecialchars($doctor['phone']); ?></p>
        </div>

                    <div class="actions">
                            <a href="book_appointment.php?id=<?php echo $doctor['id']; ?>" class="btn-consult">
                                <i class="fas fa-video"></i> Book Consultation
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php else: ?>
                <p class="no-doctors">No doctors available at the moment.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>