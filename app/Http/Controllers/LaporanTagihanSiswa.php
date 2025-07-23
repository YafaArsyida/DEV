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
        // Ambil parameter tambahan dari query string
        $selectedJenjang = request()->query('selectedJenjang');
        $selectedJenisTagihan = request()->query('selectedJenisTagihan') ? json_decode(request()->query('selectedJenisTagihan'), true) : [];
        $selectedKategoriTagihan = request()->query('selectedKategoriTagihan') ? json_decode(request()->query('selectedKategoriTagihan'), true) : [];
        // Ambil parameter tanggal tanpa waktu
        $startDate = request()->query('startDate');
        $endDate = request()->query('endDate');

        $startDate = (!empty($startDate) && Carbon::hasFormat($startDate, 'Y-m-d')) ? Carbon::createFromFormat('Y-m-d', $startDate)->toDateString() : null;
        $endDate = (!empty($endDate) && Carbon::hasFormat($endDate, 'Y-m-d')) ? Carbon::createFromFormat('Y-m-d', $endDate)->toDateString() : null;

        $penempatanSiswa = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
            'ms_tagihan_siswa' => function ($query) use ($selectedJenisTagihan, $selectedKategoriTagihan, $startDate, $endDate) {
                if (!empty($selectedJenisTagihan)) {
                    $query->whereIn('ms_jenis_tagihan_siswa_id', $selectedJenisTagihan);
                }

                if (!empty($selectedKategoriTagihan)) {
                    $query->whereHas('ms_jenis_tagihan_siswa', function ($q) use ($selectedKategoriTagihan) {
                        $q->whereIn('ms_kategori_tagihan_siswa_id', $selectedKategoriTagihan);
                    });
                }

                if ($startDate && $endDate) {
                    $query->whereHas('ms_jenis_tagihan_siswa', function ($q) use ($startDate, $endDate) {
                        $q->whereBetween('tanggal_jatuh_tempo', [$startDate, $endDate]);
                    });
                }

                $query->where('status', '!=', 'Lunas');
            },
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa',
            'ms_tagihan_siswa.dt_transaksi_tagihan_siswa'
        ])->find($msPenempatanSiswaId);
        // Hitung total tagihan
        $totalTagihan = $penempatanSiswa->ms_tagihan_siswa->sum(function ($tagihan) {
            return $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar();
        });

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
        // $pdf = new TCPDF();
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false); // Landscape
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
                        <img src="' . $kopBase64 . '" height="100px"/>
                    </td>
                </tr>
            </table>
            ';
        // Menulis HTML ke dalam PDF
        $pdf::writeHTML($htmlHeader, true, false, true, false, '');

        $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $stylet = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $pdf::Line(10, 46, 202, 46, $style);
        $pdf::Line(10, 47, 202, 47, $stylet);

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
                    <td width="370px" align="left"></td>
                    <td width="230px" align="left">' . $surat->jabatan . '</td>
                </tr>
                <tr>
                    <td width="350px" align="left"></td>
                    <td width="230px" align="left"><img src="' . $tandaTanganBase64 . '" height="60px"></td>
                </tr>
                <tr>
                    <td width="370px" align="left"></td>
                    <td width="230px" align="left">' . $surat->nama_petugas . '</td>
                </tr>';

        if (!empty($surat->nomor_petugas)) {
            $tandaTangan .= '<tr>
                        <td width="370px" align="left"></td>
                        <td width="230px" align="left">' . $surat->nomor_petugas . '</td>
                    </tr>';
        }

        $tandaTangan .= '</table>';
        // output the HTML content
        $pdf::writeHTML($tandaTangan, true, false, true, false, '');

        $pdf::AddPage();

        $htmlHeader = '
            <table border="0" cellpadding="1" cellspacing="0">
                <tr>
                    <td style="text-align: center;">
                        <img src="' . $kopBase64 . '" height="100px"/>
                    </td>
                </tr>
            </table>
            ';

        $pdf::writeHTML($htmlHeader, true, false, true, false, '');

        $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $stylet = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
        $pdf::Line(10, 46, 202, 46, $style);
        $pdf::Line(10, 47, 202, 47, $stylet);

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
            $kekurangan = $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar();

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
                        <td>Rp" . number_format($tagihan->jumlah_sudah_dibayar(), 0, ',', '.') . "</td>
                        <td>Rp" . number_format($kekurangan, 0, ',', '.') . "</td>
                     </tr>";
            $totalTagihan += $kekurangan;
        }

        $htmlTagihan .= '</tbody></table>';

        // Menambahkan rincian tagihan ke PDF
        $pdf::writeHTML($htmlTagihan, true, false, true, false, '');

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
        $selectedJenjang = request()->query('selectedJenjang');
        $penempatanSiswaList = request()->query('penempatanSiswaList') ? json_decode(request()->query('penempatanSiswaList'), true) : [];
        $selectedJenisTagihan = request()->query('selectedJenisTagihan') ? json_decode(request()->query('selectedJenisTagihan'), true) : [];
        $selectedKategoriTagihan = request()->query('selectedKategoriTagihan') ? json_decode(request()->query('selectedKategoriTagihan'), true) : [];
        // Ambil parameter tanggal tanpa waktu
        $startDate = request()->query('startDate');
        $endDate = request()->query('endDate');

        $startDate = (!empty($startDate) && Carbon::hasFormat($startDate, 'Y-m-d')) ? Carbon::createFromFormat('Y-m-d', $startDate)->toDateString() : null;
        $endDate = (!empty($endDate) && Carbon::hasFormat($endDate, 'Y-m-d')) ? Carbon::createFromFormat('Y-m-d', $endDate)->toDateString() : null;

        // Pastikan `penempatanSiswaList` tidak kosong
        if (empty($penempatanSiswaList) || !is_array($penempatanSiswaList)) {
            return response()->json(['error' => 'Penempatan Siswa List kosong atau tidak valid'], 400);
        }

        // return response()->json([
        //     'selectedJenjang' => $selectedJenjang,
        //     'penempatanSiswaList' => $penempatanSiswaList,
        //     'selectedJenisTagihan' => $selectedJenisTagihan,
        //     'selectedKategoriTagihan' => $selectedKategoriTagihan,
        //     'startDate' => $startDate,
        //     'endDate' => $endDate,
        // ]);

        // Proses setiap ID siswa dalam `penempatanSiswaList`
        foreach ($penempatanSiswaList as $msPenempatanSiswaId) {
            $penempatanSiswa = PenempatanSiswa::with([
                'ms_siswa',
                'ms_kelas',
                'ms_tagihan_siswa' => function ($query) use ($selectedJenisTagihan, $selectedKategoriTagihan, $startDate, $endDate) {
                    if (!empty($selectedJenisTagihan)) {
                        $query->whereIn('ms_jenis_tagihan_siswa_id', $selectedJenisTagihan);
                    }

                    if (!empty($selectedKategoriTagihan)) {
                        $query->whereHas('ms_jenis_tagihan_siswa', function ($q) use ($selectedKategoriTagihan) {
                            $q->whereIn('ms_kategori_tagihan_siswa_id', $selectedKategoriTagihan);
                        });
                    }

                    if ($startDate && $endDate) {
                        $query->whereHas('ms_jenis_tagihan_siswa', function ($q) use ($startDate, $endDate) {
                            $q->whereBetween('tanggal_jatuh_tempo', [$startDate, $endDate]);
                        });
                    }

                    $query->where('status', '!=', 'Lunas');
                },
                'ms_tagihan_siswa.ms_jenis_tagihan_siswa',
                'ms_tagihan_siswa.dt_transaksi_tagihan_siswa'
            ])->find($msPenempatanSiswaId);
            // Hitung total tagihan
            $totalTagihan = $penempatanSiswa->ms_tagihan_siswa->sum(function ($tagihan) {
                return $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar();
            });

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
            $pdf = new TCPDF();
            $pdf::SetTitle('Tagihan Siswa');
            $pdf::AddPage();
            $pdf::SetFont('times', '', 12);

            // HTML untuk header dengan tabel
            $kopPath = storage_path('app/public/' . $surat->foto_kop);
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
                        <img src="' . $kopBase64 . '" height="100px"/>
                    </td>
                </tr>
            </table>
            ';
            // Menulis HTML ke dalam PDF
            $pdf::writeHTML($htmlHeader, true, false, true, false, '');

            $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
            $stylet = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
            $pdf::Line(10, 46, 202, 46, $style);
            $pdf::Line(10, 47, 202, 47, $stylet);

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
                    <td width="370px" align="left"></td>
                    <td width="230px" align="left">' . $surat->jabatan . '</td>
                </tr>
                <tr>
                    <td width="350px" align="left"></td>
                    <td width="230px" align="left"><img src="' . $tandaTanganBase64 . '" height="60px"></td>
                </tr>
                <tr>
                    <td width="370px" align="left"></td>
                    <td width="230px" align="left">' . $surat->nama_petugas . '</td>
                </tr>';

            if (!empty($surat->nomor_petugas)) {
                $tandaTangan .= '<tr>
                        <td width="370px" align="left"></td>
                        <td width="230px" align="left">' . $surat->nomor_petugas . '</td>
                    </tr>';
            }

            $tandaTangan .= '</table>';
            // output the HTML content
            $pdf::writeHTML($tandaTangan, true, false, true, false, '');

            $pdf::AddPage();

            $htmlHeader = '
            <table border="0" cellpadding="1" cellspacing="0">
                <tr>
                    <td style="text-align: center;">
                        <img src="' . $kopBase64 . '" height="100px"/>
                    </td>
                </tr>
            </table>
            ';

            $pdf::writeHTML($htmlHeader, true, false, true, false, '');

            $style = array('width' => 0.7, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
            $stylet = array('width' => 0.1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0));
            $pdf::Line(10, 46, 202, 46, $style);
            $pdf::Line(10, 47, 202, 47, $stylet);

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
                $kekurangan = $tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar();

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
                        <td>Rp" . number_format($tagihan->jumlah_sudah_dibayar(), 0, ',', '.') . "</td>
                        <td>Rp" . number_format($kekurangan, 0, ',', '.') . "</td>
                     </tr>";
                $totalTagihan += $kekurangan;
            }

            $htmlTagihan .= '</tbody></table>';

            // Menambahkan rincian tagihan ke PDF
            $pdf::writeHTML($htmlTagihan, true, false, true, false, '');

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
        $pdf::Output('Surat_Tagihan_Kelas' . $namaSiswa . '.pdf', 'I');
    }
}
