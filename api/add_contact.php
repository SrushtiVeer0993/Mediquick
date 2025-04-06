<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

// Check if user is logged in
if (!is_logged_in()) {
    echo json_encode([
        'success' => false,
        'message' => 'Please login to add emergency contacts.'
    ]);
    exit;
}

// Validate input
$name = isset($_POST['name']) ? sanitize_input($_POST['name']) : '';
$relationship = isset($_POST['relationship']) ? sanitize_input($_POST['relationship']) : '';
$phone = isset($_POST['phone']) ? sanitize_input($_POST['phone']) : '';
$email = isset($_POST['email']) ? sanitize_input($_POST['email']) : '';

// Validate required fields
if (empty($name) || empty($phone)) {
    echo json_encode([
        'success' => false,
        'message' => 'Name and phone number are required.'
    ]);
    exit;
}

// Validate phone number format
if (!preg_match('/^\+?[0-9]{10,15}$/', $phone)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid phone number format.'
    ]);
    exit;
}

// Validate email if provided
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid email format.'
    ]);
    exit;
}

try {
    // Check if contact already exists
    $stmt = $pdo->prepare("SELECT id FROM emergency_contacts WHERE user_id = ? AND phone = ?");
    $stmt->execute([$_SESSION['user_id'], $phone]);
    if ($stmt->rowCount() > 0) {
        echo json_encode([
            'success' => false,
            'message' => 'This phone number is already saved as an emergency contact.'
        ]);
        exit;
    }

    // Insert new contact
    $stmt = $pdo->prepare("
        INSERT INTO emergency_contacts (user_id, name, relationship, phone, email)
        VALUES (?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $_SESSION['user_id'],
        $name,
        $relationship,
        $phone,
        $email
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Emergency contact added successfully.'
    ]);
} catch (PDOException $e) {
    error_log('Database error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'An error occurred while adding the contact. Please try again.'
    ]);
}
?>