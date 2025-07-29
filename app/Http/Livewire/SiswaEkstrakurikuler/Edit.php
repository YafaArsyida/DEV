<?php

namespace App\Http\Livewire\SiswaEkstrakurikuler;

use App\Models\Ekstrakurikuler;
use App\Models\PenempatanEkstrakurikuler;
use App\Models\PenempatanSiswa;
use Livewire\Component;

class Edit extends Component
{
    public $ms_siswa_id, $nama_siswa, $nama_kelas, $telepon, $created_at;
    public $ms_jenjang_id;
    public $ms_penempatan_siswa_id;
    public $ms_ekstrakurikuler_id = []; // checkbox terpilih
    public $selectedEkstrakurikuler = [];

    protected $listeners = ['editSiswaEkstrakurikuler'];

    public function editSiswaEkstrakurikuler($ms_penempatan_siswa_id)
    {
        $penempatan = PenempatanSiswa::with([
            'ms_kelas',
            'ms_siswa.ms_penempatan_ekstrakurikuler.ms_ekstrakurikuler'
        ])->findOrFail($ms_penempatan_siswa_id);

        $siswa = $penempatan->ms_siswa;

        $this->ms_penempatan_siswa_id = $penempatan->ms_penempatan_siswa_id;
        $this->ms_jenjang_id = $penempatan->ms_jenjang_id;

        $this->ms_siswa_id = $siswa->ms_siswa_id;
        $this->nama_siswa = $siswa->nama_siswa;
        $this->nama_kelas = $penempatan->ms_kelas->nama_kelas ?? '-';
        $this->telepon = $siswa->telepon;
        $this->created_at = $siswa->created_at->format('d F Y H:i');

        // Ambil ekstrakurikuler yang sudah terdaftar
        $this->selectedEkstrakurikuler = $siswa->ms_penempatan_ekstrakurikuler->pluck('ms_ekstrakurikuler_id')->toArray();
        $this->ms_ekstrakurikuler_id = $this->selectedEkstrakurikuler;
    }
    public function update()
    {
        // Validasi data
        $this->validate([
            'ms_ekstrakurikuler_id' => 'array|min:1',
        ]);

        // Cek kuota satu per satu
        foreach ($this->ms_ekstrakurikuler_id as $ekskul_id) {
            $ekskul = Ekstrakurikuler::withCount('ms_penempatan_ekstrakurikuler')->find($ekskul_id);

            if (!$ekskul) {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Ekskul dengan ID ' . $ekskul_id . ' tidak ditemukan.'
                ]);
                return;
            }

            $jumlah_terdaftar = $ekskul->ms_penempatan_ekstrakurikuler_count;

            // Jika siswa ini sudah terdaftar sebelumnya di ekskul yang sama, kurangi 1 dari hitungan
            $sudah_terdaftar = PenempatanEkstrakurikuler::where('ms_siswa_id', $this->ms_siswa_id)
                ->where('ms_ekstrakurikuler_id', $ekskul_id)
                ->exists();

            $jumlah_terdaftar -= $sudah_terdaftar ? 1 : 0;

            if ($jumlah_terdaftar >= $ekskul->kuota) {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'EKuota untuk ekstrakurikuler ' . $ekskul->nama_ekstrakurikuler . ' sudah penuh.'
                ]);
                return;
            }
        }

        // Jika lolos validasi, hapus semua penempatan lama siswa
        PenempatanEkstrakurikuler::where('ms_siswa_id', $this->ms_siswa_id)->delete();

        // Simpan penempatan baru
        foreach ($this->ms_ekstrakurikuler_id as $ekskul_id) {
            PenempatanEkstrakurikuler::create([
                'ms_siswa_id' => $this->ms_siswa_id,
                'ms_jenjang_id' => $this->ms_jenjang_id,
                'ms_ekstrakurikuler_id' => $ekskul_id,
            ]);
        }

        $this->emit('refreshSiswas');
        $this->emit('refreshEkstrakurikuler');
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Data berhasil diperbarui']);
        $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'editSiswaEkstrakurikuler']);
    }

    public function render()
    {
        return view('livewire.siswa-ekstrakurikuler.edit', [
            'select_ekstrakurikuler' => Ekstrakurikuler::get(),
        ]);
    }
}
