<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';

try {
    $conn = getConnection();
    
    $sql = "SELECT id, location FROM tbl_location ORDER BY location";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $locations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $locations,
        'message' => 'Locations fetched successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching locations: ' . $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        closeConnection($conn);
    }
}
?> 