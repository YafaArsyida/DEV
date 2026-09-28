<?php

namespace App\Http\Livewire\Akademik\Kelas;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Kelas;
use App\Models\PenempatanSiswa;
use App\Models\TagihanSiswa;
use App\Models\TahunAjar;

class Promote extends Component
{
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

        // Cari tahun ajar berikutnya berdasarkan urutan ID
        $this->tahunAjarBerikut = TahunAjar::where('ms_tahun_ajar_id', '>', $this->selectedTahunAjar)
            ->orderBy('ms_tahun_ajar_id', 'asc')
            ->first();
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

    public function naikKelasSiswa()
    {
        if (!$this->tahunAjarBerikut) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Tahun ajar berikut tidak ditemukan'
            ]);
            return;
        }

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

        DB::beginTransaction();

        try {
            $tahunAjarId = $this->tahunAjarBerikut->ms_tahun_ajar_id;

            // 🔥 ambil semua penempatan sekaligus (NO N+1)
            $penempatans = PenempatanSiswa::with('ms_siswa:ms_siswa_id,ms_siswa_id,nama_siswa')
                ->whereIn('ms_penempatan_siswa_id', $this->siswaSelected)
                ->get();

            $siswaIds = $penempatans->pluck('ms_siswa_id')->toArray();

            // 🔥 cek yang sudah ada (1 query)
            $existing = PenempatanSiswa::whereIn('ms_siswa_id', $siswaIds)
                ->where('ms_tahun_ajar_id', $tahunAjarId)
                ->pluck('ms_siswa_id')
                ->toArray();

            $insertData = [];
            $sudahAda = [];

            foreach ($penempatans as $p) {
                if (in_array($p->ms_siswa_id, $existing)) {
                    $sudahAda[] = $p->ms_siswa->nama_siswa ?? '-';
                    continue;
                }

                $insertData[] = [
                    'ms_siswa_id' => $p->ms_siswa_id,
                    'ms_kelas_id' => $this->kelasTujuan,
                    'ms_tahun_ajar_id' => $tahunAjarId,
                    'ms_jenjang_id' => $p->ms_jenjang_id,
                    'ms_pengguna_id' => auth()->id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            // 🔥 BULK INSERT
            if (!empty($insertData)) {
                DB::table('ms_penempatan_siswa')->insert($insertData);
            }

            DB::commit();

            // RESET
            $this->reset(['siswaSelected', 'selectAll']);

            // UX FEEDBACK
            if (!empty($sudahAda)) {
                $this->dispatchBrowserEvent('alertify-success', [
                    'message' => 'Sebagian siswa sudah ada: ' . implode(', ', array_slice($sudahAda, 0, 5))
                ]);
            } else {
                $this->dispatchBrowserEvent('alertify-success', [
                    'message' => 'Semua siswa berhasil dinaikkan'
                ]);
            }

            $this->emit('refreshKelass');
        } catch (\Throwable $e) {
            DB::rollBack();

            // report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function batalNaikKelasSiswa($siswaId)
    {
        if (!$this->tahunAjarBerikut) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Tahun ajar berikut tidak ditemukan'
            ]);
            return;
        }

        DB::beginTransaction();

        try {
            $penempatan = PenempatanSiswa::where('ms_siswa_id', $siswaId)
                ->where('ms_tahun_ajar_id', $this->tahunAjarBerikut->ms_tahun_ajar_id)
                ->first();

            if (!$penempatan) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Penempatan tidak ditemukan'
                ]);
                return;
            }

            // 🔥 cek tagihan (1 query)
            $hasTagihan = TagihanSiswa::where('ms_penempatan_siswa_id', $penempatan->ms_penempatan_siswa_id)
                ->exists();

            if ($hasTagihan) {
                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Tidak bisa dibatalkan karena ada tagihan'
                ]);
                return;
            }

            $penempatan->delete();

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil dibatalkan'
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

            $siswas = $query->orderBy('ms_siswa.nama_siswa')->get();
        }

        return view('livewire.akademik.kelas.promote', [
            'select_kelas' => $select_kelas,
            'siswas' => $siswas,
        ]);
    }
}
