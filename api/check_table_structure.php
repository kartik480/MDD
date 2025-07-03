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
    
    $result = [];
    
    // Check tbl_user structure
    $stmt = $pdo->query("DESCRIBE tbl_user");
    $userColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result['tbl_user_columns'] = $userColumns;
    
    // Check tbl_designation structure
    $stmt = $pdo->query("DESCRIBE tbl_designation");
    $designationColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result['tbl_designation_columns'] = $designationColumns;
    
    // Get sample data from tbl_user
    $stmt = $pdo->query("SELECT * FROM tbl_user LIMIT 3");
    $userSample = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result['tbl_user_sample'] = $userSample;
    
    // Get sample data from tbl_designation
    $stmt = $pdo->query("SELECT * FROM tbl_designation LIMIT 5");
    $designationSample = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $result['tbl_designation_sample'] = $designationSample;
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Table structure checked successfully',
        'data' => $result
    ], JSON_PRETTY_PRINT);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'columns' => [],
        'sample_data' => []
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'columns' => [],
        'sample_data' => []
    ]);
}
?> 