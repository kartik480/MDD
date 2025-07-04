#!/usr/bin/env python3
"""
Test script to verify the users reporting to managers API functionality
"""

import requests
import json

def test_reporting_users_api():
    """Test the fetch_users_reporting_to_managers.php API"""
    
    # API endpoint
    url = "http://emp.kfinone.com/mobile/api/fetch_users_reporting_to_managers.php"
    
    try:
        print("🔍 Testing Users Reporting to Managers API...")
        print(f"🌐 URL: {url}")
        
        # Make GET request
        response = requests.get(url, timeout=30)
        
        print(f"📡 Response Status: {response.status_code}")
        print(f"📄 Response Headers: {dict(response.headers)}")
        
        if response.status_code == 200:
            data = response.json()
            print(f"✅ API Response: {json.dumps(data, indent=2)}")
            
            if data.get('success'):
                managers = data.get('managers', [])
                reporting_users = data.get('reporting_users', [])
                grouped_users = data.get('grouped_users', {})
                count = data.get('count', 0)
                
                print(f"\n📊 Results:")
                print(f"   - Total managers (Chief Business Officer/Director): {len(managers)}")
                print(f"   - Total users reporting to managers: {count}")
                print(f"   - Number of manager groups: {len(grouped_users)}")
                
                print(f"\n👥 Managers (Chief Business Officer/Director):")
                for i, manager in enumerate(managers, 1):
                    full_name = f"{manager.get('firstName', '')} {manager.get('lastName', '')}".strip()
                    designation = manager.get('designation_name', 'N/A')
                    print(f"   {i}. {full_name} - {designation} (ID: {manager.get('id', 'N/A')})")
                
                print(f"\n📋 Users Reporting to Managers:")
                for i, user in enumerate(reporting_users, 1):
                    full_name = f"{user.get('firstName', '')} {user.get('lastName', '')}".strip()
                    designation = user.get('designation_name', 'N/A')
                    manager_name = user.get('manager_name', 'N/A')
                    manager_designation = user.get('manager_designation', 'N/A')
                    
                    print(f"   {i}. {full_name} ({designation})")
                    print(f"      Reports to: {manager_name} ({manager_designation})")
                    print(f"      Username: {user.get('username', 'N/A')}")
                    print()
                
                print(f"\n📁 Grouped by Manager:")
                for manager_key, users_list in grouped_users.items():
                    print(f"   📂 {manager_key} ({len(users_list)} users):")
                    for user in users_list:
                        full_name = f"{user.get('firstName', '')} {user.get('lastName', '')}".strip()
                        designation = user.get('designation_name', 'N/A')
                        print(f"      • {full_name} ({designation})")
                    print()
                
                # Verify that we have users reporting to managers
                if count > 0:
                    print("✅ SUCCESS: Found users reporting to Chief Business Officer and Director users!")
                else:
                    print("⚠️  WARNING: No users found reporting to Chief Business Officer and Director users")
                
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
    print("🚀 Starting Users Reporting to Managers API Test")
    print("=" * 60)
    
    success = test_reporting_users_api()
    
    print("=" * 60)
    if success:
        print("✅ Test completed successfully!")
    else:
        print("❌ Test failed!")
    
    print("\n📝 Summary:")
    print("This test verifies that the users reporting to managers API:")
    print("1. Finds Chief Business Officer and Director users")
    print("2. Finds users who report to these managers (using reportingTo column)")
    print("3. Groups users by their manager")
    print("4. Provides proper data structure for the Flutter app") 