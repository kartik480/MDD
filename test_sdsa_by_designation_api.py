#!/usr/bin/env python3
"""
Test script for the SDSA users by designation API endpoint
"""

import requests
import json

def test_sdsa_users_by_designation_api():
    """Test the fetch_sdsa_users_by_designation.php API endpoint"""
    
    # API endpoint URL
    url = "http://emp.kfinone.com/mobile/api/fetch_sdsa_users_by_designation.php"
    
    print("🔍 Testing SDSA Users by Designation API...")
    print(f"🌐 URL: {url}")
    print("-" * 50)
    
    try:
        # Make GET request
        response = requests.get(url, timeout=30)
        
        print(f"📡 Status Code: {response.status_code}")
        print(f"📄 Response Headers: {dict(response.headers)}")
        print(f"📄 Response Body: {response.text}")
        
        if response.status_code == 200:
            try:
                data = response.json()
                print("\n✅ JSON Response:")
                print(json.dumps(data, indent=2))
                
                if data.get('success'):
                    users = data.get('users', [])
                    count = data.get('count', 0)
                    print(f"\n📊 Found {count} users with specific designations")
                    
                    if users:
                        print("\n👥 Sample Users:")
                        for i, user in enumerate(users[:3]):  # Show first 3 users
                            print(f"  {i+1}. {user.get('firstName', 'N/A')} {user.get('lastName', 'N/A')}")
                            print(f"     Username: {user.get('username', 'N/A')}")
                            print(f"     Designation: {user.get('designation_name', 'N/A')}")
                            print()
                    else:
                        print("❌ No users found")
                else:
                    print(f"❌ API returned success=false: {data.get('message', 'Unknown error')}")
                    
            except json.JSONDecodeError as e:
                print(f"❌ Failed to parse JSON response: {e}")
        else:
            print(f"❌ HTTP Error: {response.status_code}")
            
    except requests.exceptions.Timeout:
        print("❌ Request timed out")
    except requests.exceptions.ConnectionError:
        print("❌ Connection error - check network connectivity")
    except Exception as e:
        print(f"❌ Unexpected error: {e}")

if __name__ == "__main__":
    test_sdsa_users_by_designation_api() 