<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Get chat message from request
$data = json_decode(file_get_contents('php://input'), true);
$message = $data['message'] ?? '';
$uid = $data['uid'] ?? uniqid(); // Unique user ID for conversation

if (empty($message)) {
    http_response_code(400);
    echo json_encode(['error' => 'Message is required']);
    exit;
}

// Predefined responses for common medical queries
$responses = [
    'hello' => 'Hello! I\'m your AI Health Assistant. How can I help you today?',
    'hi' => 'Hi there! I\'m here to help with your health-related questions. What would you like to know?',
    'symptoms' => 'I can help you understand your symptoms. Please describe what you\'re experiencing in detail.',
    'fever' => 'A fever is usually a sign that your body is fighting an infection. If your temperature is above 100.4°F (38°C), you should rest, stay hydrated, and consider consulting a healthcare provider.',
    'headache' => 'Headaches can have many causes. Common remedies include rest, hydration, and over-the-counter pain relievers. If headaches are severe or persistent, please consult a healthcare provider.',
    'cough' => 'A cough can be caused by various factors. Stay hydrated, use a humidifier, and consider over-the-counter remedies. If it persists for more than a week or is severe, consult a healthcare provider.',
    'pain' => 'Pain is your body\'s way of signaling that something might be wrong. Please describe the location, intensity, and duration of your pain for better assistance.',
    'emergency' => 'If you\'re experiencing a medical emergency, please call emergency services immediately or use our emergency button for quick assistance.',
    'medication' => 'For medication-related questions, I can provide general information. However, always consult your healthcare provider for specific medication advice.',
    'thank' => 'You\'re welcome! Feel free to ask if you have any other health-related questions.',
    'bye' => 'Goodbye! Take care and stay healthy. Remember, I\'m here if you need more assistance.',
    'default' => 'I understand you\'re asking about health-related matters. For the best advice, please provide more details about your symptoms or concerns. Remember, I can provide general information but cannot replace professional medical advice.'
];

// Convert message to lowercase for matching
$message = strtolower($message);

// Check for keywords in the message
$response = $responses['default'];
foreach ($responses as $keyword => $reply) {
    if (strpos($message, $keyword) !== false) {
        $response = $reply;
        break;
    }
}

// Add some context-aware responses
if (strpos($message, 'help') !== false) {
    $response = 'I can help you with general health information, symptom understanding, and basic medical guidance. What specific information are you looking for?';
} elseif (strpos($message, 'doctor') !== false) {
    $response = 'If you need to see a doctor, I can help you understand when it\'s appropriate to seek medical attention. Please describe your symptoms or concerns.';
} elseif (strpos($message, 'appointment') !== false) {
    $response = 'For scheduling appointments, please contact your healthcare provider directly. I can help you prepare for your appointment by gathering information about your symptoms.';
}

// Add disclaimer
$response .= "\n\nNote: This is general information only. For specific medical advice, please consult a healthcare professional.";

echo json_encode([
    'success' => true,
    'response' => $response,
    'uid' => $uid,
    'sources' => ['MediQuick Health Database']
]);