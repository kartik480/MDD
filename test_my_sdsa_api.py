#!/usr/bin/env python3
"""
Test script for My SDSA API
Tests the fetch_my_sdsa_users.php API
"""

import requests
import json
import sys

# Configuration
BASE_URL = "http://localhost/mobile/api"  # Update this to your server URL
API_ENDPOINT = f"{BASE_URL}/fetch_my_sdsa_users.php"

def test_my_sdsa_api():
    """Test the My SDSA API"""
    print("🧪 Testing My SDSA API...")
    print(f"🌐 API URL: {API_ENDPOINT}")
    print("-" * 50)
    
    try:
        # Make the API request
        response = requests.get(API_ENDPOINT, timeout=30)
        
        print(f"📡 Status Code: {response.status_code}")
        print(f"📄 Response Headers: {dict(response.headers)}")
        print(f"📄 Response Body: {response.text}")
        
        if response.status_code == 200:
            try:
                data = response.json()
                print("\n✅ API Response Parsed Successfully:")
                print(f"   Success: {data.get('success', 'N/A')}")
                print(f"   Message: {data.get('message', 'N/A')}")
                print(f"   Count: {data.get('count', 'N/A')}")
                
                my_sdsa_users = data.get('my_sdsa_users', [])
                print(f"\n📋 My SDSA Users Found: {len(my_sdsa_users)}")
                
                if my_sdsa_users:
                    print("\n👥 User Details:")
                    for i, user in enumerate(my_sdsa_users, 1):
                        print(f"\n   User {i}:")
                        print(f"     Name: {user.get('first_name', 'N/A')} {user.get('last_name', 'N/A')}")
                        print(f"     Phone: {user.get('Phone_number', 'N/A')}")
                        print(f"     Email: {user.get('email_id', 'N/A')}")
                        print(f"     Password: {user.get('password', 'N/A')}")
                        print(f"     Reporting To: {user.get('reportingTo', 'N/A')}")
                        print(f"     Rank: {user.get('rank', 'N/A')}")
                else:
                    print("   ⚠️  No SDSA users found created by superadmin")
                    
            except json.JSONDecodeError as e:
                print(f"❌ Failed to parse JSON response: {e}")
                return False
        else:
            print(f"❌ API request failed with status code: {response.status_code}")
            return False
            
    except requests.exceptions.RequestException as e:
        print(f"❌ Request failed: {e}")
        return False
    
    print("\n" + "=" * 50)
    return True

def test_database_connection():
    """Test database connection"""
    print("🔍 Testing database connection...")
    
    try:
        # Test basic connectivity
        test_url = f"{BASE_URL}/test_connection.php"
        response = requests.get(test_url, timeout=10)
        
        if response.status_code == 200:
            print("✅ Database connection test successful")
            return True
        else:
            print(f"❌ Database connection test failed: {response.status_code}")
            return False
            
    except requests.exceptions.RequestException as e:
        print(f"❌ Database connection test failed: {e}")
        return False

if __name__ == "__main__":
    print("🚀 My SDSA API Test Suite")
    print("=" * 50)
    
    # Test database connection first
    if test_database_connection():
        print("\n" + "=" * 50)
        # Test the main API
        success = test_my_sdsa_api()
        
        if success:
            print("🎉 All tests completed successfully!")
        else:
            print("❌ Some tests failed!")
            sys.exit(1)
    else:
        print("❌ Database connection failed. Please check your server configuration.")
        sys.exit(1) 