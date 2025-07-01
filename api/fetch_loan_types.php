<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

require_once 'db_config.php';

try {
    $conn = getConnection();
    
    // First, check if tbl_loan_type exists
    $sql = "SHOW TABLES LIKE 'tbl_loan_type'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    if ($stmt->rowCount() > 0) {
        // Table exists, fetch from it
        $sql = "SELECT id, loan_type FROM tbl_loan_type ORDER BY loan_type";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        
        $loanTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        // Table doesn't exist, check for alternative table names
        $alternativeTables = ['tbl_loan_type', 'tbl_loan_types', 'loan_types', 'loan_type'];
        $loanTypes = [];
        
        foreach ($alternativeTables as $tableName) {
            $sql = "SHOW TABLES LIKE '$tableName'";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                // Found alternative table
                $sql = "SELECT id, loan_type FROM $tableName ORDER BY loan_type";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                
                $loanTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
                break;
            }
        }
        
        // If no table found, provide default loan types
        if (empty($loanTypes)) {
            $loanTypes = [
                ['id' => 1, 'loan_type' => 'Personal Loan'],
                ['id' => 2, 'loan_type' => 'Home Loan'],
                ['id' => 3, 'loan_type' => 'Business Loan'],
                ['id' => 4, 'loan_type' => 'Vehicle Loan'],
                ['id' => 5, 'loan_type' => 'Education Loan'],
                ['id' => 6, 'loan_type' => 'Gold Loan'],
                ['id' => 7, 'loan_type' => 'Property Loan'],
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'data' => $loanTypes,
        'message' => 'Loan types fetched successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching loan types: ' . $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        closeConnection($conn);
    }
}
?> 