# Known Issues and Audit Findings

Last inspected: 2026-11-27

Scope: read-only audit of the TemanSekolah repository. No source code, migrations, database, configuration, dependencies, or Git history were changed. This document records observed findings only; it is not a remediation plan.

Method: `php artisan route:list --json`, `composer show`, `npm run`, file inspection, filesystem checks, and `git log`. No database queries, migrations, seeders, or HTTP requests were executed.

## How to use this document

- Read the relevant finding before changing routes, layouts, Livewire view paths, authentication, webhooks, seeders, or migration-dependent code.
- Do not fix unrelated findings as part of another task.
- Do not copy credentials, tokens, or other sensitive values into code, commits, documentation, or responses.
- Findings marked "Observed" were established from repository contents. Findings marked "Inferred" depend on runtime behavior that was not executed.

## Findings summary

| ID | Severity | Area | Summary | Status |
| --- | --- | --- | --- | --- |
| KI-01 | High | Akademik route | `akademik.laporan` targets a controller with no `index` method; sidebar link is active | Open |
| KI-02 | High | Keuangan route | `keuangan.sistem.jenjang-tahun-ajar` extends a missing layout and uses missing Livewire aliases | Open |
| KI-03 | Medium | Keuangan route | `keuangan.akuntansi.jurnal-detail` extends a missing layout and uses a missing Livewire alias | Open |
| KI-04 | Low | PPDB route | `POST ppdb/{jenjang}/login` targets a missing `authenticate` method; Livewire login is the active flow | Open |
| KI-05 | Medium | Views/layouts | `layouts.app` and `template_machine.*` are missing; several legacy views cannot render | Open |
| KI-06 | Medium | SmartCanteen/SmartPass | Livewire classes render view paths that do not exist; modules are unrouted | Open |
| KI-07 | High (security) | API webhook | Attendance webhook has no active authentication check and writes payload data directly | Open |
| KI-08 | High (security) | Seeder | Tracked seeder contains hardcoded SUPERADMIN account data | Open |
| KI-09 | Medium | Validation | All 146 FormRequest classes are non-functional stubs; active flows validate inline | Open |
| KI-10 | Medium | Authorization | All 7 policies are stubs; no Gate/authorize/can usage | Open |
| KI-11 | Medium | Database fixtures | All 7 factories and 10 of 11 seeders are empty | Open |
| KI-12 | Medium | Tests/CI | `tests/` is absent; stale PHPUnit cache remains; CI workflow cannot run as written | Open |
| KI-13 | High | Migrations | Migrations cover only part of the schema; `gurus` is orphaned | Open |
| KI-14 | Low | Dead assets | Letter rendering/sanitizer classes and editor asset are empty and unreferenced | Open |
| KI-15 | Low | Legacy scaffolds | Several controllers/views are unreferenced or point to missing views | Open |

## KI-01: `akademik.laporan` route is broken

Observed:

- `routes/web.php:438` registers `GET /akademik/laporan` as `[LaporanController::class, 'index']` with name `akademik.laporan`.
- `app/Http/Controllers/Akademik/LaporanController.php` contains only `class LaporanController extends Controller { // }`; there is no `index` method.
- The link is present in the active Akademik sidebar: `resources/views/template_akademik/v_sidebar.blade.php:324`.
- Route middleware: `web`, `App\Http\Middleware\Authenticate`, `App\Http\Middleware\MiddlewarePeran:SUPERADMIN,ADMIN,KEUANGAN,PETUGAS_PPDB`.

Impact (Inferred):

- Requesting `akademik/laporan` or clicking "Laporan Akademik" will fail with a controller-method error because the action does not exist.

Status: not fixed.

## KI-02: `keuangan.sistem.jenjang-tahun-ajar` route is broken

Observed:

- `routes/web.php:469-470` registers `GET /keuangan/sistem/jenjang-tahun-ajar` as `[JenjangTahunAjar::class, 'index']` with name `keuangan.sistem.jenjang-tahun-ajar`.
- `app/Http/Controllers/Keuangan/JenjangTahunAjar.php:12` returns `view('keuangan.sistem.jenjang-tahun-ajar')`.
- `resources/views/keuangan/sistem/jenjang-tahun-ajar.blade.php:1` extends `template_machine.v_template`.
- `resources/views/template_machine` does not exist.
- The same view includes `@livewire('jenjang.create')`, `jenjang.index`, `jenjang.edit`, `jenjang.delete`, `tahun-ajar.create`, `tahun-ajar.index`, `tahun-ajar.edit`, and `tahun-ajar.delete` (lines 25-35). These aliases do not exist. The actual classes are `App\Http\Livewire\Akademik\Jenjang\*` and `App\Http\Livewire\Akademik\TahunAjaran\*`, whose aliases are `akademik.jenjang.*` and `akademik.tahun-ajaran.*`.
- The link is present in the active Keuangan sidebar: `resources/views/template_keuangan/v_sidebar.blade.php:357-358`.

Impact (Inferred):

- Requesting the route or clicking the sidebar link will fail because the layout and Livewire components cannot be resolved.

Status: not fixed.

## KI-03: `keuangan.akuntansi.jurnal-detail` route is broken

Observed:

- `routes/web.php:790-791` registers `GET /keuangan/akuntansi/jurnal-detail` as `[AkuntansiJurnalDetail::class, 'index']` with name `keuangan.akuntansi.jurnal-detail`.
- `app/Http/Controllers/Keuangan/AkuntansiJurnalDetail.php:12` returns `view('keuangan.akuntansi.jurnal-detail')`.
- `resources/views/keuangan/akuntansi/jurnal-detail.blade.php:1` extends `template_machine.v_template`, which does not exist.
- The view also uses `@livewire('parameter.jenjang-tahun-ajar')` at line 12. That alias does not exist; the active alias is `keuangan.parameter.jenjang-tahun-ajar`.
- No Blade file references `route('keuangan.akuntansi.jurnal-detail')`.

Impact (Inferred):

- Direct access to the route will fail. It is not linked from the active sidebar.

Status: not fixed.

## KI-04: PPDB `authenticate` route target is missing

Observed:

- `routes/web.php:152` registers `POST /ppdb/{jenjang}/login` as `[AuthLoginController::class, 'authenticate']` with name `ppdb.authenticate` (`use` alias at `routes/web.php:78`).
- `app/Http/Controllers/PPDB/Auth/LoginController.php` defines only `index(string $jenjang)` and `logOut(Request $request)`; there is no `authenticate` method.
- No Blade file references `ppdb.authenticate`.
- The active PPDB login page includes Livewire `p-p-d-b.auth.login` (`resources/views/ppdb/auth/login.blade.php`). The Livewire component calls `Auth::attempt(...)` in `app/Http/Livewire/PPDB/Auth/Login.php`.

Impact (Inferred):

- Posting directly to the legacy route will fail. The normal PPDB login flow uses Livewire and is not affected by this missing method.

Status: not fixed.

## KI-05: Missing layouts and legacy views

Observed:

- `resources/views/layouts` does not exist. `resources/views/livewire/siswa-ekstrakurikuler/detail.blade.php:1` extends `layouts.app`.
- `resources/views/template_machine` does not exist. References to `template_machine.v_template`, `template_machine.v_head`, `template_machine.v_footer`, and `template_machine.v_script` exist in:
  - `resources/views/v_home.blade.php`
  - `resources/views/keuangan/sistem/jenjang-tahun-ajar.blade.php`
  - `resources/views/keuangan/akuntansi/jurnal-detail.blade.php`
  - `resources/views/keuangan/administrasi/manajemen-kepegawaian.blade.php`
  - `resources/views/SMARTPASS/ADMINNISTRASI/kelas-siswa/v_index.blade.php`
  - the inactive `template_machine_*` templates.
- `v_home.blade.php` and `livewire/siswa-ekstrakurikuler/detail.blade.php` are not referenced by application code.
- `keuangan/administrasi/manajemen-kepegawaian.blade.php` corresponds to `ManajemenKepegawaian`, whose route is commented out at `routes/web.php:311`.
- `SMARTPASS/...` views belong to the unrouted SmartPass module.

Impact (Inferred):

- Two active routes are affected (KI-02 and KI-03). The other references are dormant or legacy.

Status: not fixed.

## KI-06: SmartCanteen and SmartPass view paths do not match

Observed:

- SmartCanteen components render `livewire.smart-canteen.*`; example: `app/Http/Livewire/SmartCanteen/AksesKantin/Create.php:111`.
- SmartPass components render `livewire.smart-pass.*`; example: `app/Http/Livewire/SmartPass/FingerSpotPresensiPegawai/Index.php:23`.
- `resources/views/livewire/smart-canteen` and `resources/views/livewire/smart-pass` do not exist.
- Views for those components exist under `resources/views/livewire/keuangan/smart-canteen/*` and `resources/views/livewire/keuangan/smart-pass/*`.
- No PHP or Blade file references `livewire.keuangan.smart-canteen.*` or `livewire.keuangan.smart-pass.*`.
- SmartCanteen, KoperasiPintar, and SmartPass routes are commented out at `routes/web.php:266-303`.
- KoperasiPintar renders `livewire.koperasi-pintar.*`, and that view directory exists.

Impact (Inferred):

- Re-enabling SmartCanteen or SmartPass routes without correcting the view paths will fail at render time. The modules are currently unreachable.

Status: not fixed.

## KI-07: Attendance webhook is unauthenticated

Observed:

- `routes/api.php` exposes `POST /api/absensi/webhook` as `AbsensiWebhookController@receive`.
- `app/Http/Controllers/Api/AbsensiWebhookController.php:14-21` contains a commented-out header-token check.
- The method writes `App\Models\FingerSpot\PresensiPegawaiLog` from request data (`:25-33`) and returns `echo "OK"` (`:39`).
- The payload accesses `$payload['data']['pin']` and `$payload['data']['scan']` directly; missing keys can raise undefined-index errors.

Impact (Inferred):

- Any caller that can reach the endpoint can create attendance log records. This is a security-sensitive path. Do not change it without explicit instruction.

Status: not fixed.

## KI-08: Hardcoded SUPERADMIN account data in a tracked seeder

Observed:

- `database/seeders/SuperAdminSeeder.php` is tracked by Git.
- It calls `User::updateOrCreate(...)` with hardcoded account values for a SUPERADMIN user, including email and password.

Impact (Inferred):

- Sharing the repository exposes the hardcoded account data. Running the seeder creates a known account. The values are intentionally omitted from this document.

Status: not fixed.

## KI-09: FormRequest classes are non-functional stubs

Observed:

- There are 146 files under `app/Http/Requests`.
- Every file has `authorize()` returning `false` and an empty `rules()` body.
- FormRequest imports outside `app/Http/Requests` occur in 6 scaffold controllers:
  - `JadwalMengajarController`
  - `PenugasanGuruController`
  - `PPDBGelombangController`
  - `PPDBJawabanController`
  - `PPDBPeriodeController`
  - `PPDBPertanyaanController`
- `JadwalMengajarController` and `PenugasanGuruController` are routed only through `index`; their imports are unused. The other four controllers are unrouted.
- No active route invokes a FormRequest-validated action.
- Active validation is inline: 8 `->validate(` call sites in 7 controllers and validation methods in 87 Livewire files.

Impact (Inferred):

- An agent could incorrectly assume FormRequests provide validation or authorization. They do not.

Status: not fixed.

## KI-10: Policies and Gate authorization are stubs

Observed:

- There are 7 classes under `app/Policies`; each has no `return` statement.
- `app/Providers/AuthServiceProvider.php` has an empty `$policies` array.
- No `Gate::`, `->authorize(`, `->can(`, or `@can` usage was found in `app` or `resources/views`.
- `GuruPolicy` imports `App\Models\Guru`, which does not exist; its methods declare `: bool` but have no returns.
- Authorization is enforced by `App\Http\Middleware\MiddlewarePeran` through the `peran` route middleware.

Impact (Inferred):

- Policy-based checks are not active. Calling `GuruPolicy` methods would fail because they inherit no return values and reference a missing model type.

Status: not fixed.

## KI-11: Factories and seeders are mostly empty

Observed:

- All 7 files under `database/factories` have empty `definition()` bodies.
- 10 of 11 files under `database/seeders` have empty `run()` bodies.
- The exception is `SuperAdminSeeder` (see KI-08).

Impact (Inferred):

- Factories and seeders cannot be relied on to create usable development or test data.

Status: not fixed.

## KI-12: Tests and CI are absent or non-runnable

Observed:

- `tests/` does not exist.
- `phpunit.xml` references `tests/Unit` and `tests/Feature`.
- `.phpunit.result.cache` references 9 test names, including `Tests\Unit\Livewire\...`, `Tests\Unit\Letters\LetterHtmlSanitizerTest`, and `Tests\Feature\TagihanJenisDetailPdfTest`.
- `git log -- tests` shows commits that previously touched tests.
- `.github/workflows/tests.yml`:
  - triggers on `master` and `*.x`, while the active branch is `main`;
  - runs `cp .env.example .env`, but `.env.example` is absent;
  - runs `vendor/bin/phpunit` with no test suite.
- `composer.json` has no `test` script; `package.json` has only `dev` and `build`.

Impact (Inferred):

- CI is not a reliable validation signal, and no automated tests currently exist.

Status: not fixed.

## KI-13: Migrations do not reproduce the full schema

Observed:

- 22 migration files create 20 tables: `ms_pengguna`, `password_reset_tokens`, `failed_jobs`, `personal_access_tokens`, `ppdb_periode`, `ppdb_gelombang`, `ppdb_pendaftaran`, `ppdb_calon_siswa`, `ppdb_orangtua`, `ppdb_alamat`, `ppdb_pendidikan`, `ppdb_berkas`, `ppdb_jalur`, `ppdb_pertanyaan`, `ppdb_jawaban`, `ms_periode_akademik`, `ms_mata_pelajaran`, `gurus`, `ms_penugasan_guru`, `ej_jadwal_mengajar`.
- There are no migrations creating `ms_siswa`, `ms_pegawai`, `ms_kelas`, `ms_jenjang`, or the finance, canteen, cooperative, and smart-pass tables.
- `gurus` is orphaned: it has a migration, `GuruFactory`, and `GuruPolicy`, but no `App\Models\Guru`.
- The application models reference the existing database schema; migrations cover only part of it.

Impact (Inferred):

- `php artisan migrate` on an empty database does not produce a working application schema. Destructive database commands are unsafe.

Status: not fixed.

## KI-14: Empty letter classes and editor asset

Observed:

- 0-byte files:
  - `app/Services/Letters/LetterTemplateRenderer.php`
  - `app/Support/Letters/LetterHtmlSanitizer.php`
  - `app/Support/Letters/LetterVariableRegistry.php`
  - `resources/js/letter-template-editor.js`
- No application code references the three Letter classes.
- `resources/js/app.js` only imports `./bootstrap`; `resources/css/app.css` is empty.
- Vite is configured in `vite.config.js`, but no Blade file uses `@vite`.

Impact (Inferred):

- Letter rendering and sanitization are not implemented in these files. Any feature relying on them is incomplete.

Status: not fixed.

## KI-15: Legacy or unreferenced scaffolds

Observed:

- `App\Http\Controllers\ManajemenKepegawaian` returns `view('ADMINISTRASI.manajemen-kepegawaian.v_index')`; `resources/views/ADMINISTRASI` does not exist; its route is commented out (`routes/web.php:311`).
- `App\Http\Controllers\Keuangan\KelasSiswa` returns `view('keuangan.administrasi.kelas-siswa')`; that view does not exist and the controller is not routed.
- `App\Exports\ExportTabunganSiswa` returns `view('EXPORTS.laporan-tabungan-siswa-export')`; that view does not exist and the class is not referenced elsewhere.
- `resources/views/v_home.blade.php` is not referenced.
- `resources/views/livewire/siswa-ekstrakurikuler/detail.blade.php` is not referenced and extends a missing layout.
- `resources/views/keuangan/pegawai/index.blade.php` includes missing `livewire.pegawai.data`; the view is not referenced by active code.
- `livewire/keuangan/smart-canteen/*` and `livewire/keuangan/smart-pass/*` are orphaned by the path mismatch in KI-06.

Impact (Inferred):

- These files can mislead future work into copying stale patterns or expecting non-existent screens.

Status: not fixed.

## Unverified information

The following were not verified during the audit:

- The actual production database schema and data values; no database was queried.
- Whether the inactive modules (SmartCanteen, KoperasiPintar, SmartPass) are planned for re-enablement.
- Why the test suite was removed, and whether `.phpunit.result.cache` came from this branch or another local state.
- Whether external systems call the attendance webhook.
- Whether the hardcoded seeder account is used in any environment; it was not executed.
- Runtime behavior of the broken routes; findings are inferred from missing classes, methods, views, and layout references, not from HTTP requests.

## Non-goals

- No source code fixes or refactors.
- No migration, database, configuration, or dependency changes.
- No credential rotation or secret cleanup.
- No Git commit.
