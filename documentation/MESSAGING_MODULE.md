# Messaging Module

This document describes the internal architecture and usage of the Messaging feature.

## Data Model
- conversations (id, type: direct|group, title nullable for direct, created_by)
- conversation_participants (conversation_id, user_id, last_read_message_id)
- messages (id, conversation_id, user_id, body, attachments JSON, timestamps)

Direct conversations: exactly two participants; reused if it already exists between the same two users.
Group conversations: title required, 2+ other participants plus creator automatically added.

Read state: A participant row stores last_read_message_id pointer. Unread count per conversation = messages with id > last_read_message_id authored by others.

## Authorization (ConversationPolicy)
- view / send: participant of the conversation
- addParticipant / removeParticipant: group only, creator or super admin
- destroy: creator or super admin
- deleteMessage: author within 5 minutes, else super admin

## Endpoints
Routes are namespaced under authentication and CSRF protection.

GET /messages
- Returns JSON list of user conversations (id, title, last_message, unread count)
- Also serves the full Blade UI when requested via browser (HTML) at route name messages.index

GET /messages/conversations/{id}
- Returns conversation meta + last 30 messages (ascending order)

POST /messages/direct { user_id }
- Creates or reuses a direct conversation with specified user; returns { id }

POST /messages/group { title, participants[] }
- Creates a new group conversation with the provided participants; returns { id }

POST /messages/conversations/{id}/mark-read
- Marks latest message as read for current user

GET /messages/conversations/{id}/items?after_id=123
- Returns messages after a given id (polling). Without after_id, returns last 30.

POST /messages/conversations/{id}/items { body, attachments[] }
- Sends a message. Either body or attachments required. Attachments: [{ name, url }]

DELETE /messages/conversations/{id}/items/{message}
- Deletes a message if policy permits

## UI (Blade + Alpine.js)
- Sidebar link with unread badge computed server-side
- Polling every 4 seconds for new messages in open conversation
- New group creation modal with comma-separated participant IDs (placeholder to be refined with user picker)
- Automatic scrolling to newest messages
- Batch refresh of conversation list after message activity

## Activity Logging
Logged events:
- conversation.create (metadata: mode direct|group)
- message.post (metadata: conversation_id)
- message.delete (metadata: conversation_id)

Participant events:
- conversation.participant.add (metadata: conversation_id, user_id added, count added)
- conversation.participant.remove (metadata: conversation_id, user_id removed)
- conversation.participant.leave (metadata: conversation_id, user_id)

These feed adoption/badge mechanics.

## Caching
Per-user sidebar counts (announcements, polls, events, unread conversations) cached ~90s via cache key sidebar_counts_user_{id}.

## Future Enhancements
- Real user search + multi-select for participant selection
- WebSockets / Broadcasting for real-time updates instead of polling
 - Typing indicators and online presence
 - Conversation settings (rename, role delegation, description/topic)
- Soft delete or edit messages with audit trail

## Participant Management (Implemented)
Endpoints enable listing, adding, removing, and self-leaving (with safeguards) for group conversations.

### Endpoints
GET /messages/conversations/{id}/participants
- List participants for a conversation the user can view.
- Response: { participants: [ { id, name } ... ] }

POST /messages/conversations/{id}/participants { participants: [user_id, ...] }
- Add one or more users to a group conversation.
- Requires policy addParticipant (creator or super admin; only for group type).
- Skips users already present (idempotent). Returns { added: N }.

DELETE /messages/conversations/{id}/participants/{user}
- Remove a participant from a group conversation.
- Requires policy removeParticipant (creator or super admin; cannot target creator).
- Returns 204 on success.

POST /messages/conversations/{id}/leave
- Current user leaves a group conversation.
- Forbidden if user is the creator (ensures a permanent owner) or if conversation is direct.
- Returns 204 on success.

### Authorization Rules (Supplemental)
- Direct conversations: no participant add/remove/leave endpoints permitted (enforced both in routes via policy and controller guards).
- Creator protections: creator cannot be removed and cannot leave; only super admin could eventually destroy conversation (future destroy not yet implemented in UI).
- Add/remove limited to group conversations and to creator or super admin roles.

### UI Behavior
- "Manage" link opens a modal (Alpine.js) loading participants via GET endpoint.
- Adding participants: comma-separated user IDs (placeholder until user search implemented) -> POST -> refresh list & conversation roster in sidebar.
- Removing participant triggers DELETE; user is optimistically removed from modal list.
- Leave option appears only for non-creator participants.

### Activity Logging
Each participant lifecycle action logs an ActivityEvent (see Activity Logging section) enabling analytics and potential badge triggers.

### Edge Cases & Safeguards
- Duplicate additions suppressed silently (idempotent adds array diff).
- Attempt to remove creator returns 422/403 (depending on policy evaluation path) and no change.
- Attempt to leave direct or as creator returns 403.
- Unauthorized access to participant endpoints returns 403 via ConversationPolicy.

### Testing Coverage (tests/Feature/MessagingParticipantsTest.php)
- Listing participants of owned conversation.
- Adding new participants (including multiple) increases count & ignores existing.
- Removing a participant updates count and prevents self-removal of creator.
- Leave flow success for non-creator participant.
- Direct conversation participant modifications rejected.
- Creator leave attempt rejected.

### Future Improvements (Participants)
- Replace manual ID entry with searchable multi-select (AJAX user lookup / typeahead).
- Allow ownership transfer so creator can leave after delegating.
- Participant roles (moderator) for finer-grained permissions.
- Notification (email/in-app) on being added to a conversation.

## Rename Group Conversation (Implemented)
PATCH /messages/conversations/{id}/title { title }
- Group conversations only; 422 returned if attempted on direct.
- Authorization: ConversationPolicy::rename (creator or super admin).
- Validates: title required, string, max 80 chars.
- Response: { id, title }
Activity logged: conversation.rename with new title.
UI: (Not yet wired) — future minimal enhancement could add inline edit or small modal; current scope backend-only to keep complexity down.

## Attachments (Implemented)
JSON structure stored on each message:
[
	{
		"name": "doc.png",
		"url": "/storage/chat/2025/09/abc123.png",
		"size": 12345,
		"mime": "image/png",
		"ext": "png",
		"kind": "image" | "doc" | "other"
	}
]

Upload Endpoint:
POST /messages/conversations/{conversation}/attachments (multipart form-data: files[])
Returns: { attachments: [ ...metadata ] }
Limits: max 5 files per message, 5MB each.
Allowed MIME types (config/messaging.php): images (jpeg/png/gif/webp), pdf, docx, xlsx, pptx, txt.

Usage Flow:
1. Client uploads selected files -> receives metadata.
2. Client includes metadata array in POST /messages/conversations/{id}/items.
3. Message persists attachments JSON unchanged.

Security Notes:
- Filenames hashed on disk; original name retained for display only.
- Strict MIME whitelist; oversize and count validated.
- Future antivirus / async scanning hook can be inserted post-store.

Testing Coverage (tests/Feature/MessagingAttachmentsTest.php):
- Valid upload returns metadata
- Disallowed MIME rejected
- Oversize rejected
- Unauthorized conversation access returns 403
- End-to-end send message with uploaded attachment

## Testing
Feature tests in tests/Feature/MessagingTest.php cover:
- Direct conversation creation & reuse
- Group conversation creation & posting messages
- Unauthorized access prevention

Add more tests for:
- Message deletion policy window
- Unread counts logic (unit-level or integration scenario)

## Developer Notes
Keep polling interval conservative to limit load. When adding broadcast events, remove client polling logic. Ensure indexes on messages(conversation_id, id) for performance.

## Message Deletion (Implemented)
Endpoint:
DELETE /messages/conversations/{id}/items/{message}

Rules:
- Author can delete within 5 minutes of creation.
- Super admin (policy) may delete after window (future UI optional).
- Others receive 403.

UI Behavior:
- Inline delete button shown only on own messages still within 5-minute window (client pre-check + server enforcement).
- On success message removed locally; failures logged silently (future toast optional).

Activity Logging:
- message.delete event already captured for analytics/badges.

Testing Coverage (tests/Feature/MessagingDeleteTest.php):
- Author delete inside window (success).
- Author delete after window (403).
- Non-author delete attempt (403).

Future Improvements:
- Soft delete (retain tombstone) instead of hard delete.
- Attachment physical cleanup (current deletion leaves files on disk for potential retention/audit period).
- Admin override UI control for post-window removals.
