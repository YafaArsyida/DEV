<?php

namespace App\Http\Livewire\LaporanRekapitulasiKeuangan;

use App\Models\JenisTagihanSiswa;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Menggunakan tema Bootstrap untuk paginasi

    public $jenisTagihan;
    public $jenisRekapitulasi = 'tagihan';
    public $total;
    public $grandTotal;

    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedKelas = null;

    public $search = '';

    // Listener untuk Livewire
    protected $listeners = [
        'parameterUpdated' => 'updateParameters',
    ];

    public function updateParameters($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function updatedJenisRekapitulasi()
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function render()
    {
        // Data untuk dropdown Kelas (hanya jika Jenjang dan Tahun Ajar dipilih)
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $this->jenisTagihan = JenisTagihanSiswa::with('ms_kategori_tagihan_siswa')
            ->whereHas('ms_kategori_tagihan_siswa', function ($q) {
                $q->where('ms_jenjang_id', $this->selectedJenjang)
                    ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);
            })
            ->get()
            ->sortBy('ms_kategori_tagihan_siswa_id')
            ->values(); // Kembalikan koleksi lengkap

        $query = PenempatanSiswa::with([
            'ms_siswa.ms_educard',
            'ms_kelas',
            'ms_tagihan_siswa.ms_jenis_tagihan_siswa' // Pastikan jenis tagihan di-load
        ])
            ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar);

        // Tambahkan filter kelas
        if ($this->selectedKelas) {
            $query->where('ms_kelas_id', $this->selectedKelas);
        }

        // Filter pencarian
        if ($this->search) {
            $query->where(function ($query) {
                $query->where('ms_siswa.nama_siswa', 'like', '%' . $this->search . '%')
                    ->orWhereHas('ms_siswa.ms_educard', function ($query) {
                        $query->where('kode_kartu', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $siswas = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
            ->orderBy('ms_siswa.nama_siswa')
            ->get();
        // ->paginate(50);

        // Ambil data siswa sesuai filter
        $siswas = $query->orderBy('ms_penempatan_siswa.ms_kelas_id')
            ->orderBy('ms_siswa.nama_siswa')
            ->get();

        // Hitung total per jenis tagihan berdasarkan data yang telah difilter
        $this->total = $this->jenisTagihan->mapWithKeys(function ($jenis) use ($siswas) {
            $total = $siswas->sum(function ($siswa) use ($jenis) {
                $tagihanItem = $siswa->ms_tagihan_siswa->where('ms_jenis_tagihan_siswa_id', $jenis->ms_jenis_tagihan_siswa_id)->first();

                if (!$tagihanItem) return 0;

                if ($this->jenisRekapitulasi === 'tagihan') {
                    return $tagihanItem->jumlah_tagihan_siswa;
                } elseif ($this->jenisRekapitulasi === 'pembayaran') {
                    return $tagihanItem->jumlah_sudah_dibayar();
                } elseif ($this->jenisRekapitulasi === 'kekurangan') {
                    return $tagihanItem->jumlah_kekurangan();
                }

                return 0;
            });

            return [$jenis->ms_jenis_tagihan_siswa_id => $total];
        });

        // Hitung grand total
        $this->grandTotal = $this->total->sum();

        return view('livewire.laporan-rekapitulasi-keuangan.index', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
