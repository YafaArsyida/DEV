<?php

namespace App\Http\Livewire\Pegawai;

use App\Http\Controllers\HelperController;
use App\Models\EduCard;
use App\Models\Jabatan;
use App\Models\Jenjang;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\Pegawai;

class Edit extends Component
{
    public $ms_pegawai_id, $ms_jabatan_id, $nama_pegawai, $nip, $email, $telepon, $alamat, $deskripsi;
    public $created_at;
    public $nama_petugas;
    public $educard;
    public $edupay;

    public $nama_jenjang; // Tambahkan variabel untuk menyimpan jenjang
    public $selectedJenjang; // Tambahkan variabel untuk menyimpan jenjang

    protected $listeners = [
        'loadDataPegawai',
    ];

    public function loadDataPegawai($ms_pegawai_id)
    {
        $pegawai = Pegawai::findOrFail($ms_pegawai_id);

        $this->selectedJenjang = $pegawai->ms_jenjang_id;
        $this->nama_jenjang = Jenjang::where('ms_jenjang_id', $pegawai->ms_jenjang_id)->value('nama_jenjang');


        $this->ms_pegawai_id = $pegawai->ms_pegawai_id;
        $this->ms_jabatan_id = $pegawai->ms_jabatan_id;

        $this->nama_petugas = $pegawai->ms_pengguna->nama;
        $this->nama_pegawai = $pegawai->nama_pegawai;
        $this->email = $pegawai->email;
        $this->nip = $pegawai->nip;
        $this->telepon = $pegawai->telepon;
        $this->alamat = $pegawai->alamat;
        $this->deskripsi = $pegawai->deskripsi;
        $this->educard = $pegawai->ms_educard ? $pegawai->ms_educard->kode_kartu : null;
        // $this->edupay = $pegawai->ms_pegawai->saldo_edupay();
        $this->created_at = HelperController::formatTanggalIndonesia($pegawai->created_at, 'd F Y H:i');
    }

    protected function rules()
    {
        return [
            'nama_pegawai' => 'required|string|max:255',
            'telepon' => 'required|string|max:20',
            'email' => 'required|string|max:20',
            'ms_jabatan_id' => 'required|exists:ms_jabatan,ms_jabatan_id',
            'deskripsi' => 'nullable|string',
            'nip' => 'nullable|string',
        ];
    }

    protected $messages = [
        'nama_pegawai.required' => 'Nama pegawai tidak boleh kosong',
        'nama_pegawai.string' => 'Nama pegawai harus berupa teks',
        'nama_pegawai.max' => 'Nama pegawai maksimal 255 karakter',

        'telepon.required' => 'Telepon tidak boleh kosong',
        'telepon.string' => 'Telepon harus berupa teks',
        'telepon.max' => 'Telepon maksimal 20 karakter',

        'ms_jabatan_id.required' => 'Kelas tidak boleh kosong',
        'ms_jabatan_id.exists' => 'Kelas tidak valid',
    ];

    public function updated($fields)
    {
        $this->validateOnly($fields);
    }

    public function updatePegawai()
    {
        $validatedData = $this->validate();

        try {
            // Ambil data jenis tagihan berdasarkan ID dan update
            $ms_pengguna_id = Auth::id();
            $pegawai = Pegawai::findOrFail($this->ms_pegawai_id);
            $pegawai->update([
                'ms_jabatan_id' => $this->ms_jabatan_id,
                'ms_pengguna_id' => $ms_pengguna_id,
                'nama_pegawai' => $this->nama_pegawai,
                'nip' => $this->nip,
                'email' => $this->email,
                'telepon' => $this->telepon,
                'alamat' => $this->alamat,
                'deskripsi' => $this->deskripsi
            ]);

            // Logika untuk menangani kolom educard
            if (!empty($this->educard)) {
                // Insert or Update data di tabel ms_educard
                EduCard::updateOrCreate(
                    ['ms_pegawai_id' => $this->ms_pegawai_id], // Kondisi untuk cek apakah data sudah ada
                    [
                        'ms_pengguna_id' => $ms_pengguna_id,
                        'kode_kartu' => $this->educard, // Input dari form
                        'jenis_pemilik' => 'pegawai', // Disesuaikan dengan jenis pemilik
                        'status_kartu' => 'aktif', // Status default
                        'deskripsi' => 'EduCard ' . $this->nama_pegawai, // Bisa diubah sesuai kebutuhan
                    ]
                );
            } else {
                // Hapus data di tabel ms_educard jika kolom educard dikosongkan
                EduCard::where('ms_pegawai_id', $this->ms_pegawai_id)->delete();
            }

            DB::commit();

            $this->dispatchBrowserEvent('alertify-success', ['message' => 'Berhasil memperbarui data pegawai!']);
            $this->dispatchBrowserEvent('hide-edit-modal', ['modalId' => 'ModalEditPegawai']);
            $this->emit('refreshPegawais');
            $this->emit('refreshJabatans');
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', ['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        $select_jabatan = Jabatan::get();
        return view('livewire.pegawai.edit', [
            'select_jabatan' => $select_jabatan,
        ]);
    }
}
