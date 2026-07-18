<?php

namespace App\Http\Livewire\TransaksiPengeluaran;

use App\Models\AkuntansiJurnalDetail;
use App\Models\Pengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $transaksi;

    public $tanggal; // Tanggal transaksi yang akan diedit
    public $nominal; // Tanggal transaksi yang akan diedit
    public $deskripsi;

    protected $listeners = [
        'editPengeluaran',
    ];

    public function editPengeluaran($ms_pengeluaran_id)
    {
        $transaksi = Pengeluaran::findOrFail($ms_pengeluaran_id);

        if (!$transaksi) {
            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Transaksi tidak ditemukan.']);
            return;
        }

        $this->ms_jenjang_id = $transaksi->ms_jenjang_id ?? null;
        $this->ms_tahun_ajar_id = $transaksi->ms_tahun_ajar_id ?? null;

        $this->transaksi = $transaksi;
        $this->tanggal = $transaksi->tanggal;
        $this->nominal = $transaksi->nominal;
        $this->tanggal = Carbon::parse($transaksi->tanggal)->format('Y-m-d');
        $this->deskripsi = $transaksi->deskripsi;
    }

    protected $rules = [
        'tanggal' => 'required|date',
        'deskripsi' => 'nullable|string|max:255',
    ];

    protected $messages = [
        'tanggal.required' => 'Tanggal tidak boleh kosong',
        'tanggal.date' => 'Format tanggal tidak valid',

        'deskripsi.string' => 'Deskripsi harus berupa teks',
        'deskripsi.max' => 'Deskripsi maksimal 255 karakter',
    ];

    protected function processUpdateTransaksi()
    {
        $data = [];

        // Deskripsi
        if (!empty($this->deskripsi) && $this->deskripsi !== $this->transaksi->deskripsi) {
            $data['deskripsi'] = $this->deskripsi;
        }

        // Tanggal (pertahankan jam lama)
        if ($this->tanggal) {
            $old = Carbon::parse($this->transaksi->tanggal);
            $new = Carbon::parse($this->tanggal);

            $newTanggal = $new->setTimeFrom($old);

            if (!$newTanggal->equalTo($old)) {
                $data['tanggal'] = $newTanggal->format('Y-m-d H:i:s');
            }
        }

        // Tidak ada perubahan
        if (empty($data)) {
            return;
        }

        $this->transaksi->update($data);

        // Sinkronkan jurnal
        $updateJurnal = [];

        if (isset($data['tanggal'])) {
            $updateJurnal['tanggal_transaksi'] = $data['tanggal'];
        }

        if (isset($data['deskripsi'])) {
            $updateJurnal['deskripsi'] = $data['deskripsi'];
        }

        if (!empty($updateJurnal)) {
            AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', [
                $this->transaksi->akuntansi_jurnal_detail_debit_id,
                $this->transaksi->akuntansi_jurnal_detail_kredit_id,
            ])->update($updateJurnal);
        }
    }

    public function updateTransaksi()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            if (!$this->transaksi) {
                throw new \Exception('Tidak ada data transaksi untuk diperbarui.');
            }

            // Ambil ulang data + lock
            $transaksi = Pengeluaran::lockForUpdate()
                ->find($this->transaksi->ms_pengeluaran_id);

            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan.');
            }

            $this->transaksi = $transaksi;

            $this->processUpdateTransaksi();

            DB::commit();

            $this->transaksi->refresh();

            $this->deskripsi = '';

            $this->emit('refreshTransaksiPengeluaran');

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Transaksi berhasil diperbarui.'
            ]);

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'editPengeluaran'
            ]);
        } catch (\Throwable $e) {

            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.transaksi-pengeluaran.edit');
    }
}
