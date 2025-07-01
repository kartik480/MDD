class AppConfig {
  // API Configuration
  static const String apiBaseUrl = 'http://emp.kfinone.com/mobile/api';
  
  // Database Configuration
  static const String dbName = 'u344026722_md';
  static const String dbUsername = 'u344026722_md';
  static const String dbPassword = 'zC\$:KHc]iB2';
  
  // App Configuration
  static const String appName = 'Managing Director App';
  static const String appVersion = '1.0.0';
  
  // Network Configuration
  static const int connectionTimeout = 30; // seconds
  static const int receiveTimeout = 30; // seconds
  
  // Debug Configuration
  static const bool enableDebugLogs = true;
  static const bool enableNetworkLogs = true;
  
  // API Endpoints
  static const String loginEndpoint = '/login.php';
  static const String testConnectionEndpoint = '/test_connection.php';
  static const String testEndpoint = '/test.php';
  static const String diagnoseEndpoint = '/diagnose.php';
  
  // Get full API URL
  static String getApiUrl(String endpoint) {
    return '$apiBaseUrl$endpoint';
  }
  
  // Check if running in debug mode
  static bool get isDebugMode {
    bool inDebugMode = false;
    assert(inDebugMode = true); // This will only be true in debug mode
    return inDebugMode;
  }
  
  // Get appropriate timeout based on environment
  static int get connectionTimeoutSeconds {
    return isDebugMode ? 60 : connectionTimeout;
  }
} 