# NIMR Intranet System

A comprehensive intranet system built for the National Institute for Medical Research (NIMR), Tanzania. This Laravel-based platform provides hierarchy-aware features, dynamic organizationa#### 💬 2.4 Internal Chat System

> **⚠️ COMPLEX FEATURE - Requires WebSocket/Real-time Setup**
>
> This feature involves real-time messaging and will require:agement, and engaging UI/UX for internal communications and document management.

## 🏛️ About NIMR

The National Institute for Medical Research (NIMR) is Tanzania's premier medical research institution with a hierarchical structure consisting of:

-   **Headquarters** (Dar es Salaam) - Central administrative body
-   **Research Centres** - Regional research hubs
-   **Research Stations** - Field research facilities under specific centres

## 🎯 Project Overview

This intranet system is designed to support NIMR's organizational structure with:

-   **Hierarchy-aware features** - Content and announcements targeted by organizational level
-   **Dynamic organizational management** - Real-time centre and station selection
-   **Secure authentication** - Email domain restriction (@nimr.or.tz)
-   **Modern UI/UX** - Built with Laravel Breeze and Tailwind CSS

## 🏗️ Technical Stack

-   **Backend**: Laravel 12
-   **Frontend**: Blade Templates with Tailwind CSS
-   **Database**: SQLite (development) / MySQL (production)
-   **Authentication**: Laravel Breeze with email verification
-   **JavaScript**: Vanilla JS with AJAX for dynamic interactions

## 📋 Development Roadmap

### ✅ **PHASE 1: FOUNDATION & AUTHENTICATION** (COMPLETED)

#### ✅ 1.1 Project Setup

-   [x] Laravel 12 installation and configuration
-   [x] Tailwind CSS integration
-   [x] Laravel Breeze authentication setup
-   [x] Database configuration (SQLite for development)

#### ✅ 1.2 Database Schema Design

-   [x] Headquarters table (NIMR central administration)
-   [x] Centres table (Research centres under headquarters)
-   [x] Stations table (Research stations under centres)
-   [x] Departments table (Flexible hierarchy placement)
-   [x] Users table with organizational hierarchy foreign keys
-   [x] Migration files with proper foreign key constraints

#### ✅ 1.3 Eloquent Models & Relationships

-   [x] Headquarters model with hasMany centres
-   [x] Centre model with belongsTo headquarters, hasMany stations
-   [x] Station model with belongsTo centre
-   [x] Department model with flexible hierarchy placement
-   [x] User model with organizational hierarchy relationships

#### ✅ 1.4 Authentication System

-   [x] Email domain restriction (@nimr.or.tz)
-   [x] Email verification requirement
-   [x] Custom registration form with organizational hierarchy
-   [x] Dynamic centre/station selection via AJAX
-   [x] Simplified registration (removed unnecessary fields)

#### ✅ 1.5 Organizational Data Seeding

-   [x] NIMR Headquarters creation
-   [x] Accurate Research Centres:
    -   Amani Research Centre
    -   Dodoma Research Centre
    -   Mabibo Traditional Medicine Centre
    -   Mbeya Research Centre
    -   Muhimbili Research Centre
    -   Mwanza Research Centre
    -   Tanga Research Centre
-   [x] Accurate Research Stations:
    -   Amani Hill Station (under Amani Centre)
    -   Gonja Station (under Amani Centre)
    -   Ngongongare Research Station (under Mabibo Centre)
    -   Tukuyu Research Station (under Mbeya Centre)
    -   Korogwe Station (under Tanga Centre)
    -   Tabora Research Station (semi-independent under HQ)

#### ✅ 1.6 Registration Flow Enhancement

-   [x] "Where are you situated?" question for centres with stations
-   [x] Direct station name display (no generic "At one of the stations")
-   [x] Validation logic for organizational hierarchy
-   [x] Proper form state management with old values

---

### 🚧 **PHASE 2: CORE FEATURES** (IN PROGRESS)

#### ✅ 📢 2.1 Announcements System (COMPLETED)

-   [x] **Database Design**

    -   [x] Announcements table with hierarchy targeting
    -   [x] Target audience fields (headquarters, centres, stations)
    -   [x] Priority levels and categories
    -   [x] Read status tracking per user

-   [x] **Backend Implementation**

    -   [x] AnnouncementController with CRUD operations
    -   [x] Hierarchy-aware content filtering
    -   [x] Permission system for announcement creation
    -   [x] Email notification field (logic pending)

-   [x] **Frontend Implementation**
    -   [x] Announcements dashboard with cards layout
    -   [x] Create/Edit announcement forms
    -   [x] Target audience selection interface
    -   [x] Read/Unread status indicators
    -   [x] Search and filter functionality
    -   [x] Professional boxed layout with proper contrast

#### ✅ 📁 2.2 Document Management System (COMPLETED)

-   [x] **Database Design**

    -   [x] Documents table with hierarchy scoping
    -   [x] File storage and versioning
    -   [x] Access permissions by organizational level
    -   [x] Document categories and tags

-   [x] **Backend Implementation**

    -   [x] DocumentController with upload/download functionality
    -   [x] File storage using Laravel's filesystem
    -   [x] Access control middleware and permissions system
    -   [x] Document versioning system
    -   [x] User permission methods (canUpdateDocument, canDeleteDocument, canDownloadDocument)
    -   [x] Document model helper methods (getFileSize)

-   [x] **Frontend Implementation**
    -   [x] Document library interface with professional styling
    -   [x] **Grid and List view toggle** with localStorage persistence
    -   [x] **List view set as default** for better mobile experience
    -   [x] Advanced filtering (search, category, access level, tags)
    -   [x] **Document preview system** supporting:
        -   [x] PDF embedded preview
        -   [x] Image display (JPG, PNG, GIF, BMP, WebP)
        -   [x] Text file content preview (TXT, MD, LOG)
        -   [x] Video/Audio players (MP4, MP3, etc.)
        -   [x] Office documents via Google Docs viewer
        -   [x] Fallback for unsupported file types
    -   [x] **Mobile-optimized layout** with compact headers
    -   [x] Keyboard shortcuts (Alt+G for grid, Alt+L for list)
    -   [x] Upload forms with drag-and-drop (existing)
    -   [x] Document details view with metadata and actions

#### ✅ 🎉 2.3 Workplace Productivity Features (PARTIALLY COMPLETED)

> **🏢 Features to enhance workplace culture and employee engagement**

**✅ COMPLETED FEATURES:**

-   [x] **🎂 Birthday Celebrations System**

    -   [x] User birthday tracking with privacy controls
    -   [x] Monthly birthday calendar display
    -   [x] Birthday visibility settings (public/department/private)
    -   [x] Dashboard integration with today's birthdays
    -   [x] Automatic birthday detection and notifications

-   [x] **� Quick Polls System**
    -   [x] Multiple poll types (single choice, multiple choice, rating, yes/no)
    -   [x] Anonymous voting capability
    -   [x] Real-time results and analytics
    -   [x] Visibility controls (public, department-specific, custom users)
    -   [x] Poll scheduling with start/end dates
    -   [x] Comment system for polls
    -   [x] Results export (CSV) and printing functionality
    -   [x] Dashboard layout integration
    -   [x] Comprehensive poll management (draft, active, closed, archived)

**📋 PENDING FEATURES (For Future Implementation):**

-   [ ] **🏢 Resource Booking System**

    -   [ ] Meeting room reservations
    -   [ ] Equipment booking (projectors, vehicles, lab equipment)
    -   [ ] Calendar integration with booking schedules
    -   [ ] Conflict detection and availability checking
    -   [ ] Email notifications for bookings
    -   [ ] Recurring booking support

-   [ ] **📈 Project Tracker**

    -   [ ] Task management with assignments
    -   [ ] Project timelines and milestones
    -   [ ] Team collaboration features
    -   [ ] Progress tracking and reporting
    -   [ ] Gantt chart visualization
    -   [ ] File attachments to tasks/projects

-   [ ] **📚 Knowledge Base**

    -   [ ] How-to guides and procedures repository
    -   [ ] Searchable knowledge articles
    -   [ ] Category-based organization
    -   [ ] Version control for knowledge articles
    -   [ ] User contribution and editing system
    -   [ ] Integration with existing document system

-   [ ] **🏆 Employee Recognition System**
    -   [ ] Peer nomination system
    -   [ ] Achievement badges and awards
    -   [ ] Recognition wall/feed
    -   [ ] Monthly recognition reports
    -   [ ] Integration with performance management
    -   [ ] Public appreciation posts

#### �💬 2.4 Internal Chat System

> **⚠️ COMPLEX FEATURE - Requires WebSocket/Real-time Setup**
>
> This feature involves real-time messaging and will require:
>
> -   Laravel Echo + Pusher/Socket.io setup
> -   WebSocket server configuration
> -   Real-time frontend JavaScript implementation
> -   Comprehensive testing for real-time functionality

-   [ ] **Prerequisites & Setup**

    -   [ ] Install and configure Laravel Echo
    -   [ ] Set up Pusher or Socket.io for WebSocket connections
    -   [ ] Configure broadcasting drivers
    -   [ ] Test real-time connection functionality

-   [ ] **Database Design**

    -   [ ] Conversations table (1-on-1 and group chats)
    -   [ ] Messages table with user relationships
    -   [ ] Message participants/members table
    -   [ ] Message read status tracking
    -   [ ] Message types (text, file, system notifications)
    -   [ ] Group chats by organizational units (HQ, Centre, Station level)
    -   [ ] Message status and timestamps

-   [ ] **Backend Implementation**

    -   [ ] ChatController for message CRUD operations
    -   [ ] ConversationController for chat management
    -   [ ] Real-time message broadcasting with Laravel Echo
    -   [ ] Group chat creation and management
    -   [ ] Message history and search functionality
    -   [ ] File sharing within chats
    -   [ ] Permission system (who can chat with whom)
    -   [ ] Message encryption for sensitive communications

-   [ ] **Frontend Implementation**

    -   [ ] Chat interface with real-time updates
    -   [ ] Conversation list sidebar
    -   [ ] Message composer with file upload
    -   [ ] Group creation and member management
    -   [ ] Message notifications (browser notifications)
    -   [ ] Online/offline status indicators
    -   [ ] Message search and filtering
    -   [ ] Mobile-responsive chat interface
    -   [ ] Emoji support and message reactions

-   [ ] **Advanced Features (Future)**
    -   [ ] Message threading/replies
    -   [ ] Voice messages
    -   [ ] Video calling integration
    -   [ ] Chat moderation tools
    -   [ ] Message scheduling

---

### 🔧 **PHASE 3: ADVANCED FEATURES** (PLANNED)

> **💡 ALTERNATIVE: Consider starting here if Section 2.3 (Chat) is too complex**

#### 📊 3.1 Dashboard & Analytics

-   [ ] **Homepage Dashboard**

    -   [ ] Recent announcements widget
    -   [ ] Quick access to documents
    -   [ ] Document library stats (total files, recent uploads)
    -   [ ] Organizational chart display
    -   [ ] Personal notifications center
    -   [ ] Quick search across all content
    -   [ ] Weather widget (for field research stations)
    -   [ ] Calendar integration for events

-   [ ] **Analytics for Administrators**
    -   [ ] User engagement metrics
    -   [ ] Content interaction statistics (document downloads, announcement views)
    -   [ ] System usage reports
    -   [ ] Popular documents and announcements
    -   [ ] User activity logs

#### 👥 3.2 User Management Enhancement

-   [ ] **Profile Management**

    -   [ ] Extended user profiles with bio and expertise
    -   [ ] Profile picture uploads
    -   [ ] Contact information management
    -   [ ] Research interests and publications
    -   [ ] Organizational directory/phone book

-   [ ] **Administrative Features**
    -   [ ] User role management interface
    -   [ ] Bulk user operations (import/export)
    -   [ ] Organizational chart updates
    -   [ ] User onboarding workflows
    -   [ ] Permission management dashboard

#### 📱 3.3 Mobile Responsiveness Enhancement

-   [ ] **Advanced Mobile Features**
    -   [ ] Progressive Web App (PWA) setup
    -   [ ] Offline functionality for announcements
    -   [ ] Push notifications setup
    -   [ ] Mobile app icons and splash screens
    -   [ ] Touch gesture optimizations

#### 🔍 3.4 Search & Discovery

-   [ ] **Global Search System**
    -   [ ] Unified search across announcements and documents
    -   [ ] Advanced search filters
    -   [ ] Search result highlighting
    -   [ ] Search suggestions and autocomplete
    -   [ ] Save search queries

#### 📅 3.5 Events & Calendar System

-   [ ] **Event Management**
    -   [ ] Create and manage organizational events
    -   [ ] Event calendar view (monthly, weekly, daily)
    -   [ ] RSVP functionality with attendance tracking
    -   [ ] Event notifications and reminders
    -   [ ] Integration with announcements
    -   [ ] Recurring events support
    -   [ ] Event categories (meetings, training, conferences)
    -   [ ] Venue and resource booking

#### 🔐 3.6 Personal Password Vault

-   [ ] **Secure Password Storage**
    -   [ ] Encrypted password storage per user
    -   [ ] Categories for passwords (work, personal, research tools)
    -   [ ] Password generator with customizable rules
    -   [ ] Secure sharing between team members
    -   [ ] Password expiration reminders
    -   [ ] Two-factor authentication for vault access
    -   [ ] Password strength analysis
    -   [ ] Import/export functionality

#### 📝 3.7 Personal To-Do List & Task Management

-   [ ] **Notion-style Task Management**
    -   [ ] Personal to-do lists with priorities
    -   [ ] Task categories and tags
    -   [ ] Due dates and reminders
    -   [ ] Progress tracking (Not Started, In Progress, Completed)
    -   [ ] Task notes and attachments
    -   [ ] Recurring tasks
    -   [ ] Task templates for common workflows
    -   [ ] Team collaboration on shared tasks
    -   [ ] Time tracking per task
    -   [ ] Task analytics and productivity insights

---

### 🎯 **CURRENT IMPLEMENTATION PLAN:**

> **Phase A: Events & Calendar System (3.5)** - Foundation for organizational coordination
>
> **Phase B: Password Vault (3.6)** - Security and productivity tool
>
> **Phase C: Personal To-Do Lists (3.7)** - Individual productivity enhancement
>
> **Phase D: User vs Admin Dashboards** - Differentiated experiences based on role

---

### 🚀 **PHASE 4: DEPLOYMENT & OPTIMIZATION** (PLANNED)

#### 🌐 4.1 Production Deployment

-   [ ] **Server Configuration**

    -   [ ] Production server setup
    -   [ ] MySQL database configuration
    -   [ ] SSL certificate installation
    -   [ ] Domain configuration

-   [ ] **Performance Optimization**
    -   [ ] Database query optimization
    -   [ ] Caching implementation
    -   [ ] File compression and CDN setup

#### 🔒 4.2 Security Enhancements

-   [ ] **Security Auditing**
    -   [ ] Vulnerability assessment
    -   [ ] Rate limiting implementation
    -   [ ] Backup and recovery procedures

---

## 🏢 NIMR Organizational Structure

### Current Implementation:

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

## 🔧 Installation & Setup

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

4. **Database Setup**

    ```bash
    php artisan migrate:fresh --seed
    ```

5. **Development Server**
    ```bash
    php artisan serve
    npm run dev
    ```

## 📝 Developer Notes

### Current Implementation Status:

-   ✅ **Authentication**: Fully functional with email domain restriction
-   ✅ **Registration**: Simplified with accurate organizational hierarchy
-   ✅ **Database**: Properly seeded with real NIMR structure
-   ✅ **Announcements System**: Completed with hierarchy-aware targeting
-   ✅ **Document Management**: Completed with preview system and mobile optimization
-   ✅ **Birthday Celebrations**: Completed with privacy controls and dashboard integration
-   ✅ **Quick Polls System**: Completed with comprehensive voting and analytics
-   🎯 **Next Priority**: Dashboard implementation and remaining workplace productivity features

### Key Design Decisions:

1. **Email Domain Restriction**: Only @nimr.or.tz emails allowed
2. **Organizational Levels**: Headquarters → Centres → Stations (no "Station" as org level)
3. **Station Selection**: Shows actual station names, not generic options
4. **Database**: SQLite for development, MySQL for production

### Known Issues:

-   None currently identified

### Future Considerations:

-   Consider implementing role-based permissions
-   Plan for multi-language support (English/Swahili)
-   Evaluate need for API endpoints for mobile app integration

---

## 👨‍💻 For New Developers

If you're taking over this project:

1. **Start Here**: Review this roadmap and the current implementation status
2. **Next Task**: Implement the Announcements System (Phase 2.1)
3. **Code Structure**: Follow Laravel conventions and existing patterns
4. **Testing**: Ensure all features work with the NIMR organizational hierarchy
5. **Documentation**: Update this README as you complete tasks

### Quick Commands:

```bash
# Reset database with fresh data
php artisan migrate:fresh --seed

# Check routes
php artisan route:list

# Run tests
php artisan test
```

---

_Last Updated: September 3, 2025_
_Current Developer: GitHub Copilot (AI Assistant)_

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
