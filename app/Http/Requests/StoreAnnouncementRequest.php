<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAnnouncementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->canCreateAnnouncements();
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|in:general,urgent,info,event',
            'priority' => 'required|in:low,medium,high,urgent',
            'target_scope' => 'required|in:all,headquarters,centres,stations,specific',
            'target_centres' => 'nullable|array',
            'target_centres.*' => 'exists:centres,id',
            'target_stations' => 'nullable|array',
            'target_stations.*' => 'exists:stations,id',
            'published_at' => 'nullable|date|after_or_equal:now',
            'expires_at' => 'nullable|date|after:published_at',
            'email_notification' => 'boolean',
            'attachments' => 'nullable|array|max:5',
            'attachments.*' => 'file|max:10240|mimes:pdf,doc,docx,txt,jpg,jpeg,png,gif,zip,rar',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Please provide a title for the announcement.',
            'content.required' => 'Please provide content for the announcement.',
            'target_scope.required' => 'Please select who should see this announcement.',
            'attachments.max' => 'You can only attach up to 5 files.',
            'attachments.*.max' => 'Each attachment must not exceed 10MB.',
        ];
    }
}
