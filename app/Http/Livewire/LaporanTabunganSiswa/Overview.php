<?php

namespace App\Http\Livewire\LaporanTabunganSiswa;

use App\Models\Jenjang;
use App\Models\Kelas;
use Livewire\WithPagination;
use Livewire\Component;

use App\Models\Tabungan;
use App\Models\TabunganSiswa;

class Overview extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

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
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $query = TabunganSiswa::query()
            ->with(['ms_siswa', 'ms_pengguna', 'ms_penempatan_siswa'])
            ->join('ms_siswa', 'ms_siswa.ms_siswa_id', '=', 'ms_tabungan_siswa.ms_siswa_id')
            ->join('ms_penempatan_siswa', 'ms_penempatan_siswa.ms_penempatan_siswa_id', '=', 'ms_tabungan_siswa.ms_penempatan_siswa_id')
            ->select('ms_tabungan_siswa.*', 'ms_siswa.nama_siswa', 'ms_penempatan_siswa.ms_jenjang_id', 'ms_penempatan_siswa.ms_tahun_ajar_id')
            ->where('ms_penempatan_siswa.ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_penempatan_siswa.ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->orderBy('tanggal', 'ASC');

        // Filter berdasarkan tahun ajar
        if ($this->selectedKelas) {
            $query->where('ms_penempatan_siswa.ms_kelas_id', $this->selectedKelas);
        }

        $totalKredit = (clone $query)->where('jenis_transaksi', 'setoran')->sum('nominal');
        $totalDebit = (clone $query)->where('jenis_transaksi', 'penarikan')->sum('nominal');
        $totalSaldo = $totalKredit - $totalDebit;

        return view('livewire.laporan-tabungan-siswa.overview', [
            'select_kelas' => $select_kelas,
            'totalKredit' => $totalKredit,
            'totalDebit' => $totalDebit,
            'totalSaldo' => $totalSaldo,
        ]);
    }
}
