<?php

namespace App\Http\Livewire\PPDB\Pendaftaran;

use App\Models\PPDBJawaban;
use App\Models\PPDBPendaftaran;
use App\Models\PPDBPertanyaan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Livewire\Component;

class PertanyaanTambahan extends Component
{
    public $pendaftaranId;
    public $pendaftaran;

    public $pertanyaan = [];
    public $jawaban = [];

    public $pernyataan = false;

    protected $listeners = [
        'refreshPertanyaanTambahan' => 'muatData',
    ];

    public function mount($pendaftaranId)
    {
        $this->pendaftaranId = $pendaftaranId;

        $this->muatData();
    }

    /**
     * Muat pendaftaran, pertanyaan aktif, dan jawaban tersimpan.
     */
    public function muatData()
    {
        $this->pendaftaran = PPDBPendaftaran::query()
            ->where('ms_pengguna_id', Auth::id())
            ->findOrFail($this->pendaftaranId);

        $this->pertanyaan = PPDBPertanyaan::query()
            ->where(
                'ppdb_periode_id',
                $this->pendaftaran->ppdb_periode_id
            )
            ->where('status', 'aktif')
            ->orderBy('urutan')
            ->get()
            ->map(function ($item) {
                $item->opsi_jawaban = is_array($item->opsi_jawaban)
                    ? $item->opsi_jawaban
                    : (json_decode($item->opsi_jawaban ?? '[]', true) ?: []);

                return $item;
            });

        $jawabanTersimpan = PPDBJawaban::query()
            ->where(
                'ppdb_pendaftaran_id',
                $this->pendaftaran->ppdb_pendaftaran_id
            )
            ->where(
                'ppdb_periode_id',
                $this->pendaftaran->ppdb_periode_id
            )
            ->get()
            ->keyBy('ppdb_pertanyaan_id');

        $this->jawaban = [];

        foreach ($this->pertanyaan as $item) {
            $tersimpan = $jawabanTersimpan->get(
                $item->ppdb_pertanyaan_id
            );

            $this->jawaban[$item->ppdb_pertanyaan_id] =
                $tersimpan ? $tersimpan->jawaban : '';
        }
    }

    /**
     * Pastikan pendaftaran masih bisa diedit.
     */
    protected function pastikanDraft()
    {
        $this->pendaftaran = PPDBPendaftaran::query()
            ->where('ms_pengguna_id', Auth::id())
            ->findOrFail($this->pendaftaranId);

        if ($this->pendaftaran->status !== 'draft') {
            $this->muatData();

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pendaftaran sudah dikirim dan tidak dapat diubah.',
            ]);

            return false;
        }

        return true;
    }

    /**
     * Aturan validasi dinamis berdasarkan jenis pertanyaan.
     */
    protected function aturanJawaban()
    {
        $rules = [];

        foreach ($this->pertanyaan as $item) {
            $id = $item->ppdb_pertanyaan_id;
            $field = 'jawaban.' . $id;

            $rules[$field] = [
                $item->wajib_diisi ? 'required' : 'nullable',
            ];

            switch ($item->jenis) {
                case 'teks':
                    $rules[$field][] = 'string';
                    $rules[$field][] = 'max:255';
                    break;

                case 'textarea':
                    $rules[$field][] = 'string';
                    $rules[$field][] = 'max:5000';
                    break;

                case 'angka':
                    $rules[$field][] = 'numeric';
                    break;

                case 'pilihan':
                    $rules[$field][] = 'string';
                    $rules[$field][] = Rule::in(
                        $item->opsi_jawaban
                    );
                    break;

                case 'ya_tidak':
                    $rules[$field][] = Rule::in([
                        'ya',
                        'tidak',
                    ]);
                    break;
            }
        }

        return $rules;
    }

    protected $messages = [
        'jawaban.*.required' =>
            'Pertanyaan ini wajib dijawab.',

        'jawaban.*.string' =>
            'Jawaban harus berupa teks.',

        'jawaban.*.numeric' =>
            'Jawaban harus berupa angka.',

        'jawaban.*.max' =>
            'Jawaban melebihi batas karakter yang diizinkan.',

        'jawaban.*.in' =>
            'Pilihan jawaban tidak valid.',

        'pernyataan.accepted' => 'Anda harus menyetujui pernyataan kebenaran data.',
    ];

    /**
     * Validasi jawaban sebelum menampilkan konfirmasi.
     */
    public function bukaKonfirmasi()
    {
        if (!$this->pastikanDraft()) {
            return;
        }

        $this->validate(array_merge(
            $this->aturanJawaban(),
            [
                'pernyataan' => ['accepted'],
            ]
        ));

        $this->dispatchBrowserEvent('show-modal', [
            'modalId' => 'ModalKonfirmasiKirim',
        ]);
    }

    /**
     * Simpan jawaban dan kirim pendaftaran.
     */
    public function kirimPendaftaran()
    {
        if (!$this->pastikanDraft()) {
            return;
        }

        $this->validate(array_merge(
            $this->aturanJawaban(),
            [
                'pernyataan' => ['accepted'],
            ]
        ));

        DB::beginTransaction();

        try {
            // Kunci pendaftaran agar status diperiksa kembali.
            $pendaftaran = PPDBPendaftaran::query()
                ->where('ms_pengguna_id', Auth::id())
                ->where(
                    'ppdb_pendaftaran_id',
                    $this->pendaftaranId
                )
                ->lockForUpdate()
                ->firstOrFail();

            if ($pendaftaran->status !== 'draft') {
                DB::rollBack();

                $this->muatData();

                $this->dispatchBrowserEvent('alertify-error', [
                    'message' =>
                        'Pendaftaran sudah dikirim sebelumnya.',
                ]);

                return;
            }

            // Ambil ulang pertanyaan aktif dari database.
            $pertanyaanAktif = PPDBPertanyaan::query()
                ->where(
                    'ppdb_periode_id',
                    $pendaftaran->ppdb_periode_id
                )
                ->where('status', 'aktif')
                ->orderBy('urutan')
                ->get();

            // Validasi ulang agar tidak hanya bergantung
            // pada data pertanyaan saat halaman pertama dibuka.
            $this->pertanyaan = $pertanyaanAktif
                ->map(function ($item) {
                    $item->opsi_jawaban = is_array(
                        $item->opsi_jawaban
                    )
                        ? $item->opsi_jawaban
                        : (
                            json_decode(
                                $item->opsi_jawaban ?? '[]',
                                true
                            ) ?: []
                        );

                    return $item;
                })
                ->all();

            $this->validate(array_merge(
                $this->aturanJawaban(),
                [
                    'pernyataan' => ['accepted'],
                ]
            ));

            foreach ($this->pertanyaan as $item) {
                $id = $item->ppdb_pertanyaan_id;
                $nilai = $this->jawaban[$id] ?? null;

                if (is_string($nilai)) {
                    $nilai = trim($nilai);
                }

                // Jawaban opsional kosong tidak perlu disimpan.
                if ($nilai === '') {
                    $nilai = null;
                }

                $kondisi = [
                    'ppdb_periode_id' =>  $pendaftaran->ppdb_periode_id,
                    'ppdb_pendaftaran_id' =>  $pendaftaran->ppdb_pendaftaran_id,
                    'ppdb_pertanyaan_id' => $id,
                ];

                if ($nilai === null && !$item->wajib_diisi) {
                    PPDBJawaban::query()
                        ->where($kondisi)
                        ->delete();

                    continue;
                }

                PPDBJawaban::updateOrCreate(
                    $kondisi,
                    ['jawaban' => $nilai]
                );
            }

            $pendaftaran->status = 'pengecekan';
            $pendaftaran->submitted_at = now();
            $pendaftaran->current_step = 7;
            $pendaftaran->save();

            DB::commit();

            $this->dispatchBrowserEvent('hide-modal', [
                'modalId' => 'ModalKonfirmasiKirim',
            ]);

            $this->dispatchBrowserEvent('alertify-success', [
                'message' => 'Pendaftaran berhasil dikirim.',
            ]);

            $this->muatData();

            // Muat ulang halaman agar status dan ringkasan
            // mengikuti data terbaru.
            return redirect()->route('ppdb.pendaftaran.status');
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();

            throw $e;
        } catch (\Throwable $e) {
            if (DB::transactionLevel() > 0) {
                DB::rollBack();
            }

            Log::error('Gagal mengirim pendaftaran PPDB', [
                'pendaftaran_id' => $this->pendaftaranId,
                'user_id' => Auth::id(),
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            report($e);

            $this->dispatchBrowserEvent('alertify-error', [
                'message' => 'Pendaftaran gagal dikirim. Silakan coba kembali.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.p-p-d-b.pendaftaran.pertanyaan-tambahan');
    }
}
