<?php

namespace App\Http\Livewire\Siswa;

use Livewire\Component;
use App\Models\Kelas as KelasModel;
use App\Models\PenempatanSiswa as PenempatanSiswaModel;

use Illuminate\Validation\ValidationException;

use App\Http\Controllers\HelperController;
use App\Models\EduCard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Edit extends Component
{
    public $siswa;

    public $created_at_formatted;

    public $selectKelas = [];

    public $form = [
        'nama_siswa' => null,
        'nisn' => null,
        'tempat_lahir' => null,
        'tanggal_lahir' => null,
        'jenis_kelamin' => null,
        'alamat' => null,
        'nama_ayah' => null,
        'nama_ibu' => null,
        'telepon' => null,
        'deskripsi' => null,

        'ms_kelas_id' => null,
        'ms_jenjang_id' => null,
        'ms_tahun_ajar_id' => null,

        'educard' => null,
    ];

    protected $listeners = ['loadDataSiswa'];

    public function loadDataSiswa($id)
    {
        $siswa = PenempatanSiswaModel::with([
            'ms_siswa.ms_educard',
            'ms_jenjang',
            'ms_tahun_ajar',
            'ms_kelas',
            'ms_pengguna'
        ])->find($id);

        if (!$siswa) {
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Siswa tidak ditemukan!'
            ]);

            return;
        }

        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Siswa dimuat'
        ]);

        $this->siswa = $siswa;

        $this->created_at_formatted = HelperController::formatTanggalIndonesia(
            $this->siswa->ms_siswa->created_at,
            'd F Y H:i'
        );

        // 🔥 INI WAJIB
        $this->resetErrorBag();
        $this->resetValidation();

        $this->form = [
            'nama_siswa'   => $siswa->ms_siswa->nama_siswa,
            'nisn'         => $siswa->ms_siswa->nisn,
            'tempat_lahir' => $siswa->ms_siswa->tempat_lahir,
            'tanggal_lahir'=> $siswa->ms_siswa->tanggal_lahir,
            'jenis_kelamin'=> $siswa->ms_siswa->jenis_kelamin,
            'alamat'       => $siswa->ms_siswa->alamat,
            'nama_ayah'    => $siswa->ms_siswa->nama_ayah,
            'nama_ibu'     => $siswa->ms_siswa->nama_ibu,
            'telepon'      => HelperController::normalizePhoneNumber($siswa->ms_siswa->telepon),
            'deskripsi'    => $siswa->ms_siswa->deskripsi,

            'ms_kelas_id'       => $siswa->ms_kelas_id,
            'ms_jenjang_id'     => $siswa->ms_jenjang_id,
            'ms_tahun_ajar_id'  => $siswa->ms_tahun_ajar_id,

            'educard' => $siswa->ms_siswa->ms_educard?->kode_kartu,
        ];

        $this->loadKelas();
    }

    public function rules()
    {
        return [
            'form.nama_siswa' => 'required|string|max:255',
            'form.telepon' => 'required|string|max:20',
            'form.ms_kelas_id' => 'required|exists:ms_kelas,ms_kelas_id',
            'form.ms_jenjang_id' => 'required|exists:ms_jenjang,ms_jenjang_id',
            'form.ms_tahun_ajar_id' => 'required|exists:ms_tahun_ajar,ms_tahun_ajar_id',
            'form.deskripsi' => 'nullable|string',
        ];
    }

    protected $messages = [
        'form.nama_siswa.required' => 'Nama siswa tidak boleh kosong',
        'form.nama_siswa.string' => 'Nama siswa harus berupa teks',
        'form.nama_siswa.max' => 'Nama siswa maksimal 255 karakter',

        'form.telepon.required' => 'Telepon tidak boleh kosong',    
        'form.telepon.string' => 'Telepon harus berupa teks',
        'form.telepon.max' => 'Telepon maksimal 20 karakter',

        'form.ms_kelas_id.required' => 'Kelas tidak boleh kosong',
        'forn.ms_kelas_id.exists' => 'Kelas tidak valid',

        'form.ms_jenjang_id.required' => 'Jenjang tidak boleh kosong',
        'form.ms_jenjang_id.exists' => 'Jenjang tidak valid',

        'form.ms_tahun_ajar_id.required' => 'Tahun ajar tidak boleh kosong',
        'form.ms_tahun_ajar_id.exists' => 'Tahun ajar tidak valid',
    ];

    public function updated($field)
    {
        if (str_starts_with($field, 'form.')) {
            $this->validateOnly($field);
        }
    }

    protected function updateDataSiswa()
    {
        $this->siswa->ms_siswa->update([
            'nama_siswa'     => $this->form['nama_siswa'],
            'nisn'           => $this->form['nisn'] ?: null,
            'tempat_lahir'   => $this->form['tempat_lahir'],
            'tanggal_lahir'  => $this->form['tanggal_lahir'],
            'jenis_kelamin'  => $this->form['jenis_kelamin'],
            'alamat'         => $this->form['alamat'],
            'nama_ayah'      => $this->form['nama_ayah'],
            'nama_ibu'       => $this->form['nama_ibu'],
            'telepon'        => HelperController::normalizePhoneNumber($this->form['telepon']),
            'deskripsi'      => $this->form['deskripsi'],
        ]);
    }

    protected function updatePenempatan()
    {
        $this->siswa->update([
            'ms_kelas_id'       => $this->form['ms_kelas_id'],
            'ms_jenjang_id'     => $this->form['ms_jenjang_id'],
            'ms_tahun_ajar_id'  => $this->form['ms_tahun_ajar_id'],
            'ms_pengguna_id'    => Auth::id(),
        ]);
    }

    protected function syncEduCard()
    {
        if ($this->form['educard']) {
            EduCard::updateOrCreate(
                ['ms_siswa_id' => $this->siswa->ms_siswa_id],
                [
                    'ms_pengguna_id' => Auth::id(),
                    'kode_kartu' => $this->form['educard'],
                    'jenis_pemilik' => 'siswa',
                    'status_kartu' => 'aktif',
                    'deskripsi' => 'EduCard ' . $this->form['nama_siswa'],
                ]
            );
        } else {
            EduCard::where('ms_siswa_id', $this->siswa->ms_siswa_id)->delete();
        }
    }

    protected function afterUpdateSuccess()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Berhasil mengubah siswa!'
        ]);

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalEditSiswa'
        ]);

        // 🔥 jangan reload semua
        $this->emit('refreshSiswas');
    }

    public function updateSiswa()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            $this->updateDataSiswa();
            $this->updatePenempatan();
            $this->syncEduCard();

            DB::commit();

            $this->siswa->refresh();
            $this->afterUpdateSuccess();
        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Validasi gagal, cek input'
            ]);

            throw $e; // 🔥 INI KUNCI

        }catch (\Throwable $e) {
            DB::rollBack();
            $this->dispatchBrowserEvent('alertify-error', [
                'message' => $e->getMessage() ?? 'Terjadi kesalahan sistem'
            ]);
        }
    }

    public function loadKelas()
    {
        if (!$this->form['ms_jenjang_id'] || !$this->form['ms_tahun_ajar_id']) {
            $this->selectKelas = [];
            return;
        }

        $this->selectKelas = KelasModel::where('ms_jenjang_id', $this->form['ms_jenjang_id'])
            ->where('ms_tahun_ajar_id', $this->form['ms_tahun_ajar_id'])
            ->get();
    }

    public function render()
    {
        return view('livewire.siswa.edit');
    }
}
