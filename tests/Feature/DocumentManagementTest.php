<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Document;
use App\Models\Department;
use App\Models\Centre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DocumentManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /** @test */
    public function authenticated_user_can_view_documents_list()
    {
        $user = User::factory()->create(['role' => 'staff']);
        $documents = Document::factory()->count(5)->create();

        $response = $this->actingAs($user)->get('/documents');

        $response->assertOk()
                ->assertViewIs('documents.index')
                ->assertViewHas('documents');
    }

    /** @test */
    public function admin_can_upload_document()
    {
        $user = User::factory()->create(['role' => 'centre_admin']);
        $department = Department::factory()->create();
        
        $file = UploadedFile::fake()->create('test-document.pdf', 1024, 'application/pdf');

        $response = $this->actingAs($user)->post('/documents', [
            'title' => 'Test Document',
            'description' => 'This is a test document',
            'department_id' => $department->id,
            'access_level' => 'public',
            'file' => $file
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('documents', [
            'title' => 'Test Document',
            'uploaded_by' => $user->id,
            'department_id' => $department->id
        ]);

        Storage::disk('public')->assertExists('documents/' . Document::latest()->first()->filename);
    }

    /** @test */
    public function staff_cannot_upload_documents()
    {
        $user = User::factory()->create(['role' => 'staff']);
        $file = UploadedFile::fake()->create('test-document.pdf', 1024);

        $response = $this->actingAs($user)->post('/documents', [
            'title' => 'Test Document',
            'file' => $file
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function user_can_download_accessible_document()
    {
        $user = User::factory()->create(['role' => 'staff']);
        $document = Document::factory()->create([
            'access_level' => 'public',
            'file_path' => 'documents/test.pdf'
        ]);

        // Create fake file
        Storage::disk('public')->put($document->file_path, 'fake file content');

        $response = $this->actingAs($user)->get("/documents/{$document->id}/download");

        $response->assertOk();
        $this->assertEquals(1, $document->fresh()->download_count);
    }

    /** @test */
    public function user_cannot_download_restricted_document_without_permission()
    {
        $user = User::factory()->create(['role' => 'staff']);
        $document = Document::factory()->create([
            'access_level' => 'confidential'
        ]);

        $response = $this->actingAs($user)->get("/documents/{$document->id}/download");

        $response->assertForbidden();
    }

    /** @test */
    public function admin_can_delete_documents_in_their_scope()
    {
        $centre = Centre::factory()->create();
        $department = Department::factory()->create(['centre_id' => $centre->id]);
        $admin = User::factory()->create([
            'role' => 'centre_admin',
            'centre_id' => $centre->id
        ]);
        
        $document = Document::factory()->create([
            'department_id' => $department->id,
            'file_path' => 'documents/test.pdf'
        ]);

        Storage::disk('public')->put($document->file_path, 'fake content');

        $response = $this->actingAs($admin)->delete("/documents/{$document->id}");

        $response->assertRedirect();
        $this->assertDatabaseMissing('documents', ['id' => $document->id]);
        Storage::disk('public')->assertMissing($document->file_path);
    }

    /** @test */
    public function document_search_returns_relevant_results()
    {
        $user = User::factory()->create();
        
        Document::factory()->create(['title' => 'Annual Financial Report']);
        Document::factory()->create(['title' => 'Monthly Budget Analysis']);
        Document::factory()->create(['title' => 'Staff Meeting Minutes']);

        $response = $this->actingAs($user)->get('/documents?search=financial');

        $response->assertOk()
                ->assertSeeText('Annual Financial Report')
                ->assertDontSeeText('Staff Meeting Minutes');
    }

    /** @test */
    public function file_upload_validates_file_type()
    {
        $user = User::factory()->create(['role' => 'centre_admin']);
        $department = Department::factory()->create();
        
        $file = UploadedFile::fake()->create('malicious.exe', 1024, 'application/x-executable');

        $response = $this->actingAs($user)->post('/documents', [
            'title' => 'Test Document',
            'department_id' => $department->id,
            'file' => $file
        ]);

        $response->assertSessionHasErrors(['file']);
        $this->assertDatabaseMissing('documents', ['title' => 'Test Document']);
    }

    /** @test */
    public function file_upload_validates_file_size()
    {
        $user = User::factory()->create(['role' => 'centre_admin']);
        $department = Department::factory()->create();
        
        // Create file larger than 50MB
        $file = UploadedFile::fake()->create('large-file.pdf', 60000, 'application/pdf');

        $response = $this->actingAs($user)->post('/documents', [
            'title' => 'Large Document',
            'department_id' => $department->id,
            'file' => $file
        ]);

        $response->assertSessionHasErrors(['file']);
    }

    /** @test */
    public function api_returns_documents_with_proper_structure()
    {
        $user = User::factory()->create();
        $documents = Document::factory()->count(3)->create();

        $response = $this->actingAs($user)->getJson('/api/v1/documents');

        $response->assertOk()
                ->assertJsonStructure([
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'filename',
                            'file_size',
                            'file_size_human',
                            'mime_type',
                            'access_level',
                            'download_count',
                            'permissions' => [
                                'can_view',
                                'can_update',
                                'can_delete',
                                'can_download'
                            ],
                            'created_at',
                            'updated_at'
                        ]
                    ],
                    'links',
                    'meta'
                ]);
    }

    /** @test */
    public function api_search_endpoint_works_correctly()
    {
        $user = User::factory()->create();
        
        Document::factory()->create(['title' => 'Project Report 2024']);
        Document::factory()->create(['title' => 'Budget Analysis']);

        $response = $this->actingAs($user)->getJson('/api/v1/documents/search?q=project');

        $response->assertOk()
                ->assertJsonFragment(['title' => 'Project Report 2024'])
                ->assertJsonMissing(['title' => 'Budget Analysis']);
    }

    /** @test */
    public function rate_limiting_works_on_api_endpoints()
    {
        $user = User::factory()->create();

        // Make multiple requests rapidly
        for ($i = 0; $i < 65; $i++) {
            $response = $this->actingAs($user)->getJson('/api/v1/documents');
        }

        $response->assertStatus(429); // Too Many Requests
        $response->assertJsonFragment(['message' => 'Too many requests. Please try again later.']);
    }
}
