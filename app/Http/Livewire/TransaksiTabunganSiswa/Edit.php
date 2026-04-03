<?php

namespace App\Http\Livewire\TransaksiTabunganSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\TransaksiTabungan;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $transaksi;
    
    public $tanggal;
    public $deskripsi;

    protected $listeners = [
        'loadTransaksiTabungan',
    ];

    public function loadTransaksiTabungan($id)
    {
        $transaksi = TransaksiTabungan::find($id);

        if (!$transaksi) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Transaksi tidak ditemukan!'
            ]);

            return;
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

        if ($this->tanggal) {
            $old = Carbon::parse($this->transaksi->tanggal);
            $new = Carbon::parse($this->tanggal);

            // gabungkan tanggal baru + jam lama
            $newTanggal = $new->setTimeFrom($old);

            if (!$newTanggal->equalTo($old)) {

                $data['tanggal'] = $newTanggal->format('Y-m-d H:i:s');

                AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', [
                    $this->transaksi->akuntansi_jurnal_detail_debit_id,
                    $this->transaksi->akuntansi_jurnal_detail_kredit_id,
                ])->update([
                    'tanggal_transaksi' => $data['tanggal']
                ]);
            }
        }

        $this->transaksi->update($data);
    }

    protected function afterUpdateSuccess()
    {
        $this->emit('successTransaksiTabungan');

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi berhasil diperbarui.'
        ]);

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'loadTransaksiTabungan'
        ]);
    }

    public function updateTransaksi()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            if (!$this->transaksi) {
                $this->dispatchBrowserEvent('alertify-error', [
                    'message' => 'Transaksi tidak ditemukan!'
                ]);
                return;
            }

            $this->processUpdateTransaksi();

            DB::commit();

            $this->transaksi->refresh();
            $this->afterUpdateSuccess();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Terjadi kesalahan sistem'
            ]);
        }
    }
    
    public function render()
    {
        return view('livewire.transaksi-tabungan-siswa.edit');
    }
}
