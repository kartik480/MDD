<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';

try {
    $conn = getConnection();
    
    $sql = "SELECT id, bsa_name FROM tbl_bsa_name ORDER BY bsa_name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $bsaNames = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $bsaNames,
        'message' => 'BSA names fetched successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching BSA names: ' . $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        closeConnection($conn);
    }
}
?> 