## Changelog

This changelog documents meaningful functional, architectural, UI/UX, and data-model changes from the point the project design was consolidated (2025-09) onward. Historical placeholder content was superseded for clarity.

### [Unreleased]

Planned / Proposed:

-   Introduce explicit `affiliation_type` (headquarters|centre|station) replacing dual `organizational_level + work_location` pattern.
-   Refactor station selection to direct `station_id` dropdown when station users are supported explicitly.
-   Config-driven allowed registration email domains (multi-domain support).
-   Backend Form Request classes for auth validation (testable encapsulation).

### [2025-10-09] Messaging Phase 11D: Infinite Scroll & Scroll Restoration

Added

-   Infinite scroll: IntersectionObserver on top sentinel to load older messages automatically when scrolling up.
-   Older messages endpoint: GET `/messages/conversations/{id}/items/older?before_id={id}&limit=30` fetches messages before a given message ID.
-   Scroll position preservation: Height delta calculation maintains scroll position after prepending older messages.
-   Loading indicator: Animated spinner with "Loading older messages..." text at top of message list.
-   "Beginning of conversation" marker: Displays when all messages loaded (no more history available).
-   Smart pagination: Backend returns up to 30 messages per request, frontend detects end of history when fewer returned.

Changed

-   Message container now includes scroll sentinel (invisible 1px div) at top for IntersectionObserver.
-   Conversation selection resets infinite scroll state (`hasMoreMessages`, `loadingOlder`, observer binding).
-   `loadOlderMessages()` preserves scroll position by compensating for new content height after prepend.

Technical Details

-   IntersectionObserver root margin: 100px (loads slightly before sentinel visible for smoother UX).
-   Scroll restoration: `scrollTop = previousScrollTop + (newHeight - previousHeight)` prevents jump.
-   Observer lifecycle: Bound on conversation load, unbound on conversation switch.
-   Backend endpoint reuses existing message query with `where('id', '<', beforeId)->orderByDesc('id')->limit()`.
-   Has more detection: If returned messages < limit (30), sets `hasMoreMessages = false`.

User Impact

-   Seamless browsing of long conversation history without manual "Load more" button clicks.
-   Scroll position maintained when loading older messages (no jarring jumps to top).
-   Visual feedback during loading prevents user confusion.
-   Performance: Lazy loads messages on demand rather than fetching entire conversation upfront.

### [2025-10-09] Messaging Phase 11E: Presence & Online Badges

Added

-   Presence heartbeat: Client sends heartbeat every 45 seconds to `/messages/presence/heartbeat`.
-   Presence tracking: Server-side caching of user online status (TTL: 60 seconds).
-   Online indicators: Green dot badges next to user names in message bubbles.
-   Presence fetch: Load online status on conversation selection via `/messages/conversations/{id}/presence`.
-   Visual feedback: Real-time green dot shows when message authors are currently online.

Changed

-   Widget initialization now starts heartbeat timer automatically.
-   Conversation selection fetches presence status in background.

Technical Details

-   Heartbeat interval: 45 seconds (prevents timeout before 60s TTL expires).
-   Cache-based presence: Uses Laravel cache with `presence:user:{id}` keys.
-   TTL: 60 seconds (configurable via `messaging.presence.ttl_seconds`).
-   Presence fetch returns array of online user IDs for conversation participants.
-   Green dot only shows for users with active heartbeat within TTL window.

User Impact

-   See which participants are currently active/online.
-   Real-time presence updates as users join/leave conversations.
-   No database writes (pure cache-based, minimal overhead).

### [2025-10-08] Messaging Phase 11B: Draft Autosave & Recovery

Added

-   Draft autosave: LocalStorage persistence (300ms debounce) for instant recovery on refresh/navigation.
-   Draft server sync: Background synchronization to `message_drafts` table for cross-device draft roaming (2s throttle).
-   Draft restoration: Automatic draft loading on conversation selection with smart merge (local-first, server backup).
-   Draft clearing: Automatic cleanup on message send (both local and server).
-   Visual feedback: "Saving..." and "Draft saved ✓" indicators in message compose area.
-   Draft endpoints: GET/PUT/DELETE `/messages/conversations/{id}/draft` with participant authorization.

Changed

-   Message textarea now triggers both typing indicator and draft save on input (combined debounce 300ms).
-   Conversation switching clears draft field before loading new conversation's draft.

Technical Details

-   Hybrid approach: LocalStorage for speed + offline resilience, server for cross-device sync.
-   Server draft wins in merge conflicts (cross-device scenario assumes server is source of truth).
-   Draft save errors fail silently (console warnings only) to avoid interrupting user experience.
-   All draft operations respect existing conversation view policies.

User Impact

-   No message loss on accidental refresh, browser crash, or navigation.
-   Seamless draft continuation across devices (mobile/desktop).
-   Each conversation maintains independent draft state.

### [2025-10-08] Messaging Phase 11A: Real-Time Integration (Completed)

Added

-   Laravel Echo integration with Pusher broadcaster (fallback to polling on disconnect).
-   Real-time event listeners: `message.created`, `message.deleted`, `conversation.user.typing`.
-   Typing indicator broadcast: Ephemeral event with 6s TTL and 2s throttle.
-   Typing indicator UI: Animated "user typing..." display in message view.
-   Tombstone rendering: Soft-deleted messages show placeholder instead of content.
-   Polling short-circuit: Automatic disable when Echo connected (detect via `echoConnected` flag).

Changed

-   Message polling now checks `echoConnected` state before executing (fallback mode only).
-   Widget subscribes/unsubscribes to conversation channels on switch.

Technical Details

-   Echo initialization with retry logic (400ms intervals until `window.__echoReady`).
-   Private channel authorization via existing conversation policies.
-   Duplicate message prevention (check message.id before appending from push event).
-   Typing indicator self-filter (ignore own typing events).
-   Pusher configuration via environment variables with graceful degradation.

### [2025-10-08] UI Fixes: Sidebar Navigation

Fixed

-   Sidebar collapse/expand now fully functional via hamburger menu (3 lines icon).
-   Sidebar completely hides when collapsed (no partial width state).
-   Removed redundant expand arrow (>>), collapse controlled by hamburger only.
-   Dropdown sections now properly hidden on load, expand only on click.
-   Fixed Alpine.js scope conflicts by centralizing state in dashboard layout.
-   View mode toggle (Admin/Staff) styling corrected with proper CSS fallbacks.

Changed

-   Consolidated all Alpine x-data from sidebar to global dashboard layout scope.
-   Sidebar uses x-show with transitions instead of width toggling.
-   Dropdown sections use x-cloak to prevent flash before Alpine initialization.
-   On desktop (>= 1024px), sidebar always visible and fixed in place.

Technical Details

-   Fixed static class override issue (moved all classes to x-bind:class).
-   Pusher initialization now checks for key existence before init (prevents console errors).

Enhancements (Pending Release):
Added

-   Floating messaging widget (global) with toggle button, unread aggregate badge, compact conversation list, inline message view.
-   New group creation modal (any user): title input + live user search (global, debounced) + chip selection.
-   Global user search endpoint (`/messages/user-search`) supporting pre-conversation participant lookup (throttled 30/min).

Changed

-   Reused existing JSON `/messages` index response to power lightweight widget refresh (no duplicate endpoint added).

Notes

-   Widget supports both real-time (Echo/Pusher) and polling fallback (5s interval).
-   Access limited to authenticated users; respects existing conversation/message policies.
-   All real-time features degrade gracefully when broadcaster unavailable.

### [2025-09-28] Messaging Module Phase 1.1 (Admin & Hardening)

Added

-   Messaging: participant user search endpoint & UI (live search up to 10 results, excludes existing & self).
-   Messaging: toast notifications (add/remove participants, rename, delete feedback).
-   Messaging: super admin unconditional delete override (policy + UI exposure) for audit / moderation.
-   Throttling: rate limit (30 req/min) applied to participant user-search endpoint to mitigate brute-force enumeration.
-   Tests: Feature test suite now tracked in VCS (removed broad ignore patterns), enabling CI integration.

Changed

-   Message deletion response now returns HTTP 204 (no content) for successful deletes (REST semantics).
-   Front-end delete button logic extended: super admins always see delete control (server policy authoritative).
-   .gitignore narrowed (removed blanket _test_.php and /tests/ exclusions) to prevent accidental omission of critical coverage.

Fixed

-   Deletion timing enforcement: policy now respects 5‑minute window by honoring model-supplied timestamps; message model allows mass-assignment of timestamps for deterministic test coverage.

Security / Operational

-   Rate limiting foundational layer for future sensitive endpoints (consider per-IP + user composite strategy later).
-   Super admin delete actions still logged via existing ActivityLogger event `message.delete`.

Developer Notes

-   Added fillable `created_at`/`updated_at` on `Message` solely to support deterministic test scenarios; production usage should avoid arbitrary timestamp mutation.
-   Consider future refactor to soft-delete with tombstone audit trail instead of hard delete for long-lived compliance requirements.

Backward Compatibility

-   No schema changes. Policy signature for `deleteMessage` now includes conversation parameter (aligns with authorize call pattern) – update any custom Gate calls accordingly if they existed.

### [2025-09-28] Messaging Module Phase 1 (Foundations + Enhancements)

Added

-   Core messaging module (direct & group conversations, messages CRUD-lite, unread tracking via participant last_read_message_id).
-   Attachments support with upload endpoint, hashed storage under public disk, per-message limits (count & size) and MIME allowlist.
-   Participant management: list, add (idempotent), remove (creator-protected), leave (non-creator) for group conversations.
-   Group conversation rename endpoint + inline UI control (policy enforced: creator or super admin; groups only).
-   Activity logging events for conversation create, participant add/remove/leave, conversation rename.
-   Feature tests: core messaging flows, attachments (valid, invalid MIME, oversize, unauthorized), participant management (add/remove/leave restrictions), rename, unread count behavior.

Changed

-   Messaging Blade UI: polling refinements, participants modal, inline rename, basic attachment display list.
-   Documentation (`documentation/MESSAGING_MODULE.md`) expanded with Attachments, Participant Management, Rename sections.

Technical Notes

-   Polling remains (4s) — sockets intentionally deferred to keep scope lean.
-   Authorization centralized in `ConversationPolicy` including new `rename` ability mirroring participant admin rules.
-   Tests avoid GD dependency by using `UploadedFile::fake()->create` instead of `image()` for portability.

Backward Compatibility

-   No existing tables altered outside new messaging tables (introduced in earlier migration set — ensure migrations run).
-   No config breaking changes; `config/messaging.php` can be tuned without affecting unrelated modules.

Operational Guidance

-   After deploy: run migrations, clear config & route cache, ensure public storage symlink present (`php artisan storage:link`).
-   Monitor disk usage under `storage/app/public/chat/*` for attachment growth.

Security Considerations

-   MIME allowlist enforced; recommend future AV scanning hook before expanding allowed types.
-   Creator cannot be removed or leave a group, preserving ownership accountability.

### [2025-09-24] Registration Hierarchy Enhancements

Added

-   Headquarters Department selection (`department_id`) surfaced during registration when user chooses Headquarters.
-   Active HQ departments list (Research, Ethics, Internal Auditor, Legal, ICT, Procurement, Public Relations, Finance, Planning, Human Resource) injected into registration view.
-   Validation rule for optional `department_id` (exists constraint) and persisted link on user creation.
-   Design notes document: `documentation/REGISTRATION_DESIGN_NOTES.md` capturing hierarchy model, risks, and refactor roadmap.
-   Canonical HQ department taxonomy seeder (`HeadquartersDepartmentSeeder`) ensuring normalized names, codes, and descriptions.
-   Department normalization command `departments:normalize` (dry-run, reassign, deactivate legacy) for ongoing data hygiene.

Changed

-   Registration UI now conditionally renders HQ department select alongside existing centre/station logic.
-   `RegisteredUserController@create` now supplies `$hqDepartments` collection.

Technical Rationale

-   Lays groundwork for finer-grained HQ user segmentation without overhauling existing centre/station flow.
-   Keeps current simplified model while documenting migration path to clearer `affiliation_type`.

### [2025-09-23] Auth UI Modernization

Added

-   Glassmorphism utilities (`glass-card`, `glass-card-xl`, `glass-accent-edge`, `glass-input`) consolidated in custom stylesheet.
-   Password visibility toggle modularized into `resources/js/auth.js`.

Changed

-   Login (landing) and registration pages restyled: unified translucent panels, radial gradient background, professional typography (Plus Jakarta Sans).
-   Consistent white text across form labels, helper text, and interactive elements for maximal contrast on glass background.
-   Footer repositioned to global page context.

Accessibility / UX

-   Focus styles retained with visible rings.
-   Placeholder contrast tuned; semantic headings introduced (`Create your account`).

### [2025-09-22] Component & Structure Refinements

Added

-   Input component variant handling for glass styling.

Changed

-   Removed inline per-field styling in favor of reusable utilities.
-   Extracted password toggle script from inline Blade to ES module pipeline (Vite).

### [2025-09-21] Initial Design System Alignment

Added

-   Base set of design tokens & utility classes for emerging intranet design system.

Changed

-   Standardized panel spacing, rounded corners, and subtle border hairlines across guest layout.

### Conventions Going Forward

-   Date format: YYYY-MM-DD.
-   Group entries by deployment / merge date; combine small incremental commits into a cohesive narrative section.
-   Use Added / Changed / Removed / Fixed / Security headings as needed.
-   Avoid logging trivial text copy edits unless user-facing meaning changes.

### Deprecated / Legacy Notes

Previous placeholder change log content (database migration narrative) archived offline; real production DB migration steps will be re-documented when actual engine transition is scheduled.

---

Last updated: 2025-09-24
