import 'dart:io';
import 'dart:async';
import 'package:http/http.dart' as http;

class NetworkService {
  static const String baseUrl = 'http://emp.kfinone.com/mobile/api';
  
  // Check if device has internet connection
  static Future<bool> hasInternetConnection() async {
    try {
      final result = await InternetAddress.lookup('google.com');
      return result.isNotEmpty && result[0].rawAddress.isNotEmpty;
    } on SocketException catch (_) {
      return false;
    }
  }
  
  // Test server connectivity
  static Future<Map<String, dynamic>> testServerConnectivity() async {
    try {
      // First check internet connection
      final hasInternet = await hasInternetConnection();
      if (!hasInternet) {
        return {
          'success': false,
          'message': 'No internet connection available',
          'type': 'no_internet'
        };
      }
      
      // Test basic endpoint
      final response = await http.get(
        Uri.parse('$baseUrl/test.php'),
        headers: {'Accept': 'application/json'},
      ).timeout(const Duration(seconds: 10));
      
      if (response.statusCode == 200) {
        return {
          'success': true,
          'message': 'Server is reachable',
          'type': 'server_ok'
        };
      } else {
        return {
          'success': false,
          'message': 'Server returned status: ${response.statusCode}',
          'type': 'server_error'
        };
      }
    } on SocketException catch (e) {
      return {
        'success': false,
        'message': 'Cannot connect to server. Check your internet connection.',
        'type': 'connection_error'
      };
    } on TimeoutException catch (e) {
      return {
        'success': false,
        'message': 'Connection timed out. Please try again.',
        'type': 'timeout'
      };
    } catch (e) {
      return {
        'success': false,
        'message': 'Network error: ${e.toString()}',
        'type': 'unknown_error'
      };
    }
  }
  
  // Get user-friendly error message
  static String getErrorMessage(String errorType) {
    switch (errorType) {
      case 'no_internet':
        return 'No internet connection. Please check your WiFi or mobile data.';
      case 'connection_error':
        return 'Cannot connect to server. Please check your internet connection.';
      case 'timeout':
        return 'Connection timed out. Please try again.';
      case 'server_error':
        return 'Server is not responding. Please try again later.';
      case 'unknown_error':
        return 'An unexpected error occurred. Please try again.';
      default:
        return 'Network error occurred. Please try again.';
    }
  }
} 