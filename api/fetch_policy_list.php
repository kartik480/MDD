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
    
    // Fetch policies from tbl_policy with vendor bank and loan type names
    $query = "
        SELECT 
            p.id,
            p.vendor_bank_id,
            p.loan_type_id,
            p.image,
            p.content,
            vb.vendor_bank_name,
            lt.loan_type
        FROM tbl_policy p
        LEFT JOIN tbl_vendor_bank vb ON p.vendor_bank_id = vb.id
        LEFT JOIN tbl_loan_type lt ON p.loan_type_id = lt.id
        ORDER BY p.id DESC
    ";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $policies = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Return success response with policy data
    echo json_encode([
        'success' => true,
        'message' => 'Policy list fetched successfully',
        'data' => [
            'policies' => $policies,
            'count' => count($policies)
        ]
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'data' => [
            'policies' => [],
            'count' => 0
        ]
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'data' => [
            'policies' => [],
            'count' => 0
        ]
    ]);
}
?> 