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
    
    // First, get the IDs of users with Chief Business Officer and Director designations
    $managerQuery = "
        SELECT u.id, u.firstName, u.lastName, d.designation_name
        FROM tbl_user u
        INNER JOIN tbl_designation d ON u.designation_id = d.id
        WHERE d.designation_name IN ('Chief Business Officer', 'Director')
        ORDER BY d.designation_name, u.firstName, u.lastName
    ";
    
    $managerStmt = $pdo->prepare($managerQuery);
    $managerStmt->execute();
    $managers = $managerStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $managerIds = array_column($managers, 'id');
    
    if (empty($managerIds)) {
        // Return empty result if no managers found
        echo json_encode([
            'success' => true,
            'message' => 'No Chief Business Officer or Director users found',
            'managers' => [],
            'reporting_users' => [],
            'count' => 0
        ]);
        exit();
    }
    
    // Now fetch users who report to these managers
    $placeholders = str_repeat('?,', count($managerIds) - 1) . '?';
    $reportingQuery = "
        SELECT 
            u.id,
            u.username,
            u.firstName,
            u.lastName,
            u.reportingTo,
            u.designation_id,
            d.designation_name,
            CONCAT(u.firstName, ' ', u.lastName) as full_name,
            CONCAT(m.firstName, ' ', m.lastName) as manager_name,
            md.designation_name as manager_designation
        FROM tbl_user u
        LEFT JOIN tbl_designation d ON u.designation_id = d.id
        LEFT JOIN tbl_user m ON u.reportingTo = m.id
        LEFT JOIN tbl_designation md ON m.designation_id = md.id
        WHERE u.reportingTo IN ($placeholders)
        ORDER BY md.designation_name, m.firstName, m.lastName, u.firstName, u.lastName
    ";
    
    $reportingStmt = $pdo->prepare($reportingQuery);
    $reportingStmt->execute($managerIds);
    $reportingUsers = $reportingStmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Group users by their manager
    $groupedUsers = [];
    foreach ($reportingUsers as $user) {
        $managerKey = $user['manager_name'] . ' (' . $user['manager_designation'] . ')';
        if (!isset($groupedUsers[$managerKey])) {
            $groupedUsers[$managerKey] = [];
        }
        $groupedUsers[$managerKey][] = $user;
    }
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Users reporting to Chief Business Officer and Director users fetched successfully',
        'managers' => $managers,
        'reporting_users' => $reportingUsers,
        'grouped_users' => $groupedUsers,
        'count' => count($reportingUsers)
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'managers' => [],
        'reporting_users' => [],
        'grouped_users' => [],
        'count' => 0
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'managers' => [],
        'reporting_users' => [],
        'grouped_users' => [],
        'count' => 0
    ]);
}
?> 