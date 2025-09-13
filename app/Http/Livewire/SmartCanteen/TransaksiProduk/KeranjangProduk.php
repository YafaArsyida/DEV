<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\KeranjangSmartCanteen;
use Livewire\Component;

class KeranjangProduk extends Component
{
    public $totalKeranjang = 0;

    public $user_type;
    public $user_id;
    public $nama;
    public $educard;
    public $saldo_edupay;

    protected $listeners = [
        'scanSuccess',
        'tambahKeranjang',
    ];

    public function scanSuccess($data)
    {
        $this->user_type    = $data['user_type'];   // 'siswa' atau 'pegawai'
        $this->user_id      = $data['user_id'];     // ms_siswa_id atau ms_pegawai_id
        $this->nama         = $data['nama'];        // nama siswa/pegawai
        $this->educard      = $data['educard'];
        $this->saldo_edupay = $data['saldo_edupay'];
    }

    public function tambahKeranjang($produkId)
    {
        if (!$this->user_id || !$this->user_type) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Gagal, Scan EduCard']);
            return;
        }

        // cek apakah produk sudah ada di keranjang
        $item = KeranjangSmartCanteen::where('user_type', $this->user_type)
            ->where('user_id', $this->user_id)
            ->where('ms_produk_kantin_id', $produkId)
            ->first();

        if ($item) {
            // jika sudah ada, update jumlah
            $item->increment('jumlah_produk');
        } else {
            // jika belum ada, buat baru
            KeranjangSmartCanteen::create([
                'user_type' => $this->user_type,
                'user_id' => $this->user_id,
                'ms_produk_kantin_id' => $produkId,
                'ms_pengguna_id' => auth()->id(),
                'jumlah_produk' => 1,
            ]);
        }

        // reload keranjang
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil menambah produk']);
        $this->emitSelf('$refresh'); // Memicu render ulang komponen sendiri
    }


    public function render()
    {
        // default collection kosong
        $keranjang = collect();
        $this->totalKeranjang = 0;
        if ($this->user_id) {
            $ms_pengguna_id = auth()->id();
            $keranjang = KeranjangSmartCanteen::with('ms_produk_kantin')
                ->where('user_id', $this->user_id)
                ->where('ms_pengguna_id', $ms_pengguna_id)
                ->get();

            // Hitung total keranjang
            $this->totalKeranjang = $keranjang->sum(function ($item) {
                return ($item->ms_produk_kantin->harga ?? 0) * $item->jumlah_produk;
            });
        }
        // dd([
        //     'user_type'    => $this->user_type,
        //     'user_id'      => $this->user_id,
        //     'nama'         => $this->nama,
        //     'educard'      => $this->educard,
        //     'saldo_edupay' => $this->saldo_edupay,
        //     'keranjang'    => $keranjang,
        //     'totalKeranjang' => $this->totalKeranjang,
        // ]);


        return view('livewire.smart-canteen.transaksi-produk.keranjang-produk', [
            'keranjang' => $keranjang,
            'totalKeranjang' => $this->totalKeranjang,
        ]);
    }
}
