#!/usr/bin/env python3
"""
Test script to show available users in SDSA Team dropdown
"""

import requests
import json

# Base URL for the API
BASE_URL = "http://emp.kfinone.com/mobile/api"

def test_sdsa_team_dropdown_users():
    """Test fetching designated users for SDSA Team dropdown"""
    print("🔍 Testing SDSA Team dropdown users...")
    print("📋 This shows the available users in the SDSA Team dropdown")
    print("=" * 60)
    
    try:
        response = requests.get(f"{BASE_URL}/fetch_users_by_designation.php")
        
        print(f"📡 Status Code: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            
            if data.get('success'):
                users = data.get('users', [])
                count = data.get('count', 0)
                
                print(f"✅ Success! Found {count} designated users")
                print("\n📋 Available users in SDSA Team dropdown:")
                print("-" * 50)
                
                if users:
                    for i, user in enumerate(users, 1):
                        display_name = user.get('display_name', 'N/A')
                        designation = user.get('designation_name', 'N/A')
                        user_id = user.get('id', 'N/A')
                        
                        print(f"{i:2d}. {display_name}")
                        print(f"    Designation: {designation}")
                        print(f"    ID: {user_id}")
                        print()
                else:
                    print("❌ No users found")
                    
                # Show dropdown options
                dropdown_options = data.get('dropdown_options', [])
                if dropdown_options:
                    print("📝 Dropdown options:")
                    for i, option in enumerate(dropdown_options, 1):
                        print(f"  {i}. {option}")
                        
                return True
            else:
                print(f"❌ API returned success=false: {data.get('message', 'Unknown error')}")
                return False
                
        else:
            print(f"❌ HTTP Error: {response.status_code}")
            print(f"❌ Response: {response.text}")
            return False
            
    except requests.exceptions.Timeout:
        print("❌ Request timed out")
        return False
    except requests.exceptions.ConnectionError:
        print("❌ Connection error - check network connectivity")
        return False
    except Exception as e:
        print(f"❌ Unexpected error: {e}")
        return False

def test_sdsa_users_reporting_to_designated_user(user_id):
    """Test fetching SDSA users who report to a designated user"""
    print(f"\n🔍 Testing SDSA users reporting to designated user ID: {user_id}...")
    print("📋 This shows the SDSA users that report to the selected designated user")
    print("=" * 60)
    
    try:
        response = requests.get(f"{BASE_URL}/fetch_sdsa_users_reporting_to_sdsa.php?user_id={user_id}")
        
        print(f"📡 Status Code: {response.status_code}")
        
        if response.status_code == 200:
            data = response.json()
            
            if data.get('success'):
                reporting_users = data.get('reporting_users', [])
                count = data.get('count', 0)
                
                print(f"✅ Success! Found {count} SDSA users reporting to user ID {user_id}")
                
                if reporting_users:
                    print("\n📝 SDSA users reporting to this designated user:")
                    print("-" * 50)
                    for i, user in enumerate(reporting_users, 1):
                        full_name = user.get('full_name', 'N/A')
                        phone = user.get('Phone_number', 'N/A')
                        email = user.get('email_id', 'N/A')
                        
                        print(f"{i:2d}. {full_name}")
                        print(f"    Phone: {phone}")
                        print(f"    Email: {email}")
                        print()
                else:
                    print("📝 No SDSA users found reporting to this designated user")
                    
                return True
            else:
                print(f"❌ API returned success=false: {data.get('message', 'Unknown error')}")
                return False
                
        else:
            print(f"❌ HTTP Error: {response.status_code}")
            print(f"❌ Response: {response.text}")
            return False
            
    except requests.exceptions.Timeout:
        print("❌ Request timed out")
        return False
    except requests.exceptions.ConnectionError:
        print("❌ Connection error - check network connectivity")
        return False
    except Exception as e:
        print(f"❌ Unexpected error: {e}")
        return False

if __name__ == "__main__":
    print("🚀 SDSA Team Users Test")
    print("=" * 60)
    
    # Test dropdown users
    success = test_sdsa_team_dropdown_users()
    
    if success:
        # Test with first user ID if available
        response = requests.get(f"{BASE_URL}/fetch_users_by_designation.php")
        if response.status_code == 200:
            data = response.json()
            users = data.get('users', [])
            if users:
                first_user_id = users[0].get('id')
                if first_user_id:
                    test_sdsa_users_reporting_to_designated_user(first_user_id)
    
    print("\n✅ Test completed!") 