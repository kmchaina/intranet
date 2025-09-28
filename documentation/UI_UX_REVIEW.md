# Intranet UI/UX Review — September 23, 2025

This document summarizes the current UI implementation, key findings, and actionable recommendations for improving usability, clarity, and aesthetics while keeping functionality intact and performance strong.

## Project Map (UI-related)
- Layouts: `resources/views/layouts/`
  - `app.blade.php` — Generic layout, includes `layouts/navigation` (top nav)
  - `dashboard.blade.php` — Authenticated app shell with left sidebar, top header
  - `guest.blade.php`, `navigation.blade.php`
- Views (feature pages): `resources/views/**` (e.g., `dashboard/`, `announcements/`, `news/`, `documents/`, etc.)
- Components: `resources/views/components/`
  - Buttons, form fields, modal, nav link, dark mode toggle, standard card, page layout
- Styling: `resources/css/app.css` (imports Tailwind and `custom.css`), `resources/css/custom.css`
- Tailwind: `tailwind.config.js` (custom colors, shadows, animations; forms plugin)
- JS: `resources/js/app.js` (Alpine), `resources/js/search.js` (global search), `resources/js/bootstrap.js`
- Build: `vite.config.js` (laravel-vite-plugin)

## Current Implementation Summary
- Navigation:
  - Authenticated pages use `layouts/dashboard.blade.php` with a left sidebar (sectioned), top header with compact search, quick actions, notifications, and profile dropdown.
  - `app.blade.php` uses `layouts/navigation.blade.php` (top-only nav) for non-dashboard views.
  - Routes map cleanly in `routes/web.php` and most feature views extend `layouts.dashboard`.
- Color & Theme:
  - Tailwind extended palette under `nimr.*` (primary blue, secondary green, accent orange; neutrals). Semantic `feedback.*` colors exist.
  - `resources/css/custom.css` defines CSS variables but components mainly use Tailwind classes and `@apply` with extended colors.
  - Dark mode toggle component exists, but Tailwind dark mode is not configured to use `class`.
- Layout & Components:
  - Card/button/badge utilities (`nimr-*`) provide a consistent visual language.
  - Dashboard layout contains a clean, sectioned sidebar with badges and icons; pages often use card grids and list/card hybrids.
- Interactivity:
  - AlpineJS is used for sidebar, dropdowns; `search.js` provides debounced suggestions with keyboard navigation; modals are custom DOM injections.

## Key Findings (UI/UX + Code Hygiene)
- Inconsistencies
  - Two base layouts (`app` vs `dashboard`) and two nav paradigms (top-only vs sidebar). Most authenticated pages use `dashboard`, but some components/layouts (e.g., `page-layout.blade.php`) are not aligned or are broken.
  - Typography sources differ (Bunny Figtree in `app.blade.php` vs Google Inter in `dashboard.blade.php`), while Tailwind’s `fontFamily` is set to Inter.
  - Alpine is included via Vite (`app.js`) and also via CDN in `layouts/dashboard.blade.php` — duplicate inclusion risk.
- Dark Mode
  - `x-dark-mode-toggle` toggles `document.documentElement.classList.add('dark')`, but Tailwind is not set to `darkMode: 'class'`. Result: dark styles in markup (e.g., `dark:text-gray-400`) don’t activate.
- Accessibility & Semantics
  - Global search suggestions work but lack ARIA roles for combobox/listbox options.
  - No visible “Skip to content” link; focus states primarily rely on Tailwind defaults.
- Responsiveness
  - Overall responsive, but some inline CSS in `layouts/dashboard.blade.php` and fixed widths (e.g., search 16rem) could be made more fluid on small screens.
- Markup/Quality Issues (should be addressed during refactor)
  - `resources/views/layouts/navigation.blade.php`: malformed attributes and nested tags (e.g., broken SVG attribute quotes, incomplete `class` attributes, stray `<div>` in class attributes). This can cause rendering issues.
  - `resources/views/components/page-layout.blade.php`: header title binding is broken (`$title` not echoed correctly).
  - `resources/views/dashboard/index.blade.php`: duplicate `@extends('layouts.dashboard')` at file start.
  - Multiple uses of `line-clamp-*` classes without the Tailwind line-clamp plugin installed.

## UI/UX Analysis
- Color Palette: Strong core palette (blue/green/orange) aligned with institutional branding. Needs contrast checks for text on tinted backgrounds; occasional low-contrast gray-on-tint combinations.
- Spacing: Mostly consistent Tailwind spacing; recommend a compact “rhythm” for card headers/bodies and consistent gap sizes (e.g., 4/6/8) across lists and grids.
- Typography: Inter is set in Tailwind; unify across layouts. Heading scales are good; ensure semantic h1/h2/h3 usage per page.
- Components: `nimr-card`, `nimr-btn`, `nimr-badge` are solid foundations; some pages still mix ad-hoc borders/shadows.
- Responsiveness: Grids adapt; sidebar collapses; improve mobile search and overflow handling for suggestion dropdowns.
- Navigation: Sidebar with section headers is intuitive for an intranet. Keep sidebar primary. Top header should remain slim with search and user actions.

## Recommendations
- Color & Accessibility
  - Keep existing `nimr.*` palette; document usage: primary (actions/links), secondary (data/info), accent (highlights/CTAs).
  - Run contrast validation for text on gradients/tints; prefer `text-gray-900` over `text-gray-700` on light tints.
  - Add focus-visible outlines with brand color (already present in buttons via `focus:ring-*`).
- Layout Patterns
  - Dashboard: left sidebar + content area (keep). Use card grids for summaries and list/card hybrid for content feeds.
  - Lists: offer a grid/list toggle on `documents`, `news`, `announcements` indexes.
  - Detail pages: single-column content with a right rail for metadata or related items where useful.
- Design Guidelines
  - Alignment: consistent container widths (`max-w-7xl` or `max-w-6xl`), unified padding (`px-6`/`py-6` for cards).
  - Spacing: adopt 4/6/8 step system for gaps/margins. Avoid inline styles; rely on Tailwind utilities and `nimr-*` helpers.
  - Typography: Inter only; define heading utilities (`nimr-heading-*`) and apply across pages; ensure one H1 per page.
- Libraries/Plugins
  - Tailwind: add `@tailwindcss/typography`, `@tailwindcss/line-clamp`, and `@tailwindcss/aspect-ratio`.
  - Headless UI (with Alpine or Vue) for accessible menus, modals, combobox (search).
  - Consider DaisyUI or Flowbite only if you need faster prototyping; current custom system is sufficient.
- Theming & Dark Mode
  - Switch Tailwind to `darkMode: 'class'` and keep the dark mode toggle; audit a small subset of components to add `dark:*` styles.
  - Either remove unused CSS variables or wire them into Tailwind via a plugin/custom properties mapping to reduce confusion.
- JS Hygiene
  - Remove CDN Alpine from `layouts/dashboard.blade.php` to avoid double initialization; rely on Vite entry `resources/js/app.js`.
  - Extract inline script blocks in views into dedicated modules under `resources/js/` where practical.
- Accessibility
  - Add `role="combobox"`, `aria-expanded`, `aria-controls` to search input and `role="listbox"/"option"` to suggestions; manage active-descendant.
  - Add a visually-hidden “Skip to content” link and ensure focus styles are visible.

## Low-Fidelity Wireframes (ASCII)

Dashboard (Sidebar + Header + Content)

+-----------------------------------------------------------------------------------+
| [☰] NIMR Intranet                 | Search [________]      | Bell  |  Avatar     |
+----------------------+------------------------------------------------------------+
| - Dashboard          |  Welcome back, Name                                        |
| - Announcements (3)  |  [Cards: Birthdays | To-Do | Vault | Help]                 |
| - News               |                                                            |
| - Documents          |  Latest Announcements  [View all]                          |
| - Events             |  [Large carousel card, pagination dots]                    |
| - Polls (1)          |                                                            |
| - System Links       |  Document Library [Browse all]   [Card grid or list]       |
| - Staff Directory    |                                                            |
| - Birthdays          |  Right Rail: Quick Access links, Latest News, Quick Actions |
+----------------------+------------------------------------------------------------+

List View (Documents/News/Announcements index with view toggle)

+--------------------------------------------------------------+  [Grid ▢▢ | List ≡]
| Filter [Type v]  [Centre v]  [Access v]  [Search __ ]        |
+--------------------------------------------------------------+
| Card (grid) OR row (list):                                   |
| [Icon] Title                          Size    Date   Actions  |
| [Icon] Title                          Size    Date   Actions  |
| ...                                                          |
+--------------------------------------------------------------+

Detail View (News/Announcement)

+-------------------------------------------+-------------------+
| H1 Title                                  | Related/Metadata  |
| By Author • When • Location               | - Tags            |
|                                           | - Quick links     |
| [Hero/featured image if any]              | - Share/like      |
| [Rich content]                            |                   |
+-------------------------------------------+-------------------+

## Actionable Refactor Plan (Safe, Incremental)

Phase 1: Foundation (no visual breakage)
1) Tailwind config
   - Set `darkMode: 'class'`
   - Add plugins: `typography`, `line-clamp`, `aspect-ratio`
2) JS hygiene
   - Remove CDN Alpine from `layouts/dashboard.blade.php`; rely on Vite Alpine
   - Keep `resources/js/app.js` bootstrapping
3) Fix obvious markup issues (surgical)
   - `components/page-layout.blade.php` title binding
   - Duplicate `@extends` in `dashboard/index.blade.php`
   - `layouts/navigation.blade.php` malformed attributes/stray tags
4) Unify fonts
   - Prefer Inter across app; one source of truth (Bunny or Google); update one layout include, remove the other

Phase 2: Navigation and Layout Consistency
5) Standardize base layout for authenticated screens
   - Adopt `layouts/dashboard.blade.php` as the shell; extract inline styles into CSS
   - Convert ad-hoc modals and dropdowns to Blade components or Headless UI patterns
6) Global search accessibility
   - Add ARIA roles/ids; ensure keyboard nav uses `aria-activedescendant`
7) Introduce grid/list toggle component for indexes (documents/news/announcements)

Phase 3: Theming, Accessibility, and Polish
8) Dark mode pass
   - Add essential `dark:` variants to cards, headers, inputs; test contrast
9) Spacing/typography alignment
   - Apply `nimr-heading-*` and consistent spacing to high-traffic pages
10) Performance and cleanup
   - Move inline scripts to `resources/js/`; ensure Vite builds tree-shake

## What Will Change (and What Won’t)
- Won’t: Business logic, routes, data flows, permissions
- Will: Visual consistency, accessibility semantics, responsive polish, dark mode reliability, component reuse

## Optional Next Steps (Tooling)
- Add ESLint/Prettier for JS; stylelint or Tailwind class sorting (e.g., `prettier-plugin-tailwindcss`)
- Visual regression snapshots (e.g., Playwright) for critical views

---
If you want, I can implement Phase 1 as a small PR: update Tailwind config, remove duplicate Alpine, and fix the three markup issues without changing functionality.
