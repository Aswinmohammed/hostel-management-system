<?php
require_once __DIR__ . '/../includes/session.php';
require_once __DIR__ . '/../includes/hostel_functions.php';

requireStudent();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit();
}

$reason = $_POST['reason'] ?? '';
$start_date = $_POST['start_date'] ?? '';
$end_date = $_POST['end_date'] ?? '';

if (empty($reason) || empty($start_date) || empty($end_date)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
    exit();
}

// Validate dates
if (strtotime($start_date) < strtotime(date('Y-m-d'))) {
    echo json_encode(['success' => false, 'message' => 'Start date cannot be in the past']);
    exit();
}

if (strtotime($end_date) < strtotime($start_date)) {
    echo json_encode(['success' => false, 'message' => 'End date must be after start date']);
    exit();
}

try {
    $hostelManager = new HostelManager();
    $student = $hostelManager->getStudentByUserId($_SESSION['user_id']);
    
    if (!$student) {
        echo json_encode(['success' => false, 'message' => 'Student profile not found']);
        exit();
    }
    
    $database = new Database();
    $conn = $database->getConnection();
    
    $query = "INSERT INTO leave_requests (student_id, reason, start_date, end_date) VALUES (:student_id, :reason, :start_date, :end_date)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':student_id', $student['id']);
    $stmt->bindParam(':reason', $reason);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Leave request submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit leave request']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred while processing your request']);
}
?>
