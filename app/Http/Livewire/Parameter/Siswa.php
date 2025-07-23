<?php

namespace App\Http\Livewire\Parameter;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\PenempatanSiswa;
use App\Models\Siswa as ModelsSiswa;

class Siswa extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $search = '';

    protected $listeners = [
        'refreshSiswas' => 'handleRefreshSiswas',
    ];

    public function handleRefreshSiswas()
    {
        $this->emitSelf('$refresh');
    }

    public function updatingSearch()
    {
        $this->resetPage(); // Reset pagination ketika pencarian berubah
    }
    public function selectSiswa($id)
    {
        // Cari jenjang yang sesuai dengan siswa
        $jenjangId = PenempatanSiswa::where('ms_siswa_id', $id)
            ->value('ms_jenjang_id'); // Mengambil nilai ms_jenjang_id pertama yang ditemukan

        if (!$jenjangId) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang siswa tidak ditemukan.']);
            return;
        }

        // Emit event dengan ID siswa dan ID jenjang
        $this->emit('siswaSelected', $id, $jenjangId);
    }

    public function render()
    {
        $query = ModelsSiswa::query();

        // Ambil ID jenjang dari akses petugas yang login
        $jenjangIds = DB::table('ms_akses_jenjang')
            ->where('ms_pengguna_id', Auth::id())
            ->pluck('ms_jenjang_id');

        // Filter siswa berdasarkan penempatan siswa yang sesuai dengan jenjang akses petugas
        $query->whereIn('ms_siswa_id', function ($subQuery) use ($jenjangIds) {
            $subQuery->select('ms_siswa_id')
                ->from('ms_penempatan_siswa')
                ->whereIn('ms_jenjang_id', $jenjangIds); // Sesuai akses jenjang
        });

        // Filter berdasarkan nama siswa (jika search diisi)
        if (!empty($this->search)) {
            $query->where('nama_siswa', 'like', '%' . $this->search . '%');
        }

        // Urutkan dan paginasi
        $siswas = $query->orderBy('nama_siswa')->paginate(50);

        return view('livewire.parameter.siswa', [
            'siswa' => $siswas
        ]);
    }
}
