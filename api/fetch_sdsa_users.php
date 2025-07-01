<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once 'db_config.php';

try {
    // Get database connection
    $conn = getConnection();
    
    // Prepare SQL query to fetch SDSA users from tbl_sdsa_users table
    $sql = "SELECT first_name, last_name, Phone_number, email_id, password FROM tbl_sdsa_users";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    // Fetch all SDSA users
    $sdsa_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Close connection
    closeConnection($conn);
    
    // Return success response with SDSA users data
    echo json_encode([
        'success' => true,
        'message' => 'SDSA users fetched successfully',
        'sdsa_users' => $sdsa_users,
        'count' => count($sdsa_users)
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch SDSA users: ' . $e->getMessage(),
        'sdsa_users' => [],
        'count' => 0
    ]);
}
?> 