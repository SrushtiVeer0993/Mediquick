<?php
require_once '../includes/config.php';

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode(['success' => false, 'message' => 'User not logged in']);
    exit();
}

// Get user ID from session
$user_id = $_SESSION['user_id'];

// Get form data
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$relationship = $_POST['relationship'] ?? '';

// Validate input
if (empty($name) || empty($phone)) {
    echo json_encode(['success' => false, 'message' => 'Name and phone are required']);
    exit();
}

try {
    // Insert new contact
    $stmt = $pdo->prepare("
        INSERT INTO emergency_contacts (user_id, name, phone, relationship) 
        VALUES (?, ?, ?, ?)
    ");
    $stmt->execute([$user_id, $name, $phone, $relationship]);

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Failed to add contact']);
} 