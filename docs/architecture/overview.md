# TemanSekolah Architecture Overview

This document describes the architecture of the TemanSekolah repository as it exists, not an idealized Laravel application. Statements are based on static inspection of the codebase and on `php artisan route:list --json`; the production database was not inspected. Anything that could not be confirmed statically is marked **Unverified**.

- Repository root: `C:\laragon\www\TemanSekolah`
- Verification date: 2026-11-27
- Branch inspected: `main`
- Primary references: `AGENTS.md`, `docs/known-issues.md`, `composer.json`, `package.json`, `routes/web.php`, `routes/api.php`, `config/`, `app/`, `database/migrations/`, `resources/views/`

Related documents:

- [Module Architecture](./modules.md)
- [Database Architecture](./database.md)
- [Known Issues](../known-issues.md)

---

## A. Project Overview

### Purpose and domain

TemanSekolah is a Laravel monolith for Indonesian school administration. The active business areas are:

- **Portal / Authentication** - single entry point and session handling for staff and parents.
- **PPDB / SPMB** - new-student admission, parent registration, document upload, verification, and admission reporting.
- **Akademik** - students, employees/teachers, classes, subjects, teaching schedules, extracurriculars, and academic assessment.
- **Keuangan** - student billing, savings (`tabungan`), EduPay balances, accounting journals, reports, and receipts.

Three further modules exist in code, models, and views but have no active routes: **SmartCanteen**, **KoperasiPintar**, and **SmartPass**. They are dormant and must not be assumed reachable. See [modules.md](./modules.md).

The application is single-school, not multi-tenant. The nearest thing to a tenant boundary is the education level (`ms_jenjang`); staff access to jenjang is scoped through the `ms_akses_jenjang` pivot.

### Technology stack

| Area | Technology | Evidence |
| --- | --- | --- |
| Framework | Laravel `v10.48.25` | `composer.json`, `composer.lock` |
| PHP | `^8.1` required; local CLI `8.3.30` | `composer.json` |
| Server-side UI | Livewire `v2.12.8` | `composer.json` |
| View layer | Blade | `resources/views` (556 files) |
| CSS framework | Bootstrap 5.3.2 | `public/assets/css/bootstrap.min.css` |
| Build tool | Vite `^5.0.0` + `laravel-vite-plugin ^1.0.0` | `package.json`, `vite.config.js` |
| Default database | MySQL | `config/database.php:18` |
| Timezone | `Asia/Jakarta` | `config/app.php:74` |
| Locale | `en` (`faker_locale` `en_US`) | `config/app.php:87`, `config/app.php:113` |
| API auth | Laravel Sanctum `^3.3` | `composer.json`, `config/sanctum.php` |
| PDF | `barryvdh/laravel-dompdf ^3.1`, `elibyy/tcpdf-laravel ^11.4` | `composer.json` |
| Spreadsheet | `maatwebsite/excel 3.1.63` | `composer.json` |
| HTML sanitizing | `ezyang/htmlpurifier 4.18.0` | `composer.json` |
| Frontend libs | jQuery 3.7.1, DataTables (Bootstrap 4 styling), Select2, Choices.js, SweetAlert2, Alertify, Dropzone 6 beta, FilePond, AOS, Swiper | `resources/views/template/v_head.blade.php` |

Important: Vite is configured (`vite.config.js`, `package.json`) but is **not used by any Blade view**. There are zero `@vite` directives. Assets are loaded with `asset('assets/...')`. `resources/css/app.css` is empty and `resources/js/app.js` only imports `./bootstrap`. Do not convert the asset pipeline without an explicit request.

### Architectural style

- **Monolithic server-rendered MVC** with Livewire components for interactive CRUD.
- **Mixed controller/Livewire split per module**, not one uniform architecture:
  - PPDB: controllers handle pages, PDF/ZIP output, and admin flows; Livewire handles forms and verification; services hold reusable logic.
  - Akademik: thin page/PDF controllers plus Livewire CRUD.
  - Keuangan: a mix of thin page controllers, heavy controllers, and Livewire CRUD.
- **No repository layer, no DTO layer, no domain events, no CQRS.** Business logic lives in controllers, Livewire components, and a small `app/Services` layer.
- **Route-level role authorization**, not policies or gates.
- **Partly unmigrated schema.** Migrations cover only part of the schema; the existing database is the source of truth. See [database.md](./database.md).

### Repository shape

| Path | Responsibility |
| --- | --- |
| `app/Http/Controllers` | Page endpoints, PDF/ZIP/export endpoints, some heavy business flows |
| `app/Http/Livewire` | Interactive CRUD and forms (382 files) |
| `app/Models` | Eloquent models (75 files) |
| `app/Services` | `AccountingService`, report services, PPDB services |
| `app/Http/Requests` | 146 FormRequest stubs (inactive; see constraints) |
| `app/Policies` | 7 policy stubs (inactive) |
| `routes/web.php` | All web routes (130 total with framework routes) |
| `routes/api.php` | Sanctum user endpoint and attendance webhook |
| `resources/views` | Blade views, module templates, Livewire views |
| `database/migrations` | 22 migrations covering about 20 tables |
| `database/seeders`, `database/factories` | Mostly empty scaffolding |

### Module status at a glance

| Module | Status | Access |
| --- | --- | --- |
| Portal / Authentication | Active | Public, then `auth` |
| PPDB / SPMB | Active | `ORANG_TUA` parent group + staff admin group |
| Akademik | Active | Staff admin group |
| Keuangan | Active | Staff admin group |
| Manajemen Kepegawaian | Partially active / legacy | Route commented out |
| SmartCanteen | Inactive / unrouted | None |
| KoperasiPintar | Inactive / unrouted | None |
| SmartPass | Inactive / unrouted | None |

---

## B. System Architecture

### Request lifecycle

A typical authenticated page request:

1. **HTTP request** enters through `public/index.php` and the Laravel HTTP kernel.
2. **Global middleware** runs (trusted proxies, CORS, etc. per `app/Http/Kernel.php`).
3. **Route matching** uses `routes/web.php` or `routes/api.php`.
4. **`web` middleware group** runs: cookie encryption, session start, CSRF verification, route binding substitution, and `ShareErrorsFromSession`.
5. **`auth` middleware** (`App\Http\Middleware\Authenticate`) requires a logged-in `App\Models\User`.
6. **`peran` middleware** (`App\Http\Middleware\MiddlewarePeran`) checks the user's `peran` against the route's allowed roles.
7. **Controller action or Livewire mount** executes.
8. **Service / model layer** performs queries and writes. Some flows use `DB::beginTransaction()` manually; others use `DB::transaction()`.
9. **Blade view** renders inside a module template (`template_akademik`, `template_keuangan`, etc.).
10. **Livewire components** render their own Blade views and later receive AJAX updates through Livewire's routes.

Livewire browser interactions use these framework routes (from `route:list`):

- `POST livewire/message/{name}` (`livewire.message`)
- `POST {locale}/livewire/message/{name}` (`livewire.message-localized`)
- `POST livewire/upload-file` (`livewire.upload-file`)
- `GET livewire/preview-file/{filename}` (`livewire.preview-file`)
- `GET livewire/livewire.js`, `GET livewire/livewire.js.map`

There are 4 routes in the `livewire` name group; the JS/asset routes are unnamed.

### Layer responsibilities

| Layer | What it does | What it does not do |
| --- | --- | --- |
| Routes (`routes/web.php`) | URL mapping, middleware groups, route names | Business logic |
| Middleware | Authentication and role checks | Fine-grained data scoping |
| Controllers | Page rendering, PDF/Excel/ZIP output, some heavy transaction flows | Consistent validation (many rely on inline `$request->validate`) |
| Livewire components | Forms, CRUD, modal state, pagination, browser events | Cross-module orchestration |
| Services | Accounting journals, report queries, PPDB entity persistence | Uniform domain model |
| Models | Table mapping, `$fillable`, casts, soft deletes, some relationships and query scopes | Authorization |
| Blade views | Layout, forms, tables, Livewire component embedding | Asset compilation through Vite |

### Data access and services

- **`App\Services\AccountingService`** uses static methods (`create`, `update`, `reverse`, `delete`, `generateNomorJurnal`) and wraps journal writes in database transactions. It is used by Keuangan Livewire components and by the dormant SmartCanteen settlement/transaction components.
- **`App\Services\Reports\*ReportService`** are instance-based, accept a `Request` (or entity IDs), and expose methods such as `getData`, `getTransactionData`, and `getSaldoData`. They are used by Akademik, Keuangan, and PPDB controllers.
- **`App\Services\PPDB\*`** are a mix: most entity services expose `public static function create/update`, while `PPDBBerkasArsipService`, `PPDBFormulirPendaftaranService`, and `PPDBKartuAljabarService` are instance-based.
- **Transactions are inconsistent.** `DB::beginTransaction()` appears in roughly 102 files; `DB::transaction(` appears in roughly 28 files (25 of them Livewire). Match the surrounding file; do not mass-convert.
- **No global query scoping for jenjang.** `ms_akses_jenjang` filtering is implemented ad hoc in Livewire components (for example `App\Http\Livewire\Keuangan\Parameter\Jenjang` and `App\Http\Livewire\Keuangan\AksesJenjang\Index`). It is not enforced by middleware or model global scopes.

### Cross-cutting concerns

- **PDF generation** is done in controllers and report services using Dompdf and/or TCPDF. Examples include PPDB card/report endpoints and Akademik extracurricular PDFs.
- **Spreadsheet export** uses `maatwebsite/excel` from controller endpoints.
- **File uploads** for PPDB are stored through `App\Services\PPDB\PPDBBerkasService`; file deletion helpers exist (`hapusFile`, `hapusFileLama`).
- **WhatsApp notifications** are modeled but not a first-class integration: `WhatsApp*` models exist in Keuangan, and there is no confirmed outbound provider client in the inspected code. **Unverified** whether any controller actually sends messages.
- **Attendance webhook** is `POST /api/absensi/webhook` to `Api\AbsensiWebhookController@receive`, unauthenticated in `routes/api.php`. It writes to `presensi_pegawai_log` via `App\Models\FingerSpot\PresensiPegawaiLog`. Treat the endpoint as security-sensitive; see `docs/known-issues.md`.

---

## C. Authentication and Authorization

### Authentication

- Guard: `web`, driver `session`.
- Provider: `users`, driver `eloquent`, model `App\Models\User` (`config/auth.php`).
- Table: `ms_pengguna`; primary key: `ms_pengguna_id`; role column: `peran` (string).
- `App\Models\User` extends `Authenticatable`, uses `Notifiable` and `HasApiTokens` (Sanctum), and declares `$casts` for `email_verified_at` and `password`.
- The migration for `ms_pengguna` is `database/migrations/2014_10_12_000000_create_users_table.php`. The model also lists `telepon` and `current_session` in `$fillable`; these columns are **not** in that migration and are assumed to exist in the live database. **Unverified** against the live schema.

### Login flows

There are two separate login surfaces:

1. **Staff / general login** - `App\Http\Controllers\LoginController`:
   - `index()` renders `login.v_index`.
   - `authenticate()` uses `Auth::attempt` with email/password, regenerates the session on success, and stores a single `current_session` value on `ms_pengguna`.
   - Redirects to `route('portal')`.
   - `logOut()` clears `current_session` and logs out.
   - The controller imports `App\Models\ModelPengguna`, which does not exist, but the import is unused.
2. **PPDB parent login** - Livewire `App\Http\Livewire\PPDB\Auth\Login` (alias `p-p-d-b.auth.login`). Do not create a second PPDB login flow. PPDB registration (`PPDB\Auth\Register`) creates `App\Models\User` rows with `peran = 'ORANG_TUA'`.

The `current_session` field implements a single-active-session policy at login time. Preserve this behavior when editing authentication.

### Authorization model

Authorization is enforced by route middleware, not by policies or gates.

- Alias: `peran` -> `App\Http\Middleware\MiddlewarePeran` (`app/Http/Kernel.php:68`).
- Behavior (`app/Http/Middleware/MiddlewarePeran.php:20-44`):
  - Returns **401** when the request is unauthenticated.
  - Uppercases and trims both the user's `peran` and each allowed role.
  - Returns **403** when the normalized role is not in the allowed list.
  - Calls the next middleware otherwise.

Active route groups:

| Group | Middleware | Scope |
| --- | --- | --- |
| Staff admin | `['auth', 'peran:SUPERADMIN,ADMIN,KEUANGAN,PETUGAS_PPDB']` | 60 Keuangan + 17 Akademik + 12 PPDB admin routes (`routes/web.php:312`) |
| PPDB parent | `['auth', 'peran:ORANG_TUA']` | 12 routes (`routes/web.php:165`) |
| Nested PPDB `akses-petugas` | `['peran:SUPERADMIN,ADMIN,PETUGAS_PPDB']` | PPDB staff-only sub-routes |

Note that `PETUGAS_PPDB` is allowed on the **entire** staff admin group, not just PPDB routes. This is the existing behavior; do not tighten it without an explicit request.

### Role values

Active role values used by route middleware: `SUPERADMIN`, `ADMIN`, `KEUANGAN`, `PETUGAS_PPDB`, `ORANG_TUA`.

Additional role strings appear in code and data but are **not** wired to active route groups: `GURU`, `PEGAWAI`, `KANTIN`, `TATA_USAHA`, `KEPALA_SEKOLAH`, `BK`, `KOMITE_SEKOLAH`. Do not assume they grant access.

### Data scoping

- PPDB staff access to jenjang is scoped through `ms_akses_jenjang` (pivot between `ms_pengguna` and `ms_jenjang`). It is used by PPDB admin and Keuangan components, not by a global middleware.
- Keuangan `Parameter\Jenjang` selects only jenjang linked to the logged-in user in `ms_akses_jenjang`, then emits `parameterUpdated` to other components.
- Because scoping is component-local, any new component that reads jenjang-dependent data must apply the same filter explicitly.

### Unused authorization scaffolding

The repository contains scaffolding that looks like authorization but is inert:

- **146 FormRequest classes** under `app/Http/Requests`; every `authorize()` returns `false` and every `rules()` body is empty. No active route invokes a FormRequest-validated action. Do not treat them as validation or authorization.
- **7 policy classes** under `app/Policies` with empty method bodies. `AuthServiceProvider::$policies` is empty. There are zero `Gate::`, `->authorize(`, `->can(`, or `@can` usages.
- **`SuperAdminMiddleware`** exists with a `superadmin` alias but is not referenced by active routes.
- Real validation is inline (`$request->validate([...])` in controllers and `rules()` + `$messages` in Livewire).

### API authentication

- `routes/api.php` defines `GET /api/user` behind `auth:sanctum`.
- `POST /api/absensi/webhook` has no auth middleware. This is a known security concern; see `docs/known-issues.md`.
- The `api` middleware group in `app/Http/Kernel.php` includes `throttle:api` and `SubstituteBindings`; the Sanctum stateful middleware line is commented out.

---

## D. Frontend Architecture

### Rendering model

- Server-rendered Blade with Livewire 2 components for interactivity.
- 556 Blade files under `resources/views`.
- Livewire components are embedded with `@livewire('alias', [...])`. There are zero `<livewire:...>` tags.
- Livewire view paths follow `resources/views/livewire/{module-kebab}/{entity-kebab}/{action}.blade.php`. PPDB views live under `resources/views/livewire/p-p-d-b` because the `PPDB` namespace segment kebab-cases to `p-p-d-b`.

### Layouts and templates

| Module | Template |
| --- | --- |
| Akademik | `@extends('template_akademik.v_template')` |
| Keuangan | `@extends('template_keuangan.v_template')` |
| PPDB | `template_ppdb.v_template`, `template_portal_ppdb.v_template` |
| Portal / login / PPDB landing | Standalone HTML documents; they do not `@extends` |

Shared partials live in `resources/views/template/v_head.blade.php` and `v_sidebar.blade.php`. The known-missing `layouts.app` and `template_machine.v_template` are referenced only by dormant/legacy views; do not reuse them (see `docs/known-issues.md`).

### Assets and styling

- Bootstrap 5.3.2 plus a custom admin theme (`app.min.css`, `custom.min.css`, `icons.min.css`, `feather-icon.css`).
- Third-party CSS/JS loaded from `public/assets/libs`: SweetAlert2, Alertify, Select2, Choices.js, DataTables (Bootstrap 4 styling), Dropzone, FilePond, AOS, Swiper.
- jQuery 3.7.1 is loaded from a CDN in `v_head.blade.php`.
- No Blade file uses `@vite`. `resources/css/app.css` is empty. `resources/js/app.js` imports only `./bootstrap`.
- `resources/js/letter-template-editor.js` is empty and unreferenced.

### Livewire 2 conventions

- Use `$this->emit('event')`; do **not** introduce `$this->dispatch(...)`. The codebase has 206 files using `emit` and 0 using `dispatch`.
- Browser events use `$this->dispatchBrowserEvent('alertify-success')`, `'alertify-error'`, `'hide-modal'`, or specific modal IDs. 240 files use `dispatchBrowserEvent`.
- Form state is a public `$form` array (or individual public properties in older components). Validation uses `rules()` plus `$messages`; `updated()` often calls `validateOnly`.
- Listeners use `protected $listeners = ['refreshX' => '$refresh', ...]`; child-to-parent refresh uses `$this->emit('refreshX')`.
- Pagination uses `WithPagination` and `protected $paginationTheme = 'bootstrap'`.
- Component aliases are derived from the class name: `App\Http\Livewire\PPDB\Auth\Login` -> `p-p-d-b.auth.login`; `App\Http\Livewire\Keuangan\LaporanEduPayPegawai\Index` -> `keuangan.laporan-edu-pay-pegawai.index`.

### UI composition

- Module templates provide `@livewireStyles` and `@livewireScripts`.
- Many pages embed multiple sibling Livewire components (for example, a filter/parameter bar, a table, and modals) that coordinate through emitted events rather than shared state.
- Some Akademik dashboard widgets reuse Keuangan components (`keuangan.widget.*`, `keuangan.parameter.*`). This is an intentional cross-module dependency but couples the two modules at the view layer.

---

## E. Architectural Constraints

These constraints follow from the current code and must be respected when adding or changing features:

1. **Role authorization is route middleware only.** Use `peran:...`; do not rely on FormRequests or policies.
2. **`App\Models\User` is `ms_pengguna` with PK `ms_pengguna_id`.** There is no `users` table in active code.
3. **Module architectures differ.** Mirror the nearest existing controller/Livewire component in the same module; do not force one pattern across PPDB, Akademik, and Keuangan.
4. **Livewire 2 API only.** Use `emit` and `dispatchBrowserEvent`; do not introduce Livewire 3 APIs.
5. **Assets use `asset()`, not Vite.** Do not convert to `@vite` unless asked.
6. **Migrations do not reproduce the schema.** The live database is authoritative; never run destructive migration commands or seeders without explicit approval.
7. **Dormant modules must stay dormant.** SmartCanteen, KoperasiPintar, and SmartPass have no routes; do not wire them into navigation or routes unless the task explicitly targets them.
8. **Jenjang scoping is manual.** Any new jenjang-dependent screen must filter through `ms_akses_jenjang` for the logged-in user.
9. **`Guru` is not a model.** Guru data is `App\Models\Pegawai` with `jenis_pegawai = 'GURU'`. Do not create `App\Models\Guru`.
10. **Transactions are inconsistent.** Match the surrounding file (`DB::beginTransaction()` vs `DB::transaction()`); do not mass-convert.
11. **Known-broken routes and aliases exist.** Check `docs/known-issues.md` before reusing Akademik/Keuangan page routes or stale Livewire aliases.
12. **No test suite.** There is no `tests/` directory, and CI references a missing `.env.example`. Verify changes by inspection and, when safe, by `php artisan route:list` or `vendor/bin/pint --test`.

---

## Related Documentation

- [Module Architecture](./modules.md) - per-module responsibilities, routes, controllers, Livewire components, models, and dependencies.
- [Database Architecture](./database.md) - migration-defined schema, model-declared schema, relationships, and verification boundaries.
- [Known Issues](../known-issues.md) - audit findings and known bugs.
