<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once 'includes/header.php';

$db = Database::getInstance();

// Fetch appointment by ID
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Invalid ID");
}

$id = $_GET['id'];
$appointment = $db->fetch("SELECT * FROM appointments WHERE id = ?", [$id]);

if (!$appointment) {
    die("Appointment not found.");
}

// Handle update form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $date = $_POST['appointment_date'];
    $time = $_POST['appointment_time'];
    $type = $_POST['consultation_type'];
    $status = $_POST['status'];

    $sql = "UPDATE appointments 
            SET appointment_date = ?, appointment_time = ?, consultation_type = ?, status = ?
            WHERE id = ?";
    $result = $db->query($sql, [$date, $time, $type, $status, $id]);

    if ($result) {
        header("Location: appointment.php?msg=Appointment+updated+successfully");
        exit;
    } else {
        $error = "Update failed.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Appointment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Appointment</h2>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="post" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
            <label>Date</label>
            <input type="date" name="appointment_date" value="<?= $appointment['appointment_date'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Time</label>
            <input type="time" name="appointment_time" value="<?= $appointment['appointment_time'] ?>" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Consultation Type</label>
            <select name="consultation_type" class="form-control">
                <option value="in-person" <?= $appointment['consultation_type'] == 'in-person' ? 'selected' : '' ?>>In-person</option>
                <option value="online" <?= $appointment['consultation_type'] == 'online' ? 'selected' : '' ?>>Online</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Status</label>
            <select name="status" class="form-control">
                <option value="scheduled" <?= $appointment['status'] == 'scheduled' ? 'selected' : '' ?>>Scheduled</option>
                <option value="completed" <?= $appointment['status'] == 'completed' ? 'selected' : '' ?>>Completed</option>
                <option value="cancelled" <?= $appointment['status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update Appointment</button>
        <a href="appointment.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>
