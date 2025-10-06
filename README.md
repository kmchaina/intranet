# NIMR Intranet System

A comprehensive Laravel-based intranet system for the National Institute for Medical Research (NIMR) with role-based access control, messaging, announcements, and social features.

## 🚀 Features

### Core Features
- **Role-based Authentication** - Super Admin, HQ Admin, Centre Admin, Station Admin, Staff
- **Dashboard System** - Customized dashboards for each role
- **User Management** - Hierarchical user management with proper permissions
- **Email Verification** - Secure email verification system
- **East African Time** - All timestamps use EAT timezone

### Communication Features
- **Messaging System** - Direct messages and group conversations
- **Announcements** - Targeted announcements with file attachments
- **Birthday Wishes** - Social birthday celebration system with emojis and replies
- **News & Events** - Organization-wide news and event management

### Administrative Features
- **User Management** - Create, edit, and manage users by role
- **Organizational Structure** - Manage headquarters, centres, and stations
- **Reports & Analytics** - Comprehensive reporting system
- **Activity Logging** - Track user activities and system events
- **Policy Management** - Manage system policies and permissions

### UI/UX Features
- **Modern Design** - Clean, professional interface with Tailwind CSS
- **Role-based Themes** - Different color schemes for each role
- **Responsive Design** - Works on desktop, tablet, and mobile
- **Interactive Elements** - Alpine.js for dynamic interactions

## 🛠️ Installation

### Prerequisites
- PHP 8.1+
- Composer
- Node.js & NPM
- MySQL/PostgreSQL
- Laravel 10+

### Setup Instructions

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd intranet
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**
   ```bash
   php artisan migrate:fresh --seed
   ```

6. **Create super admin user**
   ```bash
   php artisan db:seed --class=MinimalSeeder
   ```

7. **Build assets**
   ```bash
   npm run build
   ```

8. **Start the server**
   ```bash
   php artisan serve
   ```

## 👤 Default Login

After running the MinimalSeeder, you can log in with:

- **Email**: `admin@nimr.or.tz`
- **Password**: `password`
- **Role**: Super Admin

## 🏗️ System Architecture

### Role Hierarchy
```
Super Admin
├── HQ Admin
│   ├── Centre Admin
│   │   ├── Station Admin
│   │   └── Staff
│   └── Staff
└── Staff
```

### Key Components

#### Models
- `User` - User management with role-based permissions
- `Announcement` - Announcement system with targeting
- `Conversation` - Messaging system
- `BirthdayWish` - Social birthday celebration system
- `Event` - Event management
- `News` - News system

#### Controllers
- `DashboardController` - Role-based dashboard logic
- `AnnouncementController` - Announcement management
- `ConversationController` - Messaging system
- `BirthdayController` - Birthday wishes and celebrations
- `UserAdminController` - User management

#### Policies
- `AnnouncementPolicy` - Announcement permissions
- `ConversationPolicy` - Messaging permissions
- `PollPolicy` - Poll permissions

## 🎨 Design System

### Color Themes by Role
- **Staff**: Indigo/Purple gradient
- **Station Admin**: Cyan/Blue gradient
- **Centre Admin**: Violet/Magenta gradient
- **HQ Admin**: Slate/Gray gradient
- **Super Admin**: Amber/Gold gradient

### Components
- **Cards**: Premium card design with gradients
- **Buttons**: Gradient buttons with hover effects
- **Forms**: Clean, accessible form design
- **Navigation**: Role-based sidebar navigation

## 📱 Features Overview

### Messaging System
- Direct messages between users
- Group conversations
- File attachments
- Real-time updates
- Self-messaging allowed

### Birthday Wishes
- Emoji reactions (🎉 🎂 ❤️ 🥳 🎁 👏 🔥 💯)
- Threaded replies
- Public/private wishes
- Celebration tracking

### Announcements
- Role-based targeting
- File attachments (PDF, DOC, images)
- Scheduled publishing
- Read tracking
- Document preview

### User Management
- Hierarchical permissions
- Email verification
- Role-based access
- Activity logging

## 🔧 Configuration

### Environment Variables
```env
APP_NAME="NIMR Intranet"
APP_TIMEZONE="Africa/Nairobi"
DB_CONNECTION=mysql
MAIL_MAILER=smtp
```

### Theme Configuration
Themes are configured in `config/theme.php` with role-specific colors and settings.

### Navigation Configuration
Sidebar navigation is configured in `config/navigation.php` with role-based menu items.

## 🧪 Testing

Run the test suite:
```bash
php artisan test
```

Key test files:
- `AuthenticationTest.php` - Authentication and authorization
- `AnnouncementManagementTest.php` - Announcement functionality
- `SidebarSmokeTest.php` - UI component testing

## 📊 Database Schema

### Key Tables
- `users` - User accounts with role hierarchy
- `announcements` - Announcement system
- `conversations` - Messaging system
- `birthday_wishes` - Birthday celebration system
- `events` - Event management
- `news` - News system

### Migrations
All database changes are tracked in the `database/migrations` directory.

## 🚀 Deployment

### Production Setup
1. Set up production environment variables
2. Configure database connection
3. Set up email service
4. Configure file storage
5. Run migrations and seeders
6. Build production assets

### Performance Optimization
- Database indexes for performance
- Caching for frequently accessed data
- Asset optimization
- Query optimization

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## 📄 License

This project is proprietary software for the National Institute for Medical Research (NIMR).

## 🆘 Support

For support and questions, contact the development team or create an issue in the repository.

---

**Built with ❤️ for NIMR**