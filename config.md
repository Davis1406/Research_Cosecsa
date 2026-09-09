# Project Notes

This repo started life as a LaravelDaily/QuickAdminPanel "conference event" demo
(see [README.md](README.md), which is now stale — ignore its framing). It has
since been transformed into a **surgical/medical training platform**
(project directory name: `Research_Cosecsa`, suggesting COSECSA) with courses,
timetables, quizzes, certificates, and role-based portals. There is no other
documentation in the repo, so this file exists to save re-deriving the
architecture each session.

## Stack

- **Laravel 9.52** (`composer.json` — despite the README title still saying
  "Laravel 8"), PHP ^8.0
- Auth: session-based `web` guard (`App\User` model) for the browser portals,
  plus `laravel/passport` for the `api` guard
- `spatie/laravel-medialibrary` for file/media uploads (training materials,
  trainee documents, speaker/venue/hotel/gallery/sponsor images)
- `yajra/laravel-datatables-oracle` for admin listing tables
- Models live directly under `app/` (pre-Laravel-8-restructure convention),
  **not** `app/Models/`

## Roles & portals

Role/permission model is custom (`App\Role`, `App\Permission`), not
Spatie permissions. Role titles checked in code: `Super Admin`, `Admin`,
`Lead Facilitator`, `Facilitator`, `Trainee`, `Viewer`.

Route-level access is enforced by the custom `role:` middleware
(`App\Http\Middleware\CheckRole`, aliased in
[app/Http/Kernel.php](app/Http/Kernel.php)), using lowercase/hyphenated slugs
(`admin`, `super-admin`, `facilitator`, `lead-facilitator`, `trainee`, `viewer`).

Four portals, each namespaced under `app/Http/Controllers/<Portal>` and
prefixed/route-grouped in [routes/web.php](routes/web.php):

| Portal | Prefix | Roles allowed | Notes |
|---|---|---|---|
| Admin | `/admin` | admin, super-admin, facilitator, lead-facilitator, trainee | Full CRUD over almost every entity; broadest role list is deliberate (dashboards embed cross-role widgets) |
| Facilitator | `/facilitator` | facilitator, lead-facilitator | Most management actions (timetable, trainees, facilitators, certificates) are further gated to `lead-facilitator` only via a second `role:` middleware on the individual route |
| Trainee | `/trainee` | trainee | Own timetable, materials, documents, profile |
| Viewer | `/viewer` | viewer | Read-only dashboard: facilitators, trainees, timetable, materials |

`GET /home` (name `dashboard`) is the smart post-login redirect: it inspects
`auth()->user()->roles` and sends the user to the right portal root.

There's also a legacy `Api/V1/Admin/*` REST layer
([app/Http/Controllers/Api/V1/Admin/](app/Http/Controllers/Api/V1/Admin/))
mirroring several Admin controllers, routed via [routes/api.php](routes/api.php).

## Key domain models (`app/*.php`)

- **People/org**: `User`, `Role`, `Permission`, `Trainee`, `LoginLog`
- **Scheduling**: `Schedule` (timetable sessions — admin `ScheduleController`
  and facilitator `ScheduleManagerController` both write to it)
- **Learning content**: `TrainingMaterial`, `Quiz` → `QuizQuestion` →
  `QuizOption`, `QuizAttempt` → `QuizAnswer`, `Certificate`
- **Trainee submissions**: `TraineeDocument` (presentations) →
  `TraineeDocumentComment` (facilitator/admin review comments)
- **Communication**: `Message` (1:1 threaded chat, admin/facilitator both have
  inbox UIs), `Discussion` → `DiscussionReply`
- **Logistics (conference-template leftovers, still in active use for
  venues/hotels)**: `Venue`, `Hotel`, plus unused-by-new-flows `Speaker`,
  `Sponsor`, `Gallery`, `Amenity`, `Price`, `Faq`, `Setting`

## Notable middleware (`app/Http/Middleware/`)

Applied in the `web` group ([app/Http/Kernel.php](app/Http/Kernel.php)), in order:
`AuthGates` → `SetLocale` → `SecurityHeaders` → `ForcePasswordChange` →
`RecordLoginActivity`. `ForcePasswordChange` intercepts any authenticated
request and redirects to `/change-password` if the account requires it,
bypassing role checks (see [routes/web.php](routes/web.php) line 10).
`TrustProxies` is configured to trust all proxies (Docker/nginx-proxy SSL
termination — see recent commit history).

## Recent work (from git log, for context)

- Physical/Online course type switcher added across admin, facilitator, and
  trainee portals
- Docker/nginx-proxy deployment fixes (redirect loop, trusted proxies)
- Word document (.doc/.docx) preview via Office Online for training materials
  / trainee documents
- Smart day expansion on the admin timetable UI

## Gaps / things to verify before relying on them

- No `CLAUDE.md` exists yet — this file is a stand-in; consider promoting it
  or letting `/init` generate one alongside it.
- No architecture docs beyond this file and the stale `README.md`.
- Role/permission slugs are inferred from `routes/web.php` string literals,
  not from a central enum/const — grep `CheckRole` and the `roles` table
  seeder before adding a new role.
