# 🚀 Campus Network - Quick Start Guide

## In 5 Minutes

```bash
# 1. Setup Database
cd c:\Users\HP\Campus_Network
copy .env.example .env
# Edit .env - add your database credentials

# 2. Create Database Tables
php artisan key:generate
php artisan migrate

# 3. Create Admin User (Optional)
php artisan tinker
User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'role_id' => 1]);
exit

# 4. Start Server
php artisan serve
# Opens: http://localhost:8000
```

## ✅ What's Already Done

| Task | Status |
|------|--------|
| React → Blade conversion | ✅ Complete |
| All controllers updated | ✅ Complete |
| 20 Blade templates | ✅ Complete |
| 30+ API endpoints | ✅ Complete |
| Database design | ✅ Complete |
| npm install | ✅ Complete |
| npm build | ✅ Complete |
| Assets compiled | ✅ Complete |

## 🔗 Important Files

| File | Purpose |
|------|---------|
| `routes/web.php` | Web routes |
| `routes/api.php` | API routes (30+ endpoints) |
| `app/Http/Controllers/Auth/` | Auth controllers (Blade views) |
| `app/Http/Controllers/Api/` | API controllers (JSON responses) |
| `resources/views/` | Blade templates (20 files) |
| `bootstrap/app.php` | Main configuration |

## 🧪 Test These URLs

```
Public:
✓ http://localhost:8000              (Home page)
✓ http://localhost:8000/login        (Login - Blade form)
✓ http://localhost:8000/register     (Register - Blade form)

After Login:
✓ http://localhost:8000/dashboard    (Dashboard)
✓ http://localhost:8000/feed         (Feed)
✓ http://localhost:8000/profile      (Profile)

API:
✓ http://localhost:8000/api/v1/publications  (List publications)
✓ http://localhost:8000/api/v1/groupes       (List groups)
```

## 🔐 Admin Setup

```bash
# Make user admin in database
php artisan tinker
$user = User::find(1);
$user->role_id = 1;  // Set to admin role ID
$user->save();

# Access admin endpoints:
GET http://localhost:8000/api/v1/admin/stats
```

## 📦 Project Contains

- **9 Auth Controllers** - All returning Blade views (no Inertia)
- **6 API Controllers** - Full CRUD implementations
- **20 Blade Templates** - Complete UI
- **18 Database Migrations** - All tables
- **9 Eloquent Models** - With relationships
- **30+ API Routes** - RESTful endpoints
- **Tailwind CSS** - 54KB compiled
- **Alpine.js** - 45KB compiled
- **Full Documentation** - 3 guide files

## ⚡ Quick Commands

```bash
# Start development
php artisan serve

# Run migrations
php artisan migrate

# Reset database
php artisan migrate:fresh

# Interactive shell
php artisan tinker

# Check routes
php artisan route:list

# Clear cache
php artisan config:clear
php artisan cache:clear

# Rebuild assets
npm run build
```

## 🎯 Next Steps

1. ✅ Copy `.env.example` to `.env`
2. ✅ Configure database in `.env`
3. ✅ Run `php artisan migrate`
4. ✅ Run `php artisan serve`
5. ✅ Visit http://localhost:8000

## 📱 API Examples

### Get Publications
```bash
curl http://localhost:8000/api/v1/publications
```

### Create Publication (with token)
```bash
curl -X POST http://localhost:8000/api/v1/publications \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"titre": "Test", "contenu": "Hello"}'
```

### Admin Stats (admin only)
```bash
curl http://localhost:8000/api/v1/admin/stats \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## ❓ Troubleshooting

| Issue | Solution |
|-------|----------|
| "View not found" | Check view file exists in `resources/views/` |
| 403 Unauthorized | Check user has admin role assigned |
| Assets not loading | Run `npm run build` |
| Database error | Check `.env` credentials |
| Blade syntax errors | Check `@` directives syntax |

## 📖 Documentation

- **PROJECT_STATUS.md** - Detailed status report
- **IMPLEMENTATION_GUIDE.md** - Complete setup guide
- **FINAL_SUMMARY.md** - Full project overview

---

**Status:** ✅ Ready to Run  
**Framework:** Laravel 11 + Blade PHP  
**Last Updated:** December 2024

🎉 Everything is ready. Just run `php artisan serve`!
