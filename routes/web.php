<?php

use App\Http\Controllers\AkuntansiJurnalDetail;
use App\Http\Controllers\AkuntansiKonfigurasi;
use App\Http\Controllers\AkuntansiLaporanBukuBesar;
use App\Http\Controllers\AkuntansiLaporanJurnalUmum;
use App\Http\Controllers\AkuntansiLaporanLabaRugi;
use App\Http\Controllers\AkuntansiLaporanNeraca;
use App\Http\Controllers\AkuntansiLaporanPendapatan;
use App\Http\Controllers\AkuntansiLaporanPengeluaran;
use App\Http\Controllers\AkuntansiLaporanRekonsiliasi;
use App\Http\Controllers\AkuntansiTransaksiPendapatan;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenAdministrasi;
use App\Http\Controllers\DokumenSiswa;
use App\Http\Controllers\EkstrakurikulerSiswa;
use App\Http\Controllers\HierarkiKepegawaian;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\JenjangTahunAjar;

use App\Http\Controllers\KelasSiswa;
use App\Http\Controllers\KonfigurasiTagihanSiswa;
use App\Http\Controllers\LandingEkstrakurikuler;
use App\Http\Controllers\LaporanEduPaySiswa;
use App\Http\Controllers\LaporanPembayaranTagihanSiswa;
use App\Http\Controllers\LaporanRekapitulasiKeuangan;
use App\Http\Controllers\LaporanTabunganSiswa;
use App\Http\Controllers\LaporanTagihanSiswa;
use App\Http\Controllers\ManajemenKepegawaian;
use App\Http\Controllers\PenggunaJenjang;
use App\Http\Controllers\TagihanJenis;
use App\Http\Controllers\TagihanSiswa;
use App\Http\Controllers\TransaksiEduPaySiswa;
use App\Http\Controllers\TransaksiPendapatanLainnya;
use App\Http\Controllers\TransaksiPengeluaran;
use App\Http\Controllers\TransaksiTabunganSiswa;
use App\Http\Controllers\TransaksiTagihanSiswa;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('v_home');
// });

Route::get('/', [LandingEkstrakurikuler::class, 'index'])->name('landing.ekstrakurikuler');

// login
Route::get('/login', [LoginController::class, 'index'])->name('login.index')->middleware('guest');
// Route::get('/', [LoginController::class, 'index'])->name('login.index')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logOut'])->name('logout');

Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

// SISTEM
// JENJANG TAHUN AJAR
Route::middleware(['auth'])->group(function () {
    Route::get('/sistem/jenjang-tahun-ajar',  [JenjangTahunAjar::class, 'index'])->name('sistem.jenjang-tahun-ajar');
    Route::get('/sistem/dokumen-administrasi',  [DokumenAdministrasi::class, 'index'])->name('sistem.dokumen-administrasi');
    Route::get('/sistem/pengguna-jenjang',  [PenggunaJenjang::class, 'index'])->name('sistem.pengguna-jenjang');

    Route::get('/administrasi/kelas-siswa',  [KelasSiswa::class, 'index'])->name('administrasi.kelas-siswa');
    // ekstrakurikuler
    Route::get('/administrasi/ekstrakurikuler-siswa',  [EkstrakurikulerSiswa::class, 'index'])->name('administrasi.ekstrakurikuler-siswa');
    Route::get('/administrasi/ekstrakurikuler-siswa/pdf',  [EkstrakurikulerSiswa::class, 'cetakPDF'])->name('administrasi.ekstrakurikuler-siswa.pdf');
    Route::get('/administrasi/ekstrakurikuler-siswa/siswapdf',  [EkstrakurikulerSiswa::class, 'cetakSiswaPDF'])->name('administrasi.ekstrakurikuler-siswa.siswapdf');
    // ekstrakurikuler
    Route::get('/administrasi/dokumen-siswa',  [DokumenSiswa::class, 'index'])->name('administrasi.dokumen-siswa');
    Route::get('/administrasi/manajemen-kepegawaian',  [ManajemenKepegawaian::class, 'index'])->name('administrasi.manajemen-kepegawaian');
    Route::get('/administrasi/hierarki-kepegawaian',  [HierarkiKepegawaian::class, 'index'])->name('administrasi.hierarki-kepegawaian');

    Route::get('/keuangan/konfigurasi-tagihan-siswa',  [KonfigurasiTagihanSiswa::class, 'index'])->name('keuangan.konfigurasi-tagihan-siswa');

    Route::get('/keuangan/tagihan-siswa',  [TagihanSiswa::class, 'index'])->name('keuangan.tagihan-siswa');
    Route::get('/keuangan/tagihan-siswa/pdf', [TagihanSiswa::class, 'cetakPDF'])
        ->name('keuangan.tagihan-siswa.pdf');

    Route::get('/keuangan/tagihan-jenis',  [TagihanJenis::class, 'index'])->name('keuangan.tagihan-jenis');
    Route::get('/keuangan/jenis-tagihan-siswa/pdf', [TagihanJenis::class, 'cetakPDF'])
        ->name('keuangan.jenis-tagihan-siswa.pdf');

    Route::get('/transaksi/tagihan-siswa',  [TransaksiTagihanSiswa::class, 'index'])->name('transaksi.tagihan-siswa');
    Route::get('/transaksi/tagihan-siswa/{transaksiId}', [TransaksiTagihanSiswa::class, 'kuitansiPDF'])->name('transaksi.tagihan-siswa.kuitansiPDF');

    Route::get('/transaksi/tabungan-siswa',  [TransaksiTabunganSiswa::class, 'index'])->name('transaksi.tabungan-siswa');

    Route::get('/transaksi/edupay-siswa',  [TransaksiEduPaySiswa::class, 'index'])->name('transaksi.edupay-siswa');
    Route::get('/transaksi/edupay-siswa/{eduPayId}', [TransaksiEduPaySiswa::class, 'kuitansiPDF'])->name('transaksi.edupay-siswa.kuitansiPDF');

    // transaksi pendapatan
    Route::get('/transaksi/pendapatan-lainnya',  [TransaksiPendapatanLainnya::class, 'index'])->name('transaksi.pendapatan-lainnya');
    Route::get('/transaksi/pendapatan-lainnya/pdf', [TransaksiPendapatanLainnya::class, 'cetakPDF'])
        ->name('transaksi.pendapatan-lainnya.pdf');


    // transaksi pengeluaran
    Route::get('/transaksi/pengeluaran',  [TransaksiPengeluaran::class, 'index'])->name('transaksi.pengeluaran');
    Route::get('/transaksi/pengeluaran/pdf', [TransaksiPengeluaran::class, 'cetakPDF'])
        ->name('transaksi.pengeluaran.pdf');

    // Laporan Pembayaran
    Route::get('/laporan/pembayaran-tagihan-siswa',  [LaporanPembayaranTagihanSiswa::class, 'index'])->name('laporan.pembayaran-tagihan-siswa');
    Route::get('/laporan/pembayaran-tagihan-siswa/pdf', [LaporanPembayaranTagihanSiswa::class, 'cetakPDF'])->name('laporan.pembayaran-tagihan-siswa.pdf');
    // END Laporan Pembayaran

    Route::get('/laporan/tagihan-siswa',  [LaporanTagihanSiswa::class, 'index'])->name('laporan.tagihan-siswa');
    Route::get('/laporan/tagihan-siswa/{msPenempatanSiswaId}', [LaporanTagihanSiswa::class, 'generatePDF'])->name('laporan.tagihan-siswa.generatePDF');
    Route::get('/laporan/tagihan-kelas/{ms_kelas_id}', [LaporanTagihanSiswa::class, 'generatePDFByClass'])->name('laporan.tagihan-kelas.generatePDFByClass');

    Route::get('/laporan/tabungan-siswa',  [LaporanTabunganSiswa::class, 'index'])->name('laporan.tabungan-siswa');
    Route::get('/laporan/edupay-siswa',  [LaporanEduPaySiswa::class, 'index'])->name('laporan.edupay-siswa');
    Route::get('/laporan/rekapitulasi-keuangan',  [LaporanRekapitulasiKeuangan::class, 'index'])->name('laporan.rekapitulasi-keuangan');

    Route::get('/akuntansi/konfigurasi',  [AkuntansiKonfigurasi::class, 'index'])->name('akuntansi.konfigurasi');
    Route::get('/akuntansi/jurnal-detail',  [AkuntansiJurnalDetail::class, 'index'])->name('akuntansi.jurnal-detail');


    // laporan akuntansi
    Route::get('/akuntansi/laporan-rekonsiliasi',  [AkuntansiLaporanRekonsiliasi::class, 'index'])->name('akuntansi.laporan-rekonsiliasi');
    Route::get('/akuntansi/laporan-buku-besar',  [AkuntansiLaporanBukuBesar::class, 'index'])->name('akuntansi.laporan-buku-besar');

    // jurnal keuangan
    Route::get('/akuntansi/laporan-jurnal-umum',  [AkuntansiLaporanJurnalUmum::class, 'index'])->name('akuntansi.laporan-jurnal-umum');
    Route::get('/akuntansi/laporan-jurnal-umum/pdf', [AkuntansiLaporanJurnalUmum::class, 'cetakPDF'])->name('akuntansi.laporan-jurnal-umum.pdf');
    // jurnal keuangan


    Route::get('/akuntansi/laporan-neraca',  [AkuntansiLaporanNeraca::class, 'index'])->name('akuntansi.laporan-neraca');

    // LAPORAN PENDAPATAN
    Route::get('/akuntansi/laporan-pendapatan',  [AkuntansiLaporanPendapatan::class, 'index'])->name('akuntansi.laporan-pendapatan');
    Route::get('/akuntansi/laporan-pendapatan/pdf', [AkuntansiLaporanPendapatan::class, 'cetakPDF'])->name('akuntansi.laporan-pendapatan.pdf');

    // LAPORAN PENGELUARAN
    Route::get('/akuntansi/laporan-pengeluaran',  [AkuntansiLaporanPengeluaran::class, 'index'])->name('akuntansi.laporan-pengeluaran');
    Route::get('/akuntansi/laporan-pengeluaran/pdf', [AkuntansiLaporanPengeluaran::class, 'cetakPDF'])->name('akuntansi.laporan-pengeluaran.pdf');

    // Laba rugi
    Route::get('/akuntansi/laporan-laba-rugi',  [AkuntansiLaporanLabaRugi::class, 'index'])->name('akuntansi.laporan-laba-rugi');
    Route::get('/akuntansi/laporan-laba-rugi/pdf', [AkuntansiLaporanLabaRugi::class, 'cetakPDF'])->name('akuntansi.laporan-laba-rugi.pdf');
    // Laba rugi

    Route::get('/akuntansi/transaksi-pendapatan',  [AkuntansiTransaksiPendapatan::class, 'index'])->name('akuntansi.transaksi-pendapatan');
});
