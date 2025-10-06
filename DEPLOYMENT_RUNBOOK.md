# NIMR Intranet - Deployment Runbook

**Version:** 2.0  
**Last Updated:** 2025-10-02  
**Environment:** Production

---

## Pre-Deployment Checklist

-   [ ] All tests passing locally (`php artisan test`)
-   [ ] Code reviewed and approved
-   [ ] Database migrations tested
-   [ ] Backup of current production database taken
-   [ ] Backup of production files taken
-   [ ] Deployment window scheduled and communicated
-   [ ] Rollback plan reviewed
-   [ ] Stakeholders notified

---

## Step 1: Pre-Deployment Backup (CRITICAL)

```bash
# Connect to production server
ssh user@intranet.nimr.or.tz

# Navigate to application directory
cd /var/www/intranet

# Create backup directory
mkdir -p ../backups/$(date +%Y%m%d_%H%M%S)

# Backup database
php artisan backup:run --only-db
# Or manually:
mysqldump -u username -p nimr_intranet > ../backups/$(date +%Y%m%d_%H%M%S)/database_backup.sql

# Backup files
tar -czf ../backups/$(date +%Y%m%d_%H%M%S)/files_backup.tar.gz .

# Verify backups
ls -lh ../backups/$(date +%Y%m%d_%H%M%S)/
```

---

## Step 2: Enable Maintenance Mode

```bash
# Put application in maintenance mode
php artisan down --secret="your-secret-token-here"

# Verify maintenance mode is active
curl https://intranet.nimr.or.tz
# Should show maintenance page

# Access site during maintenance (for testing)
# https://intranet.nimr.or.tz/your-secret-token-here
```

---

## Step 3: Pull Latest Code

```bash
# Ensure we're on the correct branch
git status

# Pull latest changes
git pull origin master

# Verify correct version
git log -1
```

---

## Step 4: Update Dependencies

```bash
# Update PHP dependencies (production only, optimized)
composer install --no-dev --optimize-autoloader

# Update JavaScript dependencies
npm ci --production

# Build frontend assets
npm run build

# Verify build succeeded
ls -lh public/build/
```

---

## Step 5: Run Database Migrations

```bash
# IMPORTANT: Review migrations first
php artisan migrate:status

# Run migrations (with backup confirmation)
php artisan migrate --force

# Verify migrations ran successfully
php artisan migrate:status
```

---

## Step 6: Clear and Rebuild Caches

```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild optimized caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
composer dump-autoload --optimize
```

---

## Step 7: Run Post-Deployment Tasks

```bash
# Storage link (if needed)
php artisan storage:link

# Queue restart (if using queues)
php artisan queue:restart

# Run any custom deployment commands
# php artisan your:custom-command
```

---

## Step 8: Verify Deployment

```bash
# Check application status
php artisan about

# Run smoke tests
php artisan test --testsuite=Feature --filter=SmokeTest

# Check key routes manually
curl -I https://intranet.nimr.or.tz/your-secret-token-here
curl -I https://intranet.nimr.or.tz/your-secret-token-here/dashboard
```

**Manual Verification Checklist:**

-   [ ] Homepage loads
-   [ ] Login works
-   [ ] Dashboard loads for each role
-   [ ] Announcements visible
-   [ ] Documents accessible
-   [ ] Messaging functional
-   [ ] Admin functions work
-   [ ] No JavaScript errors in console
-   [ ] Mobile view responsive

---

## Step 9: Disable Maintenance Mode

```bash
# Bring application back online
php artisan up

# Verify site is accessible
curl -I https://intranet.nimr.or.tz
```

---

## Step 10: Post-Deployment Monitoring

**First 15 Minutes:**

-   [ ] Monitor error logs: `tail -f storage/logs/laravel.log`
-   [ ] Monitor server resources: `htop` or `top`
-   [ ] Check response times
-   [ ] Monitor user feedback channels

**First Hour:**

-   [ ] Review error logs for any issues
-   [ ] Check database query performance
-   [ ] Monitor queue processing (if applicable)
-   [ ] Verify scheduled tasks running

**First 24 Hours:**

-   [ ] Daily log review
-   [ ] Performance metrics review
-   [ ] User feedback collection

---

## Rollback Procedure (If Needed)

### Quick Rollback (Code Issues)

```bash
# Enable maintenance mode
php artisan down

# Revert to previous version
git reset --hard <previous-commit-hash>

# Reinstall previous dependencies
composer install --no-dev --optimize-autoloader
npm ci --production
npm run build

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Rebuild caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Bring site back up
php artisan up
```

### Full Rollback (Database Issues)

```bash
# Enable maintenance mode
php artisan down

# Restore database from backup
mysql -u username -p nimr_intranet < ../backups/YYYYMMDD_HHMMSS/database_backup.sql

# Restore files if needed
cd /var/www/
tar -xzf backups/YYYYMMDD_HHMMSS/files_backup.tar.gz -C intranet/

# Clear caches
cd intranet
php artisan config:clear
php artisan cache:clear

# Bring site back up
php artisan up
```

---

## Common Issues & Solutions

### Issue: "Class not found" errors

**Solution:**

```bash
composer dump-autoload --optimize
php artisan config:clear
```

### Issue: "Mix manifest not found"

**Solution:**

```bash
npm run build
php artisan cache:clear
```

### Issue: "Permission denied" errors

**Solution:**

```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

### Issue: Slow page loads after deployment

**Solution:**

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

---

## Post-Deployment Communication

**To Users:**

```
Subject: System Update Complete

The NIMR Intranet has been successfully updated with new features and improvements.

New in this release:
- [List key features]
- [Performance improvements]
- [Bug fixes]

If you experience any issues, please contact IT Support at itsupport@nimr.or.tz

Thank you for your patience!
```

**To IT Team:**

```
Deployment completed successfully at [TIME]
- Version: [GIT COMMIT]
- Duration: [MINUTES] minutes
- Issues: [NONE/LIST]
- Rollback: [NOT REQUIRED/AVAILABLE]

Monitoring status: NORMAL
```

---

## Emergency Contacts

-   **IT Director:** +255-XXX-XXXXXX
-   **Lead Developer:** +255-XXX-XXXXXX
-   **System Administrator:** +255-XXX-XXXXXX
-   **Database Administrator:** +255-XXX-XXXXXX

---

## Deployment Schedule

**Recommended Times:**

-   Weekdays: 18:00 - 20:00 EAT (after working hours)
-   Weekends: 10:00 - 12:00 EAT (minimal usage)

**Avoid:**

-   Monday mornings (high usage)
-   End of month (reporting period)
-   During major organizational events

---

## Automated Deployment Script

For future use, create `/var/www/deploy.sh`:

```bash
#!/bin/bash
# NIMR Intranet Deployment Script

set -e  # Exit on any error

echo "Starting deployment..."

# Maintenance mode
php artisan down

# Pull latest code
git pull origin master

# Dependencies
composer install --no-dev --optimize-autoloader
npm ci --production
npm run build

# Database
php artisan migrate --force

# Caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Bring back up
php artisan up

echo "Deployment complete!"
```

---

**Last Deployment:** YYYY-MM-DD HH:MM:SS  
**Next Scheduled:** YYYY-MM-DD HH:MM:SS
