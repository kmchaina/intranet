# Admin/Staff Mode Separation Implementation

## Overview

This document outlines the complete implementation of proper admin/staff mode separation in the NIMR Intranet system.

## The Problem

Previously, the system had mixed admin/staff capabilities:

-   ❌ Admins saw staff views with edit buttons added
-   ❌ Staff views contained admin capabilities
-   ❌ No clear separation between consuming content vs managing content
-   ❌ Missing dedicated admin management interfaces

## The Solution

### 1. **Staff Mode (Default for Everyone)**

Pure consumer experience for ALL users including admins when in staff mode:

**Routes:**

-   `/announcements` - View announcements targeted to you
-   `/news` - View all published news
-   `/events` - View events
-   `/documents` - Browse document library

**Features:**

-   ✅ Only see content targeted to you (announcements respect targeting rules)
-   ✅ Clean, consumption-focused interface
-   ✅ NO edit/delete buttons
-   ✅ Can create new content (announcements, news, events, documents)
-   ✅ Can interact (mark as read, RSVP, download, etc.)

**Files Updated:**

-   `resources/views/announcements/show.blade.php` - Removed edit/delete buttons
-   `resources/views/announcements/index.blade.php` - Removed edit buttons
-   `resources/views/news/show.blade.php` - Removed edit button
-   `resources/views/documents/show.blade.php` - Removed edit buttons

### 2. **Admin Mode (For Admins Only)**

Dedicated management interfaces with full CRUD capabilities:

**Routes:**

-   `/admin/announcements` - Manage ALL announcements
-   `/admin/news` - Manage ALL news articles
-   `/admin/events` - Manage ALL events
-   `/admin/documents` - Manage ALL documents

**Features:**

-   ✅ View ALL content (no targeting filters)
-   ✅ Comprehensive data tables with stats
-   ✅ Edit any content
-   ✅ Delete any content
-   ✅ Bulk delete operations
-   ✅ Toggle publish/unpublish status
-   ✅ Advanced filtering and sorting
-   ✅ Status indicators (published, draft, expired, etc.)
-   ✅ Analytics (views, reads, downloads, RSVPs)

**New Files Created:**

Controllers:

-   `app/Http/Controllers/Admin/AnnouncementAdminController.php`
-   `app/Http/Controllers/Admin/NewsAdminController.php`
-   `app/Http/Controllers/Admin/DocumentAdminController.php`
-   `app/Http/Controllers/Admin/EventAdminController.php`

Views:

-   `resources/views/admin/announcements/index.blade.php`
-   `resources/views/admin/announcements/edit.blade.php`
-   `resources/views/admin/news/index.blade.php`
-   `resources/views/admin/news/edit.blade.php`
-   `resources/views/admin/documents/index.blade.php`
-   `resources/views/admin/events/index.blade.php`

### 3. **Navigation Updates**

Updated `config/navigation.php` for all admin roles:

**Super Admin:**

-   📋 Content Management section with "Manage X" links to admin panels

**HQ Admin:**

-   📋 Content Management with manage links for announcements, news, events, documents, policies

**Centre Admin:**

-   📋 Content Management with manage links for announcements, news, events, documents

**Station Admin:**

-   📋 Content Management with manage links for announcements, news, events

### 4. **Route Separation**

**Staff Routes** (`routes/web.php`):

```php
// Announcements (index, create, store, show only)
Route::get('announcements', [AnnouncementController::class, 'index']);
Route::get('announcements/create', [AnnouncementController::class, 'create']);
Route::post('announcements', [AnnouncementController::class, 'store']);
Route::get('announcements/{announcement}', [AnnouncementController::class, 'show']);

// Similar pattern for news, documents, events
```

**Admin Routes** (`routes/web.php` - under `/admin` prefix):

```php
// Announcements (full CRUD + bulk operations)
Route::get('announcements', [AnnouncementAdminController::class, 'index']);
Route::get('announcements/{announcement}/edit', [AnnouncementAdminController::class, 'edit']);
Route::patch('announcements/{announcement}', [AnnouncementAdminController::class, 'update']);
Route::delete('announcements/{announcement}', [AnnouncementAdminController::class, 'destroy']);
Route::delete('announcements/bulk-delete', [AnnouncementAdminController::class, 'bulkDelete']);
Route::patch('announcements/{announcement}/toggle-publish', [AnnouncementAdminController::class, 'togglePublish']);

// Similar pattern for news, documents, events
```

## Key Features of Admin Management Pages

### Statistics Dashboard

Each admin page shows:

-   Total count
-   Published/Draft counts
-   Relevant metrics (views, downloads, RSVPs, etc.)

### Data Tables

-   Checkbox selection for bulk operations
-   Status indicators with color coding
-   Quick action buttons (view, edit, publish/unpublish, delete)
-   Sortable columns
-   Pagination (20 items per page)

### Filtering & Search

-   Search by title/content
-   Filter by status
-   Sort by multiple columns (date, title, views, etc.)
-   Sort order (ascending/descending)

### Bulk Operations

-   Select all checkbox
-   Bulk delete with confirmation
-   Visual feedback

## Benefits

### For Staff Users

-   ✅ Clean, distraction-free content consumption
-   ✅ See only relevant, targeted content
-   ✅ No confusing admin buttons
-   ✅ Better focus on actual content

### For Admins

-   ✅ Dedicated management interfaces
-   ✅ See ALL content at once
-   ✅ Powerful filtering and search
-   ✅ Bulk operations for efficiency
-   ✅ Clear status indicators
-   ✅ Analytics and insights
-   ✅ Professional admin UI

### For the System

-   ✅ Clear separation of concerns
-   ✅ Better security (staff can't accidentally delete)
-   ✅ Proper targeting enforcement
-   ✅ Scalable architecture
-   ✅ Maintainable codebase

## Testing

To test the implementation:

1. **As Staff (Regular User):**

    - Navigate to `/announcements` - should see only targeted announcements
    - Open any announcement - no edit/delete buttons
    - Try to access `/admin/announcements` - should get 403 error

2. **As Admin:**

    - Switch to Admin mode
    - Navigate to sidebar: Content Management → Manage Announcements
    - Should see ALL announcements regardless of targeting
    - Can edit, delete, toggle publish status
    - Can perform bulk delete operations
    - Can filter and search comprehensively

3. **Mode Switching:**
    - Admin in Staff mode - sees filtered content, no admin buttons
    - Admin in Admin mode - sees management interfaces
    - Clear visual separation

## Next Steps (Optional Enhancements)

1. Add export functionality (CSV/Excel)
2. Add content duplication feature
3. Add version history/audit log
4. Add scheduled publishing queue
5. Add bulk publish/unpublish
6. Add content approval workflow
7. Add analytics dashboards

## Conclusion

The system now has **proper separation** between content consumption (staff mode) and content management (admin mode). This creates a professional, scalable architecture that's intuitive for both staff and administrators.
