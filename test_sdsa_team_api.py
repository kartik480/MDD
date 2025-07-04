#!/usr/bin/env python3
"""
Test script for SDSA Team APIs
Tests the new SDSA Team functionality similar to Employee Team
"""

import requests
import json

# Base URL for the API
BASE_URL = "http://emp.kfinone.com/mobile/api"

def test_sdsa_users_by_designation():
    """Test fetching SDSA users by designation for dropdown"""
    print("🔍 Testing SDSA users by designation API...")
    
    try:
        response = requests.get(f"{BASE_URL}/fetch_sdsa_users_by_designation.php")
        
        print(f"📡 Status Code: {response.status_code}")
        print(f"📄 Response Headers: {dict(response.headers)}")
        
        if response.status_code == 200:
            data = response.json()
            print(f"✅ Response: {json.dumps(data, indent=2)}")
            
            if data.get('success'):
                sdsa_users = data.get('sdsa_users', [])
                dropdown_options = data.get('dropdown_options', [])
                count = data.get('count', 0)
                
                print(f"📊 Found {count} SDSA users")
                print(f"📋 Dropdown options: {dropdown_options}")
                
                if sdsa_users:
                    print("\n📝 Sample SDSA users:")
                    for i, user in enumerate(sdsa_users[:3]):  # Show first 3 users
                        print(f"  {i+1}. {user.get('display_name', 'N/A')}")
                        print(f"     ID: {user.get('id', 'N/A')}")
                        print(f"     Name: {user.get('first_name', '')} {user.get('last_name', '')}")
                        print(f"     Phone: {user.get('Phone_number', 'N/A')}")
                        print(f"     Email: {user.get('email_id', 'N/A')}")
                        print(f"     Reports to: {user.get('reporting_person_name', 'N/A')}")
                        print()
                
                return True
            else:
                print(f"❌ API returned success=false: {data.get('message', 'Unknown error')}")
                return False
        else:
            print(f"❌ HTTP Error: {response.status_code}")
            print(f"❌ Error body: {response.text}")
            return False
            
    except requests.exceptions.RequestException as e:
        print(f"❌ Request error: {e}")
        return False
    except json.JSONDecodeError as e:
        print(f"❌ JSON decode error: {e}")
        print(f"❌ Response body: {response.text}")
        return False
    except Exception as e:
        print(f"❌ Unexpected error: {e}")
        return False

def test_sdsa_users_reporting_to_sdsa(user_id):
    """Test fetching SDSA users who report to a specific SDSA user"""
    print(f"🔍 Testing SDSA users reporting to SDSA user ID: {user_id}...")
    
    try:
        response = requests.get(f"{BASE_URL}/fetch_sdsa_users_reporting_to_sdsa.php?user_id={user_id}")
        
        print(f"📡 Status Code: {response.status_code}")
        print(f"📄 Response Headers: {dict(response.headers)}")
        
        if response.statusCode == 200:
            data = response.json()
            print(f"✅ Response: {json.dumps(data, indent=2)}")
            
            if data.get('success'):
                reporting_users = data.get('reporting_users', [])
                count = data.get('count', 0)
                
                print(f"📊 Found {count} SDSA users reporting to user ID {user_id}")
                
                if reporting_users:
                    print("\n📝 Reporting users:")
                    for i, user in enumerate(reporting_users):
                        print(f"  {i+1}. {user.get('full_name', 'N/A')}")
                        print(f"     ID: {user.get('id', 'N/A')}")
                        print(f"     Name: {user.get('first_name', '')} {user.get('last_name', '')}")
                        print(f"     Phone: {user.get('Phone_number', 'N/A')}")
                        print(f"     Email: {user.get('email_id', 'N/A')}")
                        print(f"     Reports to: {user.get('manager_name', 'N/A')}")
                        print()
                else:
                    print("📝 No users found reporting to this SDSA user")
                
                return True
            else:
                print(f"❌ API returned success=false: {data.get('message', 'Unknown error')}")
                return False
        else:
            print(f"❌ HTTP Error: {response.status_code}")
            print(f"❌ Error body: {response.text}")
            return False
            
    except requests.exceptions.RequestException as e:
        print(f"❌ Request error: {e}")
        return False
    except json.JSONDecodeError as e:
        print(f"❌ JSON decode error: {e}")
        print(f"❌ Response body: {response.text}")
        return False
    except Exception as e:
        print(f"❌ Unexpected error: {e}")
        return False

def main():
    """Main test function"""
    print("🚀 Starting SDSA Team API Tests")
    print("=" * 50)
    
    # Test 1: Fetch SDSA users by designation
    print("\n1️⃣ Testing SDSA users by designation...")
    success1 = test_sdsa_users_by_designation()
    
    if success1:
        print("✅ SDSA users by designation test passed!")
        
        # Test 2: Test with a sample user ID (you can modify this)
        print("\n2️⃣ Testing SDSA users reporting to SDSA...")
        # You can change this user ID based on your data
        test_user_id = 1  # Change this to a valid SDSA user ID
        success2 = test_sdsa_users_reporting_to_sdsa(test_user_id)
        
        if success2:
            print("✅ SDSA users reporting to SDSA test passed!")
        else:
            print("❌ SDSA users reporting to SDSA test failed!")
    else:
        print("❌ SDSA users by designation test failed!")
    
    print("\n" + "=" * 50)
    print("🏁 SDSA Team API Tests completed!")

if __name__ == "__main__":
    main() 