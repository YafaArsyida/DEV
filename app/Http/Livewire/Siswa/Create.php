<?php

namespace App\Http\Livewire\Siswa;

use Livewire\Component;
use App\Models\Siswa as SiswaModel;
use App\Models\Kelas as KelasModel;
use App\Models\PenempatanSiswa as PenempatanSiswaModel;

use Illuminate\Validation\ValidationException;

use App\Http\Controllers\HelperController;
use App\Models\EduCard;
use App\Models\Jenjang;
use App\Models\TahunAjar;
use Illuminate\Support\Facades\DB;

class Create extends Component
{
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

    public $nama_jenjang;
    public $nama_tahun_ajar;
    public $selectKelas = [];

    protected $listeners = [
        'showCreateSiswa',
    ];

    public function showCreateSiswa($jenjang, $tahunAjar)
    {
        $this->reset(['form']);
        $this->resetErrorBag();
        $this->resetValidation();

        $this->form['ms_jenjang_id'] = $jenjang;
        $this->form['ms_tahun_ajar_id'] = $tahunAjar;

        $this->nama_jenjang = Jenjang::whereKey($jenjang)->value('nama_jenjang');
        $this->nama_tahun_ajar = TahunAjar::whereKey($tahunAjar)->value('nama_tahun_ajar');

        $this->loadKelas();
    }

    protected function rules()
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

    protected function createDataSiswa()
    {
        return SiswaModel::create([
            'nama_siswa' => $this->form['nama_siswa'],
            'nisn' => $this->form['nisn'] ?: null,
            'tempat_lahir' => $this->form['tempat_lahir'],
            'tanggal_lahir' => $this->form['tanggal_lahir'],
            'jenis_kelamin' => $this->form['jenis_kelamin'],
            'alamat' => $this->form['alamat'],
            'nama_ayah' => $this->form['nama_ayah'],
            'nama_ibu' => $this->form['nama_ibu'],
            'telepon' => HelperController::normalizePhoneNumber($this->form['telepon']),
            'deskripsi' => $this->form['deskripsi'],
        ]);
    }

    protected function createPenempatan($siswaId)
    {
        return PenempatanSiswaModel::create([
            'ms_siswa_id' => $siswaId,
            'ms_kelas_id' => $this->form['ms_kelas_id'],
            'ms_tahun_ajar_id' => $this->form['ms_tahun_ajar_id'],
            'ms_jenjang_id' => $this->form['ms_jenjang_id'],
            'ms_pengguna_id' => auth()->id(),
        ]);
    }

    protected function syncEduCard($siswaId)
    {
        if ($this->form['educard']) {
            EduCard::updateOrCreate(
                ['ms_siswa_id' => $siswaId],
                [
                    'ms_pengguna_id' => auth()->id(),
                    'kode_kartu' => $this->form['educard'],
                    'jenis_pemilik' => 'siswa',
                    'status_kartu' => 'aktif',
                    'deskripsi' => 'EduCard ' . $this->form['nama_siswa'],
                ]
            );
        }
    }

    protected function afterCreateSuccess()
    {
        $this->dispatchBrowserEvent('alertify-success', [
            'message' => 'Berhasil menambah siswa!'
        ]);

        $this->dispatchBrowserEvent('hide-modal', [
            'modalId' => 'ModalCreateSiswa'
        ]);

        $this->emit('refreshSiswas');
    }

    public function save()
    {
        DB::beginTransaction();

        try {
            $this->validate();

            $siswa = $this->createDataSiswa();
            $this->createPenempatan($siswa->ms_siswa_id);
            $this->syncEduCard($siswa->ms_siswa_id);

            DB::commit();

            $this->afterCreateSuccess();
        } catch (ValidationException $e) {
            DB::rollBack();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Validasi gagal, cek input'
            ]);

            throw $e; // 🔥 INI KUNCI

        } catch (\Throwable $e) {
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
        return view('livewire.siswa.create');
    }
}
