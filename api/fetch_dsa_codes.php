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
    
    if ($stmt->rowCount() == 0) {
        echo json_encode([
            'success' => false,
            'message' => 'tbl_dsa_code table does not exist'
        ]);
        exit;
    }
    
    // Check if tbl_loan_type exists
    $sql = "SHOW TABLES LIKE 'tbl_loan_type'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $loanTableExists = $stmt->rowCount() > 0;
    
    if ($loanTableExists) {
        // All tables exist, use full JOIN
        $sql = "SELECT 
                    dc.id,
                    dc.dsa_code,
                    vb.vendor_bank_name,
                    bn.bsa_name,
                    lt.loan_type,
                    s.state_name,
                    l.location
                FROM tbl_dsa_code dc
                LEFT JOIN tbl_vendor_bank vb ON dc.vendor_bank = vb.id
                LEFT JOIN tbl_bsa_name bn ON dc.bsa_name = bn.id
                LEFT JOIN tbl_loan_type lt ON dc.loan_type = lt.id
                LEFT JOIN tbl_state s ON dc.state = s.id
                LEFT JOIN tbl_location l ON dc.location = l.id
                ORDER BY dc.dsa_code";
    } else {
        // Loan table doesn't exist, exclude it from JOIN
        $sql = "SELECT 
                    dc.id,
                    dc.dsa_code,
                    vb.vendor_bank_name,
                    bn.bsa_name,
                    dc.loan_type,
                    s.state_name,
                    l.location
                FROM tbl_dsa_code dc
                LEFT JOIN tbl_vendor_bank vb ON dc.vendor_bank = vb.id
                LEFT JOIN tbl_bsa_name bn ON dc.bsa_name = bn.id
                LEFT JOIN tbl_state s ON dc.state = s.id
                LEFT JOIN tbl_location l ON dc.location = l.id
                ORDER BY dc.dsa_code";
    }
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    
    $dsaCodes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode([
        'success' => true,
        'data' => $dsaCodes,
        'message' => 'DSA codes fetched successfully'
    ]);
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error fetching DSA codes: ' . $e->getMessage()
    ]);
} finally {
    if (isset($conn)) {
        closeConnection($conn);
    }
}
?> 