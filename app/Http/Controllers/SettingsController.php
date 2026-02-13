<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display settings page.
     */
    public function index()
    {
        $settings = $this->getAllSettings();
        $categories = config('settings.item_categories', []);
        $locations = config('settings.locations', []);
        
        return view('admin.settings.index', compact('settings', 'categories', 'locations'));
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $section = $request->get('section', 'general');
        
        switch ($section) {
            case 'general':
                return $this->updateGeneralSettings($request);
            case 'matching':
                return $this->updateMatchingSettings($request);
            case 'notifications':
                return $this->updateNotificationSettings($request);
            case 'security':
                return $this->updateSecuritySettings($request);
            case 'appearance':
                return $this->updateAppearanceSettings($request);
            case 'categories':
                return $this->updateCategorySettings($request);
            default:
                return back()->with('error', 'Invalid settings section');
        }
    }

    /**
     * Update general settings.
     */
    private function updateGeneralSettings(Request $request)
    {
        $validated = $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
            'app_description' => ['nullable', 'string', 'max:500'],
            'contact_email' => ['required', 'email', 'max:255'],
            'contact_phone' => ['nullable', 'string', 'max:20'],
            'site_url' => ['nullable', 'url', 'max:255'],
            'timezone' => ['required', 'string', 'timezone'],
            'date_format' => ['required', 'string', 'in:Y-m-d,m/d/Y,d/m/Y'],
            'items_per_page' => ['required', 'integer', 'min:5', 'max:100'],
            'maintenance_mode' => ['boolean'],
            'registration_enabled' => ['boolean'],
            'email_verification' => ['boolean'],
        ]);
        
        foreach ($validated as $key => $value) {
            $this->saveSetting($key, $value);
        }
        
        return back()->with('success', 'General settings updated successfully');
    }

    /**
     * Update matching settings.
     */
    private function updateMatchingSettings(Request $request)
    {
        $validated = $request->validate([
            'matching_enabled' => ['boolean'],
            'match_threshold' => ['required', 'integer', 'min:50', 'max:100'],
            'match_expiry_days' => ['required', 'integer', 'min:1', 'max:365'],
            'auto_match_interval' => ['required', 'integer', 'min:1', 'max:24'],
            'location_radius' => ['required', 'integer', 'min:1', 'max:100'], // in km
            'enable_ai_matching' => ['boolean'],
            'enable_manual_review' => ['boolean'],
            'max_matches_per_item' => ['required', 'integer', 'min:1', 'max:50'],
        ]);
        
        foreach ($validated as $key => $value) {
            $this->saveSetting($key, $value);
        }
        
        return back()->with('success', 'Matching settings updated successfully');
    }

    /**
     * Update notification settings.
     */
    private function updateNotificationSettings(Request $request)
    {
        $validated = $request->validate([
            'notify_on_match' => ['boolean'],
            'notify_on_message' => ['boolean'],
            'notify_admin_on_new_user' => ['boolean'],
            'notify_admin_on_new_item' => ['boolean'],
            'email_notifications' => ['boolean'],
            'push_notifications' => ['boolean'],
            'sms_notifications' => ['boolean'],
            'daily_summary' => ['boolean'],
            'weekly_report' => ['boolean'],
            'match_email_template' => ['nullable', 'string', 'max:5000'],
            'welcome_email_template' => ['nullable', 'string', 'max:5000'],
        ]);
        
        foreach ($validated as $key => $value) {
            $this->saveSetting($key, $value);
        }
        
        return back()->with('success', 'Notification settings updated successfully');
    }

    /**
     * Update security settings.
     */
    private function updateSecuritySettings(Request $request)
    {
        $validated = $request->validate([
            'password_min_length' => ['required', 'integer', 'min:6', 'max:32'],
            'password_require_numbers' => ['boolean'],
            'password_require_symbols' => ['boolean'],
            'password_require_mixed_case' => ['boolean'],
            'login_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'lockout_duration' => ['required', 'integer', 'min:1', 'max:60'], // in minutes
            'session_timeout' => ['required', 'integer', 'min:15', 'max:480'], // in minutes
            'enable_2fa' => ['boolean'],
            'require_email_verification' => ['boolean'],
            'enable_recaptcha' => ['boolean'],
            'recaptcha_site_key' => ['nullable', 'string', 'max:255'],
            'recaptcha_secret_key' => ['nullable', 'string', 'max:255'],
        ]);
        
        foreach ($validated as $key => $value) {
            $this->saveSetting($key, $value);
        }
        
        return back()->with('success', 'Security settings updated successfully');
    }

    /**
     * Update appearance settings.
     */
    private function updateAppearanceSettings(Request $request)
    {
        $validated = $request->validate([
            'primary_color' => ['required', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'secondary_color' => ['required', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'accent_color' => ['nullable', 'string', 'regex:/^#[0-9A-F]{6}$/i'],
            'font_family' => ['required', 'string', 'max:100'],
            'logo_url' => ['nullable', 'url', 'max:500'],
            'favicon_url' => ['nullable', 'url', 'max:500'],
            'enable_dark_mode' => ['boolean'],
            'custom_css' => ['nullable', 'string', 'max:10000'],
            'custom_js' => ['nullable', 'string', 'max:10000'],
        ]);
        
        foreach ($validated as $key => $value) {
            $this->saveSetting($key, $value);
        }
        
        return back()->with('success', 'Appearance settings updated successfully');
    }

    /**
     * Update category settings.
     */
    private function updateCategorySettings(Request $request)
    {
        $request->validate([
            'categories' => ['required', 'array'],
            'categories.*' => ['required', 'string', 'max:100'],
        ]);
        
        $categories = array_unique(array_filter($request->categories));
        $this->saveSetting('item_categories', json_encode($categories));
        
        return back()->with('success', 'Categories updated successfully');
    }

    /**
     * Get all settings with defaults.
     */
    private function getAllSettings()
    {
        $defaults = [
            // General
            'app_name' => 'Foundify',
            'app_description' => 'Lost and Found System',
            'contact_email' => 'admin@foundify.com',
            'contact_phone' => null,
            'site_url' => url('/'),
            'timezone' => config('app.timezone'),
            'date_format' => 'Y-m-d',
            'items_per_page' => 20,
            'maintenance_mode' => false,
            'registration_enabled' => true,
            'email_verification' => false,
            
            // Matching
            'matching_enabled' => true,
            'match_threshold' => 80,
            'match_expiry_days' => 30,
            'auto_match_interval' => 6,
            'location_radius' => 10,
            'enable_ai_matching' => true,
            'enable_manual_review' => false,
            'max_matches_per_item' => 10,
            
            // Notifications
            'notify_on_match' => true,
            'notify_on_message' => true,
            'notify_admin_on_new_user' => true,
            'notify_admin_on_new_item' => false,
            'email_notifications' => true,
            'push_notifications' => true,
            'sms_notifications' => false,
            'daily_summary' => true,
            'weekly_report' => true,
            
            // Security
            'password_min_length' => 8,
            'password_require_numbers' => true,
            'password_require_symbols' => false,
            'password_require_mixed_case' => true,
            'login_attempts' => 5,
            'lockout_duration' => 15,
            'session_timeout' => 120,
            'enable_2fa' => false,
            'require_email_verification' => false,
            'enable_recaptcha' => false,
            
            // Appearance
            'primary_color' => '#3b82f6',
            'secondary_color' => '#64748b',
            'accent_color' => '#10b981',
            'font_family' => 'Inter, sans-serif',
            'logo_url' => null,
            'favicon_url' => null,
            'enable_dark_mode' => true,
            
            // Categories (stored as JSON)
            'item_categories' => json_encode([
                'Electronics',
                'Documents',
                'Keys',
                'Wallet/Purse',
                'Jewelry',
                'Clothing',
                'Books',
                'Bag/Backpack',
                'Sports Equipment',
                'Other'
            ]),
        ];
        
        $settings = [];
        foreach ($defaults as $key => $default) {
            $settings[$key] = $this->getSetting($key, $default);
        }
        
        return $settings;
    }

    /**
     * Get a setting value.
     */
    private function getSetting($key, $default = null)
    {
        $value = Cache::rememberForever("setting.{$key}", function () use ($key, $default) {
            // In a real application, you would store settings in a database table
            // For now, we'll use config or return default
            return config("settings.{$key}", $default);
        });
        
        // Handle JSON values
        if (is_string($value) && in_array($key, ['item_categories'])) {
            $decoded = json_decode($value, true);
            return $decoded !== null ? $decoded : $default;
        }
        
        // Handle boolean values
        if (is_string($value)) {
            $lower = strtolower($value);
            if ($lower === 'true') return true;
            if ($lower === 'false') return false;
            if ($lower === 'null') return null;
        }
        
        return $value;
    }

    /**
     * Save a setting.
     */
    private function saveSetting($key, $value)
    {
        // In a real application, save to database
        // For now, we'll update config or cache
        
        // Handle array/JSON values
        if (is_array($value) && in_array($key, ['item_categories'])) {
            $value = json_encode($value);
        }
        
        // Handle boolean values
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        }
        
        // Update cache
        Cache::forever("setting.{$key}", $value);
        
        // In production, you would save to database:
        // Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        
        return true;
    }

    /**
     * Clear cache.
     */
    public function clearCache()
    {
        Cache::flush();
        
        // Clear config cache if applicable
        if (function_exists('artisan')) {
            \Artisan::call('config:clear');
            \Artisan::call('view:clear');
        }
        
        return back()->with('success', 'Cache cleared successfully');
    }

    /**
     * Backup settings.
     */
    public function backup()
    {
        $settings = $this->getAllSettings();
        
        // Generate JSON backup
        $backup = json_encode($settings, JSON_PRETTY_PRINT);
        
        return response($backup, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => 'attachment; filename="settings_backup_' . date('Y-m-d') . '.json"',
        ]);
    }

    /**
     * Restore settings from backup.
     */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => ['required', 'file', 'mimes:json', 'max:2048'],
        ]);
        
        $content = file_get_contents($request->file('backup_file')->getRealPath());
        $settings = json_decode($content, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            return back()->with('error', 'Invalid backup file format');
        }
        
        foreach ($settings as $key => $value) {
            $this->saveSetting($key, $value);
        }
        
        return back()->with('success', 'Settings restored successfully');
    }

    /**
     * Reset settings to defaults.
     */
    public function reset()
    {
        // Clear all settings cache
        $keys = array_keys($this->getAllSettings());
        foreach ($keys as $key) {
            Cache::forget("setting.{$key}");
        }
        
        return back()->with('success', 'Settings reset to defaults');
    }
}