# Deployment Guide

This guide covers deploying School CMS to production environments.

## 📋 Pre-Deployment Checklist

### 1. Environment Configuration
- [ ] Copy `.env.example` to `.env`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate new `APP_KEY` with `php artisan key:generate`
- [ ] Set correct `APP_URL` (https://your-domain.com)
- [ ] Configure database credentials
- [ ] Configure mail settings
- [ ] Set secure session/cache drivers

### 2. Security
- [ ] Change default admin credentials
- [ ] Enable HTTPS (SSL certificate)
- [ ] Configure firewall rules
- [ ] Set proper file permissions
- [ ] Review `.gitignore` to prevent leaking secrets

### 3. Performance
- [ ] Run `composer install --no-dev --optimize-autoloader`
- [ ] Run `npm run build`
- [ ] Cache configuration: `php artisan config:cache`
- [ ] Cache routes: `php artisan route:cache`
- [ ] Cache views: `php artisan view:cache`
- [ ] Enable OPcache in PHP

## 🚀 Deployment Methods

### Method 1: Shared Hosting (cPanel)

#### Step 1: Upload Files
```bash
# On your local machine
zip -r school-cms.zip . -x "*.git*" "node_modules/*" "vendor/*" ".env"

# Upload via cPanel File Manager or FTP
# Extract to public_html or subdirectory
```

#### Step 2: Install Dependencies
```bash
# SSH into your server
cd /path/to/school-cms
composer install --no-dev --optimize-autoloader
```

#### Step 3: Configure Environment
```bash
cp .env.example .env
nano .env  # Edit with your settings

# Generate application key
php artisan key:generate
```

#### Step 4: Setup Database
```bash
# Create database via cPanel MySQL
# Update .env with credentials

php artisan migrate --force
php artisan db:seed --class=DefaultThemeSeeder
```

#### Step 5: Set Permissions
```bash
chmod -R 755 storage bootstrap/cache
chmod -R 644 .env
```

#### Step 6: Configure Web Root
In cPanel, set document root to `/path/to/school-cms/public`

### Method 2: VPS (Ubuntu/Nginx)

#### Step 1: Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install required packages
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-cli \
    php8.2-mbstring php8.2-xml php8.2-bcmath php8.2-mysql \
    php8.2-zip php8.2-curl php8.2-gd composer git
```

#### Step 2: Clone Repository
```bash
cd /var/www
sudo git clone https://github.com/wondrv/sekolah.git
cd sekolah
```

#### Step 3: Install Dependencies
```bash
composer install --no-dev --optimize-autoloader
npm install
npm run build
```

#### Step 4: Configure Environment
```bash
cp .env.example .env
nano .env

# Update these values:
# APP_ENV=production
# APP_DEBUG=false
# APP_URL=https://your-domain.com
# DB_DATABASE, DB_USERNAME, DB_PASSWORD

php artisan key:generate
```

#### Step 5: Database Setup
```bash
# Create MySQL database
sudo mysql
CREATE DATABASE school_cms;
CREATE USER 'school_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL ON school_cms.* TO 'school_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;

# Run migrations
php artisan migrate --force
php artisan db:seed --class=DefaultThemeSeeder
```

#### Step 6: Set Permissions
```bash
sudo chown -R www-data:www-data /var/www/sekolah
sudo chmod -R 755 /var/www/sekolah
sudo chmod -R 775 /var/www/sekolah/storage
sudo chmod -R 775 /var/www/sekolah/bootstrap/cache
```

#### Step 7: Configure Nginx
```nginx
# /etc/nginx/sites-available/school-cms
server {
    listen 80;
    server_name your-domain.com www.your-domain.com;
    root /var/www/sekolah/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";
    add_header X-XSS-Protection "1; mode=block";

    index index.php index.html;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/school-cms /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

#### Step 8: SSL Certificate (Let's Encrypt)
```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d your-domain.com -d www.your-domain.com
```

#### Step 9: Optimize
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Method 3: Docker (Optional)

#### Dockerfile
```dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . /var/www

# Install dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

CMD ["php-fpm"]
```

#### docker-compose.yml
```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: school-cms
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
    networks:
      - school-network

  nginx:
    image: nginx:alpine
    container_name: school-nginx
    restart: unless-stopped
    ports:
      - "80:80"
    volumes:
      - ./:/var/www
      - ./docker/nginx:/etc/nginx/conf.d
    networks:
      - school-network

  mysql:
    image: mysql:8.0
    container_name: school-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: school_cms
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_USER: school_user
      MYSQL_PASSWORD: user_password
    volumes:
      - mysql-data:/var/lib/mysql
    networks:
      - school-network

networks:
  school-network:
    driver: bridge

volumes:
  mysql-data:
```

## 🔧 Post-Deployment Tasks

### 1. First Login
```
URL: https://your-domain.com/admin/dashboard
Email: admin@school.local
Password: password

⚠️ CHANGE THESE IMMEDIATELY!
```

### 2. Configure Settings
- Admin → Settings → Site Information
- Admin → Settings → Theme
- Admin → Templates → Activate a template

### 3. Setup Backups
```bash
# Create backup script
#!/bin/bash
BACKUP_DIR="/backups/school-cms"
DATE=$(date +%Y%m%d_%H%M%S)

# Backup database
mysqldump -u school_user -p school_cms > "$BACKUP_DIR/db_$DATE.sql"

# Backup files
tar -czf "$BACKUP_DIR/files_$DATE.tar.gz" /var/www/sekolah/storage/app

# Keep only last 7 days
find $BACKUP_DIR -type f -mtime +7 -delete
```

### 4. Setup Cron Jobs
```bash
# Edit crontab
crontab -e

# Add Laravel scheduler (required)
* * * * * cd /var/www/sekolah && php artisan schedule:run >> /dev/null 2>&1

# Add backup job (daily at 2 AM)
0 2 * * * /path/to/backup-script.sh
```

### 5. Monitoring
```bash
# Check logs
tail -f /var/www/sekolah/storage/logs/laravel.log

# Monitor server resources
htop

# Check Nginx logs
tail -f /var/log/nginx/error.log
```

## 🔄 Updating Production

### Safe Update Process
```bash
# 1. Backup first!
php artisan down  # Enable maintenance mode

# 2. Pull latest code
git pull origin main

# 3. Update dependencies
composer install --no-dev --optimize-autoloader
npm install && npm run build

# 4. Run migrations
php artisan migrate --force

# 5. Clear caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Bring site back up
php artisan up
```

## 🐛 Troubleshooting

### Issue: 500 Internal Server Error
```bash
# Check logs
tail -n 50 storage/logs/laravel.log

# Check file permissions
ls -la storage bootstrap/cache

# Clear all caches
php artisan optimize:clear
```

### Issue: Route Not Found
```bash
# Clear route cache
php artisan route:clear
php artisan route:cache
```

### Issue: CSS Not Loading
```bash
# Rebuild assets
npm run build

# Check public/assets/css exists
ls -la public/assets/css
```

### Issue: Database Connection Error
```bash
# Verify .env database settings
cat .env | grep DB_

# Test MySQL connection
mysql -u school_user -p school_cms
```

## 📊 Performance Optimization

### 1. Enable OPcache
```ini
# php.ini
opcache.enable=1
opcache.memory_consumption=256
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0  # Production only
```

### 2. Use Redis for Cache
```bash
# Install Redis
sudo apt install redis-server

# Update .env
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 3. Optimize Database
```sql
-- Add indexes for frequently queried columns
ALTER TABLE posts ADD INDEX idx_published (published_at);
ALTER TABLE user_templates ADD INDEX idx_active (is_active);

-- Optimize tables
OPTIMIZE TABLE posts, pages, events, galleries;
```

### 4. CDN for Assets
Consider using CloudFlare or similar CDN for:
- Static assets (CSS, JS)
- Images in `/storage/app/public`
- Template assets in `/public/template-assets`

## 🔐 Security Hardening

### 1. Disable Directory Listing
```nginx
# Nginx
autoindex off;
```

```apache
# Apache (.htaccess already includes)
Options -Indexes
```

### 2. Hide PHP Version
```ini
# php.ini
expose_php = Off
```

### 3. Rate Limiting
Already implemented in routes. Adjust in `app/Http/Kernel.php`:
```php
'api' => [
    'throttle:60,1',  // 60 requests per minute
],
```

### 4. Fail2Ban (Optional)
```bash
# Install Fail2Ban
sudo apt install fail2ban

# Configure for Nginx
sudo nano /etc/fail2ban/jail.local
```

## 📞 Support

If you encounter issues during deployment:
1. Check the troubleshooting section above
2. Review server logs
3. Search GitHub Issues
4. Create a new issue with deployment logs

---

**Good luck with your deployment! 🚀**

For questions, open an issue or contact support.
