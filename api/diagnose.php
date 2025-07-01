<?php
// Database diagnostic script
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
$servername = "p3plzcpnl508816.prod.phx3.secureserver.net";
$db_username = "emp_kfinone";
$db_password = "*F*im1!Y0D25";
$dbname = "emp_kfinone";

try {
    // Test database connection
    $conn = new mysqli($servername, $db_username, $db_password, $dbname);
    
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
    
    // Get server info
    $server_info = [
        'server_version' => $conn->server_info,
        'host_info' => $conn->host_info,
        'protocol_version' => $conn->protocol_version,
        'client_info' => $conn->client_info
    ];
    
    // Check if tables exist
    $tables = [];
    $result = $conn->query("SHOW TABLES");
    while ($row = $result->fetch_array()) {
        $tables[] = $row[0];
    }
    
    // Check specific tables
    $table_status = [];
    $important_tables = ['users', 'tbl_user', 'tbl_sdsa_users'];
    
    foreach ($important_tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        $table_status[$table] = $result->num_rows > 0;
    }
    
    $conn->close();
    
    echo json_encode([
        'success' => true,
        'message' => 'Database connection successful',
        'server_info' => $server_info,
        'all_tables' => $tables,
        'important_tables' => $table_status,
        'connection_details' => [
            'server' => $servername,
            'database' => $dbname,
            'username' => $db_username
        ]
    ]);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage(),
        'connection_details' => [
            'server' => $servername,
            'database' => $dbname,
            'username' => $db_username
        ]
    ]);
}
?> 