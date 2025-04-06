<?php
require_once '../includes/config.php';
require_once '../includes/functions.php';

header('Content-Type: application/json');

// Get user's location from request
$data = json_decode(file_get_contents('php://input'), true);
$lat = $data['latitude'] ?? null;
$lon = $data['longitude'] ?? null;

if (!$lat || !$lon) {
    http_response_code(400);
    echo json_encode(['error' => 'Latitude and longitude are required']);
    exit;
}

try {
    // Search for pharmacies using Nominatim API
    $query = urlencode("pharmacy near {$lat},{$lon}");
    $url = OPENSTREETMAP_API_URL . "?q={$query}&format=json&limit=10";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'MediQuick/1.0');
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode !== 200) {
        throw new Exception('Failed to fetch pharmacy data');
    }
    
    $pharmacies = json_decode($response, true);
    
    // Format the response
    $formattedPharmacies = array_map(function($pharmacy) use ($lat, $lon) {
        $distance = get_distance(
            $lat,
            $lon,
            floatval($pharmacy['lat']),
            floatval($pharmacy['lon'])
        );
        
        return [
            'id' => $pharmacy['osm_id'],
            'name' => $pharmacy['display_name'],
            'address' => $pharmacy['display_name'],
            'latitude' => floatval($pharmacy['lat']),
            'longitude' => floatval($pharmacy['lon']),
            'distance' => format_distance($distance)
        ];
    }, $pharmacies);
    
    // Sort by distance
    usort($formattedPharmacies, function($a, $b) {
        return $a['distance'] <=> $b['distance'];
    });
    
    echo json_encode([
        'success' => true,
        'pharmacies' => $formattedPharmacies
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'error' => 'An error occurred while fetching pharmacies',
        'message' => $e->getMessage()
    ]);
}