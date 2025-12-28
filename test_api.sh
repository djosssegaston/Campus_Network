#!/bin/bash

# Test API Endpoints
echo "Testing Campus Network API..."
echo ""

# Get the current working directory
cd "$(dirname "$0")"

# Test server is running
echo "1. Testing server..."
curl -s http://localhost:8000 > /dev/null && echo "✓ Server is running" || echo "✗ Server failed"
echo ""

# Test Registration
echo "2. Testing Registration..."
RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/register \
  -H "Content-Type: application/json" \
  -d '{
    "nom": "New User",
    "email": "newuser@test.local",
    "password": "NewPass1234!",
    "password_confirmation": "NewPass1234!"
  }')

echo "$RESPONSE" | grep -q "token" && echo "✓ Registration works" || echo "✗ Registration failed"
echo "Response: $RESPONSE" | head -c 200
echo ""

# Test Login
echo "3. Testing Login..."
LOGIN_RESPONSE=$(curl -s -X POST http://localhost:8000/api/v1/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@campus.local",
    "password": "Test1234!"
  }')

TOKEN=$(echo "$LOGIN_RESPONSE" | grep -o '"token":"[^"]*' | cut -d'"' -f4)
echo "Token: $TOKEN"

if [ -n "$TOKEN" ]; then
  echo "✓ Login works"
  
  # Test authenticated request
  echo ""
  echo "4. Testing authenticated request..."
  curl -s -X GET http://localhost:8000/api/v1/publications \
    -H "Authorization: Bearer $TOKEN" | grep -q "data" && echo "✓ Authenticated request works" || echo "✗ Authenticated request failed"
else
  echo "✗ Login failed"
fi

echo ""
echo "Done!"
