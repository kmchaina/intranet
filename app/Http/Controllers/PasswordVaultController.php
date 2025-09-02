<?php

namespace App\Http\Controllers;

use App\Models\PasswordVault;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasswordVaultController extends Controller
{
    /**
     * Display a listing of password vault entries
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $category = $request->get('category');
        $folder = $request->get('folder');
        $search = $request->get('search');

        $query = PasswordVault::forUser($user)->with('user');

        // Apply filters
        if ($category) {
            $query->byCategory($category);
        }

        if ($folder) {
            $query->byFolder($folder);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('website_url', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%");
            });
        }

        $passwords = $query->orderBy('last_used_at', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get filter options
        $categories = [
            'general' => 'General',
            'work' => 'Work',
            'personal' => 'Personal',
            'social' => 'Social Media',
            'banking' => 'Banking',
            'shopping' => 'Shopping',
            'entertainment' => 'Entertainment',
            'education' => 'Education',
            'health' => 'Health',
        ];

        $folders = PasswordVault::forUser($user)
            ->whereNotNull('folder')
            ->distinct()
            ->pluck('folder')
            ->sort();

        return view('password-vault.index', compact('passwords', 'categories', 'folders', 'category', 'folder', 'search'));
    }

    /**
     * Show the form for creating a new password entry
     */
    public function create()
    {
        $categories = [
            'general' => 'General',
            'work' => 'Work',
            'personal' => 'Personal',
            'social' => 'Social Media',
            'banking' => 'Banking',
            'shopping' => 'Shopping',
            'entertainment' => 'Entertainment',
            'education' => 'Education',
            'health' => 'Health',
        ];

        return view('password-vault.create', compact('categories'));
    }

    /**
     * Store a newly created password entry
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'required|string|min:1',
            'notes' => 'nullable|string',
            'category' => 'required|string|max:255',
            'folder' => 'nullable|string|max:255',
            'is_favorite' => 'boolean',
        ]);

        $passwordVault = new PasswordVault([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'website_url' => $request->website_url,
            'username' => $request->username,
            'notes' => $request->notes,
            'category' => $request->category,
            'folder' => $request->folder,
            'is_favorite' => $request->boolean('is_favorite'),
        ]);

        $passwordVault->setPassword($request->password);
        $passwordVault->save();

        return redirect()->route('password-vault.index')
            ->with('success', 'Password entry created successfully!');
    }

    /**
     * Display the specified password entry
     */
    public function show(PasswordVault $passwordVault)
    {
        // Check if user can access this password
        if (!PasswordVault::forUser(Auth::user())->where('id', $passwordVault->id)->exists()) {
            abort(403, 'You do not have permission to view this password entry.');
        }

        $passwordVault->recordUsage();

        return view('password-vault.show', compact('passwordVault'));
    }

    /**
     * Show the form for editing the password entry
     */
    public function edit(PasswordVault $passwordVault)
    {
        // Check permissions
        if ($passwordVault->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own password entries.');
        }

        $categories = [
            'general' => 'General',
            'work' => 'Work',
            'personal' => 'Personal',
            'social' => 'Social Media',
            'banking' => 'Banking',
            'shopping' => 'Shopping',
            'entertainment' => 'Entertainment',
            'education' => 'Education',
            'health' => 'Health',
        ];

        return view('password-vault.edit', compact('passwordVault', 'categories'));
    }

    /**
     * Update the specified password entry
     */
    public function update(Request $request, PasswordVault $passwordVault)
    {
        // Check permissions
        if ($passwordVault->user_id !== Auth::id()) {
            abort(403, 'You can only edit your own password entries.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'website_url' => 'nullable|url|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string|min:1',
            'notes' => 'nullable|string',
            'category' => 'required|string|max:255',
            'folder' => 'nullable|string|max:255',
            'is_favorite' => 'boolean',
        ]);

        $passwordVault->update([
            'title' => $request->title,
            'website_url' => $request->website_url,
            'username' => $request->username,
            'notes' => $request->notes,
            'category' => $request->category,
            'folder' => $request->folder,
            'is_favorite' => $request->boolean('is_favorite'),
        ]);

        // Only update password if provided
        if ($request->password) {
            $passwordVault->setPassword($request->password);
            $passwordVault->save();
        }

        return redirect()->route('password-vault.show', $passwordVault)
            ->with('success', 'Password entry updated successfully!');
    }

    /**
     * Remove the specified password entry
     */
    public function destroy(PasswordVault $passwordVault)
    {
        // Check permissions
        if ($passwordVault->user_id !== Auth::id()) {
            abort(403, 'You can only delete your own password entries.');
        }

        $passwordVault->delete();

        return redirect()->route('password-vault.index')
            ->with('success', 'Password entry deleted successfully!');
    }

    /**
     * Record password usage
     */
    public function recordUsage(PasswordVault $passwordVault)
    {
        // Check if user can access this password
        if (!PasswordVault::forUser(Auth::user())->where('id', $passwordVault->id)->exists()) {
            abort(403, 'You do not have permission to access this password entry.');
        }

        $passwordVault->recordUsage();

        return response()->json(['success' => true]);
    }
}
