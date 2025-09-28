# Release Notes: Messaging Module Phase 1 (2025-09-28)

## Overview
Initial rollout of the internal messaging module delivering direct & group conversations, attachments, participant management, rename capability, and foundational activity logging.

## Feature Summary
- Direct & Group Conversations (reuse direct pairings; group requires title)
- Messages with optional attachments (per-message count & size limits)
- Unread tracking via last_read_message_id pointers
- Participant Management (list/add/remove/leave with creator safeguards)
- Group Rename (creator or super admin only)
- Inline UI: attachments picker, participants modal, rename control
- Activity Logging events (create, participant lifecycle, rename)

## Endpoints (Key)
- GET /messages (list conversations + unread counts)
- GET /messages/conversations/{id} (metadata + last 30 messages)
- POST /messages/direct { user_id }
- POST /messages/group { title, participants[] }
- POST /messages/conversations/{id}/items { body?, attachments? }
- POST /messages/conversations/{id}/attachments multipart files[]
- POST /messages/conversations/{id}/mark-read
- GET /messages/conversations/{id}/participants
- POST /messages/conversations/{id}/participants { user_ids: [] }
- DELETE /messages/conversations/{id}/participants/{user}
- POST /messages/conversations/{id}/leave
- PATCH /messages/conversations/{id}/title { title }

## Authorization Highlights
- view/send: must be a participant
- add/remove participants & rename: creator or super admin (group only)
- creator cannot be removed or leave; direct conversations immutable re participants

## Attachments Configuration
`config/messaging.php`:
- max_per_message: 5
- max_size_kb: 5120
- allowed_mimes: limited set (images, pdf, Office docs, txt)

## Activity Events
- conversation.create (mode)
- participant.add / participant.remove / participant.leave
- conversation.rename
- message.post / message.delete (core foundation exists)

## Testing Coverage
Feature tests for: creation (direct/group), attachments (valid / invalid / oversize), participants (add/remove/leave rules), rename, unread counts. All green.

## Deployment / Upgrade Steps
1. Run migrations (if not already applied) `php artisan migrate`
2. Ensure storage symlink: `php artisan storage:link`
3. Clear & warm caches (optional but recommended):
   - `php artisan config:clear && php artisan route:clear && php artisan view:clear`
4. (Optional) Preload sidebar counts cache will rebuild on user navigation automatically.
5. Confirm public disk write permissions for attachment storage.

## Operational Notes
- Polling interval: 4s (keep until socket infra is justified). Monitor load; consider raising to 6–8s if traffic increases.
- Disk monitoring: watch `storage/app/public/chat/YYYY/MM`.
- Backups: include attachment directories in backup plan.

## Security / Risk Considerations
- MIME allowlist reduces executable upload risk.
- Creator lock-in prevents orphaned groups; ownership transfer not yet implemented.
- Attachments not virus scanned (documented future hook needed before expanding file types).

## Minimal Rollback Plan
- If critical issue: disable sidebar link (navigation config), remove routes block for messages; data tables remain inert.
- Clear caches and redeploy previous tag.

## Future (Out of Scope This Release)
- Real-time broadcasting (Pusher / Laravel WebSockets)
- User search & avatars in participants UI
- Ownership transfer & moderation roles
- Message editing / soft delete
- Typing indicators, presence

---
Release Owner: Messaging Phase 1 Automation
Date: 2025-09-28
