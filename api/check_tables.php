<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';

try {
    $conn = getConnection();
    
    // Get all tables in the database
    $sql = "SHOW TABLES";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Look for loan-related tables
    $loanTables = [];
    foreach ($tables as $table) {
        if (stripos($table, 'loan') !== false) {
            $loanTables[] = $table;
        }
    }
    
    echo json_encode([
        'success' => true,
        'all_tables' => $tables,
        'loan_tables' => $loanTables,
        'message' => 'Tables fetched successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error checking tables: ' . $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        closeConnection($conn);
    }
}
?> 