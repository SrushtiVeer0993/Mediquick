<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Get consultation details from request
$data = json_decode(file_get_contents('php://input'), true);
$doctor_id = $data['doctor_id'] ?? null;
$user_id = $data['user_id'] ?? null;

if (!$doctor_id || !$user_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Doctor ID and User ID are required']);
    exit;
}

try {
    // Generate a unique room name
    $room_name = 'mediquick-' . uniqid();
    
    // Create Jitsi Meet room configuration
    $room_config = [
        'roomName' => $room_name,
        'width' => '100%',
        'height' => '100%',
        'parentNode' => '#meet',
        'configOverwrite' => [
            'startWithAudioMuted' => false,
            'startWithVideoMuted' => false
        ],
        'interfaceConfigOverwrite' => [
            'filmStripOnly' => false,
            'SHOW_JITSI_WATERMARK' => false
        ],
        'userInfo' => [
            'displayName' => 'Patient ' . $user_id
        ]
    ];
    
    // Store consultation session in database
    $db = Database::getInstance();
    $session_id = $db->insert('consultation_sessions', [
        'doctor_id' => $doctor_id,
        'user_id' => $user_id,
        'room_name' => $room_name,
        'status' => 'scheduled',
        'created_at' => date('Y-m-d H:i:s')
    ]);
    
    echo json_encode([
        'success' => true,
        'room_name' => $room_name,
        'session_id' => $session_id,
        'jitsi_config' => $room_config,
        'domain' => JITSI_MEET_DOMAIN
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to create consultation session',
        'message' => $e->getMessage()
    ]);
}