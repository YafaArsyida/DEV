<?php

namespace App\Http\Livewire\SmartPass\PresensiPegawai;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use App\Models\Jenjang;
use Illuminate\Support\Collection;

use App\Models\EduCard;
use App\Models\SmartPass\PresensiPegawai as SmartPassPresensiPegawai;

class Index extends Component
{
    public $selectedJenjang;
    public $listJenjang;

    public $barcodeInput;
    public $riwayatAbsensi = [];

    public function mount()
    {
        // Ambil jenjang aktif & urut
        $this->listJenjang = Jenjang::whereIn('ms_jenjang_id', function ($query) {
                $query->select('ms_jenjang_id')
                    ->from('ms_akses_jenjang')
                    ->where('ms_pengguna_id', Auth::id()); // Filter berdasarkan pengguna yang login
            })->where('status', 'Aktif')->get();

        // Default jenjang pertama
        $this->selectedJenjang = $this->listJenjang->first()?->ms_jenjang_id;
        $this->loadRiwayatAbsensi();
    }

    public function updatedSelectedJenjang($value)
    {
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui...']);
        $this->loadRiwayatAbsensi();
        $this->emit('focusBarcode');

    }

    public function loadRiwayatAbsensi()
    {
        $today = Carbon::today()->toDateString();

        $this->riwayatAbsensi = SmartPassPresensiPegawai::with([
            'ms_pegawai.ms_jabatan' // asumsi ada relasi jabatan
        ])
            ->where('tanggal', $today)
            ->whereHas('ms_pegawai', function ($query) {
                $query->where('ms_jenjang_id', $this->selectedJenjang);
            })
            ->orderByRaw('COALESCE(jam_pulang, jam_masuk) DESC')
            ->limit(20)
            ->get();
    }

    public function scanDariBarcode()
    {
        $kodeKartu = trim($this->barcodeInput);

        if (!$kodeKartu) {
            return;
        }

        // 1️⃣ Cari kartu
        $educard = EduCard::where('kode_kartu', $kodeKartu)
            ->where('status_kartu', 1)
            ->where('jenis_pemilik', 'pegawai')
            ->first();

        if (!$educard || !$educard->ms_pegawai_id) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Kartu tidak valid atau tidak terdaftar'
            ]);
            $this->reset('barcodeInput');
            return;
        }

        $pegawaiId = $educard->ms_pegawai_id;

        // 2️⃣ Tentukan tanggal hari ini
        $today = Carbon::today()->toDateString();
        $now   = Carbon::now();

        // 3️⃣ Cek apakah sudah ada absensi hari ini
        $absensiHariIni = SmartPassPresensiPegawai::where('ms_pegawai_id', $pegawaiId)
            ->where('tanggal', $today)
            ->first();

        if (!$absensiHariIni) {
            // ================= MASUK =================
            SmartPassPresensiPegawai::create([
                'ms_pegawai_id'   => $pegawaiId,
                'ms_pengguna_id'  => Auth::id(),
                'kode_kartu'      => $kodeKartu,
                'tanggal'         => $today,
                'jam_masuk'       => $now->format('H:i:s'),
                'status_masuk'    => 'masuk',
                'deskripsi'       => 'Presensi masuk via kartu',
            ]);

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Presensi masuk berhasil']);
        } elseif (!$absensiHariIni->jam_pulang) {
            // ================= PULANG =================
            $absensiHariIni->update([
                'jam_pulang'     => $now->format('H:i:s'),
                'status_pulang'  => 'pulang',
                'deskripsi'      => 'Presensi via kartu',
            ]);

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Presensi pulang berhasil']);
        } else {
            // ================= SUDAH LENGKAP =================
            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Presensi hari ini sudah lengkap']);
        }

        // 4️⃣ Reset input
        $this->reset('barcodeInput');
        
        // 5️⃣ Emit ke riwayat (opsional)
        $this->loadRiwayatAbsensi();
        // $this->emit('absensiUpdated');
    }

    public function render()
    {
        return view('livewire.smart-pass.presensi-pegawai.index');
    }
}
