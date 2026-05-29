<?php
/**
 * API: Get Stats
 * Returns local analytics statistics for the Admin Panel dashboard.
 */

session_start();

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Authorize request - check if session exists
$is_authenticated = false;
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    $is_authenticated = true;
}

// Support local cookie sessions for smooth testing
if (!$is_authenticated) {
    if (isset($_COOKIE['admin_session']) && $_COOKIE['admin_session'] === 'active') {
        $is_authenticated = true;
    }
}

if (!$is_authenticated) {
    http_response_code(403);
    echo json_encode(["status" => "error", "message" => "Forbidden: Admin session not active."]);
    exit;
}

$file_path = __DIR__ . "/../data/stats.json";

if (file_exists($file_path)) {
    $raw_data = file_get_contents($file_path);
    $stats = json_decode($raw_data, true);
    
    if ($stats !== null) {
        echo json_encode(["status" => "success", "data" => $stats]);
    } else {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "Failed to decode stats data."]);
    }
} else {
    // If stats file doesn't exist, return default zeros
    $default_stats = ["page_views" => 0, "whatsapp_clicks" => 0, "instagram_clicks" => 0];
    echo json_encode(["status" => "success", "data" => $default_stats]);
}
?>
