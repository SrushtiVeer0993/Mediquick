<?php
require_once '../includes/config.php';

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get contact ID from request
$data = json_decode(file_get_contents('php://input'), true);
$contact_id = $data['contact_id'] ?? null;

// Validate input
if (!$contact_id) {
    echo json_encode(['success' => false, 'message' => 'Contact ID is required']);
    exit();
}

try {
    // Verify contact belongs to user
    $stmt = $pdo->prepare("SELECT id FROM emergency_contacts WHERE id = ? AND user_id = ?");
    $stmt->execute([$contact_id, $user_id]);
    if (!$stmt->fetch()) {
        throw new Exception('Contact not found or unauthorized');
    }

    // Delete contact
    $stmt = $pdo->prepare("DELETE FROM emergency_contacts WHERE id = ? AND user_id = ?");
    $stmt->execute([$contact_id, $user_id]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
} 