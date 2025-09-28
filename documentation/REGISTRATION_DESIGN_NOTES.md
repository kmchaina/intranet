# Registration & Hierarchy Design Notes

## Overview
The registration flow captures hierarchical placement of users within Headquarters, Centres, and Stations. Recent enhancement adds explicit Headquarters Department selection for HQ users.

## Current Data Captured
- Full Name (`name`)
- Work Email (`email`) – unique, optionally domain restricted in production (@nimr.or.tz)
- Organizational Level (`organizational_level`): `headquarters` | `centre`
- Centre (`centre_id`, nullable)
- Work Location (`work_location` transient UI token: `centre` or `station_{id}`)
- Station (`station_id`, derived from `work_location` if station chosen)
- Headquarters Department (`department_id`, HQ-only, nullable)
- Password (with Laravel default strength rules)

## Hierarchy Logic (Simplified Model)
| Scenario | Required Fields | Derived | Notes |
|----------|-----------------|---------|-------|
| Headquarters user | `organizational_level=headquarters`, optional `department_id` | `station_id=null`, `centre_id=null` | Department recorded if chosen |
| Centre user (at centre) | `organizational_level=centre`, `centre_id`, `work_location=centre` | `station_id=null` | Centre has/has not stations; still valid |
| Centre user (station) | `organizational_level=centre`, `centre_id`, `work_location=station_{id}` | `station_id` extracted | Validated belongs to centre |

## Departments
For now only Headquarters departments are selectable (active list):
Research, Ethics, Internal Auditor, Legal, ICT, Procurement, Public Relations, Finance, Planning, Human Resource.

## Validation Rules (Key Points)
- Email uniqueness and (production) domain regex.
- `organizational_level` must be one of: headquarters, centre.
- `centre_id` required when `organizational_level=centre`.
- If centre has active stations and organizational_level=centre → `work_location` required.
- If `work_location` refers to a station, station existence + ownership validated.
- `department_id` optional but must exist when supplied.

## Identified Risks & Ambiguities
1. Ambiguity in `organizational_level` for station users: they select `centre` then later a station, rather than choosing an explicit `station` level. This can obscure analytics on first glance.
2. Dual use of `work_location`: UI + data transport; encodes station ID inside its string token. This creates coupling and parsing logic in controller.
3. Department scope: Only HQ departments selectable; model allows department association at centre/station, but UI does not expose it yet.
4. Potential silent mismatch: A malicious crafted POST could send `station_id` directly; current custom validation covers centre belonging for station but relies on transformation path.
5. Inconsistent future evolution: Adding a genuine `station` organizational level later will require migration & refactor of both UX and validation.

## Planned / Recommended Refactors
| Refactor | Benefit | Effort | Notes |
|----------|---------|--------|-------|
| Introduce `affiliation_type` (HQ, Centre, Station) | Clarity & direct mapping | Medium | Replaces dual field logic (`organizational_level` + `work_location`). |
| Replace `work_location` with explicit station select | Removes parsing complexity | Medium | Simpler server validation. |
| Extend departments to centre / station contexts | Richer organizational insight | Low/Medium | UI conditional selects. |
| Add DB constraint or rule ensuring station.centre_id = user.centre_id | Data integrity | Low | Could be enforced at app level or with trigger. |

## Email Domain Trust
Currently the domain restriction is enforced only in production using a regex. This ensures only organizational emails register. Risks if relaxed in non-production:
- Test accounts might slip into production export if reused.
- Social login or future SSO integration will supersede this regex; treat as interim control.
Recommendation: Centralize allowed domains in config (e.g. `config/auth.php`) for easier extension (e.g. subdomains) and add a descriptive validation error.

## Role Strategy
All users start with a single base role (implicit). Elevated roles (e.g. Centre Admin, Department Admin, System Admin) to be assigned post-registration by an administrator. Benefits:
- Reduced attack surface during sign-up.
- Enables approval workflow or email verification gating before privilege escalation.
Future: Add `pending_role_requests` table if self-service role requests are desired.

## Data Quality Considerations
- Enforce trimming & lowercase on email already present.
- Consider unique constraint + index coverage on (`centre_id`, `station_id`) for common filtering.
- Department optionality: track adoption metrics; if < threshold usage, prompt HQ users to select department post-first-login.

## Open Questions
- Will station-level departments ever diverge from HQ taxonomy? If yes, need taxonomy scoping logic soon.
- Should we block registration without email verification for privileged role assignment?

## Next Steps Snapshot
1. Decide on refactor path: keep current simplified approach or adopt explicit `affiliation_type`.
2. Add config-driven domain whitelist.
3. Introduce backend form request class to centralize validation (improves testability).
4. Consider migration to remove `work_location` entirely (transient only) in favor of direct `station_id` selection UI.
 5. Seed canonical HQ departments (RSP, ETH, IAD, LEG, ICT, PROC, PRC, FIN, PLN, HR) and ensure idempotent updates.
 6. Use `php artisan departments:normalize --dry` to audit and optionally clean legacy HQ departments.

---
_Last updated: current iteration (HQ departments added to registration form)._