<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/hostel_functions.php';

requireLogin();

header('Content-Type: application/json');

$room_id = $_GET['room_id'] ?? null;

if (!$room_id) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing room_id parameter']);
    exit();
}

try {
    $hostelManager = new HostelManager();
    $roommates = $hostelManager->getRoommates($room_id);
    
    echo json_encode($roommates);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch roommates']);
}
?>
