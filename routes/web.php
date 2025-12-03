<?php

use App\Http\Controllers\AkuntansiJurnalDetail;
use App\Http\Controllers\AkuntansiKonfigurasi;
use App\Http\Controllers\AkuntansiLaporanArusKas;
use App\Http\Controllers\AkuntansiLaporanBukuBesar;
use App\Http\Controllers\AkuntansiLaporanJurnalUmum;
use App\Http\Controllers\AkuntansiLaporanLabaRugi;
use App\Http\Controllers\AkuntansiLaporanNeraca;
use App\Http\Controllers\AkuntansiLaporanPendapatan;
use App\Http\Controllers\AkuntansiLaporanPengeluaran;
use App\Http\Controllers\AkuntansiTransaksiPendapatan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DokumenAdministrasi;
use App\Http\Controllers\EkstrakurikulerSiswa;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\JenjangTahunAjar;
use App\Http\Controllers\KelasSiswa;
use App\Http\Controllers\KonfigurasiTagihanSiswa;
use App\Http\Controllers\KoperasiPintarAdministrasiProduk;
use App\Http\Controllers\KoperasiPintarDashboard;
use App\Http\Controllers\KoperasiPintarPembelianProduk;
use App\Http\Controllers\KoperasiPintarPengembalianProduk;
use App\Http\Controllers\KoperasiPintarPenjualanProduk;
use App\Http\Controllers\LandingEkstrakurikuler;
use App\Http\Controllers\LaporanEduPayPegawai;
use App\Http\Controllers\LaporanEduPaySiswa;
use App\Http\Controllers\LaporanPembayaranTagihanSiswa;
use App\Http\Controllers\LaporanRekapitulasiKeuangan;
use App\Http\Controllers\LaporanTabunganPegawai;
use App\Http\Controllers\LaporanTabunganSiswa;
use App\Http\Controllers\LaporanTagihanSiswa;
use App\Http\Controllers\ManajemenKepegawaian;
use App\Http\Controllers\PenggunaJenjang;
use App\Http\Controllers\SmartCanteenAdministrasiProduk;
use App\Http\Controllers\SmartCanteenDashboard;
use App\Http\Controllers\SmartCanteenLaporanTransaksi;
use App\Http\Controllers\SmartCanteenSettlementTransaksi;
use App\Http\Controllers\SmartCanteenTransaksiProduk;
use App\Http\Controllers\SmartPassDashboard;
use App\Http\Controllers\TagihanJenis;
use App\Http\Controllers\TagihanSiswa;
use App\Http\Controllers\TransaksiEduPayPegawai;
use App\Http\Controllers\TransaksiEduPaySiswa;
use App\Http\Controllers\TransaksiPendapatanLainnya;
use App\Http\Controllers\TransaksiPengeluaran;
use App\Http\Controllers\TransaksiTabunganPegawai;
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

Route::get('/home', function () {
    $user = auth()->user();
    if (!$user) {
        return redirect()->route('login.index');
    }

    if ($user->peran === 'kantin') {
        return redirect()->route('smartCanteen.dashboard');
    }

    return redirect()->route('dashboard.index');
});

Route::get('/landing/ekstrakurikuler', [LandingEkstrakurikuler::class, 'index'])->name('landing.ekstrakurikuler');

// login
// Route::get('/login', [LoginController::class, 'index'])->name('login.index')->middleware('guest');
Route::get('/', [LoginController::class, 'index'])->name('login.index')->middleware('guest');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.authenticate');
Route::post('/logout', [LoginController::class, 'logOut'])->name('logout');

Route::middleware(['auth', 'peran:superadmin,admin,kantin'])->group(function () {
    // SMARTCANTEEN 
    Route::get('/smartCanteen/dashboard', [SmartCanteenDashboard::class, 'index'])->name('smartCanteen.dashboard');

    Route::get('/smartCanteen/administrasi/produk', [SmartCanteenAdministrasiProduk::class, 'index'])->name('smartCanteen.administrasi.produk');

    Route::get('/smartCanteen/transaksi/produk', [SmartCanteenTransaksiProduk::class, 'index'])->name('smartCanteen.transaksi.produk');

    Route::get('/smartCanteen/laporan/transaksi',  [SmartCanteenLaporanTransaksi::class, 'index'])->name('smartCanteen.laporan.transaksi');
    Route::get('/smartCanteen/laporan/transaksi/pdf',  [SmartCanteenLaporanTransaksi::class, 'cetakPDF'])->name('smartCanteen.laporan.transaksi.pdf');

    Route::get('/smartCanteen/settlement/transaksi',  [SmartCanteenSettlementTransaksi::class, 'index'])->name('smartCanteen.settlement.transaksi');
    // END SMARTCANTEEN 

    // KOPERASIPINTAR
    // master
    Route::get('/koperasiPintar/dashboard', [KoperasiPintarDashboard::class, 'index'])->name('koperasiPintar.dashboard');
    Route::get('/koperasiPintar/administrasi/produk', [KoperasiPintarAdministrasiProduk::class, 'index'])->name('koperasiPintar.administrasi.produk');

    // transaksi
    Route::get('/koperasiPintar/pembelian/produk', [KoperasiPintarPembelianProduk::class, 'index'])->name('koperasiPintar.pembelian.produk');
    Route::get('/koperasiPintar/pengembalian/produk', [KoperasiPintarPengembalianProduk::class, 'index'])->name('koperasiPintar.pengembalian.produk');
    Route::get('/koperasiPintar/penjualan/produk', [KoperasiPintarPenjualanProduk::class, 'index'])->name('koperasiPintar.penjualan.produk');
    // END KOPERASIPINTAR

    // SMARTPASS
    Route::get('/smartPass/dashboard', [SmartPassDashboard::class, 'index'])->name('smartPass.dashboard');
});


// SISTEM
// JENJANG TAHUN AJAR
Route::middleware(['auth', 'peran:superadmin,admin'])->group(function () {
    Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::get('/sistem/jenjang-tahun-ajar',  [JenjangTahunAjar::class, 'index'])->name('sistem.jenjang-tahun-ajar');
    Route::get('/sistem/dokumen-administrasi',  [DokumenAdministrasi::class, 'index'])->name('sistem.dokumen-administrasi');
    Route::get('/sistem/pengguna-jenjang',  [PenggunaJenjang::class, 'index'])->name('sistem.pengguna-jenjang');

    Route::get('/administrasi/kelas-siswa',  [KelasSiswa::class, 'index'])->name('administrasi.kelas-siswa');
    // ekstrakurikuler
    Route::get('/administrasi/ekstrakurikuler-siswa',  [EkstrakurikulerSiswa::class, 'index'])->name('administrasi.ekstrakurikuler-siswa');
    Route::get('/administrasi/ekstrakurikuler-siswa/pdf',  [EkstrakurikulerSiswa::class, 'cetakPDF'])->name('administrasi.ekstrakurikuler-siswa.pdf');
    Route::get('/administrasi/ekstrakurikuler-siswa/siswapdf',  [EkstrakurikulerSiswa::class, 'cetakSiswaPDF'])->name('administrasi.ekstrakurikuler-siswa.siswapdf');
    // ekstrakurikuler

    Route::get('/administrasi/manajemen-kepegawaian',  [ManajemenKepegawaian::class, 'index'])->name('administrasi.manajemen-kepegawaian');

    Route::get('/keuangan/konfigurasi-tagihan-siswa',  [KonfigurasiTagihanSiswa::class, 'index'])->name('keuangan.konfigurasi-tagihan-siswa');

    Route::get('/keuangan/tagihan-siswa',  [TagihanSiswa::class, 'index'])->name('keuangan.tagihan-siswa');
    Route::get('/keuangan/tagihan-siswa/pdf', [TagihanSiswa::class, 'cetakPDF'])
        ->name('keuangan.tagihan-siswa.pdf');

    Route::get('/keuangan/tagihan-jenis',  [TagihanJenis::class, 'index'])->name('keuangan.tagihan-jenis');
    Route::get('/keuangan/jenis-tagihan-siswa/pdf', [TagihanJenis::class, 'cetakPDF'])
        ->name('keuangan.jenis-tagihan-siswa.pdf');

    Route::get('/transaksi/tagihan-siswa',  [TransaksiTagihanSiswa::class, 'index'])->name('transaksi.tagihan-siswa');
    Route::get('/transaksi/tagihan-siswa/{transaksiId}', [TransaksiTagihanSiswa::class, 'kuitansiPDF'])->name('transaksi.tagihan-siswa.kuitansiPDF');

    // TRANSAKSI SISWA
    Route::get('/transaksi/tabungan-siswa',  [TransaksiTabunganSiswa::class, 'index'])->name('transaksi.tabungan-siswa');
    Route::get('/transaksi/tabungan-siswa/{tabunganId}', [TransaksiTabunganSiswa::class, 'kuitansiPDF'])->name('transaksi.tabungan-siswa.kuitansiPDF');

    Route::get('/transaksi/edupay-siswa',  [TransaksiEduPaySiswa::class, 'index'])->name('transaksi.edupay-siswa');
    Route::get('/transaksi/edupay-siswa/{eduPayId}', [TransaksiEduPaySiswa::class, 'kuitansiPDF'])->name('transaksi.edupay-siswa.kuitansiPDF');
    // END TRANSAKSI SISWA

    // TRANSAKSI PEGAWAI
    Route::get('/transaksi/edupay-pegawai',  [TransaksiEduPayPegawai::class, 'index'])->name('transaksi.edupay-pegawai');
    Route::get('/transaksi/edupay-pegawai/{eduPayId}', [TransaksiEduPayPegawai::class, 'kuitansiPDF'])->name('transaksi.edupay-pegawai.kuitansiPDF');

    Route::get('/transaksi/tabungan-pegawai',  [TransaksiTabunganPegawai::class, 'index'])->name('transaksi.tabungan-pegawai');
    Route::get('/transaksi/tabungan-pegawai/{tabunganId}', [TransaksiTabunganPegawai::class, 'kuitansiPDF'])->name('transaksi.tabungan-pegawai.kuitansiPDF');
    // END TRANSAKSI PEGAWAI

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

    // LAPORAN TABUNGAN SISWA 
    Route::get('/laporan/tabungan-siswa',  [LaporanTabunganSiswa::class, 'index'])->name('laporan.tabungan-siswa');
    Route::get('/laporan/tabungan-siswa/pdf',  [LaporanTabunganSiswa::class, 'cetakPDF'])->name('laporan.tabungan-siswa.pdf');
    // END LAPORAN TABUNGAN SISWA

    // LAPORAN EDUPAY SISWA
    Route::get('/laporan/edupay-siswa',  [LaporanEduPaySiswa::class, 'index'])->name('laporan.edupay-siswa');
    Route::get('/laporan/edupay-siswa/pdf',  [LaporanEduPaySiswa::class, 'cetakPDF'])->name('laporan.edupay-siswa.pdf');
    // END LAPORAN EDUPAY SISWA

    Route::get('/laporan/rekapitulasi-keuangan',  [LaporanRekapitulasiKeuangan::class, 'index'])->name('laporan.rekapitulasi-keuangan');

    // LAPORAN TABUNGAN PEGAWAI
    Route::get('/laporan/tabungan-pegawai',  [LaporanTabunganPegawai::class, 'index'])->name('laporan.tabungan-pegawai');
    Route::get('/laporan/tabungan-pegawai/pdf',  [LaporanTabunganPegawai::class, 'cetakPDF'])->name('laporan.tabungan-pegawai.pdf');
    // END LAPORAN TABUNGAN PEGAWAI

    // LAPORAN EDUPAY PEGAWAI
    Route::get('/laporan/edupay-pegawai',  [LaporanEduPayPegawai::class, 'index'])->name('laporan.edupay-pegawai');
    Route::get('/laporan/edupay-pegawai/pdf',  [LaporanEduPayPegawai::class, 'cetakPDF'])->name('laporan.edupay-pegawai.pdf');
    // END LAPORAN EDUPAY PEGAWAI

    Route::get('/akuntansi/konfigurasi',  [AkuntansiKonfigurasi::class, 'index'])->name('akuntansi.konfigurasi');
    Route::get('/akuntansi/jurnal-detail',  [AkuntansiJurnalDetail::class, 'index'])->name('akuntansi.jurnal-detail');

    // laporan akuntansi
    Route::get('/akuntansi/laporan-buku-besar',  [AkuntansiLaporanBukuBesar::class, 'index'])->name('akuntansi.laporan-buku-besar');

    // jurnal keuangan
    Route::get('/akuntansi/laporan-jurnal-umum',  [AkuntansiLaporanJurnalUmum::class, 'index'])->name('akuntansi.laporan-jurnal-umum');
    Route::get('/akuntansi/laporan-jurnal-umum/pdf', [AkuntansiLaporanJurnalUmum::class, 'cetakPDF'])->name('akuntansi.laporan-jurnal-umum.pdf');
    // jurnal keuangan

    // neraca
    Route::get('/akuntansi/laporan-neraca',  [AkuntansiLaporanNeraca::class, 'index'])->name('akuntansi.laporan-neraca');
    Route::get('/akuntansi/laporan-neraca/pdf',  [AkuntansiLaporanNeraca::class, 'cetakPDF'])->name('akuntansi.laporan-neraca.pdf');
    // neraca

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

    // Arus Kas
    Route::get('/akuntansi/laporan-arus-kas', [AkuntansiLaporanArusKas::class, 'index'])->name('akuntansi.laporan-arus-kas');
    Route::get('/akuntansi/laporan-arus-kas/pdf',  [AkuntansiLaporanArusKas::class, 'cetakPDF'])->name('akuntansi.laporan-arus-kas.pdf');
    // Arus Kas

    Route::get('/akuntansi/transaksi-pendapatan',  [AkuntansiTransaksiPendapatan::class, 'index'])->name('akuntansi.transaksi-pendapatan');
});
