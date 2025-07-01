<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';

try {
    $conn = getConnection();
    
    // Check if tbl_dsa_code exists
    $sql = "SHOW TABLES LIKE 'tbl_dsa_code'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $tableExists = $stmt->rowCount() > 0;
    
    if ($tableExists) {
        // Get table structure
        $sql = "DESCRIBE tbl_dsa_code";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $structure = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Get sample data
        $sql = "SELECT * FROM tbl_dsa_code LIMIT 5";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $sampleData = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'table_exists' => true,
            'structure' => $structure,
            'sample_data' => $sampleData,
            'message' => 'DSA code table structure fetched successfully'
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'table_exists' => false,
            'message' => 'tbl_dsa_code table does not exist'
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error checking DSA table: ' . $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        closeConnection($conn);
    }
}
?> 