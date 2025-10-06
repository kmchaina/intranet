<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Centre;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CentreAdminController extends Controller
{
    public function index()
    {
        // Check if user is HQ Admin or Super Admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only HQ Administrators can manage centres.');
        }

        $centres = Centre::with(['stations', 'users'])
            ->withCount(['users', 'stations'])
            ->orderBy('name')
            ->get();

        return view('admin.centres.index', compact('centres'));
    }

    public function create()
    {
        // Check if user is HQ Admin or Super Admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only HQ Administrators can create centres.');
        }

        return view('admin.centres.create');
    }

    public function store(Request $request)
    {
        // Check if user is HQ Admin or Super Admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only HQ Administrators can create centres.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:centres,name',
            'code' => 'required|string|max:10|unique:centres,code',
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $centre = Centre::create([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'location' => $validated['location'],
                'description' => $validated['description'],
                'contact_person' => $validated['contact_person'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            DB::commit();

            return redirect()->route('admin.centres.index')
                ->with('success', 'Centre created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create centre: ' . $e->getMessage());
        }
    }

    public function show(Centre $centre)
    {
        // Check if user is HQ Admin or Super Admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only HQ Administrators can view centre details.');
        }

        $centre->load(['stations', 'users', 'stations.users']);

        return view('admin.centres.show', compact('centre'));
    }

    public function edit(Centre $centre)
    {
        // Check if user is HQ Admin or Super Admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only HQ Administrators can edit centres.');
        }

        return view('admin.centres.edit', compact('centre'));
    }

    public function update(Request $request, Centre $centre)
    {
        // Check if user is HQ Admin or Super Admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only HQ Administrators can update centres.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:centres,name,' . $centre->id,
            'code' => 'required|string|max:10|unique:centres,code,' . $centre->id,
            'location' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'contact_person' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        try {
            DB::beginTransaction();

            $centre->update([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'location' => $validated['location'],
                'description' => $validated['description'],
                'contact_person' => $validated['contact_person'],
                'contact_email' => $validated['contact_email'],
                'contact_phone' => $validated['contact_phone'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            DB::commit();

            return redirect()->route('admin.centres.index')
                ->with('success', 'Centre updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to update centre: ' . $e->getMessage());
        }
    }

    public function destroy(Centre $centre)
    {
        // Check if user is Super Admin only
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isSuperAdmin()) {
            abort(403, 'Only Super Administrators can delete centres.');
        }

        try {
            DB::beginTransaction();

            // Check if centre has users or stations
            if ($centre->users()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete centre with assigned users. Please reassign users first.');
            }

            if ($centre->stations()->count() > 0) {
                return redirect()->back()
                    ->with('error', 'Cannot delete centre with stations. Please delete or reassign stations first.');
            }

            $centre->delete();

            DB::commit();

            return redirect()->route('admin.centres.index')
                ->with('success', 'Centre deleted successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Failed to delete centre: ' . $e->getMessage());
        }
    }

    public function toggleStatus(Centre $centre)
    {
        // Check if user is HQ Admin or Super Admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only HQ Administrators can change centre status.');
        }

        try {
            $centre->update(['is_active' => !$centre->is_active]);

            $status = $centre->is_active ? 'activated' : 'deactivated';

            return response()->json([
                'success' => true,
                'message' => "Centre {$status} successfully!",
                'is_active' => $centre->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update centre status: ' . $e->getMessage()
            ]);
        }
    }

    public function getStats(Centre $centre)
    {
        // Check if user is HQ Admin or Super Admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if (!$user->isHqAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Only HQ Administrators can view centre statistics.');
        }

        $stats = [
            'total_users' => $centre->users()->count(),
            'active_users' => $centre->users()->whereNotNull('email_verified_at')->count(),
            'total_stations' => $centre->stations()->count(),
            'active_stations' => $centre->stations()->where('is_active', true)->count(),
            'total_announcements' => \App\Models\Announcement::whereHas('creator', function ($q) use ($centre) {
                $q->where('centre_id', $centre->id);
            })->count(),
            'total_documents' => \App\Models\Document::whereHas('uploader', function ($q) use ($centre) {
                $q->where('centre_id', $centre->id);
            })->count(),
        ];

        return response()->json($stats);
    }
}
