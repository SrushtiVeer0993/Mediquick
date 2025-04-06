<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=my_consultations.php');
    exit();
}

// Get user's consultations
try {
    $db = Database::getInstance();
    
    // Get all appointments for the user
    $appointments = $db->fetchAll(
        "SELECT a.*, d.name as doctor_name, d.specialization, d.email as doctor_email 
         FROM appointments a 
         JOIN doctors d ON a.doctor_id = d.id 
         WHERE a.user_id = ? 
         ORDER BY a.appointment_date DESC, a.appointment_time DESC",
        [$_SESSION['user_id']]
    );
    
    // Get user details
    $user = $db->fetch("SELECT * FROM users WHERE id = ?", [$_SESSION['user_id']]);
    
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['error'] = 'An error occurred while retrieving your consultations.';
    $appointments = [];
}
include 'header.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Consultations - MediQuick</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
            padding-top: 40px;
        }

        .consultations-container {
            max-width: 1200px;
            margin: 0 200px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }

        .consultations-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .consultation-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            padding: 1.5rem;
            transition: transform 0.3s ease;
        }

        .consultation-card:hover {
            transform: translateY(-5px);
        }

        .doctor-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .doctor-image {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 1rem;
        }

        .doctor-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .doctor-details h3 {
            margin-bottom: 0.25rem;
            color: var(--dark-color);
        }

        .doctor-details p {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }

        .appointment-details {
            margin-bottom: 1rem;
        }

        .detail-item {
            display: flex;
            margin-bottom: 0.5rem;
            color: var(--secondary-color);
        }

        .detail-item i {
            width: 20px;
            margin-right: 0.5rem;
            color: var(--primary-color);
        }

        .consultation-type {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .type-video {
            background: #e3f2fd;
            color: #1976d2;
        }

        .type-chat {
            background: #f3e5f5;
            color: #7b1fa2;
        }

        .type-phone {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .type-in-person {
            background: #fff3e0;
            color: #f57c00;
        }

        .status-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: 15px;
            font-size: 0.85rem;
            margin-bottom: 1rem;
        }

        .status-upcoming {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status-completed {
            background: #e8f5e9;
            color: #2e7d32;
        }

        .status-cancelled {
            background: #ffebee;
            color: #c62828;
        }

        .action-buttons {
            display: flex;
            gap: 0.5rem;
        }

        .btn {
            padding: 0.5rem 1rem;
            border-radius: 5px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            flex: 1;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
            border: none;
        }

        .btn-primary:hover {
            background: #0056b3;
        }

        .btn-outline-secondary {
            background: transparent;
            color: var(--secondary-color);
            border: 1px solid var(--secondary-color);
        }

        .btn-outline-secondary:hover {
            background: var(--secondary-color);
            color: white;
        }

        .no-consultations {
            text-align: center;
            padding: 3rem;
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .no-consultations i {
            font-size: 3rem;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }

        .no-consultations h3 {
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }

        .no-consultations p {
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
        }

        @media (max-width: 768px) {
            .consultations-grid {
                grid-template-columns: 1fr;
            }
            
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <!-- Consultations Content -->
    <div class="consultations-container">
        <div class="page-header">
            <h1>My Consultations</h1>
            <p>View and manage your appointments</p>
        </div>

        <?php if (empty($appointments)): ?>
            <div class="no-consultations">
                <i class="fas fa-calendar-times"></i>
                <h3>No Consultations Found</h3>
                <p>You haven't booked any consultations yet.</p>
                <a href="consultation.php" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Book a Consultation
                </a>
            </div>
        <?php else: ?>
            <div class="consultations-grid">
                <?php foreach ($appointments as $appointment): ?>
                    <div class="consultation-card">
                        <div class="doctor-info">
                            <div class="doctor-details">
                                <h3>Dr. <?php echo htmlspecialchars($appointment['doctor_name']); ?></h3>
                                <p><?php echo htmlspecialchars($appointment['specialization']); ?></p>
                            </div>
                        </div>

                        <div class="appointment-details">
                            <div class="detail-item">
                                <i class="fas fa-calendar"></i>
                                <span><?php echo date('F j, Y', strtotime($appointment['appointment_date'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-clock"></i>
                                <span><?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?></span>
                            </div>
                            <div class="detail-item">
                                <i class="fas fa-envelope"></i>
                                <span><?php echo htmlspecialchars($appointment['doctor_email']); ?></span>
                            </div>
                        </div>

                        <div class="consultation-type type-<?php echo $appointment['consultation_type']; ?>">
                            <?php 
                            $type = $appointment['consultation_type'];
                            if ($type === 'video') {
                                echo 'Video Consultation';
                            } elseif ($type === 'chat') {
                                echo 'Chat Consultation';
                            } elseif ($type === 'phone') {
                                echo 'Phone Consultation';
                            } else {
                                echo 'In-Person Consultation';
                            }
                            ?>
                        </div>

                        <div class="status-badge status-<?php echo $appointment['status']; ?>">
                            <?php echo ucfirst($appointment['status']); ?>
                        </div>

                        <div class="action-buttons">
                            <?php if ($appointment['status'] === 'upcoming'): ?>
                                <a href="join_consultation.php?id=<?php echo $appointment['id']; ?>" class="btn btn-primary">
                                    <i class="fas fa-video"></i> Join
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>