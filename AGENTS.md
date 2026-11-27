# AGENTS.md

Engineering guide for AI coding agents working in the TemanSekolah repository. It describes the repository as it exists, not an idealized Laravel project. Inspect the nearest implementation before changing code.

## 1. Project Overview

- Laravel 10 monolith: `laravel/framework` `v10.48.25`, PHP requirement `^8.1` (local CLI `8.3.30`), Livewire 2 (`livewire/livewire` `v2.12.8`).
- Domain: Indonesian school administration. Active areas are Portal/Login, PPDB, Akademik, and Keuangan.
- SmartCanteen, KoperasiPintar, and SmartPass exist in code, models, and views, but their routes are disabled. See section 3.
- Frontend is Blade + Livewire 2 with Bootstrap-style admin templates. Vite is configured but is not referenced by Blade.
- Key packages: `maatwebsite/excel` `3.1.63`, `barryvdh/laravel-dompdf` `v3.1.2`, `elibyy/tcpdf-laravel` `11.4.0`, `laravel/sanctum` `v3.3.3`, `ezyang/htmlpurifier` `v4.18.0`.
- Default database connection is MySQL (`config/database.php:18`). Timezone is `Asia/Jakarta` (`config/app.php:74`). Locale is `en` (`config/app.php:87`).
- Repository audit findings and known bugs are documented separately in `docs/known-issues.md`.

## 2. Working Commands

Run commands from the repository root. These commands are available in this repository:

- `composer install` - install PHP dependencies.
- `npm install` - install frontend dependencies.
- `php artisan serve` - start the Laravel development server.
- `php artisan route:list` - list routes; verified to work without a database connection.
- `npm run dev` - start the Vite development server (script: `vite`).
- `npm run build` - build frontend assets (script: `vite build`).
- `vendor/bin/pint` - format PHP with Pint `v1.18.3`; use `vendor/bin/pint --test` to check without fixing.
- `php artisan migrate` - run migrations only when the task explicitly requires it and the user approves; see section 10.
- `vendor/bin/phpunit` - the binary exists, but there is no `tests/` directory.
- `php artisan test` - the command exists, but no test suite is present.
- `php artisan db:seed` - available, but 10 of 11 seeders have empty `run()` methods; do not run blindly.

Do not invent commands that are not defined here. In particular, there is no `composer test` script, no `npm run lint`, and no `npm run typecheck`. Do not run `migrate:fresh`, `migrate:refresh`, `migrate:reset`, or destructive database commands without explicit approval. Do not commit changes unless explicitly asked.

## 3. Active Modules and Routes

- `php artisan route:list --json` currently reports 130 routes: `keuangan` 60, `ppdb` 36, `akademik` 17, `livewire` 4, unnamed 4, `ignition` 3, `login` 2, `sanctum` 1, `logout` 1, `landing` 1, `portal` 1.
- Active UI areas:
  - Portal/Login: `app/Http/Controllers/Portal/PortalController.php`, `app/Http/Controllers/LoginController.php`.
  - PPDB: `app/Http/Controllers/PPDB`, `app/Http/Livewire/PPDB`, `app/Services/PPDB`.
  - Akademik: `app/Http/Controllers/Akademik`, root-level academic controllers (for example `GuruController`, `JadwalMengajarController`), `app/Http/Livewire/Akademik`.
  - Keuangan: `app/Http/Controllers/Keuangan`, `app/Http/Livewire/Keuangan`, `app/Services/Reports`.
- Main admin route group: `routes/web.php:312`, middleware `['auth', 'peran:SUPERADMIN,ADMIN,KEUANGAN,PETUGAS_PPDB']`, 88 routes.
- PPDB parent route group: `routes/web.php:165`, middleware `['auth', 'peran:ORANG_TUA']`, 12 routes.
- `routes/web.php` has 888 lines. Route names use module prefixes such as `keuangan.*`, `ppdb.*`, `akademik.*`, `login.*`, `portal`, and `logout`.
- API routes are limited to `routes/api.php`: `POST /api/absensi/webhook` and `GET /api/user` (`auth:sanctum`).
- Inactive code: SmartCanteen has 40 Livewire files and 8 models, KoperasiPintar has 17 and 9, SmartPass has 8 and 4. Their route block is commented out at `routes/web.php:266-303`.
- `ManajemenKepegawaian` is also commented out at `routes/web.php:311`.
- Do not assume an inactive module is reachable. Do not wire it into navigation or add routes unless the task explicitly asks for it.

## 4. Routing, Auth, Roles

- Auth guard is `web`; the provider maps to `App\Models\User` (`config/auth.php`).
- `App\Models\User` uses table `ms_pengguna` and primary key `ms_pengguna_id`; the role column is `peran` (string). Do not assume a `users` table.
- Active route middleware uses only these role values: `SUPERADMIN`, `ADMIN`, `KEUANGAN`, `PETUGAS_PPDB`, `ORANG_TUA`.
- Additional role strings appear in application code and data: `GURU`, `PEGAWAI`, `KANTIN`, `TATA_USAHA`, `KEPALA_SEKOLAH`, `BK`, `KOMITE_SEKOLAH`. Do not assume they are wired to active routes.
- Use `MiddlewarePeran` through the `peran` alias (`app/Http/Kernel.php:68`). It returns 401 for unauthenticated requests, uppercases and trims both sides, and returns 403 on mismatch (`app/Http/Middleware/MiddlewarePeran.php:20-44`).
- Follow the existing route group for a module: `['auth', 'peran:...']`.
- `SuperAdminMiddleware` (`superadmin` alias) exists but is not referenced by active routes. `MiddlewarePeran` is the active authorization pattern.
- PPDB login is handled by Livewire `p-p-d-b.auth.login` (`app/Http/Livewire/PPDB/Auth/Login.php`). Do not create a second login flow.
- `LoginController` stores a single `current_session` value on `ms_pengguna` and regenerates the session on successful login (`app/Http/Controllers/LoginController.php`). Preserve this behavior when editing login or logout.

## 5. Module Architecture

- Akademik: controllers are usually thin page or PDF endpoints, while CRUD lives in Livewire. Examples: `App\Http\Controllers\Akademik\AkademikDashboard`, `App\Http\Controllers\GuruController`, and `App\Http\Livewire\Akademik\Guru\{Index,Create,Edit,Delete}`. PDF work remains in controllers such as `App\Http\Controllers\Akademik\EkstrakurikulerSiswa`.
- Keuangan: mixed by design. Some controllers are thin page endpoints, some are heavy (`App\Http\Controllers\Keuangan\TransaksiTabunganSiswa` is 369 lines; `TransaksiEduPaySiswa` is 253), and CRUD is partly in Livewire. `App\Services\AccountingService` uses static methods; report services use instance methods and accept `Request` (for example `App\Services\Reports\LaporanTagihanSiswaReportService`).
- PPDB: controllers handle pages, PDF/ZIP output, and admin flows; Livewire handles forms and verification; PPDB services use static methods (`app/Services/PPDB`). `App\Http\Controllers\PPDB\PPDBOrangTuaController` is 548 lines.
- SmartCanteen, KoperasiPintar, and SmartPass have full code, models, and views but no active routes. Treat them as dormant unless the task explicitly targets them.
- Shared Livewire areas: `app/Http/Livewire/Pegawai`, `Jabatan`, `FormulirEkstrakurikuler`, and `Landing`. Some shared components have missing view paths; check `docs/known-issues.md` before reusing them.
- When adding a screen, mirror the closest existing controller or Livewire component in the same module. Do not force one architecture across modules.

## 6. Livewire 2 Conventions

- This is Livewire 2.12.8. Use `$this->emit('event')`; do not introduce `$this->dispatch(...)`. There are 206 files using `emit` and 0 using `dispatch`.
- Browser events use `$this->dispatchBrowserEvent('alertify-success')`, `'alertify-error'`, or `'hide-modal'`; 240 files use `dispatchBrowserEvent`.
- Component classes use `App\Http\Livewire\{Module}\{Entity}\{Action}`. Aliases are derived from the class name: `App\Http\Livewire\PPDB\Auth\Login` becomes `p-p-d-b.auth.login`; `App\Http\Livewire\Keuangan\LaporanEduPayPegawai\Index` becomes `keuangan.laporan-edu-pay-pegawai.index`.
- Livewire views follow `resources/views/livewire/{module-kebab}/{entity-kebab}/{action}.blade.php`. PPDB views live under `resources/views/livewire/p-p-d-b`.
- Include components with `@livewire('alias', [...])`. Do not use `<livewire:...>` tags; there are 0 usages.
- Form state is a public `$form` array. Validation uses `rules()` plus `$messages`; methods such as `showCreateX()`, `loadDataX()`, and `showEditX()` are common.
- Listeners use `protected $listeners = ['refreshX' => '$refresh', ...]`. Child-to-parent refresh uses `$this->emit('refreshX')`.
- Pagination uses `WithPagination` and `protected $paginationTheme = 'bootstrap'`.
- Transactions are inconsistent: `DB::beginTransaction()` appears in 102 files; `DB::transaction(` appears in 28 files (25 Livewire). Match the surrounding file; do not mass-convert.
- Module templates provide `@livewireStyles` and `@livewireScripts`.
- SmartCanteen and SmartPass have broken view-path conventions; do not copy them. See `docs/known-issues.md`.

## 7. Models, Tables, Primary Keys, Relations

- There are 75 model files: 52 in `app/Models` root, plus `CORE` (1), `FingerSpot` (1), `KoperasiPintar` (9), `SmartCanteen` (8), and `SmartPass` (4).
- Declare `$table` and `$primaryKey` explicitly on models. 74 models declare `$table`; 73 declare `$primaryKey`.
- Primary keys are prefixed, for example `ms_siswa_id` and `ms_pengguna_id`. Do not assume `id`.
- Table naming is not uniformly Laravel plural snake_case. Prefixes in use include `ms_`, `dt_`, `ej_`, `ppdb_`, `akuntansi_`, `transaksi_`, and unprefixed names. Examples: `App\Models\Siswa` uses `ms_siswa`; `App\Models\User` uses `ms_pengguna`.
- Read the model's `$table` instead of guessing. Some class names differ from table names, for example `App\Models\SmartCanteen\TransaksiSmartCanteen` uses `ms_transaksi_kantin`.
- Mass assignment uses `$fillable` (74 models); `$guarded` is not used. Preserve local `$fillable` lists.
- `SoftDeletes` is used by 39 models. `$casts` is present on 15 models. No model sets `$timestamps = false`.
- Relations use snake_case names, often with table prefixes: `ms_penempatan_siswa`, `ms_tagihan_siswa`, `ms_jabatan`, `ms_jenjang`. Reuse those names; do not rename relations to camelCase.
- `App\Models\Siswa::setNamaSiswaAttribute()` uppercases `nama_siswa`; account for this when writing names.
- Guru is not `App\Models\Guru`. Guru data is `App\Models\Pegawai` with `jenis_pegawai = 'GURU'`, as used by `App\Http\Livewire\Akademik\Guru\Index`. Do not create a `Guru` model unless explicitly requested.
- `app/Models/CORE/Departemen.php` uses namespace `App\Models\CORE`, not `App\Models`.

## 8. Validation and Authorization Reality

- There are 146 FormRequest classes under `app/Http/Requests`; every one has `authorize()` returning `false` and an empty `rules()` body.
- The only FormRequest imports outside `app/Http/Requests` are in 6 scaffold controllers. Two of those controllers are routed only through `index` and do not use the imports; the other four are unrouted. No active route invokes a FormRequest-validated action.
- Do not treat an existing FormRequest as validating or authorizing anything. Do not assume `authorize()` will allow a request.
- Real validation is inline: `$request->validate([...])` in controllers and `rules()` plus `$messages` in Livewire. There are 8 `->validate(` call sites in 7 controllers and validation methods in 87 Livewire files.
- Real authorization is the `peran` route middleware. There are 7 policy classes, but all are stubs with no `return`; `AuthServiceProvider::$policies` is empty, and there are 0 `Gate::`, `->authorize(`, `->can(`, or `@can` usages.
- If a task needs validation or authorization, follow the nearest working inline pattern and use `peran:...` for role checks. Do not introduce a conflicting project-wide convention.

## 9. Views, Layouts, Assets

- There are 556 Blade files under `resources/views`.
- Module layouts:
  - Akademik: `@extends('template_akademik.v_template')`.
  - Keuangan: `@extends('template_keuangan.v_template')`.
  - PPDB: `template_ppdb.v_template` and `template_portal_ppdb.v_template`.
  - Portal, login, and PPDB landing pages are standalone HTML documents; they do not `@extends`.
- Use `@section('content')` with the module template. Do not introduce `layouts.app`; that view does not exist in this repository.
- Use `@livewire('alias')`, not `<livewire:...>`.
- Assets are loaded with `asset('assets/...')` in 33 Blade files. Vite is configured in `vite.config.js` and `package.json`, but no Blade file uses `@vite`. Do not convert existing assets to Vite unless asked.
- `resources/css/app.css` is empty. `resources/js/app.js` only imports `./bootstrap`.
- `resources/js/letter-template-editor.js` is empty and is not referenced by application code.
- PPDB Livewire views live under `resources/views/livewire/p-p-d-b` because the `PPDB` class segment kebab-cases to `p-p-d-b`.
- Known missing layouts `layouts.app` and `template_machine.v_template` are referenced by dormant or legacy views; do not reuse those references. See `docs/known-issues.md`.

## 10. Migrations and Database

- There are 22 migration files creating 20 tables: `ms_pengguna`, Sanctum/framework tables, PPDB tables, `ms_periode_akademik`, `ms_mata_pelajaran`, `ms_penugasan_guru`, `ej_jadwal_mengajar`, and `gurus`.
- There are no migrations for core tables such as `ms_siswa`, `ms_pegawai`, `ms_kelas`, `ms_jenjang`, or the finance, canteen, cooperative, and smart-pass tables. The existing database is the source of truth; `php artisan migrate` on an empty database does not reproduce the application schema.
- `gurus` is orphaned: it is created by migration and referenced by `GuruFactory` and `GuruPolicy`, but there is no `App\Models\Guru`. Do not use `gurus` as a model target.
- All 7 factories have empty `definition()` bodies. 10 of 11 seeders have empty `run()` methods. `SuperAdminSeeder` contains hardcoded account data; see `docs/known-issues.md` and do not copy credentials.
- Do not run `migrate:fresh`, `migrate:refresh`, `migrate:reset`, or seeders unless the task explicitly requires it and the user approves. Do not edit existing migrations to "fix" the schema without confirming the database state.
- Default connection is MySQL (`config/database.php:18`).

## 11. Known Issues

- Bug, security, stale-code, and repository-structure findings are documented separately in `docs/known-issues.md`.
- Read that file before modifying routes, layouts, Livewire view paths, authentication, webhooks, seeders, or migration-dependent code.
- Do not fix unrelated known issues as part of another task. Do not change security-sensitive code without explicit instruction.
- Do not repeat credentials, tokens, or other sensitive values from the audit in code, commits, or responses.

## 12. Do-Not-Assume Checklist

Before coding, do not assume:

- All modules share one architecture. Akademik, Keuangan, and PPDB differ.
- FormRequests validate or authorize requests. They are unused stubs in active flows.
- Policies or Gates enforce authorization. Authorization is `peran` middleware.
- SmartCanteen, KoperasiPintar, or SmartPass are active. They have no routes.
- Vite is the active asset pipeline. Blade uses `asset()`.
- Migrations recreate the database. They cover only part of the schema.
- A test suite exists or CI is green. `tests/` is absent and the workflow references a missing `.env.example`.
- `$this->dispatch()` is the current Livewire API. Use `$this->emit()`.
- Table names follow Laravel plural snake_case. Read `$table`.
- `App\Models\Guru` exists. Guru data is `Pegawai` with `jenis_pegawai = 'GURU'`.
- All role values in the database are uppercase. `MiddlewarePeran` normalizes input; `SuperAdminMiddleware` is exact-case and unused.
- `DB::transaction()` is used everywhere. `DB::beginTransaction()` is more common.
- `layouts.app` exists. Use the active module template.
- An inactive module can be enabled by adding a route. Check `docs/known-issues.md` first.
- A nested `AGENTS.md` is appropriate. Keep this root file as the single agent guide.
