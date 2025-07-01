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
    
    // Prepare SQL query to fetch users from tbl_user table
    $sql = "SELECT firstName, lastName, employee_no, mobile, email_id FROM tbl_user";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    // Fetch all users
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Close connection
    closeConnection($conn);
    
    // Return success response with users data
    echo json_encode([
        'success' => true,
        'message' => 'Users fetched successfully',
        'users' => $users,
        'count' => count($users)
    ]);
    
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Failed to fetch users: ' . $e->getMessage(),
        'users' => [],
        'count' => 0
    ]);
}
?> 