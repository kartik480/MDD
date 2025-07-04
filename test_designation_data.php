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
    
    // Check all designations in tbl_designation
    $query1 = "SELECT * FROM tbl_designation ORDER BY id";
    $stmt1 = $pdo->prepare($query1);
    $stmt1->execute();
    $all_designations = $stmt1->fetchAll(PDO::FETCH_ASSOC);
    
    // Check users with specific designations
    $query2 = "
        SELECT 
            u.id,
            u.username,
            u.firstName,
            u.lastName,
            u.designation_id,
            d.designation_name
        FROM tbl_user u
        LEFT JOIN tbl_designation d ON u.designation_id = d.id
        WHERE d.designation_name IN ('Chief Business Officer', 'Regional Business Head', 'Director')
        ORDER BY u.firstName, u.lastName
    ";
    $stmt2 = $pdo->prepare($query2);
    $stmt2->execute();
    $designated_users = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    
    // Check all users with their designations
    $query3 = "
        SELECT 
            u.id,
            u.username,
            u.firstName,
            u.lastName,
            u.designation_id,
            d.designation_name
        FROM tbl_user u
        LEFT JOIN tbl_designation d ON u.designation_id = d.id
        ORDER BY d.designation_name, u.firstName
    ";
    $stmt3 = $pdo->prepare($query3);
    $stmt3->execute();
    $all_users_with_designations = $stmt3->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'all_designations' => $all_designations,
        'designated_users_count' => count($designated_users),
        'designated_users' => $designated_users,
        'all_users_with_designations' => $all_users_with_designations
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