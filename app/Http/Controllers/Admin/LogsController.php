<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LogsController extends Controller
{
    protected string $disk = 'local';
    protected string $path = 'logs';

    protected function ensureAuthorized(): void
    {
        if (!Auth::user()->isSuperAdmin()) {
            abort(403);
        }
    }

    public function index(): View
    {
        $this->ensureAuthorized();

        $files = collect(Storage::disk($this->disk)->files($this->path))
            ->filter(fn($file) => str_ends_with($file, '.log'))
            ->map(function ($file) {
                return [
                    'name' => basename($file),
                    'size' => Storage::disk($this->disk)->size($file),
                    'last_modified' => Storage::disk($this->disk)->lastModified($file),
                ];
            })
            ->sortByDesc('last_modified')
            ->values();

        return view('admin.logs.index', compact('files'));
    }

    public function show(string $file): View
    {
        $this->ensureAuthorized();

        $path = $this->path . '/' . $file;
        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404);
        }

        $content = collect(Storage::disk($this->disk)->lines($path))->reverse()->take(500)->reverse()->implode("\n");

        return view('admin.logs.show', compact('file', 'content'));
    }

    public function download(string $file)
    {
        $this->ensureAuthorized();

        $path = $this->path . '/' . $file;
        if (!Storage::disk($this->disk)->exists($path)) {
            abort(404);
        }

        return Storage::disk($this->disk)->download($path);
    }

    public function destroy(string $file): RedirectResponse
    {
        $this->ensureAuthorized();

        $path = $this->path . '/' . $file;
        if (Storage::disk($this->disk)->exists($path)) {
            Storage::disk($this->disk)->delete($path);
            return back()->with('success', 'Log deleted successfully.');
        }

        return back()->with('error', 'Log file not found.');
    }
}
