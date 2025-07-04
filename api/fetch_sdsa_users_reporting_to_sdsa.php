<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = 'p3plzcpnl508816.prod.phx3.secureserver.net';
$dbname = 'emp_kfinone';
$username = 'emp_kfinone';
$password = '*F*im1!Y0D25';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get the target SDSA user ID from query parameter
    $targetUserId = $_GET['user_id'] ?? null;
    
    if (!$targetUserId) {
        echo json_encode([
            'success' => false,
            'message' => 'User ID parameter is required',
            'reporting_users' => [],
            'count' => 0
        ]);
        exit();
    }
    
    // Query to fetch SDSA users who report to the specified designated user
    $query = "
        SELECT 
            sdsa.id,
            sdsa.first_name,
            sdsa.last_name,
            sdsa.Phone_number,
            sdsa.email_id,
            sdsa.reportingTo,
            CONCAT(sdsa.first_name, ' ', sdsa.last_name) as full_name,
            CONCAT(u.firstName, ' ', u.lastName) as manager_name
        FROM tbl_sdsa_users sdsa
        LEFT JOIN tbl_user u ON sdsa.reportingTo = u.id
        WHERE sdsa.reportingTo = :targetUserId
        ORDER BY sdsa.first_name, sdsa.last_name
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':targetUserId', $targetUserId, PDO::PARAM_INT);
    $stmt->execute();
    
    $reportingUsers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'message' => 'SDSA users reporting to designated user fetched successfully',
        'reporting_users' => $reportingUsers,
        'count' => count($reportingUsers)
    ]);
    
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'reporting_users' => [],
        'count' => 0
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'reporting_users' => [],
        'count' => 0
    ]);
}
?> 