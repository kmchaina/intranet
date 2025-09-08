# Software Design Document (SDD)
## NIMR Intranet System

**Document Version**: 2.0.0  
**Date**: September 8, 2025  
**Project**: NIMR Intranet Management System  
**Client**: National Institute for Medical Research (NIMR)  
**Development Team**: NIMR IT Department  

---

## Table of Contents

1. [Introduction](#1-introduction)
2. [System Overview](#2-system-overview)
3. [System Architecture](#3-system-architecture)
4. [Detailed Design](#4-detailed-design)
5. [Database Design](#5-database-design)
6. [User Interface Design](#6-user-interface-design)
7. [Security Design](#7-security-design)
8. [Performance Considerations](#8-performance-considerations)
9. [Deployment Architecture](#9-deployment-architecture)
10. [Appendices](#10-appendices)

---

## 1. Introduction

### 1.1 Purpose
This Software Design Document (SDD) provides a comprehensive description of the design and architecture of the NIMR Intranet System. It serves as a blueprint for developers, system administrators, and stakeholders to understand the technical implementation and design decisions.

### 1.2 Scope
This document covers the technical design aspects of the NIMR Intranet System including:
- System architecture and components
- Database design and relationships
- User interface design patterns
- Security implementation
- Performance optimization strategies
- Deployment and infrastructure considerations

### 1.3 Definitions and Acronyms

| Term | Definition |
|------|------------|
| MVC | Model-View-Controller architectural pattern |
| ORM | Object-Relational Mapping |
| REST | Representational State Transfer |
| CRUD | Create, Read, Update, Delete operations |
| JWT | JSON Web Token |
| AJAX | Asynchronous JavaScript and XML |
| CDN | Content Delivery Network |
| SPA | Single Page Application |

### 1.4 References
- Software Requirements Specification (SRS) v2.0.0
- Laravel Framework Documentation v12.x
- MySQL Documentation v8.0
- Tailwind CSS Documentation v3.x
- Alpine.js Documentation v3.x

### 1.5 Overview
This document follows a top-down approach, starting with high-level system architecture and progressively diving into detailed component design, database structure, and implementation specifics.

---

## 2. System Overview

### 2.1 System Purpose
The NIMR Intranet System is designed as a centralized web-based platform for internal communication, document management, and organizational information sharing within the National Institute for Medical Research.

### 2.2 System Context
```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│   NIMR Staff    │    │  Administrators │    │ System Admins   │
│   (Users)       │    │                 │    │                 │
└─────────┬───────┘    └─────────┬───────┘    └─────────┬───────┘
          │                      │                      │
          └──────────────────────┼──────────────────────┘
                                 │
          ┌─────────────────────┴─────────────────────┐
          │          NIMR Intranet System           │
          │                                         │
          │  ┌─────────────┐  ┌─────────────────┐   │
          │  │   Web App   │  │  Admin Panel    │   │
          │  └─────────────┘  └─────────────────┘   │
          └─────────────────────┬─────────────────────┘
                                │
          ┌─────────────────────┼─────────────────────┐
          │                     │                     │
    ┌─────▼──────┐    ┌────────▼────────┐    ┌───────▼──────┐
    │  Database  │    │  File Storage   │    │ Email Server │
    │ (MySQL)    │    │   (Local FS)    │    │   (SMTP)     │
    └────────────┘    └─────────────────┘    └──────────────┘
```

### 2.3 System Constraints

**Technical Constraints:**
- Must be web-based and browser-compatible
- Must integrate with existing NIMR infrastructure
- Limited to PHP/MySQL technology stack
- Must support responsive design for mobile access

**Operational Constraints:**
- 24/7 availability requirement
- Support for 200+ concurrent users
- Document storage up to 100GB
- Backup and recovery capabilities

---

## 3. System Architecture

### 3.1 Architectural Style
The system follows a **layered architecture** pattern combined with **Model-View-Controller (MVC)** design pattern, implemented using the Laravel framework.

### 3.2 High-Level Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Presentation Layer                       │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │  Web UI     │  │  Admin UI   │  │  Mobile Interface   │  │
│  │ (Blade)     │  │ (Blade)     │  │  (Responsive)       │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                   Application Layer                         │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │ Controllers │  │ Middleware  │  │    Services         │  │
│  │             │  │             │  │                     │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                    Business Layer                           │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │   Models    │  │  Policies   │  │    Observers        │  │
│  │             │  │             │  │                     │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
                                │
┌─────────────────────────────────────────────────────────────┐
│                     Data Layer                              │
│  ┌─────────────┐  ┌─────────────┐  ┌─────────────────────┐  │
│  │  Database   │  │ File System │  │  External APIs      │  │
│  │  (MySQL)    │  │  Storage    │  │  (Email, etc.)      │  │
│  └─────────────┘  └─────────────┘  └─────────────────────┘  │
└─────────────────────────────────────────────────────────────┘
```

### 3.3 Component Overview

#### 3.3.1 Presentation Layer Components

**Web User Interface:**
- Blade template engine for server-side rendering
- Alpine.js for reactive client-side interactions
- Tailwind CSS for styling and responsive design
- Heroicons for consistent iconography

**Admin Interface:**
- Specialized administrative views
- Enhanced form components
- Data tables with sorting and filtering
- Dashboard widgets and analytics

#### 3.3.2 Application Layer Components

**Controllers:**
- `AuthController`: User authentication and authorization
- `DocumentController`: Document management operations
- `AnnouncementController`: Announcement CRUD operations
- `UserController`: User management functions
- `DashboardController`: Dashboard data aggregation

**Middleware:**
- `Authenticate`: Session validation
- `AdminRequired`: Role-based access control
- `CSRF Protection`: Security middleware
- `Throttle`: Rate limiting

**Services:**
- `DocumentService`: Business logic for document operations
- `UserService`: User management business logic
- `EmailService`: Email notification handling
- `AnalyticsService`: Usage statistics and reporting

#### 3.3.3 Business Layer Components

**Models:**
- `User`: User entity with authentication
- `Document`: Document metadata and relationships
- `Announcement`: Announcement entity
- `Centre`: Organizational structure
- `Department`: Departmental organization

**Policies:**
- `DocumentPolicy`: Document access control
- `UserPolicy`: User management permissions
- `AnnouncementPolicy`: Announcement management rules

### 3.4 Technology Stack

```
┌─────────────────────────────────────────────────────────────┐
│                    Frontend Technologies                    │
│                                                             │
│  HTML5 │ CSS3 │ JavaScript │ Alpine.js │ Tailwind CSS      │
│                                                             │
└─────────────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│                   Backend Technologies                      │
│                                                             │
│  PHP 8.1+ │ Laravel 12.x │ Composer │ Artisan CLI         │
│                                                             │
└─────────────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│                   Database & Storage                        │
│                                                             │
│  MySQL 8.0+ │ File System │ Redis (optional caching)       │
│                                                             │
└─────────────────────────────────────────────────────────────┘
┌─────────────────────────────────────────────────────────────┐
│                Infrastructure & Deployment                  │
│                                                             │
│  Nginx │ Ubuntu Server │ SSL/TLS │ Git │ Supervisor        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 4. Detailed Design

### 4.1 Authentication System

#### 4.1.1 Authentication Flow

```
┌─────────┐    ┌─────────────┐    ┌─────────────┐    ┌─────────────┐
│  User   │    │   Login     │    │    Auth     │    │  Dashboard  │
│         │    │   Form      │    │ Controller  │    │             │
└────┬────┘    └──────┬──────┘    └──────┬──────┘    └──────┬──────┘
     │                │                  │                  │
     │ 1. Enter creds │                  │                  │
     ├───────────────►│                  │                  │
     │                │ 2. Submit form   │                  │
     │                ├─────────────────►│                  │
     │                │                  │ 3. Validate      │
     │                │                  ├──────────────────┤
     │                │                  │ 4. Create session│
     │                │                  ├──────────────────┤
     │                │ 5. Redirect      │                  │
     │                │◄─────────────────┤                  │
     │ 6. Access dashboard               │                  │
     ├───────────────────────────────────┼─────────────────►│
```

#### 4.1.2 Authorization Matrix

| Role | Documents | Announcements | Users | System Config |
|------|-----------|---------------|-------|---------------|
| Super Admin | Full Access | Full Access | Full Access | Full Access |
| Admin | Department Access | Department Access | Department View | No Access |
| User | Read Only | Read Only | No Access | No Access |

### 4.2 Document Management System

#### 4.2.1 Document Upload Process

```php
class DocumentController extends Controller
{
    public function store(Request $request)
    {
        // 1. Validate request
        $validated = $request->validate([
            'file' => 'required|file|max:51200', // 50MB
            'department_id' => 'required|exists:departments,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        // 2. Check user permissions
        $this->authorize('create', Document::class);

        // 3. Process file upload
        $file = $request->file('file');
        $filename = $this->generateUniqueFilename($file);
        $path = $file->storeAs('documents', $filename, 'public');

        // 4. Save document metadata
        $document = Document::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'department_id' => $validated['department_id'],
            'uploaded_by' => auth()->id(),
        ]);

        // 5. Log activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($document)
            ->log('Document uploaded');

        return redirect()->back()->with('success', 'Document uploaded successfully');
    }
}
```

#### 4.2.2 Document Search Algorithm

```php
class DocumentService
{
    public function search($query, $filters = [])
    {
        $documents = Document::query()
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('original_filename', 'LIKE', "%{$query}%");
            })
            ->when($filters['department_id'] ?? null, function ($q) use ($filters) {
                $q->where('department_id', $filters['department_id']);
            })
            ->when($filters['file_type'] ?? null, function ($q) use ($filters) {
                $q->where('mime_type', 'LIKE', $filters['file_type'] . '%');
            })
            ->when($filters['date_from'] ?? null, function ($q) use ($filters) {
                $q->whereDate('created_at', '>=', $filters['date_from']);
            })
            ->when($filters['date_to'] ?? null, function ($q) use ($filters) {
                $q->whereDate('created_at', '<=', $filters['date_to']);
            })
            ->with(['department', 'uploader'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return $documents;
    }
}
```

### 4.3 User Interface Components

#### 4.3.1 Department Cards Component

```javascript
// Alpine.js component for document management
function documentsApp() {
    return {
        viewMode: 'cards', // 'cards' or 'list'
        selectedDepartment: null,
        searchQuery: '',
        
        init() {
            // Initialize component
            this.loadInitialData();
        },
        
        switchToList(departmentId = null) {
            this.viewMode = 'list';
            this.selectedDepartment = departmentId;
            this.loadDocuments();
        },
        
        switchToCards() {
            this.viewMode = 'cards';
            this.selectedDepartment = null;
        },
        
        loadDocuments() {
            // AJAX call to load documents
            fetch(`/documents/search?department=${this.selectedDepartment}&q=${this.searchQuery}`)
                .then(response => response.json())
                .then(data => {
                    this.updateDocumentsList(data);
                });
        }
    }
}
```

#### 4.3.2 Statistics Dashboard

```php
class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_documents' => Document::count(),
            'total_users' => User::count(),
            'total_departments' => Department::count(),
            'recent_uploads' => Document::where('created_at', '>=', now()->subDays(7))->count(),
            'popular_documents' => Document::withCount('downloads')
                ->orderBy('downloads_count', 'desc')
                ->limit(5)
                ->get(),
            'department_stats' => Department::withCount('documents')->get(),
        ];

        $recent_announcements = Announcement::where('published_at', '<=', now())
            ->where(function ($query) {
                $query->whereNull('expires_at')
                      ->orWhere('expires_at', '>', now());
            })
            ->orderBy('published_at', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recent_announcements'));
    }
}
```

---

## 5. Database Design

### 5.1 Entity Relationship Diagram

```
┌─────────────────┐    ┌─────────────────┐    ┌─────────────────┐
│     centres     │    │   departments   │    │     users       │
├─────────────────┤    ├─────────────────┤    ├─────────────────┤
│ id (PK)         │    │ id (PK)         │    │ id (PK)         │
│ name            │◄───┤ centre_id (FK)  │◄───┤ department_id   │
│ code            │    │ name            │    │ name            │
│ location        │    │ description     │    │ email           │
│ created_at      │    │ created_at      │    │ password        │
│ updated_at      │    │ updated_at      │    │ role            │
└─────────────────┘    └─────────────────┘    │ created_at      │
                                              │ updated_at      │
                       ┌─────────────────┐    └─────────────────┘
                       │   documents     │               │
                       ├─────────────────┤               │
                       │ id (PK)         │               │
                       │ title           │               │
                       │ description     │               │
                       │ filename        │               │
                       │ original_name   │               │
                       │ file_path       │               │
                       │ file_size       │               │
                       │ mime_type       │               │
                    ┌──┤ department_id   │               │
                    │  │ uploaded_by (FK)├───────────────┘
                    │  │ download_count  │
                    │  │ created_at      │
                    │  │ updated_at      │
                    │  └─────────────────┘
                    │
                    │  ┌─────────────────┐
                    │  │ announcements   │
                    │  ├─────────────────┤
                    │  │ id (PK)         │
                    │  │ title           │
                    │  │ content         │
                    │  │ category        │
                    │  │ published_at    │
                    │  │ expires_at      │
                    │  │ created_by (FK) ├───────────────┐
                    │  │ created_at      │               │
                    │  │ updated_at      │               │
                    │  └─────────────────┘               │
                    │                                    │
                    └────────────────────────────────────┘
```

### 5.2 Database Tables Specification

#### 5.2.1 Users Table

```sql
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'user') DEFAULT 'user',
    department_id BIGINT UNSIGNED,
    centre_id BIGINT UNSIGNED,
    phone VARCHAR(20),
    avatar VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (centre_id) REFERENCES centres(id) ON DELETE SET NULL,
    INDEX idx_users_email (email),
    INDEX idx_users_role (role),
    INDEX idx_users_department (department_id)
);
```

#### 5.2.2 Documents Table

```sql
CREATE TABLE documents (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    filename VARCHAR(255) NOT NULL,
    original_filename VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_size BIGINT UNSIGNED NOT NULL,
    mime_type VARCHAR(100) NOT NULL,
    department_id BIGINT UNSIGNED NOT NULL,
    uploaded_by BIGINT UNSIGNED NOT NULL,
    download_count INT UNSIGNED DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE,
    FOREIGN KEY (uploaded_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_documents_department (department_id),
    INDEX idx_documents_uploader (uploaded_by),
    INDEX idx_documents_created (created_at),
    FULLTEXT KEY ft_documents_search (title, description)
);
```

#### 5.2.3 Departments Table

```sql
CREATE TABLE departments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(10) UNIQUE NOT NULL,
    description TEXT,
    centre_id BIGINT UNSIGNED,
    head_id BIGINT UNSIGNED NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (centre_id) REFERENCES centres(id) ON DELETE CASCADE,
    FOREIGN KEY (head_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_departments_centre (centre_id),
    INDEX idx_departments_code (code)
);
```

#### 5.2.4 Announcements Table

```sql
CREATE TABLE announcements (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT NOT NULL,
    category ENUM('general', 'policy', 'event', 'maintenance', 'emergency') DEFAULT 'general',
    target_audience ENUM('all', 'staff', 'admins') DEFAULT 'all',
    department_id BIGINT UNSIGNED NULL,
    published_at TIMESTAMP NOT NULL,
    expires_at TIMESTAMP NULL,
    created_by BIGINT UNSIGNED NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    view_count INT UNSIGNED DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_announcements_published (published_at),
    INDEX idx_announcements_expires (expires_at),
    INDEX idx_announcements_department (department_id),
    FULLTEXT KEY ft_announcements_search (title, content)
);
```

### 5.3 Database Optimization

#### 5.3.1 Indexing Strategy

```sql
-- Performance indexes for common queries
CREATE INDEX idx_documents_popular ON documents(download_count DESC, created_at DESC);
CREATE INDEX idx_documents_recent ON documents(created_at DESC, department_id);
CREATE INDEX idx_users_active ON users(is_active, last_login_at);
CREATE INDEX idx_announcements_active ON announcements(is_active, published_at, expires_at);

-- Composite indexes for filtered queries
CREATE INDEX idx_documents_dept_date ON documents(department_id, created_at DESC);
CREATE INDEX idx_documents_size_type ON documents(file_size, mime_type);
```

#### 5.3.2 Data Archiving Strategy

```sql
-- Archive old documents (older than 5 years)
CREATE TABLE documents_archive LIKE documents;

-- Archive old announcements (older than 2 years)
CREATE TABLE announcements_archive LIKE announcements;

-- Cleanup procedure
DELIMITER //
CREATE PROCEDURE ArchiveOldData()
BEGIN
    -- Archive old documents
    INSERT INTO documents_archive 
    SELECT * FROM documents 
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 5 YEAR);
    
    DELETE FROM documents 
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 5 YEAR);
    
    -- Archive old announcements
    INSERT INTO announcements_archive 
    SELECT * FROM announcements 
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 2 YEAR);
    
    DELETE FROM announcements 
    WHERE created_at < DATE_SUB(NOW(), INTERVAL 2 YEAR);
END //
DELIMITER ;
```

---

## 6. User Interface Design

### 6.1 Design System

#### 6.1.1 Color Palette

```css
:root {
    /* Primary Colors */
    --color-primary-50: #eff6ff;
    --color-primary-100: #dbeafe;
    --color-primary-500: #3b82f6;
    --color-primary-600: #2563eb;
    --color-primary-700: #1d4ed8;
    --color-primary-900: #1e3a8a;
    
    /* Secondary Colors */
    --color-gray-50: #f9fafb;
    --color-gray-100: #f3f4f6;
    --color-gray-500: #6b7280;
    --color-gray-700: #374151;
    --color-gray-900: #111827;
    
    /* Status Colors */
    --color-success: #059669;
    --color-warning: #d97706;
    --color-error: #dc2626;
    --color-info: #0891b2;
}
```

#### 6.1.2 Typography Scale

```css
/* Typography System */
.text-xs { font-size: 0.75rem; line-height: 1rem; }
.text-sm { font-size: 0.875rem; line-height: 1.25rem; }
.text-base { font-size: 1rem; line-height: 1.5rem; }
.text-lg { font-size: 1.125rem; line-height: 1.75rem; }
.text-xl { font-size: 1.25rem; line-height: 1.75rem; }
.text-2xl { font-size: 1.5rem; line-height: 2rem; }
.text-3xl { font-size: 1.875rem; line-height: 2.25rem; }

/* Font Weights */
.font-normal { font-weight: 400; }
.font-medium { font-weight: 500; }
.font-semibold { font-weight: 600; }
.font-bold { font-weight: 700; }
```

### 6.2 Component Library

#### 6.2.1 Button Components

```html
<!-- Primary Button -->
<button class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent 
               rounded-md font-semibold text-xs text-white uppercase tracking-widest 
               hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none 
               focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
    Primary Button
</button>

<!-- Secondary Button -->
<button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 
               rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest 
               shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 
               focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
    Secondary Button
</button>
```

#### 6.2.2 Card Components

```html
<!-- Department Card -->
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 
            hover:shadow-md transition-shadow duration-200">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="currentColor">...</svg>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900">Department Name</h3>
                    <p class="text-sm text-gray-500">Department Description</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-2xl font-bold text-gray-900">24</div>
                <div class="text-sm text-gray-500">Documents</div>
            </div>
        </div>
    </div>
</div>
```

### 6.3 Responsive Design Strategy

#### 6.3.1 Breakpoint System

```css
/* Mobile First Approach */
/* xs: 0px - 479px (mobile) */
/* sm: 480px - 767px (large mobile) */
/* md: 768px - 1023px (tablet) */
/* lg: 1024px - 1279px (desktop) */
/* xl: 1280px+ (large desktop) */

/* Responsive Grid */
.grid-responsive {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1rem;
}

@media (min-width: 640px) {
    .grid-responsive { grid-template-columns: repeat(2, 1fr); }
}

@media (min-width: 1024px) {
    .grid-responsive { grid-template-columns: repeat(3, 1fr); }
}

@media (min-width: 1280px) {
    .grid-responsive { grid-template-columns: repeat(4, 1fr); }
}
```

#### 6.3.2 Mobile Navigation

```html
<!-- Mobile Menu Component -->
<div x-data="{ open: false }" class="md:hidden">
    <!-- Mobile menu button -->
    <button @click="open = !open" class="inline-flex items-center justify-center p-2 
                                         rounded-md text-gray-400 hover:text-gray-500 
                                         hover:bg-gray-100 focus:outline-none focus:ring-2 
                                         focus:ring-indigo-500">
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M4 6h16M4 12h16M4 18h16" />
        </svg>
    </button>

    <!-- Mobile menu panel -->
    <div x-show="open" x-transition class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            <!-- Navigation items -->
        </div>
    </div>
</div>
```

---

## 7. Security Design

### 7.1 Authentication Security

#### 7.1.1 Password Security

```php
// Password hashing configuration
class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role', 'department_id'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Automatic password hashing
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Password validation rules
    public static function passwordRules()
    {
        return [
            'required',
            'string',
            'min:8',
            'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/',
            'confirmed'
        ];
    }
}
```

#### 7.1.2 Session Management

```php
// Session configuration
'lifetime' => env('SESSION_LIFETIME', 480), // 8 hours
'expire_on_close' => false,
'encrypt' => true,
'driver' => 'database',
'secure' => env('SESSION_SECURE_COOKIE', true),
'http_only' => true,
'same_site' => 'strict',

// Session middleware
class SessionTimeoutMiddleware
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {
            $lastActivity = session('last_activity', time());
            
            if (time() - $lastActivity > config('session.lifetime') * 60) {
                auth()->logout();
                session()->invalidate();
                session()->regenerateToken();
                
                return redirect('/login')->with('message', 'Session expired due to inactivity.');
            }
            
            session(['last_activity' => time()]);
        }
        
        return $next($request);
    }
}
```

### 7.2 Data Protection

#### 7.2.1 File Upload Security

```php
class DocumentUploadService
{
    private $allowedMimeTypes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'image/jpeg',
        'image/png',
        'image/gif'
    ];

    private $maxFileSize = 52428800; // 50MB

    public function validateFile(UploadedFile $file)
    {
        // Check file size
        if ($file->getSize() > $this->maxFileSize) {
            throw new ValidationException('File size exceeds maximum allowed size.');
        }

        // Check mime type
        if (!in_array($file->getMimeType(), $this->allowedMimeTypes)) {
            throw new ValidationException('File type not allowed.');
        }

        // Check file extension
        $extension = $file->getClientOriginalExtension();
        $allowedExtensions = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array(strtolower($extension), $allowedExtensions)) {
            throw new ValidationException('File extension not allowed.');
        }

        // Scan for malicious content (basic check)
        $this->scanForMalware($file);

        return true;
    }

    private function scanForMalware(UploadedFile $file)
    {
        $content = file_get_contents($file->getPathname());
        
        // Check for suspicious patterns
        $maliciousPatterns = [
            '/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi',
            '/javascript:/i',
            '/vbscript:/i',
            '/onload=/i',
            '/onerror=/i'
        ];

        foreach ($maliciousPatterns as $pattern) {
            if (preg_match($pattern, $content)) {
                throw new ValidationException('File contains potentially malicious content.');
            }
        }
    }
}
```

#### 7.2.2 SQL Injection Prevention

```php
// Using Eloquent ORM for safe queries
class DocumentService
{
    public function searchDocuments($query, $filters = [])
    {
        return Document::query()
            ->when($query, function ($q) use ($query) {
                // Safe parameter binding
                $q->where('title', 'LIKE', '%' . $query . '%')
                  ->orWhere('description', 'LIKE', '%' . $query . '%');
            })
            ->when($filters['department_id'] ?? null, function ($q) use ($filters) {
                $q->where('department_id', $filters['department_id']);
            })
            ->with(['department', 'uploader'])
            ->paginate(20);
    }

    // Raw queries with parameter binding when necessary
    public function getAdvancedStats()
    {
        return DB::select("
            SELECT 
                d.name as department_name,
                COUNT(doc.id) as document_count,
                SUM(doc.download_count) as total_downloads,
                AVG(doc.file_size) as avg_file_size
            FROM departments d
            LEFT JOIN documents doc ON d.id = doc.department_id
            WHERE d.is_active = ?
            GROUP BY d.id, d.name
            ORDER BY document_count DESC
        ", [true]);
    }
}
```

### 7.3 Access Control

#### 7.3.1 Role-Based Permissions

```php
// Policy-based authorization
class DocumentPolicy
{
    public function viewAny(User $user)
    {
        return true; // All authenticated users can view documents
    }

    public function view(User $user, Document $document)
    {
        // Users can view documents from their department or public documents
        return $user->department_id === $document->department_id || 
               $document->is_public || 
               $user->isAdmin();
    }

    public function create(User $user)
    {
        // Only admins can upload documents
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    public function update(User $user, Document $document)
    {
        // Can update if user uploaded it or is admin of same department
        return $user->id === $document->uploaded_by || 
               ($user->isAdmin() && $user->department_id === $document->department_id) ||
               $user->isSuperAdmin();
    }

    public function delete(User $user, Document $document)
    {
        // Same rules as update
        return $this->update($user, $document);
    }
}

// User model with role checking
class User extends Authenticatable
{
    public function isAdmin()
    {
        return in_array($this->role, ['admin', 'super_admin']);
    }

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function canManageDepartment($departmentId)
    {
        return $this->isSuperAdmin() || 
               ($this->isAdmin() && $this->department_id === $departmentId);
    }
}
```

---

## 8. Performance Considerations

### 8.1 Database Optimization

#### 8.1.1 Query Optimization

```php
// Efficient loading with relationships
class DocumentController extends Controller
{
    public function index()
    {
        // Eager loading to prevent N+1 queries
        $documents = Document::with(['department', 'uploader:id,name'])
            ->select(['id', 'title', 'filename', 'file_size', 'department_id', 'uploaded_by', 'created_at'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('documents.index', compact('documents'));
    }

    // Optimized statistics query
    public function getDashboardStats()
    {
        $stats = Cache::remember('dashboard_stats', 300, function () {
            return [
                'total_documents' => Document::count(),
                'total_downloads' => Document::sum('download_count'),
                'recent_uploads' => Document::where('created_at', '>=', now()->subDays(7))->count(),
                'department_stats' => Department::withCount('documents')
                    ->orderBy('documents_count', 'desc')
                    ->get()
            ];
        });

        return $stats;
    }
}
```

#### 8.1.2 Caching Strategy

```php
// Cache configuration
'stores' => [
    'redis' => [
        'driver' => 'redis',
        'connection' => 'cache',
        'lock_connection' => 'default',
    ],
    'file' => [
        'driver' => 'file',
        'path' => storage_path('framework/cache/data'),
    ],
],

// Service with caching
class AnalyticsService
{
    public function getPopularDocuments($limit = 10)
    {
        return Cache::tags(['documents', 'analytics'])
            ->remember("popular_documents_{$limit}", 3600, function () use ($limit) {
                return Document::with('department:id,name')
                    ->orderBy('download_count', 'desc')
                    ->limit($limit)
                    ->get(['id', 'title', 'download_count', 'department_id']);
            });
    }

    public function invalidateDocumentCache()
    {
        Cache::tags(['documents'])->flush();
    }
}

// Cache invalidation on model events
class DocumentObserver
{
    public function created(Document $document)
    {
        $this->clearCache();
    }

    public function updated(Document $document)
    {
        $this->clearCache();
    }

    public function deleted(Document $document)
    {
        $this->clearCache();
    }

    private function clearCache()
    {
        Cache::tags(['documents', 'analytics'])->flush();
    }
}
```

### 8.2 Frontend Optimization

#### 8.2.1 Asset Optimization

```javascript
// Webpack configuration for asset bundling
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
       require('tailwindcss'),
       require('autoprefixer'),
   ])
   .options({
       processCssUrls: false,
   })
   .version(); // Cache busting

// Lazy loading for images
document.addEventListener('DOMContentLoaded', function() {
    const images = document.querySelectorAll('img[data-src]');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.removeAttribute('data-src');
                observer.unobserve(img);
            }
        });
    });

    images.forEach(img => observer.observe(img));
});
```

#### 8.2.2 JavaScript Optimization

```javascript
// Debounced search function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Optimized Alpine.js component
function documentsApp() {
    return {
        searchQuery: '',
        documents: [],
        loading: false,
        
        init() {
            // Debounced search
            this.$watch('searchQuery', debounce(() => {
                this.searchDocuments();
            }, 300));
        },
        
        async searchDocuments() {
            if (this.searchQuery.length < 2) return;
            
            this.loading = true;
            try {
                const response = await fetch(`/api/documents/search?q=${encodeURIComponent(this.searchQuery)}`);
                this.documents = await response.json();
            } catch (error) {
                console.error('Search failed:', error);
            } finally {
                this.loading = false;
            }
        }
    }
}
```

---

## 9. Deployment Architecture

### 9.1 Production Environment

#### 9.1.1 Server Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    Load Balancer (Nginx)                    │
│                        (Optional)                           │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────┼───────────────────────────────────────┐
│                Web Server (Nginx)                          │
│  ┌─────────────────────────────────────────────────────┐   │
│  │               PHP-FPM                               │   │
│  │  ┌───────────────────────────────────────────────┐  │   │
│  │  │            Laravel Application              │  │   │
│  │  └───────────────────────────────────────────────┘  │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────┼───────────────────────────────────────┐
│                Database Server                              │
│  ┌─────────────────────────────────────────────────────┐   │
│  │                MySQL 8.0                           │   │
│  │  - InnoDB Storage Engine                           │   │
│  │  - Query Cache Enabled                             │   │
│  │  - Slow Query Log Enabled                          │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────┼───────────────────────────────────────┐
│                File Storage                                 │
│  ┌─────────────────────────────────────────────────────┐   │
│  │              Local File System                      │   │
│  │  - /var/www/storage/app/public/documents            │   │
│  │  - Automated Backup to External Storage            │   │
│  └─────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

#### 9.1.2 Nginx Configuration

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
    ssl_certificate /etc/ssl/certs/nimr-intranet.crt;
    ssl_certificate_key /etc/ssl/private/nimr-intranet.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512;
    ssl_prefer_server_ciphers off;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # File Upload Limits
    client_max_body_size 50M;
    client_body_timeout 120s;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_types
        text/plain
        text/css
        text/xml
        text/javascript
        application/javascript
        application/xml+rss
        application/json;

    # Cache static assets
    location ~* \.(js|css|png|jpg|jpeg|gif|ico|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 9.2 Backup and Recovery

#### 9.2.1 Automated Backup Script

```bash
#!/bin/bash
# NIMR Intranet Backup Script

# Configuration
BACKUP_DIR="/var/backups/nimr-intranet"
APP_DIR="/var/www/nimr-intranet"
DB_NAME="nimr_intranet"
DB_USER="nimr_user"
DB_PASS="password"
DATE=$(date +%Y%m%d_%H%M%S)
RETENTION_DAYS=30

# Create backup directory
mkdir -p $BACKUP_DIR

# Database backup
echo "Starting database backup..."
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/database_$DATE.sql.gz

# File backup
echo "Starting file backup..."
tar -czf $BACKUP_DIR/files_$DATE.tar.gz -C $APP_DIR storage/app/public

# Configuration backup
echo "Starting configuration backup..."
cp $APP_DIR/.env $BACKUP_DIR/env_$DATE.backup

# Cleanup old backups
echo "Cleaning up old backups..."
find $BACKUP_DIR -name "database_*.sql.gz" -mtime +$RETENTION_DAYS -delete
find $BACKUP_DIR -name "files_*.tar.gz" -mtime +$RETENTION_DAYS -delete
find $BACKUP_DIR -name "env_*.backup" -mtime +$RETENTION_DAYS -delete

echo "Backup completed successfully"
```

#### 9.2.2 Recovery Procedures

```bash
#!/bin/bash
# Recovery Script

# Database recovery
echo "Restoring database..."
gunzip < /var/backups/nimr-intranet/database_YYYYMMDD_HHMMSS.sql.gz | mysql -u nimr_user -p nimr_intranet

# File recovery
echo "Restoring files..."
cd /var/www/nimr-intranet
tar -xzf /var/backups/nimr-intranet/files_YYYYMMDD_HHMMSS.tar.gz

# Set proper permissions
echo "Setting permissions..."
chown -R www-data:www-data storage/
chmod -R 775 storage/

echo "Recovery completed"
```

### 9.3 Monitoring and Logging

#### 9.3.1 Application Monitoring

```php
// Custom monitoring service
class SystemMonitorService
{
    public function getSystemHealth()
    {
        return [
            'database' => $this->checkDatabaseConnection(),
            'storage' => $this->checkStorageSpace(),
            'cache' => $this->checkCacheStatus(),
            'queue' => $this->checkQueueStatus(),
            'performance' => $this->getPerformanceMetrics()
        ];
    }

    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return ['status' => 'healthy', 'response_time' => $this->measureDbResponseTime()];
        } catch (Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkStorageSpace()
    {
        $storagePath = storage_path();
        $freeBytes = disk_free_space($storagePath);
        $totalBytes = disk_total_space($storagePath);
        $usedPercent = (($totalBytes - $freeBytes) / $totalBytes) * 100;

        return [
            'status' => $usedPercent > 90 ? 'warning' : 'healthy',
            'used_percent' => round($usedPercent, 2),
            'free_space' => $this->formatBytes($freeBytes)
        ];
    }
}
```

#### 9.2.2 Log Management

```php
// Log channels configuration
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'security'],
        'ignore_exceptions' => false,
    ],

    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
    ],

    'security' => [
        'driver' => 'single',
        'path' => storage_path('logs/security.log'),
        'level' => 'info',
    ],

    'audit' => [
        'driver' => 'single',
        'path' => storage_path('logs/audit.log'),
        'level' => 'info',
    ],
],

// Security event logging
class SecurityLogger
{
    public function logAuthAttempt($email, $success, $ip)
    {
        Log::channel('security')->info('Authentication attempt', [
            'email' => $email,
            'success' => $success,
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'timestamp' => now()
        ]);
    }

    public function logFileUpload($userId, $filename, $size)
    {
        Log::channel('audit')->info('File uploaded', [
            'user_id' => $userId,
            'filename' => $filename,
            'file_size' => $size,
            'timestamp' => now()
        ]);
    }
}
```

---

## 10. Appendices

### Appendix A: API Documentation

#### A.1 REST API Endpoints

```php
// Document API endpoints
Route::prefix('api/v1')->middleware(['auth:sanctum'])->group(function () {
    // Documents
    Route::get('/documents', [DocumentController::class, 'apiIndex']);
    Route::get('/documents/search', [DocumentController::class, 'apiSearch']);
    Route::get('/documents/{document}', [DocumentController::class, 'apiShow']);
    Route::post('/documents', [DocumentController::class, 'apiStore']);
    Route::post('/documents/{document}/download', [DocumentController::class, 'apiDownload']);
    
    // Departments
    Route::get('/departments', [DepartmentController::class, 'apiIndex']);
    Route::get('/departments/{department}/documents', [DepartmentController::class, 'apiDocuments']);
    
    // Analytics
    Route::get('/analytics/dashboard', [AnalyticsController::class, 'apiDashboard']);
    Route::get('/analytics/departments', [AnalyticsController::class, 'apiDepartmentStats']);
});
```

#### A.2 API Response Formats

```json
{
  "success": true,
  "data": {
    "documents": [
      {
        "id": 1,
        "title": "Annual Report 2024",
        "filename": "annual_report_2024.pdf",
        "file_size": 2048576,
        "department": {
          "id": 1,
          "name": "Executive Office",
          "code": "EXEC"
        },
        "uploader": {
          "id": 5,
          "name": "John Doe"
        },
        "download_count": 45,
        "created_at": "2025-09-01T10:30:00Z"
      }
    ]
  },
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 150,
    "last_page": 8
  }
}
```

### Appendix B: Database Schema

#### B.1 Complete Schema SQL

```sql
-- Complete database schema for NIMR Intranet
-- Execute in order

-- 1. Centres table
CREATE TABLE centres (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(10) UNIQUE NOT NULL,
    location VARCHAR(255),
    description TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 2. Departments table
CREATE TABLE departments (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    code VARCHAR(10) UNIQUE NOT NULL,
    description TEXT,
    centre_id BIGINT UNSIGNED,
    head_id BIGINT UNSIGNED NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (centre_id) REFERENCES centres(id) ON DELETE CASCADE
);

-- 3. Users table
CREATE TABLE users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    email_verified_at TIMESTAMP NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'user') DEFAULT 'user',
    department_id BIGINT UNSIGNED,
    centre_id BIGINT UNSIGNED,
    phone VARCHAR(20),
    avatar VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    last_login_at TIMESTAMP NULL,
    remember_token VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (centre_id) REFERENCES centres(id) ON DELETE SET NULL
);

-- Add foreign key for department head
ALTER TABLE departments ADD FOREIGN KEY (head_id) REFERENCES users(id) ON DELETE SET NULL;

-- Continue with remaining tables...
```

### Appendix C: Deployment Checklist

#### C.1 Pre-Deployment Checklist

- [ ] Server requirements verified
- [ ] SSL certificates obtained and installed
- [ ] Database created and configured
- [ ] Application deployed and configured
- [ ] Environment variables set
- [ ] File permissions configured
- [ ] Backup system configured
- [ ] Monitoring tools installed
- [ ] Security hardening applied
- [ ] Performance optimization implemented

#### C.2 Post-Deployment Checklist

- [ ] Application accessible via HTTPS
- [ ] User authentication working
- [ ] File upload functionality tested
- [ ] Email notifications working
- [ ] Database connections stable
- [ ] Backup procedures tested
- [ ] Monitoring alerts configured
- [ ] Documentation updated
- [ ] User training completed
- [ ] Support procedures established

---

**Document Control:**
- **Classification**: Internal Use
- **Distribution**: NIMR IT Department, Development Team
- **Review Cycle**: Quarterly
- **Next Review Date**: December 8, 2025

**Approval:**
- **Technical Architect**: [Name, Date]
- **Senior Developer**: [Name, Date]
- **IT Director**: [Name, Date]
