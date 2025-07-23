<?php

namespace App\Http\Livewire\TahunAjar;

use App\Models\AkuntansiJurnalDetail;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAjar as TahunAjarModel;
use App\Models\Jenjang;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $search = '';
    public $selectedStatus = null;
    public $selectedJenjang = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedStatus()
    {
        $this->resetPage();
    }

    protected $listeners = ['refreshTahunAjars' => '$refresh']; // Gunakan Livewire refresh untuk memuat ulang data

    public function toggleStatus($tahunId, $status)
    {
        $tahun_ajar = TahunAjarModel::find($tahunId);

        if ($tahun_ajar) {
            $tahun_ajar->status = $status ? 'Aktif' : 'Tidak Aktif';
            $tahun_ajar->save();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Status berhasil diubah!']);
            $this->emit('refreshTahunAjars');
        } else {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal mengubah status. Data tidak ditemukan.']);
        }
    }

    public function tutupBuku($tahunAjarId)
    {
        $tahunAjar = TahunAjarModel::findOrFail($tahunAjarId);

        // Optional: validasi ulang apakah sudah ditutup
        if ($tahunAjar->tutup_buku === 'sudah') {
            session()->flash('info', 'Tahun ajaran ini sudah ditutup sebelumnya.');
            return;
        }

        // Ambil jenjang yang sedang dipilih user
        $jenjang = $this->selectedJenjang;

        // Hitung laba rugi tahun ajaran untuk jenjang tsb
        $labaRugi = $tahunAjar->hitungLabaRugi($jenjang);

        // Simulasi ID akun (ganti sesuai real ID akun Anda)
        $akunLabaRugi = 39900;
        $akunEkuitas = 32000;

        // Buat jurnal penutup
        if ($labaRugi > 0) {
            // Jika laba
            AkuntansiJurnalDetail::create([
                ['akun_id' => $akunLabaRugi, 'debet' => $labaRugi, 'kredit' => 0],
                ['akun_id' => $akunEkuitas, 'debet' => 0, 'kredit' => $labaRugi],
            ]);
        } elseif ($labaRugi < 0) {
            // Jika rugi
            $rugi = abs($labaRugi);
            AkuntansiJurnalDetail::create([
                ['akun_id' => $akunEkuitas, 'debet' => $rugi, 'kredit' => 0],
                ['akun_id' => $akunLabaRugi, 'debet' => 0, 'kredit' => $rugi],
            ]);
        } else {
            // Jika nol, Anda bisa skip jurnal atau buat jurnal imbang
        }

        // Update status tahun ajaran
        $tahunAjar->update([
            'tutup_buku' => 'sudah',
            'tanggal_tutup_buku' => now(),
            'ms_pengguna_id' => Auth::id(),
        ]);

        session()->flash('success', 'Tahun ajaran berhasil ditutup.');
    }
    public function render()
    {
        $query = TahunAjarModel::query();

        if ($this->selectedStatus) {
            $query->where('status', $this->selectedStatus);
        }

        $tahunajars = $query->where(function ($query) {
            $query->where('nama_tahun_ajar', 'like', '%' . $this->search . '%')
                ->orWhere('deskripsi', 'like', '%' . $this->search . '%');
        })
            ->orderBy('urutan', 'ASC')
            ->paginate(5);
        return view('livewire.tahun-ajar.index', [
            'tahunajars' => $tahunajars,
            'selectedJenjang' => $this->selectedJenjang,
            'select_jenjang' => Jenjang::whereIn('ms_jenjang_id', function ($query) {
                $query->select('ms_jenjang_id')
                    ->from('ms_akses_jenjang')
                    ->where('ms_pengguna_id', Auth::id()); // Filter berdasarkan pengguna yang login
            })->where('status', 'Aktif')->get(),
        ]);
    }
}
