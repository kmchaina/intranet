# Intranet Adoption Strategy (Concise)

## 1. Purpose & Success
- **Why**: Ensure the intranet becomes a daily operational hub (communication, documents, collaboration) across HQ, centres, and stations.
- **Primary Success Metric**: Weekly Active Users (WAU) / Total Enabled Users ≥ 65% by end of Phase 3.
- **Support Metrics**: Announcement read coverage, document retrieval events, poll participation, birthday/engagement micro-interactions, task usage (if enabled).

## 2. Core Metrics (MVP)
| Metric | Definition | Target (Phase 1 → 2 → 3) |
|--------|------------|--------------------------|
| WAU % | Distinct users with any meaningful action in last 7 days / total | 35% → 50% → 65% |
| Daily Reach % | Users with at least one page view / total | 20% → 30% → 40% |
| Announcement Read Coverage | Avg % of targeted users who open/read within 72h | 50% → 65% → 80% |
| Document Access Events | Distinct docs opened per week | Baseline → +25% → +40% |
| Poll Participation Rate | Participants / Eligible targeted users per poll | 30% → 45% → 55% |
| First 7‑Day Activation | New user performs ≥3 distinct actions in first week | 40% → 55% → 70% |

(Keep instrumentation lean: log simple rows of (user_id, action, context, timestamp)).

## 3. Phased Rollout
### Phase 1: Pilot (2–3 weeks)
- Audience: One HQ department + one centre + 2 stations.
- Enable: Announcements, Documents, Polls, Vault (if already stable), Birthdays panel.
- Goals: Validate navigation clarity, collect pain points, baseline metrics.
- Output: Refined UX backlog + baseline adoption report.

### Phase 2: Structured Expansion (4–6 weeks)
- Roll out to remaining centres & majority of stations.
- Introduce light engagement nudges (inline prompts, subtle banners).
- Start weekly adoption snapshot (auto-generated table to Slack/email).

### Phase 3: Optimization & Sustain
- Add recognition (top contributors, stations with 100% read-rate).
- Targeted prompts for inactive users (“Haven’t checked announcements this week”).
- Tune content taxonomy & search (if needed) based on usage heatmap.

## 4. Engagement Nudges (Low Friction)
| Nudge | Trigger | Copy Example |
|-------|---------|--------------|
| Inline Empty State | User lands on feature w/ no items | “No documents yet — add the first to help your team.” |
| Soft Return Prompt | User inactive 7 days | “Quick catch-up: 4 new announcements since you last checked.” |
| Poll Participation Reminder | User hasn’t answered within 48h | “Your input is needed: Station Wellbeing Poll closes soon.” |
| Recognition Badge | User reads 10 announcements in week | “Consistency badge: you’re keeping up to date.” |
| New Feature Highlight | Feature newly enabled | “Password Vault now available — secure sensitive credentials.” |

Keep tone: helpful, not gamified overload.

## 5. Instrumentation Model (Light)
Create table `activity_events` (or reuse existing analytics store):
```
id (PK) | user_id | action (string) | context_type | context_id | meta (json) | occurred_at (datetime)
```
Examples: `announcement.read`, `document.view`, `poll.respond`, `login`, `vault.access`.
Cron (daily): Aggregate into a simple summary table `adoption_daily` (date, dau, wau, announcements_read, docs_viewed, polls_responses, active_new_users).

## 6. Governance & Feedback
- Weekly Ops Review (15 min): Look at WAU, read coverage, 3 biggest drops.
- Monthly Stakeholder Review: Phase goal check, unblock, content quality review.
- Feedback Channels: 1) Inline feedback link (email or form) 2) Quarterly survey.
- Ownership: Product (metrics), Comms (announcement quality), IT (performance), Centre Leads (local adoption), Security (vault usage audit).

## 7. Risks & Mitigations
| Risk | Impact | Mitigation |
|------|--------|------------|
| Low initial engagement | Plateau below targets | Pilot interviews + simplify navigation early |
| Content overload | Users ignore announcements | Introduce priority tags + weekly digest |
| Poor mobile experience | Field staff disengage | Responsive QA early in Phase 1 |
| Lack of trust (vault) | Feature underused | Security FAQ + audit transparency |
| Metrics drift / inaccuracy | Wrong decisions | Nightly validation job counts vs raw events |

## 8. Execution Checklist
- [ ] Create `activity_events` migration & model
- [ ] Emit events in: Announcement show, Document view, Poll submit, Login, Vault access
- [ ] Add daily aggregation command (Artisan) + schedule
- [ ] Build lightweight admin “Adoption” dashboard widget (WAU %, read coverage)
- [ ] Implement 2 core nudges (empty state + poll reminder)
- [ ] Configure weekly adoption email/export
- [ ] Pilot cohort selection + kickoff
- [ ] Baseline report after Week 1
- [ ] Phase 2 rollout approval gate
- [ ] Phase 3 optimization backlog grooming

## 9. 30 / 60 / 90 Snapshot Goals
- Day 30: Pilot complete, baseline metrics + first improvements deployed.
- Day 60: Expansion active; WAU ≥50%; read coverage trending upward.
- Day 90: Optimization underway; WAU ≥65%; poll participation ≥55%; activation ≥70%.

## 10. Minimal Event Emission Snippet (Example)
```php
// Example in a controller method after marking an announcement read
ActivityEvent::create([
  'user_id' => auth()->id(),
  'action' => 'announcement.read',
  'context_type' => 'announcement',
  'context_id' => $announcement->id,
  'meta' => ['priority' => $announcement->priority],
  'occurred_at' => now(),
]);
```

## 11. What NOT To Do (Yet)
- No heavy analytics warehouse.
- No complex scoring algorithms.
- No intrusive popups — keep nudges contextual.

---
**Next Step (if approved):** Implement migration + event model + daily aggregation command (can be done incrementally in < 1 day of focused work).

## 12. Recognition Seed Data
Badges are seeded via `BadgeSeeder` and included in `DatabaseSeeder`.

Run (local/dev) after deploying migrations:
```
php artisan db:seed --class=BadgeSeeder
```
Or full seed (will also run it):
```
php artisan db:seed
```
Adding a new badge: create a new entry in `database/seeders/BadgeSeeder.php` with a unique `code` and re-run the seeder (existing badges update safely).
