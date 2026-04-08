<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\PenempatanSiswa;
use App\Models\SaldoTabungan;
use App\Models\Siswa;
use App\Models\TagihanSiswa;
use Livewire\Component;

class DataSiswa extends Component
{
    public $nama_jenjang = null, $nama_tahun_ajar = null, $ms_penempatan_siswa_id = null, $nama_siswa = null, $nama_kelas = null, $telepon = null, $educard = null, $deskripsi = null;

    public $ms_jenjang_id;
    public $ms_tahun_ajar_id;
    public $ms_siswa_id;

    public $saldoTabunganSiswa;
    public $saldoEduPaySiswa;

    public $totalEstimasi;
    public $totalDibayarkan;
    public $totalKekurangan;

    protected $listeners = [
        'siswaSelected',

        'siswaUpdated', //from update data

        'refreshTagihanSiswa',
        
        'successTransaksiTabungan',

        'successTransaksiEduPay',
    ];

    protected function fillSiswaData($siswa)
    {
        $this->ms_penempatan_siswa_id = $siswa->ms_penempatan_siswa_id;
        $this->ms_siswa_id = $siswa->ms_siswa_id;
        $this->ms_jenjang_id = $siswa->ms_jenjang_id;
        $this->ms_tahun_ajar_id = $siswa->ms_tahun_ajar_id;

        $this->nama_siswa = $siswa->ms_siswa->nama_siswa;
        $this->nama_jenjang = $siswa->ms_jenjang->nama_jenjang;
        $this->nama_tahun_ajar = $siswa->ms_tahun_ajar->nama_tahun_ajar;
        $this->nama_kelas = $siswa->ms_kelas->nama_kelas;
        $this->telepon = $siswa->ms_siswa->telepon;
        $this->educard = $siswa->ms_siswa->ms_educard?->kode_kartu;
        $this->deskripsi = $siswa->ms_siswa->deskripsi;
    }

    public function successTransaksiTabungan()
    {
        $this->updateSaldoTabungan();
    }

    public function refreshTagihanSiswa()
    {
        $this->updateTagihan();
    }

    public function successTransaksiEduPay()
    {
        $this->updateSaldoEduPay(); 
    }

    protected function updateSaldoTabungan()
    {
        $saldo = SaldoTabungan::getSaldo($this->ms_siswa_id, 'siswa');

        $this->saldoTabunganSiswa = $saldo->saldo_tabungan;
    }

    protected function updateSaldoEduPay()
    {
        $this->saldoEduPaySiswa = Siswa::find($this->ms_siswa_id)->saldo_edupay_siswa();
    }

    protected function updateTagihan()
    {
        if (!$this->ms_penempatan_siswa_id) return;

        $tagihans = TagihanSiswa::where('ms_penempatan_siswa_id', $this->ms_penempatan_siswa_id)
            ->withSum('dt_transaksi_tagihan_siswa as total_bayar', 'jumlah_bayar')
            ->get();

        $this->totalEstimasi = $tagihans->sum('jumlah_tagihan_siswa');

        $this->totalDibayarkan = $tagihans->sum(fn($t) => $t->total_bayar ?? 0);

        $this->totalKekurangan = $this->totalEstimasi - $this->totalDibayarkan;
    }

    public function siswaSelected($id)
    {
        $siswa = PenempatanSiswa::with('ms_siswa', 'ms_kelas', 'ms_jenjang', 'ms_tahun_ajar')
            ->findOrFail($id);

        $this->fillSiswaData($siswa);

        // 🔥 modular update
        $this->updateSaldoTabungan();
        $this->updateSaldoEdupay();
        $this->updateTagihan();
    }

    public function siswaUpdated()
    {
        if (!$this->ms_penempatan_siswa_id) return;

        $siswa = PenempatanSiswa::with([
            'ms_siswa.ms_educard',
            'ms_jenjang',
            'ms_tahun_ajar',
            'ms_kelas'
        ])->find($this->ms_penempatan_siswa_id);

        if (!$siswa) return;

        $this->fillSiswaData($siswa); // 🔥 hanya ini
    }

    public function render()
    {
        return view('livewire.transaksi-tagihan-siswa.data-siswa');
    }
}
