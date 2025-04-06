<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Please login to book a consultation']);
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit();
}

// Get and validate input data
$pharmacy_id = filter_input(INPUT_POST, 'pharmacy_id', FILTER_VALIDATE_INT);
$consultation_date = filter_input(INPUT_POST, 'consultation_date', FILTER_SANITIZE_STRING);
$consultation_time = filter_input(INPUT_POST, 'consultation_time', FILTER_SANITIZE_STRING);
$consultation_type = filter_input(INPUT_POST, 'consultation_type', FILTER_SANITIZE_STRING);
$symptoms = filter_input(INPUT_POST, 'symptoms', FILTER_SANITIZE_STRING);

// Validate required fields
if (!$pharmacy_id || !$consultation_date || !$consultation_time || !$consultation_type || !$symptoms) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

// Validate date is not in the past
if (strtotime($consultation_date) < strtotime(date('Y-m-d'))) {
    echo json_encode(['success' => false, 'message' => 'Consultation date cannot be in the past']);
    exit();
}

try {
    $db = Database::getInstance();
    
    // Check if pharmacy exists and is active
    $pharmacy = $db->fetch("SELECT * FROM pharmacies WHERE id = ? AND status = 'active'", [$pharmacy_id]);
    if (!$pharmacy) {
        echo json_encode(['success' => false, 'message' => 'Pharmacy is not available']);
        exit();
    }
    
    // Check if the time slot is available
    $existing_consultation = $db->fetch(
        "SELECT * FROM consultations WHERE pharmacy_id = ? AND consultation_date = ? AND consultation_time = ? AND status != 'cancelled'",
        [$pharmacy_id, $consultation_date, $consultation_time]
    );
    
    if ($existing_consultation) {
        echo json_encode(['success' => false, 'message' => 'This time slot is already booked']);
        exit();
    }
    
    // Insert the consultation
    $consultation_data = [
        'user_id' => $_SESSION['user_id'],
        'pharmacy_id' => $pharmacy_id,
        'consultation_date' => $consultation_date,
        'consultation_time' => $consultation_time,
        'consultation_type' => $consultation_type,
        'symptoms' => $symptoms,
        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $db->insert('consultations', $consultation_data);
    
    // Send notification to pharmacy (you can implement this later)
    // sendNotification($pharmacy_id, 'New consultation request');
    
    echo json_encode(['success' => true, 'message' => 'Consultation booked successfully']);
    
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error booking consultation. Please try again.']);
} 