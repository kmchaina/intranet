# Design System Implementation Roadmap

## Overview

This document outlines the systematic approach to standardize all views in the NIMR intranet using our established design system.

## Completed Components

✅ **Design System Documentation** (`DESIGN_SYSTEM.md`)
✅ **Standardized Components**:

-   `page-layout.blade.php` - Main page container with consistent header
-   `standard-card.blade.php` - Uniform card design with consistent styling
-   `standard-form.blade.php` - Standardized form layout
-   `form-field.blade.php` - Consistent form inputs with validation
    ✅ **Dashboard** - Fully standardized using new components

## Implementation Priority Order

### Phase 1: Critical User Flows (Week 1)

1. **Announcements** (`resources/views/announcements/`)

    - `index.blade.php` - Apply page-layout, standard-card
    - `create.blade.php` - Apply standard-form, form-field
    - `edit.blade.php` - Apply standard-form, form-field
    - `show.blade.php` - Apply page-layout, standard-card

2. **Authentication** (`resources/views/auth/`)
    - `login.blade.php` - Apply standard-form, form-field
    - `register.blade.php` - Apply standard-form, form-field
    - `forgot-password.blade.php` - Apply standard-form, form-field

### Phase 2: Content Management (Week 2)

3. **Documents** (`resources/views/documents/`)

    - Apply page-layout for listings
    - Apply standard-form for uploads
    - Apply standard-card for document cards

4. **Events** (`resources/views/events/`)

    - Apply page-layout for calendar views
    - Apply standard-form for event creation
    - Apply standard-card for event listings

5. **Polls** (`resources/views/polls/`)
    - Apply page-layout for poll listings
    - Apply standard-form for poll creation
    - Apply standard-card for poll results

### Phase 3: Communication Features (Week 3)

6. **Chat/Messages** (`resources/views/chat/`)

    - Apply page-layout for chat interface
    - Apply standard-card for message threads
    - Apply standard-form for message composition

7. **Feedback** (`resources/views/feedback/`)
    - Apply page-layout for feedback listings
    - Apply standard-form for feedback submission
    - Apply standard-card for feedback cards

### Phase 4: Administrative Features (Week 4)

8. **Users Management** (`resources/views/users/`)

    - Apply page-layout for user listings
    - Apply standard-form for user creation/editing
    - Apply standard-card for user profiles

9. **Centers & Stations** (`resources/views/centres/`, `resources/views/stations/`)

    - Apply page-layout for listings
    - Apply standard-form for creation/editing
    - Apply standard-card for information cards

10. **Directories** (`resources/views/directories/`)
    - Apply page-layout for directory views
    - Apply standard-card for contact cards

### Phase 5: Specialized Features (Week 5)

11. **Publications** (`resources/views/publications/`)

    -   Apply page-layout for publication listings
    -   Apply standard-form for publication submission
    -   Apply standard-card for publication display

12. **Organization Chart** (`resources/views/org-chart/`)
    -   Apply page-layout for chart display
    -   Apply standard-card for position cards

## Quick Implementation Checklist

For each view file, follow this checklist:

### Layout Structure

-   [ ] Replace page container with `<x-page-layout>`
-   [ ] Ensure content width uses `max-w-6xl` (already in page-layout)
-   [ ] Apply consistent section spacing with `gap-6`

### Typography

-   [ ] H1 headings: `text-2xl font-bold`
-   [ ] H2 headings: `text-xl font-semibold`
-   [ ] H3 headings: `text-lg font-medium`
-   [ ] Body text: `text-base` (default)
-   [ ] Small text: `text-sm`

### Cards and Containers

-   [ ] Replace custom cards with `<x-standard-card>`
-   [ ] Use consistent padding: `p-6` for main content
-   [ ] Apply consistent border radius: `rounded-lg`

### Forms

-   [ ] Replace form containers with `<x-standard-form>`
-   [ ] Replace form inputs with `<x-form-field>`
-   [ ] Ensure max-width of `max-w-2xl` for forms

### Buttons and Actions

-   [ ] Primary buttons: `bg-blue-600 hover:bg-blue-700`
-   [ ] Secondary buttons: `bg-gray-200 hover:bg-gray-300`
-   [ ] Danger buttons: `bg-red-600 hover:bg-red-700`
-   [ ] Consistent padding: `px-4 py-2` or `px-6 py-3`

### Colors and Spacing

-   [ ] Use design system color palette
-   [ ] Apply consistent spacing: `space-y-4`, `gap-6`
-   [ ] Ensure proper dark mode support

## Code Examples

### Before (Inconsistent)

```blade
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded p-4">
        <h1 class="text-3xl font-bold mb-4">Page Title</h1>
        <div class="text-lg">Content here</div>
    </div>
</div>
```

### After (Standardized)

```blade
<x-page-layout title="Page Title">
    <x-standard-card>
        <h2 class="text-xl font-semibold mb-4">Section Title</h2>
        <p class="text-base">Content here</p>
    </x-standard-card>
</x-page-layout>
```

## Quality Assurance

After implementing each view:

1. Check typography scales match design system
2. Verify spacing consistency (6-unit grid)
3. Test responsive behavior
4. Validate dark mode compatibility
5. Ensure accessibility standards

## Benefits

-   **Consistency**: Uniform look and feel across all features
-   **Maintainability**: Centralized styling through components
-   **Performance**: Reduced CSS duplication
-   **User Experience**: Professional, cohesive interface
-   **Development Speed**: Faster implementation with reusable components

## Next Steps

1. Start with Phase 1 (Announcements and Authentication)
2. Test thoroughly after each view is updated
3. Gather user feedback on the standardized interface
4. Iterate and refine components based on real usage
5. Document any additional patterns that emerge

---

_This roadmap ensures systematic, quality implementation of the NIMR intranet design system while maintaining functionality and improving user experience._
