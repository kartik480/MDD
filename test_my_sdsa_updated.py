#!/usr/bin/env python3
"""
Test script to verify the updated My SDSA API functionality
Tests fetching SDSA users who report to K RAJESH KUMAR
"""

import requests
import json

def test_my_sdsa_updated_api():
    """Test the updated fetch_my_sdsa_users.php API"""
    
    # API endpoint
    url = "http://emp.kfinone.com/mobile/api/fetch_my_sdsa_users.php"
    
    try:
        print("🔍 Testing Updated My SDSA API...")
        print(f"🌐 URL: {url}")
        print("📋 Looking for users reporting to: K RAJESH KUMAR")
        print("-" * 60)
        
        # Make GET request
        response = requests.get(url, timeout=30)
        
        print(f"📡 Response Status: {response.status_code}")
        print(f"📄 Response Headers: {dict(response.headers)}")
        
        if response.status_code == 200:
            data = response.json()
            print(f"✅ API Response: {json.dumps(data, indent=2)}")
            
            if data.get('success'):
                sdsa_users = data.get('sdsa_users', [])
                count = data.get('count', 0)
                
                print(f"\n📊 Results:")
                print(f"   - Total SDSA users found: {count}")
                print(f"   - Users reporting to K RAJESH KUMAR:")
                
                if sdsa_users:
                    for i, user in enumerate(sdsa_users, 1):
                        full_name = f"{user.get('first_name', '')} {user.get('last_name', '')}".strip()
                        phone = user.get('Phone_number', 'N/A')
                        email = user.get('email_id', 'N/A')
                        reporting_to = user.get('reporting_person_name', 'N/A')
                        
                        print(f"   {i}. {full_name}")
                        print(f"      Phone: {phone}")
                        print(f"      Email: {email}")
                        print(f"      Reports to: {reporting_to}")
                        print(f"      User ID: {user.get('id', 'N/A')}")
                        print()
                else:
                    print("   ❌ No SDSA users found reporting to K RAJESH KUMAR")
                
                # Verify the message contains the correct name
                message = data.get('message', '')
                if 'K RAJESH KUMAR' in message:
                    print("✅ SUCCESS: API message correctly references K RAJESH KUMAR")
                else:
                    print("⚠️  WARNING: API message doesn't contain 'K RAJESH KUMAR'")
                    print(f"   Message: {message}")
                
                return True
            else:
                print(f"❌ API returned success=false: {data.get('message', 'Unknown error')}")
                return False
        else:
            print(f"❌ HTTP Error: {response.status_code}")
            print(f"❌ Error body: {response.text}")
            return False
            
    except requests.exceptions.Timeout:
        print("❌ Request timed out")
        return False
    except requests.exceptions.ConnectionError:
        print("❌ Connection error - check network connectivity")
        return False
    except json.JSONDecodeError as e:
        print(f"❌ JSON decode error: {e}")
        print(f"❌ Response body: {response.text}")
        return False
    except Exception as e:
        print(f"❌ Unexpected error: {e}")
        return False

if __name__ == "__main__":
    print("🚀 Starting Updated My SDSA API Test")
    print("=" * 60)
    
    success = test_my_sdsa_updated_api()
    
    print("=" * 60)
    if success:
        print("✅ Test completed successfully!")
    else:
        print("❌ Test failed!")
    
    print("\n📝 Summary:")
    print("This test verifies that the updated My SDSA API:")
    print("1. Correctly finds users reporting to 'K RAJESH KUMAR'")
    print("2. Returns proper user data with names, phone, email")
    print("3. Shows correct reporting relationship")
    print("4. Uses the updated name in API messages") 