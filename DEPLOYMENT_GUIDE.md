# 🚀 Campus Network - Guide de Déploiement Professionnel

## 📋 Checklist Pré-Déploiement

### ✅ Code Quality
- [ ] All tests passing: `php artisan test`
- [ ] Linting passed: `npm run lint`
- [ ] No console errors
- [ ] Environment variables configured
- [ ] Database migrations ready

### ✅ Security
- [ ] `.env` file not in git
- [ ] `APP_DEBUG=false` in production
- [ ] HTTPS enabled
- [ ] CORS configured
- [ ] Rate limiting enabled
- [ ] SQL Injection prevention checked
- [ ] XSS Protection enabled

### ✅ Performance
- [ ] Assets compiled: `npm run build`
- [ ] Database optimized (indexes created)
- [ ] Cache configured
- [ ] Session driver set
- [ ] Log levels configured

### ✅ Database
- [ ] All migrations run: `php artisan migrate`
- [ ] Seeds executed: `php artisan db:seed`
- [ ] Backup created

### ✅ Rôles & Permissions
- [ ] All 6 roles created
- [ ] All 17 permissions created
- [ ] Super Admin account created
- [ ] Test accounts created for each role

---

## 🌐 Déploiement sur Serveur

### Étape 1: Préparation Serveur

```bash
# Se connecter au serveur
ssh user@your-server.com

# Naviguer au répertoire
cd /var/www/campus-network

# Cloner le repo
git clone https://github.com/yourusername/campus-network.git .
```

### Étape 2: Installation des Dépendances

```bash
# Composer
composer install --optimize-autoloader --no-dev

# NPM
npm install --production
npm run build
```

### Étape 3: Configuration Environnement

```bash
# Copier fichier .env
cp .env.example .env

# Générer clé application
php artisan key:generate

# Éditer .env pour production
nano .env
```

**Paramètres critiques à vérifier:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=campus_network
DB_USERNAME=db_user
DB_PASSWORD=strong_password

CACHE_DRIVER=redis
SESSION_DRIVER=cookie
QUEUE_CONNECTION=sync

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

### Étape 4: Base de Données

```bash
# Créer base de données
mysql -u root -p -e "CREATE DATABASE campus_network;"

# Migrations
php artisan migrate --force

# Seeds (rôles et permissions)
php artisan db:seed --class=RolePermissionSeeder

# Créer Super Admin
php artisan tinker
> use App\Models\Utilisateur, App\Models\Role;
> $role = Role::where('slug', 'super_admin')->first();
> Utilisateur::create(['email'=>'admin@campus.com', 'nom'=>'Admin', 'mot_de_passe'=>'Strong123!', 'email_verified_at'=>now(), 'role_id'=>$role->id]);
> exit;
```

### Étape 5: Permissions Fichiers

```bash
# Permissions répertoires
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data .
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### Étape 6: Configuration Web Server (Nginx)

**Fichier: `/etc/nginx/sites-available/campus-network`**

```nginx
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;

    # Redirection HTTPS
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name your-domain.com www.your-domain.com;

    # SSL Configuration
    ssl_certificate /etc/ssl/certs/your-cert.crt;
    ssl_certificate_key /etc/ssl/private/your-key.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Root directory
    root /var/www/campus-network/public;
    index index.php;

    # Security headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;

    # Rate limiting
    limit_req_zone $binary_remote_addr zone=general:10m rate=10r/s;
    limit_req zone=general burst=20 nodelay;

    # Logs
    access_log /var/log/nginx/campus-network-access.log;
    error_log /var/log/nginx/campus-network-error.log;

    # Static files caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    # Block access to sensitive files
    location ~ /\.env {
        deny all;
    }

    location ~ /\.git {
        deny all;
    }

    # PHP configuration
    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Laravel routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
}
```

### Étape 7: Configuration PHP-FPM

**Fichier: `/etc/php/8.2/fpm/php.ini`**

```ini
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
upload_max_filesize = 100M
post_max_size = 100M

; Performance
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
```

### Étape 8: Cache & Sessions

```bash
# Générer cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views (optionnel)
php artisan view:cache

# Optimiser autoloader
composer dump-autoload --optimize
```

### Étape 9: Monitoring

```bash
# Vérifier status
php artisan health

# Voir logs
tail -f storage/logs/laravel.log

# Check processes
ps aux | grep php
```

---

## 🐳 Déploiement avec Docker

### Dockerfile

```dockerfile
FROM php:8.2-fpm

# Dépendances
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd zip pdo pdo_mysql

# Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /app

# Copy code
COPY . .

# Install dependencies
RUN composer install --optimize-autoloader --no-dev

# Permissions
RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 9000

CMD ["php-fpm"]
```

### Docker Compose

```yaml
version: '3.8'

services:
  app:
    build: .
    container_name: campus-network-app
    working_dir: /app
    volumes:
      - ./:/app
      - ./public:/app/public
    depends_on:
      - db
      - redis
    networks:
      - campus

  nginx:
    image: nginx:alpine
    container_name: campus-network-nginx
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/app
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
      - ./docker/nginx/ssl:/etc/nginx/ssl
    depends_on:
      - app
    networks:
      - campus

  db:
    image: mysql:8.0
    container_name: campus-network-db
    environment:
      MYSQL_DATABASE: campus_network
      MYSQL_ROOT_PASSWORD: root
      MYSQL_PASSWORD: password
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - campus

  redis:
    image: redis:7-alpine
    container_name: campus-network-redis
    networks:
      - campus

volumes:
  dbdata:

networks:
  campus:
    driver: bridge
```

---

## 🔄 Mise à Jour Production

```bash
# Backup database
mysqldump -u user -p database > backup-$(date +%Y%m%d).sql

# Pull latest code
git pull origin main

# Install dependencies
composer install --optimize-autoloader --no-dev

# Migrations
php artisan migrate --force

# Cache refresh
php artisan cache:clear
php artisan config:clear
php artisan config:cache
php artisan route:cache

# Compile assets
npm run build

# Restart services
sudo service php8.2-fpm restart
sudo service nginx restart
```

---

## 🚨 Troubleshooting

### Site vide/erreur 500
```bash
# Vérifier logs
tail -f storage/logs/laravel.log
tail -f /var/log/nginx/error.log

# Permissions
chmod -R 755 storage
chown -R www-data:www-data storage
```

### Pas de rôles/permissions
```bash
# Réexécuter seeds
php artisan db:seed --class=RolePermissionSeeder

# Vérifier DB
php artisan tinker
> Role::count()
> Permission::count()
```

### Problèmes connection DB
```bash
# Tester connection
php artisan tinker
> DB::connection()->getPdo()

# Vérifier credentials .env
php artisan env
```

### Assets non chargés
```bash
# Recompile
npm run build

# Clear cache
php artisan cache:clear

# Vérifier permissions public/
chmod -R 755 public/
```

---

## 📊 Monitoring en Production

### Health Check Endpoint
```php
// routes/web.php
Route::get('/health', function () {
    return response()->json([
        'status' => 'ok',
        'time' => now(),
        'database' => DB::connection()->getDatabaseName(),
    ]);
});
```

### Logs Monitoring
```bash
# Tail logs en temps réel
tail -f storage/logs/laravel.log | grep -i error

# Voir tous les logs
ls -la storage/logs/
```

### Database Monitoring
```bash
# Exécuter query de monitoring
php artisan tinker

> DB::select('SHOW PROCESSLIST');
> DB::select('SHOW TABLE STATUS');
```

---

## 🎯 Checklist Post-Déploiement

- [ ] Site accessible via domaine
- [ ] HTTPS working
- [ ] Login fonctionnel
- [ ] Tous les rôles testés
- [ ] Dashboard s'affiche correct
- [ ] API endpoints répondent
- [ ] Emails envoyés correctement
- [ ] Assets chargés (CSS/JS)
- [ ] Database sauvegardée
- [ ] Logs monitored
- [ ] Rate limiting actif
- [ ] Performance acceptable

---

## 📞 Support Déploiement

En cas de problème:
1. Consulter les logs
2. Vérifier permissions fichiers
3. Vérifier credentials DB/Email
4. Réexécuter migrations/seeds
5. Vider les caches

---

**Campus Network Deployment Guide v1.0** ✅
