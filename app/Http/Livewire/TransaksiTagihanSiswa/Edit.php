<?php

namespace App\Http\Livewire\TransaksiTagihanSiswa;

use App\Models\AkuntansiJurnalDetail;
use App\Models\TransaksiTagihanSiswa;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $transaksi;

    public $tanggalTransaksi; // Tanggal transaksi yang akan diedit
    public $deskripsi;

    protected $listeners = [
        'loadHistoriTransaksi',
    ];

    public function loadHistoriTransaksi($ms_transaksi_tagihan_siswa_id)
    {
        // Ambil data transaksi
        $transaksi = TransaksiTagihanSiswa::find($ms_transaksi_tagihan_siswa_id);

        if (!$transaksi) {
            throw new \Exception('Transaksi tidak ditemukan!');
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi dimuat'
        ]);

        $this->transaksi = $transaksi;

        $this->tanggalTransaksi = Carbon::parse($transaksi->tanggal_transaksi)->format('Y-m-d');
        $this->deskripsi = $transaksi->deskripsi;
    }

    protected $rules = [
        'tanggalTransaksi' => 'required|date',
        'deskripsi' => 'nullable|string|max:255',
    ];

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

    protected function afterUpdateSuccess()
    {
        $this->emit('refreshTagihanSiswa');

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Transaksi berhasil diperbarui.'
        ]);

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'loadHistoriTransaksi'
        ]);
    }

    protected function processUpdateTransaksi()
    {
        $data = [
            'deskripsi' => $this->deskripsi,
        ];

        if ($this->tanggalTransaksi) {
            $old = Carbon::parse($this->transaksi->tanggal_transaksi);
            $new = Carbon::parse($this->tanggalTransaksi);

            // gabungkan tanggal baru + jam lama
            $newTanggal = $new->setTimeFrom($old);

            if (!$newTanggal->equalTo($old)) {

                $data['tanggal_transaksi'] = $newTanggal->format('Y-m-d H:i:s');

                AkuntansiJurnalDetail::whereIn('akuntansi_jurnal_detail_id', [
                    $this->transaksi->akuntansi_jurnal_detail_debit_id,
                    $this->transaksi->akuntansi_jurnal_detail_kredit_id,
                ])->update([
                    'tanggal_transaksi' => $data['tanggal_transaksi']
                ]);
            }
        }

        $this->transaksi->update($data);
    }

    public function updateTransaksi()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            if (!$this->transaksi) {
                throw new \Exception('Transaksi tidak ditemukan!');
            }

            // Ambil data transaksi
            $transaksi = TransaksiTagihanSiswa::lockForUpdate()
                ->find($this->transaksi->ms_transaksi_tagihan_siswa_id);

            if (!$transaksi) {
                throw new \Exception('Transaksi tidak ditemukan!');
            }
            
            $this->transaksi = $transaksi;

            DB::commit();

            $this->processUpdateTransaksi();

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
        return view('livewire.transaksi-tagihan-siswa.edit');
    }
}
