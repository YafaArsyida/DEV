<?php

namespace App\Http\Livewire\Akademik\Kelas;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Change extends Component
{
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
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'memperbarui'
        ]);
    }

    public function loadKelas($params)
    {
        $this->selectedJenjang = $params['jenjang'];
        $this->selectedTahunAjar = $params['tahunAjar'];
        $this->selectedKelas = $params['kelasId'];

        $this->reset(['searchSiswa', 'siswaSelected', 'selectAll']);
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
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pilih kelas tujuan'
            ]);
            return;
        }

        if (empty($this->siswaSelected)) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pilih siswa terlebih dahulu'
            ]);
            return;
        }

        if ($this->kelasTujuan == $this->selectedKelas) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Tidak bisa pindah ke kelas yang sama'
            ]);
            return;
        }

        DB::beginTransaction();

        try {
            // 🔥 BULK UPDATE (1 query)
            PenempatanSiswa::whereIn('ms_penempatan_siswa_id', $this->siswaSelected)
                ->update(['ms_kelas_id' => $this->kelasTujuan]);

            DB::commit();

            // RESET STATE
            $this->reset(['siswaSelected', 'selectAll', 'kelasTujuan']);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Siswa berhasil dipindahkan'
            ]);

            $this->emit('refreshKelass');
        } catch (\Throwable $e) {
            DB::rollBack();

            // report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
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

            $siswas = $query->orderBy('ms_siswa.nama_siswa')->get();
        }

        return view('livewire.akademik.kelas.change', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
