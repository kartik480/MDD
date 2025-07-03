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
    
    // Query to fetch SDSA users who report to specific designations
    // Join: tbl_sdsa_users -> tbl_user -> tbl_designation
    $query = "
        SELECT DISTINCT 
            sdsa.first_name,
            sdsa.last_name,
            sdsa.email_id,
            sdsa.Phone_number,
            sdsa.reportingTo,
            u.username as reporting_username,
            d.designation_name as reporting_designation
        FROM tbl_sdsa_users sdsa
        LEFT JOIN tbl_user u ON sdsa.reportingTo = u.id
        LEFT JOIN tbl_designation d ON u.designation_id = d.id
        WHERE d.designation_name IN ('Chief Business Officer', 'Regional Business Head', 'Director')
        ORDER BY d.designation_name, sdsa.first_name, sdsa.last_name
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $sdsa_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group users by designation for better organization
    $grouped_users = [];
    foreach ($sdsa_users as $user) {
        $designation = $user['reporting_designation'] ?? 'Unknown';
        if (!isset($grouped_users[$designation])) {
            $grouped_users[$designation] = [];
        }
        $grouped_users[$designation][] = $user;
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'SDSA users reporting to specific designations fetched successfully',
        'sdsa_users' => $sdsa_users,
        'grouped_users' => $grouped_users,
        'count' => count($sdsa_users),
        'designations' => array_keys($grouped_users)
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'sdsa_users' => [],
        'grouped_users' => [],
        'count' => 0,
        'designations' => []
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'sdsa_users' => [],
        'grouped_users' => [],
        'count' => 0,
        'designations' => []
    ]);
}
?> 