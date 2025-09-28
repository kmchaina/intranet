<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Headquarters;
use App\Models\Centre;
use App\Models\Station;
use App\Models\Department;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        // Get the default NIMR headquarters
        $headquarters = Headquarters::where('is_active', true)->first();
        $centres = Centre::where('is_active', true)->get();
        $stations = collect(); // Empty collection, will be populated via AJAX
        $hqDepartments = Department::where('is_active', true)->where('headquarters_id', optional($headquarters)->id)->orderBy('name')->get();

        return view('auth.register', compact('headquarters', 'centres', 'stations', 'hqDepartments'));
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:' . User::class,
                // Only enforce domain restriction in production
                ...(app()->environment('production') ? ['regex:/^[a-zA-Z0-9._%+-]+@nimr\.or\.tz$/'] : [])
            ],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'organizational_level' => ['required', 'in:headquarters,centre'],
            'centre_id' => ['nullable', 'exists:centres,id'],
            'work_location' => ['nullable', 'string'],
            'station_id' => ['nullable', 'exists:stations,id'],
            'department_id' => ['nullable', 'exists:departments,id'],
        ], [
            ...(app()->environment('production') ? ['email.regex' => 'Email address must be from the @nimr.or.tz domain.'] : [])
        ]);

        // Custom validation for organizational hierarchy
        $this->validateSimplifiedHierarchy($request);

        // Get the default headquarters
        $headquarters = Headquarters::where('is_active', true)->first();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'headquarters_id' => $headquarters->id, // Always assign to headquarters
            'centre_id' => $request->centre_id,
            'station_id' => $request->station_id,
            'department_id' => $request->department_id,
        ]);

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }

    /**
     * Get stations for a specific centre
     */
    public function getStations(Request $request)
    {
        $centreId = $request->centre_id;
        $stations = Station::where('centre_id', $centreId)->where('is_active', true)->get();

        return response()->json($stations);
    }

    /**
     * Validate simplified organizational hierarchy
     */
    private function validateSimplifiedHierarchy(Request $request)
    {
        $level = $request->organizational_level;
        $workLocation = $request->work_location;

        switch ($level) {
            case 'headquarters':
                // No additional validation needed for headquarters level
                break;

            case 'centre':
                if (!$request->centre_id) {
                    throw ValidationException::withMessages([
                        'centre_id' => 'Centre selection is required for centre level.'
                    ]);
                }

                // Check if centre has stations
                $centre = Centre::find($request->centre_id);
                if ($centre && $centre->stations()->where('is_active', true)->exists()) {
                    // Centre has stations, work_location is required
                    if (!$workLocation) {
                        throw ValidationException::withMessages([
                            'work_location' => 'Please specify whether you are situated at the centre or a station.'
                        ]);
                    }

                    if ($workLocation !== 'centre' && strpos($workLocation, 'station_') === 0) {
                        // Extract station ID from work_location (station_123 -> 123)
                        $stationId = str_replace('station_', '', $workLocation);
                        $request->merge(['station_id' => $stationId]);

                        // Validate that station exists and belongs to centre
                        $station = Station::find($stationId);
                        if (!$station) {
                            throw ValidationException::withMessages([
                                'work_location' => 'The selected station is invalid.'
                            ]);
                        }
                        if ($station->centre_id != $request->centre_id) {
                            throw ValidationException::withMessages([
                                'work_location' => 'The selected station does not belong to the selected centre.'
                            ]);
                        }
                    } elseif ($workLocation === 'centre') {
                        // User is at centre, ensure station_id is null
                        $request->merge(['station_id' => null]);
                    } else {
                        throw ValidationException::withMessages([
                            'work_location' => 'Invalid work location selection.'
                        ]);
                    }
                } else {
                    // Centre has no stations, ensure station_id is null
                    $request->merge(['station_id' => null]);
                }
                break;
        }
    }
}
