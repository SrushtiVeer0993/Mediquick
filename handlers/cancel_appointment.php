<?php
require_once '../includes/config.php';

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get appointment ID from request
$data = json_decode(file_get_contents('php://input'), true);
$appointment_id = $data['appointment_id'] ?? null;

// Validate input
if (!$appointment_id) {
    echo json_encode(['success' => false, 'message' => 'Appointment ID is required']);
    exit();
}

try {
    // Verify appointment belongs to user and is upcoming
    $stmt = $pdo->prepare("
        SELECT id, status 
        FROM appointments 
        WHERE id = ? AND user_id = ? AND status = 'Upcoming'
    ");
    $stmt->execute([$appointment_id, $user_id]);
    $appointment = $stmt->fetch();

    if (!$appointment) {
        throw new Exception('Appointment not found, unauthorized, or not eligible for cancellation');
    }

    // Update appointment status to cancelled
    $stmt = $pdo->prepare("
        UPDATE appointments 
        SET status = 'Cancelled', 
            updated_at = CURRENT_TIMESTAMP 
        WHERE id = ? AND user_id = ?
    ");
    $stmt->execute([$appointment_id, $user_id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 