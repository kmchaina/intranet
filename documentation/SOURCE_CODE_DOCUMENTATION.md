# Source Code Documentation
## NIMR Intranet System

**Document Version**: 2.0.0  
**Date**: September 8, 2025  
**Project**: NIMR Intranet Management System  
**Client**: National Institute for Medical Research (NIMR)  
**Development Team**: NIMR IT Department  

---

## Table of Contents

1. [Architecture Overview](#1-architecture-overview)
2. [Directory Structure](#2-directory-structure)
3. [Core Components](#3-core-components)
4. [API Documentation](#4-api-documentation)
5. [Database Layer](#5-database-layer)
6. [Frontend Components](#6-frontend-components)
7. [Security Implementation](#7-security-implementation)
8. [Testing Framework](#8-testing-framework)
9. [Deployment Scripts](#9-deployment-scripts)
10. [Code Standards](#10-code-standards)

---

## 1. Architecture Overview

### 1.1 Framework Architecture
The NIMR Intranet system is built using **Laravel 12.x** following the **MVC (Model-View-Controller)** architectural pattern with additional layers for services and policies.

```
┌─────────────────────────────────────────────────────────────┐
│                    Frontend Layer                           │
│  Blade Templates + Alpine.js + Tailwind CSS                │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────┼───────────────────────────────────────┐
│                Controllers                                  │
│  HTTP Request Handling + Route Logic                       │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────┼───────────────────────────────────────┐
│                Services                                     │
│  Business Logic + External API Integration                 │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────┼───────────────────────────────────────┐
│                Models + Policies                            │
│  Data Layer + Authorization Logic                          │
└─────────────────────┬───────────────────────────────────────┘
                      │
┌─────────────────────┼───────────────────────────────────────┐
│                Database Layer                               │
│  MySQL + Eloquent ORM + Migrations                         │
└─────────────────────────────────────────────────────────────┘
```

### 1.2 Design Patterns Used

**Repository Pattern**: For data access abstraction
**Service Layer Pattern**: For business logic separation
**Policy Pattern**: For authorization logic
**Observer Pattern**: For model event handling
**Factory Pattern**: For test data generation

---

## 2. Directory Structure

```
intranet/
├── app/
│   ├── Http/
│   │   ├── Controllers/           # HTTP request handlers
│   │   │   ├── Auth/             # Authentication controllers
│   │   │   ├── DocumentController.php
│   │   │   ├── AnnouncementController.php
│   │   │   ├── UserController.php
│   │   │   └── DashboardController.php
│   │   ├── Middleware/           # Request middleware
│   │   │   ├── AdminRequired.php
│   │   │   └── LogActivity.php
│   │   └── Requests/             # Form request validation
│   │       ├── DocumentStoreRequest.php
│   │       └── UserStoreRequest.php
│   ├── Models/                   # Eloquent models
│   │   ├── User.php
│   │   ├── Document.php
│   │   ├── Announcement.php
│   │   ├── Department.php
│   │   └── Centre.php
│   ├── Policies/                 # Authorization policies
│   │   ├── DocumentPolicy.php
│   │   ├── UserPolicy.php
│   │   └── AnnouncementPolicy.php
│   ├── Services/                 # Business logic services
│   │   ├── DocumentService.php
│   │   ├── UserService.php
│   │   └── AnalyticsService.php
│   └── Observers/                # Model observers
│       ├── DocumentObserver.php
│       └── UserObserver.php
├── database/
│   ├── migrations/               # Database migrations
│   ├── seeders/                  # Database seeders
│   └── factories/                # Model factories
├── resources/
│   ├── views/                    # Blade templates
│   │   ├── layouts/             # Layout templates
│   │   ├── components/          # Reusable components
│   │   ├── auth/                # Authentication views
│   │   ├── documents/           # Document management views
│   │   ├── announcements/       # Announcement views
│   │   └── dashboard.blade.php  # Main dashboard
│   ├── js/                      # JavaScript files
│   │   ├── app.js              # Main JS entry point
│   │   └── components/         # Alpine.js components
│   └── css/                     # Styling files
│       └── app.css             # Main CSS with Tailwind
├── routes/
│   ├── web.php                  # Web routes
│   ├── api.php                  # API routes
│   └── auth.php                 # Authentication routes
├── storage/
│   ├── app/
│   │   └── public/
│   │       └── documents/       # Uploaded documents
│   └── logs/                    # Application logs
└── documentation/               # Project documentation
    ├── README.md
    ├── USER_GUIDE.md
    ├── DEPLOYMENT.md
    ├── SRS.md
    ├── SDD.md
    ├── SOURCE_CODE_DOCUMENTATION.md
    └── SLA.md
```

---

## 3. Core Components

### 3.1 DocumentController

**Purpose**: Handles all document-related HTTP requests including upload, download, search, and management.

**Location**: `app/Http/Controllers/DocumentController.php`

**Key Methods**:

```php
<?php

namespace App\Http\Controllers;

use App\Http\Requests\DocumentStoreRequest;
use App\Models\Document;
use App\Services\DocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    protected $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }

    /**
     * Display document management interface
     * 
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $this->authorize('viewAny', Document::class);
        
        $stats = $this->documentService->getDashboardStats();
        $departments = Department::with('documents')->get();
        
        return view('documents.index', compact('stats', 'departments'));
    }

    /**
     * Store uploaded document
     * 
     * @param DocumentStoreRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(DocumentStoreRequest $request)
    {
        $this->authorize('create', Document::class);
        
        $document = $this->documentService->storeDocument(
            $request->validated(),
            $request->file('file'),
            auth()->user()
        );
        
        return redirect()->back()->with('success', 'Document uploaded successfully');
    }

    /**
     * Download document and track download count
     * 
     * @param Document $document
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function download(Document $document)
    {
        $this->authorize('view', $document);
        
        // Increment download counter
        $document->increment('download_count');
        
        // Log download activity
        activity()
            ->causedBy(auth()->user())
            ->performedOn($document)
            ->log('Document downloaded');
        
        return Storage::download($document->file_path, $document->original_filename);
    }

    /**
     * Search documents with filters
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        $filters = $request->only(['department_id', 'file_type', 'date_from', 'date_to']);
        
        $documents = $this->documentService->searchDocuments($query, $filters);
        
        return response()->json([
            'success' => true,
            'data' => $documents,
            'query' => $query,
            'filters' => $filters
        ]);
    }
}
```

### 3.2 DocumentService

**Purpose**: Contains business logic for document operations, separated from controller logic.

**Location**: `app/Services/DocumentService.php`

```php
<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Department;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService
{
    /**
     * Store uploaded document with validation and processing
     * 
     * @param array $data
     * @param UploadedFile $file
     * @param User $user
     * @return Document
     */
    public function storeDocument(array $data, UploadedFile $file, $user)
    {
        // Generate unique filename
        $filename = $this->generateUniqueFilename($file);
        
        // Store file
        $path = $file->storeAs('documents', $filename, 'public');
        
        // Create document record
        $document = Document::create([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
            'department_id' => $data['department_id'],
            'uploaded_by' => $user->id,
        ]);
        
        // Clear relevant caches
        $this->clearDocumentCaches();
        
        return $document;
    }

    /**
     * Search documents with advanced filtering
     * 
     * @param string|null $query
     * @param array $filters
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function searchDocuments($query = null, array $filters = [])
    {
        return Document::query()
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('title', 'LIKE', "%{$query}%")
                            ->orWhere('description', 'LIKE', "%{$query}%")
                            ->orWhere('original_filename', 'LIKE', "%{$query}%");
                });
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
            ->with(['department:id,name,code', 'uploader:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
    }

    /**
     * Get dashboard statistics with caching
     * 
     * @return array
     */
    public function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', 300, function () {
            $stats = [
                'total_documents' => Document::count(),
                'recent_uploads' => Document::where('created_at', '>=', now()->subDays(7))->count(),
                'total_downloads' => Document::sum('download_count'),
                'popular_documents' => Document::with('department:id,name')
                    ->orderBy('download_count', 'desc')
                    ->limit(5)
                    ->get(['id', 'title', 'download_count', 'department_id'])
            ];

            // Department-wise document counts
            $departmentStats = Department::withCount('documents')
                ->orderBy('documents_count', 'desc')
                ->get(['id', 'name', 'code']);

            $stats['category_counts'] = $departmentStats->mapWithKeys(function ($dept) {
                return [$dept->name => $dept->documents_count];
            })->toArray();

            return $stats;
        });
    }

    /**
     * Generate unique filename to prevent conflicts
     * 
     * @param UploadedFile $file
     * @return string
     */
    private function generateUniqueFilename(UploadedFile $file)
    {
        $extension = $file->getClientOriginalExtension();
        $basename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME));
        
        return $basename . '_' . time() . '_' . Str::random(8) . '.' . $extension;
    }

    /**
     * Clear document-related caches
     */
    private function clearDocumentCaches()
    {
        Cache::forget('dashboard_stats');
        Cache::tags(['documents'])->flush();
    }
}
```

### 3.3 Document Model

**Purpose**: Eloquent model representing documents with relationships and business logic.

**Location**: `app/Models/Document.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'filename',
        'original_filename',
        'file_path',
        'file_size',
        'mime_type',
        'department_id',
        'uploaded_by',
        'download_count'
    ];

    protected $casts = [
        'file_size' => 'integer',
        'download_count' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Document belongs to a department
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Document belongs to uploader (user)
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Get formatted file size
     * 
     * @return string
     */
    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }
        
        return $bytes . ' bytes';
    }

    /**
     * Get file type icon based on mime type
     * 
     * @return string
     */
    public function getFileIconAttribute(): string
    {
        $mimeType = $this->mime_type;
        
        if (str_contains($mimeType, 'pdf')) {
            return 'document-text';
        } elseif (str_contains($mimeType, 'word')) {
            return 'document';
        } elseif (str_contains($mimeType, 'excel') || str_contains($mimeType, 'spreadsheet')) {
            return 'table';
        } elseif (str_contains($mimeType, 'image')) {
            return 'photograph';
        } elseif (str_contains($mimeType, 'powerpoint') || str_contains($mimeType, 'presentation')) {
            return 'presentation-chart-bar';
        }
        
        return 'document';
    }

    /**
     * Check if file exists in storage
     * 
     * @return bool
     */
    public function fileExists(): bool
    {
        return Storage::exists($this->file_path);
    }

    /**
     * Get download URL
     * 
     * @return string
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('documents.download', $this);
    }

    /**
     * Scope for popular documents
     */
    public function scopePopular($query, int $limit = 10)
    {
        return $query->orderBy('download_count', 'desc')->limit($limit);
    }

    /**
     * Scope for recent documents
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }
}
```

---

## 4. API Documentation

### 4.1 API Routes

**Base URL**: `/api/v1`
**Authentication**: Required for all endpoints

```php
// routes/api.php

Route::prefix('v1')->middleware(['auth:sanctum'])->group(function () {
    // Documents
    Route::apiResource('documents', DocumentController::class);
    Route::get('documents/search', [DocumentController::class, 'search']);
    Route::post('documents/{document}/download', [DocumentController::class, 'download']);
    
    // Departments
    Route::apiResource('departments', DepartmentController::class);
    Route::get('departments/{department}/documents', [DepartmentController::class, 'documents']);
    
    // Analytics
    Route::get('analytics/dashboard', [AnalyticsController::class, 'dashboard']);
    Route::get('analytics/departments', [AnalyticsController::class, 'departments']);
    
    // Users (Admin only)
    Route::middleware(['admin'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::post('users/import', [UserController::class, 'import']);
    });
});
```

### 4.2 API Endpoints

#### 4.2.1 Documents API

**GET /api/v1/documents**
```json
{
  "method": "GET",
  "endpoint": "/api/v1/documents",
  "description": "Get paginated list of documents",
  "parameters": {
    "page": "integer (optional) - Page number",
    "per_page": "integer (optional) - Items per page (max 50)",
    "department_id": "integer (optional) - Filter by department",
    "search": "string (optional) - Search in title/description"
  },
  "response": {
    "success": true,
    "data": {
      "documents": [
        {
          "id": 1,
          "title": "Annual Report 2024",
          "description": "Comprehensive annual report",
          "filename": "annual_report_2024_1234567890_abc123.pdf",
          "original_filename": "Annual Report 2024.pdf",
          "file_size": 2048576,
          "formatted_file_size": "2.00 MB",
          "mime_type": "application/pdf",
          "download_count": 45,
          "created_at": "2025-09-01T10:30:00Z",
          "updated_at": "2025-09-08T15:45:00Z",
          "department": {
            "id": 1,
            "name": "Executive Office",
            "code": "EXEC"
          },
          "uploader": {
            "id": 5,
            "name": "John Doe"
          }
        }
      ]
    },
    "meta": {
      "current_page": 1,
      "per_page": 20,
      "total": 150,
      "last_page": 8,
      "from": 1,
      "to": 20
    }
  }
}
```

**POST /api/v1/documents**
```json
{
  "method": "POST",
  "endpoint": "/api/v1/documents",
  "description": "Upload new document",
  "headers": {
    "Content-Type": "multipart/form-data"
  },
  "parameters": {
    "title": "string (required) - Document title",
    "description": "string (optional) - Document description",
    "file": "file (required) - Document file (max 50MB)",
    "department_id": "integer (required) - Department ID"
  },
  "response": {
    "success": true,
    "message": "Document uploaded successfully",
    "data": {
      "document": {
        "id": 151,
        "title": "New Policy Document",
        "filename": "new_policy_document_1234567890_xyz789.pdf",
        "file_size": 1024000,
        "department_id": 2,
        "uploaded_by": 1,
        "created_at": "2025-09-08T16:00:00Z"
      }
    }
  }
}
```

#### 4.2.2 Search API

**GET /api/v1/documents/search**
```json
{
  "method": "GET",
  "endpoint": "/api/v1/documents/search",
  "description": "Advanced document search",
  "parameters": {
    "q": "string (required) - Search query",
    "department_id": "integer (optional) - Filter by department",
    "file_type": "string (optional) - Filter by file type (pdf, word, excel, image)",
    "date_from": "date (optional) - Start date filter (YYYY-MM-DD)",
    "date_to": "date (optional) - End date filter (YYYY-MM-DD)",
    "sort": "string (optional) - Sort field (created_at, download_count, title)",
    "order": "string (optional) - Sort order (asc, desc)"
  },
  "response": {
    "success": true,
    "data": {
      "documents": [...],
      "total_results": 25,
      "search_time": "0.045s"
    },
    "query": "annual report",
    "filters": {
      "department_id": 1,
      "file_type": "pdf"
    }
  }
}
```

### 4.3 Error Responses

```json
{
  "success": false,
  "error": {
    "code": "VALIDATION_ERROR",
    "message": "The given data was invalid.",
    "details": {
      "title": ["The title field is required."],
      "file": ["The file must be a file of type: pdf, doc, docx."]
    }
  }
}
```

---

## 5. Database Layer

### 5.1 Migration Files

**Location**: `database/migrations/`

#### Create Documents Table

```php
<?php
// database/migrations/2025_09_01_000001_create_documents_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('filename');
            $table->string('original_filename');
            $table->string('file_path', 500);
            $table->bigInteger('file_size')->unsigned();
            $table->string('mime_type', 100);
            $table->foreignId('department_id')->constrained()->onDelete('cascade');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->integer('download_count')->unsigned()->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Indexes for performance
            $table->index(['department_id', 'created_at']);
            $table->index(['uploaded_by']);
            $table->index(['created_at']);
            $table->index(['download_count']);
            
            // Full-text search index
            $table->fullText(['title', 'description']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
};
```

### 5.2 Model Factories

**Location**: `database/factories/`

```php
<?php
// database/factories/DocumentFactory.php

namespace Database\Factories;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    public function definition()
    {
        $fileTypes = [
            ['ext' => 'pdf', 'mime' => 'application/pdf'],
            ['ext' => 'docx', 'mime' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
            ['ext' => 'xlsx', 'mime' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
        ];
        
        $fileType = $this->faker->randomElement($fileTypes);
        $originalName = $this->faker->words(3, true) . '.' . $fileType['ext'];
        $filename = str_replace(' ', '_', strtolower($originalName)) . '_' . time() . '_' . $this->faker->randomNumber(6) . '.' . $fileType['ext'];
        
        return [
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(2),
            'filename' => $filename,
            'original_filename' => $originalName,
            'file_path' => 'documents/' . $filename,
            'file_size' => $this->faker->numberBetween(1024, 52428800), // 1KB to 50MB
            'mime_type' => $fileType['mime'],
            'department_id' => Department::factory(),
            'uploaded_by' => User::factory(),
            'download_count' => $this->faker->numberBetween(0, 100),
            'is_active' => true,
        ];
    }
}
```

### 5.3 Database Seeders

```php
<?php
// database/seeders/DocumentSeeder.php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    public function run()
    {
        $departments = Department::all();
        $admins = User::where('role', 'admin')->get();
        
        foreach ($departments as $department) {
            // Create 5-15 documents per department
            $documentCount = rand(5, 15);
            
            Document::factory($documentCount)->create([
                'department_id' => $department->id,
                'uploaded_by' => $admins->random()->id,
            ]);
        }
        
        // Create some popular documents
        Document::factory(10)->create([
            'download_count' => rand(50, 200),
            'department_id' => $departments->random()->id,
            'uploaded_by' => $admins->random()->id,
        ]);
    }
}
```

---

## 6. Frontend Components

### 6.1 Alpine.js Components

**Location**: `resources/js/components/`

#### Document Management Component

```javascript
// resources/js/components/documents.js

function documentsApp() {
    return {
        // State
        viewMode: 'cards', // 'cards' or 'list'
        selectedDepartment: null,
        searchQuery: '',
        documents: [],
        loading: false,
        filters: {
            file_type: '',
            date_from: '',
            date_to: ''
        },

        // Initialization
        init() {
            this.loadInitialData();
            this.setupSearchDebounce();
        },

        // View mode switching
        switchToList(departmentId = null) {
            this.viewMode = 'list';
            this.selectedDepartment = departmentId;
            this.loadDocuments();
        },

        switchToCards() {
            this.viewMode = 'cards';
            this.selectedDepartment = null;
            this.searchQuery = '';
            this.resetFilters();
        },

        // Data loading
        async loadDocuments() {
            this.loading = true;
            
            try {
                const params = new URLSearchParams({
                    ...(this.selectedDepartment && { department_id: this.selectedDepartment }),
                    ...(this.searchQuery && { q: this.searchQuery }),
                    ...this.filters
                });

                const response = await fetch(`/api/v1/documents/search?${params}`);
                const data = await response.json();
                
                if (data.success) {
                    this.documents = data.data.documents;
                } else {
                    this.showError('Failed to load documents');
                }
            } catch (error) {
                console.error('Error loading documents:', error);
                this.showError('Network error occurred');
            } finally {
                this.loading = false;
            }
        },

        // Search functionality
        setupSearchDebounce() {
            this.$watch('searchQuery', this.debounce(() => {
                if (this.viewMode === 'list') {
                    this.loadDocuments();
                }
            }, 300));
        },

        // Filter management
        applyFilters() {
            this.loadDocuments();
        },

        resetFilters() {
            this.filters = {
                file_type: '',
                date_from: '',
                date_to: ''
            };
            this.loadDocuments();
        },

        // File upload
        async uploadFile(event) {
            const file = event.target.files[0];
            if (!file) return;

            const formData = new FormData();
            formData.append('file', file);
            formData.append('title', this.uploadTitle || file.name);
            formData.append('description', this.uploadDescription || '');
            formData.append('department_id', this.selectedDepartment);

            try {
                this.uploading = true;
                const response = await fetch('/api/v1/documents', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();
                
                if (data.success) {
                    this.showSuccess('Document uploaded successfully');
                    this.loadDocuments();
                    this.resetUploadForm();
                } else {
                    this.showError(data.message || 'Upload failed');
                }
            } catch (error) {
                console.error('Upload error:', error);
                this.showError('Upload failed');
            } finally {
                this.uploading = false;
            }
        },

        // Utility functions
        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        },

        showSuccess(message) {
            // Implementation depends on notification system
            console.log('Success:', message);
        },

        showError(message) {
            // Implementation depends on notification system
            console.error('Error:', message);
        }
    }
}

// Register component globally
window.documentsApp = documentsApp;
```

### 6.2 Blade Components

**Location**: `resources/views/components/`

#### Document Card Component

```php
{{-- resources/views/components/document-card.blade.php --}}

@props(['document'])

<div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-200">
    {{-- Header --}}
    <div class="flex items-start justify-between mb-4">
        <div class="flex items-center space-x-3">
            {{-- File Icon --}}
            <div class="flex-shrink-0">
                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                    @if($document->file_icon === 'document-text')
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                    @elseif($document->file_icon === 'photograph')
                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                    @else
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd" />
                    @endif
                </svg>
            </div>
            
            {{-- Document Info --}}
            <div class="min-w-0 flex-1">
                <h3 class="text-sm font-medium text-gray-900 truncate">{{ $document->title }}</h3>
                <p class="text-xs text-gray-500">{{ $document->department->name }}</p>
            </div>
        </div>
        
        {{-- Download Count --}}
        <div class="flex items-center text-xs text-gray-500">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
            {{ $document->download_count }}
        </div>
    </div>
    
    {{-- Description --}}
    @if($document->description)
        <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $document->description }}</p>
    @endif
    
    {{-- Footer --}}
    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
        <div class="flex items-center space-x-4 text-xs text-gray-500">
            <span>{{ $document->formatted_file_size }}</span>
            <span>{{ $document->created_at->format('M j, Y') }}</span>
        </div>
        
        {{-- Download Button --}}
        <a href="{{ route('documents.download', $document) }}" 
           class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200">
            Download
        </a>
    </div>
</div>
```

---

## 7. Security Implementation

### 7.1 Authentication System

**Laravel Sanctum Configuration**:

```php
// config/sanctum.php

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        env('APP_URL') ? ','.parse_url(env('APP_URL'), PHP_URL_HOST) : ''
    ))),

    'guard' => ['web'],

    'expiration' => null,

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
    ],
];
```

### 7.2 Authorization Policies

**Document Policy Implementation**:

```php
<?php
// app/Policies/DocumentPolicy.php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine if user can view any documents
     */
    public function viewAny(User $user): bool
    {
        return $user->isActive();
    }

    /**
     * Determine if user can view specific document
     */
    public function view(User $user, Document $document): bool
    {
        // Super admins can view all documents
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admins can view documents in their department
        if ($user->isAdmin() && $user->department_id === $document->department_id) {
            return true;
        }

        // Regular users can view documents in their department
        return $user->department_id === $document->department_id;
    }

    /**
     * Determine if user can create documents
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isSuperAdmin();
    }

    /**
     * Determine if user can update document
     */
    public function update(User $user, Document $document): bool
    {
        // Super admins can update any document
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Document uploader can update their own documents
        if ($user->id === $document->uploaded_by) {
            return true;
        }

        // Department admins can update documents in their department
        return $user->isAdmin() && $user->department_id === $document->department_id;
    }

    /**
     * Determine if user can delete document
     */
    public function delete(User $user, Document $document): bool
    {
        return $this->update($user, $document);
    }
}
```

### 7.3 Security Middleware

```php
<?php
// app/Http/Middleware/AdminRequired.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminRequired
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Administrative access required.');
        }

        return $next($request);
    }
}
```

---

## 8. Testing Framework

### 8.1 Feature Tests

```php
<?php
// tests/Feature/DocumentControllerTest.php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Department;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function admin_can_upload_document()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $department = Department::factory()->create();
        
        $file = UploadedFile::fake()->create('test.pdf', 1000, 'application/pdf');
        
        $response = $this->actingAs($admin)
            ->post('/documents', [
                'title' => 'Test Document',
                'description' => 'Test description',
                'file' => $file,
                'department_id' => $department->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('documents', [
            'title' => 'Test Document',
            'department_id' => $department->id,
            'uploaded_by' => $admin->id,
        ]);
        
        Storage::disk('public')->assertExists('documents/' . Document::latest()->first()->filename);
    }

    /** @test */
    public function regular_user_cannot_upload_document()
    {
        $user = User::factory()->create(['role' => 'user']);
        $department = Department::factory()->create();
        
        $file = UploadedFile::fake()->create('test.pdf', 1000, 'application/pdf');
        
        $response = $this->actingAs($user)
            ->post('/documents', [
                'title' => 'Test Document',
                'file' => $file,
                'department_id' => $department->id,
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('documents', ['title' => 'Test Document']);
    }

    /** @test */
    public function user_can_download_document_from_their_department()
    {
        $department = Department::factory()->create();
        $user = User::factory()->create([
            'role' => 'user',
            'department_id' => $department->id
        ]);
        
        $document = Document::factory()->create([
            'department_id' => $department->id,
            'download_count' => 5
        ]);
        
        // Create fake file
        Storage::disk('public')->put($document->file_path, 'fake content');
        
        $response = $this->actingAs($user)
            ->get("/documents/{$document->id}/download");

        $response->assertStatus(200);
        
        // Check download count increased
        $this->assertEquals(6, $document->fresh()->download_count);
    }
}
```

### 8.2 Unit Tests

```php
<?php
// tests/Unit/DocumentServiceTest.php

namespace Tests\Unit;

use App\Models\Document;
use App\Models\Department;
use App\Models\User;
use App\Services\DocumentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DocumentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DocumentService();
        Storage::fake('public');
    }

    /** @test */
    public function it_can_store_document_with_proper_filename()
    {
        $user = User::factory()->create();
        $department = Department::factory()->create();
        $file = UploadedFile::fake()->create('Test Document.pdf', 1000, 'application/pdf');
        
        $data = [
            'title' => 'Test Document',
            'description' => 'Test description',
            'department_id' => $department->id,
        ];

        $document = $this->service->storeDocument($data, $file, $user);

        $this->assertInstanceOf(Document::class, $document);
        $this->assertEquals('Test Document', $document->title);
        $this->assertEquals($user->id, $document->uploaded_by);
        $this->assertStringContains('test_document', $document->filename);
        $this->assertTrue(Storage::disk('public')->exists($document->file_path));
    }

    /** @test */
    public function it_can_search_documents_with_filters()
    {
        $department1 = Department::factory()->create(['name' => 'IT']);
        $department2 = Department::factory()->create(['name' => 'HR']);
        
        Document::factory()->create([
            'title' => 'Annual Report 2024',
            'department_id' => $department1->id,
            'mime_type' => 'application/pdf'
        ]);
        
        Document::factory()->create([
            'title' => 'Monthly Update',
            'department_id' => $department2->id,
            'mime_type' => 'application/msword'
        ]);

        // Search by query
        $results = $this->service->searchDocuments('Annual');
        $this->assertCount(1, $results);
        $this->assertEquals('Annual Report 2024', $results->first()->title);

        // Search with department filter
        $results = $this->service->searchDocuments(null, ['department_id' => $department1->id]);
        $this->assertCount(1, $results);
        $this->assertEquals($department1->id, $results->first()->department_id);
    }
}
```

---

## 9. Deployment Scripts

### 9.1 Deployment Script

```bash
#!/bin/bash
# deploy.sh - Production deployment script

set -e

# Configuration
APP_DIR="/var/www/nimr-intranet"
BACKUP_DIR="/var/backups/nimr-intranet"
GIT_REPO="https://github.com/nimr/intranet.git"
PHP_USER="www-data"

echo "Starting deployment process..."

# Create backup
echo "Creating backup..."
BACKUP_NAME="backup_$(date +%Y%m%d_%H%M%S)"
mkdir -p "$BACKUP_DIR/$BACKUP_NAME"

# Backup database
mysqldump -u nimr_user -p nimr_intranet > "$BACKUP_DIR/$BACKUP_NAME/database.sql"

# Backup files
cp -r "$APP_DIR/storage/app/public" "$BACKUP_DIR/$BACKUP_NAME/"
cp "$APP_DIR/.env" "$BACKUP_DIR/$BACKUP_NAME/"

echo "Backup created: $BACKUP_NAME"

# Pull latest code
echo "Pulling latest code..."
cd $APP_DIR
git pull origin main

# Install dependencies
echo "Installing dependencies..."
sudo -u $PHP_USER composer install --optimize-autoloader --no-dev
sudo -u $PHP_USER npm ci
sudo -u $PHP_USER npm run build

# Run migrations
echo "Running database migrations..."
sudo -u $PHP_USER php artisan migrate --force

# Clear and cache configuration
echo "Optimizing application..."
sudo -u $PHP_USER php artisan config:cache
sudo -u $PHP_USER php artisan route:cache
sudo -u $PHP_USER php artisan view:cache

# Set permissions
echo "Setting permissions..."
sudo chown -R $PHP_USER:$PHP_USER $APP_DIR
sudo chmod -R 755 $APP_DIR
sudo chmod -R 775 $APP_DIR/storage $APP_DIR/bootstrap/cache

# Restart services
echo "Restarting services..."
sudo systemctl reload nginx
sudo systemctl restart php8.1-fpm

echo "Deployment completed successfully!"
echo "Backup stored at: $BACKUP_DIR/$BACKUP_NAME"
```

### 9.2 Rollback Script

```bash
#!/bin/bash
# rollback.sh - Emergency rollback script

set -e

BACKUP_NAME=$1
APP_DIR="/var/www/nimr-intranet"
BACKUP_DIR="/var/backups/nimr-intranet"
PHP_USER="www-data"

if [ -z "$BACKUP_NAME" ]; then
    echo "Usage: $0 <backup_name>"
    echo "Available backups:"
    ls -la $BACKUP_DIR
    exit 1
fi

if [ ! -d "$BACKUP_DIR/$BACKUP_NAME" ]; then
    echo "Backup not found: $BACKUP_NAME"
    exit 1
fi

echo "Rolling back to: $BACKUP_NAME"

# Restore database
echo "Restoring database..."
mysql -u nimr_user -p nimr_intranet < "$BACKUP_DIR/$BACKUP_NAME/database.sql"

# Restore files
echo "Restoring files..."
cp -r "$BACKUP_DIR/$BACKUP_NAME/public" "$APP_DIR/storage/app/"
cp "$BACKUP_DIR/$BACKUP_NAME/.env" "$APP_DIR/"

# Set permissions
sudo chown -R $PHP_USER:$PHP_USER $APP_DIR
sudo chmod -R 775 $APP_DIR/storage

# Clear caches
sudo -u $PHP_USER php artisan config:clear
sudo -u $PHP_USER php artisan route:clear
sudo -u $PHP_USER php artisan view:clear

echo "Rollback completed successfully!"
```

---

## 10. Code Standards

### 10.1 PHP Code Standards

**PSR-12 Compliance**: All PHP code follows PSR-12 coding standards.

**Naming Conventions**:
- Classes: `PascalCase` (e.g., `DocumentController`)
- Methods: `camelCase` (e.g., `storeDocument`)
- Variables: `camelCase` (e.g., `$documentService`)
- Constants: `SCREAMING_SNAKE_CASE` (e.g., `MAX_FILE_SIZE`)

**Documentation Standards**:
```php
/**
 * Store uploaded document with validation and processing
 * 
 * @param array $data Validated form data
 * @param UploadedFile $file Uploaded file instance
 * @param User $user User uploading the document
 * @return Document Created document instance
 * @throws ValidationException When file validation fails
 */
public function storeDocument(array $data, UploadedFile $file, User $user): Document
{
    // Implementation
}
```

### 10.2 Database Standards

**Migration Conventions**:
- Descriptive names: `2025_09_01_create_documents_table`
- Proper indexing for performance
- Foreign key constraints for data integrity
- Rollback methods for all migrations

**Model Conventions**:
- Fillable properties explicitly defined
- Relationships properly documented
- Accessors and mutators for data formatting
- Scopes for common queries

### 10.3 Frontend Standards

**JavaScript/Alpine.js**:
- Component-based architecture
- Descriptive function and variable names
- Error handling for all async operations
- Performance optimization (debouncing, lazy loading)

**CSS/Tailwind**:
- Utility-first approach
- Component extraction for reusable patterns
- Responsive design principles
- Accessibility considerations

---

**Document Control:**
- **Classification**: Internal Technical Documentation
- **Distribution**: Development Team, Technical Lead
- **Review Cycle**: Monthly during active development
- **Last Updated**: September 8, 2025
- **Version**: 2.0.0

**Approval:**
- **Senior Developer**: [Name, Date]
- **Technical Lead**: [Name, Date]
- **Code Review**: Completed
