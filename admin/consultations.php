<?php
session_start();
require_once 'includes/connection.php';
require_once '../includes/functions.php';
require_once 'includes/header.php';

// Check if admin is logged in
if(!isset($_SESSION['admin_id'])) {
    header("Location: index.php");
    exit();
}

$message = '';
$error = '';

// Handle consultation deletion
if(isset($_POST['delete_consultation'])) {
    $consultation_id = (int)$_POST['consultation_id'];
    $sql = "DELETE FROM consultations WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $consultation_id);
    if($stmt->execute()) {
        $message = "Consultation deleted successfully";
    } else {
        $error = "Error deleting consultation";
    }
}

// Filter parameters
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Build query
$sql = "SELECT c.*, u.full_name as user_name, u.email as user_email, 
               d.name as doctor_name, d.email as doctor_email 
        FROM consultations c 
        LEFT JOIN users u ON c.user_id = u.id 
        LEFT JOIN doctors d ON c.doctor_id = d.id 
        WHERE 1=1";

$params = [];
$types = "";

if (!empty($status_filter)) {
    $sql .= " AND c.status = ?";
    $params[] = $status_filter;
    $types .= "s";
}

if (!empty($date_from)) {
    $sql .= " AND c.created_at >= ?";
    $params[] = $date_from . " 00:00:00";
    $types .= "s";
}

if (!empty($date_to)) {
    $sql .= " AND c.created_at <= ?";
    $params[] = $date_to . " 23:59:59";
    $types .= "s";
}

if (!empty($search)) {
    $sql .= " AND (u.full_name LIKE ? OR u.email LIKE ? OR d.name LIKE ? OR d.email LIKE ? OR c.symptoms LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sssss";
}

$sql .= " ORDER BY c.created_at DESC";

// Execute query
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">Manage Consultations</h1>
        </div>
    </div>

    <?php if($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="" class="row g-3">
                <div class="col-md-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Statuses</option>
                        <option value="pending" <?php echo $status_filter == 'pending' ? 'selected' : ''; ?>>Pending</option>
                        <option value="in_progress" <?php echo $status_filter == 'in_progress' ? 'selected' : ''; ?>>In Progress</option>
                        <option value="completed" <?php echo $status_filter == 'completed' ? 'selected' : ''; ?>>Completed</option>
                        <option value="cancelled" <?php echo $status_filter == 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="date_from" class="form-label">Date From</label>
                    <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo $date_from; ?>">
                </div>
                <div class="col-md-3">
                    <label for="date_to" class="form-label">Date To</label>
                    <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo $date_to; ?>">
                </div>
                <div class="col-md-3">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search..." value="<?php echo htmlspecialchars($search); ?>">
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Apply Filters</button>
                    <a href="consultations.php" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Doctor</th>
                            <th>Symptoms</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while($consultation = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $consultation['id']; ?></td>
                                <td>
                                    <?php echo htmlspecialchars($consultation['user_name']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($consultation['user_email']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($consultation['doctor_name']); ?><br>
                                    <small class="text-muted"><?php echo htmlspecialchars($consultation['doctor_email']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars(substr($consultation['symptoms'], 0, 50)) . (strlen($consultation['symptoms']) > 50 ? '...' : ''); ?></td>
                                <td>
                                    <span class="badge bg-<?php 
                                        echo $consultation['status'] == 'completed' ? 'success' : 
                                            ($consultation['status'] == 'in_progress' ? 'primary' : 
                                            ($consultation['status'] == 'cancelled' ? 'danger' : 'warning')); 
                                    ?>">
                                        <?php echo ucfirst($consultation['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('Y-m-d H:i', strtotime($consultation['created_at'])); ?></td>
                                <td>
                                    <a href="view_consultation.php?id=<?php echo $consultation['id']; ?>" class="btn btn-info btn-sm">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <form method="POST" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this consultation?');">
                                        <input type="hidden" name="consultation_id" value="<?php echo $consultation['id']; ?>">
                                        <button type="submit" name="delete_consultation" class="btn btn-danger btn-sm">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?> 