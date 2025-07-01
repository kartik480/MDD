<?php
require_once 'db_config.php';

try {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        throw new Exception('Invalid JSON input');
    }
    
    $login_username = $input['username'] ?? '';
    $login_password = $input['password'] ?? '';
    
    if (empty($login_username) || empty($login_password)) {
        throw new Exception('Username and password are required');
    }
    
    // Get database connection
    $conn = getConnection();
    
    // Prepare and execute query
    $stmt = $conn->prepare("SELECT * FROM tbl_user WHERE username = ? AND password = ?");
    $stmt->execute([$login_username, $login_password]);
    
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        // Login successful
        echo json_encode([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'username' => $user['username'],
                // Don't send password back for security
            ]
        ]);
    } else {
        // Login failed
        echo json_encode([
            'success' => false,
            'message' => 'Invalid username or password'
        ]);
    }
    
    closeConnection($conn);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Server error: ' . $e->getMessage()
    ]);
}
?> 