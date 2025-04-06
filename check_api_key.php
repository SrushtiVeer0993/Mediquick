<?php
require_once 'includes/config.php';

// Function to check if the API key is valid
function checkGoogleMapsApiKey($apiKey) {
    $url = "https://maps.googleapis.com/maps/api/geocode/json?address=test&key=" . $apiKey;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        $data = json_decode($response, true);
        if (isset($data['status']) && $data['status'] == 'OK') {
            return true;
        }
    }
    
    return false;
}

// Check the API key
$apiKey = GOOGLE_MAPS_API_KEY;
$isValid = checkGoogleMapsApiKey($apiKey);

// Return the result
header('Content-Type: application/json');
echo json_encode([
    'valid' => $isValid,
    'message' => $isValid ? 'API key is valid' : 'API key is invalid or has restrictions'
]); 