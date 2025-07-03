#!/usr/bin/env python3
"""
Test script to check table structure
"""

import urllib.request
import json

def test_table_structure():
    """Test the check_table_structure.php API endpoint"""
    
    # API endpoint URL
    url = "http://emp.kfinone.com/mobile/api/check_table_structure.php"
    
    print("🔍 Testing Table Structure API...")
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
                    table_data = data.get('data', {})
                    
                    # Show tbl_user columns
                    if 'tbl_user_columns' in table_data:
                        print("\n📋 tbl_user columns:")
                        for col in table_data['tbl_user_columns']:
                            print(f"  - {col.get('Field', 'N/A')} ({col.get('Type', 'N/A')})")
                    
                    # Show tbl_designation columns
                    if 'tbl_designation_columns' in table_data:
                        print("\n📋 tbl_designation columns:")
                        for col in table_data['tbl_designation_columns']:
                            print(f"  - {col.get('Field', 'N/A')} ({col.get('Type', 'N/A')})")
                    
                    # Show sample data
                    if 'tbl_user_sample' in table_data:
                        print("\n📊 tbl_user sample data:")
                        for i, user in enumerate(table_data['tbl_user_sample']):
                            print(f"  User {i+1}: {user}")
                    
                    if 'tbl_designation_sample' in table_data:
                        print("\n📊 tbl_designation sample data:")
                        for i, designation in enumerate(table_data['tbl_designation_sample']):
                            print(f"  Designation {i+1}: {designation}")
                            
                else:
                    print(f"❌ API returned success=false: {data.get('message', 'Unknown error')}")
                    
            except json.JSONDecodeError as e:
                print(f"❌ Failed to parse JSON response: {e}")
        else:
            print(f"❌ HTTP Error: {response.status}")
            
    except Exception as e:
        print(f"❌ Error: {e}")

if __name__ == "__main__":
    test_table_structure() 