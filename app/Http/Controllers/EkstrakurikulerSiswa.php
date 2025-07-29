<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ekstrakurikuler;
use App\Models\Jenjang;
use App\Models\PenempatanSiswa;
use App\Models\TahunAjar;
use Illuminate\Http\Request;
use Elibyy\TCPDF\Facades\TCPDF;

class EkstrakurikulerSiswa extends Controller
{
    public function index()
    {
        return view('ADMINISTRASI.ekstrakurikuler-siswa.v_index');
    }

    public function cetakPDF(Request $request)
    {
        $ms_ekstrakurikuler_id = $request->ekstrakurikuler_id;
        $ms_kelas_id = $request->kelas;
        $search = $request->search;

        $ekskul = Ekstrakurikuler::find($ms_ekstrakurikuler_id);

        if (!$ekskul) {
            return response()->json(['error' => 'Data ekstrakurikuler tidak ditemukan.'], 400);
        }

        $query = PenempatanSiswa::with([
            'ms_kelas',
            'ms_siswa.ms_penempatan_ekstrakurikuler.ms_ekstrakurikuler',
        ])
            ->where('ms_jenjang_id', $ekskul->ms_jenjang_id)
            ->whereHas('ms_siswa.ms_penempatan_ekstrakurikuler', function ($q) use ($ms_ekstrakurikuler_id) {
                $q->where('ms_ekstrakurikuler_id', $ms_ekstrakurikuler_id);
            });

        if ($ms_kelas_id) {
            $query->where('ms_kelas_id', $ms_kelas_id);
        }

        if ($search) {
            $query->whereHas('ms_siswa', function ($subQuery) use ($search) {
                $subQuery->where('nama_siswa', 'like', '%' . $search . '%');
            });
        }

        $siswa = $query->orderBy('ms_kelas_id')->get();

        // Inisialisasi PDF
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Data Ekstrakurikuler Siswa');
        $pdf::AddPage();
        $pdf::SetFont('times', 'B', 14);
        $pdf::Cell(0, 6, 'DATA SISWA EKSTRAKURIKULER', 0, 1, 'C');

        $pdf::SetFont('times', 'B', 13);
        $pdf::Cell(0, 6, strtoupper($ekskul->nama_ekstrakurikuler), 0, 1, 'C');

        $pdf::SetFont('times', '', 12);
        $pdf::Cell(0, 6, strtoupper($ekskul->ms_jenjang->nama_jenjang ?? '-'), 0, 1, 'C');

        $pdf::SetFont('times', '', 10);
        $pdf::MultiCell(0, 6, 'Dusun No.2 RT.04/RW.01, Dusun 2, Kebonan, Karanggede, Boyolali, Jawa Tengah 57381', 0, 'C');

        $pdf::Ln(4); // spasi bawah

        $pdf::SetFont('times', '', 10);

        $html = '
        <table border="0.5" cellpadding="2" cellspacing="0" style="width:100%;">
            <thead>
                <tr style="background-color:#f5f5f5;">
                    <th width="5%">No</th>
                    <th width="40%">Nama Siswa</th>
                    <th width="15%">Kelas</th>
                    <th width="20%">Ekstrakurikuler</th>
                    <th width="20%" align="left">Biaya</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($siswa as $index => $item) {
            $nama = $item->ms_siswa->nama_siswa ?? '-';
            $kelas = $item->ms_kelas->nama_kelas ?? '-';
            $ekskuls = collect($item->ms_siswa->ms_penempatan_ekstrakurikuler)
                ->pluck('ms_ekstrakurikuler.nama_ekstrakurikuler')
                ->filter()
                ->implode(', ');
            $biaya = number_format($item->ms_siswa->total_biaya_ekstrakurikuler() ?? 0, 0, ',', '.');

            $html .= '
            <tr>
                <td width="5%" align="center">' . ($index + 1) . '.' . '</td>
                <td width="40%">' . htmlspecialchars($nama) . '</td>
                <td width="15%">' . htmlspecialchars($kelas) . '</td>
                <td width="20%">' . htmlspecialchars($ekskuls) . '</td>
                <td width="20%" align="left">Rp' . $biaya . '</td>
            </tr>';
        }

        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('data_ekstrakurikuler_siswa.pdf', 'I');
    }

    public function cetakSiswaPDF(Request $request)
    {
        $ms_jenjang_id = $request->jenjang;
        $ms_tahun_ajar_id = $request->tahun;
        $ms_kelas_id = $request->kelas;
        $search = $request->search;

        $query = PenempatanSiswa::with([
            'ms_siswa.ms_educard',
            'ms_kelas',
            'ms_tahun_ajar',
            'ms_jenjang',
            'ms_siswa.ms_penempatan_ekstrakurikuler.ms_ekstrakurikuler',
        ])
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->where('ms_jenjang_id', $ms_jenjang_id)
            ->where('ms_tahun_ajar_id', $ms_tahun_ajar_id);

        if ($ms_kelas_id) {
            $query->where('ms_kelas_id', $ms_kelas_id);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('ms_siswa', function ($qr) use ($search) {
                    $qr->where('nama_siswa', 'like', '%' . $search . '%');
                })->orWhereHas('ms_siswa.ms_educard', function ($qr) use ($search) {
                    $qr->where('kode_kartu', 'like', '%' . $search . '%');
                });
            });
        }

        $siswas = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
            ->orderBy('ms_siswa.nama_siswa')->get();

        $jenjang = Jenjang::find($ms_jenjang_id);
        $tahunAjar = TahunAjar::find($ms_tahun_ajar_id);

        // === SETUP PDF ===
        $pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
        $pdf::SetTitle('Data Siswa Ekstrakurikuler');
        $pdf::AddPage();
        $pdf::SetFont('times', 'B', 14);
        $pdf::Cell(0, 6, 'DATA SISWA EKSTRAKURIKULER', 0, 1, 'C');
        $pdf::SetFont('times', '', 12);
        $pdf::Cell(0, 6, strtoupper($jenjang->nama_jenjang ?? '-'), 0, 1, 'C');
        // $pdf::SetFont('times', '', 12);
        // $pdf::Cell(0, 6, 'Tahun Ajaran: ' . ($tahunAjar->nama_tahun_ajar ?? '-'), 0, 1, 'C');
        $pdf::SetFont('times', '', 10);
        $pdf::MultiCell(0, 6, 'Dusun No.2 RT.04/RW.01, Dusun 2, Kebonan, Karanggede, Boyolali, Jawa Tengah 57381', 0, 'C');
        $pdf::Ln(4);

        // === TABEL HEADER ===
        $html = '
        <table border="0.5" cellpadding="2" cellspacing="0" style="width:100%;">
            <thead>
                <tr style="background-color:#f2f2f2;">
                    <th width="5%">No</th>
                    <th width="40%">Nama Siswa</th>
                    <th width="15%">Kelas</th>
                    <th width="20%">Ekstrakurikuler</th>
                    <th width="20%" align="left">Biaya</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($siswas as $i => $item) {
            $nama = $item->ms_siswa->nama_siswa ?? '-';
            $kelas = $item->ms_kelas->nama_kelas ?? '-';
            $biaya = 'Rp' . number_format($item->ms_siswa->total_biaya_ekstrakurikuler() ?? 0, 0, ',', '.');
            $ekskuls = collect($item->ms_siswa->ms_penempatan_ekstrakurikuler)
                ->pluck('ms_ekstrakurikuler.nama_ekstrakurikuler')
                ->filter()
                ->implode(', ');

            $html .= '
            <tr>
                <td width="5%" align="center">' . ($i + 1) . '</td>
                <td width="40%">' . htmlspecialchars($nama) . '</td>
                <td width="15%">' . htmlspecialchars($kelas) . '</td>
                <td width="20%">' . htmlspecialchars($ekskuls) . '</td>
                <td width="20%" align="left">' . $biaya . '</td>
            </tr>';
        }

        $html .= '</tbody></table>';

        $pdf::writeHTML($html, true, false, true, false, '');
        $pdf::Output('data-siswa-ekstrakurikuler.pdf', 'I');
    }
}
