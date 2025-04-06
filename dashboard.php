<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';

// Check if user is logged in
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

// Get user data
$user = getUserData($_SESSION['user_id']);

// Get recent activities from activity_log table
try {
    $stmt = $pdo->prepare("
        SELECT * FROM activity_log 
        WHERE user_id = ? 
        ORDER BY created_at DESC 
        LIMIT 5
    ");
    $stmt->execute([$_SESSION['user_id']]);
    $recentActivities = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching activities: " . $e->getMessage());
    $recentActivities = [];
}

// Get statistics
try {
    // Get total appointments
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalAppointments = $stmt->fetchColumn();

    // Get total prescriptions
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM prescriptions WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalPrescriptions = $stmt->fetchColumn();

    // Get total consultations
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM consultations WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $totalConsultations = $stmt->fetchColumn();
} catch (PDOException $e) {
    error_log("Error fetching statistics: " . $e->getMessage());
    $totalAppointments = 0;
    $totalPrescriptions = 0;
    $totalConsultations = 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - MediQuick</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .dashboard-card {
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .activity-item {
            border-left: 3px solid #007bff;
            padding-left: 15px;
            margin-bottom: 15px;
        }
        .activity-item.success { border-left-color: #28a745; }
        .activity-item.failed { border-left-color: #dc3545; }
        .activity-item.pending { border-left-color: #ffc107; }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; ?>

    <div class="container py-5">
        <div class="row">
            <!-- Welcome Section -->
            <div class="col-12 mb-4">
                <div class="dashboard-card p-4 bg-white">
                    <h2>Welcome back, <?php echo htmlspecialchars($user['name']); ?>!</h2>
                    <p class="text-muted">Here's what's happening with your health today.</p>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="col-md-4 mb-4">
                <div class="dashboard-card p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Appointments</h6>
                            <h3 class="mb-0"><?php echo $totalAppointments; ?></h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-calendar-check text-primary fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="dashboard-card p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Prescriptions</h6>
                            <h3 class="mb-0"><?php echo $totalPrescriptions; ?></h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-prescription-bottle-alt text-success fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="dashboard-card p-4 bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-2">Total Consultations</h6>
                            <h3 class="mb-0"><?php echo $totalConsultations; ?></h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded-circle">
                            <i class="fas fa-stethoscope text-info fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activities -->
            <div class="col-12">
                <div class="dashboard-card p-4 bg-white">
                    <h4 class="mb-4">Recent Activities</h4>
                    <?php if (empty($recentActivities)): ?>
                        <p class="text-muted">No recent activities to display.</p>
                    <?php else: ?>
                        <?php foreach ($recentActivities as $activity): ?>
                            <div class="activity-item <?php echo $activity['status'] ?? 'success'; ?>">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1"><?php echo htmlspecialchars($activity['action']); ?></h6>
                                        <small class="text-muted">
                                            <?php echo date('M d, Y H:i', strtotime($activity['created_at'])); ?>
                                        </small>
                                    </div>
                                    <div>
                                        <?php if (($activity['status'] ?? 'success') === 'success'): ?>
                                            <i class="fas fa-check-circle text-success"></i>
                                        <?php elseif (($activity['status'] ?? 'success') === 'failed'): ?>
                                            <i class="fas fa-times-circle text-danger"></i>
                                        <?php else: ?>
                                            <i class="fas fa-clock text-warning"></i>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 