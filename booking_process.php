<?php
session_start();
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=booking_appointment.php');
    exit();
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: consultation.php');
    exit();
}

// Get and validate input data
$doctor_id = filter_input(INPUT_POST, 'doctor_id', FILTER_VALIDATE_INT);
$patient_name = htmlspecialchars(trim($_POST['patient_name'] ?? ''));
$patient_phone = htmlspecialchars(trim($_POST['patient_phone'] ?? ''));
$patient_email = filter_input(INPUT_POST, 'patient_email', FILTER_SANITIZE_EMAIL);
$appointment_date = htmlspecialchars(trim($_POST['appointment_date'] ?? ''));
$appointment_time = htmlspecialchars(trim($_POST['appointment_time'] ?? ''));
$appointment_type = htmlspecialchars(trim($_POST['appointment_type'] ?? 'in-person'));
$symptoms = htmlspecialchars(trim($_POST['symptoms'] ?? ''));

// Validate required fields
if (!$doctor_id || !$patient_name || !$patient_phone || !$patient_email || !$appointment_date || !$appointment_time) {
    $_SESSION['booking_error'] = 'Please fill in all required fields';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Validate email format
if (!filter_var($patient_email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['booking_error'] = 'Invalid email format';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Validate date is not in the past
if (strtotime($appointment_date) < strtotime(date('Y-m-d'))) {
    $_SESSION['booking_error'] = 'Appointment date cannot be in the past';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

try {
    $db = Database::getInstance();
    
    // Check if doctor exists
    $doctor = $db->fetch("SELECT * FROM doctors WHERE id = ?", [$doctor_id]);
    if (!$doctor) {
        $_SESSION['booking_error'] = 'Doctor not found';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
    // Check if the time slot is available
    $existing_appointment = $db->fetch(
        "SELECT * FROM appointments WHERE doctor_id = ? AND appointment_date = ? AND appointment_time = ? AND status != 'cancelled'",
        [$doctor_id, $appointment_date, $appointment_time]
    );
    
    if ($existing_appointment) {
        $_SESSION['booking_error'] = 'This time slot is already booked';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
    // Insert the appointment with only the fields that exist in the database
    $appointment_data = [
        'user_id' => $_SESSION['user_id'],
        'doctor_id' => $doctor_id,
        'appointment_date' => $appointment_date,
        'appointment_time' => $appointment_time,
        'consultation_type' => $appointment_type,
        'status' => 'scheduled',
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    // Try to insert the appointment
    try {
        $appointment_id = $db->insert('appointments', $appointment_data);
        
        // Store appointment details in session for confirmation page
        $_SESSION['booking_details'] = [
            'appointment_id' => $appointment_id,
            'doctor_name' => $doctor['name'],
            'doctor_specialization' => $doctor['specialization'] ?? 'General Physician',
            'patient_name' => $patient_name,
            'patient_phone' => $patient_phone,
            'patient_email' => $patient_email,
            'appointment_date' => $appointment_date,
            'appointment_time' => $appointment_time,
            'appointment_type' => $appointment_type,
            'symptoms' => $symptoms,
            'receipt_number' => 'REC' . str_pad($appointment_id, 6, '0', STR_PAD_LEFT),
            'booking_date' => date('Y-m-d H:i:s')
        ];
        
        // Redirect to booking confirmation page
        header('Location: booking_confirmation.php');
        exit();
    } catch (Exception $e) {
        // Log the error
        error_log("Error booking appointment: " . $e->getMessage());
        
        // Set error message
        $_SESSION['booking_error'] = 'Error booking appointment. Please try again.';
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit();
    }
    
} catch (Exception $e) {
    error_log($e->getMessage());
    $_SESSION['booking_error'] = 'Error booking appointment. Please try again.';
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}