<?php
require_once '../includes/session.php';
require_once '../includes/hostel_functions.php';

requireLogin();

header('Content-Type: application/json');

try {
    $hostelManager = new HostelManager();
    $hostels = $hostelManager->getHostels();
    
    echo json_encode($hostels);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Failed to fetch hostels']);
}
?>
