#!/usr/bin/env python3
"""
Simple API Test for Managing Director App
This script will help debug login issues
"""

import requests
import json

# API Configuration
BASE_URL = "http://emp.kfinone.com/mobile/api"

def test_endpoint(url, method="GET", data=None):
    """Test an API endpoint and return detailed results"""
    print(f"\n{'='*50}")
    print(f"Testing: {method} {url}")
    print(f"{'='*50}")
    
    try:
        if method == "GET":
            response = requests.get(url, timeout=10)
        elif method == "POST":
            headers = {"Content-Type": "application/json"}
            response = requests.post(url, json=data, headers=headers, timeout=10)
        
        print(f"Status Code: {response.status_code}")
        print(f"Headers: {dict(response.headers)}")
        print(f"Response Body: {response.text}")
        
        if response.status_code == 200:
            try:
                json_data = response.json()
                print(f"JSON Response: {json.dumps(json_data, indent=2)}")
                return json_data
            except:
                print("Response is not valid JSON")
                return None
        else:
            print(f"HTTP Error: {response.status_code}")
            return None
            
    except requests.exceptions.RequestException as e:
        print(f"Request Error: {e}")
        return None
    except Exception as e:
        print(f"General Error: {e}")
        return None

def main():
    """Run all tests"""
    print("🚀 API Debug Test for Managing Director App")
    
    # Test 1: Basic test endpoint
    print("\n1️⃣ Testing basic endpoint...")
    test_endpoint(f"{BASE_URL}/test.php")
    
    # Test 2: Database connection
    print("\n2️⃣ Testing database connection...")
    test_endpoint(f"{BASE_URL}/test_connection.php")
    
    # Test 3: Database diagnosis
    print("\n3️⃣ Running database diagnosis...")
    test_endpoint(f"{BASE_URL}/diagnose.php")
    
    # Test 4: Login with admin
    print("\n4️⃣ Testing admin login...")
    test_endpoint(
        f"{BASE_URL}/login.php",
        method="POST",
        data={"username": "admin", "password": "admin123"}
    )
    
    # Test 5: Login with director
    print("\n5️⃣ Testing director login...")
    test_endpoint(
        f"{BASE_URL}/login.php",
        method="POST",
        data={"username": "director", "password": "director123"}
    )
    
    # Test 6: Login with invalid credentials
    print("\n6️⃣ Testing invalid login...")
    test_endpoint(
        f"{BASE_URL}/login.php",
        method="POST",
        data={"username": "invalid", "password": "wrong"}
    )
    
    # Test 7: Login with missing fields
    print("\n7️⃣ Testing missing fields...")
    test_endpoint(
        f"{BASE_URL}/login.php",
        method="POST",
        data={"username": "admin"}
    )
    
    print("\n" + "="*50)
    print("🎉 All tests completed!")
    print("\n📋 Next Steps:")
    print("1. Check the output above for any errors")
    print("2. Make sure all API files are uploaded to hosting")
    print("3. Verify database exists and has the tbl_user table")
    print("4. Check if the database credentials are correct")

if __name__ == "__main__":
    main() 