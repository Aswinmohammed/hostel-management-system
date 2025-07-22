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

$issue_type = $_POST['issue_type'] ?? '';
$description = $_POST['description'] ?? '';

if (empty($issue_type) || empty($description)) {
    echo json_encode(['success' => false, 'message' => 'All fields are required']);
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
    
    $query = "INSERT INTO complaints (student_id, issue_type, description) VALUES (:student_id, :issue_type, :description)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':student_id', $student['id']);
    $stmt->bindParam(':issue_type', $issue_type);
    $stmt->bindParam(':description', $description);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Complaint submitted successfully']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit complaint']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'An error occurred while processing your request']);
}
?>
