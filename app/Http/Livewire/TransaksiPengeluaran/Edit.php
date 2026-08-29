<?php

namespace App\Http\Livewire\TransaksiPengeluaran;

use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use App\Models\TransaksiPengeluaran;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $ms_jenjang_id = null;
    public $ms_tahun_ajar_id = null;

    public $transaksi;

    public $tanggal; // Tanggal transaksi yang akan diedit
    public $nominal; // Nominal transaksi yang akan diedit
    public $deskripsi;

    protected $listeners = [
        'editPengeluaran',
    ];

    public function editPengeluaran($transaksi_pengeluaran_id)
    {
        $transaksi = TransaksiPengeluaran::findOrFail($transaksi_pengeluaran_id);

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

        // UPDATE DESKRIPSI TRANSAKSI
        if (
            !empty($this->deskripsi) &&
            $this->deskripsi !== $this->transaksi->deskripsi
        ) {
            $data['deskripsi'] = $this->deskripsi;
        }

        // UPDATE TANGGAL TRANSAKSI
        if ($this->tanggal) {

            $old = Carbon::parse(
                $this->transaksi->tanggal
            );

            $new = Carbon::parse(
                $this->tanggal
            );

            // Gunakan jam transaksi lama
            $newTanggal = $new->setTimeFrom($old);

            if (!$newTanggal->equalTo($old)) {
                $data['tanggal'] = $newTanggal->format('Y-m-d H:i:s');
            }
        }

        // TIDAK ADA PERUBAHAN
        if (empty($data)) {
            return;
        }

        // UPDATE TRANSAKSI
        $this->transaksi->update($data);

        // UPDATE HEADER JURNAL
        if ($this->transaksi->akuntansi_jurnal_id) {

            $jurnalData = [];

            if (isset($data['deskripsi'])) {
                $jurnalData['deskripsi'] = $data['deskripsi'];
            }

            if (isset($data['tanggal'])) {
                $jurnalData['tanggal_transaksi'] = $data['tanggal'];
            }

            if (!empty($jurnalData)) {
                AkuntansiJurnal::where(
                    'akuntansi_jurnal_id',
                    $this->transaksi->akuntansi_jurnal_id
                )->update($jurnalData);
            }
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
            $transaksi = TransaksiPengeluaran::lockForUpdate()
                ->find($this->transaksi->transaksi_pengeluaran_id);

            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan.');
            }

            $this->transaksi = $transaksi;

            $this->processUpdateTransaksi();

            DB::commit();

            $this->transaksi->refresh();

            $this->deskripsi = '';

            $this->emit('refreshTransaksi');

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
