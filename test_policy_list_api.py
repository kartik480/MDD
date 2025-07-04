#!/usr/bin/env python3
"""
Test script for Policy List API
Tests the fetch_policy_list.php endpoint
"""

import requests
import json
import sys

def test_policy_list_api():
    """Test the policy list API endpoint"""
    
    # API endpoint URL
    base_url = "https://emp.kfinone.com/api"
    endpoint = f"{base_url}/fetch_policy_list.php"
    
    print("🧪 Testing Policy List API")
    print(f"🌐 Endpoint: {endpoint}")
    print("-" * 50)
    
    try:
        # Make GET request
        print("📡 Making GET request...")
        response = requests.get(endpoint, timeout=30)
        
        print(f"📊 Status Code: {response.status_code}")
        print(f"📄 Response Headers: {dict(response.headers)}")
        
        # Check if request was successful
        if response.status_code == 200:
            try:
                data = response.json()
                print("✅ JSON Response:")
                print(json.dumps(data, indent=2))
                
                # Validate response structure
                if data.get('success') == True:
                    print("\n✅ API Test PASSED!")
                    policies = data.get('data', {}).get('policies', [])
                    count = data.get('data', {}).get('count', 0)
                    print(f"📋 Policies Found: {count}")
                    
                    # Show sample data
                    if policies:
                        print(f"\n📋 Sample Policies:")
                        for i, policy in enumerate(policies[:3]):  # Show first 3 policies
                            print(f"  {i+1}. ID: {policy.get('id')}")
                            print(f"     Vendor Bank: {policy.get('vendor_bank_name', 'N/A')}")
                            print(f"     Loan Type: {policy.get('loan_type', 'N/A')}")
                            print(f"     Has Image: {'Yes' if policy.get('image') else 'No'}")
                            print(f"     Content: {policy.get('content', 'N/A')[:50]}...")
                            print()
                    else:
                        print("  No policies found in database")
                        
                else:
                    print("\n❌ API Test FAILED!")
                    print(f"❌ Error: {data.get('message', 'Unknown error')}")
                    return False
                    
            except json.JSONDecodeError as e:
                print(f"\n❌ JSON Decode Error: {e}")
                print(f"📄 Raw Response: {response.text}")
                return False
                
        else:
            print(f"\n❌ HTTP Error: {response.status_code}")
            print(f"📄 Error Response: {response.text}")
            return False
            
    except requests.exceptions.Timeout:
        print("\n❌ Request Timeout - Server took too long to respond")
        return False
    except requests.exceptions.ConnectionError:
        print("\n❌ Connection Error - Could not connect to server")
        return False
    except Exception as e:
        print(f"\n❌ Unexpected Error: {e}")
        return False
    
    return True

if __name__ == "__main__":
    success = test_policy_list_api()
    sys.exit(0 if success else 1) 