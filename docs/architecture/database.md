# Database Architecture

This document describes the database as evidenced by migrations and Eloquent models. The live database was **not** queried. TemanSekolah does not have a migration-complete schema, so "migration-defined" and "model-referenced" are kept separate throughout.

- Verification date: 2026-11-27
- Evidence: `database/migrations/*`, `app/Models/*`, `config/database.php`
- Live database: not inspected
- Related: [overview.md](./overview.md), [modules.md](./modules.md), [known-issues.md](../known-issues.md)

> Critical: the existing database is the source of truth. `php artisan migrate` on an empty database creates only part of the schema. Do not run `migrate:fresh`, `migrate:refresh`, `migrate:reset`, or seeders without explicit approval.

---

## A. Database Overview

### Connection and engine

| Setting | Value | Evidence |
| --- | --- | --- |
| Default connection | `mysql` | `config/database.php:18` |
| Driver | MySQL via PDO | `config/database.php` |
| Charset | `utf8mb4` (Laravel default) | `config/database.php` |
| Collation | `utf8mb4_unicode_ci` (Laravel default) | `config/database.php` |
| Engine | InnoDB (Laravel default) | Migrations do not override |
| Timezone | `Asia/Jakarta` (application) | `config/app.php:74` |
| Strict mode | `true` (Laravel default) | `config/database.php` |

Connection credentials come from `DB_*` environment variables. `.env.example` is absent, so the expected variable set and any production values are **Unverified**.

### Schema coverage

| Metric | Count | Notes |
| --- | --- | --- |
| Migration files | 22 | Includes one additive migration and one column-rename migration |
| Tables created by migrations | 20 | 4 framework/auth tables + 11 PPDB + 5 academic |
| Model files | 75 | 52 root + 1 CORE + 1 FingerSpot + 9 KoperasiPintar + 8 SmartCanteen + 4 SmartPass |
| Distinct model-referenced tables | 74 | `CORE\Departemen` declares no table |
| Model-referenced tables with a migration | 16 | `ms_pengguna`, 11 PPDB tables, `ms_periode_akademik`, `ms_mata_pelajaran`, `ms_penugasan_guru`, `ej_jadwal_mengajar` |
| Model-referenced tables without a migration | 58 | Finance, student, employee, canteen, cooperative, attendance tables |
| Migration-only tables | 4 | `password_reset_tokens`, `failed_jobs`, `personal_access_tokens`, `gurus` |

### Conventions

- **Table prefixes are meaningful, not uniform Laravel plural snake_case.**
  - `ms_` - master data (`ms_siswa`, `ms_pegawai`, `ms_jenjang`, `ms_pengguna`).
  - `dt_` - transaction detail (`dt_transaksi_tagihan_siswa`, `dt_transaksi_kantin`).
  - `ej_` - e-journal/academic schedule (`ej_jadwal_mengajar`).
  - `ppdb_` - admission (`ppdb_pendaftaran`, `ppdb_calon_siswa`).
  - `akuntansi_` - accounting (`akuntansi_jurnal`, `akuntansi_rekening`).
  - `transaksi_` - other income/expense (`transaksi_pendapatan_lainnya`, `transaksi_pengeluaran`).
  - `presensi_` - attendance (`presensi_pegawai_log`).
- **Primary keys are prefixed and often not `id`.** Examples: `ms_pengguna_id`, `ppdb_pendaftaran_id`, `akuntansi_jurnal_id`. Exceptions: `ppdb_gelombang` (migration uses `id`), `gurus` (`id`), and framework tables.
- **Timestamps and soft deletes.** Most tables created by migrations have `created_at`, `updated_at`, and `deleted_at`. `deleted_at` is `NULL` when the model does not use `SoftDeletes` in the live schema; the model declaration is the reliable signal for soft-delete behavior.
- **Casts are sparse.** Only 15 models declare `$casts`; the rest rely on implicit string/numeric handling.
- **No foreign key constraints.** No migration uses `$table->foreign(...)`, `->constrained()`, or `->references()`. Referential integrity is application-level only.
- **Enums are used in some migrations, strings in others.** Academic statuses use `enum('AKTIF','NONAKTIF')`; PPDB uses mixed `enum` and `string` columns; finance tables (unmigrated) use strings.
- **No migration for the live schema.** Do not infer the full schema from migrations alone.

---

## B. Migration-Defined Tables

The 20 tables below are the only ones created by migrations. Column types and defaults are taken directly from the migration files.

### B.1 Framework and authentication

#### `ms_pengguna`
Migration: `2014_10_12_000000_create_users_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ms_pengguna_id` | bigint unsigned, auto-increment | PK | `$table->id('ms_pengguna_id')` |
| `nama` | varchar(255) | not null | |
| `email` | varchar(255) | not null, unique | |
| `password` | varchar(255) | not null | |
| `peran` | varchar(255) | not null | Role string used by `peran` middleware |
| `email_verified_at` | timestamp | nullable | |
| `remember_token` | varchar(100) | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | Soft deletes |

Model: `App\Models\User`.
Model-declared but not in migration: `telepon`, `current_session` (in `$fillable`). **Unverified** in the live DB.

#### `password_reset_tokens`
Migration: `2014_10_12_100000_create_password_reset_tokens_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `email` | varchar(255) | PK | |
| `token` | varchar(255) | not null | |
| `created_at` | timestamp | nullable | |

No model.

#### `failed_jobs`
Migration: `2019_08_19_000000_create_failed_jobs_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `id` | bigint unsigned, auto-increment | PK | |
| `uuid` | varchar(255) | not null, unique | |
| `connection` | text | not null | |
| `queue` | text | not null | |
| `payload` | longtext | not null | |
| `exception` | longtext | not null | |
| `failed_at` | timestamp | not null, `useCurrent()` | |

No model.

#### `personal_access_tokens`
Migration: `2019_12_14_000001_create_personal_access_tokens_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `id` | bigint unsigned, auto-increment | PK | |
| `tokenable_type` | varchar(255) | not null | Morph type |
| `tokenable_id` | bigint unsigned | not null | Morph ID |
| `name` | varchar(255) | not null | |
| `token` | varchar(64) | not null, unique | |
| `abilities` | text | nullable | |
| `last_used_at` | timestamp | nullable | |
| `expires_at` | timestamp | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |

Index: morphs (`tokenable_type`, `tokenable_id`). Used by Sanctum; no dedicated application model.

### B.2 PPDB

#### `ppdb_periode`
Migration: `2026_10_09_151803_create_p_p_d_b_periodes_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ppdb_periode_id` | bigint unsigned, auto-increment | PK | |
| `ms_tahun_ajar_id` | bigint unsigned | not null | Migration uses academic year |
| `nama_periode` | varchar(150) | not null | |
| `tanggal_mulai` | date | not null | |
| `tanggal_selesai` | date | not null | |
| `status` | enum(`draft`,`aktif`,`selesai`) | default `draft` | |
| `deskripsi` | text | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |
| `tautan_grup` | varchar(2048) | nullable | Added by `2026_11_04_000000_add_tautan_grup_to_ppdb_periode_table.php` |

Index: `(ms_tahun_ajar_id, status)` named `ppdb_periode_tahun_status_idx`.
Model `PPDBPeriode` declares `ms_jenjang_id` instead of `ms_tahun_ajar_id`; the model's column is **not** migration-defined.

#### `ppdb_gelombang`
Migration: `2026_10_09_151845_create_p_p_d_b_gelombangs_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `id` | bigint unsigned, auto-increment | PK | Migration uses `id`, not `ppdb_gelombang_id` |
| `ppdb_periode_id` | bigint unsigned | not null | |
| `nama_gelombang` | varchar(100) | not null | |
| `tanggal_mulai` | date | not null | |
| `tanggal_selesai` | date | not null | |
| `kuota` | int unsigned | nullable | |
| `biaya_pendaftaran` | decimal(12,2) | default `0` | |
| `status` | enum(`draft`,`aktif`,`selesai`) | default `draft` | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Unique: `(ppdb_periode_id, nama_gelombang)` named `ppdb_gelombang_periode_nama_unique`.
Index: `(ppdb_periode_id, status)` named `ppdb_gelombang_periode_status_idx`.
Model `PPDBGelombang` declares PK `ppdb_gelombang_id`; **mismatch** with the migration's `id`.

#### `ppdb_pendaftaran`
Migration: `2026_10_10_232358_create_ppdb_pendaftaran_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ppdb_pendaftaran_id` | bigint unsigned, auto-increment | PK | |
| `user_id` | bigint unsigned | not null | Migration uses `user_id` |
| `ppdb_periode_id` | bigint unsigned | not null | |
| `ppdb_gelombang_id` | bigint unsigned | not null | |
| `ms_siswa_id` | bigint unsigned | nullable | Declared conversion link |
| `nomor_pendaftaran` | varchar(30) | not null, unique | |
| `current_step` | tinyint unsigned | default `2` | |
| `status` | enum(`draft`,`diterima`,`pengecekan`,`perbaikan`) | default `draft` | Comment mentions other statuses; enum does not |
| `submitted_at` | timestamp | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Indexes: `(user_id, status)`, `(ppdb_periode_id, status)`, `(ppdb_gelombang_id, status)`.
Model `PPDBPendaftaran` uses `ms_pengguna_id` and declares `ppdb_jalur_id`, `submitted_by`, `created_by`, `catatan_perbaikan`, none of which are in this migration. **Unverified** in the live DB.

#### `ppdb_calon_siswa`
Migration: `2026_10_10_232359_create_ppdb_calon_siswa_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ppdb_calon_siswa_id` | bigint unsigned, auto-increment | PK | |
| `ppdb_pendaftaran_id` | bigint unsigned | not null, unique | |
| `nama_siswa` | varchar(150) | not null | |
| `nisn` | varchar(20) | nullable | Indexed |
| `nik` | varchar(20) | nullable | Indexed |
| `tempat_lahir` | varchar(100) | nullable | |
| `tanggal_lahir` | date | nullable | |
| `jenis_kelamin` | enum(`Laki-Laki`,`Perempuan`) | default `Laki-Laki` | |
| `agama` | varchar(30) | nullable | |
| `anak_ke` | tinyint unsigned | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Model `PPDBCalonSiswa` declares additional fields (`nama_panggilan`, `no_kk`, `kewarganegaraan`, family, physical, and social-aid fields) that are not in the migration.

#### `ppdb_orangtua`
Migration: `2026_10_10_232359_create_ppdb_orangtua_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ppdb_orangtua_id` | bigint unsigned, auto-increment | PK | |
| `ppdb_pendaftaran_id` | bigint unsigned | not null, unique | |
| `nama_ayah`, `nik_ayah`, `pekerjaan_ayah`, `telepon_ayah` | varchar | nullable | 150/20/100/25 |
| `nama_ibu`, `nik_ibu`, `pekerjaan_ibu`, `telepon_ibu` | varchar | nullable | 150/20/100/25 |
| `nama_wali` | varchar(150) | nullable | |
| `hubungan_wali` | varchar(50) | nullable | |
| `telepon_wali` | varchar(25) | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Model `PPDBOrangTua` declares birth place/date, special needs, education, and income fields for father/mother/guardian, plus `bersedia_orang_tua_asuh`, which are not in the migration.

#### `ppdb_alamat`
Migration: `2026_10_10_232400_create_ppdb_alamat_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ppdb_alamat_id` | bigint unsigned, auto-increment | PK | |
| `ppdb_pendaftaran_id` | bigint unsigned | not null, unique | |
| `alamat_lengkap` | text | nullable | |
| `provinsi`, `kabupaten`, `kecamatan`, `kelurahan_desa` | varchar(100) | nullable | |
| `kode_pos` | varchar(10) | nullable | |
| `jenis_tempat_tinggal` | varchar(50) | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Model `PPDBAlamat` declares `rt`, `rw`, `jarak_ke_sekolah`, `waktu_tempuh_ke_sekolah`, `moda_transportasi`, which are not in the migration.

#### `ppdb_pendidikan`
Migration: `2026_10_10_232400_create_ppdb_pendidikan_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ppdb_pendidikan_id` | bigint unsigned, auto-increment | PK | |
| `ppdb_pendaftaran_id` | bigint unsigned | not null, unique | |
| `asal_sekolah` | varchar(200) | nullable | |
| `npsn_asal_sekolah` | varchar(20) | nullable | |
| `jenjang_asal` | varchar(50) | nullable | |
| `tahun_lulus` | smallint unsigned | nullable | |
| `nomor_ijazah` | varchar(100) | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Model `PPDBPendidikan` declares `alamat_asal_sekolah`, which is not in the migration.

#### `ppdb_berkas`
Migration: `2026_10_10_233254_create_ppdb_berkas_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ppdb_berkas_id` | bigint unsigned, auto-increment | PK | |
| `ppdb_pendaftaran_id` | bigint unsigned | not null | Not unique; many files per registration |
| `jenis_berkas` | varchar(50) | not null | |
| `nama_file` | varchar(255) | nullable | |
| `path_file` | varchar(500) | nullable | |
| `mime_type` | varchar(100) | nullable | |
| `ukuran_file` | bigint unsigned | nullable | |
| `status_verifikasi` | varchar(20) | default `pending` | |
| `catatan_verifikasi` | text | nullable | |
| `verified_by` | bigint unsigned | nullable | |
| `verified_at` | timestamp | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Indexes: `(ppdb_pendaftaran_id, jenis_berkas)` named `ppdb_berkas_pendaftaran_jenis_index`; `status_verifikasi`.

#### `ppdb_jalur`
Migration: `2026_10_11_115925_create_p_p_d_b_jalurs_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ppdb_jalur_id` | bigint unsigned, auto-increment | PK | |
| `ppdb_periode_id` | bigint unsigned | not null | |
| `kode_jalur` | varchar(30) | not null | |
| `nama_jalur` | varchar(100) | not null | |
| `deskripsi` | text | nullable | |
| `kuota` | int unsigned | nullable | |
| `biaya_pendaftaran` | decimal(12,2) | default `0` | |
| `status` | boolean | default `true` | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Unique: `(ppdb_periode_id, kode_jalur)` named `ppdb_jalur_periode_kode_unique`.
Index: `(ppdb_periode_id, status)`.

#### `ppdb_pertanyaan`
Migration: `2026_10_15_144612_create_p_p_d_b_pertanyaans_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ppdb_pertanyaan_id` | bigint unsigned, auto-increment | PK | |
| `ppdb_periode_id` | bigint unsigned | not null | |
| `pertanyaan` | text | not null | |
| `jenis` | varchar(30) | default `teks` | |
| `opsi_jawaban` | json | nullable | Cast to `array` |
| `wajib_diisi` | boolean | default `true` | |
| `urutan` | int unsigned | default `0` | |
| `status` | varchar(20) | default `aktif` | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Index: `(ppdb_periode_id, status, urutan)` named `ppdb_pertanyaan_periode_status_urutan_index`.

#### `ppdb_jawaban`
Migration: `2026_10_15_144640_create_p_p_d_b_jawabans_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ppdb_jawaban_id` | bigint unsigned, auto-increment | PK | |
| `ppdb_periode_id` | bigint unsigned | not null | |
| `ppdb_pendaftaran_id` | bigint unsigned | not null | |
| `ppdb_pertanyaan_id` | bigint unsigned | not null | |
| `jawaban` | text | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Unique: `(ppdb_pendaftaran_id, ppdb_pertanyaan_id)` named `ppdb_jawaban_pendaftaran_pertanyaan_unique`.
Index: `(ppdb_periode_id, ppdb_pendaftaran_id)` named `ppdb_jawaban_periode_pendaftaran_index`.

### B.3 Academic

#### `ms_periode_akademik`
Migration: `2026_11_17_231115_create_periode_akademiks_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ms_periode_akademik_id` | bigint unsigned, auto-increment | PK | `bigIncrements` |
| `nama_periode` | varchar(100) | not null | |
| `ms_jenjang_id` | bigint unsigned | not null | |
| `ms_tahun_ajar_id` | bigint unsigned | not null | |
| `tanggal_mulai` | date | not null | |
| `tanggal_selesai` | date | nullable | |
| `urutan` | int unsigned | default `1` | |
| `status` | enum(`AKTIF`,`NONAKTIF`) | default `AKTIF` | |
| `deskripsi` | text | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Indexes: `ms_jenjang_id`, `ms_tahun_ajar_id`, `status`.

#### `ms_mata_pelajaran`
Migration: `2026_11_17_231527_create_mata_pelajarans_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ms_mata_pelajaran_id` | bigint unsigned, auto-increment | PK | `bigIncrements` |
| `nama_mapel` | varchar(100) | not null | |
| `kode_mapel` | varchar(20) | nullable | |
| `ms_jenjang_id` | bigint unsigned | not null | |
| `urutan` | int unsigned | default `1` | |
| `deskripsi` | text | nullable | |
| `status` | enum(`AKTIF`,`NONAKTIF`) | default `AKTIF` | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Indexes: `ms_jenjang_id`, `status`.

#### `gurus`
Migration: `2026_11_18_164901_create_gurus_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `id` | bigint unsigned, auto-increment | PK | |
| `created_at`, `updated_at` | timestamp | nullable | |

Orphaned: no `App\Models\Guru`; referenced by `GuruFactory` and `GuruPolicy`. Do not use.

#### `ms_penugasan_guru`
Migrations: `2026_11_18_165002_create_penugasan_gurus_table.php`, then `2026_11_18_203000_align_penugasan_guru_pegawai_column.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ms_penugasan_guru_id` | bigint unsigned, auto-increment | PK | `bigIncrements` |
| `ms_guru_id` -> `ms_pegawai_id` | bigint unsigned | not null | Renamed conditionally if `ms_guru_id` exists and `ms_pegawai_id` does not |
| `ms_kelas_id` | bigint unsigned | not null | |
| `ms_mata_pelajaran_id` | bigint unsigned | not null | |
| `ms_periode_akademik_id` | bigint unsigned | not null | |
| `status` | enum(`AKTIF`,`NONAKTIF`) | default `AKTIF` | |
| `deskripsi` | text | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Indexes: `ms_pegawai_id` (or `ms_guru_id` before rename), `ms_kelas_id`, `ms_mata_pelajaran_id`, `ms_periode_akademik_id`, `status`.
Unique: `(ms_pegawai_id, ms_kelas_id, ms_mata_pelajaran_id, ms_periode_akademik_id)` named `unique_penugasan_guru`.
Model `PenugasanGuru` expects `ms_pegawai_id`; this aligns with the rename.

#### `ej_jadwal_mengajar`
Migration: `2026_11_18_165052_create_jadwal_mengajars_table.php`

| Column | Type | Null/default | Notes |
| --- | --- | --- | --- |
| `ej_jadwal_mengajar_id` | bigint unsigned, auto-increment | PK | `bigIncrements` |
| `ms_penugasan_guru_id` | bigint unsigned | not null | |
| `hari` | enum(`SENIN`,`SELASA`,`RABU`,`KAMIS`,`JUMAT`,`SABTU`,`MINGGU`) | not null | |
| `jam_mulai` | time | not null | |
| `jam_selesai` | time | not null | |
| `tanggal_mulai` | date | nullable | |
| `tanggal_selesai` | date | nullable | |
| `status` | enum(`AKTIF`,`NONAKTIF`) | default `AKTIF` | |
| `deskripsi` | text | nullable | |
| `created_at`, `updated_at` | timestamp | nullable | |
| `deleted_at` | timestamp | nullable | |

Indexes: `ms_penugasan_guru_id`, `hari`, `status`, `(hari, jam_mulai, jam_selesai)` named `idx_jadwal_hari_jam`.

---

## C. Model-Referenced Tables

The tables below are used by Eloquent models. For tables that also have migrations, Section B is authoritative for column types. For tables without migrations, only model declarations are available; the live schema is **Unverified**.

Legend: **SD** = `SoftDeletes`; **Casts** = declared `$casts`.

### C.1 Authentication and access

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `User` | `ms_pengguna` | `ms_pengguna_id` | no | `email_verified_at` datetime, `password` hashed | `nama`, `email`, `password`, `telepon`, `peran`, `current_session` | `belongsToMany Jenjang` via `ms_akses_jenjang`; `hasMany AksesJenjang`; `hasOne Pegawai` via `user_id`; `belongsToMany Kantin` via `ms_akses_kantin`; `telepon` and `current_session` are not migration-defined |
| `AksesJenjang` | `ms_akses_jenjang` | `ms_akses_jenjang_id` | no | none | `ms_jenjang_id`, `ms_pengguna_id` | `belongsTo Jenjang`; pivot for per-user jenjang scoping; used by `PPDBPeriode::scopeForAuthorizedJenjang` and Keuangan `Parameter\Jenjang` |
| `AktifitasPengguna` | `ms_aktifitas_pengguna` | `ms_aktifitas_pengguna_id` | yes | none | `ms_pengguna_id`, `ms_jenjang_id`, `ms_tahun_ajar_id`, `tipe_aksi`, `tipe_tabel`, `id_tabel`, `ip_pengguna`, `perangkat_pengguna`, `deskripsi` | `belongsTo Jenjang`, `TahunAjar`; audit-log shaped |

### C.2 Academic master data

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `Jenjang` | `ms_jenjang` | `ms_jenjang_id` | yes | none | `nama_jenjang`, `alamat`, `urutan`, `status`, `deskripsi`, `slug` | `belongsToMany User` via `ms_akses_jenjang`; `hasMany PeriodeAkademik`, `MataPelajaran` |
| `TahunAjar` | `ms_tahun_ajar` | `ms_tahun_ajar_id` | yes | none | `nama_tahun_ajar`, `urutan`, `tanggal_mulai`, `tanggal_selesai`, `status`, `deskripsi`, `beranda`, `tutup_buku`, `tanggal_tutup_buku`, `ms_pengguna_id` | `belongsTo User`; `hasMany PeriodeAkademik`; `beranda`/`tutup_buku` are likely boolean flags |
| `Kelas` | `ms_kelas` | `ms_kelas_id` | yes | none | `nama_kelas`, `ms_jenjang_id`, `ms_tahun_ajar_id`, `urutan`, `deskripsi`, `status` | `belongsTo TahunAjar`, `Jenjang`; `hasMany PenempatanSiswa`, `PenugasanGuru`; `hasManyThrough TagihanSiswa` via `PenempatanSiswa`; `jumlah_siswa`; `totalEstimasi`/`totalDibayarkan`/`totalKekurangan` join legacy `ms_tagihan` and `dt_transaksi` |
| `MataPelajaran` | `ms_mata_pelajaran` | `ms_mata_pelajaran_id` | yes | none | `nama_mapel`, `kode_mapel`, `ms_jenjang_id`, `urutan`, `deskripsi`, `status` | `belongsTo Jenjang` |
| `PeriodeAkademik` | `ms_periode_akademik` | `ms_periode_akademik_id` | yes | `tanggal_mulai` date, `tanggal_selesai` date | `nama_periode`, `ms_jenjang_id`, `ms_tahun_ajar_id`, `tanggal_mulai`, `tanggal_selesai`, `urutan`, `status`, `deskripsi` | `belongsTo Jenjang`, `TahunAjar`; `hasMany PenugasanGuru` |
| `Pegawai` | `ms_pegawai` | `ms_pegawai_id` | yes | none | `user_id`, `ms_pengguna_id`, `nama_pegawai`, `nip`, `nik`, `jenis_kelamin`, `tempat_lahir`, `tanggal_lahir`, `ms_jabatan_id`, `ms_jenjang_id`, `jenis_pegawai`, `status_kepegawaian`, `tanggal_masuk`, `tanggal_keluar`, `email`, `telepon`, `alamat`, `pin_fingerspot`, `foto`, `deskripsi`, `status` | `belongsTo User` via `ms_pengguna_id`; `belongsTo User` via `user_id` (`akun_pengguna`); `belongsTo Jabatan`, `Jenjang`; `hasOne SaldoTabungan`; `hasMany TransaksiTabungan` via `user_id` with `user_type = 'pegawai'`; `hasOne EduCard`; `hasOne SaldoEduPay`/`hasMany TransaksiEduPay`; `hasMany PenugasanGuru`; aggregate savings/EduPay helpers; teachers are `jenis_pegawai = 'GURU'` |
| `Jabatan` | `ms_jabatan` | `ms_jabatan_id` | yes | none | `nama_jabatan`, `deskripsi` | `hasMany Pegawai`; `jumlah_pegawai` |
| `EduCard` | `ms_educard` | `ms_educard_id` | yes | none | `ms_pengguna_id`, `ms_pegawai_id`, `ms_siswa_id`, `kode_kartu`, `jenis_pemilik`, `status_kartu`, `deskripsi` | `belongsTo Siswa`, `Pegawai`; card can belong to student or employee |
| `PenugasanGuru` | `ms_penugasan_guru` | `ms_penugasan_guru_id` | yes | none | `ms_pegawai_id`, `ms_kelas_id`, `ms_mata_pelajaran_id`, `ms_periode_akademik_id`, `status`, `deskripsi` | `belongsTo Pegawai`, `Kelas`, `MataPelajaran`, `PeriodeAkademik`; `hasMany JadwalMengajar` |
| `JadwalMengajar` | `ej_jadwal_mengajar` | `ej_jadwal_mengajar_id` | yes | `tanggal_mulai` date, `tanggal_selesai` date | `ms_penugasan_guru_id`, `hari`, `jam_mulai`, `jam_selesai`, `tanggal_mulai`, `tanggal_selesai`, `status`, `deskripsi` | No relationships declared; join to `PenugasanGuru` is manual |

### C.3 Students and extracurriculars

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `Siswa` | `ms_siswa` | `ms_siswa_id` | yes | none | `nama_siswa`, `nisn`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `alamat`, `nama_ayah`, `nama_ibu`, `telepon`, `deskripsi` | Mutator uppercases `nama_siswa`; `hasMany PenempatanSiswa`; `hasManyThrough TagihanSiswa` via `PenempatanSiswa`; `hasOne SaldoTabungan`/`hasMany TransaksiTabungan` via `user_id` with `user_type = 'siswa'`; `hasOne EduCard`; `hasOne SaldoEduPay`/`hasMany TransaksiEduPay`; `hasMany TransaksiSmartCanteen`; savings/EduPay aggregate helpers |
| `PenempatanSiswa` | `ms_penempatan_siswa` | `ms_penempatan_siswa_id` | yes | none | `ms_siswa_id`, `ms_kelas_id`, `ms_tahun_ajar_id`, `ms_jenjang_id`, `ms_pengguna_id` | `belongsTo Siswa`, `Kelas`, `TahunAjar`, `Jenjang`, `User`; `hasOne PenempatanEkstrakurikuler`; `hasMany TagihanSiswa`, `TransaksiTagihanSiswa`; `hasManyThrough DetailTransaksiTagihanSiswa` via `TransaksiTagihanSiswa`; aggregate billing helpers; `sudahDinaikkan()` checks next-year placement |
| `Ekstrakurikuler` | `ms_ekstrakurikuler` | `ms_ekstrakurikuler_id` | yes | none | `ms_jenjang_id`, `ms_tahun_ajar_id`, `nama_ekstrakurikuler`, `deskripsi`, `biaya`, `kuota` | `belongsTo Jenjang`, `TahunAjar`; `hasMany PenempatanEkstrakurikuler`; `total_penempatan_siswa()`, `kuota_tersedia()` |
| `PenempatanEkstrakurikuler` | `ms_penempatan_ekstrakurikuler` | `ms_penempatan_ekstrakurikuler_id` | yes | none | `ms_ekstrakurikuler_id`, `ms_penempatan_siswa_id` | `belongsTo PenempatanSiswa`, `Ekstrakurikuler` |

### C.4 Billing and payments

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `JenisTagihanSiswa` | `ms_jenis_tagihan_siswa` | `ms_jenis_tagihan_siswa_id` | no | none | `ms_tahun_ajar_id`, `ms_jenjang_id`, `ms_kategori_tagihan_siswa_id`, `nama_jenis_tagihan_siswa`, `tanggal_jatuh_tempo`, `deskripsi`, `cicilan_status` | `belongsTo KategoriTagihanSiswa`, `TahunAjar`, `Jenjang`; `hasMany TagihanSiswa`; `hasManyThrough DetailTransaksiTagihanSiswa` via `TagihanSiswa`; billing aggregate helpers |
| `KategoriTagihanSiswa` | `ms_kategori_tagihan_siswa` | `ms_kategori_tagihan_siswa_id` | yes | none | `ms_tahun_ajar_id`, `ms_jenjang_id`, `nama_kategori_tagihan_siswa`, `urutan`, `deskripsi` | `belongsTo TahunAjar`, `Jenjang`; `hasMany JenisTagihanSiswa`; `hasManyThrough TagihanSiswa`; `total_tagihan_siswa()`, `total_dibayarkan()` |
| `TagihanSiswa` | `ms_tagihan_siswa` | `ms_tagihan_siswa_id` | yes | none | `ms_penempatan_siswa_id`, `ms_jenis_tagihan_siswa_id`, `ms_pengguna_id`, `jumlah_tagihan_siswa`, `status`, `deskripsi`, `akuntansi_jurnal_id` | `belongsTo PenempatanSiswa`, `JenisTagihanSiswa`, `User`, `AkuntansiJurnal`; `hasMany DetailTransaksiTagihanSiswa`; `jumlah_sudah_dibayar()` excludes `dibatalkan`; `jumlah_kekurangan()`; `scopeOrderByKategori` joins legacy `ms_kategori_tagihan` |
| `KeranjangTagihanSiswa` | `ms_keranjang_tagihan_siswa` | `ms_keranjang_tagihan_siswa_id` | no | none | `ms_penempatan_siswa_id`, `ms_tagihan_siswa_id`, `ms_pengguna_id`, `jumlah_bayar`, `tanggal_dibayar`, `status`, `deskripsi` | `belongsTo PenempatanSiswa`, `TagihanSiswa`, `User`; `hasMany DetailTransaksiTagihanSiswa` by `ms_tagihan_siswa_id`; payment-cart style |
| `DetailTransaksiTagihanSiswa` | `dt_transaksi_tagihan_siswa` | `dt_transaksi_tagihan_siswa_id` | yes | none | `ms_transaksi_tagihan_siswa_id`, `ms_tagihan_siswa_id`, `jumlah_bayar`, `deskripsi`, `status_transaksi` | `belongsTo TransaksiTagihanSiswa`, `TagihanSiswa`; `nama_jenis_tagihan_siswa()` |
| `TransaksiTagihanSiswa` | `ms_transaksi_tagihan_siswa` | `ms_transaksi_tagihan_siswa_id` | yes | none | `ms_penempatan_siswa_id`, `ms_pengguna_id`, `tanggal_transaksi`, `metode_pembayaran`, `deskripsi`, `akuntansi_jurnal_id`, `akuntansi_jurnal_reversal_id`, `status_transaksi` | `belongsTo PenempatanSiswa`, `User`, `AkuntansiJurnal` (normal and reversal); `hasMany DetailTransaksiTagihanSiswa` |
| `SuratTagihanSiswa` | `ms_surat_tagihan_siswa` | `ms_surat_tagihan_siswa_id` | no | none | `ms_jenjang_id`, `foto_kop`, `tempat_tanggal`, `nomor_surat`, `lampiran`, `hal`, `salam_pembuka`, `pembuka`, `isi`, `rincian`, `panduan`, `instruksi_1..5`, `penutup`, `salam_penutup`, `jabatan`, `tanda_tangan`, `nama_petugas`, `nomor_petugas`, `catatan_1..3` | No relationships; letter template per jenjang |
| `KuitansiPembayaranTagihanSiswa` | `ms_kuitansi_pembayaran_tagihan_siswa` | `ms_kuitansi_pembayaran_tagihan_siswa_id` | no | none | `ms_jenjang_id`, `logo`, `nama_institusi`, `alamat`, `kontak`, `judul`, `pesan`, `tempat` | No relationships; receipt template per jenjang |

### C.5 Savings and EduPay

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `SaldoTabungan` | `ms_saldo_tabungan` | `ms_saldo_tabungan_id` | no | none | `user_id`, `user_type`, `saldo_tabungan` | `scopeSiswa`, `scopePegawai`; `getSaldo($userId,$userType)` uses `firstOrCreate` |
| `TransaksiTabungan` | `ms_transaksi_tabungan` | `ms_transaksi_tabungan_id` | yes | `tanggal` datetime | `user_id`, `user_type`, `ms_penempatan_siswa_id`, `ms_pengguna_id`, `jenis_transaksi`, `nominal`, `tanggal`, `deskripsi`, `akuntansi_jurnal_id`, `akuntansi_jurnal_reversal_id`, `status_transaksi` | `belongsTo User`, `Siswa` (via `user_id`), `PenempatanSiswa`, `Pegawai` (via `user_id`), `AkuntansiJurnal` (normal and reversal); polymorphic by `user_type` but no morph type |
| `KuitansiTransaksiTabungan` | `ms_kuitansi_transaksi_tabungan` | `ms_kuitansi_transaksi_tabungan_id` | no | none | `ms_jenjang_id`, `logo`, `nama_institusi`, `alamat`, `kontak`, `judul`, `pesan`, `tempat` | No relationships |
| `SaldoEduPay` | `ms_saldo_edupay` | `ms_saldo_edupay_id` | no | none | `user_id`, `user_type`, `saldo_edupay` | `scopeSiswa`, `scopePegawai`; `getSaldo(...)` |
| `TransaksiEduPay` | `ms_transaksi_edupay` | `ms_transaksi_edupay_id` | yes | none | `user_type`, `user_id`, `ms_penempatan_siswa_id`, `ms_pengguna_id`, `jenis_transaksi`, `nominal`, `tanggal`, `deskripsi`, `akuntansi_jurnal_id`, `akuntansi_jurnal_reversal_id`, `status_transaksi` | `belongsTo User`, `Siswa` (via `user_id`), `PenempatanSiswa`, `Pegawai` (via `user_id`), `AkuntansiJurnal` (normal and reversal) |
| `KuitansiTransaksiEduPay` | `ms_kuitansi_transaksi_edupay` | `ms_kuitansi_transaksi_edupay_id` | no | none | `ms_jenjang_id`, `logo`, `nama_institusi`, `alamat`, `kontak`, `judul`, `pesan`, `tempat` | No relationships |

### C.6 Accounting and other income/expense

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `AkuntansiJurnal` | `akuntansi_jurnal` | `akuntansi_jurnal_id` | yes | none | `nomor_jurnal`, `tanggal_transaksi`, `deskripsi`, `ms_pengguna_id`, `ms_tahun_ajaran_id`, `ms_jenjang_id`, `ms_departemen_id` | `belongsTo User`; `hasMany AkuntansiJurnalDetail`; static `generateNomorJurnal()` uses `JV{ymd}-####` with `lockForUpdate` inside a transaction |
| `AkuntansiJurnalDetail` | `akuntansi_jurnal_detail` | `akuntansi_jurnal_detail_id` | yes | none | `akuntansi_jurnal_id`, `kode_rekening`, `posisi`, `nominal` | `belongsTo AkuntansiJurnal`, `AkuntansiRekening` via `kode_rekening`; `belongsTo User` via `ms_pengguna_id` (column not fillable); `hasOne TransaksiTagihanSiswa`, `TagihanSiswa`; `totalPendapatan()` references nonexistent `ms_akuntansi_rekening` |
| `AkuntansiRekening` | `akuntansi_rekening` | `akuntansi_rekening_id` | yes | none | `akuntansi_rekening_id`, `akuntansi_kelompok_rekening_id`, `kode_rekening`, `nama_rekening`, `posisi_normal`, `deskripsi` | `belongsTo AkuntansiKelompokRekening`; `hasMany AkuntansiJurnalDetail`; `saldo_akhir()` queries `is_canceled` (not in fillable) |
| `AkuntansiKelompokRekening` | `akuntansi_kelompok_rekening` | `akuntansi_kelompok_rekening_id` | yes | none | `akuntansi_kelompok_rekening_id`, `nama_kelompok_rekening`, `deskripsi` | `hasMany AkuntansiRekening` |
| `TransaksiPendapatanLainnya` | `transaksi_pendapatan_lainnya` | `transaksi_pendapatan_lainnya_id` | yes | none | `ms_pengguna_id`, `ms_jenjang_id`, `kode_rekening`, `nominal`, `metode_pembayaran`, `tanggal`, `deskripsi`, `akuntansi_jurnal_id`, `akuntansi_jurnal_reversal_id`, `status_transaksi` | `belongsTo User`, `Jenjang`, `AkuntansiRekening` via `kode_rekening`, `AkuntansiJurnal` (normal and reversal) |
| `TransaksiPengeluaran` | `transaksi_pengeluaran` | `transaksi_pengeluaran_id` | yes | none | `ms_pengguna_id`, `ms_jenjang_id`, `kode_rekening`, `nominal`, `metode_pembayaran`, `tanggal`, `deskripsi`, `akuntansi_jurnal_id`, `akuntansi_jurnal_reversal_id`, `status_transaksi` | Same relationships as income; `ms_tahun_ajar_id` is commented out |

### C.7 WhatsApp templates

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `WhatsAppTagihanSiswa` | `ms_whatsapp_tagihan_siswa` | `ms_whatsapp_tagihan_siswa_id` | yes | none | `ms_jenjang_id`, `judul`, `salam_pembuka`, `kalimat_pembuka`, `kalimat_penutup`, `salam_penutup` | No relationships; message template per jenjang |
| `WhatsAppPembayaranTagihanSiswa` | `ms_whatsapp_pembayaran_tagihan_siswa` | `ms_whatsapp_pembayaran_tagihan_siswa_id` | yes | none | same as above | No relationships |
| `WhatsAppTransaksiTabungan` | `ms_whatsapp_transaksi_tabungan` | `ms_whatsapp_transaksi_tabungan_id` | yes | none | same as above | No relationships |
| `WhatsAppTransaksiEduPay` | `ms_whatsapp_transaksi_edupay` | `ms_whatsapp_transaksi_edupay_id` | yes | none | same as above | No relationships |

No outbound WhatsApp client was found in the inspected code; these are template stores. **Unverified** whether they are used at runtime.

### C.8 PPDB

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `PPDBPeriode` | `ppdb_periode` | `ppdb_periode_id` | yes | dates, `tautan_grup` string | `ms_jenjang_id`, `nama_periode`, `tanggal_mulai`, `tanggal_selesai`, `tautan_grup`, `status`, `deskripsi` | `belongsTo Jenjang`; `hasMany PPDBGelombang`; `scopeForAuthorizedJenjang($userId)` filters via `ms_akses_jenjang`; migration uses `ms_tahun_ajar_id`, model uses `ms_jenjang_id` |
| `PPDBGelombang` | `ppdb_gelombang` | `ppdb_gelombang_id` | yes | dates, `kuota` int, `biaya_pendaftaran` decimal:2 | `ppdb_periode_id`, `nama_gelombang`, `tanggal_mulai`, `tanggal_selesai`, `kuota`, `biaya_pendaftaran`, `status` | `belongsTo PPDBPeriode`; migration PK is `id` |
| `PPDBPendaftaran` | `ppdb_pendaftaran` | `ppdb_pendaftaran_id` | yes | `current_step` int, `submitted_at` datetime, `deleted_at` datetime | `ms_pengguna_id`, `ppdb_periode_id`, `ppdb_gelombang_id`, `ppdb_jalur_id`, `ms_siswa_id`, `nomor_pendaftaran`, `current_step`, `status`, `submitted_at`, `submitted_by`, `created_by`, `catatan_perbaikan` | `belongsTo User` (`ms_pengguna`), `User` (`created_by`), `User` (`submitted_by`), `PPDBPeriode`, `PPDBGelombang`, `PPDBJalur`, `Siswa`; `hasOne` calon/orangtua/alamat/pendidikan; `hasMany` berkas/jawaban; migration lacks `ms_pengguna_id`, `ppdb_jalur_id`, `submitted_by`, `created_by`, `catatan_perbaikan` |
| `PPDBCalonSiswa` | `ppdb_calon_siswa` | `ppdb_calon_siswa_id` | yes | `tanggal_lahir` date, `anak_ke` int | `ppdb_pendaftaran_id`, `nama_siswa`, `nama_panggilan`, `nisn`, `no_kk`, `nik`, `tempat_lahir`, `tanggal_lahir`, `jenis_kelamin`, `agama`, `kewarganegaraan`, `anak_ke`, `jumlah_saudara_kandung`, `jumlah_saudara_tiri`, `status_yatim_piatu`, `cita_cita`, `hobi`, `berkebutuhan_khusus`, `penyakit`, `alergi`, `tinggi_badan`, `berat_badan`, `ukuran_baju`, `penerima_bantuan_sosial`, `nomor_bantuan_sosial` | `belongsTo PPDBPendaftaran`; uppercases `nama_siswa`; many extra columns vs migration |
| `PPDBOrangTua` | `ppdb_orangtua` | `ppdb_orangtua_id` | yes | `ppdb_pendaftaran_id` int, `deleted_at` datetime, `tanggal_lahir_*` date | `ppdb_pendaftaran_id`, father/mother/guardian identity, birth, special-needs, education, occupation, income, phone fields, `bersedia_orang_tua_asuh` | `belongsTo PPDBPendaftaran`; uppercases father/mother/guardian names; many extra columns vs migration |
| `PPDBAlamat` | `ppdb_alamat` | `ppdb_alamat_id` | yes | `ppdb_pendaftaran_id` int, `deleted_at` datetime | `ppdb_pendaftaran_id`, `alamat_lengkap`, `provinsi`, `kabupaten`, `kecamatan`, `kelurahan_desa`, `rt`, `rw`, `kode_pos`, `jenis_tempat_tinggal`, `jarak_ke_sekolah`, `waktu_tempuh_ke_sekolah`, `moda_transportasi` | `belongsTo PPDBPendaftaran`; `rt`/`rw`/travel fields are not migration-defined |
| `PPDBPendidikan` | `ppdb_pendidikan` | `ppdb_pendidikan_id` | yes | `ppdb_pendaftaran_id` int, `tahun_lulus` int, `deleted_at` datetime | `ppdb_pendaftaran_id`, `asal_sekolah`, `alamat_asal_sekolah`, `npsn_asal_sekolah`, `jenjang_asal`, `tahun_lulus`, `nomor_ijazah` | `belongsTo PPDBPendaftaran`; `alamat_asal_sekolah` is not migration-defined |
| `PPDBBerkas` | `ppdb_berkas` | `ppdb_berkas_id` | yes | `ppdb_pendaftaran_id`, `ukuran_file`, `verified_by` int; `verified_at`, `deleted_at` datetime | `ppdb_pendaftaran_id`, `jenis_berkas`, `nama_file`, `path_file`, `mime_type`, `ukuran_file`, `status_verifikasi`, `catatan_verifikasi`, `verified_by`, `verified_at` | `belongsTo PPDBPendaftaran`; `verifier()` uses owner key `id` on `User`, whose PK is `ms_pengguna_id` (likely broken) |
| `PPDBJalur` | `ppdb_jalur` | `ppdb_jalur_id` | yes | `kuota` int, `biaya_pendaftaran` decimal:2, `deleted_at` datetime | `ppdb_periode_id`, `kode_jalur`, `nama_jalur`, `deskripsi`, `kuota`, `biaya_pendaftaran`, `status` | `belongsTo PPDBPeriode`; `hasMany PPDBPendaftaran` via `ppdb_jalur_id` (column not in migration) |
| `PPDBPertanyaan` | `ppdb_pertanyaan` | `ppdb_pertanyaan_id` | yes | `opsi_jawaban` array, `wajib_diisi` bool, `urutan` int | `ppdb_periode_id`, `pertanyaan`, `jenis`, `opsi_jawaban`, `wajib_diisi`, `urutan`, `status` | `belongsTo PPDBPeriode`; `hasMany PPDBJawaban` |
| `PPDBJawaban` | `ppdb_jawaban` | `ppdb_jawaban_id` | yes | none | `ppdb_periode_id`, `ppdb_pendaftaran_id`, `ppdb_pertanyaan_id`, `jawaban` | `belongsTo PPDBPeriode`, `PPDBPendaftaran`, `PPDBPertanyaan` |

### C.9 SmartCanteen (dormant)

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `Kantin` | `ms_kantin` | `ms_kantin_id` | no | none | `nama_kantin`, `deskripsi` | `belongsToMany User` via `ms_akses_kantin`; imports `SoftDeletes` but does not use it |
| `AksesKantin` | `ms_akses_kantin` | `ms_akses_kantin_id` | no | none | `ms_kantin_id`, `ms_pengguna_id`, `deskripsi` | `belongsTo Kantin`, `User` |
| `KategoriProdukSmartCanteen` | `ms_kategori_produk_kantin` | `ms_kategori_produk_kantin_id` | yes | none | `ms_kantin_id`, `nama_kategori_produk_kantin`, `icon`, `deskripsi` | `belongsTo Kantin`; `hasMany ProdukSmartCanteen`; `belongsTo User` (column not in fillable) |
| `ProdukSmartCanteen` | `ms_produk_kantin` | `ms_produk_kantin_id` | yes | none | `ms_kantin_id`, `ms_pengguna_id`, `ms_kategori_produk_kantin_id`, `nama_produk_kantin`, `harga`, `stok`, `satuan`, `status`, `deskripsi`, `icon`, `icon_color` | `belongsTo Kantin`, `KategoriProdukSmartCanteen`, `User` |
| `KeranjangSmartCanteen` | `ms_keranjang_kantin` | `ms_keranjang_kantin_id` | no | none | `user_type`, `user_id`, `ms_produk_kantin_id`, `ms_pengguna_id`, `jumlah_produk` | `belongsTo Siswa`/`Pegawai` via `user_id` with `user_type` filter; `belongsTo ProdukSmartCanteen` |
| `TransaksiSmartCanteen` | `ms_transaksi_kantin` | `ms_transaksi_kantin_id` | yes | none | `user_type`, `user_id`, `ms_jenjang_id`, `ms_pengguna_id`, `ms_kantin_id`, `tanggal_transaksi`, `total_transaksi`, `metode_pembayaran`, `deskripsi`, `akuntansi_jurnal_id`, `akuntansi_jurnal_reversal_id`, `status_settlement`, `ms_settlement_kantin_id`, `status_transaksi` | `belongsTo Pegawai`/`Siswa` via `user_id`, `User`, `AkuntansiJurnal` (normal and reversal), `Jenjang`; `hasMany DetailTransaksiSmartCanteen` |
| `DetailTransaksiSmartCanteen` | `dt_transaksi_kantin` | `dt_transaksi_kantin_id` | yes | none | `ms_transaksi_kantin_id`, `ms_produk_kantin_id`, `jumlah_produk`, `jumlah_bayar`, `deskripsi`, `status_transaksi` | `belongsTo TransaksiSmartCanteen`, `ProdukSmartCanteen` |
| `SettlementSmartCanteen` | `ms_settlement_kantin` | `ms_settlement_kantin_id` | no | none | `ms_pengguna_id`, `ms_kantin_id`, `ms_jenjang_id`, `tanggal_settlement`, `total_settlement`, `metode_pembayaran`, `deskripsi`, `akuntansi_jurnal_id` | `hasMany TransaksiSmartCanteen`; `belongsTo Kantin`, `User`, `AkuntansiJurnal`, `Jenjang` |

### C.10 KoperasiPintar (dormant)

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `KategoriProdukKoperasi` | `ms_kategori_produk_koperasi` | `ms_kategori_produk_koperasi_id` | yes | none | `ms_jenjang_id`, `nama_kategori_produk_koperasi`, `status_kategori_produk_koperasi`, `deskripsi` | No relationships |
| `ProdukKoperasi` | `ms_produk_koperasi` | `ms_produk_koperasi_id` | yes | none | `ms_jenjang_id`, `ms_pengguna_id`, `ms_kategori_produk_koperasi_id`, `kode_produk_koperasi`, `nama_produk_koperasi`, `satuan`, `stok`, `harga_beli`, `harga_jual`, `status_produk_koperasi`, `deskripsi` | `belongsTo Jenjang`, `KategoriProdukKoperasi`; `keuntungan()` = `harga_jual - harga_beli` |
| `SupplierKoperasi` | `ms_supplier_koperasi` | `ms_supplier_koperasi_id` | yes | none | `nama_supplier_koperasi`, `alamat`, `telepon`, `email`, `npwp`, `deskripsi` | No relationships |
| `TransaksiPembelianProdukKoperasi` (file) / class `TransaksiPengembalianProdukKoperasi` | `ms_transaksi_pembelian_produk_koperasi` | `ms_transaksi_pembelian_produk_koperasi_id` | yes | none | `ms_supplier_koperasi_id`, `ms_pengguna_id`, `tanggal_pembelian`, `total_pembelian`, `metode_pembayaran`, `deskripsi`, `akuntansi_jurnal_detail_debit_id`, `akuntansi_jurnal_detail_kredit_id` | No relationships; class/file name mismatch |
| `DetailTransaksiPembelianProdukKoperasi` | `dt_pembelian_produk_koperasi` | `dt_pembelian_produk_koperasi_id` | no | none | `ms_transaksi_pembelian_produk_koperasi_id`, `ms_produk_koperasi_id`, `jumlah`, `harga_beli`, `subtotal` | No relationships |
| `TransaksiPengembalianProdukKoperasi` (file) / class `TransaksiPengembalianBarangKoperasi` | `ms_transaksi_pengembalian_produk_koperasi` | `ms_transaksi_pengembalian_produk_koperasi_id` | yes | none | `ms_transaksi_pengembalian_produk_koperasi_id`, `ms_pengguna_id`, `tanggal_return`, `total_return`, `deskripsi`, `akuntansi_jurnal_detail_debit_id`, `akuntansi_jurnal_detail_kredit_id` | No relationships; class/file name mismatch; PK is also in fillable |
| `DetailTransaksiPengembalianProdukKoperasi` | `dt_pengembalian_produk_koperasi` | `dt_pengembalian_produk_koperasi_id` | no | none | `ms_transaksi_pengembalian_produk_koperasi_id`, `ms_produk_koperasi_id`, `jumlah`, `harga_beli`, `subtotal` | No relationships |
| `TransaksiPenjualanProdukKoperasi` | `ms_transaksi_penjualan_produk_koperasi` | `ms_transaksi_penjualan_produk_koperasi_id` | yes | none | `user_type`, `user_id`, `ms_penempatan_siswa_id`, `ms_pengguna_id`, `tanggal_transaksi`, `total_transaksi`, `metode_pembayaran`, `deskripsi`, `akuntansi_jurnal_detail_debit_id`, `akuntansi_jurnal_detail_kredit_id` | No relationships |
| `DetailTransaksiPenjualanProdukKoperasi` | `dt_penjualan_produk_koperasi` | `dt_penjualan_produk_koperasi_id` | no | none | `ms_transaksi_penjualan_produk_koperasi_id`, `ms_produk_koperasi_id`, `jumlah_produk`, `jumlah_bayar`, `deskripsi` | No relationships |

### C.11 SmartPass and FingerSpot (dormant)

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `PresensiPegawai` | `ms_presensi_pegawai` | `ms_presensi_pegawai_id` | yes | none | `ms_pegawai_id`, `ms_pengguna_id`, `kode_kartu`, `tanggal`, `jam_masuk`, `jam_pulang`, `status_masuk`, `status_pulang`, `deskripsi` | `belongsTo Pegawai`, `User` |
| `PresensiSiswa` | `ms_absensi_siswa` | `ms_absensi_siswa_id` | yes | none | `ms_siswa_id`, `ms_penempatan_siswa_id`, `ms_pengguna_id`, `kode_kartu`, `tanggal`, `jam_masuk`, `jam_pulang`, `status_masuk`, `status_pulang`, `deskripsi` | `belongsTo Siswa`, `PenempatanSiswa`, `User` |
| `KonfigPresensiPegawai` | `ms_absensi_pegawai_konfig` | `ms_absensi_pegawai_konfig_id` | no | none | `ms_jenjang_id`, `hari`, `jam_masuk_awal`, `jam_masuk_akhir`, `jam_pulang_awal`, `jam_pulang_akhir`, `toleransi_masuk_menit`, `toleransi_pulang_menit`, `status_aktif`, `deskripsi` | `belongsTo Jenjang`; `scopeAktifHari($hari)` |
| `KonfigPresensiSiswa` | `ms_absensi_siswa_konfig` | `ms_absensi_siswa_konfig_id` | no | none | same as employee config | `belongsTo Jenjang`; `scopeAktifHari($hari)` |
| `FingerSpot\PresensiPegawaiLog` | `presensi_pegawai_log` | none declared | no | `scan_time` datetime, `raw` array | `type`, `cloud_id`, `pin`, `scan_time`, `verify_method`, `status_scan`, `raw` | `belongsTo Pegawai` via `pin` -> `pin_fingerspot`; written by the unauthenticated webhook (KI-07) |

### C.12 Other

| Model | Table | PK | SD | Casts | Fillable | Relationships / behavior |
| --- | --- | --- | --- | --- | --- | --- |
| `CORE\Departemen` | none declared | none | no | none | none | Empty model in namespace `App\Models\CORE`; no table or primary key. Do not assume a `departemen` table exists |

---

## D. Relationships and Data Flow

### Identity and access
- `ms_pengguna` is the identity root. `User` is the only authentication model.
- `ms_akses_jenjang` is a pivot between `ms_pengguna` and `ms_jenjang`; it is the only per-user data-scoping mechanism.
- `Pegawai.user_id` and `Pegawai.ms_pengguna_id` are two separate links to `User`. `User::ms_pegawai()` uses `user_id`; `Pegawai::ms_pengguna()` uses `ms_pengguna_id`. This dual linkage is a source of ambiguity.

### Student lifecycle
```
ppdb_pendaftaran (1) ── (1) ppdb_calon_siswa
                    ├── (1) ppdb_orangtua
                    ├── (1) ppdb_alamat
                    ├── (1) ppdb_pendidikan
                    ├── (n) ppdb_berkas
                    └── (n) ppdb_jawaban

ppdb_pendaftaran.ms_siswa_id ──> ms_siswa.ms_siswa_id   (nullable, no automatic conversion found)

ms_siswa (1) ── (n) ms_penempatan_siswa ── (1) ms_kelas
                                      ├── (1) ms_tahun_ajar
                                      └── (1) ms_jenjang

ms_penempatan_siswa (1) ── (n) ms_tagihan_siswa ── (n) dt_transaksi_tagihan_siswa
                      └── (n) ms_transaksi_tagihan_siswa ── (n) dt_transaksi_tagihan_siswa
```

### Billing and savings
- A bill (`ms_tagihan_siswa`) belongs to a placement (`ms_penempatan_siswa`) and a bill type (`ms_jenis_tagihan_siswa`).
- Payments are recorded in `ms_transaksi_tagihan_siswa` (header) and `dt_transaksi_tagihan_siswa` (lines).
- Savings and EduPay use a polymorphic-by-convention pattern: `user_id` + `user_type` (`siswa`/`pegawai`) instead of Laravel morph columns. `Siswa` and `Pegawai` define relationships with the `user_type` filter.
- Accounting journals are linked from transactions through `akuntansi_jurnal_id` and `akuntansi_jurnal_reversal_id`.
- `akuntansi_jurnal_detail.kode_rekening` links to `akuntansi_rekening.kode_rekening` (business key, not the numeric PK).

### Academic
- `ms_penugasan_guru` links a teacher (`ms_pegawai`), a class (`ms_kelas`), a subject (`ms_mata_pelajaran`), and a semester (`ms_periode_akademik`).
- `ej_jadwal_mengajar` links to a teaching assignment and stores day/time.
- Extracurricular placement links a student placement (`ms_penempatan_siswa`) to an extracurricular (`ms_ekstrakurikuler`).

### Dormant modules
- SmartCanteen: `ms_transaksi_kantin` -> `dt_transaksi_kantin`; `ms_settlement_kantin` -> many transactions; journals via `AccountingService`.
- KoperasiPintar: purchase/return/sales headers -> detail tables; no declared relationships.
- SmartPass: attendance rows link to `ms_pegawai`/`ms_siswa`; `presensi_pegawai_log` links by `pin` to `ms_pegawai.pin_fingerspot`.

### Cross-module joins
- Keuangan joins Akademik data through `ms_penempatan_siswa`, `ms_siswa`, `ms_kelas`, `ms_pegawai`, `ms_jenjang`, `ms_tahun_ajar`.
- PPDB joins `ms_jenjang`, `ms_tahun_ajar`, `ms_pengguna`, `ms_akses_jenjang`.
- SmartCanteen joins `ms_siswa`/`ms_pegawai` and `akuntansi_jurnal`.

---

## E. Inferred and Unverified Schema

Everything in this section is **not** confirmed by a migration. It is derived from model `$fillable`, query code, or comments, and must be verified against the live database before relying on it.

### E.1 Model columns absent from migrations

| Table | Columns referenced by model/code but absent from migration |
| --- | --- |
| `ms_pengguna` | `telepon`, `current_session` |
| `ppdb_periode` | `ms_jenjang_id` (model) vs `ms_tahun_ajar_id` (migration) |
| `ppdb_pendaftaran` | `ms_pengguna_id`, `ppdb_jalur_id`, `submitted_by`, `created_by`, `catatan_perbaikan` |
| `ppdb_calon_siswa` | `nama_panggilan`, `no_kk`, `kewarganegaraan`, `jumlah_saudara_kandung`, `jumlah_saudara_tiri`, `status_yatim_piatu`, `cita_cita`, `hobi`, `berkebutuhan_khusus`, `penyakit`, `alergi`, `tinggi_badan`, `berat_badan`, `ukuran_baju`, `penerima_bantuan_sosial`, `nomor_bantuan_sosial` |
| `ppdb_orangtua` | `tempat_lahir_*`, `tanggal_lahir_*`, `berkebutuhan_khusus_*`, `pendidikan_*`, `penghasilan_*`, `nik_wali`, `pekerjaan_wali`, `penghasilan_wali`, `bersedia_orang_tua_asuh` |
| `ppdb_alamat` | `rt`, `rw`, `jarak_ke_sekolah`, `waktu_tempuh_ke_sekolah`, `moda_transportasi` |
| `ppdb_pendidikan` | `alamat_asal_sekolah` |
| `akuntansi_jurnal` | `ms_tahun_ajaran_id` (note spelling: `ajaran`, not `ajar`) |
| `akuntansi_jurnal_detail` | `ms_pengguna_id` (relationship), `is_canceled` (queried by `saldo_akhir`) |
| `ms_siswa` | No extra fillable, but live columns beyond the migration are **Unverified** |
| `ms_pegawai` | No migration exists; all columns are model-inferred |
| `ms_jenjang` | No migration; all columns model-inferred |
| `ms_tahun_ajar` | No migration; all columns model-inferred |
| `ms_kelas` | No migration; all columns model-inferred |

### E.2 Column-name and PK mismatches

| Issue | Evidence | Risk |
| --- | --- | --- |
| `ppdb_gelombang` PK | Migration creates `id`; model declares `ppdb_gelombang_id` | Model lookups/relations can fail or use the wrong column |
| `ppdb_pendaftaran` owner | Migration has `user_id`; model uses `ms_pengguna_id` | Login-to-application joins may fail |
| `ppdb_pendaftaran` pathway | Model declares `ppdb_jalur_id`; migration has no such column | Pathway feature may rely on an unmigrated column |
| `ppdb_periode` scope | Migration has `ms_tahun_ajar_id`; model uses `ms_jenjang_id` | Jenjang scoping may fail |
| `ppdb_berkas.verifier()` | Uses owner key `id` on `User` (PK `ms_pengguna_id`) | Verifier relationship likely returns null |
| `AkuntansiJurnalDetail::totalPendapatan()` | References `ms_akuntansi_rekening`; actual relation is `akuntansi_rekening` | Method likely throws |
| `TagihanSiswa::scopeOrderByKategori` | Joins `ms_kategori_tagihan`; actual table is `ms_kategori_tagihan_siswa` | Scope likely fails |
| `Kelas::totalEstimasi()` / `totalDibayarkan()` | Joins `ms_tagihan` and `dt_transaksi`; current tables are `ms_tagihan_siswa` and `dt_transaksi_tagihan_siswa` | Legacy methods likely fail |
| `Pegawai` dual user link | `user_id` and `ms_pengguna_id` both exist in `$fillable` | Ambiguous account linkage |
| KoperasiPintar class names | File `TransaksiPembelianProdukKoperasi.php` declares `TransaksiPengembalianProdukKoperasi`; file `TransaksiPengembalianProdukKoperasi.php` declares `TransaksiPengembalianBarangKoperasi` | Autoloading and relationships are fragile |

### E.3 Status and enum conventions

| Table | Declared enum | Code usage / comments |
| --- | --- | --- |
| `ppdb_periode` | `draft`, `aktif`, `selesai` | Consistent |
| `ppdb_gelombang` | `draft`, `aktif`, `selesai` | Consistent |
| `ppdb_pendaftaran` | `draft`, `diterima`, `pengecekan`, `perbaikan` | Migration comment lists `submitted`, `under_review`, `revision`, `accepted`, `rejected`; code may use either set. **Unverified** |
| `ppdb_jalur` | boolean `status` | `1`/`0` active flag |
| `ppdb_pertanyaan` | string `status` default `aktif` | Likely `aktif`/`nonaktif` |
| `ms_periode_akademik` | `AKTIF`, `NONAKTIF` | Uppercase |
| `ms_mata_pelajaran` | `AKTIF`, `NONAKTIF` | Uppercase |
| `ms_penugasan_guru` | `AKTIF`, `NONAKTIF` | Uppercase |
| `ej_jadwal_mengajar` | `AKTIF`, `NONAKTIF` | Uppercase |
| `ms_tagihan_siswa` | No migration | Code comments mention `Belum Dibayar`, `Masih Dicicil`, `Lunas`; `DetailTransaksiTagihanSiswa.status_transaksi` uses `dibatalkan` |
| `ms_transaksi_*` | No migration | `status_transaksi` values include `dibatalkan`; other values **Unverified** |
| `ms_transaksi_edupay` | No migration | `jenis_transaksi` includes `topup tunai`, `topup online`, `pengembalian dana`, `penarikan`, `pembayaran`, `kantin` |
| `ms_transaksi_tabungan` | No migration | `jenis_transaksi` includes `setoran`, `penarikan` |

### E.4 Missing migrations

No migrations exist for these model-referenced tables:

- Student/academic: `ms_siswa`, `ms_pegawai`, `ms_jenjang`, `ms_tahun_ajar`, `ms_kelas`, `ms_ekstrakurikuler`, `ms_penempatan_siswa`, `ms_penempatan_ekstrakurikuler`, `ms_jabatan`, `ms_educard`, `ms_akses_jenjang`, `ms_aktifitas_pengguna`.
- Finance: `ms_jenis_tagihan_siswa`, `ms_kategori_tagihan_siswa`, `ms_tagihan_siswa`, `ms_keranjang_tagihan_siswa`, `dt_transaksi_tagihan_siswa`, `ms_transaksi_tagihan_siswa`, `ms_surat_tagihan_siswa`, `ms_kuitansi_pembayaran_tagihan_siswa`, `ms_saldo_tabungan`, `ms_transaksi_tabungan`, `ms_kuitansi_transaksi_tabungan`, `ms_saldo_edupay`, `ms_transaksi_edupay`, `ms_kuitansi_transaksi_edupay`, `akuntansi_jurnal`, `akuntansi_jurnal_detail`, `akuntansi_rekening`, `akuntansi_kelompok_rekening`, `transaksi_pendapatan_lainnya`, `transaksi_pengeluaran`, and the four `ms_whatsapp_*` tables.
- SmartCanteen: all 8 tables.
- KoperasiPintar: all 9 tables.
- SmartPass/FingerSpot: all 5 tables.

### E.5 Referential integrity

- No foreign key constraints are declared in any migration.
- Cross-table references are by convention (`ms_*_id`, `kode_rekening`, `user_id` + `user_type`, `pin` -> `pin_fingerspot`).
- Deleting a parent row will not cascade; orphan rows are possible. Soft deletes are model-level only and do not cascade.

### E.6 Data-scoping assumptions

- `ms_akses_jenjang` is assumed to contain one row per `(ms_pengguna_id, ms_jenjang_id)` pair. The migration does not exist, so uniqueness is **Unverified**.
- `ms_saldo_tabungan` and `ms_saldo_edupay` are assumed to be unique per `(user_id, user_type)` because `getSaldo()` uses `firstOrCreate`. The live constraint is **Unverified**.
- `akuntansi_rekening.kode_rekening` is assumed unique because journal details join on it. The live constraint is **Unverified**.

---

## F. Verification Boundaries

### Verified from repository contents

- Migration file count, table names, column types, defaults, indexes, unique constraints, and the conditional rename of `ms_guru_id` to `ms_pegawai_id`.
- Model class names, table names, primary keys, `$fillable`, `$casts`, `SoftDeletes` usage, declared relationships, and model helper methods.
- The absence of foreign key declarations in migrations.
- The absence of migrations for the 58 model-referenced tables listed in E.4.
- The KoperasiPintar class/file mismatches.
- The `ms_pengguna`, PPDB, and academic migration columns.

### Not verified

- The actual live MySQL schema, column types, nullability, defaults, indexes, and constraints.
- Whether the extra model columns exist in the live tables.
- Whether `ms_akses_jenjang`, `ms_saldo_*`, and `akuntansi_rekening.kode_rekening` have unique constraints.
- Actual row counts, data quality, orphan rows, and status values in production.
- Whether `ppdb_pendaftaran` uses `user_id` or `ms_pengguna_id` in the live DB.
- Whether `ppdb_gelombang` uses `id` or `ppdb_gelombang_id` in the live DB.
- Whether the live `ppdb_periode` has `ms_tahun_ajar_id`, `ms_jenjang_id`, or both.
- Runtime behavior of any relationship or scope marked "likely broken".

### How to verify safely (read-only)

Do not run migrations, seeders, or destructive commands. When a human operator can access the database, use read-only inspection:

- `SHOW CREATE TABLE <table>;` for each table.
- `SELECT COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_DEFAULT, COLUMN_KEY, EXTRA FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '<table>';`
- `SELECT INDEX_NAME, NON_UNIQUE, COLUMN_NAME, SEQ_IN_INDEX FROM information_schema.STATISTICS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = '<table>';`
- `SELECT CONSTRAINT_NAME, TABLE_NAME, REFERENCED_TABLE_NAME FROM information_schema.KEY_COLUMN_USAGE WHERE TABLE_SCHEMA = DATABASE() AND REFERENCED_TABLE_NAME IS NOT NULL;`
- `php artisan db:table <table>` if the installed Laravel version supports it; this is read-only, but confirm before running.

Record findings in a separate audit note; do not edit migrations to match the live schema without confirming the database state and obtaining approval.
