<?php

namespace App\Http\Livewire\TahunAjar;

use App\Models\AkuntansiJurnalDetail;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TahunAjar as TahunAjarModel;
use App\Models\Jenjang;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Index extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap'; // Gunakan tema Bootstrap

    public $search = '';
    public $selectedStatus = null;
    public $selectedJenjang = null;

    public function mount()
    {
        // Tetapkan nilai pertama dari data yang tersedia jika ada
        $firstJenjang = Jenjang::whereIn('ms_jenjang_id', function ($query) {
            $query->select('ms_jenjang_id')
                ->from('ms_akses_jenjang')
                ->where('ms_pengguna_id', Auth::id());
        })->where('status', 'Aktif')->first();

        $this->selectedJenjang = $firstJenjang->ms_jenjang_id ?? null;
    }

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
        $jenjang = $this->selectedJenjang;

        // Cek apakah sudah ditutup
        if ($tahunAjar->tutup_buku === 'sudah') {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tahun ajaran ini sudah ditutup sebelumnya']);
            return;
        }

        // Ambil nilai laba/rugi dari fungsi model
        $labaRugi = $tahunAjar->hitungLabaRugi($jenjang);

        // Akun-akun
        $akunLabaRugi = '32001';
        $akunAkumulasi = '33001';
        $tanggal = now();
        $deskripsi = "Tutup Buku Tahun Ajaran {$tahunAjar->nama_tahun_ajar}";

        DB::beginTransaction();
        try {
            if ($labaRugi !== 0) {
                if ($labaRugi > 0) {
                    // Laba: debit laba rugi, kredit saldo ditahan
                    AkuntansiJurnalDetail::create([
                        'kode_rekening' => $akunLabaRugi,
                        'posisi' => 'debit',
                        'nominal' => $labaRugi,
                        'tanggal_transaksi' => $tanggal,
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $tahunAjarId,
                        'ms_jenjang_id' => $jenjang,
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsi,
                    ]);
                    AkuntansiJurnalDetail::create([
                        'kode_rekening' => $akunAkumulasi,
                        'posisi' => 'kredit',
                        'nominal' => $labaRugi,
                        'tanggal_transaksi' => $tanggal,
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $tahunAjarId,
                        'ms_jenjang_id' => $jenjang,
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsi,
                    ]);
                } else {
                    $rugi = abs($labaRugi);
                    // Rugi: debit saldo ditahan, kredit laba rugi
                    AkuntansiJurnalDetail::create([
                        'kode_rekening' => $akunAkumulasi,
                        'posisi' => 'debit',
                        'nominal' => $rugi,
                        'tanggal_transaksi' => $tanggal,
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $tahunAjarId,
                        'ms_jenjang_id' => $jenjang,
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsi,
                    ]);
                    AkuntansiJurnalDetail::create([
                        'kode_rekening' => $akunLabaRugi,
                        'posisi' => 'kredit',
                        'nominal' => $rugi,
                        'tanggal_transaksi' => $tanggal,
                        'ms_pengguna_id' => auth()->id(),
                        'ms_tahun_ajaran_id' => $tahunAjarId,
                        'ms_jenjang_id' => $jenjang,
                        'is_canceled' => 'active',
                        'deskripsi' => $deskripsi,
                    ]);
                }
            }

            // Update status tahun ajaran
            $tahunAjar->update([
                'tutup_buku' => 'sudah',
                'tanggal_tutup_buku' => $tanggal,
                'ms_pengguna_id' => auth()->id(),
            ]);

            DB::commit();
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Tutup buku berhasil.']);
        } catch (\Exception $e) {
            DB::rollback();
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tutup buku gagal: ' . $e->getMessage()]);
        }
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
