<?php

namespace App\Http\Livewire\Kelas;

use App\Models\AktifitasPengguna;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;

use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\Tagihan;
use App\Models\TagihanSiswa;
use App\Models\TahunAjar;

class Promote extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedJenjang; // Jenjang saat ini
    public $selectedTahunAjar; // Tahun ajar saat ini
    public $selectedKelas; // Kelas saat ini

    public $siswaSelected = []; // ID siswa yang dipilih
    public $kelasTujuan = null; // Kelas tujuan
    public $searchSiswa = '';

    public $selectAll = false;

    public $tahunAjarBerikut; // Tahun ajar berikutnya

    protected $listeners = ['showPromote' => 'loadKelas'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function loadKelas($params)
    {
        $this->selectedJenjang = $params['jenjang'];
        $this->selectedTahunAjar = $params['tahunAjar'];
        $this->selectedKelas = $params['kelasId'];

        // Cari tahun ajar berikutnya berdasarkan urutan ID
        $this->tahunAjarBerikut = TahunAjar::where('ms_tahun_ajar_id', '>', $this->selectedTahunAjar)
            ->orderBy('ms_tahun_ajar_id', 'asc')
            ->first();
    }

    public function updatedSelectAll($value)
    {
        if ($value) {
            // Pilih semua siswa dalam halaman saat ini
            $this->siswaSelected = PenempatanSiswa::where('ms_kelas_id', $this->selectedKelas)
                ->pluck('ms_penempatan_siswa_id')
                ->toArray();
        } else {
            $this->siswaSelected = [];
        }
    }

    public function naikKelasSiswa()
    {
        if (!$this->tahunAjarBerikut) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tahun ajar berikutnya tidak ditemukan.']);
            return;
        }

        if (!$this->kelasTujuan) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pilih kelas tujuan terlebih dahulu.']);
            return;
        }

        if (empty($this->siswaSelected)) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pilih siswa yang ingin dinaikkan.']);
            return;
        }

        $ms_pengguna_id = Auth::id();
        $siswaSudahAda = []; // Untuk menyimpan siswa yang sudah dinaikkan sebelumnya

        // Query untuk mendapatkan nama tahun ajaran berdasarkan ID
        $tahunAjarNama = $this->tahunAjarBerikut->nama_tahun_ajar ?? 'Tidak Diketahui';

        $kelasTujuan = Kelas::find($this->kelasTujuan);
        $namaKelasTujuan = $kelasTujuan ? $kelasTujuan->nama_kelas : 'Tidak Diketahui';

        foreach ($this->siswaSelected as $siswaId) {
            $siswa = PenempatanSiswa::with('ms_siswa')->find($siswaId);

            if ($siswa) {
                // Cek apakah siswa sudah ada di tahun ajar berikutnya
                $existingPenempatan = PenempatanSiswa::where('ms_siswa_id', $siswa->ms_siswa_id)
                    ->where('ms_tahun_ajar_id', $this->tahunAjarBerikut->ms_tahun_ajar_id)
                    ->exists();

                if ($existingPenempatan) {
                    $siswaSudahAda[] = $siswa->siswa->nama_siswa ?? 'Tidak Diketahui'; // Simpan nama siswa untuk laporan
                    continue; // Lewati siswa yang sudah ada
                }

                // Buat penempatan baru
                PenempatanSiswa::create([
                    'ms_siswa_id' => $siswa->ms_siswa_id,
                    'ms_kelas_id' => $this->kelasTujuan,
                    'ms_tahun_ajar_id' => $this->tahunAjarBerikut->ms_tahun_ajar_id,
                    'ms_jenjang_id' => $siswa->ms_jenjang_id,
                    'ms_pengguna_id' => $ms_pengguna_id, // ID pengguna yang login
                ]);
            }
        }

        $this->siswaSelected = [];
        $this->selectAll = false;

        if (!empty($siswaSudahAda)) {
            $message = 'Siswa berikut sudah dinaikkan sebelumnya: ' . implode(', ', $siswaSudahAda);
            $this->dispatchBrowserEvent('alertify-error', ['message' => $message]);
        } else {
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Siswa berhasil dinaikkan ke kelas tujuan.']);
        }
    }

    public function batalNaikKelasSiswa($siswaId)
    {
        if (!$this->tahunAjarBerikut) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Tahun ajar berikutnya tidak ditemukan.']);
            return;
        }

        // Cari penempatan siswa di tahun ajar berikutnya
        $penempatanBerikut = PenempatanSiswa::where('ms_siswa_id', $siswaId)
            ->where('ms_tahun_ajar_id', $this->tahunAjarBerikut->ms_tahun_ajar_id)
            ->first();

        if (!$penempatanBerikut) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Penempatan siswa tidak ditemukan.']);
            return;
        }

        // Cek apakah ada tagihan terkait dengan penempatan ini
        $tagihanExist = TagihanSiswa::where('ms_penempatan_siswa_id', $penempatanBerikut->ms_penempatan_siswa_id)->exists();

        if ($tagihanExist) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Pembatalan tidak diperbolehkan karena terdapat tagihan']);
            return;
        }

        // Ambil data siswa, kelas, dan tahun ajar untuk log aktivitas
        $nama_siswa = $penempatanBerikut->ms_siswa->nama_siswa ?? 'Tidak Diketahui';
        $nama_kelas = $penempatanBerikut->ms_kelas->nama_kelas ?? 'Tidak Diketahui';

        $tahunAjarNama = $this->tahunAjarBerikut->nama_tahun_ajar ?? 'Tidak Diketahui';

        $penempatanBerikut->delete();
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Penempatan siswa berhasil dibatalkan.']);
    }

    public function render()
    {
        $select_kelas = [];
        if ($this->tahunAjarBerikut) {
            $select_kelas = Kelas::where('ms_jenjang_id', $this->selectedJenjang)
                ->where('ms_tahun_ajar_id', $this->tahunAjarBerikut->ms_tahun_ajar_id)
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

        return view('livewire.kelas.promote', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
