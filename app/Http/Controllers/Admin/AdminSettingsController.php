<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class AdminSettingsController extends Controller
{
    public function index()
    {
        // Check if user is super admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can access system settings.');
        }

        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        // Check if user is super admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can modify system settings.');
        }

        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url',
            'app_env' => 'required|in:local,staging,production',
            'app_debug' => 'boolean',
            'db_connection' => 'required|in:sqlite,mysql,pgsql',
            'db_database' => 'required|string',
            'db_host' => 'required|string',
            'mail_mailer' => 'required|in:smtp,sendmail,mailgun,ses,postmark',
            'mail_host' => 'nullable|string',
            'mail_port' => 'nullable|integer|min:1|max:65535',
            'mail_username' => 'nullable|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
            'session_lifetime' => 'required|integer|min:1|max:1440',
            'session_encrypt' => 'boolean',
            'bcrypt_rounds' => 'required|integer|min:4|max:31',
            'cache_driver' => 'required|in:file,redis,memcached,database',
            'cache_prefix' => 'nullable|string',
        ]);

        try {
            // Update .env file
            $this->updateEnvFile($validated);

            // Clear configuration cache
            Artisan::call('config:clear');

            return redirect()->back()->with('success', 'Settings updated successfully! Please restart the application for all changes to take effect.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    public function testEmail(Request $request)
    {
        // Check if user is super admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can test email configuration.');
        }

        try {
            Mail::raw('This is a test email from the NIMR Intranet System.', function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('Email Configuration Test');
            });

            return response()->json(['success' => true, 'message' => 'Test email sent successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to send test email: ' . $e->getMessage()]);
        }
    }

    public function clearCache(Request $request)
    {
        // Check if user is super admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can clear cache.');
        }

        $cacheType = $request->input('type');

        try {
            switch ($cacheType) {
                case 'application':
                    Artisan::call('cache:clear');
                    break;
                case 'route':
                    Artisan::call('route:clear');
                    break;
                case 'view':
                    Artisan::call('view:clear');
                    break;
                case 'all':
                    Artisan::call('cache:clear');
                    Artisan::call('route:clear');
                    Artisan::call('view:clear');
                    Artisan::call('config:clear');
                    break;
                default:
                    return response()->json(['success' => false, 'message' => 'Invalid cache type']);
            }

            return response()->json(['success' => true, 'message' => ucfirst($cacheType) . ' cache cleared successfully!']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Failed to clear cache: ' . $e->getMessage()]);
        }
    }

    public function resetToDefaults(Request $request)
    {
        // Check if user is super admin
        /** @var \App\Models\User $user */
        $user = Auth::user();
        if ($user->role !== 'super_admin') {
            abort(403, 'Only Super Administrators can reset settings.');
        }

        try {
            // Copy .env.example to .env (backup current first)
            $currentEnv = base_path('.env');
            $exampleEnv = base_path('.env.example');

            if (File::exists($currentEnv)) {
                File::move($currentEnv, base_path('.env.backup.' . date('Y-m-d-H-i-s')));
            }

            File::copy($exampleEnv, $currentEnv);

            // Generate new application key
            Artisan::call('key:generate');

            // Clear all caches
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return redirect()->back()->with('success', 'Settings reset to defaults successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to reset settings: ' . $e->getMessage());
        }
    }

    private function updateEnvFile($settings)
    {
        $envFile = base_path('.env');

        if (!File::exists($envFile)) {
            throw new \Exception('Environment file not found');
        }

        $envContent = File::get($envFile);

        // Map of setting keys to env variable names
        $envMapping = [
            'app_name' => 'APP_NAME',
            'app_url' => 'APP_URL',
            'app_env' => 'APP_ENV',
            'app_debug' => 'APP_DEBUG',
            'db_connection' => 'DB_CONNECTION',
            'db_database' => 'DB_DATABASE',
            'db_host' => 'DB_HOST',
            'mail_mailer' => 'MAIL_MAILER',
            'mail_host' => 'MAIL_HOST',
            'mail_port' => 'MAIL_PORT',
            'mail_username' => 'MAIL_USERNAME',
            'mail_from_address' => 'MAIL_FROM_ADDRESS',
            'mail_from_name' => 'MAIL_FROM_NAME',
            'session_lifetime' => 'SESSION_LIFETIME',
            'session_encrypt' => 'SESSION_ENCRYPT',
            'bcrypt_rounds' => 'BCRYPT_ROUNDS',
            'cache_driver' => 'CACHE_STORE',
            'cache_prefix' => 'CACHE_PREFIX',
        ];

        foreach ($envMapping as $settingKey => $envKey) {
            $value = $settings[$settingKey] ?? '';

            // Handle boolean values
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            // Handle quotes for string values
            if (!is_numeric($value) && !in_array($value, ['true', 'false'])) {
                $value = '"' . addslashes($value) . '"';
            }

            // Update the environment variable
            $pattern = '/^' . preg_quote($envKey) . '=.*/m';
            $replacement = $envKey . '=' . $value;

            if (preg_match($pattern, $envContent)) {
                $envContent = preg_replace($pattern, $replacement, $envContent);
            } else {
                $envContent .= "\n" . $replacement;
            }
        }

        File::put($envFile, $envContent);
    }
}
