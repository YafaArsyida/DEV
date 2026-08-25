<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\EduCard;
use App\Models\Jenjang;
use App\Models\PenempatanSiswa;
use App\Models\SaldoEduPay;
use App\Models\SmartCanteen\Kantin;
use App\Models\SmartCanteen\KategoriProdukSmartCanteen;
use App\Models\SmartCanteen\ProdukSmartCanteen;
use Livewire\Component;

class Index extends Component
{
    public $search = '';


    public $selectedKantin = null;

    public $namaKantin = '';
    public $selectedKategori = null;

    public $smartcardInput;

    public $user_type;
    public $user_id;

    public $ms_jenjang_id;

    public $nama;
    public $educard;
    public $saldo_edupay;

    public $nama_jabatan;

    public $activeTab = 'semua'; // default tab

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    protected $listeners = [
        'refreshProduk' => '$refresh',
        'parameterUpdated',
        'resetScan',
        'filterKategori' => 'setKategori',
    ];

    public function parameterUpdated($kantin)
    {
        // Update nilai selectedKantin 
        $this->selectedKantin = $kantin;

        $j = Kantin::find($kantin);
        $this->namaKantin = $j ? $j->nama_kantin : 'Tidak Diketahui';
    }

    public function resetScan()
    {
        $this->reset([
            'user_type',
            'user_id',
            
            'nama',
            'nama_jabatan',

            'educard',
            'saldo_edupay',
        ]);
        $this->emit('resetKeranjang');
        $this->resetSmartcardInput();
    }

    public function setKategori($kategoriId)
    {
        $this->selectedKategori = $kategoriId;
    }

    public function prosesSmartcard()
    {
        $value = trim($this->smartcardInput);

        if (!$value) {
            return;
        }

        $card = EduCard::with(['ms_siswa', 'ms_pegawai.ms_jabatan'])
            ->where('kode_kartu', $value)
            ->first();

        if (!$card) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Kartu tidak terdaftar.'
            ]);

            $this->resetSmartcardInput();
            $this->resetScan();
            $this->emit('resetKeranjang');

            return;
        }

        // Reset jenjang terlebih dahulu
        $this->ms_jenjang_id = null;

        // =========================================
        // HANDLE SISWA
        // =========================================
        if ($card->ms_siswa) {

            $this->user_type = 'siswa';
            $this->user_id   = $card->ms_siswa->ms_siswa_id;
            $this->nama      = $card->ms_siswa->nama_siswa;
            $this->educard   = $card->kode_kartu;

            /*
            * Siswa tidak memiliki ms_jenjang_id
            * langsung di tabel ms_siswa.
            *
            * Jenjang diambil dari penempatan siswa
            * pada Tahun Ajar yang sedang aktif.
            *
            * Penempatan hanya digunakan untuk
            * mendapatkan informasi jenjang,
            * bukan sebagai validasi transaksi kantin.
            */
           $penempatan = PenempatanSiswa::where(
                'ms_siswa_id', $this->user_id
            )
            ->latest('ms_penempatan_siswa_id')
            ->first();

            if ($penempatan) {
                $this->ms_jenjang_id = $penempatan->ms_jenjang_id;
            }

            // Ambil saldo EduPay siswa
            $saldo = SaldoEduPay::getSaldo(
                $this->user_id, 'siswa'
            );

            $this->saldo_edupay = $saldo->saldo_edupay;
        }

        // HANDLE PEGAWAI
        elseif ($card->ms_pegawai) {

            $this->user_type = 'pegawai';
            $this->user_id   = $card->ms_pegawai->ms_pegawai_id;
            $this->nama      = $card->ms_pegawai->nama_pegawai;

            // Jenjang langsung dari data pegawai
            $this->ms_jenjang_id = $card->ms_pegawai->ms_jenjang_id;

            $this->nama_jabatan = $card->ms_pegawai->ms_jabatan
                ? $card->ms_pegawai->ms_jabatan->nama_jabatan
                : null;

            $this->educard = $card->kode_kartu;

            // Ambil saldo EduPay pegawai
            $saldo = SaldoEduPay::getSaldo(
                $this->user_id,
                'pegawai'
            );

            $this->saldo_edupay = $saldo->saldo_edupay;
        }

        // KARTU VALID
        $this->emit('scanSuccess', [
            'user_type'    => $this->user_type,
            'user_id'      => $this->user_id,
            'nama'         => $this->nama,

            'nama_jabatan' => $this->nama_jabatan ?? null,

            'educard'      => $this->educard,
            'saldo_edupay' => $this->saldo_edupay,

            // Jenjang sumber transaksi
            'ms_jenjang_id' => $this->ms_jenjang_id,
            'ms_kantin_id'  => $this->selectedKantin,
        ]);

        // NOTIFIKASI
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Kartu valid.'
        ]);

        // Reset & refocus
        $this->resetSmartcardInput();
    }

    private function resetSmartcardInput()
    {
        $this->smartcardInput = '';
        $this->dispatchBrowserEvent('focus-smartcard-input');
    }

    public function render()
    {
        // default kosong
        $allProduk = collect();
        $kategori = collect();

        if ($this->selectedKantin) {
            $query = ProdukSmartCanteen::where(
                'ms_kantin_id', $this->selectedKantin
            );

            if ($this->search) {
                $query->where(
                    'nama_produk_kantin', 'like',
                    '%' . $this->search . '%'
                );
            }

            $allProduk = $query->get();
            $kategori = KategoriProdukSmartCanteen::where('ms_kantin_id', $this->selectedKantin)->get();
        }

        return view('livewire.smart-canteen.transaksi-produk.index', [
            'allProduk' => $allProduk,
            'kategori' => $kategori,
        ]);
    }
}
