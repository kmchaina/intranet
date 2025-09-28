# Registration Discussion Summary

Date: 2025-09-24
Scope: Clarification of hierarchy capture, validation risks, ambiguity in organizational level, dual use of `work_location`, email domain trust model, and initial role assignment philosophy.

## 1. Validation Risks
| Risk | Description | Current Mitigation | Gap | Recommendation |
|------|-------------|--------------------|-----|----------------|
| Station–Centre mismatch | Crafted POST with `station_id` not belonging to `centre_id` | Controller custom validation (`validateSimplifiedHierarchy`) checks when using `work_location` token | Direct `station_id` injection path if `work_location` bypassed | Enforce explicit server check whenever `station_id` present (independent of work_location) |
| Missing centre for centre-level user | User selects centre level but omits `centre_id` | Validation rule + custom check | None | Keep; add Form Request for consistency |
| Work location token integrity | `work_location` embeds `station_{id}` string | Parsing logic only in controller | Tight coupling; harder to refactor | Replace with direct station select & remove token encoding |
| Department misuse (future) | Department IDs from non-HQ contexts | HQ-only list passed to view | No server-side parent scope check | Add rule verifying `department_id` belongs to HQ when level=headquarters |
| Silent orphan station | Centre deleted leaving user.station_id orphaned | DB foreign key `onDelete set null` | Business logic may still expect linkage | Periodic integrity job or constraint-based reporting |

## 2. Ambiguity in `organizational_level`
Station users are modeled as `organizational_level=centre` + `work_location=station_{id}`. This hides a distinct semantic category (station). It also complicates analytics and makes future policy rules less explicit.

Resolution Options:
- Minimal: Keep current design; document translation table (station users where `station_id` not null).
- Preferred: Introduce `affiliation_type` enum (headquarters|centre|station) and migrate existing data (derive station if `station_id` present; else centre; else HQ).

## 3. Dual Use of `work_location`
`work_location` is both a UX control and a data transport mechanism encoding selection state for either staying at centre or choosing a station via prefix pattern `station_#`.

Issues:
- Parsing overhead & fragility.
- Hard to validate with generic Laravel rules (needs custom logic).
- Inhibits clean future expansion (e.g. adding remote/hybrid location types).

Refactor Path:
1. Replace `work_location` with two explicit conditional controls: (A) radio: At Centre / Station (if stations exist) and (B) direct `station_id` `<select>` when Station chosen.
2. Persist only scalar `station_id` (nullable). Drop `work_location` field entirely (no storage or validation).

## 4. Email Domain Trust
Current: Production-only regex enforces `@nimr.or.tz` domain.
Considerations:
- Non-production flexibility is good for testing, but risks confusion if test accounts leak.
- Single-domain assumption may not hold (subdomains, service aliases).
- Regex lives inline inside controller, reducing discoverability and configurability.

Recommendations:
- Move allowed domain(s) to config, e.g. `config/registration.php` with `allowed_domains` array.
- Provide specific validation message referencing support contact if mismatch.
- Add optional toggle to force domain enforcement in all environments except `local`.
- Future: Consider domain-based auto-role suggestion (e.g. service accounts) while still requiring admin approval.

## 5. Role Strategy
Policy: All users register with a baseline role (implicit). Elevated administrative or managerial roles are manually assigned by an administrator post-registration.
Benefits:
- Reduces attack surface from privilege escalation via forged sign-up.
- Keeps onboarding simple for end users.
- Supports later workflow: role request tickets or approval queues.
Future Enhancements (Deferred):
- `role_requests` table tracking pending upgrade requests.
- Audit log entry when roles change (who changed, when, old/new roles).

## 6. Headquarters Department Field
Added a simple HQ department select. Present gap: user may skip selection (optional). If departmental reporting adoption is desired, a gentle post-login prompt can encourage completion.

Potential Future Extensions:
- Department scoping for centres/stations if taxonomy diversifies.
- Department-level access policies (e.g., document visibility) once adoption stabilizes.

## 7. Migration Roadmap (If Refactoring)
| Phase | Change | Notes |
|-------|--------|-------|
| 1 | Introduce `affiliation_type` column (nullable) | Backfill via data script. |
| 2 | Populate `affiliation_type` for existing users | station_id ? station : (centre_id ? centre : headquarters). |
| 3 | Update registration form to use radios for affiliation | Remove `organizational_level` + `work_location`. |
| 4 | Deprecate legacy fields in code paths | Mark in docs. |
| 5 | Drop / ignore `work_location` references | Final cleanup after grace period. |

## 8. Integrity & Monitoring
Add low-cost integrity command (artisan) to scan for:
- station_id without centre_id.
- department_id where organizational level ≠ headquarters.
- station belongs to different centre than recorded.
Reports anomalies to log + optional email to admins.

## 9. Open Questions / Decisions Needed
- Approve `affiliation_type` introduction? (Yes/Defer)
- Make HQ department mandatory for HQ users? (No for now)
- Add domain whitelist config sooner or with broader auth refactor? (Soon)

## 10. Immediate Action Recommendations
1. Implement server-level department HQ scope validation (light rule).
2. Draft migration for `affiliation_type` (optional now; keep behind feature flag).
3. Refactor email domain check into config-driven helper.
4. Replace `work_location` UI token with explicit station selection when capacity allows.

---
_Last updated: 2025-09-24_
