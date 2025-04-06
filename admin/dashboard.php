<?php
session_start();
require_once 'includes/connection.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

// Get statistics
$stats = [
    'users' => 0,
    'symptoms' => 0,
    'first_aid' => 0
];

// Count users
$sql = "SELECT COUNT(*) as count FROM users";
$result = $conn->query($sql);
$stats['users'] = $result->fetch_assoc()['count'];

// Count symptoms
$sql = "SELECT COUNT(*) as count FROM symptoms";
$result = $conn->query($sql);
$stats['symptoms'] = $result->fetch_assoc()['count'];

// Count first aid guides
$sql = "SELECT COUNT(*) as count FROM first_aid_guides";
$result = $conn->query($sql);
$stats['first_aid'] = $result->fetch_assoc()['count'];
include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Dashboard</h1>
        </div>
    </div>

    <div class="row">
        <!-- Users Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Users</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['users']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Symptoms Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Symptoms</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['symptoms']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-clipboard-pulse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- First Aid Guides Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">First Aid Guides</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $stats['first_aid']; ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-heart-pulse fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity Section -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Activity</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Type</th>
                                    <th>Title</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                // Get recent activities from both symptoms and first aid guides
                                $sql = "SELECT * FROM (
                                    SELECT 'symptom' as type, created_at, name as title, id as reference_id 
                                    FROM symptoms 
                                    UNION ALL 
                                    SELECT 'first_aid' as type, created_at, title, id as reference_id 
                                    FROM first_aid_guides
                                ) as activities 
                                ORDER BY created_at DESC LIMIT 5";
                                
                                $result = $conn->query($sql);
                                
                                if ($result->num_rows > 0) {
                                    while($row = $result->fetch_assoc()) {
                                        echo "<tr>";
                                        echo "<td>" . date('M d, Y H:i', strtotime($row['created_at'])) . "</td>";
                                        echo "<td>" . ucfirst(htmlspecialchars($row['type'])) . "</td>";
                                        echo "<td>" . htmlspecialchars($row['title']) . "</td>";
                                        echo "<td>";
                                        if ($row['type'] == 'symptom') {
                                            echo "<a href='manage_symptoms.php' class='btn btn-sm btn-info'>Manage</a>";
                                        } else {
                                            echo "<a href='manage_first_aid.php' class='btn btn-sm btn-info'>Manage</a>";
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='4' class='text-center'>No recent activity</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
