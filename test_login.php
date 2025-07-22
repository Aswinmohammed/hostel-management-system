<?php
// Test script to verify login functionality
require_once 'config/database.php';

echo "<h2>Testing Login System</h2>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    // Test database connection
    echo "<p>✅ Database connection successful</p>";
    
    // Check if users exist
    $stmt = $conn->prepare("SELECT username, role FROM users");
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<h3>Users in database:</h3>";
    foreach ($users as $user) {
        echo "<p>- {$user['username']} ({$user['role']})</p>";
    }
    
    // Test password verification
    echo "<h3>Testing password verification:</h3>";
    
    $test_credentials = [
        ['admin', 'admin123'],
        ['john.doe', 'student123']
    ];
    
    foreach ($test_credentials as $cred) {
        $username = $cred[0];
        $password = $cred[1];
        
        $stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $is_valid = password_verify($password, $user['password']);
            echo "<p>{$username} / {$password}: " . ($is_valid ? "✅ VALID" : "❌ INVALID") . "</p>";
        } else {
            echo "<p>{$username}: ❌ USER NOT FOUND</p>";
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
}
?>
