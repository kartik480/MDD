<?php
require_once 'db_config.php';

try {
    // Get database connection
    $conn = getConnection();
    
    // Test query to check if tbl_user table exists
    $stmt = $conn->prepare("SHOW TABLES LIKE 'tbl_user'");
    $stmt->execute();
    
    $tableExists = $stmt->fetch();
    
    if ($tableExists) {
        // Count users in table
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM tbl_user");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo json_encode([
            'success' => true,
            'message' => 'Database connection successful',
            'table_exists' => true,
            'user_count' => $result['count']
        ]);
    } else {
        echo json_encode([
            'success' => true,
            'message' => 'Database connection successful but tbl_user table not found',
            'table_exists' => false
        ]);
    }
    
    closeConnection($conn);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Database connection failed: ' . $e->getMessage()
    ]);
}
?> 