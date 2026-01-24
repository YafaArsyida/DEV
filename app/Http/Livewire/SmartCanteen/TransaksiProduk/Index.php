<?php

namespace App\Http\Livewire\SmartCanteen\TransaksiProduk;

use App\Models\EduCard;
use App\Models\Jenjang;
use App\Models\PenempatanSiswa;
use App\Models\SmartCanteen\KategoriProdukSmartCanteen;
use App\Models\SmartCanteen\ProdukSmartCanteen;
use Livewire\Component;

class Index extends Component
{
    public $search = '';


    public $selectedJenjang = null;
    public $selectedTahunAjar = null;

    public $namaJenjang = '';
    public $selectedKategori = null;

    public $smartcardInput;

    public $user_type;
    public $user_id;
    public $ms_penempatan_siswa_id;
    public $nama;
    public $nama_kelas;
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

    public function parameterUpdated($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;

        $j = Jenjang::find($jenjang);
        $this->namaJenjang = $j ? $j->nama_jenjang : 'Tidak Diketahui';
    }

    public function resetScan()
    {
        $this->reset([
            'user_type',
            'user_id',
            'ms_penempatan_siswa_id',
            'nama',
            'nama_kelas',
            'educard',
            'saldo_edupay',

            // 'ms_jenjang_id',
            // 'ms_tahun_ajar_id',

            'nama_jabatan',
        ]);
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

        $card = EduCard::with(['ms_siswa', 'ms_pegawai'])
            ->where('kode_kartu', $value)
            ->first();

        if (!$card) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Kartu tidak terdaftar.']);
            $this->resetSmartcardInput();
            $this->resetScan();
            $this->emit('resetScan');
            return;
        }

        // =========================================
        //  HANDLE SISWA
        // =========================================
        if ($card->ms_siswa) {
            $penempatan = PenempatanSiswa::where('ms_siswa_id', $card->ms_siswa->ms_siswa_id)
                ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
                ->where('ms_jenjang_id', $this->selectedJenjang)
                ->first();

            if (!$penempatan) {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Transaksi ditolak. Tidak ada penempatan pada Jenjang/Tahun Ajar ini.'
                ]);

                $this->resetSmartcardInput();
                return;
            }

            $this->user_type  = 'siswa';
            $this->user_id    = $card->ms_siswa->ms_siswa_id;
            $this->ms_penempatan_siswa_id = $penempatan->ms_penempatan_siswa_id;

            $this->nama       = $card->ms_siswa->nama_siswa;
            $this->nama_kelas = $penempatan->ms_kelas->nama_kelas;
            $this->educard    = $card->kode_kartu;
            $this->saldo_edupay = $card->ms_siswa->saldo_edupay_siswa();
        }

        // =========================================
        //  HANDLE PEGAWAI
        // =========================================
        elseif ($card->ms_pegawai) {

            $this->user_type  = 'pegawai';
            $this->user_id    = $card->ms_pegawai->ms_pegawai_id;

            $this->nama       = $card->ms_pegawai->nama_pegawai;
            $this->nama_jabatan = $card->ms_pegawai->ms_jabatan->nama_jabatan;

            $this->educard    = $card->kode_kartu;
            $this->saldo_edupay = method_exists($card->ms_pegawai, 'saldo_edupay_pegawai')
                ? $card->ms_pegawai->saldo_edupay_pegawai()
                : 0;
        }

        $this->emit('scanSuccess', [
            'user_type'             => $this->user_type,
            'user_id'               => $this->user_id,
            'ms_penempatan_siswa_id' => $this->ms_penempatan_siswa_id,
            'nama'                  => $this->nama,
            'nama_kelas'            => $this->nama_kelas ?? null,
            'nama_jabatan'            => $this->nama_jabatan ?? null,
            'educard'               => $this->educard,
            'saldo_edupay'          => $this->saldo_edupay,
            'ms_jenjang_id'         => $this->selectedJenjang,
            'ms_tahun_ajar_id'      => $this->selectedTahunAjar,
        ]);

        // NOTIFIKASI
        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Kartu valid.']);

        // Reset & Refocus
        $this->resetSmartcardInput();
    }

    private function resetSmartcardInput()
    {
        $this->smartcardInput = '';
        $this->dispatchBrowserEvent('focus-smartcard-input');
    }

    public function render()
    {
        $query = ProdukSmartCanteen::query();

        if ($this->selectedJenjang) {
            $query->where('ms_jenjang_id', $this->selectedJenjang);
        }

        if ($this->search) {
            $query->where('nama_produk_kantin', 'like', '%' . $this->search . '%');
        }

        if (auth()->check()) {
            $peran = auth()->user()->peran;

            if ($peran === 'kantin') {
                $query->where('ms_pengguna_id', auth()->id());
            }
            // superadmin → tidak difilter (lihat semua)
        }

        // ini ambil SEMUA produk sesuai jenjang + search
        $allProduk = $query->get();

        // ambil kategori
        $kategori = KategoriProdukSmartCanteen::where('ms_jenjang_id', $this->selectedJenjang)->get();

        return view('livewire.smart-canteen.transaksi-produk.index', [
            'allProduk' => $allProduk,
            'kategori' => $kategori,
        ]);
    }
}
