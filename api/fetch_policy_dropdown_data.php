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
    
    // Fetch Loan Types from tbl_loan_type
    $loanTypeQuery = "SELECT loan_type FROM tbl_loan_type ORDER BY loan_type";
    $loanTypeStmt = $pdo->prepare($loanTypeQuery);
    $loanTypeStmt->execute();
    $loanTypes = $loanTypeStmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Fetch Vendor Banks from tbl_vendor_bank
    $vendorBankQuery = "SELECT vendor_bank_name FROM tbl_vendor_bank ORDER BY vendor_bank_name";
    $vendorBankStmt = $pdo->prepare($vendorBankQuery);
    $vendorBankStmt->execute();
    $vendorBanks = $vendorBankStmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Return success response with all dropdown data
    echo json_encode([
        'success' => true,
        'message' => 'Policy dropdown data fetched successfully',
        'data' => [
            'loan_types' => $loanTypes,
            'vendor_banks' => $vendorBanks
        ]
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'data' => [
            'loan_types' => [],
            'vendor_banks' => []
        ]
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'data' => [
            'loan_types' => [],
            'vendor_banks' => []
        ]
    ]);
}
?> 