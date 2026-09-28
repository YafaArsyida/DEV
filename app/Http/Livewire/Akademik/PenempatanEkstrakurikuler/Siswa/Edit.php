<?php

namespace App\Http\Livewire\Akademik\PenempatanEkstrakurikuler\Siswa;

use App\Models\Ekstrakurikuler;
use App\Models\PenempatanEkstrakurikuler;
use App\Models\PenempatanSiswa;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Edit extends Component
{
    public $ms_siswa_id, $nama_siswa, $nama_kelas, $telepon, $created_at;
    public $ms_jenjang_id;
    public $ms_penempatan_siswa_id;
    
    public $ms_ekstrakurikuler_id;

    protected $listeners = ['editSiswaEkstrakurikuler'];

    public function editSiswaEkstrakurikuler($ms_penempatan_siswa_id)
    {
        $this->resetValidation();

        $penempatan = PenempatanSiswa::with([
            'ms_siswa',
            'ms_kelas',
            'ms_penempatan_ekstrakurikuler.ms_ekstrakurikuler',
        ])->findOrFail($ms_penempatan_siswa_id);

        $this->ms_penempatan_siswa_id = $penempatan->ms_penempatan_siswa_id;
        $this->ms_jenjang_id          = $penempatan->ms_jenjang_id;

        $this->ms_siswa_id  = $penempatan->ms_siswa->ms_siswa_id;
        $this->nama_siswa   = $penempatan->ms_siswa->nama_siswa;
        $this->nama_kelas   = $penempatan->ms_kelas->nama_kelas ?? '-';
        $this->telepon      = $penempatan->ms_siswa->telepon;
        $this->created_at   = optional($penempatan->ms_siswa->created_at)->format('d F Y H:i');

        // Ekstrakurikuler yang dipilih (hasOne)
        $this->ms_ekstrakurikuler_id = optional(
            $penempatan->ms_penempatan_ekstrakurikuler
        )->ms_ekstrakurikuler_id;
    }

    public function update()
    {
        DB::beginTransaction();

        try {
            $this->validate([
                'ms_ekstrakurikuler_id' => 'required|exists:ms_ekstrakurikuler,ms_ekstrakurikuler_id',
            ]);

            $ekskul = Ekstrakurikuler::withCount('ms_penempatan_ekstrakurikuler')
                ->findOrFail($this->ms_ekstrakurikuler_id);

            // Cek apakah siswa sudah memilih ekstrakurikuler ini
            $sudahTerdaftar = PenempatanEkstrakurikuler::where(
                    'ms_penempatan_siswa_id',
                    $this->ms_penempatan_siswa_id
                )
                ->where('ms_ekstrakurikuler_id', $this->ms_ekstrakurikuler_id)
                ->exists();

            $jumlahTerdaftar = $ekskul->ms_penempatan_ekstrakurikuler_count;

            if ($sudahTerdaftar) {
                $jumlahTerdaftar--;
            }

            if ($jumlahTerdaftar >= $ekskul->kuota) {
                throw new \Exception(
                    "Kuota {$ekskul->nama_ekstrakurikuler} sudah penuh."
                );
            }

            PenempatanEkstrakurikuler::updateOrCreate(
                [
                    'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                ],
                [
                    'ms_ekstrakurikuler_id' => $this->ms_ekstrakurikuler_id,
                ]
            );

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Ekstrakurikuler berhasil diperbarui.'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'editSiswaEkstrakurikuler'
            ]);

            $this->emit('refreshSiswas');
            $this->emit('refreshEkstrakurikuler');

        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Validasi gagal, cek kembali pilihan ekstrakurikuler.'
            ]);

            throw $e;

        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem.'
            ]);
        }
    }
    public function render()
    {
        return view('livewire.akademik.penempatan-ekstrakurikuler.siswa.edit',[
            'select_ekstrakurikuler' => Ekstrakurikuler::query()
                ->withCount([
                    'ms_penempatan_ekstrakurikuler as total_peserta',
                ])
                ->get(),
        ]);
    }
}
