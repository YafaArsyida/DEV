<?php

namespace App\Http\Livewire\Kelas;

use App\Models\AktifitasPengguna;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Change extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedJenjang;
    public $selectedTahunAjar;
    public $selectedKelas;

    public $searchSiswa = '';

    public $siswaSelected = [];
    public $kelasTujuan = null;
    public $selectAll = false;

    protected $listeners = ['showKelas' => 'loadKelas'];

    public function updatingSearchSiswa()
    {
        $this->resetPage();
    }

    public function loadKelas($params)
    {
        $this->selectedJenjang = $params['jenjang'];
        $this->selectedTahunAjar = $params['tahunAjar'];
        $this->selectedKelas = $params['kelasId'];
        $this->resetPage();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->siswaSelected = PenempatanSiswa::where('ms_kelas_id', $this->selectedKelas)
                ->pluck('ms_penempatan_siswa_id')
                ->toArray();
        } else {
            $this->siswaSelected = [];
        }
    }

    public function pindahkanSiswa()
    {
        if (!$this->kelasTujuan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pilih kelas tujuan terlebih dahulu.']);
            return;
        }

        if (empty($this->siswaSelected)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pilih siswa yang ingin dipindahkan.']);
            return;
        }

        // Dapatkan data siswa yang dipindahkan untuk log
        $siswaDipindahkan = PenempatanSiswa::whereIn('ms_penempatan_siswa_id', $this->siswaSelected)->get();
        // Dapatkan nama kelas tujuan
        $kelasTujuan = Kelas::find($this->kelasTujuan);
        $namaKelasTujuan = $kelasTujuan ? $kelasTujuan->nama_kelas : 'Tidak Diketahui';

        PenempatanSiswa::whereIn('ms_penempatan_siswa_id', $this->siswaSelected)
            ->update(['ms_kelas_id' => $this->kelasTujuan]);

        // Log aktivitas untuk setiap siswa yang dipindahkan
        foreach ($siswaDipindahkan as $penempatan) {
            $namaSiswa = $penempatan->ms_siswa ? $penempatan->ms_siswa->nama_siswa : 'Tidak Diketahui';
            AktifitasPengguna::create([
                'ms_pengguna_id'       => Auth::id(),
                'ms_tahun_ajar_id'     => $penempatan->ms_tahun_ajar_id,
                'ms_jenjang_id'        => $penempatan->ms_jenjang_id,
                'tipe_aksi'            => 'update',
                'tipe_tabel'           => 'tabel penempatan siswa',
                'id_tabel'             => $penempatan->ms_penempatan_siswa_id,
                'ip_pengguna'          => request()->ip(),
                'perangkat_pengguna'   => request()->header('User-Agent'),
                'deskripsi'            => "Memindahkan siswa '{$namaSiswa}' ke kelas '{$namaKelasTujuan}'.",
            ]);
        }

        $this->siswaSelected = [];
        $this->selectAll = false;

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Siswa berhasil dipindahkan.']);

        $this->emitSelf('$refresh');
        $this->emit('refreshKelass');
        $this->emit('refreshSiswas');
    }

    public function render()
    {
        $select_kelas = [];
        if ($this->selectedJenjang && $this->selectedTahunAjar) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->get();
        }

        $siswas = [];
        if ($this->selectedKelas) {
            $query = PenempatanSiswa::with(['ms_siswa', 'ms_kelas'])
                ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
                ->where('ms_kelas_id', $this->selectedKelas);

            if ($this->searchSiswa) {
                $query->whereHas('ms_siswa', function ($query) {
                    $query->where('nama_siswa', 'like', '%' . $this->searchSiswa . '%');
                });
            }

            $siswas = $query->orderBy('ms_siswa.nama_siswa')->paginate(100);
        }

        return view('livewire.kelas.change', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
