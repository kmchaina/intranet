# System Test Plan

Version: 1.0  
Date: 2025-09-28  
Scope Release: v0.5.0 (Messaging Phase 1.1) + Core Modules
Owner: QA / Engineering Collaboration

---
## 1. Purpose
Provide a consolidated, actionable blueprint to validate end-to-end functional behavior, security posture basics, role-based authorization, UX integrity, and regression stability across the intranet platform. This plan combines manual exploratory flows, deterministic scripted checks, and existing automated PHPUnit coverage to guide a structured test cycle before broader rollout.

## 2. Objectives
- Confirm critical user journeys succeed across all supported roles.
- Expose authorization leaks, missing validations, or unsafe destructive actions.
- Validate messaging module maturity (group vs direct conversations, attachments, participant lifecycle, deletion policies, search add-on readiness placeholder).
- Ensure administrative surfaces (announcements, documents, events, polls, news, password vault, system links) operate within intended role boundaries.
- Provide measurable exit criteria for sign‑off.

## 3. In-Scope Modules
| Area | Features Covered | Notes |
|------|------------------|-------|
| Authentication & Registration | Login, registration variants (HQ/Centre/Station paths), logout, email verification (if enabled), password reset (if configured) | Confirm domain / role assignment logic |
| Roles & Authorization | Super Admin, HQ Admin, Centre Admin, Station Admin, Staff | Policy-based gates, UI visibility |
| Messaging | Direct/group create, unread tracking, mark-read, attachments (size/MIME/count), participant add/remove/leave, rename, delete window, super admin override, throttled user search, toasts | Future: search indexing, real-time |
| Activity Logging | conversation.*, message.post/delete, participant.*, rename events | Verify persisted entries if UI surfaced later |
| Announcements | CRUD, attachments download, mark-as-read, read status badge | |
| Documents | CRUD, secure download | |
| Events | CRUD, RSVP, attendance marking, calendar UI | |
| Password Vault | CRUD, usage tracking | Sensitive data handling |
| To-Do Lists | CRUD, toggle, progress updates | |
| Polls | CRUD, vote, results, double-vote prevention | |
| News | CRUD, like/unlike, comments | |
| Badges & Adoption | Badge display, awarding logic triggers (smoke), adoption daily aggregates | |
| System Links | CRUD, click/increment, favorite toggle | |
| Feedback | Submit, status update (admin) | |
| Global Search | Basic results relevance, suggestions endpoint | Messaging integration future |
| UI/Design System | Layout consistency, components, dark mode (if present), glassmorphism styles | |
| Localization | English (baseline) + Swahili sample keys present | Spot-check critical UI |
| Performance (Smoke) | First-byte & render sanity (<1s dev), DB query hotspots (informal) | |

## 4. Out of Scope (Current Cycle)
- Full accessibility audit (WCAG deep pass) – partial spot checks only.
- Real-time websocket integration (deferred; polling baseline).
- Advanced search relevance ranking and cross-module federated search.
- Virus scanning of attachments (future enhancement placeholder).
- Soft deletion / audit tombstones (future compliance iteration).

## 5. Test Environment
| Aspect | Configuration |
|--------|---------------|
| Framework | Laravel (PHP 8.2) |
| DB (Automated) | SQLite in-memory (phpunit.xml) |
| DB (Manual) | MySQL / MariaDB recommended for FULLTEXT prep (future) |
| Storage | public disk symlink (`php artisan storage:link`) |
| Queue | sync (testing); future: async for heavy tasks |
| Cache | array / file (testing) |
| Mail | array (testing) |
| Browser Targets | Latest Chrome, Firefox ESR, Chromium Edge | 
| Roles Test Accounts | One seeded or manually created per role |

## 6. Personas & Role Matrix
| Persona | Role | Primary Capabilities |
|---------|------|----------------------|
| Olivia | Super Admin | Full system control, policy overrides |
| Henry | HQ Admin | HQ content mgmt (announcements, policies) |
| Clara | Centre Admin | Centre-level management, events, staff ops |
| Sam | Station Admin | Station subset of operations |
| Ben | Staff User | Core consumption, messaging participation |

## 7. Risk Areas & Mitigations
| Risk | Impact | Mitigation |
|------|--------|------------|
| Incorrect authorization in messaging participant actions | Data leakage / membership abuse | Policy tests + manual role matrix passes |
| Attachment upload bypass (MIME spoof) | Security / storage misuse | Enforced validation + future scan placeholder |
| Message deletion misuse (beyond window) | Integrity loss | Super admin only override; time-gated policy tests |
| Throttle absence on search | Enumeration risk | throttle:30,1 applied; attempt to exceed to confirm 429 |
| Hard delete logs insufficient for audit | Compliance gaps | Logged events; future soft-delete plan documented |

## 8. Exit Criteria
- 0 unresolved Critical / High severity defects.
- All P1 (Core Journey) test cases pass.
- Automated suite green (feature + future unit tests).
- Manual regression checklist 100% executed & signed.
- Changelog / release notes updated (done for v0.5.0).

## 9. Test Categories & Representative Cases
### 9.1 Authentication & Roles
| ID | Case | Steps | Expected |
|----|------|-------|----------|
| AUTH-01 | Register HQ user | Fill form selecting HQ dept | User created w/ department_id set |
| AUTH-02 | Register centre user | Choose centre; submit | Centre linkage established |
| AUTH-03 | Login / Logout cycle | Valid credentials; logout | Session created then destroyed |
| AUTH-04 | Role UI isolation | Login as Staff | No admin nav items visible |

### 9.2 Messaging Core
| ID | Case | Steps | Expected |
|----|------|-------|----------|
| MSG-01 | Create direct conversation | User A -> User B | Conversation type=direct, 2 participants |
| MSG-02 | Create group conversation | Provide title + participants | type=group, participants persisted |
| MSG-03 | Unread tracking | User A sends, User B views later | last_read increments on mark-read |
| MSG-04 | Attachment valid | Upload <= limits allowed MIME | Stored & metadata returned |
| MSG-05 | Attachment invalid MIME | Upload .exe | 422 validation error |
| MSG-06 | Participant add | Add new user to group | Appears in participants list |
| MSG-07 | Participant remove blocked (creator) | Attempt to remove creator | 403 / error |
| MSG-08 | Leave conversation (non-creator) | Member chooses leave | Removed & list refresh |
| MSG-09 | Rename group (creator) | Change title | Title updated & logged |
| MSG-10 | Rename denied (non-privileged) | Member attempts rename | 403 |
| MSG-11 | Delete own message inside window | Send then delete <5m | 204, disappears |
| MSG-12 | Delete own message after window | Wait >5m (manually adjust timestamp) | 403 |
| MSG-13 | Super admin delete any | Delete other’s old message | 204 success |
| MSG-14 | User search throttle | Perform >30 queries in a minute | 429 on excess |
| MSG-15 | Toast feedback | Perform add/remove/delete actions | Toasts appear & auto-disappear |

### 9.3 Announcements
| ANN-01 | Create announcement | Fill form incl. body | Listed with metadata |
| ANN-02 | Mark as read | Click read action | Badge decrements |
| ANN-03 | Attachment download | Download file | File served w/ correct headers |

### 9.4 Documents
| DOC-01 | Upload document | Provide valid file | Visible in index |
| DOC-02 | Unauthorized access | Non-owner restricted (if policy) | 403 / hidden |

### 9.5 Events
| EVT-01 | Create event | Fill form w/ schedule | Appears calendar + list |
| EVT-02 | RSVP | User submits RSVP | Status stored |
| EVT-03 | Attendance mark | Admin marks user attended | Flag persisted |

### 9.6 Password Vault
| PV-01 | Create secret | Add entry | Encrypted/accessible to allowed role |
| PV-02 | Record usage | Use action | Usage event stored |

### 9.7 To-Do
| TODO-01 | Create item | Add new | Appears list |
| TODO-02 | Toggle complete | Click toggle | State flips |
| TODO-03 | Progress update | Set % | Stored |

### 9.8 Polls
| POLL-01 | Create poll | Add options | Persisted |
| POLL-02 | Vote | Submit vote | Count increments |
| POLL-03 | Double vote blocked | Re-submit | Rejected |

### 9.9 News
| NEWS-01 | Create news item | Add content | Listed |
| NEWS-02 | Like/unlike | Toggle like | Count changes |
| NEWS-03 | Comment | Add comment | Appears under item |

### 9.10 Badges & Adoption
| BADGE-01 | Badge widget display | View dashboard | Recent badges visible |
| ADOPT-01 | Adoption daily aggregation | Trigger command (if any) | Row created/updated |

### 9.11 System Links
| LINK-01 | Create link | Provide URL | Listed |
| LINK-02 | Click increments | Click link | Click count +1 |
| LINK-03 | Favorite toggle | Toggle star | Favorite flag persists |

### 9.12 Feedback
| FB-01 | Submit feedback | Fill form | Stored |
| FB-02 | Update status | Admin changes status | Updated & visible |

### 9.13 Global Search
| SRCH-01 | Search common term | Enter query | Multi-module results |
| SRCH-02 | Suggestions | Type partial | JSON suggestion list returned |

### 9.14 UI / Design System
| UI-01 | Layout consistency | Navigate modules | Header/sidebar consistent |
| UI-02 | Dark / contrast variant | Toggle (if present) | Theme updates |
| UI-03 | Responsive width | Resize to mobile | Menu adapts |

### 9.15 Localization
| L10N-01 | Switch language (if toggle) | Activate Swahili | Key strings translate |
| L10N-02 | Fallback behavior | Missing key simulation | English fallback |

### 9.16 Performance Smoke
| PERF-01 | Dashboard load | Measure dev environment | <1s server time |
| PERF-02 | Messaging load with 30 messages | Load conversation | Single page load without N+1 (inspect queries) |

## 10. Traceability Matrix (Abbreviated)
| Requirement Area | Representative Test IDs |
|------------------|-------------------------|
| Messaging CRUD & Policy | MSG-01..MSG-15 |
| Attachments | MSG-04, MSG-05 |
| Roles & Authorization | AUTH-04, MSG-07, MSG-10, MSG-13 |
| Notifications/Feedback | MSG-15 |
| Search (basic) | SRCH-01, SRCH-02 |
| Content Modules | ANN-01..ANN-03, DOC-01..DOC-02, EVT-01..EVT-03, POLL-*, NEWS-* |
| Security Controls | MSG-12, MSG-13, MSG-14 |
| Adoption/Badges | BADGE-01, ADOPT-01 |

## 11. Execution Strategy
- Automated first (phpunit) → ensure green baseline.
- Seed test accounts (or factories) for each persona.
- Run high-risk manual tests (messaging, attachments, throttling) early.
- Perform breadth pass across content modules.
- Conclude with UI + performance smoke & localization spot checks.

## 12. Tooling & Commands
Run automated tests:
```powershell
php vendor/bin/phpunit
```
Re-run a subset (e.g., messaging):
```powershell
php vendor/bin/phpunit --filter=Messaging
```
(Optional) Cache clear between scenario sets:
```powershell
php artisan optimize:clear
```
Run adoption aggregation command (if present):
```powershell
php artisan adoption:aggregate-daily
```

## 13. Defect Classification
| Severity | Definition |
|----------|------------|
| Critical | Data loss, security exposure, core workflow blocked |
| High | Major feature unusable; no workaround |
| Medium | Incorrect behavior; workaround exists |
| Low | Minor UI issue or cosmetic defect |
| Trivial | Typos / non-blocking visual polish |

## 14. Reporting
Daily test execution log should capture: date, tester, case IDs executed, pass/fail, defect references. Maintain lightweight spreadsheet or issue tracker tags (e.g., label:system-test).

## 15. Open Questions / Follow-Ups
- Will we introduce soft delete before external pilot? (Impacts audit tests.)
- Do we need data seeding scripts to accelerate persona provisioning for staging? 
- Should messaging search move into next sprint to reduce retrieval friction?

## 16. Future Enhancements (Testing Roadmap)
| Future Feature | Testing Impact |
|----------------|----------------|
| Real-time websockets | Add latency & event sequence validation |
| Message reactions / pins | Extend messaging matrix (reaction persistence, toggle idempotency) |
| Attachment virus scanning | Quarantine state & delayed availability tests |
| Soft delete & restore | Tombstone presence, restore integrity |
| External search index | Relevance scoring tests, pagination, stemming |

## 17. Sign-Off Template
| Role | Name | Date | Approval |
|------|------|------|----------|
| Engineering Lead |  |  |  |
| QA Lead |  |  |  |
| Product Owner |  |  |  |

---
Prepared for release validation of v0.5.0. Update this document as scope evolves.
