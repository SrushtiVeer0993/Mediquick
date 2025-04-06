<?php
require_once 'db.php';

if (!function_exists('sanitize_input')) {
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

function validate_phone($phone) {
    return preg_match('/^\+?[0-9]{10,15}$/', $phone);
}

function validate_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function get_distance($lat1, $lon1, $lat2, $lon2) {
    $earth_radius = 6371; // Radius of the earth in km

    $lat_diff = deg2rad($lat2 - $lat1);
    $lon_diff = deg2rad($lon2 - $lon1);

    $a = sin($lat_diff/2) * sin($lat_diff/2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($lon_diff/2) * sin($lon_diff/2);
    
    $c = 2 * atan2(sqrt($a), sqrt(1-$a));
    $distance = $earth_radius * $c;

    return $distance;
}

function format_distance($distance) {
    if ($distance < 1) {
        return round($distance * 1000) . 'm';
    }
    return round($distance, 1) . 'km';
}

function get_nearby_pharmacies($lat, $lon, $radius = 5) {
    $db = Database::getInstance();
    $pharmacies = $db->fetchAll("SELECT * FROM pharmacies");
    
    $nearby = [];
    foreach ($pharmacies as $pharmacy) {
        $distance = get_distance($lat, $lon, $pharmacy['latitude'], $pharmacy['longitude']);
        if ($distance <= $radius) {
            $pharmacy['distance'] = $distance;
            $nearby[] = $pharmacy;
        }
    }
    
    usort($nearby, function($a, $b) {
        return $a['distance'] <=> $b['distance'];
    });
    
    return $nearby;
}

function get_emergency_contacts($user_id) {
    $db = Database::getInstance();
    return $db->fetchAll(
        "SELECT * FROM emergency_contacts WHERE user_id = ? ORDER BY name",
        [$user_id]
    );
}

function analyze_symptoms($symptoms) {
    // This is a placeholder for the actual AI/ML implementation
    // In a real implementation, this would call an AI service
    $db = Database::getInstance();
    
    $placeholders = str_repeat('?,', count($symptoms) - 1) . '?';
    $sql = "SELECT c.*, COUNT(sc.symptom_id) as match_count
            FROM conditions c
            JOIN symptom_condition sc ON c.id = sc.condition_id
            WHERE sc.symptom_id IN ($placeholders)
            GROUP BY c.id
            ORDER BY match_count DESC
            LIMIT 5";
    
    return $db->fetchAll($sql, $symptoms);
}

function send_emergency_alert($user_id, $location) {
    // This is a placeholder for the actual emergency alert implementation
    // In a real implementation, this would:
    // 1. Get user's emergency contacts
    // 2. Send alerts via SMS/email
    // 3. Notify emergency services
    $contacts = get_emergency_contacts($user_id);
    
    // Simulate sending alerts
    foreach ($contacts as $contact) {
        // Send alert to contact
        error_log("Emergency alert sent to {$contact['name']} at {$contact['phone']}");
    }
    
    return true;
}

function log_activity($user_id, $action, $details = '') {
    $db = Database::getInstance();
    try {
        $db->insert('activity_logs', [
            'user_id' => $user_id,
            'action' => $action,
            'description' => $details,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    } catch (Exception $e) {
        error_log("Failed to log activity: " . $e->getMessage());
    }
}