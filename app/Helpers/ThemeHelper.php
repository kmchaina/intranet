<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class ThemeHelper
{
    /**
     * Get theme colors for a specific role
     */
    public static function getColors(string $role): array
    {
        $colors = config('theme.roles', []);
        return $colors[$role] ?? $colors['staff'];
    }

    /**
     * Get current user's theme colors
     */
    public static function getCurrentColors(): array
    {
        $user = Auth::user();
        if (!$user) {
            return self::getColors('staff');
        }

        // Check if viewing as staff
        $isStaffView = request()->get('view') === 'staff' || session('staffView', false);
        $effectiveRole = $isStaffView ? 'staff' : $user->role;

        return self::getColors($effectiveRole);
    }

    /**
     * Generate Tailwind classes for cards based on role colors
     */
    public static function getCardClasses(string $role): array
    {
        $colors = self::getColors($role);
        $primary = $colors['primary'];
        $accent = $colors['accent'];

        return [
            'header_bg' => "bg-gradient-to-r from-{$primary}-50 to-{$accent}-50 border-b border-{$primary}-100",
            'icon_bg' => "bg-gradient-to-br from-{$primary}-600 to-{$accent}-600",
            'link_text' => "text-{$primary}-600 hover:text-{$primary}-700",
            'hover_border' => "hover:border-{$primary}-300 hover:bg-{$primary}-50",
            'dot_bg' => "bg-{$primary}-600",
            'badge_bg' => "bg-gradient-to-br from-{$primary}-100 to-{$accent}-100",
            'badge_text' => "text-{$primary}-700",
        ];
    }

    /**
     * Get inline styles for dynamic colors (fallback for non-compiled Tailwind)
     */
    public static function getInlineStyles(string $role): array
    {
        $colors = self::getColors($role);

        return [
            'header_bg' => "background: linear-gradient(to right, {$colors['primary_from']}, {$colors['accent_600']}20);",
            'icon_bg' => "background: linear-gradient(135deg, {$colors['primary_600']}, {$colors['accent_600']});",
            'link_text' => "color: {$colors['primary_600']};",
            'dot_bg' => "background-color: {$colors['primary_600']};",
        ];
    }
}
