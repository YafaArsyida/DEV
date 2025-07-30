<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AkuntansiJurnalDetail;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;

class AkuntansiLaporanNeraca extends Controller
{
    public function index()
    {
        return view('LAPORAN-AKUNTANSI.laporan-neraca.v_index');
    }
    public function cetakPDF(Request $request)
    {
        $jenjangId = $request->jenjang;
        $tahunAjarId = $request->tahun;
        $endDate = $request->end_date;

        $jenjang = Jenjang::find($jenjangId);
        $tahunAjar = TahunAjar::find($tahunAjarId);

        $transaksi = AkuntansiJurnalDetail::with('akuntansi_rekening')
            ->where('ms_jenjang_id', $jenjangId)
            ->where('ms_tahun_ajaran_id', $tahunAjarId)
            ->when($endDate, function ($q) use ($endDate) {
                $q->whereDate('tanggal_transaksi', '<=', $endDate);
            })
            ->get();

        $kelompok = [
            'aset' => [],
            'kewajiban' => [],
            'ekuitas' => [],
        ];

        foreach ($transaksi->groupBy('kode_rekening') as $kode => $rekGroup) {
            if (in_array($kode, ['32001', '33001'])) continue;

            $rek = $rekGroup->first()->akuntansi_rekening;
            $nama = $rek->nama_rekening;
            $posisiNormal = $rek->posisi_normal;
            $kodeAwal = substr($kode, 0, 1);

            $saldo = $rekGroup->sum(fn($t) => $t->posisi == $posisiNormal ? $t->nominal : -$t->nominal);

            $data = ['kode' => $kode, 'nama' => $nama, 'saldo' => $saldo];

            if ($kodeAwal === '1') $kelompok['aset'][] = $data;
            elseif ($kodeAwal === '2') $kelompok['kewajiban'][] = $data;
            elseif ($kodeAwal === '3') $kelompok['ekuitas'][] = $data;
        }

        $labaRugi = $tahunAjar->hitungLabaRugi($jenjangId);
        $tutupBuku = $tahunAjar->tutup_buku;

        // ========================== PDF ==========================
        $judul = 'Laporan Neraca';
        $yayasan = 'Yayasan Drul Khukama Unit ' . ($jenjang->nama_jenjang ?? '-') . ' Tahun Ajaran ' . ($tahunAjar->nama_tahun_ajar ?? '-');

        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle($judul);
        $pdf::AddPage();

        $pdf::SetFont('times', 'B', 13);
        $pdf::Cell(0, 5, $judul, 0, 1, 'C');
        $pdf::SetFont('times', '', 11);
        $pdf::Cell(0, 5, $yayasan, 0, 1, 'C');
        $pdf::SetFont('times', '', 10);
        $pdf::MultiCell(0, 6, ($jenjang->deskripsi ?? '-'), 0, 'C');
        $pdf::Ln(3);

        // ========== TABEL ==========
        $html = '<table border="0.5" cellpadding="4" cellspacing="0" width="100%">
                    <thead>
                        <tr style="background-color:#f2f2f2;">
                            <th width="50%">Nama Akun</th>
                            <th width="50%" align="right">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>';

        // ASET
        $totalAset = 0;
        $html .= '<tr><td colspan="2"><b>ASET</b></td></tr>';
        foreach ($kelompok['aset'] as $akun) {
            $totalAset += $akun['saldo'];
            $html .= '<tr>
                        <td>' . $akun['nama'] . '</td>
                        <td align="right">RP' . number_format($akun['saldo'], 0, ',', '.') . '</td>
                      </tr>';
        }
        $html .= '<tr style="background-color:#d1d1d1;">
                    <td><b>TOTAL ASET</b></td>
                    <td align="right"><b>RP' . number_format($totalAset, 0, ',', '.') . '</b></td>
                  </tr>';

        // KEWAJIBAN
        $totalKewajiban = 0;
        $html .= '<tr><td colspan="2"><b>KEWAJIBAN</b></td></tr>';
        foreach ($kelompok['kewajiban'] as $akun) {
            $totalKewajiban += $akun['saldo'];
            $html .= '<tr>
                        <td>' . $akun['nama'] . '</td>
                        <td align="right">RP' . number_format($akun['saldo'], 0, ',', '.') . '</td>
                      </tr>';
        }
        $html .= '<tr>
                    <td><b>Total Kewajiban</b></td>
                    <td align="right"><b>RP' . number_format($totalKewajiban, 0, ',', '.') . '</b></td>
                  </tr>';

        // EKUITAS
        $totalEkuitas = 0;
        $html .= '<tr><td colspan="2"><b>EKUITAS</b></td></tr>';

        $html .= '<tr>
                    <td>' . ($tutupBuku === 'sudah' ? 'Akumulasi Surplus/Defisit' : 'Surplus/Defisit Tahun Berjalan') . '</td>
                    <td align="right">RP' . number_format($labaRugi, 0, ',', '.') . '</td>
                  </tr>';

        foreach ($kelompok['ekuitas'] as $akun) {
            $totalEkuitas += $akun['saldo'];
            $html .= '<tr>
                        <td>' . $akun['nama'] . '</td>
                        <td align="right">RP' . number_format($akun['saldo'], 0, ',', '.') . '</td>
                      </tr>';
        }

        $html .= '<tr>
                    <td><b>Total Ekuitas</b></td>
                    <td align="right"><b>RP' . number_format($totalEkuitas + $labaRugi, 0, ',', '.') . '</b></td>
                  </tr>';

        // TOTAL KEWAJIBAN + EKUITAS
        $html .= '<tr style="background-color:#d1d1d1;">
                    <td><b>TOTAL KEWAJIBAN + EKUITAS</b></td>
                    <td align="right"><b>RP' . number_format($totalKewajiban + $totalEkuitas + $labaRugi, 0, ',', '.') . '</b></td>
                  </tr>';

        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('laporan-neraca.pdf', 'I');
    }
}
