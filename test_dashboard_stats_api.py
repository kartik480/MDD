#!/usr/bin/env python3
"""
Test script for Dashboard Statistics API
Tests the fetch_dashboard_stats.php endpoint
"""

import requests
import json
import sys

def test_dashboard_stats_api():
    """Test the dashboard statistics API endpoint"""
    
    # API endpoint URL
    base_url = "https://emp.kfinone.com/api"
    endpoint = f"{base_url}/fetch_dashboard_stats.php"
    
    print("🧪 Testing Dashboard Statistics API")
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
                    stats = data.get('data', {})
                    print(f"👥 Total Employees: {stats.get('total_emp', 0)}")
                    print(f"🔒 Total SDSA: {stats.get('total_sdsa', 0)}")
                    print(f"🤝 Total Partners: {stats.get('total_partner', 0)}")
                    print(f"📁 Total Portfolio: {stats.get('total_portfolio', 0)}")
                    print(f"🔍 Total Agents: {stats.get('total_agents', 0)}")
                    
                    # Show total count
                    total = sum([
                        stats.get('total_emp', 0),
                        stats.get('total_sdsa', 0),
                        stats.get('total_partner', 0),
                        stats.get('total_portfolio', 0),
                        stats.get('total_agents', 0)
                    ])
                    print(f"\n📊 Total Records: {total}")
                        
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

def test_curl_command():
    """Test using curl command"""
    print("\n" + "="*60)
    print("🔧 CURL Command Test")
    print("="*60)
    
    base_url = "https://emp.kfinone.com/api"
    endpoint = f"{base_url}/fetch_dashboard_stats.php"
    
    curl_command = f'curl -X GET "{endpoint}" -H "Content-Type: application/json" -H "Accept: application/json"'
    
    print("💻 CURL Command:")
    print(curl_command)
    print("\n📝 You can run this command in terminal to test manually")

if __name__ == "__main__":
    print("🚀 Dashboard Statistics API Test Suite")
    print("="*60)
    
    # Run the main test
    success = test_dashboard_stats_api()
    
    # Show curl command
    test_curl_command()
    
    # Exit with appropriate code
    sys.exit(0 if success else 1) 