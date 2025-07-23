<?php

namespace App\Http\Livewire\TransaksiPendapatanLainnya;

use App\Models\AkuntansiJurnalDetail;
use App\Models\AkuntansiRekening;
use App\Models\PendapatanLainnya;
use Livewire\Component;

class Index extends Component
{
    public $selectedJenjang = null;
    public $selectedTahunAjar = null;
    public $selectedRekening = null;
    public $startDate = null;
    public $endDate = null;

    public $search;

    public $nominal, $kode_rekening, $metode_pembayaran = 'tunai', $deskripsi;

    public $totalPendapatanLainnya;

    protected $listeners = [
        'parameterUpdated',
        'refreshTransaksiPendapatanLainnya'
    ];

    public function parameterUpdated($jenjang, $tahunAjar)
    {
        // Update nilai selectedJenjang dan selectedTahunAjar
        $this->selectedJenjang = $jenjang;
        $this->selectedTahunAjar = $tahunAjar;
    }

    public function refreshTransaksiPendapatanLainnya()
    {
        $this->emitSelf('$refresh'); //ringan
    }

    public function simpanTransaksi()
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
                $kode_rekening_debit = $kode_rekening_bank;
            } else {
                $kode_rekening_debit = $kode_rekening_kas;
            }

            $deskripsi = "Pendapatan Lainnya Rp {$this->nominal}, " . $this->deskripsi;

            // Data untuk jurnal debit
            $jurnalDebit = [
                'kode_rekening' => $kode_rekening_debit,
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
                'kode_rekening' => $this->kode_rekening,
                'posisi' => 'kredit',
                'nominal' => $this->nominal,
                'tanggal_transaksi' => now(),
                'ms_pengguna_id' => auth()->id(),
                'ms_tahun_ajaran_id' => $this->selectedTahunAjar,
                'ms_jenjang_id' => $this->selectedJenjang,
                'deskripsi' => $deskripsi,
            ];
            $jurnalKreditId = AkuntansiJurnalDetail::create($jurnalKredit)->akuntansi_jurnal_detail_id;

            PendapatanLainnya::create([
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
            $this->emit('refreshSaldo');

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Transaksi berhasil disimpan.']);
        } catch (\Exception $e) {
            // Notifikasi error
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function cetakLaporan()
    {
        if (!$this->selectedJenjang || !$this->selectedTahunAjar) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Jenjang dan Tahun Ajar wajib dipilih']);
            return;
        }

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Laporan diproses.']);

        $url = route('transaksi.pendapatan-lainnya.pdf', [
            'jenjang' => $this->selectedJenjang,
            'tahun' => $this->selectedTahunAjar,
            'rekening' => $this->selectedRekening,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'search' => $this->search,
        ]);

        $this->emit('openNewTab', $url);
    }

    public function render()
    {
        $select_transaksi = [];
        $select_transaksi = AkuntansiRekening::where('akuntansi_kelompok_rekening_id', 4)
            ->where('tipe_akun', 'Pendapatan Lainnya')
            ->orderBy('kode_rekening', 'ASC')
            ->get();

        $query = PendapatanLainnya::query()
            ->where('ms_tahun_ajar_id', $this->selectedTahunAjar)
            ->where('ms_jenjang_id', $this->selectedJenjang)
            ->when($this->startDate && $this->endDate, function ($query) {
                $query->whereBetween('tanggal', [$this->startDate, $this->endDate]);
            });

        if (!empty($this->selectedRekening)) {
            $query->where('kode_rekening', $this->selectedRekening);
        }

        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('deskripsi', 'like', '%' . $this->search . '%')
                    ->orWhereHas('akuntansi_rekening', function ($qr) {
                        $qr->where('nama_rekening', 'like', '%' . $this->search . '%');
                    });
            });
        }

        $query->orderBy('tanggal', 'ASC');

        $data = $query->get();

        $this->totalPendapatanLainnya = (clone $query)->sum('nominal');

        $this->dispatchBrowserEvent('alertify-success', ['message' => 'Memperbarui..']);

        return view('livewire.transaksi-pendapatan-lainnya.index', [
            'data' => $data,
            'select_transaksi' => $select_transaksi,
        ]);
    }
}
