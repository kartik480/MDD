#!/bin/bash

# API Test Script for Managing Director App
# Run these curl commands to test your API endpoints

BASE_URL="http://emp.kfinone.com/mobile/api"

echo "🚀 Testing Managing Director App API"
echo "=================================="

# Test 1: Database Connection
echo -e "\n1. Testing Database Connection..."
curl -X GET "$BASE_URL/test_connection.php" \
  -H "Content-Type: application/json" \
  -w "\nHTTP Status: %{http_code}\n"

# Test 2: Login with Admin
echo -e "\n2. Testing Login with Admin..."
curl -X POST "$BASE_URL/login.php" \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"admin123"}' \
  -w "\nHTTP Status: %{http_code}\n"

# Test 3: Login with Director
echo -e "\n3. Testing Login with Director..."
curl -X POST "$BASE_URL/login.php" \
  -H "Content-Type: application/json" \
  -d '{"username":"director","password":"director123"}' \
  -w "\nHTTP Status: %{http_code}\n"

# Test 4: Login with Manager
echo -e "\n4. Testing Login with Manager..."
curl -X POST "$BASE_URL/login.php" \
  -H "Content-Type: application/json" \
  -d '{"username":"manager","password":"manager123"}' \
  -w "\nHTTP Status: %{http_code}\n"

# Test 5: Invalid Login
echo -e "\n5. Testing Invalid Login..."
curl -X POST "$BASE_URL/login.php" \
  -H "Content-Type: application/json" \
  -d '{"username":"invalid","password":"wrongpassword"}' \
  -w "\nHTTP Status: %{http_code}\n"

# Test 6: Missing Fields
echo -e "\n6. Testing Missing Fields..."
curl -X POST "$BASE_URL/login.php" \
  -H "Content-Type: application/json" \
  -d '{"username":"admin"}' \
  -w "\nHTTP Status: %{http_code}\n"

echo -e "\n✅ All tests completed!" 