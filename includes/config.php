<?php
// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'mediquick');
define('DB_USER', 'root');
define('DB_PASS', '');

// API Configuration
define('DISEASE_API_KEY', 'your_disease_sh_api_key');
define('OPENSTREETMAP_API_URL', 'https://nominatim.openstreetmap.org/search');
define('TEXTBELT_API_KEY', 'your_textbelt_api_key');
define('BRAINSHOP_API_KEY', 'your_brainshop_api_key');
define('BRAINSHOP_BOT_ID', 'your_bot_id');
define('JITSI_MEET_DOMAIN', 'meet.jit.si');
define('RESPONSIVE_VOICE_KEY', 'your_responsive_voice_key');
define('OPENHEALTH_API_KEY', 'your_openhealth_api_key');
define('JITSI_MEET_APP_ID', 'your_jitsi_app_id');
define('JITSI_MEET_API_KEY', 'your_jitsi_api_key');
define('MEDGPT_API_KEY', 'your_medgpt_api_key');
define('MEDGPT_API_URL', 'https://api.medgpt.com/v1');
define('TWILIO_ACCOUNT_SID', 'your_twilio_account_sid');
define('TWILIO_AUTH_TOKEN', 'your_twilio_auth_token');
define('TWILIO_PHONE_NUMBER', 'your_twilio_phone_number');

// Load environment variables if .env file exists
if (file_exists(__DIR__ . '/../.env')) {
    $envFile = file_get_contents(__DIR__ . '/../.env');
    $lines = explode("\n", $envFile);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            if (!empty($key)) {
                $_ENV[$key] = $value;
            }
        }
    }
}

// OpenDisease API Configuration - Load from environment variables
define('OPENDISEASE_API_URL', isset($_ENV['OPENDISEASE_API_URL']) ? $_ENV['OPENDISEASE_API_URL'] : 'https://api.opendisease.com/v1');

// API Keys
// Google Maps API key removed - now using OpenStreetMap with Leaflet.js

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Time Zone
date_default_timezone_set('UTC');

// Database Connection
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper Functions
if (!function_exists('sanitize_input')) {
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function require_login() {
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

// Emergency Contact Numbers
$emergency_numbers = [
    'ambulance' => '102',
    'police' => '100',
    'fire' => '101',
    'poison_control' => '1800-116-117'
];
?>