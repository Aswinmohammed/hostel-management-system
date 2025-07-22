<?php
require_once '../includes/session.php';
require_once '../includes/hostel_functions.php';

requireLogin();

header('Content-Type: application/json');

$hostel_id = $_GET['hostel_id'] ?? null;
$floor = $_GET['floor'] ?? null;

if (!$hostel_id || !$floor) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing hostel_id or floor parameter']);
    exit();
}

try {
    $hostelManager = new HostelManager();
    $rooms = $hostelManager->getRoomsByFloor($hostel_id, $floor);
    
    echo json_encode($rooms);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch rooms']);
}
?>
