<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PenempatanSiswa;
use App\Models\SuratTagihanSiswa;
use Elibyy\TCPDF\Facades\TCPDF;
use Carbon\Carbon;

class LaporanTagihanSiswa extends Controller
{
    public function index()
    {
        return view('LAPORAN.tagihan-siswa.v_index');
    }
    
    public function generatePDF($msPenempatanSiswaId)
    {
        // ================================
        // 1. Ambil parameter dari query string
        // ================================
        $selectedJenjang = request()->query('selectedJenjang');

        $selectedJenisTagihan = request()->query('selectedJenisTagihan')
            ? json_decode(request()->query('selectedJenisTagihan'), true)
            : [];

        $selectedKategoriTagihan = request()->query('selectedKategoriTagihan')
            ? json_decode(request()->query('selectedKategoriTagihan'), true)
            : [];

        $endDate = request()->query('endDate');

        if (!empty($endDate) && Carbon::hasFormat($endDate, 'Y-m-d')) {
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
        } else {
            $endDate = Carbon::now()->endOfMonth();
        }

        // ================================
        // 2. Ambil penempatan siswa
        // ================================
        $penempatanSiswa = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',

            'ms_tagihan_siswa' => function ($q) use (
                $selectedJenisTagihan,
                $selectedKategoriTagihan,
                $endDate
            ) {
                $q->with([
                    'ms_jenis_tagihan_siswa',
                ])

                // Total pembayaran tanpa transaksi N+1
                ->withSum([
                    'dt_transaksi_tagihan_siswa as jumlah_sudah_dibayar' => function ($q) {
                        $q->where(
                            'dt_transaksi_tagihan_siswa.status_transaksi',
                            '!=',
                            'dibatalkan'
                        );
                    }
                ], 'jumlah_bayar')

                // Hanya tagihan belum lunas
                ->where('status', '!=', 'Lunas')

                // Jatuh tempo sampai tanggal laporan
                ->whereHas('ms_jenis_tagihan_siswa', function ($q) use (
                    $selectedKategoriTagihan,
                    $endDate
                ) {
                    $q->where('tanggal_jatuh_tempo', '<=', $endDate);

                    if (!empty($selectedKategoriTagihan)) {
                        $q->whereIn(
                            'ms_kategori_tagihan_siswa_id',
                            $selectedKategoriTagihan
                        );
                    }
                });

                // Filter jenis tagihan
                if (!empty($selectedJenisTagihan)) {
                    $q->whereIn(
                        'ms_jenis_tagihan_siswa_id', $selectedJenisTagihan
                    );
                }
            },
        ])->find($msPenempatanSiswaId);


        // ================================
        // 3. Validasi
        // ================================
        if (!$penempatanSiswa) {
            return response()->json([
                'error' => 'Penempatan Siswa tidak ditemukan'
            ], 404);
        }


        // ================================
        // 4. Ambil template surat
        // ================================
        $surat = SuratTagihanSiswa::where(
            'ms_jenjang_id',
            $selectedJenjang
        )->first();

        if (!$surat) {
            return response()->json([
                'error' => 'Template surat tidak ditemukan'
            ], 404);
        }

       // ================================
        // 5. Hitung total tagihan
        // ================================
        $totalTagihan = $penempatanSiswa->ms_tagihan_siswa->sum(
            function ($tagihan) {
                $dibayar = (float) ($tagihan->jumlah_sudah_dibayar ?? 0);

                return max(
                    0,
                    (float) $tagihan->jumlah_tagihan_siswa - $dibayar
                );
            }
        );

        // ================================
        // 6. Data siswa
        // ================================
        $namaSiswa = $penempatanSiswa->ms_siswa->nama_siswa ?? 'N/A';
        $namaKelas = $penempatanSiswa->ms_kelas->nama_kelas ?? 'N/A';

        // Inisialisasi TCPDF
        // $pdf = new TCPDF();
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape
        // $pdf = new TCPDF('P', 'mm', array(210, 330), true, 'UTF-8', false); // Portrait F4

        $pdf::SetMargins(0, 0, 0); // kiri, atas, kanan
        $pdf::SetHeaderMargin(0);    // margin header
        $pdf::SetFooterMargin(10);    // margin footer
        $pdf::SetAutoPageBreak(TRUE, 20); // jarak bawah

        $pdf::SetTitle('Tagihan Siswa');
        $pdf::AddPage();
        $pdf::SetFont('times', '', 12);

        // HTML untuk header dengan tabel
        $kopPath = storage_path('app/public/' . $surat->foto_kop);
        if (!file_exists($kopPath)) {
            return response()->json(['error' => 'Kop surat tidak ditemukan di path ' . $kopPath], 404);
        }

        $kopBase64 = 'data:image/' . pathinfo($kopPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($kopPath));

        $htmlHeader = '
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="text-align: center;">
                        <img src="' . $kopBase64 . '" width="1500px"/>
                    </td>
                </tr>
            </table>
            ';
        // Menulis HTML ke dalam PDF
        $pdf::writeHTML($htmlHeader, true, false, true, false, '');
        // Kembalikan margin isi

        // $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        // $stylet = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        // $pdf::Line(10, 46, 202, 46, $style);
        // $pdf::Line(10, 47, 202, 47, $stylet);

        // Detail Surat
        $kop = '
            <table cellpadding="1">
                <tr>
                    <td width="100%" style="text-align: right;">' . $surat->tempat_tanggal . '</td>
                </tr>
                <tr>
                    <td width="12%"><b>No</b></td>
                    <td width="78%">: ' . $surat->nomor_surat . '</td>
                </tr>
                <tr>
                    <td><b>Lampiran</b></td>
                    <td>: ' . $surat->lampiran . '</td>
                </tr>
                <tr>
                    <td><b>Hal</b></td>
                    <td>: ' . $surat->hal . '</td>
                </tr>
            </table>';
        $pdf::SetMargins(20, 5, 20);
        $pdf::SetX(20); //agar ke kiri 20
        $pdf::writeHTML($kop, true, false, true, false, '');

        $alamatTujuan = '<table border="0">
                <tr>
                    <td width="100%" align="left">Kepada Yth.</td>
                </tr>
                <tr>
                    <td width="100%" align="left">Bapak/Ibu Wali Murid Ananda <i>' . $namaSiswa . '</i></td>
                </tr>
                <tr>
                    <td width="100%" align="left">' . $namaKelas . '</td>
                </tr>
            </table>';

        $pdf::writeHTML($alamatTujuan, true, false, true, false, '');

        // Salam Pembuka
        $salamPembuka = '<table border="0">
                <tr>
                    <td width="100%" align="left">' . $surat->salam_pembuka . '</td>
                </tr>
            </table>';
        $pdf::writeHTML($salamPembuka, true, false, true, false, '');
        // Pembuka
        $pembuka = '<table border="0">
                <tr>
                    <td width="100%" style="text-indent: 20px;" align="justify">' . $surat->pembuka . '</td>
                </tr>
            </table>';
        $pdf::writeHTML($pembuka, true, false, true, false, '');

        // Latar Belakang
        $isi = '<table border="0">
                <tr>
                    <td width="100%" style="text-indent: 20px;" align="justify">' . $surat->isi . '</td>
                </tr>
            </table>';
        $pdf::writeHTML($isi, true, false, true, false, '');

        $rincianTagihan = '<table border="0">
                <tr>
                    <td width="100%" style="text-indent: 20px;" align="justify">' . $surat->rincian . '<b>Rp' . number_format($totalTagihan, 0, ',', '.') . '</b> dengan rincian terlampir</td>
                </tr>
            </table>';

        if (!empty($surat->rincian)) {
            $pdf::writeHTML($rincianTagihan, true, false, true, false, '');
        };

        $instruksi = '<table border="0">';

        if (!empty($surat->panduan)) {
            $instruksi .= '<tr>
                <td width="100%">' . $surat->panduan . '</td>
            </tr>';
        }
        if (!empty($surat->instruksi_1)) {
            $instruksi .= '<tr>
                <td width="100%" align="justify">' . $surat->instruksi_1 . '</td>
            </tr>';
        }
        if (!empty($surat->instruksi_2)) {
            $instruksi .= '<tr>
                <td width="100%" align="justify">' . $surat->instruksi_2 . '</td>
            </tr>';
        }
        if (!empty($surat->instruksi_3)) {
            $instruksi .= '<tr>
                <td width="100%" align="justify">' . $surat->instruksi_3 . '</td>
            </tr>';
        }
        if (!empty($surat->instruksi_4)) {
            $instruksi .= '<tr>
                <td width="100%" align="justify">' . $surat->instruksi_4 . '</td>
            </tr>';
        }
        if (!empty($surat->instruksi_5)) {
            $instruksi .= '<tr>
                <td width="100%" align="justify">' . $surat->instruksi_5 . '</td>
            </tr>';
        }

        $instruksi .= '</table>';

        $pdf::writeHTML($instruksi, true, false, true, false, '');

        // Penutup
        $penutup = '<table border="0">
                <tr>
                    <td width="100%" style="text-indent: 20px;" align="justify">' . $surat->penutup . '</td>
                </tr>
            </table>';
        $pdf::writeHTML($penutup, true, false, true, false, '');

        // Salam Penutup
        $salamPenutup = '<table border="0">
                <tr>
                    <td width="100%" align="justify">' . $surat->salam_penutup . '</td>
                </tr>
            </table>';
        $pdf::writeHTML($salamPenutup, true, false, true, false, '');

        $tandaTanganPath = storage_path('app/public/' . $surat->tanda_tangan);
        if (!file_exists($tandaTanganPath)) {
            return response()->json(['error' => 'Kop surat tidak ditemukan di path ' . $tandaTanganPath], 404);
        }

        $tandaTanganBase64 = 'data:image/' . pathinfo($tandaTanganPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($tandaTanganPath));

        $tandaTangan = '<table border="0">
                <tr>
                    <td width="350px" align="left"></td>
                    <td width="230px" align="left">' . $surat->jabatan . '</td>
                </tr>
                <tr>
                    <td width="330px" align="left"></td>
                    <td width="230px" align="left"><img src="' . $tandaTanganBase64 . '" height="60px"></td>
                </tr>
                <tr>
                    <td width="350px" align="left"></td>
                    <td width="230px" align="left">' . $surat->nama_petugas . '</td>
                </tr>';

        if (!empty($surat->nomor_petugas)) {
            $tandaTangan .= '<tr>
                        <td width="350px" align="left"></td>
                        <td width="230px" align="left">' . $surat->nomor_petugas . '</td>
                    </tr>';
        }

        $tandaTangan .= '</table>';
        // output the HTML content
        $pdf::writeHTML($tandaTangan, true, false, true, false, '');

        $pdf::AddPage();
        $pdf::SetMargins(0, 0, 0); // kiri, atas, kanan

        $htmlHeader = '
            <table border="0" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="text-align: center;">
                        <img src="' . $kopBase64 . '" width="1500px"/>
                    </td>
                </tr>
            </table>
            ';

        $pdf::writeHTML($htmlHeader, true, false, true, false, '');
        $pdf::SetMargins(20, 5, 20);
        $pdf::SetX(20); //agar ke kiri 20
        
        // $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        // $stylet = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        // $pdf::Line(10, 46, 202, 46, $style);
        // $pdf::Line(10, 47, 202, 47, $stylet);


        // Rincian Tagihan
        $htmlTagihan = "<p><b>Rincian Tagihan Administrasi Sekolah</b></p>";

        $htmlTagihan .= '<table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tagihan</th>
                            <th>Estimasi</th>
                            <th>Dibayarkan</th>
                            <th>Kekurangan</th>
                        </tr>
                    </thead>
                    <tbody>';

        $totalTagihan = 0;

        foreach ($penempatanSiswa->ms_tagihan_siswa as $tagihan) {
            $kekurangan = $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar;

            if ($kekurangan <= 0) {
                continue; // Skip tagihan yang sudah lunas
            }

            $namaTagihan = strtoupper($tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? 'Tidak Ditemukan');
            $jatuhTempo = $tagihan->tanggal_jatuh_tempo
                ? HelperController::formatTanggalIndonesia($tagihan->tanggal_jatuh_tempo, 'd F Y')
                : 'Tidak Ditentukan';

            // <td>{$jatuhTempo}</td>

            $htmlTagihan .= "<tr>
                        <td>{$namaTagihan}</td>
                        <td>Rp" . number_format($tagihan->jumlah_tagihan_siswa, 0, ',', '.') . "</td>
                        <td>Rp" . number_format($tagihan->jumlah_sudah_dibayar, 0, ',', '.') . "</td>
                        <td>Rp" . number_format($kekurangan, 0, ',', '.') . "</td>
                     </tr>";
            $totalTagihan += $kekurangan;
        }

        $htmlTagihan .= '</tbody></table>';

        // Menambahkan rincian tagihan ke PDF
        $pdf::writeHTML($htmlTagihan, true, false, true, false, '');

        $pdf::SetMargins(20, 5, 20);
        $pdf::SetX(20); //agar ke kiri 20
        
        // Menambahkan Total Tagihan
        $totalTagihanHtml = "<h4>Total Kekurangan: Rp" . number_format($totalTagihan, 0, ',', '.') . "</h4>";
        $pdf::writeHTML($totalTagihanHtml, true, false, true, false, '');
        $pdf::Ln(2);

        $catatan = '<table border="0">';

        if (!empty($surat->catatan_1)) {
            $catatan .= '<tr>
                            <td width="100%">' . $surat->catatan_1 . '</td>                
                        </tr>';
        }

        if (!empty($surat->catatan_2)) {
            $catatan .= '<tr>
                            <td width="100%">' . $surat->catatan_2 . '</td>                
                        </tr>';
        }

        if (!empty($surat->catatan_3)) {
            $catatan .= '<tr>
                            <td width="100%">' . $surat->catatan_3 . '</td>                
                        </tr>';
        }

        $catatan .= '</table>';

        $pdf::writeHTML($catatan, true, false, true, false, '');

        // Output PDF
        $pdf::Output('Surat_Tagihan_' . $namaSiswa . '.pdf', 'I');
    }

    public function generatePDFByClass($ms_kelas_id)
    {
        // ================================
        // 1. Ambil parameter dari query string
        // ================================
        $selectedJenjang = request()->query('selectedJenjang');

        $selectedJenisTagihan = request()->query('selectedJenisTagihan')
            ? json_decode(request()->query('selectedJenisTagihan'), true)
            : [];

        $selectedKategoriTagihan = request()->query('selectedKategoriTagihan')
            ? json_decode(request()->query('selectedKategoriTagihan'), true)
            : [];

        $endDate = request()->query('endDate');

        if (!empty($endDate) && Carbon::hasFormat($endDate, 'Y-m-d')) {
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();
        } else {
            $endDate = Carbon::now()->endOfMonth();
        }

        // ==========================================================
        // 2) Ambil seluruh siswa dalam kelas
        // ==========================================================
        $penempatanSiswaList = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa',
        ])
            ->where('ms_kelas_id', $ms_kelas_id)
            ->where('ms_jenjang_id', $selectedJenjang)
            ->pluck('ms_penempatan_siswa_id')   // <-- penting
            ->toArray();                        // <-- biar pasti array

        // return response()->json([
        //     'selectedJenjang' => $selectedJenjang,
        //     'penempatanSiswaList' => $penempatanSiswaList,
        //     'selectedJenisTagihan' => $selectedJenisTagihan,
        //     'selectedKategoriTagihan' => $selectedKategoriTagihan,
        //     'endDate' => $endDate,
        // ]);

        // Pastikan `penempatanSiswaList` tidak kosong
        if (empty($penempatanSiswaList) || !is_array($penempatanSiswaList)) {
            return response()->json(['error' => 'Penempatan Siswa List kosong atau tidak valid'], 400);
        }


        // Proses setiap ID siswa dalam `penempatanSiswaList`
        foreach ($penempatanSiswaList as $msPenempatanSiswaId) {
            $penempatanSiswa = PenempatanSiswa::with([
                'ms_siswa',
                'ms_kelas',

                'ms_tagihan_siswa' => function ($q) use (
                    $selectedJenisTagihan,
                    $selectedKategoriTagihan,
                    $endDate
                ) {
                    $q->with([
                        'ms_jenis_tagihan_siswa',
                    ])

                    // Total pembayaran, tidak termasuk transaksi dibatalkan
                    ->withSum([
                        'dt_transaksi_tagihan_siswa as jumlah_sudah_dibayar' => function ($q) {
                            $q->where(
                                'dt_transaksi_tagihan_siswa.status_transaksi',
                                '!=',
                                'dibatalkan'
                            );
                        }
                    ], 'jumlah_bayar')

                    // Hanya tagihan yang belum lunas
                    ->where('status', '!=', 'Lunas')

                    // Filter jatuh tempo dan kategori
                    ->whereHas('ms_jenis_tagihan_siswa', function ($q) use (
                        $selectedKategoriTagihan,
                        $endDate
                    ) {
                        $q->where('tanggal_jatuh_tempo', '<=', $endDate);

                        if (!empty($selectedKategoriTagihan)) {
                            $q->whereIn(
                                'ms_kategori_tagihan_siswa_id',
                                $selectedKategoriTagihan
                            );
                        }
                    });

                    // Filter jenis tagihan
                    if (!empty($selectedJenisTagihan)) {
                        $q->whereIn(
                            'ms_jenis_tagihan_siswa_id', $selectedJenisTagihan
                        );
                    }
                },
            ])->find($msPenempatanSiswaId);


            // Jika penempatan tidak ditemukan
            if (!$penempatanSiswa) {
                continue;
            }


            // Hitung total kekurangan
            $totalTagihan = $penempatanSiswa->ms_tagihan_siswa->sum(
                function ($tagihan) {

                    $dibayar = (float) (
                        $tagihan->jumlah_sudah_dibayar ?? 0
                    );

                    return max(
                        0,
                        (float) $tagihan->jumlah_tagihan_siswa - $dibayar
                    );
                }
            );


            // Jika tidak ada tunggakan, jangan cetak surat
            if ($totalTagihan <= 0) {
                continue;
            }

            $surat = SuratTagihanSiswa::where('ms_jenjang_id', $selectedJenjang)->first();
            // Pastikan transaksi ditemukan
            if (!$penempatanSiswa) {
                return response()->json(['error' => 'Penempatan Siswa tidak ditemukan'], 404);
            }
            if (!$surat) {
                return response()->json(['error' => 'Template surat tidak ditemukan'], 404);
            }

            $namaSiswa = $penempatanSiswa->ms_siswa->nama_siswa ?? 'N/A';
            $namaKelas = $penempatanSiswa->ms_kelas->nama_kelas ?? 'N/A';

            // Lakukan proses pembuatan PDF (contoh respons)
            // return response()->json([
            //     'selectedJenjang' => $selectedJenjang,
            //     'msPenempatanSiswaId' => $msPenempatanSiswaId,
            //     'selectedJenisTagihan' => $selectedJenisTagihan,
            //     'selectedKategoriTagihan' => $selectedKategoriTagihan,
            //     'startDate' => $startDate,
            //     'endDate' => $endDate,
            // ]);

            // Inisialisasi TCPDF
            $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape

            $pdf::SetMargins(0, 0, 0); // kiri, atas, kanan
            $pdf::SetHeaderMargin(0);    // margin header
            $pdf::SetFooterMargin(10);    // margin footer
            $pdf::SetAutoPageBreak(TRUE, 20); // jarak bawah

            $pdf::SetTitle('Tagihan Siswa');
            $pdf::AddPage();
            $pdf::SetFont('times', '', 12);

            // HTML untuk header dengan tabel
            $kopPath = storage_path('app/public/' . $surat->foto_kop);
            if (!file_exists($kopPath)) {
                return response()->json(['error' => 'Kop surat tidak ditemukan di path ' . $kopPath], 404);
            }

            $kopBase64 = 'data:image/' . pathinfo($kopPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($kopPath));

            $htmlHeader = '
                <table border="0" cellpadding="1" cellspacing="0">
                    <tr>
                        <td style="text-align: center;">
                            <img src="' . $kopBase64 . '" width="1500px"/>
                        </td>
                    </tr>
                </table>
                ';
            // Menulis HTML ke dalam PDF
            $pdf::writeHTML($htmlHeader, true, false, true, false, '');

            // $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
            // $stylet = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
            // $pdf::Line(10, 46, 202, 46, $style);
            // $pdf::Line(10, 47, 202, 47, $stylet);

            // Detail Surat
            $kop = '
            <table cellpadding="1">
                <tr>
                    <td width="100%" style="text-align: right;">' . $surat->tempat_tanggal . '</td>
                </tr>
                <tr>
                    <td width="12%"><b>No</b></td>
                    <td width="78%">: ' . $surat->nomor_surat . '</td>
                </tr>
                <tr>
                    <td><b>Lampiran</b></td>
                    <td>: ' . $surat->lampiran . '</td>
                </tr>
                <tr>
                    <td><b>Hal</b></td>
                    <td>: ' . $surat->hal . '</td>
                </tr>
            </table>';
            $pdf::SetMargins(20, 5, 20);
            $pdf::SetX(20); //agar ke kiri 20
            $pdf::writeHTML($kop, true, false, true, false, '');

            $alamatTujuan = '<table border="0">
                <tr>
                    <td width="100%" align="left">Kepada Yth.</td>
                </tr>
                <tr>
                    <td width="100%" align="left">Bapak/Ibu Wali Murid Ananda <i>' . $namaSiswa . '</i></td>
                </tr>
                <tr>
                    <td width="100%" align="left">' . $namaKelas . '</td>
                </tr>
            </table>';

            $pdf::writeHTML($alamatTujuan, true, false, true, false, '');

            // Salam Pembuka
            $salamPembuka = '<table border="0">
                <tr>
                    <td width="100%" align="left">' . $surat->salam_pembuka . '</td>
                </tr>
            </table>';
            $pdf::writeHTML($salamPembuka, true, false, true, false, '');
            // Pembuka
            $pembuka = '<table border="0">
                <tr>
                    <td width="100%" style="text-indent: 20px;" align="justify">' . $surat->pembuka . '</td>
                </tr>
            </table>';
            $pdf::writeHTML($pembuka, true, false, true, false, '');

            // Latar Belakang
            $isi = '<table border="0">
                <tr>
                    <td width="100%" style="text-indent: 20px;" align="justify">' . $surat->isi . '</td>
                </tr>
            </table>';
            $pdf::writeHTML($isi, true, false, true, false, '');

            $rincianTagihan = '<table border="0">
                <tr>
                    <td width="100%" style="text-indent: 20px;" align="justify">' . $surat->rincian . '<b>Rp' . number_format($totalTagihan, 0, ',', '.') . '</b> dengan rincian terlampir</td>
                </tr>
            </table>';

            if (!empty($surat->rincian)) {
                $pdf::writeHTML($rincianTagihan, true, false, true, false, '');
            };

            $instruksi = '<table border="0">';

            if (!empty($surat->panduan)) {
                $instruksi .= '<tr>
                <td width="100%">' . $surat->panduan . '</td>
            </tr>';
            }
            if (!empty($surat->instruksi_1)) {
                $instruksi .= '<tr>
                <td width="100%" align="justify">' . $surat->instruksi_1 . '</td>
            </tr>';
            }
            if (!empty($surat->instruksi_2)) {
                $instruksi .= '<tr>
                <td width="100%" align="justify">' . $surat->instruksi_2 . '</td>
            </tr>';
            }
            if (!empty($surat->instruksi_3)) {
                $instruksi .= '<tr>
                <td width="100%" align="justify">' . $surat->instruksi_3 . '</td>
            </tr>';
            }
            if (!empty($surat->instruksi_4)) {
                $instruksi .= '<tr>
                <td width="100%" align="justify">' . $surat->instruksi_4 . '</td>
            </tr>';
            }
            if (!empty($surat->instruksi_5)) {
                $instruksi .= '<tr>
                <td width="100%" align="justify">' . $surat->instruksi_5 . '</td>
            </tr>';
            }

            $instruksi .= '</table>';

            $pdf::writeHTML($instruksi, true, false, true, false, '');

            // Penutup
            $penutup = '<table border="0">
                <tr>
                    <td width="100%" style="text-indent: 20px;" align="justify">' . $surat->penutup . '</td>
                </tr>
            </table>';
            $pdf::writeHTML($penutup, true, false, true, false, '');

            // Salam Penutup
            $salamPenutup = '<table border="0">
                <tr>
                    <td width="100%" align="justify">' . $surat->salam_penutup . '</td>
                </tr>
            </table>';
            $pdf::writeHTML($salamPenutup, true, false, true, false, '');

            $tandaTanganPath = storage_path('app/public/' . $surat->tanda_tangan);
            if (!file_exists($tandaTanganPath)) {
                return response()->json(['error' => 'Kop surat tidak ditemukan di path ' . $tandaTanganPath], 404);
            }

            $tandaTanganBase64 = 'data:image/' . pathinfo($tandaTanganPath, PATHINFO_EXTENSION) . ';base64,' . base64_encode(file_get_contents($tandaTanganPath));


            $tandaTangan = '<table border="0">
                <tr>
                    <td width="350px" align="left"></td>
                    <td width="230px" align="left">' . $surat->jabatan . '</td>
                </tr>
                <tr>
                    <td width="330px" align="left"></td>
                    <td width="230px" align="left"><img src="' . $tandaTanganBase64 . '" height="60px"></td>
                </tr>
                <tr>
                    <td width="350px" align="left"></td>
                    <td width="230px" align="left">' . $surat->nama_petugas . '</td>
                </tr>';

            if (!empty($surat->nomor_petugas)) {
                $tandaTangan .= '<tr>
                        <td width="350px" align="left"></td>
                        <td width="230px" align="left">' . $surat->nomor_petugas . '</td>
                    </tr>';
            }

            $tandaTangan .= '</table>';
            // output the HTML content
            $pdf::writeHTML($tandaTangan, true, false, true, false, '');

            $pdf::AddPage();
            $pdf::SetMargins(0, 0, 0); // kiri, atas, kanan

            $htmlHeader = '
            <table border="0" cellpadding="1" cellspacing="0">
                <tr>
                    <td style="text-align: center;">
                        <img src="' . $kopBase64 . '" widht="1500px"/>
                    </td>
                </tr>
            </table>
            ';

            $pdf::writeHTML($htmlHeader, true, false, true, false, '');
            $pdf::SetMargins(20, 5, 20);
            $pdf::SetX(20); //agar ke kiri 20

            // $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
            // $stylet = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
            // $pdf::Line(10, 46, 202, 46, $style);
            // $pdf::Line(10, 47, 202, 47, $stylet);

            // Rincian Tagihan
            $htmlTagihan = "<p><b>Rincian Tagihan Administrasi Sekolah</b></p>";

            $htmlTagihan .= '<table border="1" cellpadding="5" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Tagihan</th>
                            <th>Estimasi</th>
                            <th>Dibayarkan</th>
                            <th>Kekurangan</th>
                        </tr>
                    </thead>
                    <tbody>';

            $totalTagihan = 0;

            foreach ($penempatanSiswa->ms_tagihan_siswa as $tagihan) {
                $kekurangan = $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar;

                if ($kekurangan <= 0) {
                    continue; // Skip tagihan yang sudah lunas
                }

                $namaTagihan = strtoupper($tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa ?? 'Tidak Ditemukan');
                $jatuhTempo = $tagihan->tanggal_jatuh_tempo
                    ? HelperController::formatTanggalIndonesia($tagihan->tanggal_jatuh_tempo, 'd F Y')
                    : 'Tidak Ditentukan';

                // <td>{$jatuhTempo}</td>

                $htmlTagihan .= "<tr>
                        <td>{$namaTagihan}</td>
                        <td>Rp" . number_format($tagihan->jumlah_tagihan_siswa, 0, ',', '.') . "</td>
                        <td>Rp" . number_format($tagihan->jumlah_sudah_dibayar, 0, ',', '.') . "</td>
                        <td>Rp" . number_format($kekurangan, 0, ',', '.') . "</td>
                     </tr>";
                $totalTagihan += $kekurangan;
            }

            $htmlTagihan .= '</tbody></table>';

            // Menambahkan rincian tagihan ke PDF
            $pdf::writeHTML($htmlTagihan, true, false, true, false, '');

            $pdf::SetMargins(20, 5, 20);
            $pdf::SetX(20); //agar ke kiri 20
            
            // Menambahkan Total Tagihan
            $totalTagihanHtml = "<h4>Total Kekurangan: Rp" . number_format($totalTagihan, 0, ',', '.') . "</h4>";
            $pdf::writeHTML($totalTagihanHtml, true, false, true, false, '');
            $pdf::Ln(2);

            $catatan = '<table border="0">';

            if (!empty($surat->catatan_1)) {
                $catatan .= '<tr>
                            <td width="100%">' . $surat->catatan_1 . '</td>                
                        </tr>';
            }

            if (!empty($surat->catatan_2)) {
                $catatan .= '<tr>
                            <td width="100%">' . $surat->catatan_2 . '</td>                
                        </tr>';
            }

            if (!empty($surat->catatan_3)) {
                $catatan .= '<tr>
                            <td width="100%">' . $surat->catatan_3 . '</td>                
                        </tr>';
            }

            $catatan .= '</table>';

            $pdf::writeHTML($catatan, true, false, true, false, '');
        }
        $pdf::Output('Surat_Tagihan_Kelas' . $namaKelas . '.pdf', 'I');
    }
}
