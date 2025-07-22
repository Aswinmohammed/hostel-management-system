<?php
// Quick database check script
require_once '../config/database.php';

echo "<h2>Database Connection Check</h2>";

try {
    $database = new Database();
    $conn = $database->getConnection();
    
    echo "<p>✅ Database connection successful</p>";
    
    // Check if database exists and has tables
    $stmt = $conn->prepare("SHOW TABLES");
    $stmt->execute();
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h3>Tables in database:</h3>";
    if (empty($tables)) {
        echo "<p>❌ No tables found. Please run the schema creation first.</p>";
        echo "<p><a href='../database/schema.sql'>Run schema.sql first</a></p>";
    } else {
        foreach ($tables as $table) {
            $stmt = $conn->prepare("SELECT COUNT(*) as count FROM {$table}");
            $stmt->execute();
            $count = $stmt->fetch(PDO::FETCH_ASSOC)['count'];
            echo "<p>📊 {$table}: {$count} records</p>";
        }
    }
    
    // Check users specifically
    if (in_array('users', $tables)) {
        echo "<h3>Users in system:</h3>";
        $stmt = $conn->prepare("SELECT username, role FROM users");
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (empty($users)) {
            echo "<p>❌ No users found</p>";
            echo "<p><strong>Action needed:</strong> <a href='reset_database.php'>Run Database Reset</a></p>";
        } else {
            foreach ($users as $user) {
                echo "<p>👤 {$user['username']} ({$user['role']})</p>";
            }
        }
    }
    
} catch (Exception $e) {
    echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    
    if (strpos($e->getMessage(), "Unknown database") !== false) {
        echo "<div style='background: #fef2f2; border: 1px solid #fecaca; padding: 1rem; border-radius: 8px; margin: 1rem 0;'>";
        echo "<h3>Database Not Found</h3>";
        echo "<p>The database 'hostel_management' doesn't exist. Please create it first:</p>";
        echo "<pre>CREATE DATABASE hostel_management;</pre>";
        echo "<p>Then run the schema.sql file to create tables.</p>";
        echo "</div>";
    }
}
?>
