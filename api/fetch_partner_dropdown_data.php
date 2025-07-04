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
    
    // Fetch Partner Types from tbl_partner_type
    $partnerTypeQuery = "SELECT partner_type FROM tbl_partner_type ORDER BY partner_type";
    $partnerTypeStmt = $pdo->prepare($partnerTypeQuery);
    $partnerTypeStmt->execute();
    $partnerTypes = $partnerTypeStmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Fetch Branch States from tbl_branch_state
    $branchStateQuery = "SELECT branch_state_name FROM tbl_branch_state ORDER BY branch_state_name";
    $branchStateStmt = $pdo->prepare($branchStateQuery);
    $branchStateStmt->execute();
    $branchStates = $branchStateStmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Fetch Branch Locations from tbl_branch_location
    $branchLocationQuery = "SELECT branch_location FROM tbl_branch_location ORDER BY branch_location";
    $branchLocationStmt = $pdo->prepare($branchLocationQuery);
    $branchLocationStmt->execute();
    $branchLocations = $branchLocationStmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Fetch Bank Names from tbl_bank
    $bankQuery = "SELECT bank_name FROM tbl_bank ORDER BY bank_name";
    $bankStmt = $pdo->prepare($bankQuery);
    $bankStmt->execute();
    $bankNames = $bankStmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Fetch Account Types from tbl_account_type
    $accountTypeQuery = "SELECT account_type FROM tbl_account_type ORDER BY account_type";
    $accountTypeStmt = $pdo->prepare($accountTypeQuery);
    $accountTypeStmt->execute();
    $accountTypes = $accountTypeStmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Return success response with all dropdown data
    echo json_encode([
        'success' => true,
        'message' => 'Partner dropdown data fetched successfully',
        'data' => [
            'partner_types' => $partnerTypes,
            'branch_states' => $branchStates,
            'branch_locations' => $branchLocations,
            'bank_names' => $bankNames,
            'account_types' => $accountTypes
        ]
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'data' => [
            'partner_types' => [],
            'branch_states' => [],
            'branch_locations' => [],
            'bank_names' => [],
            'account_types' => []
        ]
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'data' => [
            'partner_types' => [],
            'branch_states' => [],
            'branch_locations' => [],
            'bank_names' => [],
            'account_types' => []
        ]
    ]);
}
?> 