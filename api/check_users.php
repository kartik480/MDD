<?php
// Check existing users and their passwords
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database configuration
$host = "p3plzcpnl508816.prod.phx3.secureserver.net";
$dbname = "emp_kfinone";
$username = "emp_kfinone";
$password = "*F*im1!Y0D25";

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all users with their passwords
    $stmt = $conn->query("SELECT username, password FROM tbl_user");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'message' => 'Found ' . count($users) . ' users in database',
        'users' => $users
    ], JSON_PRETTY_PRINT);
    
} catch(PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?> 