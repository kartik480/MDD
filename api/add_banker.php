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

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'success' => false,
        'message' => 'Method not allowed'
    ]);
    exit();
}

// Database configuration
$host = 'p3plzcpnl508816.prod.phx3.secureserver.net';
$dbname = 'emp_kfinone';
$username = 'emp_kfinone';
$password = '*F*im1!Y0D25';

try {
    // Get POST data
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate required fields
    $requiredFields = [
        'vendor_bank', 'banker_name', 'phone_no', 'email', 
        'designation', 'loan_type', 'branch_state', 'branch_location', 'address'
    ];
    
    foreach ($requiredFields as $field) {
        if (!isset($input[$field]) || empty(trim($input[$field]))) {
            throw new Exception("Field '$field' is required");
        }
    }
    
    // Validate email format
    if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Invalid email format");
    }
    
    // Validate phone number (basic validation)
    if (!preg_match('/^[0-9+\-\s()]{10,15}$/', $input['phone_no'])) {
        throw new Exception("Invalid phone number format");
    }
    
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if banker with same email already exists
    $checkQuery = "SELECT id FROM tbl_bankers WHERE email = ?";
    $checkStmt = $pdo->prepare($checkQuery);
    $checkStmt->execute([$input['email']]);
    
    if ($checkStmt->fetch()) {
        throw new Exception("Banker with this email already exists");
    }
    
    // Insert new banker
    $insertQuery = "INSERT INTO tbl_bankers (
        vendor_bank, banker_name, phone_no, email, designation, 
        loan_type, branch_state, branch_location, visiting_card, address, 
        created_at, updated_at
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
    
    $insertStmt = $pdo->prepare($insertQuery);
    $insertStmt->execute([
        $input['vendor_bank'],
        $input['banker_name'],
        $input['phone_no'],
        $input['email'],
        $input['designation'],
        $input['loan_type'],
        $input['branch_state'],
        $input['branch_location'],
        $input['visiting_card'] ?? null,
        $input['address']
    ]);
    
    $bankerId = $pdo->lastInsertId();
    
    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Banker added successfully',
        'data' => [
            'id' => $bankerId,
            'banker_name' => $input['banker_name'],
            'email' => $input['email']
        ]
    ]);
    
} catch (PDOException $e) {
    // Return database error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    // Return validation error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 