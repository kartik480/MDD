import 'dart:convert';
import 'package:http/http.dart' as http;
import 'network_service.dart';

class DatabaseService {
  static const String baseUrl = 'http://emp.kfinone.com/mobile/api';
  
  // Database credentials (for reference - actual connection handled by PHP APIs)
  static const String dbName = 'emp_kfinone';
  static const String dbUsername = 'emp_kfinone';
  static const String dbPassword = '*F*im1!Y0D25';
  static const String dbServer = 'p3plzcpnl508816.prod.phx3.secureserver.net';

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

  // Fetch partner users from tbl_partner_users table
  static Future<List<Map<String, dynamic>>> fetchPartnerUsers() async {
    try {
      print('🔍 Fetching partner users from tbl_partner_users table...');
      print('🌐 API URL: $baseUrl/fetch_partner_users.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_partner_users.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch partner users status: ${response.statusCode}');
      print('📄 Fetch partner users body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch partner users response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['partner_users'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch partner users');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch partner users: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch partner users error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch SDSA users who report to KRAJESHK
  static Future<List<Map<String, dynamic>>> fetchSDSAUsersReportingToKrajeshk() async {
    try {
      print('🔍 Fetching SDSA users who report to KRAJESHK...');
      print('🌐 API URL: $baseUrl/fetch_sdsa_users_reporting_to_krajeshk.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_sdsa_users_reporting_to_krajeshk.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch SDSA users reporting to KRAJESHK status: ${response.statusCode}');
      print('📄 Fetch SDSA users reporting to KRAJESHK body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch SDSA users reporting to KRAJESHK response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['sdsa_users'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch SDSA users reporting to KRAJESHK');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch SDSA users reporting to KRAJESHK: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch SDSA users reporting to KRAJESHK error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch users with specific designations (Chief Business Officer, Regional Business Head, Director)
  static Future<Map<String, dynamic>> fetchSDSAUsersByDesignation() async {
    try {
      print('🔍 Fetching users with specific designations...');
      print('🌐 API URL: $baseUrl/fetch_sdsa_users_by_designation.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_sdsa_users_by_designation.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch users by designation status: ${response.statusCode}');
      print('📄 Fetch users by designation body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch users by designation response: $data');
        
        if (data['success'] == true) {
          return data;
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch users by designation');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch users by designation: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch users by designation error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch My SDSA users who report to K RAJESH KUMAR
  static Future<List<Map<String, dynamic>>> fetchMySDSAUsers() async {
    try {
      print('🔍 Fetching My SDSA users who report to K RAJESH KUMAR...');
      print('🌐 API URL: $baseUrl/fetch_my_sdsa_users.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_my_sdsa_users.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch My SDSA users status: ${response.statusCode}');
      print('📄 Fetch My SDSA users body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch My SDSA users response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['sdsa_users'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch My SDSA users');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch My SDSA users: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch My SDSA users error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch users with specific designations (Chief Business Officer, Director)
  static Future<Map<String, dynamic>> fetchUsersByDesignation() async {
    try {
      print('🔍 Fetching users with specific designations...');
      print('🌐 API URL: $baseUrl/fetch_users_by_designation.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_users_by_designation.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch users by designation status: ${response.statusCode}');
      print('📄 Fetch users by designation body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch users by designation response: $data');
        
        if (data['success'] == true) {
          return data;
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch users by designation');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch users by designation: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch users by designation error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch users who report to Chief Business Officer and Director users
  static Future<Map<String, dynamic>> fetchUsersReportingToManagers() async {
    try {
      print('🔍 Fetching users who report to Chief Business Officer and Director users...');
      print('🌐 API URL: $baseUrl/fetch_users_reporting_to_managers.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_users_reporting_to_managers.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch users reporting to managers status: ${response.statusCode}');
      print('📄 Fetch users reporting to managers body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch users reporting to managers response: $data');
        
        if (data['success'] == true) {
          return data;
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch users reporting to managers');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch users reporting to managers: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch users reporting to managers error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch SDSA users by designation for dropdown
 

  // Fetch SDSA users who report to a specific SDSA user
  static Future<List<Map<String, dynamic>>> fetchSDSAUsersReportingToSDSA(int userId) async {
    try {
      print('🔍 Fetching SDSA users who report to SDSA user ID: $userId...');
      print('🌐 API URL: $baseUrl/fetch_sdsa_users_reporting_to_sdsa.php?user_id=$userId');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_sdsa_users_reporting_to_sdsa.php?user_id=$userId'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch SDSA users reporting to SDSA status: ${response.statusCode}');
      print('📄 Fetch SDSA users reporting to SDSA body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch SDSA users reporting to SDSA response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['reporting_users'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch SDSA users reporting to SDSA');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch SDSA users reporting to SDSA: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch SDSA users reporting to SDSA error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch partner dropdown data from database tables
  static Future<Map<String, dynamic>> fetchPartnerDropdownData() async {
    try {
      print('🔍 Fetching partner dropdown data...');
      print('🌐 API URL: $baseUrl/fetch_partner_dropdown_data.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_partner_dropdown_data.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch partner dropdown data status: ${response.statusCode}');
      print('📄 Fetch partner dropdown data body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch partner dropdown data response: $data');
        
        if (data['success'] == true) {
          return data['data'] ?? {};
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch partner dropdown data');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch partner dropdown data: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch partner dropdown data error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch policy dropdown data from database tables
  static Future<Map<String, dynamic>> fetchPolicyDropdownData() async {
    try {
      print('🔍 Fetching policy dropdown data...');
      print('🌐 API URL: $baseUrl/fetch_policy_dropdown_data.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_policy_dropdown_data.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch policy dropdown data status: ${response.statusCode}');
      print('📄 Fetch policy dropdown data body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch policy dropdown data response: $data');
        
        if (data['success'] == true) {
          return data['data'] ?? {};
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch policy dropdown data');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch policy dropdown data: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch policy dropdown data error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch policy list from database
  static Future<List<Map<String, dynamic>>> fetchPolicyList() async {
    try {
      print('🔍 Fetching policy list...');
      print('🌐 API URL: $baseUrl/fetch_policy_list.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_policy_list.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch policy list status: ${response.statusCode}');
      print('📄 Fetch policy list body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch policy list response: $data');
        
        if (data['success'] == true) {
          return List<Map<String, dynamic>>.from(data['data']['policies'] ?? []);
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch policy list');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch policy list: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch policy list error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }

  // Fetch dashboard statistics
  static Future<Map<String, dynamic>> fetchDashboardStats() async {
    try {
      print('🔍 Fetching dashboard statistics...');
      print('🌐 API URL: $baseUrl/fetch_dashboard_stats.php');
      
      final networkTest = await NetworkService.testServerConnectivity();
      if (!networkTest['success']) {
        throw Exception(NetworkService.getErrorMessage(networkTest['type']));
      }
      
      final response = await http.get(
        Uri.parse('$baseUrl/fetch_dashboard_stats.php'),
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
        },
      ).timeout(const Duration(seconds: 30));

      print('📡 Fetch dashboard stats status: ${response.statusCode}');
      print('📄 Fetch dashboard stats body: ${response.body}');

      if (response.statusCode == 200) {
        final data = json.decode(response.body);
        print('✅ Fetch dashboard stats response: $data');
        
        if (data['success'] == true) {
          return data['data'] ?? {};
        } else {
          throw Exception(data['message'] ?? 'Failed to fetch dashboard statistics');
        }
      } else {
        print('❌ HTTP Error: ${response.statusCode}');
        print('❌ Error body: ${response.body}');
        throw Exception('Failed to fetch dashboard statistics: ${response.statusCode} - ${response.body}');
      }
    } catch (e) {
      print('❌ Fetch dashboard stats error: $e');
      if (e.toString().contains('SocketException')) {
        throw Exception('Network connection failed. Please check your internet connection.');
      } else if (e.toString().contains('TimeoutException')) {
        throw Exception('Request timed out. Please try again.');
      } else {
        throw Exception('Connection error: $e');
      }
    }
  }
} 