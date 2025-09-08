# NIMR Intranet Administrator Manual

Production deployment and administration guide for system administrators.

## 🎯 Pre-Deployment Checklist

### Server Requirements
- [x] PHP 8.1+ with extensions (mysql, xml, curl, zip, gd, mbstring)
- [x] MySQL 8.0+ or MariaDB 10.3+
- [x] Web server (Apache 2.4+ or Nginx 1.18+)
- [x] SSL certificate
- [x] Minimum 4GB RAM, 20GB storage
- [x] Backup solution configured

### Security Requirements
- [x] Firewall configured (ports 80, 443, 22)
- [x] SSH key-based authentication
- [x] Regular security updates enabled
- [x] Database access restricted
- [x] File upload limits configured

## 🚀 Deployment Steps

### 1. Server Setup

```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.1
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-xml php8.1-curl php8.1-zip php8.1-gd php8.1-mbstring php8.1-bcmath

# Install MySQL
sudo apt install mysql-server

# Install Nginx
sudo apt install nginx

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs
```

### 2. Database Setup

```bash
# Secure MySQL installation
sudo mysql_secure_installation

# Create database and user
sudo mysql -u root -p
```

```sql
CREATE DATABASE nimr_intranet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'nimr_user'@'localhost' IDENTIFIED BY 'secure_password_here';
GRANT ALL PRIVILEGES ON nimr_intranet.* TO 'nimr_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Application Deployment

```bash
# Create application directory
sudo mkdir -p /var/www/nimr-intranet
cd /var/www/nimr-intranet

# Clone repository (replace with actual repo)
sudo git clone <repository-url> .

# Set ownership
sudo chown -R www-data:www-data /var/www/nimr-intranet

# Install PHP dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev

# Install Node dependencies and build assets
sudo -u www-data npm install
sudo -u www-data npm run build

# Set permissions
sudo chmod -R 755 /var/www/nimr-intranet
sudo chmod -R 775 /var/www/nimr-intranet/storage
sudo chmod -R 775 /var/www/nimr-intranet/bootstrap/cache
```

### 4. Environment Configuration

```bash
# Copy environment file
sudo -u www-data cp .env.example .env

# Generate application key
sudo -u www-data php artisan key:generate

# Edit environment file
sudo nano .env
```

**Required .env configurations:**
```env
APP_NAME="NIMR Intranet"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://intranet.nimr.or.tz

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nimr_intranet
DB_USERNAME=nimr_user
DB_PASSWORD=secure_password_here

MAIL_MAILER=smtp
MAIL_HOST=mail.nimr.or.tz
MAIL_PORT=587
MAIL_USERNAME=noreply@nimr.or.tz
MAIL_PASSWORD=mail_password_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@nimr.or.tz
MAIL_FROM_NAME="NIMR Intranet"

SESSION_DRIVER=database
QUEUE_CONNECTION=database

# Security
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=strict
```

### 5. Database Migration

```bash
# Run migrations
sudo -u www-data php artisan migrate

# Seed organizational structure
sudo -u www-data php artisan db:seed --class=OrganizationSeeder

# Create storage link
sudo -u www-data php artisan storage:link

# Optimize for production
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

### 6. Web Server Configuration

#### Nginx Configuration
```bash
sudo nano /etc/nginx/sites-available/nimr-intranet
```

```nginx
server {
    listen 80;
    server_name intranet.nimr.or.tz;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name intranet.nimr.or.tz;
    root /var/www/nimr-intranet/public;

    index index.php index.html index.htm;

    # SSL Configuration
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # File Upload Limits
    client_max_body_size 50M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
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
sudo ln -s /etc/nginx/sites-available/nimr-intranet /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 7. PHP-FPM Configuration

```bash
sudo nano /etc/php/8.1/fpm/php.ini
```

**Key settings:**
```ini
upload_max_filesize = 50M
post_max_size = 50M
max_execution_time = 300
memory_limit = 256M
```

```bash
sudo systemctl restart php8.1-fpm
```

## 🔧 Post-Deployment Configuration

### 1. Create Admin User

```bash
sudo -u www-data php artisan tinker
```

```php
use App\Models\User;
use App\Models\Centre;

$user = User::create([
    'name' => 'System Administrator',
    'email' => 'admin@nimr.or.tz',
    'password' => bcrypt('secure_admin_password'),
    'role' => 'super_admin',
    'email_verified_at' => now(),
    'headquarters_id' => 1
]);
```

### 2. Set Up Cron Jobs

```bash
sudo crontab -e -u www-data
```

Add:
```bash
* * * * * cd /var/www/nimr-intranet && php artisan schedule:run >> /dev/null 2>&1
```

### 3. Configure Log Rotation

```bash
sudo nano /etc/logrotate.d/nimr-intranet
```

```
/var/www/nimr-intranet/storage/logs/*.log {
    daily
    missingok
    rotate 52
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
}
```

## 🔒 Security Hardening

### 1. File Permissions
```bash
sudo chown -R www-data:www-data /var/www/nimr-intranet
sudo find /var/www/nimr-intranet -type f -exec chmod 644 {} \;
sudo find /var/www/nimr-intranet -type d -exec chmod 755 {} \;
sudo chmod -R 775 /var/www/nimr-intranet/storage
sudo chmod -R 775 /var/www/nimr-intranet/bootstrap/cache
```

### 2. Firewall Configuration
```bash
sudo ufw allow 22/tcp
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp
sudo ufw enable
```

### 3. Disable Unused Services
```bash
sudo systemctl disable apache2 # if installed
sudo systemctl disable snapd # if not needed
```

## 📊 Monitoring & Maintenance

### 1. Log Monitoring
```bash
# Application logs
tail -f /var/www/nimr-intranet/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# System logs
journalctl -u nginx -f
journalctl -u php8.1-fpm -f
```

### 2. Performance Monitoring
```bash
# Check disk usage
df -h

# Check memory usage
free -h

# Check system load
htop

# Check database performance
sudo mysql -u root -p -e "SHOW PROCESSLIST;"
```

### 3. Regular Maintenance Tasks

**Daily:**
- Check system logs for errors
- Monitor disk space usage
- Verify backup completion

**Weekly:**
- Update system packages
- Review user access logs
- Check SSL certificate expiration

**Monthly:**
- Database optimization
- Security audit
- Performance review

## 🔄 Backup & Recovery

### 1. Database Backup
```bash
# Create backup script
sudo nano /usr/local/bin/backup-nimr-db.sh
```

```bash
#!/bin/bash
BACKUP_DIR="/var/backups/nimr-intranet"
DATE=$(date +%Y%m%d_%H%M%S)
mkdir -p $BACKUP_DIR

mysqldump -u nimr_user -p'password' nimr_intranet > $BACKUP_DIR/database_$DATE.sql
gzip $BACKUP_DIR/database_$DATE.sql

# Keep only last 30 days
find $BACKUP_DIR -name "database_*.sql.gz" -mtime +30 -delete
```

```bash
sudo chmod +x /usr/local/bin/backup-nimr-db.sh

# Add to crontab
sudo crontab -e
```

Add: `0 2 * * * /usr/local/bin/backup-nimr-db.sh`

### 2. File Backup
```bash
# Backup uploaded files and configurations
sudo rsync -av /var/www/nimr-intranet/storage/app/public/ /var/backups/nimr-intranet/files/
sudo cp /var/www/nimr-intranet/.env /var/backups/nimr-intranet/config/
```

## 🚨 Troubleshooting

### Common Issues

**Application not loading:**
```bash
sudo tail -f /var/log/nginx/error.log
sudo systemctl status php8.1-fpm
sudo systemctl status nginx
```

**Database connection issues:**
```bash
sudo mysql -u nimr_user -p
php artisan tinker
# Test: DB::connection()->getPdo();
```

**Permission issues:**
```bash
sudo chown -R www-data:www-data /var/www/nimr-intranet/storage
sudo chmod -R 775 /var/www/nimr-intranet/storage
```

**SSL certificate issues:**
```bash
openssl x509 -in /path/to/certificate.crt -text -noout
sudo nginx -t
```

### Emergency Procedures

**Rollback deployment:**
```bash
cd /var/www/nimr-intranet
git log --oneline -10
git checkout <previous-commit-hash>
sudo -u www-data composer install --no-dev
sudo -u www-data npm run build
sudo -u www-data php artisan config:cache
```

**Database recovery:**
```bash
gunzip < /var/backups/nimr-intranet/database_YYYYMMDD_HHMMSS.sql.gz | mysql -u nimr_user -p nimr_intranet
```

## ✅ Go-Live Checklist

- [ ] Server requirements met
- [ ] SSL certificate installed and valid
- [ ] Database created and configured
- [ ] Application deployed and configured
- [ ] Admin user created
- [ ] Email configuration tested
- [ ] File uploads working
- [ ] Backups configured and tested
- [ ] Monitoring set up
- [ ] Security hardening applied
- [ ] Performance optimizations applied
- [ ] Documentation updated
- [ ] User training completed

---

**For support:** Contact the development team or NIMR IT Department.

**Version**: 2.0.0  
**Last Updated**: September 8, 2025
