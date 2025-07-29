<?php

namespace App\Http\Livewire\FormulirEkstrakurikuler;

use App\Models\Ekstrakurikuler;
use App\Models\Jenjang;
use App\Models\PenempatanEkstrakurikuler;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $selectedJenjang, $ms_siswa_id, $ms_ekstrakurikuler_id;
    public $selectedEkstrakurikuler = null;
    public $siswaSelected = null;
    public $ms_penempatan_siswa_id = null;
    public $ms_kelas_id = null;

    public $search = '';
    public $nama_siswa = null;
    public $nama_kelas = null;
    public $nama_jenjang = null;

    public function updatedSelectedJenjang()
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function mount()
    {
        // Tetapkan nilai pertama dari data yang tersedia jika ada
        $firstJenjang = Jenjang::whereIn('ms_jenjang_id', function ($query) {
            $query->select('ms_jenjang_id')
                ->from('ms_akses_jenjang')
                ->where('ms_pengguna_id', Auth::id());
        })->where('status', 'Aktif')->first();

        $this->selectedJenjang = $firstJenjang->ms_jenjang_id ?? null;
        $this->nama_jenjang = $firstJenjang->nama_jenjang ?? '';
    }

    public function siswaSelected($ms_penempatan_siswa_id)
    {
        // $siswa = Siswa::find($ms_penempatan_siswa_id);
        $penempatanSiswa = PenempatanSiswa::find($ms_penempatan_siswa_id);

        if ($penempatanSiswa) {
            $this->siswaSelected = $penempatanSiswa->ms_siswa_id;
            $this->ms_penempatan_siswa_id = $penempatanSiswa->ms_penempatan_siswa_id;
            $this->ms_kelas_id = $penempatanSiswa->ms_kelas_id;
            $this->nama_siswa = $penempatanSiswa->ms_siswa->nama_siswa;
            $this->nama_kelas = $penempatanSiswa->ms_kelas->nama_kelas;

            $this->search = '';
        }
    }

    public function daftar()
    {
        if (empty($this->siswaSelected)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pilih Siswa.']);
            return;
        }

        if (empty($this->selectedJenjang)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pilih Jenjang']);
            return;
        }
        if (empty($this->selectedEkstrakurikuler)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pilih Ekstrakurikuler.']);
            return;
        }

        $penempatan = PenempatanSiswa::with('ms_kelas')->find($this->ms_penempatan_siswa_id);

        if (!$penempatan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Penempatan siswa tidak ditemukan.']);
            return;
        }

        // $kelasId = $penempatan->ms_kelas_id;
        // $ekskulId = $this->selectedEkstrakurikuler;

        // // Validasi khusus: TIK hanya untuk kelas 3-6
        // if ($ekskulId == 1 && in_array($kelasId, [8, 9, 10, 11])) {
        //     $this->dispatchBrowserEvent('alertify-error', ['message' => 'Ekstrakurikuler TIK hanya tersedia untuk siswa kelas 3 sampai 6.']);
        //     return;
        // }

        // Cek apakah siswa sudah terdaftar di ekstrakurikuler yang sama
        $exists = PenempatanEkstrakurikuler::where([
            'ms_siswa_id' => $this->siswaSelected,
            'ms_ekstrakurikuler_id' => $this->selectedEkstrakurikuler,
            'ms_jenjang_id' => $this->selectedJenjang,
        ])->exists();

        if ($exists) {
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Siswa sudah terdaftar dalam ekstrakurikuler.']);
            return;
        }

        // Ambil data ekstrakurikuler untuk cek kuota
        $ekstra = Ekstrakurikuler::find($this->selectedEkstrakurikuler);

        if ($ekstra) {
            $kuota = (int) $ekstra->kuota;
            $terisi = $ekstra->total_penempatan_siswa();
            $tersedia = max($kuota - $terisi, 0);

            if ($tersedia <= 0) {
                $this->dispatchBrowserEvent('alertify-error', ['message' => 'Kuota ekstrakurikuler sudah penuh.']);
                return;
            }
        }

        // Simpan ke database
        PenempatanEkstrakurikuler::create([
            'ms_ekstrakurikuler_id' => $this->selectedEkstrakurikuler,
            'ms_siswa_id' => $this->siswaSelected,
            'ms_jenjang_id' => $this->selectedJenjang,
        ]);

        // Reset input
        $this->siswaSelected = null;
        $this->selectedEkstrakurikuler = null;
        $this->nama_siswa = null;

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Pendaftaran berhasil disimpan.']);
    }
    public function render()
    {
        $select_ekstrakurikuler = collect(); // ← Tambahkan ini
        if ($this->siswaSelected) {
            $select_ekstrakurikuler = Ekstrakurikuler::where('ms_jenjang_id', $this->selectedJenjang)
                ->get();

            // Jika siswa sudah dipilih
            if (in_array($this->ms_kelas_id, [8, 9, 10, 11])) {
                $select_ekstrakurikuler->where('ms_ekstrakurikuler_id', '!=', 1);
            }
        }

        $siswas = collect();
        if ($this->search && $this->selectedJenjang) {
            $siswas = PenempatanSiswa::with([
                'ms_kelas',
                'ms_tahun_ajar',
                'ms_jenjang',
                'ms_siswa.ms_penempatan_ekstrakurikuler.ms_ekstrakurikuler',
            ])
                ->join('ms_siswa', 'ms_penempatan_siswa.ms_siswa_id', '=', 'ms_siswa.ms_siswa_id')
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->where(function ($query) {
                    $query->whereHas('ms_siswa', function ($query) {
                        $query->where('nama_siswa', 'like', '%' . $this->search . '%');
                    });
                })
                ->limit(10)
                ->get();
        }

        return view('livewire.formulir-ekstrakurikuler.index', [
            'select_jenjang' => Jenjang::where('status', 'Aktif')->get(),

            'select_ekstrakurikuler' => $select_ekstrakurikuler,
            'siswa' => $siswas
        ]);
    }
}
