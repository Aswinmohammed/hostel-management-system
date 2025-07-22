<?php
// This script creates properly hashed passwords for the demo users
// Run this once to generate the correct password hashes

require_once '../config/database.php';

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Clear existing users
    $conn->exec("DELETE FROM complaints");
    $conn->exec("DELETE FROM leave_requests");
    $conn->exec("DELETE FROM students");
    $conn->exec("DELETE FROM users");
    
    // Reset auto increment
    $conn->exec("ALTER TABLE users AUTO_INCREMENT = 1");
    $conn->exec("ALTER TABLE students AUTO_INCREMENT = 1");
    
    // Create admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $stmt->execute(['admin', $admin_password, 'admin']);
    echo "Admin user created successfully\n";
    
    // Create student users
    $student_password = password_hash('student123', PASSWORD_DEFAULT);
    
    $students = [
        ['john.doe', 'student'],
        ['jane.smith', 'student'],
        ['mike.johnson', 'student'],
        ['sarah.wilson', 'student']
    ];
    
    foreach ($students as $student) {
        $stmt->execute([$student[0], $student_password, $student[1]]);
        echo "Student user {$student[0]} created successfully\n";
    }
    
    // Create student profiles
    $student_profiles = [
        [2, 'John Doe', 'john.doe@university.edu', 'Computer Science', 1, 1, '+1234567890'],
        [3, 'Jane Smith', 'jane.smith@university.edu', 'Electrical Engineering', 1, 2, '+1234567891'],
        [4, 'Mike Johnson', 'mike.johnson@university.edu', 'Mechanical Engineering', 1, 3, '+1234567892'],
        [5, 'Sarah Wilson', 'sarah.wilson@university.edu', 'Civil Engineering', 2, 1, '+1234567893']
    ];
    
    $stmt = $conn->prepare("INSERT INTO students (user_id, full_name, email, course_name, room_id, bed_number, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($student_profiles as $profile) {
        $stmt->execute($profile);
        echo "Student profile for {$profile[1]} created successfully\n";
    }
    
    echo "\nAll users created successfully!\n";
    echo "Login credentials:\n";
    echo "Admin: admin / admin123\n";
    echo "Student: john.doe / student123\n";
    echo "Student: jane.smith / student123\n";
    echo "Student: mike.johnson / student123\n";
    echo "Student: sarah.wilson / student123\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>
