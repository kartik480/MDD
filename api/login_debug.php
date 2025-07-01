<?php
// Debug version of login script to show database connection details
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Database configuration for kfinone database
$servername = "localhost";
$db_username = "u344026722_md";
$db_password = "@h9DDnaX";
$dbname = "u344026722_md";

echo "=== DATABASE CONFIGURATION DEBUG ===\n";
echo "Server: $servername\n";
echo "Database: $dbname\n";
echo "Database Username: $db_username\n";
echo "Database Password: " . substr($db_password, 0, 3) . "***\n\n";

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Create connection
        $conn = new mysqli($servername, $db_username, $db_password, $dbname);
        
        // Check connection
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }
        
        $username = $input['username'] ?? '';
        $password = $input['password'] ?? '';
        
        echo "=== LOGIN ATTEMPT DEBUG ===\n";
        echo "Login Username: $username\n";
        echo "Login Password: " . substr($password, 0, 3) . "***\n\n";
        
        if (empty($username) || empty($password)) {
            throw new Exception('Username and password are required');
        }
        
        echo "=== DATABASE CONNECTION ATTEMPT ===\n";
        echo "Trying to connect with:\n";
        echo "- Database Username: $db_username (NOT the login username)\n";
        echo "- Database Password: " . substr($db_password, 0, 3) . "***\n";
        echo "- Database Name: $dbname\n\n";
        
        // Debug information
        $debug_info = [
            'server' => $servername,
            'database' => $dbname,
            'username_provided' => $username,
            'password_provided' => !empty($password) ? 'YES' : 'NO',
            'connection_status' => 'Connected successfully'
        ];
        
        // Prepare and execute query
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        if (!$stmt) {
            throw new Exception("Prepare failed: " . $conn->error);
        }
        
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            echo json_encode([
                'success' => true,
                'message' => 'Login successful',
                'user' => $user,
                'debug' => $debug_info
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid username or password',
                'debug' => $debug_info
            ]);
        }
        
        $stmt->close();
        $conn->close();
        
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage(),
            'debug' => [
                'server' => $servername,
                'database' => $dbname,
                'error' => $e->getMessage()
            ]
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Only POST method allowed'
    ]);
}
?> 