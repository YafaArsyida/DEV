# PRD: Modul Jadwal Mengajar

## 1. Latar Belakang

Menu Jadwal Mengajar merupakan bagian dari modul E-Jurnal TemanSekolah.

Saat ini menu Jadwal Mengajar belum stabil dan belum dapat digunakan dengan baik. Modul perlu dievaluasi dan dibangun ulang berdasarkan struktur data serta kebutuhan operasional sekolah.

Jadwal Mengajar berfungsi sebagai data perencanaan kegiatan belajar mengajar yang menghubungkan guru, kelas, mata pelajaran, hari, jam, dan rentang tanggal pelaksanaan.

Jadwal ini nantinya menjadi dasar untuk:
- Menampilkan daftar jadwal mengajar guru.
- Menentukan kelas dan mata pelajaran yang harus diajar guru.
- Menjadi referensi pengisian jurnal mengajar.
- Monitoring jadwal mengajar sekolah.
- Mendeteksi bentrok jadwal guru dan kelas.

## 2. Tujuan

Membangun modul Jadwal Mengajar yang:
1. Stabil dan dapat digunakan untuk operasional sekolah.
2. Memiliki pengelolaan jadwal yang mudah dipahami administrator.
3. Menggunakan data penugasan guru sebagai sumber data utama.
4. Mencegah bentrok jadwal.
5. Mendukung jadwal rutin mingguan dengan batas tanggal berlaku.
6. Dapat diintegrasikan dengan modul Jurnal Mengajar.

## 3. Struktur Database

### 3.1. Tabel utama

Nama tabel: `ej_jadwal_mengajar`

Model: `EJJadwalMengajar` (periksa nama model aktual di repository).

Struktur model:

```php
use HasFactory;
use SoftDeletes;

protected $table = 'ej_jadwal_mengajar';

protected $primaryKey = 'ej_jadwal_mengajar_id';

protected $fillable = [
    'ms_penugasan_guru_id',
    'hari',
    'jam_mulai',
    'jam_selesai',
    'tanggal_mulai',
    'tanggal_selesai',
    'status',
    'deskripsi',
];

protected $casts = [
    'tanggal_mulai' => 'date',
    'tanggal_selesai' => 'date',
];
```

**Ketentuan penting:**
- Jangan mengubah struktur tabel tanpa alasan teknis yang jelas.
- Jangan menambahkan kolom baru hanya untuk mempermudah implementasi.
- Periksa migration dan struktur database aktual.
- Jika terdapat kebutuhan perubahan database, dokumentasikan alasannya terlebih dahulu.

### 3.2. Relasi penugasan guru

`ej_jadwal_mengajar.ms_penugasan_guru_id` merupakan foreign key menuju penugasan guru.

Data guru, kelas, mata pelajaran, jenjang, dan periode akademik harus diperoleh melalui relasi penugasan tersebut.

Jangan menduplikasi data guru, kelas, atau mata pelajaran ke tabel jadwal mengajar.

Sebelum implementasi, audit:
- Model penugasan guru.
- Relasi guru dengan penugasan.
- Relasi kelas dengan penugasan.
- Relasi mata pelajaran dengan penugasan.
- Relasi periode akademik dengan penugasan.
- Status aktif dan aturan penggunaan penugasan.

## 4. Konsep dan Aturan Bisnis

### 4.1. Konsep jadwal

Satu record jadwal merepresentasikan satu kegiatan mengajar rutin pada hari dan rentang waktu tertentu.

Contoh:

| Guru | Kelas | Mapel | Hari | Waktu |
|---|---|---|---|---|
| Ahmad | 1A | Matematika | Senin | 07:00–08:00 |
| Ahmad | 1A | Bahasa Indonesia | Senin | 08:00–09:00 |
| Budi | 2A | Matematika | Senin | 07:00–08:00 |

Satu penugasan guru dapat memiliki beberapa jadwal.

Jadwal bersifat berulang setiap minggu selama rentang tanggal berlaku.

### 4.2. Rentang tanggal

Kolom:
- `tanggal_mulai`
- `tanggal_selesai`

Menentukan masa berlaku jadwal.

Aturan:
- Tanggal mulai wajib diisi.
- Tanggal selesai wajib diisi.
- Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.
- Jadwal hanya berlaku dalam rentang tanggal tersebut.
- Jadwal yang sudah melewati tanggal selesai tidak otomatis dihapus.
- Jadwal yang belum memasuki tanggal mulai belum berlaku.

### 4.3. Hari

Jadwal menggunakan hari dalam satu minggu.

Nilai dan format penyimpanan hari harus mengikuti implementasi database yang sudah ada.

Pastikan konsisten antara:
- Penyimpanan database.
- Validasi backend.
- Form input.
- Tampilan jadwal.
- Filter hari.

### 4.4. Waktu

Aturan:
- `jam_mulai` wajib diisi.
- `jam_selesai` wajib diisi.
- Jam selesai harus lebih besar dari jam mulai.
- Gunakan format waktu yang konsisten dengan database.
- Hindari jadwal dengan durasi nol atau negatif.

### 4.5. Status

Gunakan nilai status yang sudah diterapkan pada sistem.

Status menentukan apakah jadwal dapat digunakan dalam operasional E-Jurnal.

Jangan mengasumsikan nilai enum status sebelum memeriksa migration dan implementasi yang sudah ada.

## 5. Pencegahan Bentrok Jadwal

Ini merupakan salah satu fitur paling penting dalam modul.

Sistem wajib memeriksa bentrok sebelum menyimpan atau memperbarui jadwal.

### 5.1. Bentrok guru

Guru tidak boleh memiliki dua jadwal aktif yang waktunya bertabrakan pada hari yang sama.

Contoh:

| Guru | Kelas | Hari | Waktu |
|---|---|---|---|
| Ahmad | 1A | Senin | 07:00–08:00 |
| Ahmad | 2A | Senin | 07:30–08:30 |

Hasil: Ditolak karena guru memiliki dua jadwal bersamaan.

### 5.2. Bentrok kelas

Satu kelas tidak boleh memiliki dua jadwal mengajar bersamaan.

Contoh:

| Guru | Kelas | Hari | Waktu |
|---|---|---|---|
| Ahmad | 1A | Senin | 07:00–08:00 |
| Budi | 1A | Senin | 07:30–08:30 |

Hasil: Ditolak karena kelas memiliki dua kegiatan mengajar bersamaan.

### 5.3. Logika bentrok waktu

Gunakan logika overlap:

```php
$jadwalLain->jam_mulai < $jamSelesai
&&
$jadwalLain->jam_selesai > $jamMulai
```

Dua jadwal yang bersentuhan tepat pada batas waktu tidak dianggap bentrok.

Contoh:
- 07:00–08:00
- 08:00–09:00

Tidak bentrok.

### 5.4. Bentrok rentang tanggal

Pengecekan juga harus mempertimbangkan rentang tanggal.

Dua jadwal hanya berpotensi bentrok jika rentang tanggal keduanya saling beririsan.

Gunakan prinsip:

```php
$tanggalMulaiLain <= $tanggalSelesai
&&
$tanggalSelesaiLain >= $tanggalMulai
```

Pengecekan bentrok harus mempertimbangkan:
- Hari yang sama.
- Waktu yang beririsan.
- Rentang tanggal yang beririsan.
- Guru yang sama atau kelas yang sama.
- Jadwal lain yang masih berlaku.
- Mengecualikan record sendiri ketika melakukan update.
- Mengecualikan record yang sudah dihapus secara soft delete.

### 5.5. Informasi bentrok

Jangan hanya menampilkan pesan umum.

Tampilkan informasi jadwal yang menyebabkan bentrok, minimal:
- Nama guru.
- Kelas.
- Mata pelajaran.
- Hari.
- Jam.

Pesan harus mudah dipahami pengguna.

## 6. Desain Antarmuka

Gunakan Velzon Modern Bootstrap yang sudah diterapkan di TemanSekolah.

Pertahankan:
- Card dengan rounded-4.
- Modal dengan header konsisten.
- Ikon dan warna sesuai pola aplikasi.
- Alertify untuk notifikasi sukses dan gagal.
- Loading state pada proses Livewire.
- Responsif untuk desktop dan perangkat mobile.

### 6.1. Hierarki tampilan

Halaman Jadwal Mengajar terdiri dari empat bagian.

#### A. Parameter akademik

Gunakan parameter akademik yang sudah digunakan pada modul akademik lainnya.

Parameter:
- Jenjang.
- Tahun Ajaran.
- Periode Akademik.

Pastikan periode yang tersedia sesuai dengan jenjang dan tahun ajaran terpilih.

Gunakan mekanisme `parameterUpdated` apabila memang sudah menjadi pola komponen terkait.

#### B. Pengaturan tampilan

Sediakan mode tampilan:

1. Per Kelas.
2. Per Guru.
3. Seluruh Sekolah.

Filter tambahan:
- Kelas.
- Guru.
- Hari.
- Pencarian.

Mode tampilan tidak boleh menyebabkan perubahan data jadwal.

#### C. Daftar jadwal

Tampilan utama disarankan menggunakan matriks jadwal mingguan.

Contoh:

| Jam | Senin | Selasa | Rabu |
|---|---|---|---|
| 07:00–08:00 | Matematika 1A | IPA 1A | Bahasa 1A |
| 08:00–09:00 | Bahasa 1A | Matematika 1A | IPA 1A |

Untuk mode per kelas, tampilkan jadwal berdasarkan kelas terpilih.

Untuk mode per guru, tampilkan jadwal berdasarkan guru terpilih.

Untuk mode seluruh sekolah, tampilkan ringkasan jadwal yang mudah dibaca dan difilter.

Tidak perlu menggunakan FullCalendar Resource Timeline karena fitur tersebut merupakan bagian dari versi premium.

Gunakan komponen Bootstrap atau implementasi matriks HTML yang responsif.

#### D. Ringkasan dan monitoring

Tampilkan informasi:
- Total jadwal.
- Jadwal aktif.
- Jadwal belum berlaku.
- Jadwal berakhir.
- Jumlah guru yang memiliki jadwal.
- Jumlah kelas yang memiliki jadwal.

Jangan menghitung status secara hardcode. Gunakan kondisi tanggal dan status aktual.

## 7. Fitur CRUD

### 7.1. Tambah jadwal

Form tambah jadwal minimal memiliki:

1. Penugasan guru.
2. Hari.
3. Jam mulai.
4. Jam selesai.
5. Tanggal mulai.
6. Tanggal selesai.
7. Deskripsi.

Penugasan guru harus ditampilkan dengan informasi yang memudahkan identifikasi, misalnya:

Nama Guru - Kelas - Mata Pelajaran.

Jangan membuat input guru, kelas, dan mata pelajaran terpisah apabila seluruhnya sudah tersedia melalui penugasan guru.

Sebelum menyimpan:
- Validasi seluruh input.
- Periksa status penugasan.
- Periksa bentrok guru.
- Periksa bentrok kelas.
- Periksa rentang tanggal.

### 7.2. Edit jadwal

Edit harus:
- Mengambil data berdasarkan primary key.
- Mengisi form dengan data sebelumnya.
- Menggunakan validasi yang sama dengan tambah.
- Memeriksa bentrok dengan mengecualikan jadwal yang sedang diedit.
- Tidak mengubah record lain.

### 7.3. Hapus jadwal

Gunakan SoftDeletes yang sudah diterapkan.

Sediakan konfirmasi sebelum menghapus.

Jangan menghapus data penugasan guru ketika menghapus jadwal.

### 7.4. Detail jadwal

Sediakan informasi lengkap:
- Guru.
- Kelas.
- Mata pelajaran.
- Jenjang.
- Periode akademik.
- Hari.
- Jam mulai dan selesai.
- Rentang tanggal.
- Status.
- Deskripsi.

## 8. Integrasi dengan E-Jurnal

Jadwal Mengajar merupakan sumber referensi untuk modul Jurnal Mengajar.

Integrasi yang diharapkan:

1. Guru dapat melihat jadwal mengajarnya sendiri.
2. Guru dapat mengetahui kelas dan mata pelajaran yang menjadi tanggung jawabnya.
3. Jadwal dapat digunakan sebagai referensi ketika membuat jurnal.
4. Sistem dapat membatasi akses jurnal berdasarkan penugasan guru.
5. Jadwal tidak otomatis membuat data pertemuan aktual.
6. Perubahan jadwal tidak boleh menghapus riwayat jurnal yang sudah dibuat.

Penting: Jangan mencampurkan entitas jadwal rutin dengan entitas jurnal atau pertemuan aktual.

## 9. Hak Akses

Periksa sistem role dan middleware yang sudah ada.

Ketentuan fungsional:

- Administrator yang memiliki hak pengelolaan dapat melihat, menambah, mengubah, dan menghapus jadwal.
- Guru hanya dapat melihat jadwal yang berkaitan dengan penugasannya.
- Guru tidak boleh mengubah jadwal melalui menu pengelolaan administrator.

Jangan membuat role atau permission baru jika mekanisme yang ada sudah mencukupi.

Pastikan otorisasi diterapkan di backend, bukan hanya menyembunyikan tombol pada tampilan.

## 10. Validasi dan Penanganan Error

Pastikan sistem menangani kondisi berikut:

- Penugasan guru tidak ditemukan.
- Penugasan guru tidak aktif.
- Penugasan tidak sesuai dengan parameter akademik.
- Hari tidak valid.
- Jam mulai dan selesai tidak valid.
- Tanggal mulai dan selesai tidak valid.
- Jadwal guru bentrok.
- Jadwal kelas bentrok.
- Record sudah dihapus.
- Pengguna tidak memiliki akses.
- Data parameter akademik belum dipilih.
- Tidak terdapat data jadwal.

Semua error harus ditampilkan dengan bahasa yang mudah dipahami pengguna.

## 11. Kriteria Penerimaan (Acceptance Criteria)

Modul dianggap selesai apabila:

### Data dan CRUD
- [ ] Data jadwal dapat ditampilkan.
- [ ] Administrator dapat menambahkan jadwal.
- [ ] Administrator dapat mengedit jadwal.
- [ ] Administrator dapat menghapus jadwal.
- [ ] Data penugasan guru terhubung dengan benar.
- [ ] Informasi guru, kelas, dan mapel tampil sesuai penugasan.

### Validasi
- [ ] Jam selesai harus lebih besar dari jam mulai.
- [ ] Tanggal selesai tidak boleh lebih kecil dari tanggal mulai.
- [ ] Sistem mendeteksi bentrok guru.
- [ ] Sistem mendeteksi bentrok kelas.
- [ ] Sistem mendeteksi irisan rentang tanggal.
- [ ] Update tidak dianggap bentrok dengan dirinya sendiri.
- [ ] Jadwal yang dihapus tidak ikut dalam pemeriksaan bentrok.

### Tampilan
- [ ] Parameter akademik bekerja.
- [ ] Mode per kelas bekerja.
- [ ] Mode per guru bekerja.
- [ ] Mode seluruh sekolah bekerja.
- [ ] Filter hari dan pencarian bekerja.
- [ ] Tampilan responsif.
- [ ] Notifikasi dan loading state berfungsi.

### Integrasi dan keamanan
- [ ] Guru hanya dapat melihat jadwal yang menjadi tanggung jawabnya.
- [ ] Administrator dapat mengelola jadwal sesuai hak akses.
- [ ] Jadwal dapat digunakan sebagai referensi E-Jurnal.
- [ ] Penghapusan jadwal tidak menghapus jurnal historis.
- [ ] Tidak ada perubahan pada modul lain yang tidak berkaitan.

## 12. Pengujian

Buat pengujian untuk skenario:

1. Menambah jadwal pertama.
2. Menambah jadwal guru yang sama pada jam yang sama.
3. Menambah jadwal kelas yang sama pada jam yang sama.
4. Menambah jadwal dengan jam berurutan.
5. Menambah jadwal dengan rentang tanggal yang tidak beririsan.
6. Menambah jadwal dengan rentang tanggal yang beririsan.
7. Mengubah jadwal tanpa menyebabkan bentrok.
8. Mengubah jadwal sehingga menyebabkan bentrok.
9. Menghapus jadwal kemudian menambahkan jadwal pengganti.
10. Menampilkan jadwal berdasarkan parameter akademik.
11. Memastikan guru tidak dapat melihat jadwal guru lain.

Gunakan pola testing dan tools yang sudah tersedia di repository.

## 13. Batasan Implementasi

Dalam pengerjaan tahap ini:

- Fokus hanya pada modul Jadwal Mengajar.
- Jangan melakukan refactor besar pada modul Akademik.
- Jangan mengubah struktur penugasan guru tanpa kebutuhan yang terbukti.
- Jangan mengubah struktur tabel jurnal.
- Jangan membuat data pertemuan otomatis.
- Jangan menambahkan fitur presensi.
- Jangan mengubah route atau nama komponen lain yang tidak terkait.
- Jangan menggunakan dummy data sebagai solusi permanen.

## 14. Definition of Done

Modul Jadwal Mengajar dinyatakan selesai apabila seluruh CRUD berjalan stabil, validasi bentrok bekerja, parameter akademik terintegrasi, hak akses berjalan, tampilan responsif, dan pengujian utama berhasil.

Dokumentasikan perubahan file, keputusan teknis, hasil pengujian, dan keterbatasan yang masih tersisa.