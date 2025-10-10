# NIMR Intranet System Administrator Manual

**Version**: 1.0.0  
**Last Updated**: January 2025  
**Target Audience**: System Administrators, DevOps Engineers, IT Operations  
**System Version**: NIMR Intranet v2.1.0

---

## Table of Contents

1. [System Overview](#system-overview)
2. [Installation & Deployment](#installation--deployment)
3. [System Configuration](#system-configuration)
4. [User & Role Management](#user--role-management)
5. [Organizational Structure Management](#organizational-structure-management)
6. [Content Management](#content-management)
7. [Security Configuration](#security-configuration)
8. [Backup & Recovery](#backup--recovery)
9. [Monitoring & Maintenance](#monitoring--maintenance)
10. [Performance Optimization](#performance-optimization)
11. [Integration Management](#integration-management)
12. [Troubleshooting & Diagnostics](#troubleshooting--diagnostics)
13. [System Updates & Patches](#system-updates--patches)
14. [Emergency Procedures](#emergency-procedures)

---

## 🏗️ System Overview

### Architecture Overview

The NIMR Intranet System is a Laravel-based web application designed for internal organizational communication and document management.

#### **Technology Stack**
- **Backend Framework**: Laravel 11.x (PHP 8.2+)
- **Database**: MySQL 8.0+ / MariaDB 10.6+
- **Frontend**: Blade Templates with Tailwind CSS
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **PHP Requirements**: PHP 8.2+ with required extensions
- **Caching**: Redis (recommended) / File-based caching
- **Queue System**: Redis / Database queues
- **File Storage**: Local filesystem / S3-compatible storage

#### **System Components**
- **Web Application**: Main Laravel application
- **Database Server**: MySQL/MariaDB for data storage
- **File Storage**: Document and attachment storage
- **Cache Server**: Redis for session and application caching
- **Queue Worker**: Background job processing
- **Email Server**: SMTP for notifications and communications

#### **Organizational Hierarchy**
```
NIMR Headquarters
├── Centre A
│   ├── Station A1
│   │   ├── Department A1a
│   │   └── Department A1b
│   └── Station A2
└── Centre B
    ├── Station B1
    └── Station B2
```

### System Requirements

#### **Minimum Server Requirements**
- **CPU**: 2 cores, 2.4 GHz
- **RAM**: 4 GB (8 GB recommended)
- **Storage**: 50 GB SSD (100 GB recommended)
- **Network**: 100 Mbps connection
- **Operating System**: Ubuntu 20.04+ / CentOS 8+ / RHEL 8+

#### **Recommended Production Requirements**
- **CPU**: 4+ cores, 3.0 GHz
- **RAM**: 16 GB
- **Storage**: 200 GB SSD with backup storage
- **Network**: 1 Gbps connection
- **Load Balancer**: For high availability setups
- **CDN**: For static asset delivery

#### **Software Dependencies**
- **PHP**: 8.2+ with extensions (mbstring, xml, curl, zip, gd, mysql, redis)
- **Composer**: Latest version for dependency management
- **Node.js**: 18+ for frontend asset compilation
- **NPM/Yarn**: For JavaScript dependency management

---

## 🚀 Installation & Deployment

### Pre-Installation Checklist

#### **Server Preparation**
1. **Update System Packages**
   ```bash
   sudo apt update && sudo apt upgrade -y
   ```

2. **Install Required Software**
   ```bash
   # Install PHP and extensions
   sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-curl \
                    php8.2-zip php8.2-gd php8.2-mbstring php8.2-redis

   # Install MySQL
   sudo apt install mysql-server-8.0

   # Install Redis
   sudo apt install redis-server

   # Install Nginx
   sudo apt install nginx
   ```

3. **Install Composer**
   ```bash
   curl -sS https://getcomposer.org/installer | php
   sudo mv composer.phar /usr/local/bin/composer
   ```

4. **Install Node.js and NPM**
   ```bash
   curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
   sudo apt install nodejs
   ```

### Database Setup

#### **MySQL Configuration**
1. **Secure MySQL Installation**
   ```bash
   sudo mysql_secure_installation
   ```

2. **Create Database and User**
   ```sql
   CREATE DATABASE nimr_intranet CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   CREATE USER 'nimr_user'@'localhost' IDENTIFIED BY 'secure_password';
   GRANT ALL PRIVILEGES ON nimr_intranet.* TO 'nimr_user'@'localhost';
   FLUSH PRIVILEGES;
   ```

3. **Optimize MySQL Configuration**
   ```ini
   # /etc/mysql/mysql.conf.d/mysqld.cnf
   [mysqld]
   innodb_buffer_pool_size = 2G
   innodb_log_file_size = 256M
   max_connections = 200
   query_cache_size = 64M
   tmp_table_size = 64M
   max_heap_table_size = 64M
   ```

### Application Deployment

#### **Clone and Setup Application**
1. **Clone Repository**
   ```bash
   cd /var/www
   git clone [repository-url] nimr-intranet
   cd nimr-intranet
   ```

2. **Install Dependencies**
   ```bash
   # Install PHP dependencies
   composer install --optimize-autoloader --no-dev

   # Install Node.js dependencies
   npm install

   # Build frontend assets
   npm run build
   ```

3. **Environment Configuration**
   ```bash
   # Copy environment file
   cp .env.example .env

   # Generate application key
   php artisan key:generate
   ```

4. **Configure Environment Variables**
   ```env
   # .env file configuration
   APP_NAME="NIMR Intranet"
   APP_ENV=production
   APP_KEY=base64:generated_key_here
   APP_DEBUG=false
   APP_URL=https://intranet.nimr.or.tz

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nimr_intranet
   DB_USERNAME=nimr_user
   DB_PASSWORD=secure_password

   CACHE_DRIVER=redis
   QUEUE_CONNECTION=redis
   SESSION_DRIVER=redis

   REDIS_HOST=127.0.0.1
   REDIS_PASSWORD=null
   REDIS_PORT=6379

   MAIL_MAILER=smtp
   MAIL_HOST=smtp.nimr.or.tz
   MAIL_PORT=587
   MAIL_USERNAME=noreply@nimr.or.tz
   MAIL_PASSWORD=mail_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@nimr.or.tz
   MAIL_FROM_NAME="NIMR Intranet"

   FILESYSTEM_DISK=local
   ```

5. **Database Migration and Seeding**
   ```bash
   # Run database migrations
   php artisan migrate

   # Seed initial data (optional)
   php artisan db:seed
   ```

6. **Set File Permissions**
   ```bash
   # Set ownership
   sudo chown -R www-data:www-data /var/www/nimr-intranet

   # Set permissions
   sudo chmod -R 755 /var/www/nimr-intranet
   sudo chmod -R 775 /var/www/nimr-intranet/storage
   sudo chmod -R 775 /var/www/nimr-intranet/bootstrap/cache
   ```

### Web Server Configuration

#### **Nginx Configuration**
```nginx
# /etc/nginx/sites-available/nimr-intranet
server {
    listen 80;
    server_name intranet.nimr.or.tz;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name intranet.nimr.or.tz;
    root /var/www/nimr-intranet/public;

    index index.php;

    # SSL Configuration
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # File Upload Limits
    client_max_body_size 20M;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Static file caching
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

#### **Enable Site and Restart Services**
```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/nimr-intranet /etc/nginx/sites-enabled/

# Test configuration
sudo nginx -t

# Restart services
sudo systemctl restart nginx
sudo systemctl restart php8.2-fpm
```

### Queue Worker Setup

#### **Systemd Service Configuration**
```ini
# /etc/systemd/system/nimr-queue-worker.service
[Unit]
Description=NIMR Intranet Queue Worker
After=redis.service

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/nimr-intranet/artisan queue:work redis --sleep=3 --tries=3 --max-time=3600
StandardOutput=journal
StandardError=journal

[Install]
WantedBy=multi-user.target
```

#### **Enable and Start Queue Worker**
```bash
sudo systemctl daemon-reload
sudo systemctl enable nimr-queue-worker
sudo systemctl start nimr-queue-worker
```

### SSL Certificate Setup

#### **Using Let's Encrypt (Certbot)**
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Obtain certificate
sudo certbot --nginx -d intranet.nimr.or.tz

# Auto-renewal setup
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

---

## ⚙️ System Configuration

### Application Configuration

#### **Core Settings**
The application configuration is managed through the `.env` file and Laravel configuration files.

**Key Configuration Areas:**
- **Application Settings**: Name, environment, debug mode
- **Database Configuration**: Connection parameters and optimization
- **Cache Configuration**: Redis setup and cache drivers
- **Mail Configuration**: SMTP settings for notifications
- **File Storage**: Local or cloud storage configuration
- **Security Settings**: Encryption keys and security headers

#### **Environment-Specific Configuration**

**Production Environment (.env)**
```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
CACHE_DRIVER=redis
SESSION_LIFETIME=120
QUEUE_CONNECTION=redis
```

**Development Environment (.env.local)**
```env
APP_ENV=local
APP_DEBUG=true
LOG_LEVEL=debug
CACHE_DRIVER=file
SESSION_LIFETIME=1440
QUEUE_CONNECTION=sync
```

### Email Configuration

#### **SMTP Setup**
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.nimr.or.tz
MAIL_PORT=587
MAIL_USERNAME=noreply@nimr.or.tz
MAIL_PASSWORD=secure_mail_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@nimr.or.tz
MAIL_FROM_NAME="NIMR Intranet System"
```

#### **Email Testing**
```bash
# Test email configuration
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('admin@nimr.or.tz')->subject('Test'); });
```

### File Storage Configuration

#### **Local Storage (Default)**
```env
FILESYSTEM_DISK=local
```

#### **S3-Compatible Storage**
```env
FILESYSTEM_DISK=s3
AWS_ACCESS_KEY_ID=your_access_key
AWS_SECRET_ACCESS_KEY=your_secret_key
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=nimr-intranet-files
AWS_USE_PATH_STYLE_ENDPOINT=false
```

### Cache Configuration

#### **Redis Cache Setup**
```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
REDIS_DB=0
```

#### **Cache Optimization Commands**
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Timezone Configuration

#### **Application Timezone**
```php
// config/app.php
'timezone' => 'Africa/Nairobi',
```

#### **Database Timezone Considerations**
Ensure MySQL timezone matches application timezone:
```sql
SET GLOBAL time_zone = '+03:00';
```

---

## 👥 User & Role Management

### User Role Hierarchy

The system implements a hierarchical role structure with specific permissions and access levels.

#### **Role Definitions**

**Super Administrator**
- Complete system access and control
- User management across all organizational levels
- System configuration and maintenance
- Backup and security management
- Technical system administration

**HQ Administrator**
- Organization-wide content management
- Multi-centre user management
- Policy and procedure management
- Organization-wide reporting and analytics
- Strategic communication management

**Centre Administrator**
- Centre-level user management
- Multi-station oversight and coordination
- Centre-wide content creation and management
- Centre performance monitoring and reporting
- Cross-station resource coordination

**Station Administrator**
- Station-level user management
- Local content creation and management
- Station event and activity coordination
- Local reporting and analytics
- Station resource management

**Staff Member**
- Personal productivity tools access
- Content consumption and participation
- Messaging and communication features
- Event participation and RSVP
- Personal profile and preference management

### User Account Management

#### **Creating User Accounts**

**Via Admin Interface:**
1. Navigate to Admin → Users → Create New User
2. Fill required information:
   - Full Name
   - Email Address (@nimr.or.tz domain)
   - Role Assignment
   - Organizational Assignment (Centre/Station/Department)
   - Employee ID (if applicable)
3. Set initial password or generate random password
4. Configure account settings and permissions
5. Send welcome email with login instructions

**Via Command Line:**
```bash
# Create super admin user
php artisan make:user --role=super_admin --email=admin@nimr.or.tz --name="System Administrator"

# Create regular user
php artisan make:user --role=staff --email=user@nimr.or.tz --name="Staff Member" --centre=1 --station=1
```

#### **Bulk User Import**

**CSV Import Format:**
```csv
name,email,role,centre_id,station_id,department_id,employee_id
John Doe,john.doe@nimr.or.tz,staff,1,1,1,EMP001
Jane Smith,jane.smith@nimr.or.tz,station_admin,1,1,,EMP002
```

**Import Command:**
```bash
php artisan users:import /path/to/users.csv
```

#### **User Account Maintenance**

**Password Management:**
- Force password reset for security
- Set password expiration policies
- Monitor password strength compliance
- Handle forgotten password requests

**Account Status Management:**
- Activate/deactivate user accounts
- Suspend accounts for policy violations
- Archive accounts for departed employees
- Restore archived accounts if needed

**Profile Information Updates:**
- Update organizational assignments
- Modify role assignments (with proper authorization)
- Update contact information
- Manage profile visibility settings

### Role Permission Management

#### **Permission Matrix**

| Feature | Staff | Station Admin | Centre Admin | HQ Admin | Super Admin |
|---------|-------|---------------|--------------|----------|-------------|
| View Dashboard | ✅ | ✅ | ✅ | ✅ | ✅ |
| Create Announcements | ❌ | Station Only | Centre Only | All | All |
| Manage Documents | ❌ | Station Only | Centre Only | All | All |
| User Management | ❌ | Station Only | Centre Only | All | All |
| System Settings | ❌ | ❌ | ❌ | Limited | Full |
| Backup Management | ❌ | ❌ | ❌ | ❌ | ✅ |

#### **Custom Permission Configuration**

**Modify Permissions (config/permissions.php):**
```php
'roles' => [
    'staff' => [
        'announcements.view',
        'documents.view',
        'messages.send',
        'events.rsvp',
    ],
    'station_admin' => [
        'announcements.create.station',
        'users.manage.station',
        'documents.upload.station',
    ],
    // ... additional role configurations
],
```

### Organizational Assignment Management

#### **Hierarchy Management**

**Centre Management:**
- Create and configure centres
- Assign centre administrators
- Set centre-specific policies
- Monitor centre performance

**Station Management:**
- Create stations within centres
- Assign station administrators
- Configure station-specific settings
- Manage station resources

**Department Management:**
- Create departments within stations
- Assign department heads
- Configure department-specific access
- Manage department workflows

#### **User Assignment Commands**

```bash
# Assign user to organizational unit
php artisan user:assign {user_id} --centre=1 --station=2 --department=3

# Bulk reassignment
php artisan users:reassign --from-station=1 --to-station=2

# Update user role
php artisan user:role {user_id} station_admin
```

---

## 🏢 Organizational Structure Management

### Hierarchy Configuration

#### **Headquarters Setup**
The headquarters represents the top level of the organizational hierarchy.

**Configuration Steps:**
1. **Create Headquarters Record**
   ```bash
   php artisan org:create-headquarters --name="NIMR Headquarters" --code="HQ" --location="Dar es Salaam"
   ```

2. **Configure Headquarters Settings**
   - Set headquarters contact information
   - Configure headquarters-level policies
   - Set up headquarters administrators
   - Define headquarters-wide settings

#### **Centre Management**

**Creating Centres:**
```bash
# Create new centre
php artisan org:create-centre --name="Mwanza Centre" --code="MWZ" --location="Mwanza"
```

**Centre Configuration:**
- Centre name and identification code
- Physical location and contact details
- Centre administrator assignments
- Centre-specific policies and procedures
- Resource allocation and budgets

**Centre Administration Interface:**
1. Navigate to Admin → Centres
2. Click "Create New Centre"
3. Fill centre information:
   - Centre Name
   - Centre Code (unique identifier)
   - Description and purpose
   - Physical location
   - Contact information
4. Assign centre administrator
5. Configure centre-specific settings

#### **Station Management**

**Creating Stations:**
```bash
# Create station under specific centre
php artisan org:create-station --name="Mwanza Research Station" --code="MWZ-RS" --centre=1
```

**Station Configuration:**
- Station identification and naming
- Centre assignment and hierarchy
- Station administrator assignment
- Local resource management
- Station-specific procedures

**Station Administration:**
1. Navigate to Admin → Stations
2. Select parent centre
3. Click "Create New Station"
4. Configure station details:
   - Station name and code
   - Centre assignment
   - Location details
   - Administrator assignment
5. Set station-specific permissions and policies

#### **Department Management**

**Creating Departments:**
```bash
# Create department under station
php artisan org:create-department --name="Research Department" --code="RES" --station=1
```

**Department Structure:**
- Department identification
- Station assignment
- Department head assignment
- Functional area definition
- Resource allocation

### Organizational Data Management

#### **Data Integrity Maintenance**

**Hierarchy Validation:**
- Ensure proper parent-child relationships
- Validate organizational assignments
- Check for orphaned records
- Maintain referential integrity

**Data Cleanup Commands:**
```bash
# Validate organizational hierarchy
php artisan org:validate

# Fix orphaned records
php artisan org:cleanup

# Generate organizational report
php artisan org:report
```

#### **Organizational Reporting**

**Hierarchy Reports:**
- Complete organizational chart
- User distribution by organizational unit
- Administrative assignment reports
- Resource allocation summaries

**Generate Reports:**
```bash
# Generate organizational chart
php artisan report:org-chart --format=pdf --output=/tmp/org-chart.pdf

# User distribution report
php artisan report:user-distribution --centre=1
```

### Migration and Restructuring

#### **Organizational Changes**

**Centre Restructuring:**
- Merge or split centres
- Reassign stations between centres
- Update administrative assignments
- Migrate user assignments

**Station Reorganization:**
- Transfer stations between centres
- Merge or split stations
- Reassign departments
- Update user assignments

**Department Restructuring:**
- Move departments between stations
- Merge or split departments
- Reassign staff members
- Update reporting structures

#### **Migration Commands**

```bash
# Move station to different centre
php artisan org:move-station {station_id} --to-centre={centre_id}

# Merge departments
php artisan org:merge-departments {source_dept_id} {target_dept_id}

# Bulk user reassignment
php artisan org:reassign-users --from-station={old_station} --to-station={new_station}
```

#### **Change Management Process**

1. **Planning Phase**
   - Document current structure
   - Plan new organizational structure
   - Identify affected users and data
   - Prepare migration timeline

2. **Preparation Phase**
   - Backup current organizational data
   - Prepare migration scripts
   - Notify affected users
   - Schedule maintenance window

3. **Migration Phase**
   - Execute organizational changes
   - Migrate user assignments
   - Update permissions and access
   - Validate data integrity

4. **Validation Phase**
   - Test organizational hierarchy
   - Verify user access and permissions
   - Validate reporting structures
   - Confirm system functionality

5. **Communication Phase**
   - Notify users of changes
   - Update documentation
   - Provide training if needed
   - Monitor for issues

---

## 📄 Content Management

### Document Management Administration

#### **Document Categories and Organization**

**Department-Based Categories:**
- Human Resources: Policies, procedures, forms
- Information Technology: Guidelines, manuals, procedures
- Research & Development: Protocols, studies, reports
- Finance: Policies, procedures, forms, reports
- Administration: General documents, procedures
- Training & Development: Materials, courses, guides

**Document Access Control:**
- **All NIMR**: Accessible to all staff members
- **Centre-Specific**: Limited to specific centres
- **Station-Specific**: Limited to specific stations
- **Department-Specific**: Limited to specific departments
- **Role-Based**: Access based on user role level

#### **Document Upload and Management**

**Administrative Document Upload:**
1. Navigate to Admin → Documents → Upload
2. Select document file (PDF, DOC, XLS, images)
3. Configure document metadata:
   - Title and description
   - Category assignment
   - Access level and visibility
   - Target audience (centres/stations)
   - Tags for searchability
4. Set document properties:
   - Version control settings
   - Expiration date (if applicable)
   - Download permissions
   - Approval workflow

**Bulk Document Management:**
```bash
# Bulk document import
php artisan documents:import /path/to/documents --category=hr --access=all

# Document cleanup
php artisan documents:cleanup --expired --orphaned

# Document indexing for search
php artisan documents:reindex
```

#### **Document Version Control**

**Version Management:**
- Automatic version tracking
- Version comparison tools
- Rollback capabilities
- Version approval workflows

**Version Control Commands:**
```bash
# Create new document version
php artisan document:version {document_id} --file=/path/to/new/version.pdf

# List document versions
php artisan document:versions {document_id}

# Rollback to previous version
php artisan document:rollback {document_id} --version=2
```

### Announcement Management

#### **Announcement Creation and Targeting**

**Administrative Announcement Creation:**
1. Navigate to Admin → Announcements → Create
2. Configure announcement details:
   - Title and content (rich text editor)
   - Category and priority level
   - Target scope (All NIMR, Centre, Station)
   - Specific targeting (select centres/stations)
3. Set scheduling and expiration:
   - Publication date and time
   - Expiration date (optional)
   - Email notification settings
4. Add attachments if needed
5. Preview and publish or save as draft

**Announcement Targeting Options:**
- **Organization-wide**: All NIMR staff
- **Centre-specific**: All staff in selected centres
- **Station-specific**: All staff in selected stations
- **Role-based**: Specific user roles only
- **Custom**: Manually selected user groups

#### **Announcement Analytics and Reporting**

**Engagement Metrics:**
- View counts and read rates
- User engagement by organizational unit
- Response rates to call-to-action announcements
- Time-based engagement analysis

**Generate Announcement Reports:**
```bash
# Announcement engagement report
php artisan report:announcements --period=month --format=pdf

# Unread announcements report
php artisan report:unread-announcements --centre=1
```

### Content Moderation and Approval

#### **Content Approval Workflows**

**Approval Process Configuration:**
- Define approval requirements by content type
- Set up approval chains by organizational level
- Configure automatic approval rules
- Set up escalation procedures

**Approval Workflow Management:**
1. **Content Submission**: User submits content for approval
2. **Initial Review**: Assigned reviewer evaluates content
3. **Approval/Rejection**: Reviewer approves or requests changes
4. **Publication**: Approved content is published automatically
5. **Notification**: Relevant parties are notified of status changes

#### **Content Quality Control**

**Quality Assurance Measures:**
- Automated content scanning for policy compliance
- Manual review processes for sensitive content
- Version control and audit trails
- Content archival and retention policies

**Content Moderation Commands:**
```bash
# Review pending content
php artisan content:review --type=announcements --status=pending

# Bulk approve content
php artisan content:approve --author={user_id} --type=documents

# Content audit report
php artisan content:audit --period=quarter
```

### Content Analytics and Insights

#### **Usage Analytics**

**Content Performance Metrics:**
- Most viewed documents and announcements
- User engagement patterns by organizational unit
- Content effectiveness and reach analysis
- Search query analysis and content gaps

**Analytics Dashboard:**
- Real-time content performance metrics
- User engagement heatmaps
- Content lifecycle analytics
- ROI analysis for content creation efforts

#### **Content Optimization**

**Performance Optimization:**
- Identify high-performing content for replication
- Analyze low-engagement content for improvement
- Optimize content targeting and timing
- Improve content discoverability through SEO

**Optimization Commands:**
```bash
# Generate content performance report
php artisan analytics:content-performance --period=month

# Identify content gaps
php artisan analytics:content-gaps --department=hr

# Content optimization recommendations
php artisan analytics:optimize-content
```
---


## 🔒 Security Configuration

### Authentication and Authorization

#### **Authentication Security**

**Password Policies:**
```php
// config/auth.php - Password requirements
'passwords' => [
    'users' => [
        'provider' => 'users',
        'table' => 'password_reset_tokens',
        'expire' => 60,
        'throttle' => 60,
        'min_length' => 8,
        'require_uppercase' => true,
        'require_lowercase' => true,
        'require_numbers' => true,
        'require_symbols' => false,
    ],
],
```

**Session Security Configuration:**
```env
SESSION_LIFETIME=120
SESSION_ENCRYPT=true
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=strict
```

**Two-Factor Authentication (Optional):**
```bash
# Install 2FA package
composer require pragmarx/google2fa-laravel

# Enable 2FA for admin users
php artisan 2fa:enable --role=super_admin
```

#### **Authorization and Permissions**

**Role-Based Access Control (RBAC):**
- Hierarchical role structure with inheritance
- Permission-based access control
- Dynamic permission assignment
- Audit trails for permission changes

**Security Middleware Configuration:**
```php
// app/Http/Kernel.php
protected $routeMiddleware = [
    'auth' => \App\Http\Middleware\Authenticate::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    'role' => \App\Http\Middleware\CheckRole::class,
    'permission' => \App\Http\Middleware\CheckPermission::class,
];
```

### Data Security and Encryption

#### **Database Security**

**Encryption Configuration:**
```env
# Application encryption key
APP_KEY=base64:generated_encryption_key

# Database encryption for sensitive fields
DB_ENCRYPT_SENSITIVE=true
```

**Sensitive Data Protection:**
- Personal information encryption
- Password hashing with bcrypt
- API token encryption
- File attachment encryption

**Database Access Security:**
```sql
-- Create read-only user for reporting
CREATE USER 'nimr_readonly'@'localhost' IDENTIFIED BY 'readonly_password';
GRANT SELECT ON nimr_intranet.* TO 'nimr_readonly'@'localhost';

-- Revoke unnecessary privileges
REVOKE ALL PRIVILEGES ON *.* FROM 'nimr_user'@'localhost';
GRANT SELECT, INSERT, UPDATE, DELETE ON nimr_intranet.* TO 'nimr_user'@'localhost';
```

#### **File Security**

**File Upload Security:**
- File type validation and restrictions
- Virus scanning integration
- File size limitations
- Secure file storage with access controls

**File Storage Security Configuration:**
```php
// config/filesystems.php
'local' => [
    'driver' => 'local',
    'root' => storage_path('app/private'),
    'permissions' => [
        'file' => [
            'public' => 0644,
            'private' => 0600,
        ],
        'dir' => [
            'public' => 0755,
            'private' => 0700,
        ],
    ],
],
```

### Network Security

#### **SSL/TLS Configuration**

**SSL Certificate Management:**
```bash
# Check certificate expiration
openssl x509 -in /path/to/certificate.crt -text -noout | grep "Not After"

# Renew Let's Encrypt certificate
sudo certbot renew --nginx

# Test SSL configuration
curl -I https://intranet.nimr.or.tz
```

**Security Headers Configuration:**
```nginx
# Nginx security headers
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
```

#### **Firewall Configuration**

**UFW Firewall Setup:**
```bash
# Enable firewall
sudo ufw enable

# Allow SSH (change port if needed)
sudo ufw allow 22/tcp

# Allow HTTP and HTTPS
sudo ufw allow 80/tcp
sudo ufw allow 443/tcp

# Allow MySQL (local only)
sudo ufw allow from 127.0.0.1 to any port 3306

# Deny all other incoming traffic
sudo ufw default deny incoming
sudo ufw default allow outgoing
```

### Security Monitoring and Auditing

#### **Audit Logging**

**Enable Audit Logging:**
```php
// config/logging.php
'channels' => [
    'audit' => [
        'driver' => 'daily',
        'path' => storage_path('logs/audit.log'),
        'level' => 'info',
        'days' => 90,
    ],
],
```

**Audit Events to Monitor:**
- User login/logout activities
- Permission and role changes
- Sensitive data access
- Administrative actions
- Failed authentication attempts
- File upload/download activities

#### **Security Monitoring Commands**

```bash
# Monitor failed login attempts
php artisan security:failed-logins --period=24h

# Generate security audit report
php artisan security:audit-report --format=pdf

# Check for suspicious activities
php artisan security:suspicious-activity --threshold=5

# Monitor file access patterns
php artisan security:file-access-report
```

### Incident Response

#### **Security Incident Procedures**

**Incident Response Plan:**
1. **Detection**: Identify security incidents through monitoring
2. **Assessment**: Evaluate severity and impact
3. **Containment**: Isolate affected systems
4. **Investigation**: Analyze incident details
5. **Recovery**: Restore normal operations
6. **Documentation**: Document incident and lessons learned

**Emergency Security Commands:**
```bash
# Disable user account immediately
php artisan user:disable {user_id} --reason="Security incident"

# Lock down system (maintenance mode)
php artisan down --secret="emergency-access-token"

# Clear all user sessions
php artisan session:flush

# Generate emergency security report
php artisan security:emergency-report
```

---

## 💾 Backup & Recovery

### Backup Strategy

#### **Backup Components**

**Critical Data to Backup:**
- Database (MySQL)
- Application files and configuration
- User-uploaded documents and attachments
- Log files and audit trails
- SSL certificates and keys

**Backup Types:**
- **Full Backup**: Complete system backup (weekly)
- **Incremental Backup**: Changed files only (daily)
- **Database Backup**: Database-only backup (multiple times daily)
- **Configuration Backup**: System configuration files (after changes)

#### **Automated Backup Setup**

**Database Backup Script:**
```bash
#!/bin/bash
# /usr/local/bin/backup-database.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/database"
DB_NAME="nimr_intranet"
DB_USER="nimr_user"
DB_PASS="secure_password"

# Create backup directory
mkdir -p $BACKUP_DIR

# Perform database backup
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/nimr_db_$DATE.sql.gz

# Remove backups older than 30 days
find $BACKUP_DIR -name "nimr_db_*.sql.gz" -mtime +30 -delete

# Log backup completion
echo "$(date): Database backup completed - nimr_db_$DATE.sql.gz" >> /var/log/backup.log
```

**Application Backup Script:**
```bash
#!/bin/bash
# /usr/local/bin/backup-application.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/backup/application"
APP_DIR="/var/www/nimr-intranet"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup application files (excluding cache and logs)
tar -czf $BACKUP_DIR/nimr_app_$DATE.tar.gz \
    --exclude='storage/logs/*' \
    --exclude='storage/framework/cache/*' \
    --exclude='storage/framework/sessions/*' \
    --exclude='storage/framework/views/*' \
    --exclude='node_modules' \
    -C /var/www nimr-intranet

# Remove backups older than 7 days
find $BACKUP_DIR -name "nimr_app_*.tar.gz" -mtime +7 -delete

echo "$(date): Application backup completed - nimr_app_$DATE.tar.gz" >> /var/log/backup.log
```

#### **Cron Job Configuration**

```bash
# Edit crontab
sudo crontab -e

# Add backup schedules
# Database backup every 6 hours
0 */6 * * * /usr/local/bin/backup-database.sh

# Application backup daily at 2 AM
0 2 * * * /usr/local/bin/backup-application.sh

# Full system backup weekly on Sunday at 1 AM
0 1 * * 0 /usr/local/bin/backup-full-system.sh
```

### Backup Management

#### **Laravel Backup Package**

**Install Backup Package:**
```bash
composer require spatie/laravel-backup
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"
```

**Configure Backup Settings:**
```php
// config/backup.php
'backup' => [
    'name' => 'nimr-intranet',
    'source' => [
        'files' => [
            'include' => [
                base_path(),
            ],
            'exclude' => [
                base_path('vendor'),
                base_path('node_modules'),
                storage_path('framework/cache'),
                storage_path('framework/sessions'),
                storage_path('framework/views'),
                storage_path('logs'),
            ],
        ],
        'databases' => [
            'mysql',
        ],
    ],
    'destination' => [
        'filename_prefix' => '',
        'disks' => [
            'backup',
        ],
    ],
],
```

**Backup Commands:**
```bash
# Create full backup
php artisan backup:run

# Create database-only backup
php artisan backup:run --only-db

# List all backups
php artisan backup:list

# Clean old backups
php artisan backup:clean
```

### Recovery Procedures

#### **Database Recovery**

**Full Database Restore:**
```bash
# Stop application (maintenance mode)
php artisan down

# Restore database from backup
gunzip < /backup/database/nimr_db_20250115_020000.sql.gz | mysql -u nimr_user -p nimr_intranet

# Clear application cache
php artisan cache:clear
php artisan config:clear

# Bring application back online
php artisan up
```

**Point-in-Time Recovery:**
```bash
# Restore to specific backup point
mysql -u nimr_user -p nimr_intranet < /backup/database/nimr_db_specific_date.sql

# Apply incremental changes if available
mysql -u nimr_user -p nimr_intranet < /backup/incremental/changes_after_backup.sql
```

#### **Application Recovery**

**Full Application Restore:**
```bash
# Stop web server
sudo systemctl stop nginx

# Backup current application (if needed)
mv /var/www/nimr-intranet /var/www/nimr-intranet.backup

# Extract backup
cd /var/www
tar -xzf /backup/application/nimr_app_20250115_020000.tar.gz

# Set permissions
sudo chown -R www-data:www-data /var/www/nimr-intranet
sudo chmod -R 755 /var/www/nimr-intranet
sudo chmod -R 775 /var/www/nimr-intranet/storage

# Clear caches and optimize
cd /var/www/nimr-intranet
php artisan cache:clear
php artisan config:cache
php artisan route:cache

# Start web server
sudo systemctl start nginx
```

#### **Disaster Recovery**

**Complete System Recovery:**
1. **Prepare New Server**: Set up new server with same specifications
2. **Install Dependencies**: Install all required software and dependencies
3. **Restore Application**: Extract application backup to new server
4. **Restore Database**: Import database backup to new MySQL instance
5. **Configure Environment**: Update .env file with new server settings
6. **Update DNS**: Point domain to new server IP address
7. **Test Functionality**: Verify all features work correctly
8. **Monitor Performance**: Monitor system performance and stability

**Recovery Testing:**
```bash
# Test database connectivity
php artisan tinker
>>> DB::connection()->getPdo();

# Test file permissions
php artisan storage:link

# Test email functionality
php artisan queue:work --once

# Verify backup integrity
php artisan backup:monitor
```

### Backup Monitoring and Alerts

#### **Backup Monitoring**

**Monitor Backup Health:**
```bash
# Check backup status
php artisan backup:monitor

# Verify backup integrity
php artisan backup:list --check-health

# Generate backup report
php artisan backup:report --email=admin@nimr.or.tz
```

**Backup Alert Configuration:**
```php
// config/backup.php
'notifications' => [
    'notifications' => [
        \Spatie\Backup\Notifications\Notifications\BackupHasFailed::class => ['mail'],
        \Spatie\Backup\Notifications\Notifications\UnhealthyBackupWasFound::class => ['mail'],
        \Spatie\Backup\Notifications\Notifications\CleanupHasFailed::class => ['mail'],
        \Spatie\Backup\Notifications\Notifications\BackupWasSuccessful::class => ['mail'],
    ],
    'notifiable' => \Spatie\Backup\Notifications\Notifiable::class,
    'mail' => [
        'to' => 'admin@nimr.or.tz',
        'from' => [
            'address' => 'backup@nimr.or.tz',
            'name' => 'NIMR Backup System',
        ],
    ],
],
```

---

## 📊 Monitoring & Maintenance

### System Monitoring

#### **Performance Monitoring**

**Key Metrics to Monitor:**
- Server resource utilization (CPU, RAM, Disk)
- Database performance and query times
- Application response times
- User session counts and activity
- File storage usage and growth
- Network bandwidth utilization

**Monitoring Tools Setup:**

**System Resource Monitoring:**
```bash
# Install monitoring tools
sudo apt install htop iotop nethogs

# Monitor system resources
htop                    # CPU and memory usage
iotop                   # Disk I/O monitoring
nethogs                 # Network usage by process
df -h                   # Disk space usage
```

**Database Performance Monitoring:**
```sql
-- Enable MySQL slow query log
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;
SET GLOBAL slow_query_log_file = '/var/log/mysql/slow.log';

-- Monitor database performance
SHOW PROCESSLIST;
SHOW ENGINE INNODB STATUS;
SELECT * FROM information_schema.INNODB_METRICS WHERE status = 'enabled';
```

#### **Application Monitoring**

**Laravel Application Monitoring:**
```bash
# Monitor application logs
tail -f storage/logs/laravel.log

# Monitor queue workers
php artisan queue:monitor

# Check application health
php artisan health:check

# Monitor cache performance
php artisan cache:monitor
```

**Custom Health Checks:**
```php
// app/Console/Commands/HealthCheck.php
class HealthCheck extends Command
{
    public function handle()
    {
        // Check database connectivity
        try {
            DB::connection()->getPdo();
            $this->info('Database: OK');
        } catch (Exception $e) {
            $this->error('Database: FAILED - ' . $e->getMessage());
        }

        // Check Redis connectivity
        try {
            Redis::ping();
            $this->info('Redis: OK');
        } catch (Exception $e) {
            $this->error('Redis: FAILED - ' . $e->getMessage());
        }

        // Check file storage
        if (Storage::disk('local')->exists('test')) {
            $this->info('Storage: OK');
        } else {
            $this->error('Storage: FAILED');
        }
    }
}
```

### Log Management

#### **Log Configuration**

**Laravel Logging Configuration:**
```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'slack'],
        'ignore_exceptions' => false,
    ],
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
    ],
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'NIMR Intranet',
        'emoji' => ':boom:',
        'level' => env('LOG_LEVEL', 'critical'),
    ],
],
```

**Log Rotation Setup:**
```bash
# /etc/logrotate.d/nimr-intranet
/var/www/nimr-intranet/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        /bin/systemctl reload php8.2-fpm > /dev/null 2>&1 || true
    endscript
}
```

#### **Log Analysis and Monitoring**

**Log Analysis Commands:**
```bash
# Monitor error logs in real-time
tail -f storage/logs/laravel.log | grep ERROR

# Count error types
grep ERROR storage/logs/laravel.log | cut -d' ' -f4 | sort | uniq -c

# Find slow queries
grep "slow query" /var/log/mysql/slow.log

# Monitor failed login attempts
grep "Failed login" storage/logs/laravel.log | tail -20
```

**Automated Log Analysis:**
```bash
#!/bin/bash
# /usr/local/bin/analyze-logs.sh

LOG_FILE="/var/www/nimr-intranet/storage/logs/laravel.log"
REPORT_FILE="/tmp/log-analysis-$(date +%Y%m%d).txt"

echo "NIMR Intranet Log Analysis - $(date)" > $REPORT_FILE
echo "========================================" >> $REPORT_FILE

# Count errors by type
echo "Error Summary:" >> $REPORT_FILE
grep ERROR $LOG_FILE | grep "$(date +%Y-%m-%d)" | cut -d']' -f3 | sort | uniq -c >> $REPORT_FILE

# Failed login attempts
echo -e "\nFailed Login Attempts:" >> $REPORT_FILE
grep "Failed login" $LOG_FILE | grep "$(date +%Y-%m-%d)" | wc -l >> $REPORT_FILE

# Performance issues
echo -e "\nSlow Queries:" >> $REPORT_FILE
grep "slow query" /var/log/mysql/slow.log | grep "$(date +%Y-%m-%d)" | wc -l >> $REPORT_FILE

# Email report
mail -s "NIMR Intranet Daily Log Analysis" admin@nimr.or.tz < $REPORT_FILE
```

### Maintenance Tasks

#### **Regular Maintenance Schedule**

**Daily Tasks:**
- Monitor system resources and performance
- Check application and error logs
- Verify backup completion
- Monitor user activity and system health

**Weekly Tasks:**
- Clean up temporary files and cache
- Analyze performance metrics
- Review security logs and alerts
- Update system packages (if needed)

**Monthly Tasks:**
- Full system backup verification
- Performance optimization review
- Security audit and vulnerability assessment
- User account cleanup and maintenance

**Quarterly Tasks:**
- System update planning and testing
- Disaster recovery testing
- Documentation review and updates
- Capacity planning and scaling assessment

#### **Automated Maintenance Scripts**

**Daily Maintenance Script:**
```bash
#!/bin/bash
# /usr/local/bin/daily-maintenance.sh

cd /var/www/nimr-intranet

# Clear expired sessions
php artisan session:gc

# Clean up temporary files
find storage/framework/cache -name "*.php" -mtime +7 -delete
find storage/logs -name "*.log" -mtime +30 -delete

# Optimize database
php artisan model:prune

# Clear expired password reset tokens
php artisan auth:clear-resets

# Generate daily report
php artisan report:daily-maintenance
```

**Weekly Maintenance Script:**
```bash
#!/bin/bash
# /usr/local/bin/weekly-maintenance.sh

cd /var/www/nimr-intranet

# Optimize database tables
php artisan db:optimize

# Clear and rebuild caches
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Update search indexes
php artisan scout:import "App\Models\Document"
php artisan scout:import "App\Models\Announcement"

# Generate weekly performance report
php artisan report:weekly-performance
```

### Performance Optimization

#### **Database Optimization**

**Query Optimization:**
```sql
-- Analyze slow queries
SELECT * FROM mysql.slow_log WHERE start_time > DATE_SUB(NOW(), INTERVAL 1 DAY);

-- Optimize tables
OPTIMIZE TABLE users, announcements, documents, messages;

-- Update table statistics
ANALYZE TABLE users, announcements, documents, messages;
```

**Index Optimization:**
```bash
# Generate index recommendations
php artisan db:analyze-indexes

# Create missing indexes
php artisan db:create-indexes
```

#### **Application Performance Optimization**

**Cache Optimization:**
```bash
# Optimize application caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize Composer autoloader
composer dump-autoload --optimize

# Enable OPcache
echo "opcache.enable=1" >> /etc/php/8.2/fpm/php.ini
echo "opcache.memory_consumption=256" >> /etc/php/8.2/fpm/php.ini
```

**Queue Optimization:**
```bash
# Monitor queue performance
php artisan queue:monitor

# Optimize queue workers
php artisan queue:restart

# Scale queue workers based on load
php artisan horizon:scale
```

---

## 🔧 Troubleshooting & Diagnostics

### Common Issues and Solutions

#### **Application Issues**

**Issue: Application Returns 500 Error**
```bash
# Check application logs
tail -f storage/logs/laravel.log

# Check web server error logs
sudo tail -f /var/log/nginx/error.log

# Check PHP-FPM logs
sudo tail -f /var/log/php8.2-fpm.log

# Common solutions:
# 1. Check file permissions
sudo chown -R www-data:www-data /var/www/nimr-intranet
sudo chmod -R 755 /var/www/nimr-intranet
sudo chmod -R 775 /var/www/nimr-intranet/storage

# 2. Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 3. Check environment configuration
php artisan config:show
```

**Issue: Database Connection Failed**
```bash
# Test database connectivity
php artisan tinker
>>> DB::connection()->getPdo();

# Check MySQL service status
sudo systemctl status mysql

# Check MySQL error logs
sudo tail -f /var/log/mysql/error.log

# Verify database credentials
mysql -u nimr_user -p nimr_intranet

# Common solutions:
# 1. Restart MySQL service
sudo systemctl restart mysql

# 2. Check MySQL configuration
sudo nano /etc/mysql/mysql.conf.d/mysqld.cnf

# 3. Verify user permissions
mysql -u root -p
>>> SHOW GRANTS FOR 'nimr_user'@'localhost';
```

**Issue: File Upload Failures**
```bash
# Check file upload limits
php -i | grep upload_max_filesize
php -i | grep post_max_size

# Check directory permissions
ls -la storage/app/
ls -la public/

# Check disk space
df -h

# Common solutions:
# 1. Increase upload limits
sudo nano /etc/php/8.2/fpm/php.ini
# upload_max_filesize = 20M
# post_max_size = 20M

# 2. Fix permissions
sudo chmod -R 775 storage/
sudo chown -R www-data:www-data storage/

# 3. Restart PHP-FPM
sudo systemctl restart php8.2-fpm
```

#### **Performance Issues**

**Issue: Slow Page Loading**
```bash
# Enable query logging
php artisan db:listen

# Monitor slow queries
sudo tail -f /var/log/mysql/slow.log

# Check system resources
htop
iotop

# Common solutions:
# 1. Enable caching
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 2. Optimize database
php artisan db:optimize

# 3. Enable OPcache
sudo nano /etc/php/8.2/fpm/php.ini
# opcache.enable=1
```

**Issue: High Memory Usage**
```bash
# Monitor memory usage
free -h
ps aux --sort=-%mem | head

# Check PHP memory limits
php -i | grep memory_limit

# Monitor application memory usage
php artisan monitor:memory

# Common solutions:
# 1. Increase PHP memory limit
sudo nano /etc/php/8.2/fpm/php.ini
# memory_limit = 512M

# 2. Optimize queries and reduce memory usage
php artisan optimize:memory

# 3. Restart services
sudo systemctl restart php8.2-fpm nginx
```

### Diagnostic Tools and Commands

#### **System Diagnostics**

**Health Check Command:**
```bash
# Create comprehensive health check
php artisan make:command SystemHealthCheck

# Run health check
php artisan system:health-check --verbose
```

**Performance Diagnostics:**
```bash
# Monitor system performance
php artisan monitor:performance --duration=300

# Generate performance report
php artisan report:performance --format=json

# Database performance analysis
php artisan db:analyze --slow-queries --table-sizes
```

#### **Debug Mode and Logging**

**Enable Debug Mode (Development Only):**
```env
APP_DEBUG=true
LOG_LEVEL=debug
```

**Advanced Logging Configuration:**
```php
// config/logging.php
'debug' => [
    'driver' => 'daily',
    'path' => storage_path('logs/debug.log'),
    'level' => 'debug',
    'days' => 7,
],
```

**Custom Debug Commands:**
```bash
# Debug specific user issues
php artisan debug:user {user_id}

# Debug permission issues
php artisan debug:permissions {user_id}

# Debug file access issues
php artisan debug:file-access {file_id}
```

### Emergency Procedures

#### **System Recovery Procedures**

**Emergency Maintenance Mode:**
```bash
# Enable maintenance mode with secret access
php artisan down --secret="emergency-access-2025"

# Access system during maintenance
https://intranet.nimr.or.tz/emergency-access-2025

# Disable maintenance mode
php artisan up
```

**Emergency User Access:**
```bash
# Create emergency admin user
php artisan make:user --role=super_admin --email=emergency@nimr.or.tz --password=TempPass123!

# Reset user password
php artisan user:reset-password {user_id} --password=NewPassword123!

# Disable compromised user account
php artisan user:disable {user_id} --reason="Security incident"
```

**System Rollback Procedures:**
```bash
# Rollback to previous application version
git checkout previous-stable-tag
composer install --no-dev
php artisan migrate:rollback --step=1
php artisan cache:clear

# Restore from backup
php artisan backup:restore --backup=latest --confirm
```

#### **Incident Response**

**Security Incident Response:**
```bash
# Lock down system immediately
php artisan down --message="System maintenance in progress"

# Disable all user sessions
php artisan session:flush

# Generate security incident report
php artisan security:incident-report --severity=high

# Enable audit logging
php artisan audit:enable --level=verbose
```

**Data Recovery Procedures:**
```bash
# Recover deleted records (if soft deletes enabled)
php artisan recover:deleted --model=Document --days=7

# Restore from point-in-time backup
php artisan backup:restore --timestamp="2025-01-15 14:30:00"

# Verify data integrity after recovery
php artisan verify:data-integrity
```

---

## 📞 Emergency Procedures

### Emergency Contacts

#### **Primary Contacts**
- **System Administrator**: admin@nimr.or.tz / +255-XXX-XXXX
- **IT Director**: itdirector@nimr.or.tz / +255-XXX-XXXX
- **Security Officer**: security@nimr.or.tz / +255-XXX-XXXX
- **Database Administrator**: dba@nimr.or.tz / +255-XXX-XXXX

#### **Escalation Procedures**
1. **Level 1**: System Administrator (Response: 1 hour)
2. **Level 2**: IT Director (Response: 2 hours)
3. **Level 3**: Executive Management (Response: 4 hours)
4. **External**: Vendor Support (Response: 8 hours)

### Emergency Response Procedures

#### **System Outage Response**
1. **Immediate Assessment** (0-15 minutes)
   - Verify outage scope and impact
   - Check system status and error logs
   - Notify stakeholders of outage

2. **Initial Response** (15-30 minutes)
   - Implement temporary workarounds if possible
   - Begin diagnostic procedures
   - Activate incident response team

3. **Resolution Efforts** (30 minutes - 4 hours)
   - Execute recovery procedures
   - Monitor system restoration progress
   - Provide regular status updates

4. **Post-Incident** (After resolution)
   - Conduct post-mortem analysis
   - Document lessons learned
   - Implement preventive measures

---

**Document Information**  
**Version**: 1.0.0  
**Last Updated**: January 2025  
**Maintained by**: NIMR IT Department  
**Next Review**: April 2025  
**Classification**: Internal Use Only

---

*This manual contains sensitive system administration information. Distribute only to authorized personnel and maintain appropriate security controls.*