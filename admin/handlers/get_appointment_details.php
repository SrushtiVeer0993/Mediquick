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

    // Check if request is GET and has appointment ID
    if ($_SERVER['REQUEST_METHOD'] !== 'GET' || !isset($_GET['id'])) {
        throw new Exception('Invalid request');
    }

    $db = Database::getInstance();
    $appointment_id = (int)$_GET['id'];

    // Get appointment details with user and doctor information
    $query = "
        SELECT 
            a.*,
            u.name as user_name,
            u.email as user_email,
            u.phone as user_phone,
            d.name as doctor_name,
            d.specialization as doctor_specialization,
            d.qualification as doctor_qualification,
            d.experience as doctor_experience,
            d.consultation_fee,
            d.rating
        FROM appointments a
        JOIN users u ON a.user_id = u.id
        JOIN doctors d ON a.doctor_id = d.id
        WHERE a.id = ?
    ";
    
    $stmt = $db->prepare($query);
    $stmt->execute([$appointment_id]);
    $appointment = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($appointment) {
        echo json_encode(['success' => true, 'appointment' => $appointment]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Appointment not found']);
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Error fetching appointment details']);
}
?> 