## Changelog

This changelog documents meaningful functional, architectural, UI/UX, and data-model changes from the point the project design was consolidated (2025-09) onward. Historical placeholder content was superseded for clarity.

### [Unreleased]
Planned / Proposed:
- Introduce explicit `affiliation_type` (headquarters|centre|station) replacing dual `organizational_level + work_location` pattern.
- Refactor station selection to direct `station_id` dropdown when station users are supported explicitly.
- Config-driven allowed registration email domains (multi-domain support).
- Backend Form Request classes for auth validation (testable encapsulation).
 
Enhancements (Pending Release):
// (none – all recent messaging enhancements released in 2025-09-29 section below)

### [2025-09-28] Messaging Module Phase 1.1 (Admin & Hardening)
Added
- Messaging: participant user search endpoint & UI (live search up to 10 results, excludes existing & self).
- Messaging: toast notifications (add/remove participants, rename, delete feedback).
- Messaging: super admin unconditional delete override (policy + UI exposure) for audit / moderation.
- Throttling: rate limit (30 req/min) applied to participant user-search endpoint to mitigate brute-force enumeration.
- Tests: Feature test suite now tracked in VCS (removed broad ignore patterns), enabling CI integration.

Changed
- Message deletion response now returns HTTP 204 (no content) for successful deletes (REST semantics).
- Front-end delete button logic extended: super admins always see delete control (server policy authoritative).
- .gitignore narrowed (removed blanket *test*.php and /tests/ exclusions) to prevent accidental omission of critical coverage.

Fixed
- Deletion timing enforcement: policy now respects 5‑minute window by honoring model-supplied timestamps; message model allows mass-assignment of timestamps for deterministic test coverage.

Security / Operational
- Rate limiting foundational layer for future sensitive endpoints (consider per-IP + user composite strategy later).
- Super admin delete actions still logged via existing ActivityLogger event `message.delete`.

Developer Notes
- Added fillable `created_at`/`updated_at` on `Message` solely to support deterministic test scenarios; production usage should avoid arbitrary timestamp mutation.
- Consider future refactor to soft-delete with tombstone audit trail instead of hard delete for long-lived compliance requirements.

Backward Compatibility
- No schema changes. Policy signature for `deleteMessage` now includes conversation parameter (aligns with authorize call pattern) – update any custom Gate calls accordingly if they existed.

### [2025-09-28] Messaging Module Phase 1 (Foundations + Enhancements)
Added
- Core messaging module (direct & group conversations, messages CRUD-lite, unread tracking via participant last_read_message_id).
- Attachments support with upload endpoint, hashed storage under public disk, per-message limits (count & size) and MIME allowlist.
- Participant management: list, add (idempotent), remove (creator-protected), leave (non-creator) for group conversations.
- Group conversation rename endpoint + inline UI control (policy enforced: creator or super admin; groups only).
- Activity logging events for conversation create, participant add/remove/leave, conversation rename.
- Feature tests: core messaging flows, attachments (valid, invalid MIME, oversize, unauthorized), participant management (add/remove/leave restrictions), rename, unread count behavior.

Changed
- Messaging Blade UI: polling refinements, participants modal, inline rename, basic attachment display list.
- Documentation (`documentation/MESSAGING_MODULE.md`) expanded with Attachments, Participant Management, Rename sections.

Technical Notes
- Polling remains (4s) — sockets intentionally deferred to keep scope lean.
- Authorization centralized in `ConversationPolicy` including new `rename` ability mirroring participant admin rules.
- Tests avoid GD dependency by using `UploadedFile::fake()->create` instead of `image()` for portability.

Backward Compatibility
- No existing tables altered outside new messaging tables (introduced in earlier migration set — ensure migrations run).
- No config breaking changes; `config/messaging.php` can be tuned without affecting unrelated modules.

Operational Guidance
- After deploy: run migrations, clear config & route cache, ensure public storage symlink present (`php artisan storage:link`).
- Monitor disk usage under `storage/app/public/chat/*` for attachment growth.

Security Considerations
- MIME allowlist enforced; recommend future AV scanning hook before expanding allowed types.
- Creator cannot be removed or leave a group, preserving ownership accountability.

### [2025-09-24] Registration Hierarchy Enhancements
Added
- Headquarters Department selection (`department_id`) surfaced during registration when user chooses Headquarters.
- Active HQ departments list (Research, Ethics, Internal Auditor, Legal, ICT, Procurement, Public Relations, Finance, Planning, Human Resource) injected into registration view.
- Validation rule for optional `department_id` (exists constraint) and persisted link on user creation.
- Design notes document: `documentation/REGISTRATION_DESIGN_NOTES.md` capturing hierarchy model, risks, and refactor roadmap.
 - Canonical HQ department taxonomy seeder (`HeadquartersDepartmentSeeder`) ensuring normalized names, codes, and descriptions.
 - Department normalization command `departments:normalize` (dry-run, reassign, deactivate legacy) for ongoing data hygiene.

Changed
- Registration UI now conditionally renders HQ department select alongside existing centre/station logic.
- `RegisteredUserController@create` now supplies `$hqDepartments` collection.

Technical Rationale
- Lays groundwork for finer-grained HQ user segmentation without overhauling existing centre/station flow.
- Keeps current simplified model while documenting migration path to clearer `affiliation_type`.

### [2025-09-23] Auth UI Modernization
Added
- Glassmorphism utilities (`glass-card`, `glass-card-xl`, `glass-accent-edge`, `glass-input`) consolidated in custom stylesheet.
- Password visibility toggle modularized into `resources/js/auth.js`.

Changed
- Login (landing) and registration pages restyled: unified translucent panels, radial gradient background, professional typography (Plus Jakarta Sans).
- Consistent white text across form labels, helper text, and interactive elements for maximal contrast on glass background.
- Footer repositioned to global page context.

Accessibility / UX
- Focus styles retained with visible rings.
- Placeholder contrast tuned; semantic headings introduced (`Create your account`).

### [2025-09-22] Component & Structure Refinements
Added
- Input component variant handling for glass styling.

Changed
- Removed inline per-field styling in favor of reusable utilities.
- Extracted password toggle script from inline Blade to ES module pipeline (Vite).

### [2025-09-21] Initial Design System Alignment
Added
- Base set of design tokens & utility classes for emerging intranet design system.

Changed
- Standardized panel spacing, rounded corners, and subtle border hairlines across guest layout.

### Conventions Going Forward
- Date format: YYYY-MM-DD.
- Group entries by deployment / merge date; combine small incremental commits into a cohesive narrative section.
- Use Added / Changed / Removed / Fixed / Security headings as needed.
- Avoid logging trivial text copy edits unless user-facing meaning changes.

### Deprecated / Legacy Notes
Previous placeholder change log content (database migration narrative) archived offline; real production DB migration steps will be re-documented when actual engine transition is scheduled.

---
Last updated: 2025-09-24
