<?php
/**
 * API: Log Click
 * Local click and page-view tracking endpoint.
 */

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Method Not Allowed."]);
    exit;
}

$raw_input = file_get_contents("php://input");
$decoded_input = json_decode($raw_input, true);
$action = isset($decoded_input['action']) ? trim($decoded_input['action']) : '';

$valid_actions = ['page_view', 'whatsapp_click', 'instagram_click'];

if (!in_array($action, $valid_actions)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Invalid action. Supported: page_view, whatsapp_click, instagram_click."]);
    exit;
}

$file_path = __DIR__ . "/../data/stats.json";

// Ensure data directory and file exist
if (!is_dir(dirname($file_path))) {
    mkdir(dirname($file_path), 0755, true);
}

// Read current stats
if (file_exists($file_path)) {
    $stats = json_decode(file_get_contents($file_path), true);
    if ($stats === null) {
        $stats = ["page_views" => 0, "whatsapp_clicks" => 0, "instagram_clicks" => 0];
    }
} else {
    $stats = ["page_views" => 0, "whatsapp_clicks" => 0, "instagram_clicks" => 0];
}

// Update specific stat
switch ($action) {
    case 'page_view':
        $stats['page_views']++;
        break;
    case 'whatsapp_click':
        $stats['whatsapp_clicks']++;
        break;
    case 'instagram_click':
        $stats['instagram_clicks']++;
        break;
}

// Write back atomically
$json_data = json_encode($stats, JSON_PRETTY_PRINT);
if (file_put_contents($file_path, $json_data, LOCK_EX)) {
    echo json_encode(["status" => "success", "action" => $action, "stats" => $stats]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Failed to update stats database."]);
}
?>
