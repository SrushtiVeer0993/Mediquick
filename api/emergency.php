<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Get request data
$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? null;

switch ($action) {
    case 'send_alert':
        $user_id = $data['user_id'] ?? null;
        $location = $data['location'] ?? null;
        
        if (!$user_id || !$location) {
            http_response_code(400);
            echo json_encode(['error' => 'User ID and location are required']);
            exit;
        }
        
        try {
            // Get emergency contacts
            $contacts = get_emergency_contacts($user_id);
            
            // Send SMS alerts using Twilio API
            $successCount = 0;
            foreach ($contacts as $contact) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/" . TWILIO_ACCOUNT_SID . "/Messages.json");
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
                    'To' => $contact['phone'],
                    'From' => TWILIO_PHONE_NUMBER,
                    'Body' => "EMERGENCY ALERT: User needs assistance at {$location}. Click here for directions: https://www.openstreetmap.org/?mlat={$location['lat']}&mlon={$location['lng']}"
                ]));
                curl_setopt($ch, CURLOPT_USERPWD, TWILIO_ACCOUNT_SID . ":" . TWILIO_AUTH_TOKEN);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                
                $response = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);
                
                if ($httpCode === 201) {
                    $successCount++;
                }
            }
            
            echo json_encode([
                'success' => true,
                'message' => "Emergency alerts sent to {$successCount} contacts"
            ]);
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Failed to send emergency alert',
                'message' => $e->getMessage()
            ]);
        }
        break;
        
    case 'get_contacts':
        $user_id = $data['user_id'] ?? null;
        
        if (!$user_id) {
            http_response_code(400);
            echo json_encode(['error' => 'User ID is required']);
            exit;
        }
        
        try {
            $contacts = get_emergency_contacts($user_id);
            echo json_encode([
                'success' => true,
                'contacts' => $contacts
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'error' => 'Failed to fetch emergency contacts',
                'message' => $e->getMessage()
            ]);
        }
        break;
        
    default:
        http_response_code(400);
        echo json_encode(['error' => 'Invalid action']);
        break;
}