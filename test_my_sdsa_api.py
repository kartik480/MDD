#!/usr/bin/env python3
"""
Test script for My SDSA API endpoint
Tests fetching SDSA users who report to KRAJESHK (id 1)
"""

import requests
import json
from datetime import datetime

# API Configuration
BASE_URL = "https://emp.kfinone.com/api"
ENDPOINT = "/fetch_my_sdsa_users.php"
FULL_URL = BASE_URL + ENDPOINT

def test_my_sdsa_api():
    """Test the My SDSA API endpoint"""
    print("=" * 60)
    print("🧪 TESTING MY SDSA API ENDPOINT")
    print("=" * 60)
    print(f"📡 URL: {FULL_URL}")
    print(f"⏰ Time: {datetime.now().strftime('%Y-%m-%d %H:%M:%S')}")
    print()
    
    try:
        # Make the API request
        print("🔄 Making API request...")
        response = requests.get(
            FULL_URL,
            headers={
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            timeout=30
        )
        
        print(f"📊 Status Code: {response.status_code}")
        print(f"📄 Response Headers: {dict(response.headers)}")
        print()
        
        # Check if request was successful
        if response.status_code == 200:
            print("✅ Request successful!")
            
            try:
                data = response.json()
                print(f"📋 Response Data Type: {type(data)}")
                print(f"📋 Response Keys: {list(data.keys())}")
                print()
                
                # Check response structure
                if 'success' in data:
                    print(f"✅ Success Flag: {data['success']}")
                else:
                    print("❌ Missing 'success' field in response")
                
                if 'message' in data:
                    print(f"📝 Message: {data['message']}")
                else:
                    print("❌ Missing 'message' field in response")
                
                if 'count' in data:
                    print(f"📊 Count: {data['count']}")
                else:
                    print("❌ Missing 'count' field in response")
                
                if 'sdsa_users' in data:
                    sdsa_users = data['sdsa_users']
                    print(f"👥 SDSA Users: {len(sdsa_users)} users found")
                    print()
                    
                    # Display user details
                    if sdsa_users:
                        print("📋 USER DETAILS:")
                        print("-" * 40)
                        for i, user in enumerate(sdsa_users, 1):
                            print(f"User {i}:")
                            print(f"  ID: {user.get('id', 'N/A')}")
                            print(f"  Name: {user.get('first_name', '')} {user.get('last_name', '')}")
                            print(f"  Phone: {user.get('Phone_number', 'N/A')}")
                            print(f"  Email: {user.get('email_id', 'N/A')}")
                            print(f"  Reports To ID: {user.get('reportingTo', 'N/A')}")
                            print(f"  Reports To Name: {user.get('reporting_person_name', 'N/A')}")
                            print()
                    else:
                        print("ℹ️  No SDSA users found reporting to KRAJESHK")
                else:
                    print("❌ Missing 'sdsa_users' field in response")
                
                # Validate data structure
                print("🔍 DATA VALIDATION:")
                if 'sdsa_users' in data and isinstance(data['sdsa_users'], list):
                    print("✅ sdsa_users is a list")
                    
                    if data['sdsa_users']:
                        first_user = data['sdsa_users'][0]
                        required_fields = ['id', 'first_name', 'last_name', 'Phone_number', 'email_id', 'reportingTo']
                        
                        for field in required_fields:
                            if field in first_user:
                                print(f"✅ Field '{field}' present")
                            else:
                                print(f"❌ Field '{field}' missing")
                        
                        if 'reporting_person_name' in first_user:
                            print("✅ Field 'reporting_person_name' present")
                        else:
                            print("❌ Field 'reporting_person_name' missing")
                    else:
                        print("ℹ️  No users to validate")
                else:
                    print("❌ sdsa_users is not a list or missing")
                
            except json.JSONDecodeError as e:
                print(f"❌ Failed to parse JSON response: {e}")
                print(f"📄 Raw response: {response.text}")
        else:
            print(f"❌ Request failed with status code: {response.status_code}")
            print(f"📄 Error response: {response.text}")
    
    except requests.exceptions.Timeout:
        print("❌ Request timed out")
    except requests.exceptions.ConnectionError:
        print("❌ Connection error - check network connectivity")
    except requests.exceptions.RequestException as e:
        print(f"❌ Request error: {e}")
    except Exception as e:
        print(f"❌ Unexpected error: {e}")
    
    print()
    print("=" * 60)
    print("🏁 TEST COMPLETED")
    print("=" * 60)

if __name__ == "__main__":
    test_my_sdsa_api() 