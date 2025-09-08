# NIMR Intranet System

**National Institute for Medical Research - Internal Communication Platform**

## 🎯 System Overview

A modern, high-performance intranet system built with Laravel 12.x and MySQL, designed to handle 200+ concurrent users with enterprise-grade security and scalability.

### ✨ Key Features
- **📢 Announcements & News Management** - Multi-level organizational communication
- **📁 Document Management** - Secure file storage with role-based access
- **📅 Event Management** - Schedule and track organizational events  
- **🗳️ Polls & Feedback** - Gather organizational insights
- **� User Hierarchy Management** - Support for HQ → Centres → Stations → Departments
- **🔐 Advanced Security** - Role-based permissions with 5 access levels
- **⚡ High Performance** - MySQL database with optimized indexes
- **📱 Responsive Design** - Mobile-friendly interface

## 🏗️ Architecture

- **Backend**: Laravel 12.x (PHP 8.2+)
- **Database**: MySQL/MariaDB with performance optimizations
- **Frontend**: Tailwind CSS + Alpine.js
- **Caching**: File-based caching (Redis-ready)
- **Security**: Multi-layer authentication & authorization

## �📚 Documentation

All project documentation is located in the [`documentation/`](documentation/) folder.

**Quick Links:**
- 📋 [Complete Documentation Index](documentation/INDEX.md)
- 🚀 [Developer Setup Guide](documentation/README.md)
- 👤 [User Guide](documentation/USER_GUIDE.md)
- ⚙️ [Administrator Manual](documentation/ADMINISTRATOR_MANUAL.md)
- 🔧 [Enhancement Recommendations](ENHANCEMENT_RECOMMENDATIONS.md)
- 🗄️ [Database Migration Guide](DATABASE_MIGRATION_GUIDE.md)

## 🚀 Quick Start

### Prerequisites
- PHP 8.2+
- MySQL 8.0+ or MariaDB 10.4+
- Composer
- Node.js & NPM

### Installation

```bash
# 1. Clone the repository
git clone <repository-url>
cd intranet

# 2. Install dependencies
composer install
npm install

# 3. Environment setup
cp .env.example .env
php artisan key:generate

# 4. Database setup
# Update .env with your database credentials:
# DB_CONNECTION=mysql
# DB_DATABASE=intranet
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# 5. Run migrations
php artisan migrate --seed

# 6. Build assets
npm run build

# 7. Start the server
php artisan serve
```

### Production Deployment

See [Database Migration Guide](DATABASE_MIGRATION_GUIDE.md) for migrating from SQLite to MySQL.

## 🔧 Configuration

### Database Configuration
The system is optimized for MySQL/MariaDB with performance indexes. Key configurations:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=intranet
DB_USERNAME=root
DB_PASSWORD=
```

### Performance Optimizations
- Database indexes on frequently queried columns
- File-based caching for improved response times
- Optimized Laravel configuration for production

## 👥 User Roles & Permissions

1. **Super Admin** - Full system access
2. **HQ Admin** - Headquarters-level management
3. **Centre Admin** - Centre-level management  
4. **Station Admin** - Station-level management
5. **Staff** - Basic user access

## 🛠️ Development

### Running Tests
```bash
php artisan test
```

### Database Operations
```bash
# Add performance indexes
php artisan db:add-indexes

# Migrate from SQLite to MySQL
php artisan migrate:from-sqlite --backup --verify
```

## 📈 Performance Features

- **MySQL Database** - Enterprise-grade performance and reliability
- **Optimized Indexes** - Fast queries on user roles, announcements, hierarchical data
- **Efficient Caching** - Reduced database load with intelligent caching
- **Query Optimization** - Minimized N+1 queries with eager loading

## 🔒 Security Features

- **Role-Based Access Control (RBAC)** - Granular permissions system
- **CSRF Protection** - All forms protected against cross-site request forgery
- **Rate Limiting** - API and form submission rate limiting
- **Secure File Uploads** - File type validation and security scanning
- **Password Security** - Strong password requirements and hashing

## 📞 Support

- **Help Desk**: helpdesk@nimr.or.tz
- **IT Support**: itsupport@nimr.or.tz  
- **Documentation**: See [`documentation/`](documentation/) folder

## 🚀 Recent Improvements

### Version 2.1.0 (September 2025)
- ✅ **Database Migration**: Migrated from SQLite to MySQL for better performance
- ✅ **Performance Optimization**: Added database indexes for 3-5x query speed improvement
- ✅ **Enhanced Security**: Implemented rate limiting and improved authentication
- ✅ **API Enhancement**: Added versioned REST API with proper resource handling
- ✅ **Code Quality**: Comprehensive test suite and improved error handling

---

**Version**: 2.1.0 | **Laravel**: 12.x | **Database**: MySQL | **Status**: Production Ready ✅
