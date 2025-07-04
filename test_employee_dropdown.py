#!/usr/bin/env python3
"""
Test script to verify the employee dropdown API functionality
"""

import requests
import json

def test_employee_dropdown_api():
    """Test the fetch_sdsa_users_by_designation.php API"""
    
    # API endpoint
    url = "http://emp.kfinone.com/mobile/api/fetch_sdsa_users_by_designation.php"
    
    try:
        print("🔍 Testing Employee Dropdown API...")
        print(f"🌐 URL: {url}")
        
        # Make GET request
        response = requests.get(url, timeout=30)
        
        print(f"📡 Response Status: {response.status_code}")
        print(f"📄 Response Headers: {dict(response.headers)}")
        
        if response.status_code == 200:
            data = response.json()
            print(f"✅ API Response: {json.dumps(data, indent=2)}")
            
            if data.get('success'):
                users = data.get('users', [])
                dropdown_options = data.get('dropdown_options', [])
                count = data.get('count', 0)
                
                print(f"\n📊 Results:")
                print(f"   - Total users found: {count}")
                print(f"   - Users with Chief Business Officer or Director designation:")
                
                for i, user in enumerate(users, 1):
                    full_name = f"{user.get('firstName', '')} {user.get('lastName', '')}".strip()
                    designation = user.get('designation_name', 'N/A')
                    display_name = user.get('display_name', 'N/A')
                    
                    print(f"   {i}. {full_name} - {designation}")
                    print(f"      Display: {display_name}")
                    print(f"      Username: {user.get('username', 'N/A')}")
                    print(f"      ID: {user.get('id', 'N/A')}")
                    print()
                
                print(f"📋 Dropdown Options ({len(dropdown_options)}):")
                for i, option in enumerate(dropdown_options, 1):
                    print(f"   {i}. {option}")
                
                # Verify that only Chief Business Officer and Director are included
                designations = set(user.get('designation_name') for user in users)
                expected_designations = {'Chief Business Officer', 'Director'}
                
                print(f"\n✅ Verification:")
                print(f"   - Expected designations: {expected_designations}")
                print(f"   - Found designations: {designations}")
                
                if designations.issubset(expected_designations):
                    print("   ✅ SUCCESS: Only expected designations found!")
                else:
                    print("   ❌ WARNING: Unexpected designations found!")
                    unexpected = designations - expected_designations
                    if unexpected:
                        print(f"      Unexpected: {unexpected}")
                
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
    print("🚀 Starting Employee Dropdown API Test")
    print("=" * 50)
    
    success = test_employee_dropdown_api()
    
    print("=" * 50)
    if success:
        print("✅ Test completed successfully!")
    else:
        print("❌ Test failed!")
    
    print("\n📝 Summary:")
    print("This test verifies that the employee dropdown API:")
    print("1. Returns only users with 'Chief Business Officer' or 'Director' designation")
    print("2. Includes firstName, lastName, designation_name, and display_name")
    print("3. Provides proper dropdown options for the Flutter app") 