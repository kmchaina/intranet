# NIMR Intranet System

A comprehensive intranet system built for the National Institute for Medical Research (NIMR), Tanzania. This Laravel-based platform provides hierarchy-aware features, role-based access control, and modern UI/UX for internal communications and document management.

![Laravel](https://img.shields.io/badge/Laravel-12-red.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-blue.svg)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3.x-blueviolet.svg)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-green.svg)
![Production Ready](https://img.shields.io/badge/Status-Production_Ready-brightgreen.svg)

## 🏛️ About NIMR

The National Institute for Medical Research (NIMR) is Tanzania's premier medical research institution with a hierarchical structure consisting of:

- **Headquarters** (Dar es Salaam) - Central administrative body
- **Research Centres** - Regional research hubs across Tanzania
- **Research Stations** - Field research facilities under specific centres

## 🎯 System Overview

This intranet system supports NIMR's organizational structure with:

- **📊 Hierarchy-aware features** - Content and announcements targeted by organizational level
- **🔐 Role-based access control** - Five-tier permission system
- **🎨 Modern UI/UX** - Built with Laravel Breeze, Tailwind CSS, and Alpine.js
- **🔒 Secure authentication** - Email domain restriction (@nimr.or.tz)
- **📱 Mobile-responsive design** - Optimized for desktop and mobile use
- **🔍 Advanced search** - Unified search across all content types
- **📁 Document management** - Department-based document organization

## 🏗️ Technical Stack

- **Backend**: Laravel 12 with PHP 8.1+
- **Frontend**: Blade Templates + Tailwind CSS + Alpine.js
- **Database**: SQLite (development) / MySQL (production)
- **Authentication**: Laravel Breeze with email verification
- **File Storage**: Laravel Filesystem
- **Build Tools**: Vite + PostCSS

## ✨ Key Features

### 🔐 **Authentication & User Management**
- Email domain restriction (@nimr.or.tz only)
- Five-tier role-based access control:
  - **Super Admin** - Complete system control
  - **HQ Admin** - Organization-wide management
  - **Centre Admin** - Centre-specific management
  - **Station Admin** - Station-level management
  - **Staff** - Content access and personal tools

### 📢 **Announcements System**
- Hierarchy-aware content distribution
- Priority levels and expiration dates
- Target audience selection (All, Centre-specific, Station-specific)
- Read/Unread status tracking
- Mobile-optimized interface

### 📁 **Document Management**
- **Two-view architecture**:
  - Department overview with visual cards
  - Filtered document browsing
- Department-based organization (HR, IT, R&D, Finance, Admin, Training)
- Document statistics and quick access
- Preview system (PDF, images, videos, Office docs)
- Grid and list view options
- Admin-only upload functionality

### 🔍 **Global Search**
- Unified search across all content types
- Auto-suggest with real-time results
- Hierarchy-aware filtering
- Mobile-responsive search interface

### 🎉 **Workplace Features**
- Birthday celebrations system
- Quick polls and surveys
- News and updates feed
- Password vault (personal)
- Task management (personal)

### 🏢 **Role-Based Dashboards**
- **Staff Dashboard** - Personal productivity focus
- **Station Admin Dashboard** - Local management tools
- **Centre Admin Dashboard** - Centre-wide oversight
- **HQ Admin Dashboard** - Organizational management
- **Super Admin Dashboard** - Complete system control

## 🏢 NIMR Organizational Structure

```
NIMR Headquarters (Dar es Salaam)
├── Amani Research Centre
│   ├── Amani Hill Station
│   └── Gonja Station
├── Dodoma Research Centre
├── Mabibo Traditional Medicine Centre
│   └── Ngongongare Research Station
├── Mbeya Research Centre
│   └── Tukuyu Research Station
├── Muhimbili Research Centre
├── Mwanza Research Centre
├── Tanga Research Centre
│   └── Korogwe Station
└── Tabora Research Station (Semi-independent)
```

## 🚀 Quick Start

### Prerequisites
- PHP 8.1 or higher
- Composer
- Node.js & npm
- SQLite (development) or MySQL (production)

### Installation

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd intranet
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure Environment**
   Edit `.env` file:
   ```env
   DB_CONNECTION=sqlite
   # or for production:
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nimr_intranet
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   
   MAIL_MAILER=smtp
   MAIL_HOST=your_smtp_host
   MAIL_PORT=587
   MAIL_USERNAME=your_email
   MAIL_PASSWORD=your_password
   ```

5. **Database Setup**
   ```bash
   # Fresh installation with sample data
   php artisan migrate:fresh --seed
   ```

6. **Build Assets**
   ```bash
   npm run build
   ```

7. **Start Development Server**
   ```bash
   php artisan serve
   ```

8. **Set Up File Storage (Production)**
   ```bash
   php artisan storage:link
   ```

## 👥 Test Accounts

The system includes pre-seeded test accounts for each role:

| Role | Email | Password | Location | Access Level |
|------|-------|----------|----------|--------------|
| Super Admin | admin@nimr.or.tz | password | HQ | Full System |
| HQ Admin | finance@nimr.or.tz | password | HQ | Organization-wide |
| Centre Admin | sarah.mmbando@nimr.or.tz | password | Amani Centre | Centre Management |
| Station Admin | frank.sadelwa@nimr.or.tz | password | Amani Hill Station | Station Management |
| Staff | emmanuel.kaindoa@nimr.or.tz | password | Amani Centre | Content Access |

## 🔧 Configuration

### Email Domain Restriction
The system restricts registration to @nimr.or.tz email addresses. To modify:
1. Edit `app/Http/Requests/Auth/RegisterRequest.php`
2. Update the email validation rule

### Adding New Centres/Stations
1. Use the admin interface (recommended)
2. Or add via database seeder in `database/seeders/OrganizationSeeder.php`

### Customizing Access Levels
Modify access control logic in:
- `app/Models/User.php` - User permission methods
- `app/Http/Controllers/DashboardController.php` - Dashboard access
- Individual feature controllers for content filtering

## 📊 Admin Commands

```bash
# Reset database with fresh data
php artisan migrate:fresh --seed

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan route:clear
php artisan config:clear

# Check system status
php artisan about
```

## 🧪 Testing the System

1. **Login with different user roles** to test hierarchy-aware content
2. **Test document management** with the new department-based interface
3. **Verify role-based dashboard access** for each user type
4. **Check announcement targeting** across organizational levels
5. **Test search functionality** across content types

## 🚀 Production Deployment

### Server Requirements
- PHP 8.1+ with required extensions
- MySQL 8.0+ or equivalent
- Web server (Apache/Nginx)
- SSL certificate
- Sufficient storage for file uploads

### Deployment Steps

1. **Server Setup**
   ```bash
   sudo apt update
   sudo apt install php8.1 mysql-server nginx
   sudo apt install php8.1-mysql php8.1-xml php8.1-curl php8.1-zip
   ```

2. **Application Deployment**
   ```bash
   git clone <repository> /var/www/nimr-intranet
   cd /var/www/nimr-intranet
   composer install --optimize-autoloader --no-dev
   npm install && npm run build
   sudo chown -R www-data:www-data storage bootstrap/cache
   sudo chmod -R 775 storage bootstrap/cache
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   # Configure database and mail settings in .env
   ```

4. **Database Setup**
   ```bash
   php artisan migrate
   php artisan db:seed --class=OrganizationSeeder
   ```

### Security Considerations
- Enable HTTPS/SSL
- Configure firewall rules
- Set up regular database backups
- Implement log monitoring
- Configure rate limiting
- Regular security updates

## 📋 Maintenance

### Regular Tasks
- Database backups
- Log file rotation (`storage/logs/laravel.log`)
- Cache clearing
- Security updates
- User access reviews

### Monitoring
- Application logs: `storage/logs/laravel.log`
- Monitor disk space for file uploads
- Database performance monitoring
- SSL certificate expiration

## 🛠️ Development

### File Structure
```
app/
├── Http/Controllers/     # Controllers for all features
├── Models/              # Eloquent models
├── Policies/            # Authorization policies
resources/
├── views/
│   ├── dashboard/       # Role-specific dashboards
│   ├── documents/       # Document management
│   ├── announcements/   # Announcement system
│   └── layouts/         # Layout templates
database/
├── migrations/          # Database migrations
└── seeders/            # Database seeders
```

### Key Models
- `User.php` - User management with hierarchy
- `Document.php` - Document management
- `Announcement.php` - Announcement system
- `Centre.php` & `Station.php` - Organizational structure

### Contributing
1. Fork the repository
2. Create feature branch (`git checkout -b feature/new-feature`)
3. Commit changes (`git commit -m 'Add new feature'`)
4. Push to branch (`git push origin feature/new-feature`)
5. Create Pull Request

## 📜 License

This project is proprietary software developed for NIMR. Unauthorized distribution is prohibited.

## 📞 Support

For technical support or feature requests:
- **Internal**: Contact IT Department
- **Development**: Create issue in repository
- **Emergency**: Contact system administrator

---

**Version**: 2.0.0  
**Last Updated**: September 8, 2025  
**Status**: Production Ready  
**Developed for**: National Institute for Medical Research (NIMR), Tanzania
