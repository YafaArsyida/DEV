<?php

namespace App\Http\Livewire\Keuangan\LaporanTabunganPegawai;

use App\Models\Jabatan;
use App\Models\TransaksiTabungan;
use Carbon\Carbon;
use Livewire\Component;

class Overview extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedJabatan = null;

    protected $listeners = [
        'parameterUpdated' => 'updateParameters'
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function render()
    {
        $select_jabatan = Jabatan::get();

        $query = TransaksiTabungan::query()
            ->with(['ms_pegawai', 'ms_pengguna', 'ms_pegawai.ms_jabatan'])
            ->join('ms_pegawai', 'ms_pegawai.ms_pegawai_id', '=', 'ms_transaksi_tabungan.user_id')
            ->leftJoin('ms_jabatan', 'ms_jabatan.ms_jabatan_id', '=', 'ms_pegawai.ms_jabatan_id')
            ->select(
                'ms_transaksi_tabungan.*',
                'ms_pegawai.nama_pegawai',
                'ms_pegawai.ms_jenjang_id',
                'ms_pegawai.ms_jabatan_id',
                'ms_jabatan.nama_jabatan'
            )
            ->where('status_transaksi', '!=', 'dibatalkan')
            ->where('ms_transaksi_tabungan.user_type', 'pegawai')
            ->orderBy('tanggal', 'ASC');

        // Filter berdasarkan jenjang
        if ($this->selectedJenjang) {
            $query->where('ms_pegawai.ms_jenjang_id', $this->selectedJenjang);
        }

        // Filter berdasarkan jabatan (bukan kelas)
        if ($this->selectedJabatan) {
            $query->where('ms_pegawai.ms_jabatan_id', $this->selectedJabatan);
        }

        // Hitung total setoran dan penarikan
        $totalKredit = (clone $query)->where('jenis_transaksi', 'setoran')->sum('nominal');
        $totalDebit = (clone $query)->where('jenis_transaksi', 'penarikan')->sum('nominal');
        $totalSaldo = $totalKredit - $totalDebit;

        return view('livewire.keuangan.laporan-tabungan-pegawai.overview', [
            'select_jabatan' => $select_jabatan,
            'totalKredit' => $totalKredit,
            'totalDebit' => $totalDebit,
            'totalSaldo' => $totalSaldo,
        ]);
    }
}
