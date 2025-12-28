# Test API Endpoints
Write-Host "Testing Campus Network API..." -ForegroundColor Cyan
Write-Host ""

# Test server is running
Write-Host "1. Testing server..." -ForegroundColor Yellow
try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000" -UseBasicParsing -TimeoutSec 5
    Write-Host "✓ Server is running" -ForegroundColor Green
} catch {
    Write-Host "✗ Server failed: $_" -ForegroundColor Red
    exit 1
}

Write-Host ""

# Test Registration
Write-Host "2. Testing Registration..." -ForegroundColor Yellow
$body = @{
    nom = "New User"
    email = "newuser@test.local"
    password = "NewPass1234!"
    password_confirmation = "NewPass1234!"
} | ConvertTo-Json

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/v1/register" `
        -Method POST `
        -ContentType "application/json" `
        -Body $body `
        -UseBasicParsing

    $json = $response.Content | ConvertFrom-Json
    if ($json.token) {
        Write-Host "✓ Registration works" -ForegroundColor Green
        Write-Host "User: $($json.user.email)" -ForegroundColor Gray
    } else {
        Write-Host "✗ Registration failed - no token" -ForegroundColor Red
    }
} catch {
    Write-Host "✗ Registration error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""

# Test Login
Write-Host "3. Testing Login..." -ForegroundColor Yellow
$body = @{
    email = "test@campus.local"
    password = "Test1234!"
} | ConvertTo-Json

try {
    $response = Invoke-WebRequest -Uri "http://localhost:8000/api/v1/login" `
        -Method POST `
        -ContentType "application/json" `
        -Body $body `
        -UseBasicParsing

    $json = $response.Content | ConvertFrom-Json
    $token = $json.token
    
    if ($token) {
        Write-Host "✓ Login works" -ForegroundColor Green
        Write-Host "Token (first 20 chars): $($token.Substring(0, 20))..." -ForegroundColor Gray
        
        # Test authenticated request
        Write-Host ""
        Write-Host "4. Testing authenticated request..." -ForegroundColor Yellow
        
        $headers = @{
            "Authorization" = "Bearer $token"
        }
        
        $pubResponse = Invoke-WebRequest -Uri "http://localhost:8000/api/v1/publications" `
            -Headers $headers `
            -UseBasicParsing
        
        $pubJson = $pubResponse.Content | ConvertFrom-Json
        Write-Host "✓ Authenticated request works" -ForegroundColor Green
        Write-Host "Publications returned: $($pubJson.data.Count)" -ForegroundColor Gray
    } else {
        Write-Host "✗ Login failed - no token" -ForegroundColor Red
    }
} catch {
    Write-Host "✗ Login error: $($_.Exception.Message)" -ForegroundColor Red
}

Write-Host ""
Write-Host "✓ All tests completed!" -ForegroundColor Cyan
