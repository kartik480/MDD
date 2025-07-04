<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database configuration
$host = 'p3plzcpnl508816.prod.phx3.secureserver.net';
$dbname = 'emp_kfinone';
$username = 'emp_kfinone';
$password = '*F*im1!Y0D25';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if tbl_sdsa_users exists and has data
    $query = "SELECT COUNT(*) as total FROM tbl_sdsa_users";
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Get sample data
    $query2 = "SELECT * FROM tbl_sdsa_users LIMIT 5";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute();
    $sample_data = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'total_sdsa_users' => $count['total'],
        'sample_data' => $sample_data
    ]);
    
} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Database error: ' . $e->getMessage()
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Server error: ' . $e->getMessage()
    ]);
}
?> 