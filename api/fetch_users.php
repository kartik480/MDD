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

// Database configuration
$host = 'p3plzcpnl508816.prod.phx3.secureserver.net';
$dbname = 'emp_kfinone';
$username = 'emp_kfinone';
$password = '*F*im1!Y0D25';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all users from tbl_user table (same as used in My Employees)
    $query = "
        SELECT 
            u.id,
            u.username,
            u.firstName,
            u.lastName,
            u.designation_id,
            u.reportingTo,
            d.designation_name,
            CONCAT(u.firstName, ' ', u.lastName) as full_name
        FROM tbl_user u
        LEFT JOIN tbl_designation d ON u.designation_id = d.id
        ORDER BY u.firstName, u.lastName
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Users retrieved successfully',
        'users' => $users,
        'count' => count($users)
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'users' => [],
        'count' => 0
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'users' => [],
        'count' => 0
    ]);
}
?> 