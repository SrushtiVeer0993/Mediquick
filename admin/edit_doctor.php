<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once 'includes/header.php';

$db = Database::getInstance();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Doctor ID is missing.');
}

$id = (int)$_GET['id'];
$doctor = $db->fetch("SELECT * FROM doctors WHERE id = ?", [$id]);

if (!$doctor) {
    die('Doctor not found.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $specialization = $_POST['specialization'] ?? '';
    $qualification = $_POST['qualification'] ?? '';
    $experience = $_POST['experience'] ?? 0;
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $status = $_POST['status'] ?? 'offline';
    $availability_days = $_POST['availability_days'] ?? '';
    $time_slots = $_POST['time_slots'] ?? '';
    $consultation_fee = $_POST['consultation_fee'] ?? 0.00;
    $rating = $_POST['rating'] ?? 0.00;
    $total_consultations = $_POST['total_consultations'] ?? 0;

    $sql = "UPDATE doctors SET name = ?, specialization = ?, qualification = ?, experience = ?, phone = ?, email = ?, status = ?, availability_days = ?, time_slots = ?, consultation_fee = ?, rating = ? WHERE id = ?";
    $params = [
        $_POST['name'],
        $_POST['specialization'],
        $_POST['qualification'],
        $_POST['experience'],
        $_POST['phone'],
        $_POST['email'],
        $_POST['status'],
        $_POST['availability_days'],
        $_POST['time_slots'],
        $_POST['consultation_fee'],
        $_POST['rating'],
        $_POST['id'] // assuming this is coming from a hidden input or query param
    ];
    $db->query($sql, $params);
    


    header("Location: doctors.php?msg=updated");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Doctor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h2>Edit Doctor: <?= htmlspecialchars($doctor['name']) ?></h2>
    <form method="POST" class="mt-4">
    <input type="hidden" name="id" value="<?= (int)$doctor['id'] ?>">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Name</label>
                <input type="text" name="name" class="form-control" required value="<?= htmlspecialchars($doctor['name']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Specialization</label>
                <input type="text" name="specialization" class="form-control" required value="<?= htmlspecialchars($doctor['specialization']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Qualification</label>
                <textarea name="qualification" class="form-control"><?= htmlspecialchars($doctor['qualification']) ?></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label">Experience (years)</label>
                <input type="number" name="experience" class="form-control" value="<?= (int)$doctor['experience'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Phone</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($doctor['phone']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($doctor['email']) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="available" <?= $doctor['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                    <option value="busy" <?= $doctor['status'] === 'busy' ? 'selected' : '' ?>>Busy</option>
                    <option value="offline" <?= $doctor['status'] === 'offline' ? 'selected' : '' ?>>Offline</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Availability Days</label>
                <input type="text" name="availability_days" class="form-control" value="<?= htmlspecialchars($doctor['availability_days']) ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Time Slots</label>
                <input type="text" name="time_slots" class="form-control" value="<?= htmlspecialchars($doctor['time_slots']) ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Fee (₹)</label>
                <input type="number" name="consultation_fee" step="0.01" class="form-control" value="<?= (float)$doctor['consultation_fee'] ?>">
            </div>
            <div class="col-md-3">
                <label class="form-label">Rating</label>
                <input type="number" name="rating" step="0.01" class="form-control" value="<?= (float)$doctor['rating'] ?>">
            </div>
            <div class="col-md-6">
                <label class="form-label">Total Consultations</label>
                <input type="number" name="total_consultations" class="form-control" value="<?= (int)$doctor['total_consultations'] ?>">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="btn btn-success">Update Doctor</button>
            <a href="doctors.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php require_once 'includes/footer.php'; ?>
</body>
</html>
