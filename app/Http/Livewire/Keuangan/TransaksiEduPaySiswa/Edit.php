<?php

namespace App\Http\Livewire\Keuangan\TransaksiEduPaySiswa;

use App\Models\AkuntansiJurnal;
use App\Models\AkuntansiJurnalDetail;
use App\Models\TransaksiEduPay;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $transaksi;

    public $tanggal;
    public $deskripsi;

    protected $listeners = [
        'loadTransaksiEduPay',
    ];

    public function loadTransaksiEduPay($id)
    {
        $transaksi = TransaksiEduPay::find($id);

        if (!$transaksi) {
            throw new \Exception('Transaksi tidak ditemukan!');
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi dimuat'
        ]);

        // 🔥 INI WAJIB
        $this->resetErrorBag();
        $this->resetValidation();

        $this->transaksi = $transaksi;

        $this->tanggal = Carbon::parse($transaksi->tanggal)->format('Y-m-d');
        $this->deskripsi = $transaksi->deskripsi;
    }

    public function rules()
    {
        return [
            'tanggal' => 'required|date',
            'deskripsi' => 'nullable|string|max:255',
        ];
    }

    protected $messages = [
        'tanggal.required' => 'Tanggal tidak boleh kosong',
        'tanggal.date' => 'Format tanggal tidak valid',

        'deskripsi.string' => 'Deskripsi harus berupa teks',
        'deskripsi.max' => 'Deskripsi maksimal 255 karakter',
    ];

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    protected function processUpdateTransaksi()
    {
        $data = [
            'deskripsi' => $this->deskripsi,
        ];

        $tanggalJurnal = null;

        if ($this->tanggal) {

            $old = Carbon::parse(
                $this->transaksi->tanggal
            );

            $new = Carbon::parse(
                $this->tanggal
            );

            // Gabungkan tanggal baru + jam lama
            $newTanggal = $new->setTimeFrom($old);

            if (!$newTanggal->equalTo($old)) {
                $tanggalJurnal = $newTanggal->format('Y-m-d H:i:s');

                $data['tanggal'] = $tanggalJurnal;
            }
        }

        // Update transaksi EduPay
        $this->transaksi->update($data);

        // Update tanggal jurnal saja
        if ($this->transaksi->akuntansi_jurnal_id && $tanggalJurnal) 
        {
            AkuntansiJurnal::where('akuntansi_jurnal_id', $this->transaksi->akuntansi_jurnal_id)
            ->update([
                'tanggal_transaksi' => $tanggalJurnal,
            ]);
        }
    }
    
    protected function afterUpdateSuccess()
    {
        $this->emit('successTransaksiEduPay');

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi berhasil diperbarui.'
        ]);

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'loadTransaksiEduPay'
        ]);
    }

    public function updateTransaksi()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            if (!$this->transaksi) {
                throw new \Exception('Transaksi tidak ditemukan!');
            }

            // 🔥 ambil ulang + lock (INI KUNCI)
            $transaksi = TransaksiEduPay::lockForUpdate()
                ->find($this->transaksi->ms_transaksi_edupay_id);

            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan!');
            }

            // 🔥 inject ulang ke property biar konsisten
            $this->transaksi = $transaksi;

            $this->processUpdateTransaksi();

            DB::commit();

            $this->transaksi->refresh();
            $this->afterUpdateSuccess();
        } catch (\Throwable $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.keuangan.transaksi-edu-pay-siswa.edit');
    }
}
