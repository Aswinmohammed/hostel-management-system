<?php
// Complete database reset and setup script
require_once '../config/database.php';

echo "<h2>Hostel Management System - Database Reset</h2>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<p>🔄 Starting database reset...</p>";
    
    // Step 1: Clear all existing data
    echo "<p>🗑️ Clearing existing data...</p>";
    $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
    $conn->exec("DELETE FROM complaints");
    $conn->exec("DELETE FROM leave_requests");
    $conn->exec("DELETE FROM announcements");
    $conn->exec("DELETE FROM students");
    $conn->exec("DELETE FROM users");
    $conn->exec("DELETE FROM rooms");
    $conn->exec("DELETE FROM hostels");
    $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
    
    // Reset auto increment
    $conn->exec("ALTER TABLE hostels AUTO_INCREMENT = 1");
    $conn->exec("ALTER TABLE rooms AUTO_INCREMENT = 1");
    $conn->exec("ALTER TABLE users AUTO_INCREMENT = 1");
    $conn->exec("ALTER TABLE students AUTO_INCREMENT = 1");
    $conn->exec("ALTER TABLE announcements AUTO_INCREMENT = 1");
    $conn->exec("ALTER TABLE leave_requests AUTO_INCREMENT = 1");
    $conn->exec("ALTER TABLE complaints AUTO_INCREMENT = 1");
    
    echo "<p>✅ Data cleared successfully</p>";
    
    // Step 2: Insert hostels
    echo "<p>🏢 Creating hostels...</p>";
    $stmt = $conn->prepare("INSERT INTO hostels (name, total_floors, rooms_per_floor, beds_per_room) VALUES (?, ?, ?, ?)");
    $stmt->execute(['Hostel-01', 3, 20, 4]);
    $stmt->execute(['Hostel-02', 3, 20, 4]);
    echo "<p>✅ Hostels created</p>";
    
    // Step 3: Insert rooms
    echo "<p>🚪 Creating rooms...</p>";
    $room_stmt = $conn->prepare("INSERT INTO rooms (hostel_id, floor_number, room_number) VALUES (?, ?, ?)");
    
    // Create rooms for both hostels
    for ($hostel = 1; $hostel <= 2; $hostel++) {
        for ($floor = 1; $floor <= 3; $floor++) {
            for ($room = 1; $room <= 20; $room++) {
                $room_number = $floor . str_pad($room, 2, '0', STR_PAD_LEFT);
                $room_stmt->execute([$hostel, $floor, $room_number]);
            }
        }
    }
    echo "<p>✅ 120 rooms created (60 per hostel)</p>";
    
    // Step 4: Create users with proper password hashes
    echo "<p>👤 Creating users...</p>";
    
    // Create admin user
    $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
    $user_stmt = $conn->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
    $user_stmt->execute(['admin', $admin_password, 'admin']);
    echo "<p>✅ Admin user created (admin / admin123)</p>";
    
    // Create student users
    $student_password = password_hash('student123', PASSWORD_DEFAULT);
    $students = ['john.doe', 'jane.smith', 'mike.johnson', 'sarah.wilson'];
    
    foreach ($students as $username) {
        $user_stmt->execute([$username, $student_password, 'student']);
        echo "<p>✅ Student user created ({$username} / student123)</p>";
    }
    
    // Step 5: Create student profiles
    echo "<p>📋 Creating student profiles...</p>";
    $student_profiles = [
        [2, 'John Doe', 'john.doe@university.edu', 'Computer Science', 1, 1, '+1234567890'],
        [3, 'Jane Smith', 'jane.smith@university.edu', 'Electrical Engineering', 1, 2, '+1234567891'],
        [4, 'Mike Johnson', 'mike.johnson@university.edu', 'Mechanical Engineering', 1, 3, '+1234567892'],
        [5, 'Sarah Wilson', 'sarah.wilson@university.edu', 'Civil Engineering', 2, 1, '+1234567893']
    ];
    
    $profile_stmt = $conn->prepare("INSERT INTO students (user_id, full_name, email, course_name, room_id, bed_number, phone) VALUES (?, ?, ?, ?, ?, ?, ?)");
    
    foreach ($student_profiles as $profile) {
        $profile_stmt->execute($profile);
        echo "<p>✅ Profile created for {$profile[1]}</p>";
    }
    
    // Step 6: Create sample announcements
    echo "<p>📢 Creating announcements...</p>";
    $announcement_stmt = $conn->prepare("INSERT INTO announcements (title, content, created_by, category) VALUES (?, ?, ?, ?)");
    
    $announcements = [
        ['Welcome to New Academic Year', 'Welcome all students to the new academic year. Please ensure you follow all hostel rules and regulations.', 1, 'general'],
        ['Maintenance Schedule', 'Routine maintenance will be conducted on weekends. Please cooperate with the maintenance staff.', 1, 'maintenance'],
        ['Mess Timings Updated', 'New mess timings: Breakfast 7-9 AM, Lunch 12-2 PM, Dinner 7-9 PM', 1, 'mess']
    ];
    
    foreach ($announcements as $announcement) {
        $announcement_stmt->execute($announcement);
    }
    echo "<p>✅ Sample announcements created</p>";
    
    // Step 7: Create sample leave requests
    echo "<p>📅 Creating sample leave requests...</p>";
    $leave_stmt = $conn->prepare("INSERT INTO leave_requests (student_id, reason, start_date, end_date, status) VALUES (?, ?, ?, ?, ?)");
    
    $leaves = [
        [1, 'Family emergency - need to visit home', '2024-01-15', '2024-01-20', 'pending'],
        [2, 'Medical appointment in hometown', '2024-01-10', '2024-01-12', 'approved'],
        [3, 'Wedding ceremony to attend', '2024-02-01', '2024-02-05', 'pending']
    ];
    
    foreach ($leaves as $leave) {
        $leave_stmt->execute($leave);
    }
    echo "<p>✅ Sample leave requests created</p>";
    
    // Step 8: Create sample complaints
    echo "<p>💬 Creating sample complaints...</p>";
    $complaint_stmt = $conn->prepare("INSERT INTO complaints (student_id, issue_type, description, status) VALUES (?, ?, ?, ?)");
    
    $complaints = [
        [1, 'Maintenance', 'Air conditioning not working in room 101', 'open'],
        [2, 'Facilities', 'Hot water not available in morning hours', 'in_progress'],
        [3, 'Noise', 'Loud music from neighboring room disturbing studies', 'open'],
        [4, 'Cleanliness', 'Common area needs better cleaning schedule', 'resolved']
    ];
    
    foreach ($complaints as $complaint) {
        $complaint_stmt->execute($complaint);
    }
    echo "<p>✅ Sample complaints created</p>";
    
    // Step 9: Verify the setup
    echo "<h3>🔍 Verification</h3>";
    
    // Count records
    $tables = ['hostels', 'rooms', 'users', 'students', 'announcements', 'leave_requests', 'complaints'];
    foreach ($tables as $table) {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM {$table}");
        $stmt->execute();
        $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
        echo "<p>📊 {$table}: {$count} records</p>";
    }
    
    // Test password verification
    echo "<h3>🔐 Password Verification Test</h3>";
    $test_users = [
        ['admin', 'admin123'],
        ['john.doe', 'student123']
    ];
    
    foreach ($test_users as $test) {
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->execute([$test[0]]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $is_valid = password_verify($test[1], $user['password']);
            echo "<p>{$test[0]} / {$test[1]}: " . ($is_valid ? "✅ VALID" : "❌ INVALID") . "</p>";
        } else {
            echo "<p>{$test[0]}: ❌ USER NOT FOUND</p>";
        }
    }
    
    echo "<h2>🎉 Database Setup Complete!</h2>";
    echo "<div style='background: #f0f9ff; border: 1px solid #0ea5e9; padding: 1rem; border-radius: 8px; margin: 1rem 0;'>";
    echo "<h3>Login Credentials:</h3>";
    echo "<p><strong>Admin:</strong> admin / admin123</p>";
    echo "<p><strong>Students:</strong></p>";
    echo "<ul>";
    echo "<li>john.doe / student123</li>";
    echo "<li>jane.smith / student123</li>";
    echo "<li>mike.johnson / student123</li>";
    echo "<li>sarah.wilson / student123</li>";
    echo "</ul>";
    echo "<p><a href='../login.php' style='color: #0ea5e9; text-decoration: none; font-weight: bold;'>→ Go to Login Page</a></p>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    echo "<p>Stack trace: " . $e->getTraceAsString() . "</p>";
}
?>
