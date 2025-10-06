# Production Environment Configuration Template

Copy the content below to your `.env` file on the production server.

```env
# ===========================================
# NIMR Intranet - Production Environment Configuration
# ===========================================

# Application
APP_NAME="NIMR Intranet"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_TIMEZONE=Africa/Dar_es_Salaam
APP_URL=https://intranet.nimr.or.tz

# Database (MySQL/MariaDB for Production)
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nimr_intranet
DB_USERNAME=intranet_user
DB_PASSWORD=

# Cache (Redis Recommended for Production)
CACHE_STORE=redis
CACHE_PREFIX=nimr_intranet

# Session
SESSION_DRIVER=redis
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_SECURE_COOKIE=true

# Queue (Redis for Production)
QUEUE_CONNECTION=redis

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_FROM_ADDRESS="noreply@nimr.or.tz"
MAIL_FROM_NAME="${APP_NAME}"

# Logging
LOG_CHANNEL=daily
LOG_LEVEL=warning
LOG_DAILY_DAYS=14

# Security & Rate Limiting
THROTTLE_LOGIN_ATTEMPTS=5
THROTTLE_REGISTRATION_ATTEMPTS=3
THROTTLE_PASSWORD_RESET_ATTEMPTS=3
```

## Production Checklist

-   [ ] Set APP_ENV=production
-   [ ] Set APP_DEBUG=false
-   [ ] Generate APP_KEY
-   [ ] Configure MySQL credentials
-   [ ] Set up Redis
-   [ ] Configure mail server
-   [ ] Enable HTTPS/SSL
