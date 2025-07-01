<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';

try {
    $conn = getConnection();
    
    $sql = "SELECT id, vendor_bank_name FROM tbl_vendor_bank ORDER BY vendor_bank_name";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $vendorBanks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $vendorBanks,
        'message' => 'Vendor banks fetched successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching vendor banks: ' . $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        closeConnection($conn);
    }
}
?> 