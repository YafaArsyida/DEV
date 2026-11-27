# Module Architecture

This document inventories the functional modules of TemanSekolah, their status, entry points, internal structure, and dependencies. It complements [overview.md](./overview.md) and [database.md](./database.md).

- Verification date: 2026-11-27
- Evidence: static inspection plus `php artisan route:list --json`
- Database state: not inspected; schema statements are migration/model evidence only

## Classification

| Class | Meaning |
| --- | --- |
| **Active** | Has registered routes and reachable controllers/Livewire components |
| **Partially active / legacy** | Code exists, but its routes are commented out, its views are missing, or only part of it is wired |
| **Inactive / unrouted** | Full code, models, and views exist, but all routes are commented out |

## Summary

| Module | Class | Routes | Livewire files | Primary access |
| --- | --- | --- | --- | --- |
| Portal / Authentication | Active | 8 (`portal`, `login.*`, `logout`, `landing.ekstrakurikuler`, `ppdb.login`, `ppdb.authenticate`, `ppdb.logout`) | 2 PPDB auth | Public / `guest` / `auth` |
| PPDB / SPMB | Active | 36 | 77 | Public, `guest`, `auth`, `peran:ORANG_TUA`, staff group |
| Akademik | Active | 17 | 62 | Staff group |
| Keuangan | Active | 60 | 164 | Staff group |
| Manajemen Kepegawaian | Partially active / legacy | 0 (commented) | Shared `Pegawai` (8), `Jabatan` (4) | None |
| SmartCanteen | Inactive / unrouted | 0 (commented) | 40 | None |
| KoperasiPintar | Inactive / unrouted | 0 (commented) | 17 | None |
| SmartPass | Inactive / unrouted | 0 (commented) | 8 | None |

The staff group is `['auth', 'peran:SUPERADMIN,ADMIN,KEUANGAN,PETUGAS_PPDB']` (`routes/web.php:312`). The PPDB parent group is `['auth', 'peran:ORANG_TUA']` (`routes/web.php:165`).

---

## Portal / Authentication

### Status
Active.

### Purpose and business domain
Single entry point for all users. Renders the portal landing page, authenticates staff through a session guard, and provides PPDB parent login/registration through Livewire. Also exposes the public extracurricular landing page (`landing.ekstrakurikuler`) which is not part of authentication but is publicly reachable from the portal.

### Routes
| Route | Middleware | Action |
| --- | --- | --- |
| `GET /` (`portal`) | `web` | `Portal\PortalController@index` |
| `GET /login` (`login.index`) | `guest` | `LoginController@index` |
| `POST /login` (`login.authenticate`) | `guest` | `LoginController@authenticate` |
| `POST /logout` (`logout`) | `auth` | `LoginController@logOut` |
| `GET /ekstrakurikuler` (`landing.ekstrakurikuler`) | `web` | `LandingEkstrakurikuler@index` |
| `GET /ppdb/{jenjang}/login` (`ppdb.login`) | `guest` | `PPDB\Auth\LoginController@index` |
| `POST /ppdb/{jenjang}/login` (`ppdb.authenticate`) | `guest` | `PPDB\Auth\LoginController@authenticate` (missing method, KI-04) |
| `POST /ppdb/{jenjang}/logout` (`ppdb.logout`) | `auth`, `peran:ORANG_TUA` | `PPDB\Auth\LoginController@logOut` |

### Main controllers
- `App\Http\Controllers\Portal\PortalController` - renders `portal.index`.
- `App\Http\Controllers\LoginController` - staff login/logout, `current_session` single-session handling.
- `App\Http\Controllers\PPDB\Auth\LoginController` - PPDB login page and logout.
- `App\Http\Controllers\PPDB\Auth\RegisterController` - PPDB registration page.
- `App\Http\Controllers\LandingEkstrakurikuler` - renders `LANDING-PAGE.ekstrakurikuler.v_index`.

### Main Livewire components
- `App\Http\Livewire\PPDB\Auth\Login` -> `p-p-d-b.auth.login` - active PPDB authentication path.
- `App\Http\Livewire\PPDB\Auth\Register` -> `p-p-d-b.auth.register` - creates `User` rows with `peran = 'ORANG_TUA'`.
- `App\Http\Livewire\Landing\Ekstrakurikuler\InformasiKuota` -> `landing.ekstrakurikuler.informasi-kuota`.
- `App\Http\Livewire\FormulirEkstrakurikuler\Index` -> `formulir-ekstrakurikuler.index` (renders `livewire.keuangan.formulir-ekstrakurikuler.index`).

### Key views
- `resources/views/portal/index.blade.php` (standalone).
- `resources/views/login/v_index.blade.php` (standalone).
- `resources/views/ppdb/auth/login.blade.php`, `register.blade.php`.
- `resources/views/LANDING-PAGE/ekstrakurikuler/v_index.blade.php` (standalone public landing).

### Models and tables
- `App\Models\User` -> `ms_pengguna`, PK `ms_pengguna_id`.
- No dedicated portal model.

### Services
None specific. Login uses `Auth` directly.

### Internal dependencies
- `ms_pengguna.current_session` for single-session behavior.
- `App\Models\Jenjang` for PPDB registration/login context (jenjang segment).

### Cross-module dependencies
- All staff modules depend on the session created here.
- PPDB depends on `PPDB\Auth\Login` and `PPDB\Auth\Register`.
- Portal links to the public extracurricular landing page.

### Known issues / limitations
- `POST /ppdb/{jenjang}/login` targets a missing `authenticate` method (KI-04); the Livewire login is the real flow.
- `LoginController` imports a nonexistent `App\Models\ModelPengguna` (unused).
- `current_session` is not defined in the `ms_pengguna` migration; its existence in the live DB is **Unverified**.

---

## PPDB / SPMB

### Status
Active.

### Purpose and business domain
New-student admission (`Penerimaan Peserta Didik Baru` / `Sistem Penerimaan Murid Baru`). Covers public landing pages per jenjang, parent registration and login, multi-step application forms, document upload, staff verification, selection, announcement, re-registration (`daftar ulang`), reporting, PDF cards/forms, and bulk document download. Jenjang-aware: URLs and data are scoped by a `{jenjang}` segment.

### Routes
36 routes, grouped by access:

- **Public (5):** `ppdb.landing`, `ppdb.sd`, `ppdb.tk`, `ppdb.kb-a`, `ppdb.kb-b` -> `PPDB\PPDBLandingController`.
- **Guest (3):** `ppdb.daftar`, `ppdb.login`, `ppdb.authenticate` -> `PPDB\Auth\*`.
- **Authenticated, any role (4):** `ppdb.berkas.preview`, `ppdb.kartu-aljabar.pdf`, `ppdb.formulir-pendaftaran.pdf`, `ppdb.buku-induk.pdf` -> `PPDB\PPDBBerkasController`.
- **Parent (`peran:ORANG_TUA`, 12):** `ppdb.dashboard`, `ppdb.profil`, `ppdb.pendaftaran.status`, `ppdb.pendaftaran.kirim-ulang`, `ppdb.pendaftaran.jalur`, `ppdb.pendaftaran.data-siswa`, `ppdb.pendaftaran.orang-tua`, `ppdb.pendaftaran.alamat`, `ppdb.pendaftaran.pendidikan`, `ppdb.pendaftaran.berkas`, `ppdb.pendaftaran.review`, `ppdb.logout` -> `PPDB\PPDBOrangTuaController` and `PPDB\Auth\LoginController`.
- **Staff (12):** `ppdb.admin.dashboard`, `ppdb.admin.pendaftar`, `ppdb.admin.verifikasi`, `ppdb.admin.seleksi`, `ppdb.admin.pengumuman`, `ppdb.admin.daftar-ulang`, `ppdb.admin.laporan`, `ppdb.admin.laporan.pdf`, `ppdb.admin.laporan.berkas.zip`, `ppdb.admin.pengaturan`, `ppdb.admin.akses-petugas`, `ppdb.admin.berkas.preview` -> `PPDB\PPDBAdminController`.

`ppdb.admin.akses-petugas` adds a nested `peran:SUPERADMIN,ADMIN,PETUGAS_PPDB` check.

### Main controllers
- `App\Http\Controllers\PPDB\PPDBLandingController` - public landing pages (`sd`, `tk`, `kb_a`, `kb_b`).
- `App\Http\Controllers\PPDB\Auth\LoginController` - parent login page/logout.
- `App\Http\Controllers\PPDB\Auth\RegisterController` - parent registration.
- `App\Http\Controllers\PPDB\PPDBOrangTuaController` - 548 lines; parent dashboard, profile, multi-step forms, status, resubmission.
- `App\Http\Controllers\PPDB\PPDBAdminController` - staff dashboard, registrants, verification, selection, announcement, re-registration, reports, settings, staff access, document preview.
- `App\Http\Controllers\PPDB\PPDBBerkasController` - preview, card, registration form, and student master-book PDFs.

### Main Livewire components
Namespaces under `app/Http/Livewire/PPDB` (77 files):

- `Auth\Login`, `Auth\Register` -> `p-p-d-b.auth.*`.
- `Admin\Dashboard\*`, `Admin\Pendaftar\*`, `Admin\Verifikasi\*`, `Admin\Seleksi\*`, `Admin\Pengumuman\*`, `Admin\DaftarUlang\*`, `Admin\Laporan\*`, `Admin\Pengaturan\*`, `Admin\AksesPetugas\*` -> `p-p-d-b.admin.*`.
- `Pendaftaran\*` (19 files) -> `p-p-d-b.pendaftaran.*` (parent application steps and previews).
- `Periode\*`, `Gelombang\*`, `Jalur\*`, `Pertanyaan\*` -> `p-p-d-b.periode.*`, `p-p-d-b.gelombang.*`, `p-p-d-b.jalur.*`, `p-p-d-b.pertanyaan.*`.
- `Parameter\Jenjang`, `Parameter\JenjangPeriode` -> `p-p-d-b.parameter.*`.
- `Profil\*` -> `p-p-d-b.profil.*`.
- `Landing\*` -> `p-p-d-b.landing.*`.

Aliases kebab-case the `PPDB` segment to `p-p-d-b`, so views live under `resources/views/livewire/p-p-d-b`.

### Key views
- `resources/views/ppdb/` - landing, auth, parent dashboard, application steps, admin pages.
- `resources/views/livewire/p-p-d-b/` - all PPDB Livewire views.
- `resources/views/template_ppdb.v_template` and `template_portal_ppdb.v_template`.
- PPDB landing pages are standalone HTML documents.

### Models and tables
- `PPDBPeriode` -> `ppdb_periode`
- `PPDBGelombang` -> `ppdb_gelombang`
- `PPDBPendaftaran` -> `ppdb_pendaftaran`
- `PPDBCalonSiswa` -> `ppdb_calon_siswa`
- `PPDBOrangTua` -> `ppdb_orangtua`
- `PPDBAlamat` -> `ppdb_alamat`
- `PPDBPendidikan` -> `ppdb_pendidikan`
- `PPDBBerkas` -> `ppdb_berkas`
- `PPDBJalur` -> `ppdb_jalur`
- `PPDBPertanyaan` -> `ppdb_pertanyaan`
- `PPDBJawaban` -> `ppdb_jawaban`
- Supporting: `User` (`ms_pengguna`), `Jenjang` (`ms_jenjang`), `TahunAjar` (`ms_tahun_ajar`), `AksesJenjang` (`ms_akses_jenjang`).

### Services
- `App\Services\PPDB\PPDBPendaftaranService` - create/delete/updateJalur (static).
- `App\Services\PPDB\PPDBDataSiswaService` - create/update calon siswa (static).
- `App\Services\PPDB\PPDBOrangTuaService` - create/update parent data (static).
- `App\Services\PPDB\PPDBAlamatService` - create/update address (static).
- `App\Services\PPDB\PPDBPendidikanService` - create/update education history (static).
- `App\Services\PPDB\PPDBBerkasService` - file save/create/update/delete (static).
- `App\Services\PPDB\PPDBBerkasArsipService` - builds ZIP archives (instance).
- `App\Services\PPDB\PPDBFormulirPendaftaranService` - registration form data (instance).
- `App\Services\PPDB\PPDBKartuAljabarService` - card view/data (instance).
- `App\Services\Reports\PPDBLaporanPendataanReportService` - admission reporting.

### Internal dependencies
- Application data is split across `ppdb_pendaftaran`, `ppdb_calon_siswa`, `ppdb_orangtua`, `ppdb_alamat`, `ppdb_pendidikan`, `ppdb_berkas`, and `ppdb_jawaban`.
- Period, wave (`gelombang`), and pathway (`jalur`) configuration come from `ppdb_periode`, `ppdb_gelombang`, `ppdb_jalur`.
- `ppdb_pendaftaran.ms_siswa_id` is nullable and is the declared link to a student record. No automatic PPDB-to-`ms_siswa` conversion was found; the field is only set to `null` in `PPDB\Admin\Pendaftar\Create.php:1380`. **Unverified** whether a manual conversion exists elsewhere.

### Cross-module dependencies
- **Akademik:** references jenjang, academic year, and student master data; the conversion to `ms_siswa` is the intended hand-off.
- **Keuangan:** PPDB admin reuses `keuangan.pengguna.reset-password` in `resources/views/ppdb/admin/pendaftar.blade.php`.
- **Auth:** uses the shared `App\Models\User` and `ms_akses_jenjang` for staff jenjang scoping.

### Known issues / limitations
- `ppdb.authenticate` targets a missing method (KI-04); use Livewire login.
- Migration/model mismatches: `ppdb_periode` (`ms_tahun_ajar_id` vs model `ms_jenjang_id`), `ppdb_gelombang` (`id` column vs model PK `ppdb_gelombang_id`), `ppdb_pendaftaran` (`user_id` vs model `ms_pengguna_id`, plus model fields absent from the migration). See [database.md](./database.md).
- `PPDBPendaftaran::verifier()` uses owner key `id` on `User`, whose PK is `ms_pengguna_id`; this relationship is likely broken. **Inferred**.
- Status values used by code and the migration's enum do not fully agree; see [database.md](./database.md).

---

## Akademik

### Status
Active.

### Purpose and business domain
Academic master data and operations: education levels (`jenjang`), academic years, semesters, classes, subjects, teacher assignments, teaching schedules, students, student placement, extracurriculars, extracurricular placement, academic assessment, and academic reports.

### Routes
17 routes, all in the staff group:

- Dashboard: `akademik.dashboard` -> `Akademik\AkademikDashboard@index`.
- Master data: `akademik.jenjang`, `akademik.tahun-ajaran`, `akademik.semester`, `akademik.kelas`, `akademik.mata-pelajaran`.
- Teachers: `akademik.data-guru` -> `GuruController@index`; `akademik.penugasan-guru`; `akademik.jadwal-mengajar`.
- Students: `akademik.siswa`, `akademik.penempatan-siswa`.
- Assessment: `akademik.penilaian-akademik` -> `Akademik\AkademikSetupController@penilaianAkademik`.
- Extracurricular: `akademik.ekstrakurikuler`, `akademik.penempatan-ekstrakurikuler`, `akademik.ekstrakurikuler-siswa.pdf`, `akademik.ekstrakurikuler-siswa.siswapdf`.
- Reports: `akademik.laporan` (broken, KI-01).

### Main controllers
- `App\Http\Controllers\Akademik\AkademikDashboard` - renders `akademik.dashboard`.
- `App\Http\Controllers\Akademik\JenjangController`, `TahunAjaranController`, `KelasController`.
- `App\Http\Controllers\Akademik\PeriodeAkademikController` - semester page.
- `App\Http\Controllers\GuruController` - teacher list (Guru is `Pegawai` with `jenis_pegawai = 'GURU'`).
- `App\Http\Controllers\MataPelajaranController`, `PenugasanGuruController`, `JadwalMengajarController`.
- `App\Http\Controllers\Akademik\SiswaController`, `PenempatanSiswaController`.
- `App\Http\Controllers\Akademik\EkstrakurikulerSiswa`, `PenempatanEkstrakurikulerSiswa` - pages and PDFs.
- `App\Http\Controllers\Akademik\AkademikSetupController` - `penilaianAkademik` is routed; `semester`, `mataPelajaran`, `dataGuru`, `jadwalMengajar` are unrouted/static.
- `App\Http\Controllers\Akademik\LaporanController` - empty class, no `index` (KI-01).

### Main Livewire components
`app/Http/Livewire/Akademik` (62 files), aliases `akademik.*`:

- `Jenjang\{Index,Create,Edit,Delete}` -> `akademik.jenjang.*`
- `TahunAjaran\*` -> `akademik.tahun-ajaran.*`
- `PeriodeAkademik\*` -> `akademik.periode-akademik.*`
- `Kelas\*` -> `akademik.kelas.*`
- `MataPelajaran\*` -> `akademik.mata-pelajaran.*`
- `Guru\*` -> `akademik.guru.*` (works on `Pegawai`)
- `PenugasanGuru\*` -> `akademik.penugasan-guru.*`
- `JadwalMengajar\*` -> `akademik.jadwal-mengajar.*`
- `Siswa\*` -> `akademik.siswa.*`
- `PenempatanSiswa\*` -> `akademik.penempatan-siswa.*`
- `Ekstrakurikuler\*` -> `akademik.ekstrakurikuler.*`
- `PenempatanEkstrakurikuler\*` -> `akademik.penempatan-ekstrakurikuler.*`

### Key views
- `resources/views/akademik/*.blade.php` - one page per feature.
- `resources/views/template_akademik.v_template` and sidebar.
- Livewire views under `resources/views/livewire/akademik/`.

### Models and tables
- `Jenjang` -> `ms_jenjang`
- `TahunAjar` -> `ms_tahun_ajar`
- `PeriodeAkademik` -> `ms_periode_akademik`
- `Kelas` -> `ms_kelas`
- `MataPelajaran` -> `ms_mata_pelajaran`
- `Pegawai` -> `ms_pegawai` (teachers are `jenis_pegawai = 'GURU'`)
- `Jabatan` -> `ms_jabatan`
- `PenugasanGuru` -> `ms_penugasan_guru`
- `JadwalMengajar` -> `ej_jadwal_mengajar`
- `Siswa` -> `ms_siswa`
- `PenempatanSiswa` -> `ms_penempatan_siswa`
- `Ekstrakurikuler` -> `ms_ekstrakurikuler`
- `PenempatanEkstrakurikuler` -> `ms_penempatan_ekstrakurikuler`
- `EduCard` -> `ms_educard` (employee/student card data)

### Services
- `App\Services\Reports\DaftarSiswaEkstrakurikulerReportService`.
- `App\Services\Reports\EkstrakurikulerSiswaReportService`.

### Internal dependencies
- Placement links `ms_siswa` to `ms_kelas`, `ms_jenjang`, and `ms_tahun_ajar`.
- Teacher assignment links `ms_pegawai` to `ms_mata_pelajaran`, `ms_kelas`, and `ms_tahun_ajar`.
- Schedule links assignments to day/time slots in `ej_jadwal_mengajar`.
- Extracurricular placement links `ms_siswa` to `ms_ekstrakurikuler`.

### Cross-module dependencies
- **Keuangan:** Akademik pages embed `keuangan.parameter.informasi-tahun-ajar` and `keuangan.parameter.jenjang-tahun-ajar` on nearly every page, and the dashboard embeds 8+ `keuangan.widget.*` components. Keuangan pages embed `akademik.siswa.create` and `akademik.siswa.edit`.
- **PPDB:** PPDB reads jenjang/academic-year data; intended student hand-off is `ms_siswa`.
- **SmartCanteen / SmartPass:** dormant modules read student/employee data.

### Known issues / limitations
- `akademik.laporan` is broken: `LaporanController` has no `index` method, and the sidebar links to it (KI-01).
- `akademik.semester` is routed to `PeriodeAkademikController@index`, but the Akademik sidebar may also reference `akademik.semester` in contexts that expect other data; verify before changing.
- `AkademikSetupController` has unrouted methods with static sample data; do not assume they are wired.
- `App\Models\Guru` does not exist; teacher data is `Pegawai`.

---

## Keuangan

### Status
Active.

### Purpose and business domain
School finance: student billing (`tagihan`), bill types and categories, payments, receipts, savings (`tabungan`) for students and employees, EduPay balances for students and employees, other income, expenses, accounting journals and reports, financial reports, and administrative documents.

### Routes
60 routes, all in the staff group:

- Dashboard: `keuangan.dashboard`.
- **Sistem:** `keuangan.sistem.jenjang-tahun-ajar` (broken, KI-02), `keuangan.sistem.dokumen-administrasi`, `keuangan.sistem.pengguna-jenjang`.
- **Administrasi:** `keuangan.administrasi.penempatan-siswa`.
- **Tagihan:** `keuangan.tagihan.konfigurasi`, `keuangan.tagihan.siswa` (+ `.pdf`, `.detail-pdf`), `keuangan.tagihan.jenis` (+ `.pdf`, `.detail-pdf`).
- **Transaksi:** `keuangan.transaksi.tagihan-siswa` (+ `.kuitansi`), `tabungan-siswa`, `edupay-siswa`, `edupay-pegawai`, `tabungan-pegawai`, `pendapatan-lainnya`, `pengeluaran` (each with PDF/kuitansi where applicable).
- **Akuntansi:** `keuangan.akuntansi.konfigurasi`, `keuangan.akuntansi.jurnal-detail` (broken, KI-03), and 7 report families with HTML + PDF endpoints: `arus-kas`, `buku-besar`, `jurnal-umum`, `laba-rugi`, `neraca`, `pendapatan`, `pengeluaran`.
- **Laporan:** `keuangan.laporan.siswa.edupay`, `keuangan.laporan.siswa.tabungan`, `keuangan.laporan.pegawai.edupay`, `keuangan.laporan.pegawai.tabungan`, `keuangan.laporan.pembayaran.tagihan-siswa`, `keuangan.laporan.tagihan.siswa`, `keuangan.laporan.tagihan.kelas.pdf`, `keuangan.laporan.rekapitulasi-keuangan-siswa`.

### Main controllers
`app/Http/Controllers/Keuangan` (32 files). Controllers are mostly thin page/PDF endpoints; some are heavy:

- `KeuanganDashboard`.
- `JenjangTahunAjar`, `DokumenAdministrasi`, `PenggunaJenjang`.
- `PenempatanSiswaController` (aliased `KeuanganPenempatanSiswaController`).
- `KonfigurasiTagihanSiswa`, `TagihanSiswa`, `JenisTagihanSiswa`.
- `TransaksiTagihanSiswa` (253+ lines), `TransaksiTabunganSiswa` (369 lines), `TransaksiEduPaySiswa` (253 lines), `TransaksiTabunganPegawai`, `TransaksiEduPayPegawai`, `TransaksiPendapatanLainnya`, `TransaksiPengeluaran`.
- `AkuntansiJurnalDetail` (broken, KI-03), `AkuntansiKonfigurasiJurnal`, and the `AkuntansiLaporan*` controllers.
- `LaporanTagihanSiswa`, `LaporanPembayaranTagihanSiswa`, `LaporanTabunganSiswa`, `LaporanTabunganPegawai`, `LaporanEduPaySiswa`, `LaporanEduPayPegawai`, `LaporanRekapitulasiKeuanganSiswa`.

### Main Livewire components
`app/Http/Livewire/Keuangan` (164 files), aliases `keuangan.*`. Major groups:

- `Parameter\*` (15) -> `keuangan.parameter.*` - jenjang/academic-year selectors.
- `Widget\*` (11) -> `keuangan.widget.*` - dashboard cards.
- `Pengguna\*`, `AksesJenjang\*` -> `keuangan.pengguna.*`, `keuangan.akses-jenjang.*`.
- `AkuntansiRekening\*`, `AkuntansiKelompokRekening\*`, `AkuntansiLaporan*\*` -> `keuangan.akuntansi-*.*`.
- `JenisTagihanSiswa\*`, `KategoriTagihanSiswa\*`, `TagihanSiswa\*`, `TagihanJenis\*`.
- `TransaksiTagihanSiswa\*` (10), `TransaksiTabunganSiswa\*` (6), `TransaksiEduPaySiswa\*` (6), `TransaksiTabunganPegawai\*` (2), `TransaksiEduPayPegawai\*` (2), `TransaksiPendapatanLainnya\*` (5), `TransaksiPengeluaran\*` (5).
- `LaporanTagihanSiswa\*`, `LaporanPembayaranTagihanSiswa\*` (8), `LaporanTabunganSiswa\*` (7), `LaporanTabunganPegawai\*` (3), `LaporanEduPaySiswa\*` (7), `LaporanEduPayPegawai\*` (4), `LaporanRekapitulasiKeuangan\*`.
- `KuitansiPembayaranTagihanSiswa\*`, `KuitansiTransaksiTabungan\*`, `KuitansiTransaksiEduPay\*`.
- `SuratTagihanSiswa\*`, `WhatsApp*\*`.

### Key views
- `resources/views/keuangan/` - dashboard, sistem, administrasi, tagihan, transaksi, akuntansi, laporan.
- `resources/views/template_keuangan.v_template` and sidebar.
- Livewire views under `resources/views/livewire/keuangan/`.
- `resources/views/livewire/keuangan/smart-canteen/*` and `livewire/keuangan/smart-pass/*` are orphaned dormant-module views (KI-06).

### Models and tables
- **Billing:** `TagihanSiswa` -> `ms_tagihan_siswa`, `JenisTagihanSiswa` -> `ms_jenis_tagihan_siswa`, `KategoriTagihanSiswa` -> `ms_kategori_tagihan_siswa`, `KeranjangTagihanSiswa` -> `ms_keranjang_tagihan_siswa`, `DetailTransaksiTagihanSiswa` -> `dt_transaksi_tagihan_siswa`, `TransaksiTagihanSiswa` -> `ms_transaksi_tagihan_siswa`, `SuratTagihanSiswa` -> `ms_surat_tagihan_siswa`.
- **Receipts:** `KuitansiPembayaranTagihanSiswa` -> `ms_kuitansi_pembayaran_tagihan_siswa`, `KuitansiTransaksiTabungan` -> `ms_kuitansi_transaksi_tabungan`, `KuitansiTransaksiEduPay` -> `ms_kuitansi_transaksi_edupay`.
- **Savings / EduPay:** `SaldoTabungan` -> `ms_saldo_tabungan`, `TransaksiTabungan` -> `ms_transaksi_tabungan`, `SaldoEduPay` -> `ms_saldo_edupay`, `TransaksiEduPay` -> `ms_transaksi_edupay`.
- **Accounting:** `AkuntansiJurnal` -> `akuntansi_jurnal`, `AkuntansiJurnalDetail` -> `akuntansi_jurnal_detail`, `AkuntansiRekening` -> `akuntansi_rekening`, `AkuntansiKelompokRekening` -> `akuntansi_kelompok_rekening`.
- **Other income / expense:** `TransaksiPendapatanLainnya` -> `transaksi_pendapatan_lainnya`, `TransaksiPengeluaran` -> `transaksi_pengeluaran`.
- **WhatsApp:** `WhatsAppTagihanSiswa`, `WhatsAppPembayaranTagihanSiswa`, `WhatsAppTransaksiTabungan`, `WhatsAppTransaksiEduPay` (tables prefixed `ms_whatsapp_*`).
- **Supporting:** `Jenjang`, `TahunAjar`, `Kelas`, `Siswa`, `PenempatanSiswa`, `Pegawai`, `User`, `AksesJenjang`.

### Services
- `App\Services\AccountingService` - static journal create/update/reverse/delete and number generation.
- Report services (`app/Services/Reports`): `AkuntansiLaporanArusKasReportService`, `AkuntansiLaporanBukuBesarReportService`, `AkuntansiLaporanJurnalUmumReportService`, `AkuntansiLaporanLabaRugiReportService`, `AkuntansiLaporanNeracaReportService`, `AkuntansiLaporanPendapatanReportService`, `AkuntansiLaporanPengeluaranReportService`, `JenisTagihanSiswaReportService`, `LaporanEduPayPegawaiReportService`, `LaporanEduPaySiswaReportService`, `LaporanPembayaranTagihanSiswaReportService`, `LaporanTabunganPegawaiReportService`, `LaporanTabunganSiswaReportService`, `LaporanTagihanSiswaReportService`, `TagihanSiswaDetailReportService`, `TagihanSiswaReportService`, `TransaksiPendapatanLainnyaReportService`, `TransaksiPengeluaranReportService`.

### Internal dependencies
- Billing flows span `ms_jenis_tagihan_siswa`, `ms_tagihan_siswa`, `dt_transaksi_tagihan_siswa`, `ms_transaksi_tagihan_siswa`, and `ms_kuitansi_pembayaran_tagihan_siswa`.
- Savings/EduPay flows update balance tables and write accounting journals.
- Accounting flows depend on `akuntansi_rekening` and `akuntansi_kelompok_rekening`; journals are written through `AccountingService`.
- Jenjang scoping depends on `ms_akses_jenjang`.

### Cross-module dependencies
- **Akademik:** reads students, placements, classes, employees, and jenjang; embeds `akademik.siswa.create`/`edit`.
- **PPDB:** PPDB admin embeds `keuangan.pengguna.reset-password`.
- **SmartCanteen (dormant):** SmartCanteen settlement and transaction components call `AccountingService`.
- **SmartPass (dormant):** no direct Keuangan dependency found.

### Known issues / limitations
- `keuangan.sistem.jenjang-tahun-ajar` extends a missing layout and uses nonexistent aliases `jenjang.*` / `tahun-ajar.*` (KI-02).
- `keuangan.akuntansi.jurnal-detail` extends a missing layout and uses alias `parameter.jenjang-tahun-ajar` instead of `keuangan.parameter.jenjang-tahun-ajar` (KI-03).
- `AkuntansiJurnalDetail::totalPendapatan()` references relation `ms_akuntansi_rekening`, but the actual relation/table is `akuntansi_rekening`; likely broken. **Inferred**.
- `AkuntansiRekening::saldo_akhir()` queries `is_canceled`, which is not in the model's `$fillable`; assumed to be a real column. **Unverified**.
- `TagihanSiswa` scopes reference legacy tables `ms_tagihan` and `ms_kategori_tagihan`; current tables are `ms_tagihan_siswa` and `ms_kategori_tagihan_siswa`. **Inferred** stale.
- `Kelas::totalEstimasi()` references `ms_tagihan` and `dt_transaksi`; legacy table names. **Inferred** stale.

---

## Manajemen Kepegawaian

### Status
Partially active / legacy. Route commented out; controller and legacy view exist; the view the controller targets does not.

### Purpose and business domain
Employee and position management: employee records, positions (`jabatan`), imports, contact imports, EduCard imports, and position CRUD. Intended to be shared by Akademik (teachers) and SmartPass (attendance).

### Routes
None active. `routes/web.php:311` has `Route::get('/administrasi/manajemen-kepegawaian', [ManajemenKepegawaian::class, 'index'])->name('administrasi.manajemen-kepegawaian');` inside a commented block. The commented block used `peran:SUPERADMIN,ADMINISTRASI`.

### Main controllers
- `App\Http\Controllers\ManajemenKepegawaian` - returns `view('ADMINISTRASI.manajemen-kepegawaian.v_index')`. `resources/views/ADMINISTRASI` does not exist (KI-15).
- `App\Http\Controllers\Keuangan\KelasSiswa` - unrouted and targets a missing view (KI-15).

### Main Livewire components
Shared components, not module-specific:

- `App\Http\Livewire\Pegawai\{Index,Create,Edit,Delete,Import,ImportKontak,ImportEduCard,Data}` -> `pegawai.*`.
- `App\Http\Livewire\Jabatan\{Index,Create,Edit,Delete}` -> `jabatan.*`.

### Key views
- `resources/views/keuangan/administrasi/manajemen-kepegawaian.blade.php` - legacy view that includes `pegawai.*` and `jabatan.*` Livewire components; not targeted by the controller and not routed.
- The controller-targeted `resources/views/ADMINISTRASI/manajemen-kepegawaian/v_index.blade.php` does not exist.

### Models and tables
- `Pegawai` -> `ms_pegawai`
- `Jabatan` -> `ms_jabatan`
- `EduCard` -> `ms_educard`
- `User` -> `ms_pengguna` (employees may have linked user accounts via `user_id`/`ms_pengguna_id`)

### Services
None dedicated. Imports use `maatwebsite/excel`.

### Internal dependencies
- `Pegawai` links to `Jabatan`, `EduCard`, and optionally `User`.
- Teacher views in Akademik use `Pegawai` filtered by `jenis_pegawai = 'GURU'`.

### Cross-module dependencies
- **Akademik:** teacher data (`GuruController`, `akademik.guru.*`).
- **Keuangan:** employee savings/EduPay transactions and employee selection.
- **SmartPass (dormant):** employee attendance.

### Known issues / limitations
- Route commented out and target view missing (KI-15).
- Shared `pegawai.*`/`jabatan.*` components are used only by legacy/unrouted views (`keuangan/administrasi/manajemen-kepegawaian.blade.php`, `SMARTPASS/ADMINNISTRASI/data-pegawai/v_index.blade.php`).
- The legacy SmartCanteen view uses `pengguna.reset-password`; the actual alias is `keuangan.pengguna.reset-password`. **Inferred** stale.

---

## SmartCanteen

### Status
Inactive / unrouted.

### Purpose and business domain
School canteen: canteen master data, product categories, products, carts, sales transactions, settlements, canteen access, transaction reports, and dashboard widgets. Integrates with accounting journals via `AccountingService`.

### Routes
None. The route block is commented out at `routes/web.php:266-279`; it used `peran:SUPERADMIN,ADMINISTRASI,KANTIN`. Commented names include `smartCanteen.dashboard`, `smartCanteen.administrasi.kantin`, `smartCanteen.administrasi.produk`, `smartCanteen.transaksi.produk`, `smartCanteen.laporan.transaksi`, `smartCanteen.laporan.transaksi.pdf`, `smartCanteen.settlement.transaksi`.

### Main controllers
- `SmartCanteenDashboard`, `SmartCanteenAdministrasiKantin`, `SmartCanteenAdministrasiProduk`, `SmartCanteenTransaksiProduk`, `SmartCanteenLaporanTransaksi`, `SmartCanteenSettlementTransaksi` (root `app/Http/Controllers`).

### Main Livewire components
`app/Http/Livewire/SmartCanteen` (40 files), aliases `smart-canteen.*`:

- `AksesKantin\*`, `Kantin\*`, `KategoriProdukKantin\*`, `ProdukKantin\*`.
- `TransaksiProduk\*`, `SettlementTransaksi\*`, `LaporanTransaksi\*`, `Widget\*`.

### Key views
- `resources/views/SMARTCANTEEN/` - page templates (`dashboard`, `administrasi-kantin`, `administrasi-produk`, `transaksi-produk`, `settlement-transaksi`, `laporan-transaksi`).
- `resources/views/livewire/keuangan/smart-canteen/` - actual component views, orphaned because components render `livewire.smart-canteen.*` (KI-06).

### Models and tables
- `Kantin` -> `ms_kantin`
- `AksesKantin` -> `ms_akses_kantin`
- `KategoriProdukSmartCanteen` -> `ms_kategori_produk_kantin`
- `ProdukSmartCanteen` -> `ms_produk_kantin`
- `KeranjangSmartCanteen` -> `ms_keranjang_kantin`
- `TransaksiSmartCanteen` -> `ms_transaksi_kantin`
- `DetailTransaksiSmartCanteen` -> `dt_transaksi_kantin`
- `SettlementSmartCanteen` -> `ms_settlement_kantin`

### Services
- `App\Services\AccountingService` (journal creation/update/reversal).

### Internal dependencies
- Cart -> transaction -> detail -> settlement.
- Access is per canteen (`ms_akses_kantin`).
- Accounting journals are created for transactions and settlements.

### Cross-module dependencies
- **Keuangan:** accounting journals.
- **Akademik:** student data for transactions. **Inferred** from models; verify before enabling.
- **Auth:** commented role values `KANTIN` and `ADMINISTRASI`.

### Known issues / limitations
- No active routes.
- Livewire view paths do not match the actual view directory (KI-06); re-enabling requires fixing `return view(...)` paths.
- Migration files for canteen tables do not exist (KI-13); the live DB is authoritative.
- Controllers and views may reference missing layouts or stale aliases.

---

## KoperasiPintar

### Status
Inactive / unrouted.

### Purpose and business domain
School cooperative: product categories, products, suppliers, purchase transactions, purchase returns, and sales transactions.

### Routes
None. The route block is commented out at `routes/web.php:281-290`; it used `peran:SUPERADMIN,ADMINISTRASI,KANTIN`. Commented names include `koperasiPintar.dashboard`, `koperasiPintar.administrasi.produk`, `koperasiPintar.pembelian.produk`, `koperasiPintar.pengembalian.produk`, `koperasiPintar.penjualan.produk`.

### Main controllers
- `KoperasiPintarDashboard`, `KoperasiPintarAdministrasiProduk`, `KoperasiPintarPembelianProduk`, `KoperasiPintarPengembalianProduk`, `KoperasiPintarPenjualanProduk`.

### Main Livewire components
`app/Http/Livewire/KoperasiPintar` (17 files), aliases `koperasi-pintar.*`:

- `KategoriProdukKoperasi\*`, `ProdukKoperasi\*`, `SupplierKoperasi\*`, `TransaksiPembelianProduk\*`.

### Key views
- `resources/views/KOPERASIPINTAR/` - `dashboard`, `administrasi-produk`, `pembelian-produk`.
- `resources/views/livewire/koperasi-pintar/` - `kategori-produk-koperasi`, `produk-koperasi`, `supplier-koperasi`, `transaksi-pembelian-produk`. These paths match component renders.

### Models and tables
- `KategoriProdukKoperasi` -> `ms_kategori_produk_koperasi`
- `ProdukKoperasi` -> `ms_produk_koperasi`
- `SupplierKoperasi` -> `ms_supplier_koperasi`
- `TransaksiPembelianProdukKoperasi` -> `ms_transaksi_pembelian_produk_koperasi`
- `DetailTransaksiPembelianProdukKoperasi` -> `dt_pembelian_produk_koperasi`
- `TransaksiPengembalianProdukKoperasi` -> `ms_transaksi_pengembalian_produk_koperasi`
- `DetailTransaksiPengembalianProdukKoperasi` -> `dt_pengembalian_produk_koperasi`
- `TransaksiPenjualanProdukKoperasi` -> `ms_transaksi_penjualan_produk_koperasi`
- `DetailTransaksiPenjualanProdukKoperasi` -> `dt_penjualan_produk_koperasi`

### Services
None dedicated.

### Internal dependencies
- Purchase/return/sales transactions link to products and suppliers through detail tables.

### Cross-module dependencies
- **Keuangan:** no direct dependency found.
- **Auth:** commented role values `KANTIN` and `ADMINISTRASI`.

### Known issues / limitations
- No active routes.
- Two model files declare mismatched class names:
  - `app/Models/KoperasiPintar/TransaksiPembelianProdukKoperasi.php` declares `TransaksiPengembalianProdukKoperasi`.
  - `app/Models/KoperasiPintar/TransaksiPengembalianProdukKoperasi.php` declares `TransaksiPengembalianBarangKoperasi`.
  This makes autoloading/relationship resolution fragile; do not rely on those class names.
- No migrations for cooperative tables (KI-13).

---

## SmartPass

### Status
Inactive / unrouted.

### Purpose and business domain
Attendance (`presensi`) for employees and students, including FingerSpot integration, attendance configuration, attendance reports, and dashboard widgets.

### Routes
None. The route block is commented out at `routes/web.php:292-302`; it used `peran:SUPERADMIN,ADMINISTRASI,KANTIN`. Commented names include `smartPass.dashboard`, `smartPass.administrasi.pegawai`, `smartPass.administrasi.siswa`, `smartPass.laporan.pegawai`, `smartPass.laporan-fingerspot.pegawai`, `smartPass.presensi.pegawai`.

### Main controllers
- `SmartPassDashboard`, `SmartPassAdministrasiPegawai`, `SmartPassLaporanPresensiPegawai`, `SmartPassLaporanFingerSpotPegawai`, `SmartPassPresensiPegawai`.

### Main Livewire components
`app/Http/Livewire/SmartPass` (8 files), aliases `smart-pass.*`:

- `PresensiPegawai\*`, `FingerSpotPresensiPegawai\*`, `LaporanPresensiPegawai\*`, `Widget\*`.

### Key views
- `resources/views/SMARTPASS/` - `dashboard`, `ADMINNISTRASI` (typo preserved), `FINGERSPOT`, `LAPORAN`, `OPERASIONAL`.
- `resources/views/livewire/keuangan/smart-pass/` - actual component views, orphaned because components render `livewire.smart-pass.*` (KI-06).

### Models and tables
- `PresensiPegawai` -> `ms_presensi_pegawai`
- `PresensiSiswa` -> `ms_absensi_siswa`
- `KonfigPresensiPegawai` -> `ms_absensi_pegawai_konfig`
- `KonfigPresensiSiswa` -> `ms_absensi_siswa_konfig`
- `App\Models\FingerSpot\PresensiPegawaiLog` -> `presensi_pegawai_log` (webhook target; no `$primaryKey` declared)

### Services
None dedicated.

### Internal dependencies
- Attendance records link to employees/students by identifier (`pin`/`pin_fingerspot` for logs).
- Configuration tables control attendance behavior.

### Cross-module dependencies
- **Akademik / Kepegawaian:** employee and student master data.
- **API webhook:** `POST /api/absensi/webhook` writes `presensi_pegawai_log` (KI-07).
- **Auth:** commented role values `ADMINISTRASI` and `KANTIN`.

### Known issues / limitations
- No active routes.
- Livewire view paths do not match the actual view directory (KI-06).
- The attendance webhook is unauthenticated (KI-07). Do not change it without explicit instruction.
- `resources/views/SMARTPASS/ADMINNISTRASI` is misspelled; references may rely on the typo.
- No migrations for attendance tables (KI-13).

---

## Shared Components and Cross-Module Dependencies

### Shared Livewire components
| Component | Alias | Used by |
| --- | --- | --- |
| `App\Http\Livewire\Pegawai\*` | `pegawai.*` | Legacy Manajemen Kepegawaian view, dormant SmartPass view |
| `App\Http\Livewire\Jabatan\*` | `jabatan.*` | Legacy Manajemen Kepegawaian view, dormant SmartPass view |
| `App\Http\Livewire\FormulirEkstrakurikuler\Index` | `formulir-ekstrakurikuler.index` | Public extracurricular landing |
| `App\Http\Livewire\Landing\Ekstrakurikuler\InformasiKuota` | `landing.ekstrakurikuler.informasi-kuota` | Public extracurricular landing |

### Shared data and services
| Dependency | Owner | Consumers |
| --- | --- | --- |
| `ms_pengguna` + `ms_akses_jenjang` | Auth / Keuangan admin | All staff modules, PPDB staff |
| `ms_jenjang`, `ms_tahun_ajar`, `ms_kelas` | Akademik | PPDB, Keuangan, SmartCanteen, SmartPass |
| `ms_siswa`, `ms_penempatan_siswa` | Akademik | Keuangan, SmartCanteen, SmartPass |
| `ms_pegawai`, `ms_jabatan` | Kepegawaian / Akademik | Keuangan, SmartPass |
| `AccountingService` | Keuangan | Keuangan, dormant SmartCanteen |
| `ReportService` classes | Shared | Akademik, Keuangan, PPDB |
| `keuangan.parameter.*` / `keuangan.widget.*` | Keuangan | Akademik pages and dashboard |
| `akademik.siswa.*` | Akademik | Keuangan placement and billing pages |
| `keuangan.pengguna.reset-password` | Keuangan | PPDB admin registrant page |

### Student lifecycle (as declared in code)
1. PPDB application is stored in `ppdb_pendaftaran` and related `ppdb_*` tables.
2. `ppdb_pendaftaran.ms_siswa_id` is the declared link to `ms_siswa`, but no automatic conversion was found. **Unverified**.
3. Akademik manages `ms_siswa` and `ms_penempatan_siswa`.
4. Keuangan bills students through `ms_tagihan_siswa` and related tables.
5. SmartCanteen and SmartPass (dormant) reference student data.

### Employee lifecycle (as declared in code)
1. `ms_pegawai` stores employees; teachers are `jenis_pegawai = 'GURU'`.
2. `ms_jabatan` stores positions.
3. Akademik assigns teachers to subjects/classes through `ms_penugasan_guru` and schedules through `ej_jadwal_mengajar`.
4. Keuangan manages employee savings/EduPay through `ms_transaksi_tabungan` / `ms_transaksi_edupay` and employee-specific reports.
5. SmartPass (dormant) records attendance in `ms_presensi_pegawai` and `presensi_pegawai_log`.

### Dependency risks
- **Bidirectional view coupling between Akademik and Keuangan** means a change to `keuangan.parameter.*` or `akademik.siswa.*` can break pages in the other module.
- **No global jenjang scoping** means new components must replicate the `ms_akses_jenjang` filter.
- **Dormant modules still autoload** and are referenced by orphaned views; do not assume they are safe to delete or ignore when refactoring shared models.
- **Migrations are incomplete**, so enabling a dormant module requires verifying the live schema first.
