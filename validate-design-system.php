#!/usr/bin/env php
<?php

/**
 * Design System Validation Script
 * Scans view files for inconsistent design patterns
 */

$baseDir = __DIR__ . '/resources/views';
$issueCount = 0;

// Patterns to check for inconsistencies
$patterns = [
    'inconsistent_text_sizes' => [
        'pattern' => '/text-(xs|sm|lg|xl|2xl|3xl|4xl)/',
        'allowed' => ['text-sm', 'text-base', 'text-lg', 'text-xl', 'text-2xl'],
        'message' => 'Inconsistent text sizes found. Use design system typography scale.'
    ],
    'inconsistent_max_widths' => [
        'pattern' => '/max-w-(xs|sm|md|lg|xl|2xl|3xl|4xl|5xl|7xl|full)/',
        'allowed' => ['max-w-2xl', 'max-w-6xl'],
        'message' => 'Inconsistent max-widths found. Use max-w-2xl for forms, max-w-6xl for containers.'
    ],
    'custom_cards' => [
        'pattern' => '/class="[^"]*bg-white[^"]*shadow[^"]*"/',
        'allowed' => [],
        'message' => 'Custom card styling found. Consider using <x-standard-card> component.'
    ],
    'inconsistent_padding' => [
        'pattern' => '/p-([0-9]+)/',
        'allowed' => ['p-4', 'p-6', 'p-8'],
        'message' => 'Inconsistent padding found. Use p-6 for main content, p-4 for compact areas.'
    ],
    'missing_page_layout' => [
        'pattern' => '/@section\(\'content\'\)/',
        'check_for' => '<x-page-layout',
        'message' => 'Page may be missing standardized page-layout component.'
    ]
];

function scanDirectory($dir, $patterns)
{
    global $issueCount;

    $files = glob($dir . '/*.blade.php');
    $subdirs = glob($dir . '/*', GLOB_ONLYDIR);

    foreach ($files as $file) {
        scanFile($file, $patterns);
    }

    foreach ($subdirs as $subdir) {
        scanDirectory($subdir, $patterns);
    }
}

function scanFile($file, $patterns)
{
    global $issueCount;

    $content = file_get_contents($file);
    $relativePath = str_replace(__DIR__ . '/resources/views/', '', $file);

    echo "\n📄 Scanning: $relativePath\n";

    foreach ($patterns as $patternName => $config) {
        if ($patternName === 'missing_page_layout') {
            // Special check for page layout
            if (preg_match($config['pattern'], $content) && !str_contains($content, $config['check_for'])) {
                echo "  ⚠️  {$config['message']}\n";
                $issueCount++;
            }
        } else {
            preg_match_all($config['pattern'], $content, $matches);

            if (!empty($matches[0])) {
                $violations = array_unique($matches[0]);
                $hasViolations = false;

                foreach ($violations as $match) {
                    if (!empty($config['allowed'])) {
                        $isAllowed = false;
                        foreach ($config['allowed'] as $allowed) {
                            if (str_contains($match, $allowed)) {
                                $isAllowed = true;
                                break;
                            }
                        }
                        if (!$isAllowed) {
                            $hasViolations = true;
                            break;
                        }
                    } else {
                        $hasViolations = true;
                        break;
                    }
                }

                if ($hasViolations) {
                    echo "  ⚠️  {$config['message']}\n";
                    echo "     Found: " . implode(', ', array_slice($violations, 0, 3)) . "\n";
                    $issueCount++;
                }
            }
        }
    }
}

// Check if components exist
function checkComponents()
{
    $components = [
        'resources/views/components/page-layout.blade.php',
        'resources/views/components/standard-card.blade.php',
        'resources/views/components/standard-form.blade.php',
        'resources/views/components/form-field.blade.php'
    ];

    echo "🔍 Checking standardized components...\n";

    foreach ($components as $component) {
        if (file_exists($component)) {
            echo "  ✅ " . basename($component) . " exists\n";
        } else {
            echo "  ❌ " . basename($component) . " missing\n";
        }
    }
}

// Main execution
echo "🎨 NIMR Intranet Design System Validation\n";
echo "========================================\n";

checkComponents();

echo "\n🔍 Scanning view files for design inconsistencies...\n";

if (is_dir($baseDir)) {
    scanDirectory($baseDir, $patterns);
} else {
    echo "❌ Views directory not found: $baseDir\n";
    exit(1);
}

echo "\n📊 Summary\n";
echo "==========\n";

if ($issueCount === 0) {
    echo "🎉 No design inconsistencies found! All views follow the design system.\n";
} else {
    echo "⚠️  Found $issueCount potential design inconsistencies.\n";
    echo "📋 Review the issues above and update views to use standardized components.\n";
    echo "📖 See DESIGN_SYSTEM.md and IMPLEMENTATION_ROADMAP.md for guidance.\n";
}

echo "\n🚀 Design System Components Available:\n";
echo "   • <x-page-layout> - Standardized page container\n";
echo "   • <x-standard-card> - Uniform card design\n";
echo "   • <x-standard-form> - Consistent form layout\n";
echo "   • <x-form-field> - Standardized form inputs\n";

echo "\n✨ Run this script regularly to maintain design consistency!\n";
