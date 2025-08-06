<?php

namespace App\Http\Livewire\Widget;

use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use App\Models\Pengeluaran;
use Livewire\Component;

class KartuTransaksiPengeluaran extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedRekening = null;

    public $nominal, $kode_rekening, $metode_pembayaran = 'tunai', $deskripsi;

    // public $totalPengeluaran;

    protected $listeners = [
        'parameterUpdated',
        'refreshTransaksiPengeluaran',
    ];

    public function parameterUpdated($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function refreshTransaksiPengeluaran()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function simpanPengeluaran()
    {
        try {
            // Validasi input untuk topup
            $validatedData = $this->validate([
                'kode_rekening' => 'required',
                'nominal' => 'required|numeric|min:1000',
                'deskripsi' => 'nullable|string|max:255',
                'metode_pembayaran' => 'required',
            ], [
                'kode_rekening.required' => 'Jenis Transaksi harus dipilih.',
                'nominal.required' => 'Nominal harus diisi.',
                'nominal.numeric' => 'Nominal harus berupa angka.',
                'nominal.min' => 'Nominal harus minimal 1000.',
                'deskripsi.max' => 'Deskripsi tidak boleh lebih dari 255 karakter.',
                'metode_pembayaran.required' => 'Metode Pembayaran harus dipilih.',
            ]);

            $kode_rekening_kas = 11001;
            $kode_rekening_bank = 11002;

            if ($this->metode_pembayaran === 'bank') {
                $kode_rekening_kredit = $kode_rekening_bank;
            } else {
                $kode_rekening_kredit = $kode_rekening_kas;
            }

            $deskripsi = "Pengeluaran Operasional Rp {$this->nominal}, " . $this->deskripsi;

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $this->kode_rekening,
                'posisi' => 'debit',
                'nominal' => $this->nominal,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->selectedTahunAjar,
                'ms_jenjang_id' => $this->selectedJenjang,
                'deskripsi' => $deskripsi,
            ];
            $jurnalDebitId = AkuntansiJurnalDetail::create($jurnalDebit)->akuntansi_jurnal_detail_id;

            // Data untuk jurnal kredit
            $jurnalKredit = [
                'kode_rekening' => $kode_rekening_kredit,
                'posisi' => 'kredit',
                'nominal' => $this->nominal,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->selectedTahunAjar,
                'ms_jenjang_id' => $this->selectedJenjang,
                'deskripsi' => $deskripsi,
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            Pengeluaran::create([
                'ms_pengguna_id' => auth()->id(),
                'ms_jenjang_id' => $this->selectedJenjang,
                'ms_tahun_ajar_id' => $this->selectedTahunAjar,
                'kode_rekening' => $this->kode_rekening, // Jenis transaksi untuk top-up
                'nominal' => $this->nominal,
                'metode_pembayaran' => $this->metode_pembayaran,
                'tanggal' => now(),
                'deskripsi' => $deskripsi,
                'akuntansi_jurnal_detail_debit_id' => $jurnalDebitId,
                'akuntansi_jurnal_detail_kredit_id' => $jurnalKreditId,
            ]);

            // Reset input
            $this->reset(['nominal', 'deskripsi']);
            $this->emitSelf('$refresh'); //ringan
            $this->emit('refreshJurnalHariIni');

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi berhasil disimpan.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $select_pengeluaran = [];
        $select_pengeluaran = AkuntansiRekening::where('akuntansi_kelompok_rekening_id', 5)
            ->where('tipe_akun', 'Beban Operasional')
            ->orderBy('kode_rekening', 'ASC')
            ->get();

        $totalPengeluaran = AkuntansiJurnalDetail::with('akuntansi_rekening')
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->where('ms_tahun_ajaran_id', $this->selectedTahunAjar)
            ->where('posisi', 'debit')
            ->whereHas('akuntansi_rekening', function ($query) {
                $query->where('kode_rekening', 'like', '5%');
            })
            ->sum('nominal');

        return view('livewire.widget.kartu-transaksi-pengeluaran', [
            'select_pengeluaran' => $select_pengeluaran,
            'totalPengeluaran' => $totalPengeluaran,
        ]);
    }
}
