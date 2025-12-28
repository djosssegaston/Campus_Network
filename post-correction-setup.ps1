# Campus Network - Post-Correction Setup (Windows)

Write-Host "🔄 Campus Network - Post-Correction Setup" -ForegroundColor Cyan
Write-Host "==========================================" -ForegroundColor Cyan
Write-Host ""

# Clear caches
Write-Host "📦 Clearing caches..." -ForegroundColor Yellow
php artisan cache:clear
php artisan config:clear  
php artisan route:clear

Write-Host "✅ Caches cleared" -ForegroundColor Green
Write-Host ""

# Refresh migrations
Write-Host "🗄️  Running migrations..." -ForegroundColor Yellow
php artisan migrate:refresh --seed

Write-Host "✅ Migrations completed" -ForegroundColor Green
Write-Host ""

# List routes
Write-Host "📋 API Routes:" -ForegroundColor Yellow
php artisan route:list --path=api

Write-Host ""
Write-Host "✅ Setup complete!" -ForegroundColor Green
Write-Host ""
Write-Host "📚 Next steps:" -ForegroundColor Cyan
Write-Host "  1. Read GUIDE_TESTING.md for comprehensive tests"
Write-Host "  2. Run: php artisan tinker"
Write-Host "  3. Test API endpoints with Postman or curl"
Write-Host ""
Write-Host "🧪 Quick test:" -ForegroundColor Yellow
Write-Host "  GET http://localhost:8000/api/v1/publications"
Write-Host "  POST http://localhost:8000/api/v1/publications (with auth)"
Write-Host ""
