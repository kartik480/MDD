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
    
    // Query to fetch SDSA users who report to KRAJESHK (id 1)
    // Join: tbl_sdsa_users -> tbl_user (to get reporting person's name)
    $query = "
        SELECT 
            sdsa.id,
            sdsa.first_name,
            sdsa.last_name,
            sdsa.Phone_number,
            sdsa.email_id,
            sdsa.reportingTo,
            CONCAT(u.firstName, ' ', u.lastName) as reporting_person_name
        FROM tbl_sdsa_users sdsa
        LEFT JOIN tbl_user u ON sdsa.reportingTo = u.id
        WHERE sdsa.reportingTo = '1'  -- KRAJESHK has id 1
        ORDER BY sdsa.first_name, sdsa.last_name
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    
    $sdsa_users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'SDSA users reporting to KRAJESHK fetched successfully',
        'sdsa_users' => $sdsa_users,
        'count' => count($sdsa_users)
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'sdsa_users' => [],
        'count' => 0
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'sdsa_users' => [],
        'count' => 0
    ]);
}
?> 