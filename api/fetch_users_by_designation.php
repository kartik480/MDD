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
    
    // Query to fetch users with specific designations (Chief Business Officer, Regional Business Head, Director)
    $query = "
        SELECT 
            u.id,
            u.username,
            u.firstName,
            u.lastName,
            u.designation_id,
            d.designation_name,
            CONCAT(u.firstName, ' ', u.lastName, ' (', d.designation_name, ')') as display_name
        FROM tbl_user u
        LEFT JOIN tbl_designation d ON u.designation_id = d.id
        WHERE d.designation_name IN ('Chief Business Officer', 'Regional Business Head', 'Director')
        ORDER BY u.firstName, u.lastName
    ";
    
    // Also get all designations for debugging
    $query_debug = "SELECT * FROM tbl_designation ORDER BY id";
    $stmt_debug = $pdo->prepare($query_debug);
    $stmt_debug->execute();
    $all_designations = $stmt_debug->fetchAll(PDO::FETCH_ASSOC);
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Create dropdown options with user names
    $dropdownOptions = [];
    foreach ($users as $user) {
        $dropdownOptions[] = $user['display_name'];
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Users with specific designations fetched successfully',
        'users' => $users,
        'dropdown_options' => $dropdownOptions,
        'count' => count($users),
        'debug_all_designations' => $all_designations
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'users' => [],
        'dropdown_options' => [],
        'count' => 0
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'users' => [],
        'dropdown_options' => [],
        'count' => 0
    ]);
}
?> 