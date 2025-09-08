<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'filename' => $this->filename,
            'original_filename' => $this->original_filename,
            'file_size' => $this->file_size,
            'file_size_human' => $this->formatFileSize($this->file_size),
            'mime_type' => $this->mime_type,
            'file_type' => $this->getFileType(),
            'access_level' => $this->access_level,
            'download_count' => $this->download_count,
            'is_active' => $this->is_active,
            'department' => $this->whenLoaded('department', function () {
                return [
                    'id' => $this->department->id,
                    'name' => $this->department->name,
                    'code' => $this->department->code ?? null,
                ];
            }),
            'uploader' => $this->whenLoaded('uploader', function () {
                return [
                    'id' => $this->uploader->id,
                    'name' => $this->uploader->name,
                ];
            }),
            'permissions' => [
                'can_view' => $request->user()->can('view', $this->resource),
                'can_update' => $request->user()->can('update', $this->resource),
                'can_delete' => $request->user()->can('delete', $this->resource),
                'can_download' => $request->user()->can('download', $this->resource),
            ],
            'urls' => [
                'view' => route('documents.show', $this->id),
                'download' => route('documents.download', $this->id),
                'api_show' => route('api.v1.documents.show', $this->id),
            ],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_at_human' => $this->created_at->diffForHumans(),
            'updated_at_human' => $this->updated_at->diffForHumans(),
        ];
    }

    /**
     * Format file size in human readable format
     */
    private function formatFileSize(int $size): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, 2) . ' ' . $units[$i];
    }

    /**
     * Get simplified file type based on mime type
     */
    private function getFileType(): string
    {
        return match (true) {
            str_contains($this->mime_type, 'pdf') => 'PDF',
            str_contains($this->mime_type, 'word') || str_contains($this->mime_type, 'document') => 'Word',
            str_contains($this->mime_type, 'excel') || str_contains($this->mime_type, 'spreadsheet') => 'Excel',
            str_contains($this->mime_type, 'powerpoint') || str_contains($this->mime_type, 'presentation') => 'PowerPoint',
            str_contains($this->mime_type, 'image') => 'Image',
            str_contains($this->mime_type, 'text') => 'Text',
            str_contains($this->mime_type, 'video') => 'Video',
            str_contains($this->mime_type, 'audio') => 'Audio',
            default => 'File'
        };
    }
}
