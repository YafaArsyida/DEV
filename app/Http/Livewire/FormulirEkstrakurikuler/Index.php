<?php

namespace App\Http\Livewire\FormulirEkstrakurikuler;

use App\Models\Ekstrakurikuler;
use App\Models\Jenjang;
use App\Models\PenempatanEkstrakurikuler;
use App\Models\PenempatanSiswa;
use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $selectedJenjang;
    public $ms_siswa_id, $ms_ekstrakurikuler_id;
    public $selectedEkstrakurikuler = null;
    public $siswaSelected = null;
    public $ms_penempatan_siswa_id = null;
    public $ms_kelas_id = null;

    public $search = '';
    public $nama_siswa = null;
    public $nama_kelas = null;
    public $nama_jenjang = null;

    public $registrationSuccess = false;

    // Tambahan
    public $sudahTerdaftar = false;
    public $nama_ekstrakurikuler_terdaftar = null;
    
    public function updatedSelectedJenjang()
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
    }

    public function mount()
    {
        $this->selectedJenjang = 1;

        $jenjang = Jenjang::find(1);

        $this->nama_jenjang = $jenjang->nama_jenjang ?? '';
    }

    public function siswaSelected($ms_penempatan_siswa_id)
    {
        // Reset status terlebih dahulu
        $this->registrationSuccess = false;
        $this->sudahTerdaftar = false;
        $this->nama_ekstrakurikuler_terdaftar = null;
        $this->selectedEkstrakurikuler = null;

        $penempatanSiswa = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
        ])->find($ms_penempatan_siswa_id);

        if ($penempatanSiswa) {

            $this->siswaSelected =
                $penempatanSiswa->ms_siswa_id;

            $this->ms_penempatan_siswa_id =
                $penempatanSiswa->ms_penempatan_siswa_id;

            $this->ms_kelas_id =
                $penempatanSiswa->ms_kelas_id;

            $this->nama_siswa =
                $penempatanSiswa->ms_siswa->nama_siswa;

            $this->nama_kelas =
                $penempatanSiswa->ms_kelas->nama_kelas;

            $this->search = '';

            // Cek apakah siswa sudah terdaftar
            $this->cekPendaftaranSiswa();
        }
    }

    private function cekPendaftaranSiswa()
    {
        $this->sudahTerdaftar = false;
        $this->nama_ekstrakurikuler_terdaftar = null;

        if (!$this->ms_penempatan_siswa_id) {
            return;
        }

        $penempatanEkstrakurikuler = PenempatanEkstrakurikuler::with([
            'ms_ekstrakurikuler'
        ])
            ->where(
                'ms_penempatan_siswa_id',
                $this->ms_penempatan_siswa_id
            )
            ->first();

        if ($penempatanEkstrakurikuler) {
            $this->sudahTerdaftar = true;

            $this->selectedEkstrakurikuler =
                $penempatanEkstrakurikuler->ms_ekstrakurikuler_id;

            $this->nama_ekstrakurikuler_terdaftar =
                $penempatanEkstrakurikuler->ms_ekstrakurikuler
                    ->nama_ekstrakurikuler ?? '-';
        }
    }

    public function daftar()
    {
        // Validasi siswa
        if (empty($this->siswaSelected)) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Silakan pilih siswa terlebih dahulu.'
            ]);

            return;
        }

        // Validasi jenjang
        if (empty($this->selectedJenjang)) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Jenjang siswa belum dipilih.'
            ]);

            return;
        }

        // Validasi ekstrakurikuler
        if (empty($this->selectedEkstrakurikuler)) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Silakan pilih ekstrakurikuler terlebih dahulu.'
            ]);

            return;
        }

        DB::beginTransaction();

        try {
            $penempatan = PenempatanSiswa::with([
                'ms_siswa', 'ms_kelas',
            ])->find($this->ms_penempatan_siswa_id);

            if (!$penempatan) {
                throw new \Exception(
                    'Data penempatan siswa tidak ditemukan.'
                );
            }

            $ekskul = Ekstrakurikuler::withCount(
                'ms_penempatan_ekstrakurikuler'
            )->findOrFail(
                $this->selectedEkstrakurikuler
            );

            // Cek apakah siswa sudah terdaftar
            $sudahTerdaftar = PenempatanEkstrakurikuler::where(
                    'ms_penempatan_siswa_id',
                    $this->ms_penempatan_siswa_id
                )
                ->exists();

            if ($sudahTerdaftar) {
                DB::rollBack();
                $this->sudahTerdaftar = true;
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Siswa sudah terdaftar pada ekstrakurikuler.'
                ]);
                return;
            }

            // Cek kuota
            if (
                $ekskul->ms_penempatan_ekstrakurikuler_count
                >= $ekskul->kuota
            ) {

                DB::rollBack();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => "Kuota {$ekskul->nama_ekstrakurikuler} sudah penuh."
                ]);

                return;
            }

            // Simpan pendaftaran
            PenempatanEkstrakurikuler::create([
                'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                'ms_ekstrakurikuler_id' => $this->selectedEkstrakurikuler,
            ]);

            DB::commit();

            $this->emit('ekstrakurikulerTerdaftar', $this->selectedEkstrakurikuler);

            // Ambil nama ekskul untuk kartu sukses
            $this->nama_ekstrakurikuler_terdaftar = $ekskul->nama_ekstrakurikuler;

            // Tandai berhasil
            $this->registrationSuccess = true;

            // Siswa sekarang dianggap sudah terdaftar
            $this->sudahTerdaftar = true;

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Pendaftaran ekstrakurikuler berhasil disimpan.'
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan saat menyimpan pendaftaran.'
            ]);
        }
    }

    public function render()
    {
        $select_ekstrakurikuler = collect();
        $select_ekstrakurikuler = Ekstrakurikuler::where(
                'ms_jenjang_id',
                $this->selectedJenjang
            )->get();
            
        $siswas = collect();
        if ($this->search && $this->selectedJenjang) {
            $siswas = PenempatanSiswa::with([
                'ms_kelas',
                'ms_tahun_ajar',
                'ms_jenjang',
                'ms_penempatan_ekstrakurikuler.ms_ekstrakurikuler',
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

        return view('livewire.keuangan.formulir-ekstrakurikuler.index', [
            'select_ekstrakurikuler' => $select_ekstrakurikuler,
            'siswa' => $siswas
        ]);
    }
}
