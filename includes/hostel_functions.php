<?php
require_once __DIR__ . '/../config/database.php';

class HostelManager {
    private $conn;
    
    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }
    
    public function getHostels() {
        $query = "SELECT * FROM hostels ORDER BY name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRoomsByFloor($hostel_id, $floor) {
        $query = "SELECT r.*, h.name as hostel_name, 
                  COUNT(s.id) as occupied_beds
                  FROM rooms r
                  LEFT JOIN hostels h ON r.hostel_id = h.id
                  LEFT JOIN students s ON r.id = s.room_id
                  WHERE r.hostel_id = :hostel_id AND r.floor_number = :floor
                  GROUP BY r.id, h.name
                  ORDER BY r.room_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':hostel_id', $hostel_id);
        $stmt->bindParam(':floor', $floor);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getRoommates($room_id) {
        $query = "SELECT s.*, r.room_number, h.name as hostel_name
                  FROM students s
                  LEFT JOIN rooms r ON s.room_id = r.id
                  LEFT JOIN hostels h ON r.hostel_id = h.id
                  WHERE s.room_id = :room_id
                  ORDER BY s.bed_number";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':room_id', $room_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getAdminStats() {
        $stats = [];
        
        // Total students
        $query = "SELECT COUNT(*) as count FROM students";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_students'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Total rooms
        $query = "SELECT COUNT(*) as count FROM rooms";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['total_rooms'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Occupied rooms
        $query = "SELECT COUNT(DISTINCT room_id) as count FROM students WHERE room_id IS NOT NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['occupied_rooms'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Pending leaves
        $query = "SELECT COUNT(*) as count FROM leave_requests WHERE status = 'pending'";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['pending_leaves'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        // Open complaints
        $query = "SELECT COUNT(*) as count FROM complaints WHERE status IN ('open', 'in_progress')";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stats['open_complaints'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        
        return $stats;
    }
    
    public function getAnnouncements($limit = 10) {
        $query = "SELECT a.*, u.username as creator_name
                  FROM announcements a
                  LEFT JOIN users u ON a.created_by = u.id
                  ORDER BY a.created_at DESC
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getLeaveRequests($limit = 20) {
        $query = "SELECT l.*, s.full_name as student_name
                  FROM leave_requests l
                  LEFT JOIN students s ON l.student_id = s.id
                  ORDER BY l.created_at DESC
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getComplaints($limit = 20) {
        $query = "SELECT c.*, s.full_name as student_name
                  FROM complaints c
                  LEFT JOIN students s ON c.student_id = s.id
                  ORDER BY c.submitted_at DESC
                  LIMIT :limit";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getStudentByUserId($user_id) {
        $query = "SELECT s.*, r.room_number, h.name as hostel_name
                  FROM students s
                  LEFT JOIN rooms r ON s.room_id = r.id
                  LEFT JOIN hostels h ON r.hostel_id = h.id
                  WHERE s.user_id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function getStudentLeaves($student_id) {
        $query = "SELECT * FROM leave_requests WHERE student_id = :student_id ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getStudentComplaints($student_id) {
        $query = "SELECT * FROM complaints WHERE student_id = :student_id ORDER BY submitted_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
