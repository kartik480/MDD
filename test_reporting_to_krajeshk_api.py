#!/usr/bin/env python3
"""
Test script for the updated fetch_sdsa_users_reporting_to_krajeshk.php API
"""

import requests
import json

def test_reporting_to_krajeshk_api():
    """Test the API endpoint for users reporting to KRAJESHK"""
    
    # API endpoint
    url = "http://emp.kfinone.com/mobile/api/fetch_sdsa_users_reporting_to_krajeshk.php"
    
    print("🔍 Testing API: fetch_sdsa_users_reporting_to_krajeshk.php")
    print(f"🌐 URL: {url}")
    print("-" * 60)
    
    try:
        # Make GET request
        response = requests.get(url, timeout=30)
        
        print(f"📡 Status Code: {response.statusCode}")
        print(f"📄 Response Headers: {dict(response.headers)}")
        print(f"📄 Response Body: {response.text}")
        
        if response.status_code == 200:
            try:
                data = response.json()
                print("\n✅ JSON Response:")
                print(json.dumps(data, indent=2))
                
                if data.get('success'):
                    users = data.get('sdsa_users', [])
                    count = data.get('count', 0)
                    print(f"\n📊 Found {count} users reporting to KRAJESHK")
                    
                    if users:
                        print("\n👥 Users found:")
                        for i, user in enumerate(users, 1):
                            print(f"  {i}. {user.get('first_name', 'N/A')} {user.get('last_name', 'N/A')}")
                            print(f"     Email: {user.get('email_id', 'N/A')}")
                            print(f"     Password: {user.get('password', 'N/A')}")
                            print()
                    else:
                        print("❌ No users found")
                else:
                    print(f"❌ API Error: {data.get('message', 'Unknown error')}")
                    
            except json.JSONDecodeError as e:
                print(f"❌ JSON Decode Error: {e}")
        else:
            print(f"❌ HTTP Error: {response.status_code}")
            
    except requests.exceptions.RequestException as e:
        print(f"❌ Request Error: {e}")

if __name__ == "__main__":
    test_reporting_to_krajeshk_api() 