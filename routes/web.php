<?php

// portal

use App\Http\Controllers\Akademik\AkademikDashboard;
use App\Http\Controllers\Akademik\EkstrakurikulerSiswa;
use App\Http\Controllers\Akademik\JenjangController;
use App\Http\Controllers\Akademik\KelasController;
use App\Http\Controllers\Akademik\LaporanController;
use App\Http\Controllers\Akademik\PenempatanEkstrakurikulerSiswa;
use App\Http\Controllers\Akademik\PenempatanSiswaController;
use App\Http\Controllers\Akademik\SiswaController;
use App\Http\Controllers\Akademik\TahunAjaranController;
use App\Http\Controllers\Portal\PortalController;

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Keuangan\AkuntansiJurnalDetail;
use App\Http\Controllers\Keuangan\AkuntansiKonfigurasiJurnal;
use App\Http\Controllers\Keuangan\AkuntansiLaporanArusKas;
use App\Http\Controllers\Keuangan\AkuntansiLaporanBukuBesar;
use App\Http\Controllers\Keuangan\AkuntansiLaporanJurnalUmum;
use App\Http\Controllers\Keuangan\AkuntansiLaporanLabaRugi;
use App\Http\Controllers\Keuangan\AkuntansiLaporanNeraca;
use App\Http\Controllers\Keuangan\AkuntansiLaporanPendapatan;
use App\Http\Controllers\Keuangan\AkuntansiLaporanPengeluaran;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\Keuangan\DokumenAdministrasi;
use App\Http\Controllers\Keuangan\JenisTagihanSiswa;
use App\Http\Controllers\Keuangan\JenjangTahunAjar;
use App\Http\Controllers\Keuangan\KelasSiswa;
use App\Http\Controllers\Keuangan\KeuanganDashboard;
use App\Http\Controllers\Keuangan\KonfigurasiTagihanSiswa;
use App\Http\Controllers\Keuangan\LaporanEduPayPegawai;
use App\Http\Controllers\Keuangan\LaporanEduPaySiswa;
use App\Http\Controllers\Keuangan\LaporanPembayaranTagihanSiswa;
use App\Http\Controllers\Keuangan\LaporanRekapitulasiKeuanganSiswa;
use App\Http\Controllers\Keuangan\LaporanTabunganPegawai;
use App\Http\Controllers\Keuangan\LaporanTabunganSiswa;
use App\Http\Controllers\Keuangan\LaporanTagihanSiswa;
use App\Http\Controllers\Keuangan\PenggunaJenjang;
use App\Http\Controllers\Keuangan\TagihanSiswa;
use App\Http\Controllers\Keuangan\TransaksiEduPayPegawai;
use App\Http\Controllers\Keuangan\TransaksiEduPaySiswa;
use App\Http\Controllers\Keuangan\TransaksiPendapatanLainnya;
use App\Http\Controllers\Keuangan\TransaksiPengeluaran;
use App\Http\Controllers\Keuangan\TransaksiTabunganPegawai;
use App\Http\Controllers\Keuangan\TransaksiTabunganSiswa;
use App\Http\Controllers\Keuangan\TransaksiTagihanSiswa;
use App\Http\Controllers\KoperasiPintarAdministrasiProduk;
use App\Http\Controllers\KoperasiPintarDashboard;
use App\Http\Controllers\KoperasiPintarPembelianProduk;
use App\Http\Controllers\KoperasiPintarPengembalianProduk;
use App\Http\Controllers\KoperasiPintarPenjualanProduk;
use App\Http\Controllers\LandingEkstrakurikuler;
use App\Http\Controllers\ManajemenKepegawaian;
use App\Http\Controllers\SmartCanteenAdministrasiKantin;
use App\Http\Controllers\SmartCanteenAdministrasiProduk;
use App\Http\Controllers\SmartCanteenDashboard;
use App\Http\Controllers\SmartCanteenLaporanTransaksi;
use App\Http\Controllers\SmartCanteenSettlementTransaksi;
use App\Http\Controllers\SmartCanteenTransaksiProduk;
use App\Http\Controllers\SmartPassAdministrasiPegawai;
use App\Http\Controllers\SmartPassDashboard;
use App\Http\Controllers\SmartPassLaporanFingerSpotPegawai;
use App\Http\Controllers\SmartPassLaporanPresensiPegawai;
use App\Http\Controllers\SmartPassPresensiPegawai;

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


// LANDING
Route::get('/landing/ekstrakurikuler', [LandingEkstrakurikuler::class, 'index'])->name('landing.ekstrakurikuler');

// UNTUK MENGARAHKAN HALAMAN YG LOGIN
// Route::get('/', function () {
//     // Jika belum login → tampilkan halaman login
//     if (!auth()->check()) {
//         return redirect()->route('login.index');
//     }

//     // Jika sudah login → arahkan sesuai role
//     $user = auth()->user();

//     if ($user->peran === 'KANTIN') {
//         return redirect()->route('smartCanteen.dashboard');
//     }

//     return redirect()->route('dashboard.index');
// })->name('home');

/*
|--------------------------------------------------------------------------
| Portal
|--------------------------------------------------------------------------
*/

Route::get('/', [PortalController::class, 'index'])
    ->name('portal');
/*
|--------------------------------------------------------------------------
| Log-In
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'index'])
    ->name('login.index')
    ->middleware('guest');

Route::post('/login', [LoginController::class, 'authenticate'])
    ->name('login.authenticate');

Route::post('/logout', [LoginController::class, 'logOut'])
    ->name('logout');



// Route::middleware(['auth', 'peran:SUPERADMIN,ADMINISTRASI,KANTIN'])->group(function () {
//     // SMARTCANTEEN 
//     Route::get('/smartCanteen/dashboard', [SmartCanteenDashboard::class, 'index'])->name('smartCanteen.dashboard');

//     Route::get('/smartCanteen/administrasi/kantin', [SmartCanteenAdministrasiKantin::class, 'index'])->name('smartCanteen.administrasi.kantin');
//     Route::get('/smartCanteen/administrasi/produk', [SmartCanteenAdministrasiProduk::class, 'index'])->name('smartCanteen.administrasi.produk');

//     Route::get('/smartCanteen/transaksi/produk', [SmartCanteenTransaksiProduk::class, 'index'])->name('smartCanteen.transaksi.produk');

//     Route::get('/smartCanteen/laporan/transaksi',  [SmartCanteenLaporanTransaksi::class, 'index'])->name('smartCanteen.laporan.transaksi');
//     Route::get('/smartCanteen/laporan/transaksi/pdf',  [SmartCanteenLaporanTransaksi::class, 'cetakPDF'])->name('smartCanteen.laporan.transaksi.pdf');

//     Route::get('/smartCanteen/settlement/transaksi',  [SmartCanteenSettlementTransaksi::class, 'index'])->name('smartCanteen.settlement.transaksi');
//     // END SMARTCANTEEN 

//     // KOPERASIPINTAR
//     // master
//     Route::get('/koperasiPintar/dashboard', [KoperasiPintarDashboard::class, 'index'])->name('koperasiPintar.dashboard');
//     Route::get('/koperasiPintar/administrasi/produk', [KoperasiPintarAdministrasiProduk::class, 'index'])->name('koperasiPintar.administrasi.produk');

//     // transaksi
//     Route::get('/koperasiPintar/pembelian/produk', [KoperasiPintarPembelianProduk::class, 'index'])->name('koperasiPintar.pembelian.produk');
//     Route::get('/koperasiPintar/pengembalian/produk', [KoperasiPintarPengembalianProduk::class, 'index'])->name('koperasiPintar.pengembalian.produk');
//     Route::get('/koperasiPintar/penjualan/produk', [KoperasiPintarPenjualanProduk::class, 'index'])->name('koperasiPintar.penjualan.produk');
//     // END KOPERASIPINTAR

//     // SMARTPASS
//     Route::get('/smartPass/dashboard', [SmartPassDashboard::class, 'index'])->name('smartPass.dashboard');
    
//     Route::get('/smartPass/administrasi/pegawai', [SmartPassAdministrasiPegawai::class, 'index'])->name('smartPass.administrasi.pegawai');
//     Route::get('/smartPass/administrasi/siswa', [SmartPassAdministrasiPegawai::class, 'index'])->name('smartPass.administrasi.siswa');

//     Route::get('/smartPass/laporan/pegawai', [SmartPassLaporanPresensiPegawai::class, 'index'])->name('smartPass.laporan.pegawai');
//     Route::get('/smartPass/laporan-fingerspot/pegawai', [SmartPassLaporanFingerSpotPegawai::class, 'index'])->name('smartPass.laporan-fingerspot.pegawai');

//     Route::get('/smartPass/presensi/pegawai', [SmartPassPresensiPegawai::class, 'index'])->name('smartPass.presensi.pegawai');
//     // SMARTPASS
// });


// SISTEM
// JENJANG TAHUN AJAR
// Route::middleware(['auth', 'peran:SUPERADMIN,ADMINISTRASI'])->group(function () {
//     Route::middleware(['auth'])->get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

//     Route::get('/administrasi/manajemen-kepegawaian',  [ManajemenKepegawaian::class, 'index'])->name('administrasi.manajemen-kepegawaian');

Route::middleware(['auth', 'peran:SUPERADMIN, ADMIN, KEUANGAN'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Akademik
    |--------------------------------------------------------------------------
    */

    Route::prefix('akademik')
        ->name('akademik.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */

            Route::get('/', [AkademikDashboard::class, 'index'])
                ->name('dashboard');

            Route::get('/jenjang', [JenjangController::class, 'index'])
                ->name('jenjang');

            Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])
                ->name('tahun-ajaran');

            Route::get('/kelas', [KelasController::class, 'index'])
                ->name('kelas');

            Route::get('/siswa', [SiswaController::class, 'index'])
                ->name('siswa');

            Route::get('/penempatan-siswa', [PenempatanSiswaController::class, 'index'])
                ->name('penempatan-siswa');


            /*
            |--------------------------------------------------------------------------
            | Ekstrakurikuler
            |--------------------------------------------------------------------------
            */

            Route::get('/ekstrakurikuler', [EkstrakurikulerSiswa::class, 'index'])
                ->name('ekstrakurikuler');

            Route::get('/penempatan-ekstrakurikuler', [PenempatanEkstrakurikulerSiswa::class, 'index'])
                ->name('penempatan-ekstrakurikuler');

            Route::get('/ekstrakurikuler-siswa/pdf', [EkstrakurikulerSiswa::class, 'cetakPDF'])
                ->name('ekstrakurikuler-siswa.pdf');

            Route::get('/ekstrakurikuler-siswa/siswapdf', [EkstrakurikulerSiswa::class, 'cetakSiswaPDF'])
                ->name('ekstrakurikuler-siswa.siswapdf');

            Route::get('/laporan', [LaporanController::class, 'index'])
                ->name('laporan');
        });

    Route::prefix('keuangan')
        ->name('keuangan.')
        ->group(function () {

            /*
            |--------------------------------------------------------------------------
            | Dashboard
            |--------------------------------------------------------------------------
            */
            Route::get('/', [KeuanganDashboard::class, 'index'])
                ->name('dashboard');

             /*
            |--------------------------------------------------------------------------
            | Sistem
            |--------------------------------------------------------------------------
            */
            Route::prefix('sistem')
                ->name('sistem.')
                ->group(function () {

                    Route::get('/jenjang-tahun-ajar', [JenjangTahunAjar::class, 'index'])
                        ->name('jenjang-tahun-ajar');

                    Route::get('/dokumen-administrasi', [DokumenAdministrasi::class, 'index'])
                        ->name('dokumen-administrasi');

                    Route::get('/pengguna-jenjang', [PenggunaJenjang::class, 'index'])
                        ->name('pengguna-jenjang');
                });

            /*
            |--------------------------------------------------------------------------
            | Administrasi
            |--------------------------------------------------------------------------
            */
            Route::prefix('administrasi')
                ->name('administrasi.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Kelas Siswa
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/kelas-siswa', [KelasSiswa::class, 'index'])
                        ->name('kelas-siswa');

                });




            /*
            |--------------------------------------------------------------------------
            | Tagihan
            |--------------------------------------------------------------------------
            */
            Route::prefix('tagihan')
                ->name('tagihan.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Konfigurasi Tagihan
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/konfigurasi', [KonfigurasiTagihanSiswa::class, 'index'])
                        ->name('konfigurasi');


                    /*
                    |--------------------------------------------------------------------------
                    | Tagihan Siswa
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/siswa', [TagihanSiswa::class, 'index'])
                        ->name('siswa');

                    Route::get('/siswa/pdf', [TagihanSiswa::class, 'cetakPDF'])
                        ->name('siswa.pdf');

                    Route::get('/siswa/detail-pdf', [TagihanSiswa::class, 'detailPDF'])
                        ->name('siswa.detail-pdf');


                    /*
                    |--------------------------------------------------------------------------
                    | Jenis Tagihan
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/jenis', [JenisTagihanSiswa::class, 'index'])
                        ->name('jenis');

                    Route::get('/jenis/pdf', [JenisTagihanSiswa::class, 'cetakPDF'])
                        ->name('jenis.pdf');

                    Route::get('/jenis/detail-pdf', [JenisTagihanSiswa::class, 'detailPDF'])
                        ->name('jenis.detail-pdf');
                });


            /*
            |--------------------------------------------------------------------------
            | Transaksi
            |--------------------------------------------------------------------------
            */
            Route::prefix('transaksi')
                ->name('transaksi.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Tagihan Siswa
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/tagihan-siswa', [TransaksiTagihanSiswa::class, 'index'])
                        ->name('tagihan-siswa');

                    Route::get('/tagihan-siswa/{transaksiId}', [TransaksiTagihanSiswa::class, 'kuitansiPDF'])
                        ->name('tagihan-siswa.kuitansi');


                    /*
                    |--------------------------------------------------------------------------
                    | Transaksi Siswa
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/tabungan-siswa', [TransaksiTabunganSiswa::class, 'index'])
                        ->name('tabungan-siswa');

                    Route::get('/tabungan-siswa/{tabunganId}', [TransaksiTabunganSiswa::class, 'kuitansiPDF'])
                        ->name('tabungan-siswa.kuitansi');

                    Route::get('/edupay-siswa', [TransaksiEduPaySiswa::class, 'index'])
                        ->name('edupay-siswa');

                    Route::get('/edupay-siswa/{eduPayId}', [TransaksiEduPaySiswa::class, 'kuitansiPDF'])
                        ->name('edupay-siswa.kuitansi');


                    /*
                    |--------------------------------------------------------------------------
                    | Transaksi Pegawai
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/edupay-pegawai', [TransaksiEduPayPegawai::class, 'index'])
                        ->name('edupay-pegawai');

                    Route::get('/edupay-pegawai/{eduPayId}', [TransaksiEduPayPegawai::class, 'kuitansiPDF'])
                        ->name('edupay-pegawai.kuitansi');

                    Route::get('/tabungan-pegawai', [TransaksiTabunganPegawai::class, 'index'])
                        ->name('tabungan-pegawai');

                    Route::get('/tabungan-pegawai/{tabunganId}', [TransaksiTabunganPegawai::class, 'kuitansiPDF'])
                        ->name('tabungan-pegawai.kuitansi');


                    /*
                    |--------------------------------------------------------------------------
                    | Pendapatan
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/pendapatan-lainnya', [TransaksiPendapatanLainnya::class, 'index'])
                        ->name('pendapatan-lainnya');

                    Route::get('/pendapatan-lainnya/pdf', [TransaksiPendapatanLainnya::class, 'cetakPDF'])
                        ->name('pendapatan-lainnya.pdf');


                    /*
                    |--------------------------------------------------------------------------
                    | Pengeluaran
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/pengeluaran', [TransaksiPengeluaran::class, 'index'])
                        ->name('pengeluaran');

                    Route::get('/pengeluaran/pdf', [TransaksiPengeluaran::class, 'cetakPDF'])
                        ->name('pengeluaran.pdf');
                });
            

            /*
            |--------------------------------------------------------------------------
            | Laporan
            |--------------------------------------------------------------------------
            */
            Route::prefix('laporan')
                ->name('laporan.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Pembayaran
                    |--------------------------------------------------------------------------
                    */
                    Route::prefix('pembayaran')
                        ->name('pembayaran.')
                        ->group(function () {

                            Route::get('/tagihan-siswa', [LaporanPembayaranTagihanSiswa::class, 'index'])
                                ->name('tagihan-siswa');

                            Route::get('/tagihan-siswa/pdf', [LaporanPembayaranTagihanSiswa::class, 'cetakPDF'])
                                ->name('tagihan-siswa.pdf');
                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Tagihan
                    |--------------------------------------------------------------------------
                    */
                    Route::prefix('tagihan')
                        ->name('tagihan.')
                        ->group(function () {

                            Route::get('/siswa', [LaporanTagihanSiswa::class, 'index'])
                                ->name('siswa');

                            Route::get('/siswa/{msPenempatanSiswaId}', [LaporanTagihanSiswa::class, 'generatePDF'])
                                ->name('siswa.pdf');

                            Route::get('/kelas/{ms_kelas_id}', [LaporanTagihanSiswa::class, 'generatePDFByClass'])
                                ->name('kelas.pdf');
                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Siswa
                    |--------------------------------------------------------------------------
                    */
                    Route::prefix('siswa')
                        ->name('siswa.')
                        ->group(function () {

                            /*
                            |--------------------------------------------------------------------------
                            | Tabungan Siswa
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/tabungan', [LaporanTabunganSiswa::class, 'index'])
                                ->name('tabungan');

                            Route::get('/tabungan/pdf', [LaporanTabunganSiswa::class, 'cetakPDF'])
                                ->name('tabungan.pdf');

                            Route::get('/tabungan/saldo/pdf', [LaporanTabunganSiswa::class, 'cetakSaldoPDF'])
                                ->name('tabungan.saldo.pdf');


                            /*
                            |--------------------------------------------------------------------------
                            | EduPay Siswa
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/edupay', [LaporanEduPaySiswa::class, 'index'])
                                ->name('edupay');

                            Route::get('/edupay/pdf', [LaporanEduPaySiswa::class, 'cetakPDF'])
                                ->name('edupay.pdf');

                            Route::get('/edupay/saldo/pdf', [LaporanEduPaySiswa::class, 'cetakSaldoPDF'])
                                ->name('edupay.saldo.pdf');
                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Pegawai
                    |--------------------------------------------------------------------------
                    */
                    Route::prefix('pegawai')
                        ->name('pegawai.')
                        ->group(function () {

                            /*
                            |--------------------------------------------------------------------------
                            | Tabungan Pegawai
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/tabungan', [LaporanTabunganPegawai::class, 'index'])
                                ->name('tabungan');

                            Route::get('/tabungan/pdf', [LaporanTabunganPegawai::class, 'cetakPDF'])
                                ->name('tabungan.pdf');

                            Route::get('/tabungan/saldo/pdf', [LaporanTabunganPegawai::class, 'cetakSaldoPDF'])
                                ->name('tabungan.saldo.pdf');


                            /*
                            |--------------------------------------------------------------------------
                            | EduPay Pegawai
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/edupay', [LaporanEduPayPegawai::class, 'index'])
                                ->name('edupay');

                            Route::get('/edupay/pdf', [LaporanEduPayPegawai::class, 'cetakPDF'])
                                ->name('edupay.pdf');

                            Route::get('/edupay/saldo/pdf', [LaporanEduPayPegawai::class, 'cetakSaldoPDF'])
                                ->name('edupay.saldo.pdf');
                        });


                    /*
                    |--------------------------------------------------------------------------
                    | Rekapitulasi
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/rekapitulasi-keuangan-siswa', [LaporanRekapitulasiKeuanganSiswa::class, 'index'])
                        ->name('rekapitulasi-keuangan-siswa');
                });

            /*
            |--------------------------------------------------------------------------
            | Akuntansi
            |--------------------------------------------------------------------------
            */
            Route::prefix('akuntansi')
                ->name('akuntansi.')
                ->group(function () {

                    /*
                    |--------------------------------------------------------------------------
                    | Konfigurasi
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/konfigurasi', [AkuntansiKonfigurasiJurnal::class, 'index'])
                        ->name('konfigurasi');


                    /*
                    |--------------------------------------------------------------------------
                    | Jurnal
                    |--------------------------------------------------------------------------
                    */
                    Route::get('/jurnal-detail', [AkuntansiJurnalDetail::class, 'index'])
                        ->name('jurnal-detail');


                    /*
                    |--------------------------------------------------------------------------
                    | Laporan
                    |--------------------------------------------------------------------------
                    */
                    Route::prefix('laporan')
                        ->name('laporan.')
                        ->group(function () {

                            /*
                            |--------------------------------------------------------------------------
                            | Buku Besar
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/buku-besar', [AkuntansiLaporanBukuBesar::class, 'index'])
                                ->name('buku-besar');

                            Route::get('/buku-besar/pdf', [AkuntansiLaporanBukuBesar::class, 'cetakPDF'])
                                ->name('buku-besar.pdf');


                            /*
                            |--------------------------------------------------------------------------
                            | Jurnal Umum
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/jurnal-umum', [AkuntansiLaporanJurnalUmum::class, 'index'])
                                ->name('jurnal-umum');

                            Route::get('/jurnal-umum/pdf', [AkuntansiLaporanJurnalUmum::class, 'cetakPDF'])
                                ->name('jurnal-umum.pdf');


                            /*
                            |--------------------------------------------------------------------------
                            | Neraca
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/neraca', [AkuntansiLaporanNeraca::class, 'index'])
                                ->name('neraca');

                            Route::get('/neraca/pdf', [AkuntansiLaporanNeraca::class, 'cetakPDF'])
                                ->name('neraca.pdf');


                            /*
                            |--------------------------------------------------------------------------
                            | Pendapatan
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/pendapatan', [AkuntansiLaporanPendapatan::class, 'index'])
                                ->name('pendapatan');

                            Route::get('/pendapatan/pdf', [AkuntansiLaporanPendapatan::class, 'cetakPDF'])
                                ->name('pendapatan.pdf');


                            /*
                            |--------------------------------------------------------------------------
                            | Pengeluaran
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/pengeluaran', [AkuntansiLaporanPengeluaran::class, 'index'])
                                ->name('pengeluaran');

                            Route::get('/pengeluaran/pdf', [AkuntansiLaporanPengeluaran::class, 'cetakPDF'])
                                ->name('pengeluaran.pdf');


                            /*
                            |--------------------------------------------------------------------------
                            | Laba Rugi
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/laba-rugi', [AkuntansiLaporanLabaRugi::class, 'index'])
                                ->name('laba-rugi');

                            Route::get('/laba-rugi/pdf', [AkuntansiLaporanLabaRugi::class, 'cetakPDF'])
                                ->name('laba-rugi.pdf');


                            /*
                            |--------------------------------------------------------------------------
                            | Arus Kas
                            |--------------------------------------------------------------------------
                            */
                            Route::get('/arus-kas', [AkuntansiLaporanArusKas::class, 'index'])
                                ->name('arus-kas');

                            Route::get('/arus-kas/pdf', [AkuntansiLaporanArusKas::class, 'cetakPDF'])
                                ->name('arus-kas.pdf');
                        });
                });
        });
});
