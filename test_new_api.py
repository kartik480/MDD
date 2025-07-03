#!/usr/bin/env python3
"""
Test script for the new fetch_sdsa_users_by_designation.php API
"""

import urllib.request
import json

def test_new_api():
    """Test the fetch_sdsa_users_by_designation.php API endpoint"""
    
    # API endpoint URL
    url = "http://emp.kfinone.com/mobile/api/fetch_sdsa_users_by_designation.php"
    
    print("🔍 Testing New SDSA Users by Designation API...")
    print(f"🌐 URL: {url}")
    print("-" * 50)
    
    try:
        # Make GET request
        req = urllib.request.Request(url, headers={'Content-Type': 'application/json'})
        with urllib.request.urlopen(req, timeout=30) as response:
            response_text = response.read().decode('utf-8')
            
        print(f"📡 Status Code: {response.status}")
        print(f"📄 Response Body: {response_text}")
        
        if response.status == 200:
            try:
                data = json.loads(response_text)
                print("\n✅ JSON Response:")
                print(json.dumps(data, indent=2))
                
                if data.get('success'):
                    users = data.get('users', [])
                    dropdown_options = data.get('dropdown_options', [])
                    count = data.get('count', 0)
                    
                    print(f"\n📊 Found {count} users with specific designations")
                    
                    # Show dropdown options
                    if dropdown_options:
                        print("\n📋 Dropdown Options:")
                        for i, option in enumerate(dropdown_options):
                            print(f"  {i+1}. {option}")
                    
                    if users:
                        print("\n👥 Sample Users:")
                        for i, user in enumerate(users[:3]):  # Show first 3 users
                            print(f"  {i+1}. {user.get('display_name', 'N/A')}")
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
            print(f"❌ HTTP Error: {response.status}")
            
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    test_new_api() 