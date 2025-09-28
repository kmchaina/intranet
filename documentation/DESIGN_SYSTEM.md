# NIMR Intranet Design System

## Overview
This design system provides a cohesive, professional, and accessible approach to UI design for the NIMR Intranet application. It eliminates color chaos and ensures consistency across all components.

## Color Philosophy

### Professional Medical/Research Identity
- **Primary Blue**: Trust, reliability, professionalism
- **Secondary Green**: Health, growth, positive outcomes  
- **Accent Orange**: Action, energy, important calls-to-action
- **Neutrals**: Clean, readable, accessible backgrounds and text

### 60-30-10 Rule Implementation
- **60%**: Neutral backgrounds and surfaces (whites, light grays)
- **30%**: Primary blue for navigation, headers, and main UI elements
- **10%**: Accent colors for CTAs, success states, and highlights

## Color Palette

### Primary Colors (Medical Blue)
```css
--nimr-primary-50: #eff6ff    /* Lightest backgrounds */
--nimr-primary-500: #3b82f6   /* Main primary color */
--nimr-primary-600: #2563eb   /* Primary buttons */
--nimr-primary-700: #1d4ed8   /* Active states */
--nimr-primary-800: #1e40af   /* Text on light backgrounds */
```

### Secondary Colors (Research Green)
```css
--nimr-secondary-500: #22c55e  /* Success indicators */
--nimr-secondary-600: #16a34a  /* Success buttons */
--nimr-secondary-700: #15803d  /* Main secondary color */
```

### Accent Colors (Action Orange)
```css
--nimr-accent-500: #f97316     /* Accent highlights */
--nimr-accent-600: #ea580c     /* Main accent color */
--nimr-accent-700: #c2410c     /* Active accent states */
```

### Semantic Colors
```css
/* Success (Green) */
--color-success: #16a34a       /* Form success, positive feedback */

/* Warning (Amber) */
--color-warning: #d97706       /* Caution, pending states */

/* Error (Red) */
--color-error: #dc2626         /* Errors, critical alerts */

/* Info (Blue) */
--color-info: #2563eb          /* Information, neutral actions */
```

### Neutral Scale
```css
--gray-50: #f8fafc            /* Page backgrounds */
--gray-100: #f1f5f9           /* Card backgrounds */
--gray-200: #e2e8f0           /* Borders */
--gray-300: #cbd5e1           /* Disabled states */
--gray-600: #475569           /* Secondary text */
--gray-700: #334155           /* Primary text */
--gray-800: #1e293b           /* Headings */
--gray-900: #0f172a           /* High contrast text */
```

## Component Classes

### Cards
```html
<!-- Basic professional card -->
<div class="nimr-card">
  <div class="nimr-card-header">
    <h3 class="nimr-heading-3">Card Title</h3>
  </div>
  <div class="nimr-card-body">
    <p class="nimr-text-body">Card content...</p>
  </div>
</div>

<!-- Stats card with hover effect -->
<div class="nimr-stats-card nimr-hover-lift">
  <!-- Stats content -->
</div>
```

### Buttons
```html
<!-- Primary action button -->
<button class="nimr-btn-primary">Create New</button>

<!-- Secondary action button -->
<button class="nimr-btn-secondary">Save Draft</button>

<!-- Outline button -->
<button class="nimr-btn-outline">Cancel</button>

<!-- Ghost button -->
<button class="nimr-btn-ghost">Learn More</button>
```

### Status Badges
```html
<!-- Success state -->
<span class="nimr-badge-success">Completed</span>

<!-- Warning state -->
<span class="nimr-badge-warning">Pending</span>

<!-- Error state -->
<span class="nimr-badge-error">Failed</span>

<!-- Info state -->
<span class="nimr-badge-info">In Progress</span>

<!-- Primary state -->
<span class="nimr-badge-primary">New</span>
```

### Icon Containers
```html
<!-- Primary icon -->
<div class="nimr-icon-primary">
  <svg class="w-6 h-6"><!-- icon --></svg>
</div>

<!-- Success icon -->
<div class="nimr-icon-success">
  <svg class="w-6 h-6"><!-- icon --></svg>
</div>

<!-- Warning icon -->
<div class="nimr-icon-warning">
  <svg class="w-6 h-6"><!-- icon --></svg>
</div>
```

### Typography
```html
<!-- Headings -->
<h1 class="nimr-heading-1">Main Page Title</h1>
<h2 class="nimr-heading-2">Section Title</h2>
<h3 class="nimr-heading-3">Subsection Title</h3>

<!-- Body text -->
<p class="nimr-text-body">Regular paragraph text</p>
<p class="nimr-text-muted">Secondary or helper text</p>
```

### Form Elements
```html
<!-- Professional input -->
<input type="text" class="nimr-input" placeholder="Enter text...">
```

### Navigation
```html
<!-- Sidebar navigation item -->
<a href="#" class="nimr-nav-item nimr-nav-link active">
  <svg class="w-5 h-5"><!-- icon --></svg>
  <span>Dashboard</span>
</a>
```

## Usage Guidelines

### Do's ✅
- Use primary blue for main navigation and primary actions
- Use secondary green for success states and positive feedback
- Use accent orange sparingly for important CTAs and urgent actions
- Maintain consistent spacing using Tailwind's spacing scale
- Use semantic colors to convey meaning (success, warning, error, info)
- Test color combinations for accessibility (WCAG AA compliance)

### Don'ts ❌
- Don't use multiple bright colors in the same interface
- Don't use decorative gradients without semantic meaning
- Don't rely on color alone to convey information
- Don't use colors inconsistently across similar components
- Don't ignore accessibility guidelines for contrast ratios

## Accessibility Standards

### Color Contrast Requirements
- **Text on backgrounds**: Minimum 4.5:1 ratio (WCAG AA)
- **Large text (18px+)**: Minimum 3:1 ratio
- **Interactive elements**: Clear focus indicators with 3:1 contrast

### Color Blindness Considerations
- All color-coded information includes additional indicators (icons, text labels)
- Success/error states use both color and iconography
- Form validation provides text descriptions beyond color changes

## Migration Guide

### From Old System to New System
```css
/* Old colorful approach ❌ */
.icon-gradient-blue { background: linear-gradient(...); }
.icon-gradient-green { background: linear-gradient(...); }
.icon-gradient-purple { background: linear-gradient(...); }

/* New professional approach ✅ */
.nimr-icon-primary { /* Primary blue */ }
.nimr-icon-success { /* Success green */ }
.nimr-icon-warning { /* Warning amber */ }
```

### Tailwind Class Replacements
```html
<!-- Old approach ❌ -->
<div class="bg-gradient-to-r from-blue-500 via-purple-500 to-blue-600">

<!-- New approach ✅ -->
<div class="bg-nimr-primary-600 hover:bg-nimr-primary-700">
```

## Implementation Checklist

### Phase 1: Foundation
- [x] Update `tailwind.config.js` with NIMR color palette
- [x] Create professional CSS component classes
- [x] Replace old gradient classes with semantic colors

### Phase 2: Component Updates
- [ ] Update dashboard cards to use `nimr-card` classes
- [ ] Replace buttons with `nimr-btn-*` classes  
- [ ] Update status indicators with `nimr-badge-*` classes
- [ ] Apply professional typography classes

### Phase 3: Testing & Refinement
- [ ] Test accessibility compliance
- [ ] Verify responsive behavior
- [ ] User testing for professional appearance
- [ ] Performance optimization

## Tools & Resources

### Design Tools
- **Figma**: Use the NIMR color tokens for design mockups
- **Adobe Color**: Generate harmonious color variations
- **Coolors.co**: Test color palette combinations

### Accessibility Tools
- **WebAIM Contrast Checker**: Verify color contrast ratios
- **Stark**: Figma plugin for accessibility testing
- **axe DevTools**: Browser extension for accessibility auditing

### Development Tools
- **Tailwind CSS IntelliSense**: VS Code extension for class suggestions
- **Headwind**: VS Code extension for class sorting

## Examples in Context

### Professional Dashboard Card
```html
<div class="nimr-card nimr-hover-lift">
  <div class="nimr-card-header">
    <div class="flex items-center justify-between">
      <h3 class="nimr-heading-3">Recent Announcements</h3>
      <span class="nimr-badge-primary">5 New</span>
    </div>
  </div>
  <div class="nimr-card-body">
    <div class="space-y-4">
      <!-- Announcement items -->
    </div>
  </div>
  <div class="nimr-card-footer">
    <button class="nimr-btn-outline w-full">View All</button>
  </div>
</div>
```

### Professional Stats Grid
```html
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
  <div class="nimr-stats-card">
    <div class="flex items-center">
      <div class="nimr-icon-primary">
        <svg class="w-6 h-6"><!-- icon --></svg>
      </div>
      <div class="ml-4">
        <p class="nimr-text-muted">Total Users</p>
        <p class="nimr-heading-2">1,245</p>
      </div>
    </div>
  </div>
  <!-- More stats cards... -->
</div>
```

## Conclusion

This design system ensures your NIMR Intranet looks professional, remains accessible, and scales consistently. The color palette is specifically chosen for medical/research contexts while maintaining excellent usability and visual hierarchy.

Remember: **Less is more**. The power of this system comes from restraint and consistency, not from using every color available.