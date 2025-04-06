<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';
require_once 'includes/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die('Invalid appointment ID');
}

$id = $_GET['id'];
$db = Database::getInstance();

// First, check if the appointment exists
$checkSql = "SELECT * FROM appointments WHERE id = ?";
$appointment = $db->fetch($checkSql, [$id]);

if (!$appointment) {
    die("Appointment not found.");
}

// Proceed to delete
$deleteSql = "DELETE FROM appointments WHERE id = ?";
$deleted = $db->query($deleteSql, [$id]);

if ($deleted) {
    header("Location: appointment.php?msg=Appointment+deleted+successfully");
    exit;
} else {
    echo "Failed to delete appointment.";
}

require_once 'includes/footer.php';
?>
