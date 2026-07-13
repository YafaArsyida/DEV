<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Http\Controllers\HelperController;
use App\Models\KeranjangTagihanSiswa;
use App\Models\PenempatanSiswa;
use App\Models\SuratTagihanSiswa;
use App\Models\TagihanSiswa;
use App\Models\User;
use App\Models\WhatsAppTagihanSiswa;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\WithPagination;

class DataTagihan extends Component
{
    public $ms_penempatan_siswa_id;
    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $ms_siswa_id;

    public $tagihans = [];

    public $totalEstimasi;
    public $totalDibayarkan;
    public $totalKekurangan;

    public $siswaSelected = false;

    protected $listeners = [
        'siswaSelected', // Listener untuk parameter siswa yang dipilih

        'reloadTagihanSiswa' => 'loadTagihan',
        
        'refreshTagihanSiswa' => 'loadTagihan'
    ];

    public function siswaSelected($id)
    {
        $penempatan = PenempatanSiswa::with('ms_siswa')->find($id);

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Tagihan dimuat'
        ]);

        $this->siswaSelected = true;
        $this->ms_penempatan_siswa_id = $id;

        $this->ms_jenjang_id = $penempatan->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $penempatan->ms_tahun_ajar_id;
        $this->ms_siswa_id = $penempatan->ms_siswa_id;

        $this->loadTagihan(); // 🔥 load sekali
    }

    public function loadTagihan()
    {
        $tagihans = TagihanSiswa::query()
            ->with(['ms_jenis_tagihan_siswa.ms_kategori_tagihan_siswa'])
            ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar')
            ->orderBy('ms_jenis_tagihan_siswa_id')
            ->get();

        $keranjangIds = KeranjangTagihanSiswa::where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->whereIn('ms_tagihan_siswa_id', $tagihans->pluck('ms_tagihan_siswa_id'))
            ->pluck('ms_tagihan_siswa_id')
            ->flip();

        $this->tagihans = $tagihans->map(function ($item) use ($keranjangIds) {
            $totalBayar = $item->total_bayar ?? 0;

            return [
                'ms_tagihan_siswa_id' => $item->ms_tagihan_siswa_id,
                'nama_jenis' => $item->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa,
                'nama_kategori' => $item->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa,
                'jumlah_tagihan_siswa' => $item->jumlah_tagihan_siswa,
                'total_bayar' => $totalBayar,
                'kekurangan' => $item->jumlah_tagihan_siswa - $totalBayar,
                'cicilan_status' => $item->ms_jenis_tagihan_siswa->cicilan_status,
                'status' => $item->status,
                'in_keranjang' => isset($keranjangIds[$item->ms_tagihan_siswa_id]),
            ];
        })->values()->toArray(); // 🔥 WAJIB
    }

    public function tambahKeranjang($tagihanId)
    {
        DB::beginTransaction();
        
        try {
            if (!$this->ms_penempatan_siswa_id) {
                throw new \Exception('Siswa belum dipilih.');
            }

            $tagihan = TagihanSiswa::query()
                ->where('ms_tagihan_siswa_id', $tagihanId)
                ->where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
                ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar')
                ->first();

            if (!$tagihan) {
                throw new \Exception('Tagihan tidak ditemukan');
            }

            $jumlahBayar = max(
                0,
                ($tagihan->jumlah_tagihan_siswa ?? 0) - ($tagihan->total_bayar ?? 0)
            );

            if ($jumlahBayar <= 0) {
                throw new \Exception('Tagihan sudah lunas');
            }

            KeranjangTagihanSiswa::firstOrCreate(
                [
                    'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
                    'ms_tagihan_siswa_id' => $tagihanId,
                ],
                [
                    'ms_pengguna_id' => auth()->id(),
                    'jumlah_bayar' => $jumlahBayar,
                    'tanggal_dibayar' => now(),
                    'status' => 'Lunas',
                    'deskripsi' => "Tagihan #{$tagihanId} dimasukkan ke keranjang",
                ]
            );

            $tagihan->update([
                'status' => 'Masuk Keranjang',
                'deskripsi' => 'Masuk keranjang oleh petugas ' . auth()->id()
            ]);

            DB::commit();

            // 🔥 update local state (INI KUNCI UTAMA)
            foreach ($this->tagihans as $i => $item) {
                if ($item['ms_tagihan_siswa_id'] == $tagihanId) {
                    $this->tagihans[$i]['in_keranjang'] = true;
                    $this->tagihans[$i]['status'] = 'Masuk Keranjang';
                    break;
                }
            }

            
            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Berhasil masuk keranjang'
            ]);
            
            $this->emit('keranjangUpdated');
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.data-tagihan', [
            'tagihans' => $this->tagihans,
            'totalEstimasi' => collect($this->tagihans)->sum(fn($x) => $x['jumlah_tagihan_siswa'] ?? 0),
            'totalDibayarkan' => collect($this->tagihans)->sum(fn($x) => $x['total_bayar'] ?? 0),
            'totalKekurangan' => collect($this->tagihans)->sum(fn($x) => $x['kekurangan'] ?? 0),
        ]);
    }
}
