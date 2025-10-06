# MySQL Migration Guide

**Purpose:** Migrate NIMR Intranet from SQLite (development) to MySQL/MariaDB (production)

**Estimated Time:** 30-60 minutes  
**Risk Level:** Medium (requires database backup and verification)

---

## Prerequisites

-   [ ] MySQL 8.0+ or MariaDB 10.4+ installed
-   [ ] Database user with CREATE, INSERT, UPDATE, DELETE, INDEX privileges
-   [ ] PHP MySQL extension (`php-mysql` or `php-mysqli`) installed
-   [ ] Backup of current SQLite database
-   [ ] At least 1GB free disk space

---

## Step 1: Backup Current SQLite Database

```bash
# Create backup directory
mkdir -p backups/migration-$(date +%Y%m%d)

# Copy SQLite database
cp database/database.sqlite backups/migration-$(date +%Y%m%d)/database.sqlite.backup

# Export to SQL (optional, for reference)
sqlite3 database/database.sqlite .dump > backups/migration-$(date +%Y%m%d)/sqlite_dump.sql

# Verify backup size
ls -lh backups/migration-$(date +%Y%m%d)/
```

---

## Step 2: Create MySQL Database

```sql
-- Connect to MySQL as root or admin user
mysql -u root -p

-- Create database
CREATE DATABASE nimr_intranet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Create dedicated user
CREATE USER 'intranet_user'@'localhost' IDENTIFIED BY 'SECURE_PASSWORD_HERE';

-- Grant privileges
GRANT ALL PRIVILEGES ON nimr_intranet.* TO 'intranet_user'@'localhost';
FLUSH PRIVILEGES;

-- Verify
SHOW DATABASES;
SELECT User, Host FROM mysql.user WHERE User = 'intranet_user';

-- Exit
EXIT;
```

---

## Step 3: Update Environment Configuration

```bash
# Backup current .env
cp .env .env.sqlite.backup

# Update .env for MySQL
```

Update these lines in `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nimr_intranet
DB_USERNAME=intranet_user
DB_PASSWORD=SECURE_PASSWORD_HERE
```

---

## Step 4: Test Database Connection

```bash
# Test connection
php artisan db:show

# Should display:
# MySQL 8.x.x
# Database: nimr_intranet
# Host: 127.0.0.1
```

If connection fails:

-   Verify MySQL is running: `sudo systemctl status mysql`
-   Check credentials match
-   Verify user has proper permissions

---

## Step 5: Run Migrations

```bash
# Clear cached config
php artisan config:clear

# Run migrations (creates all tables)
php artisan migrate

# Verify tables were created
php artisan db:table --database=mysql
```

**Expected Output:** 40+ tables created successfully

---

## Step 6: Export Data from SQLite

### Option A: Using Laravel Seeder (Recommended for Dev Data)

```bash
# Run seeders to populate with fresh data
php artisan db:seed

# Or specific seeders
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=HeadquartersDepartmentSeeder
php artisan db:seed --class=HierarchySeeder
```

### Option B: Data Migration Script (For Production Data)

Create `database/migration_script.php`:

```php
<?php

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

// Configure connections
$sqlite = DB::connection('sqlite');
$mysql = DB::connection('mysql');

$tables = [
    'users',
    'headquarters',
    'centres',
    'stations',
    'departments',
    'announcements',
    'documents',
    'events',
    'polls',
    'news',
    // Add all tables you need to migrate
];

foreach ($tables as $table) {
    echo "Migrating $table...\n";

    $data = $sqlite->table($table)->get()->toArray();
    $data = json_decode(json_encode($data), true);

    if (!empty($data)) {
        $mysql->table($table)->insert($data);
        echo "  ✓ Migrated " . count($data) . " records\n";
    } else {
        echo "  - No records to migrate\n";
    }
}

echo "\nMigration complete!\n";
```

Run it:

```bash
php database/migration_script.php
```

---

## Step 7: Verify Data Integrity

```bash
# Check record counts
php artisan tinker
```

In Tinker:

```php
// Compare counts
echo "Users: " . \App\Models\User::count() . "\n";
echo "Centres: " . \App\Models\Centre::count() . "\n";
echo "Stations: " . \App\Models\Station::count() . "\n";
echo "Announcements: " . \App\Models\Announcement::count() . "\n";
echo "Documents: " . \App\Models\Document::count() . "\n";
echo "Messages: " . \App\Models\Message::count() . "\n";

// Test a complex query
$user = \App\Models\User::with('centre', 'station')->first();
echo "User: " . $user->name . "\n";
echo "Centre: " . optional($user->centre)->name . "\n";

// Test birthday queries (verify MySQL compatibility)
$birthdays = \App\Models\User::birthdaysToday()->get();
echo "Birthdays today: " . $birthdays->count() . "\n";

exit
```

---

## Step 8: Test Application

```bash
# Start server
php artisan serve

# In browser, test:
# - Login
# - Dashboard loads
# - Announcements list
# - Documents list
# - Messaging
# - Admin functions
```

**Critical Tests:**

-   [ ] User authentication works
-   [ ] Dashboard loads without errors
-   [ ] Announcements visible
-   [ ] Documents accessible
-   [ ] Messaging functional
-   [ ] Birthday queries work
-   [ ] Admin reports load

---

## Step 9: Performance Optimization

```bash
# Cache configuration
php artisan config:cache

# Cache routes
php artisan route:cache

# Cache views
php artisan view:cache

# Optimize autoloader
composer install --optimize-autoloader --no-dev
```

---

## Step 10: Set Up Indexes (Already Done)

Indexes were added in migration: `2025_10_02_090855_add_performance_indexes_to_tables.php`

Verify indexes exist:

```sql
-- Show indexes for key tables
SHOW INDEX FROM users;
SHOW INDEX FROM announcements;
SHOW INDEX FROM documents;
SHOW INDEX FROM messages;
```

---

## Rollback Plan (If Issues Occur)

```bash
# 1. Stop application
sudo systemctl stop nginx  # or apache

# 2. Restore .env
cp .env.sqlite.backup .env

# 3. Restore SQLite database (if needed)
cp backups/migration-$(date +%Y%m%d)/database.sqlite.backup database/database.sqlite

# 4. Clear caches
php artisan config:clear
php artisan cache:clear

# 5. Restart application
sudo systemctl start nginx
```

---

## Post-Migration Checklist

-   [ ] All tests pass: `php artisan test`
-   [ ] Data integrity verified
-   [ ] Performance acceptable (page load < 500ms)
-   [ ] Birthday queries working
-   [ ] File uploads working
-   [ ] Search functionality working
-   [ ] Reports generating correctly
-   [ ] Messaging system functional
-   [ ] Admin functions accessible
-   [ ] Mobile view responsive
-   [ ] Error logs clean (`storage/logs/laravel.log`)
-   [ ] Backup strategy in place
-   [ ] Monitoring configured

---

## Common Issues & Solutions

### Issue: "Access denied for user"

**Solution:** Verify MySQL user credentials and permissions

```sql
SHOW GRANTS FOR 'intranet_user'@'localhost';
```

### Issue: "Table doesn't exist"

**Solution:** Run migrations again

```bash
php artisan migrate:fresh
```

### Issue: "SQLSTATE[HY000]: General error: 1364"

**Solution:** Some columns need default values. Check migration files.

### Issue: Slow queries after migration

**Solution:** Verify indexes are created

```bash
php artisan db:table users --database=mysql
```

### Issue: Character encoding issues

**Solution:** Ensure database uses utf8mb4

```sql
ALTER DATABASE nimr_intranet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

---

## Performance Benchmarks

**Target Performance (MySQL vs SQLite):**

| Operation         | SQLite | MySQL (Expected) |
| ----------------- | ------ | ---------------- |
| User login        | 150ms  | 80ms             |
| Dashboard load    | 800ms  | 300ms            |
| Announcement list | 200ms  | 100ms            |
| Search query      | 500ms  | 150ms            |
| Report generation | 2000ms | 800ms            |

---

## Next Steps After Migration

1. **Set up Redis** for caching and sessions
2. **Configure queue workers** for background jobs
3. **Set up automated backups**
4. **Enable query logging** temporarily to identify slow queries
5. **Monitor disk space** usage
6. **Set up database replication** (optional, for high availability)

---

## Support

If issues persist:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check MySQL error log: `/var/log/mysql/error.log`
3. Enable query logging in `.env`: `DB_LOG_QUERIES=true`
4. Contact: itsupport@nimr.or.tz

---

**Last Updated:** 2025-10-02  
**Tested On:** MySQL 8.0, MariaDB 10.6, Laravel 12
