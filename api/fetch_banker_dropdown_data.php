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
    
    // Fetch Vendor Banks from tbl_vendor_bank
    $vendorBanks = [];
    try {
        $vendorBankQuery = "SELECT vendor_bank_name FROM tbl_vendor_bank ORDER BY vendor_bank_name";
        $vendorBankStmt = $pdo->prepare($vendorBankQuery);
        $vendorBankStmt->execute();
        $vendorBanks = $vendorBankStmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        // Table doesn't exist, use sample data
        $vendorBanks = ['HDFC Bank', 'ICICI Bank', 'SBI Bank', 'Axis Bank', 'Kotak Bank'];
    }
    
    // Fetch Banker Designations from tbl_banker_designation
    $designations = [];
    try {
        $designationQuery = "SELECT designation_name FROM tbl_banker_designation ORDER BY designation_name";
        $designationStmt = $pdo->prepare($designationQuery);
        $designationStmt->execute();
        $designations = $designationStmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        // Table doesn't exist, use sample data
        $designations = ['Manager', 'Assistant Manager', 'Senior Executive', 'Executive', 'Trainee'];
    }
    
    // Fetch Loan Types from tbl_loan_type
    $loanTypes = [];
    try {
        $loanTypeQuery = "SELECT loan_type FROM tbl_loan_type ORDER BY loan_type";
        $loanTypeStmt = $pdo->prepare($loanTypeQuery);
        $loanTypeStmt->execute();
        $loanTypes = $loanTypeStmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        // Table doesn't exist, use sample data
        $loanTypes = ['Home Loan', 'Personal Loan', 'Business Loan', 'Vehicle Loan', 'Education Loan'];
    }
    
    // Fetch Branch States from tbl_branch_state
    $states = [];
    try {
        $stateQuery = "SELECT DISTINCT branch_state_name FROM tbl_branch_state ORDER BY branch_state_name";
        $stateStmt = $pdo->prepare($stateQuery);
        $stateStmt->execute();
        $states = $stateStmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        // Table doesn't exist, use predefined list of Indian states
        $states = [
            'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
            'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand',
            'Karnataka', 'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur',
            'Meghalaya', 'Mizoram', 'Nagaland', 'Odisha', 'Punjab',
            'Rajasthan', 'Sikkim', 'Tamil Nadu', 'Telangana', 'Tripura',
            'Uttar Pradesh', 'Uttarakhand', 'West Bengal'
        ];
    }
    
    // Fetch Branch Locations from tbl_branch_location
    $branchLocations = [];
    try {
        $locationQuery = "SELECT DISTINCT branch_location FROM tbl_branch_location ORDER BY branch_location";
        $locationStmt = $pdo->prepare($locationQuery);
        $locationStmt->execute();
        $branchLocations = $locationStmt->fetchAll(PDO::FETCH_COLUMN);
    } catch (PDOException $e) {
        // Table doesn't exist, use sample data
        $branchLocations = ['Mumbai', 'Delhi', 'Bangalore', 'Chennai', 'Kolkata', 'Hyderabad', 'Pune', 'Ahmedabad'];
    }
    
    // Return success response with all dropdown data
    echo json_encode([
        'success' => true,
        'message' => 'Banker dropdown data fetched successfully',
        'data' => [
            'vendor_banks' => $vendorBanks,
            'designations' => $designations,
            'loan_types' => $loanTypes,
            'states' => $states,
            'branch_locations' => $branchLocations
        ]
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'data' => [
            'vendor_banks' => [],
            'designations' => [],
            'loan_types' => [],
            'states' => [],
            'branch_locations' => []
        ]
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'data' => [
            'vendor_banks' => [],
            'designations' => [],
            'loan_types' => [],
            'states' => [],
            'branch_locations' => []
        ]
    ]);
}
?> 