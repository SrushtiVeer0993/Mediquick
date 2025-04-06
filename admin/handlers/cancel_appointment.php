<?php
// Prevent any output before headers
ob_start();

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Set header to return JSON
header('Content-Type: application/json');

try {
    // Include required files
    require_once '../../includes/config.php';
    require_once '../../includes/functions.php';

    // Check if request is POST and has appointment ID
    if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['id'])) {
        throw new Exception('Invalid request');
    }

    $db = Database::getInstance();
    $appointment_id = (int)$_POST['id'];

    // Update appointment status to cancelled
    $query = "UPDATE appointments SET status = 'cancelled' WHERE id = ? AND status = 'scheduled'";
    $stmt = $db->prepare($query);
    $stmt->execute([$appointment_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Appointment cancelled successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Appointment not found or already cancelled']);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error cancelling appointment']);
} 