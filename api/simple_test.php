<?php
// Simple database connection test
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuration for kfinone database
$servername = "localhost";
$db_username = "u344026722_md";
$db_password = "@h9DDnaX";
$dbname = "u344026722_md";

echo "Testing database connection...\n";
echo "Host: $servername\n";
echo "Database: $dbname\n";
echo "Database Username: $db_username\n";
echo "Database Password: " . substr($db_password, 0, 3) . "***\n\n";

try {
    // Test basic connectivity
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    echo json_encode([
        'success' => true,
        'message' => 'Database connection successful',
        'server_info' => [
            'server' => $servername,
            'database' => $dbname,
            'server_version' => $conn->server_info
        ]
    ]);
    
    $conn->close();
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Connection failed: ' . $e->getMessage(),
        'server_info' => [
            'server' => $servername,
            'database' => $dbname
        ]
    ]);
}
?> 