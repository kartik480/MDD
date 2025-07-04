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
    
    // Fetch Total Employees (from tbl_user table - the same table used in My Employees)
    $empQuery = "SELECT COUNT(*) as total_emp FROM tbl_user";
    $empStmt = $pdo->prepare($empQuery);
    $empStmt->execute();
    $totalEmp = $empStmt->fetch(PDO::FETCH_ASSOC)['total_emp'];
    
    // Fetch Total SDSA (from tbl_sdsa_users - only count users who report to K RAJESH KUMAR, same as My SDSA panel)
    $totalSdsa = 0;
    try {
        $sdsaQuery = "SELECT COUNT(*) as total_sdsa FROM tbl_sdsa_users WHERE reportingTo = '8'";
        $sdsaStmt = $pdo->prepare($sdsaQuery);
        $sdsaStmt->execute();
        $totalSdsa = $sdsaStmt->fetch(PDO::FETCH_ASSOC)['total_sdsa'];
    } catch (Exception $e) {
        // Table doesn't exist, keep count as 0
        $totalSdsa = 0;
    }
    
    // Fetch Total Partners (from tbl_partners - check if table exists first)
    $totalPartner = 0;
    try {
        $partnerQuery = "SELECT COUNT(*) as total_partner FROM tbl_partners";
        $partnerStmt = $pdo->prepare($partnerQuery);
        $partnerStmt->execute();
        $totalPartner = $partnerStmt->fetch(PDO::FETCH_ASSOC)['total_partner'];
    } catch (Exception $e) {
        // Table doesn't exist, keep count as 0
        $totalPartner = 0;
    }
    
    // Fetch Total Portfolio (from tbl_portfolio - check if table exists first)
    $totalPortfolio = 0;
    try {
        $portfolioQuery = "SELECT COUNT(*) as total_portfolio FROM tbl_portfolio";
        $portfolioStmt = $pdo->prepare($portfolioQuery);
        $portfolioStmt->execute();
        $totalPortfolio = $portfolioStmt->fetch(PDO::FETCH_ASSOC)['total_portfolio'];
    } catch (Exception $e) {
        // Table doesn't exist, keep count as 0
        $totalPortfolio = 0;
    }
    
    // Fetch Total Agents (from tbl_agents - check if table exists first)
    $totalAgents = 0;
    try {
        $agentQuery = "SELECT COUNT(*) as total_agents FROM tbl_agents";
        $agentStmt = $pdo->prepare($agentQuery);
        $agentStmt->execute();
        $totalAgents = $agentStmt->fetch(PDO::FETCH_ASSOC)['total_agents'];
    } catch (Exception $e) {
        // Table doesn't exist, keep count as 0
        $totalAgents = 0;
    }
    
    // Return success response with all dashboard stats
    echo json_encode([
        'success' => true,
        'message' => 'Dashboard statistics fetched successfully',
        'data' => [
            'total_emp' => (int)$totalEmp,
            'total_sdsa' => (int)$totalSdsa,
            'total_partner' => (int)$totalPartner,
            'total_portfolio' => (int)$totalPortfolio,
            'total_agents' => (int)$totalAgents
        ]
    ]);
    
} catch (PDOException $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage(),
        'data' => [
            'total_emp' => 0,
            'total_sdsa' => 0,
            'total_partner' => 0,
            'total_portfolio' => 0,
            'total_agents' => 0
        ]
    ]);
} catch (Exception $e) {
    // Return error response
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage(),
        'data' => [
            'total_emp' => 0,
            'total_sdsa' => 0,
            'total_partner' => 0,
            'total_portfolio' => 0,
            'total_agents' => 0
        ]
    ]);
}
?> 