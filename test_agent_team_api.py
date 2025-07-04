#!/usr/bin/env python3
"""
Test script for Agent Team API - fetchUsersByDesignation
"""

import requests
import json

def test_fetch_users_by_designation():
    """Test the fetchUsersByDesignation API endpoint"""
    
    # API endpoint
    url = "http://emp.kfinone.com/mobile/api/fetch_users_by_designation.php"
    
    print("🔍 Testing Agent Team API - fetchUsersByDesignation")
    print("=" * 60)
    
    try:
        # Make the request
        response = requests.get(url, timeout=10)
        
        print(f"📡 Response Status: {response.status_code}")
        print(f"📡 Response Headers: {dict(response.headers)}")
        
        if response.status_code == 200:
            try:
                data = response.json()
                print(f"✅ JSON Response: {json.dumps(data, indent=2)}")
                
                if data.get('success') == True:
                    designated_users = data.get('designated_users', [])
                    print(f"\n📊 Found {len(designated_users)} designated users")
                    
                    if designated_users:
                        print("\n👥 Sample users:")
                        for i, user in enumerate(designated_users[:5]):  # Show first 5
                            fullname = f"{user.get('firstName', '')} {user.get('lastName', '')}".strip()
                            designation = user.get('designation_name', 'N/A')
                            print(f"  {i+1}. {fullname} - {designation}")
                        
                        if len(designated_users) > 5:
                            print(f"  ... and {len(designated_users) - 5} more users")
                    else:
                        print("⚠️  No designated users found")
                        
                else:
                    print(f"❌ API returned success=false: {data.get('message', 'Unknown error')}")
                    
            except json.JSONDecodeError as e:
                print(f"❌ Failed to parse JSON: {e}")
                print(f"📄 Raw response: {response.text}")
                
        else:
            print(f"❌ HTTP Error {response.status_code}")
            print(f"📄 Response: {response.text}")
            
    except requests.exceptions.RequestException as e:
        print(f"❌ Request failed: {e}")
    except Exception as e:
        print(f"❌ Unexpected error: {e}")

if __name__ == "__main__":
    test_fetch_users_by_designation() 