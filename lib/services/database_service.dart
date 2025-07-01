import 'dart:convert';
import 'package:http/http.dart' as http;
import 'network_service.dart';

class DatabaseService {
  static const String baseUrl = 'http://emp.kfinone.com/mobile/api';
  
  // Database credentials
  static const String dbName = 'u344026722_md';
  static const String dbUsername = 'u344026722_md';
  static const String dbPassword = '@h9DDnaX';

  // Login user
  static Future<Map<String, dynamic>> loginUser(String username, String password) async {
    try {
      print('🔐 Attempting login for username: $username');
      print('🌐 API URL: $baseUrl/login.php');
      print('📱 App is running on live server');
      
      // Check network connectivity first
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.post(
        Uri.parse('$baseUrl/login.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
        body: json.encode({
          'username': username,
          'password': password,
        }),
      ).timeout(const Duration(seconds: 30));

      print('📡 Response status: ${response.statusCode}');
      print('📄 Response headers: ${response.headers}');
      print('📄 Response body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Login response: $data');
        return data;
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to login: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Login error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Test database connection
  static Future<bool> testConnection() async {
    try {
      print('🔍 Testing database connection...');
      print('🌐 Testing URL: $baseUrl/test_connection.php');
      
      // Check network connectivity first
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        print('❌ Network connectivity failed: ${networkTest['message']}');
        return false;
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/test_connection.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Connection test status: ${response.statusCode}');
      print('📄 Connection test headers: ${response.headers}');
      print('📄 Connection test body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Connection test result: $data');
        return data['success'] == true;
      }
      print('❌ Connection test failed with status: ${response.statusCode}');
      return false;
    } catch (e) {
      print('❌ Connection test error: $e');
      return false;
    }
  }

  // Test basic connectivity
  static Future<bool> testBasicConnectivity() async {
    try {
      print('🔍 Testing basic connectivity...');
      print('🌐 Testing URL: $baseUrl/test.php');
      
      final response = await http.get(
        Uri.parse('$baseUrl/test.php'),
        headers: {
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 10));

      print('📡 Basic test status: ${response.statusCode}');
      print('📄 Basic test body: ${response.body}');

      return response.statusCode == 200;
    } catch (e) {
      print('❌ Basic connectivity test error: $e');
      return false;
    }
  }

  // Fetch all users from tbl_user table
  static Future<List<Map<String, dynamic>>> fetchUsers() async {
    try {
      print('🔍 Fetching users from tbl_user table...');
      print('🌐 API URL: $baseUrl/fetch_users.php');
      
      // Check network connectivity first
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_users.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch users status: ${response.statusCode}');
      print('📄 Fetch users body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch users response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['users'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch users');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch users: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch users error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch all SDSA users from tbl_sdsa_users table
  static Future<List<Map<String, dynamic>>> fetchSDSAUsers() async {
    try {
      print('🔍 Fetching SDSA users from tbl_sdsa_users table...');
      print('🌐 API URL: $baseUrl/fetch_sdsa_users.php');
      
      // Check network connectivity first
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_sdsa_users.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch SDSA users status: ${response.statusCode}');
      print('📄 Fetch SDSA users body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch SDSA users response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['sdsa_users'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch SDSA users');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch SDSA users: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch SDSA users error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch vendor banks from tbl_vendor_bank table
  static Future<List<Map<String, dynamic>>> fetchVendorBanks() async {
    try {
      print('🔍 Fetching vendor banks from tbl_vendor_bank table...');
      print('🌐 API URL: $baseUrl/fetch_vendor_banks.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_vendor_banks.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch vendor banks status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch vendor banks response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['data'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch vendor banks');
        }
      } else {
        throw Exception('Failed to fetch vendor banks: ${response.statusCode}');
      }
    } catch (e) {
      print('❌ Fetch vendor banks error: $e');
      throw Exception('Connection error: $e');
    }
  }

  // Fetch BSA names from tbl_bsa_name table
  static Future<List<Map<String, dynamic>>> fetchBSANames() async {
    try {
      print('🔍 Fetching BSA names from tbl_bsa_name table...');
      print('🌐 API URL: $baseUrl/fetch_bsa_names.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_bsa_names.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch BSA names status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch BSA names response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['data'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch BSA names');
        }
      } else {
        throw Exception('Failed to fetch BSA names: ${response.statusCode}');
      }
    } catch (e) {
      print('❌ Fetch BSA names error: $e');
      throw Exception('Connection error: $e');
    }
  }

  // Fetch loan types from tbl_loan_typ table
  static Future<List<Map<String, dynamic>>> fetchLoanTypes() async {
    try {
      print('🔍 Fetching loan types from tbl_loan_typ table...');
      print('🌐 API URL: $baseUrl/fetch_loan_types.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_loan_types.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch loan types status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch loan types response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['data'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch loan types');
        }
      } else {
        throw Exception('Failed to fetch loan types: ${response.statusCode}');
      }
    } catch (e) {
      print('❌ Fetch loan types error: $e');
      throw Exception('Connection error: $e');
    }
  }

  // Fetch states from tbl_state table
  static Future<List<Map<String, dynamic>>> fetchStates() async {
    try {
      print('🔍 Fetching states from tbl_state table...');
      print('🌐 API URL: $baseUrl/fetch_states.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_states.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch states status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch states response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['data'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch states');
        }
      } else {
        throw Exception('Failed to fetch states: ${response.statusCode}');
      }
    } catch (e) {
      print('❌ Fetch states error: $e');
      throw Exception('Connection error: $e');
    }
  }

  // Fetch locations from tbl_location table
  static Future<List<Map<String, dynamic>>> fetchLocations() async {
    try {
      print('🔍 Fetching locations from tbl_location table...');
      print('🌐 API URL: $baseUrl/fetch_locations.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_locations.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch locations status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch locations response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['data'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch locations');
        }
      } else {
        throw Exception('Failed to fetch locations: ${response.statusCode}');
      }
    } catch (e) {
      print('❌ Fetch locations error: $e');
      throw Exception('Connection error: $e');
    }
  }

  // Fetch DSA codes from tbl_dsa_code table with all related data
  static Future<List<Map<String, dynamic>>> fetchDSACodes() async {
    try {
      print('🔍 Fetching DSA codes from tbl_dsa_code table...');
      print('🌐 API URL: $baseUrl/fetch_dsa_codes.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_dsa_codes.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch DSA codes status: ${response.statusCode}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch DSA codes response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['data'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch DSA codes');
        }
      } else {
        throw Exception('Failed to fetch DSA codes: ${response.statusCode}');
      }
    } catch (e) {
      print('❌ Fetch DSA codes error: $e');
      throw Exception('Connection error: $e');
    }
  }
} 