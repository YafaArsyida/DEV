
<div>
    @if ($pendaftaran->status === 'draft')
        <div class="card border rounded-4 shadow-none mt-4">
            <div class="card-header bg-transparent">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-questionnaire-line"></i>
                        </div>
                    </div>

                    <div>
                        <h6 class="mb-1 fw-semibold">
                            Informasi Tambahan
                        </h6>

                        <p class="text-muted small mb-0">
                            Lengkapi informasi tambahan berikut
                            sebelum mengirim pendaftaran.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-body">
                @if (count($pertanyaan))
                    <div class="row g-4">
                        @foreach ($pertanyaan as $item)
                            <div class="col-12"
                                wire:key="pertanyaan-{{ $item->ppdb_pertanyaan_id }}">

                                <label
                                    for="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                    class="form-label fw-medium">

                                    {{ $item->pertanyaan }}

                                    @if ($item->wajib_diisi)
                                        <span class="text-danger">*</span>
                                    @else
                                        <span class="text-muted small">
                                            (Opsional)
                                        </span>
                                    @endif
                                </label>

                                @switch($item->jenis)
                                    @case('teks')
                                        <input
                                            type="text"
                                            id="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                            class="form-control rounded-3 @error('jawaban.' . $item->ppdb_pertanyaan_id) is-invalid @enderror"
                                            wire:model.defer="jawaban.{{ $item->ppdb_pertanyaan_id }}"
                                            placeholder="Masukkan jawaban"
                                            @if ($item->wajib_diisi) required @endif
                                        >
                                        @break

                                    @case('textarea')
                                        <textarea
                                            id="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                            class="form-control rounded-3 @error('jawaban.' . $item->ppdb_pertanyaan_id) is-invalid @enderror"
                                            wire:model.defer="jawaban.{{ $item->ppdb_pertanyaan_id }}"
                                            rows="3"
                                            placeholder="Tuliskan jawaban Anda"
                                            @if ($item->wajib_diisi) required @endif
                                        ></textarea>
                                        @break

                                    @case('angka')
                                        <input
                                            type="number"
                                            id="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                            class="form-control rounded-3 @error('jawaban.' . $item->ppdb_pertanyaan_id) is-invalid @enderror"
                                            wire:model.defer="jawaban.{{ $item->ppdb_pertanyaan_id }}"
                                            placeholder="Masukkan angka"
                                            @if ($item->wajib_diisi) required @endif
                                        >
                                        @break

                                    @case('pilihan')
                                        <select
                                            id="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                            class="form-select rounded-3 @error('jawaban.' . $item->ppdb_pertanyaan_id) is-invalid @enderror"
                                            wire:model.defer="jawaban.{{ $item->ppdb_pertanyaan_id }}"
                                            @if ($item->wajib_diisi) required @endif
                                        >
                                            <option value="">
                                                Pilih jawaban
                                            </option>

                                            @foreach ($item->opsi_jawaban as $opsi)
                                                <option value="{{ $opsi }}">
                                                    {{ $opsi }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @break

                                    @case('ya_tidak')
                                        <div class="d-flex flex-wrap gap-4 pt-1">
                                            @foreach (['ya' => 'Ya', 'tidak' => 'Tidak'] as $nilai => $label)
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="radio"
                                                        id="jawaban-{{ $item->ppdb_pertanyaan_id }}-{{ $nilai }}"
                                                        name="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                                        value="{{ $nilai }}"
                                                        wire:model.defer="jawaban.{{ $item->ppdb_pertanyaan_id }}"
                                                    >

                                                    <label
                                                        class="form-check-label"
                                                        for="jawaban-{{ $item->ppdb_pertanyaan_id }}-{{ $nilai }}">
                                                        {{ $label }}
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                        @break
                                @endswitch

                                @error('jawaban.' . $item->ppdb_pertanyaan_id)
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="ri-checkbox-circle-line text-success fs-1"></i>

                        <h6 class="fw-semibold mt-2 mb-1">
                            Tidak ada pertanyaan tambahan
                        </h6>

                        <p class="text-muted small mb-0">
                            Anda dapat melanjutkan ke pengiriman pendaftaran.
                        </p>
                    </div>
                @endif

                <hr class="my-4">

                <div class="border rounded-4 p-3">
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            id="agree-review-{{ $pendaftaranId }}"
                            wire:model="pernyataan"
                        >

                        <label
                            class="form-check-label"
                            for="agree-review-{{ $pendaftaranId }}">
                            Saya menyatakan bahwa data yang saya isi
                            adalah benar dan dapat dipertanggungjawabkan.
                        </label>
                    </div>

                    @error('pernyataan')
                        <div class="text-danger small mt-2">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4">
                    <a
                        href="{{ route('ppdb.pendaftaran.berkas') }}"
                        class="btn btn-outline-secondary rounded-pill px-4">
                        <i class="ri-arrow-left-line me-1"></i>
                        Kembali
                    </a>

                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4"
                        wire:click="bukaKonfirmasi"
                        wire:loading.attr="disabled"
                        wire:target="bukaKonfirmasi">

                        <span wire:loading.remove wire:target="bukaKonfirmasi">
                            <i class="ri-send-plane-line me-1"></i>
                            Kirim Pendaftaran
                        </span>

                        <span wire:loading wire:target="bukaKonfirmasi">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Memeriksa data...
                        </span>
                    </button>
                </div>
            </div>
        </div>

        {{-- MODAL KONFIRMASI --}}
        <div
            wire:ignore.self
            class="modal fade"
            id="ModalKonfirmasiKirim"
            tabindex="-1"
            aria-labelledby="ModalKonfirmasiKirimLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                    {{-- CLOSE BUTTON --}}
                    <div class="modal-header border-0 pb-0">
                        <button
                            type="button"
                            class="btn btn-light btn-icon rounded-circle ms-auto"
                            data-bs-dismiss="modal"
                            aria-label="Tutup"
                        >
                            <i class="ri-close-line fs-18"></i>
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="modal-body px-4 pb-5 pt-2 text-center">
                        {{-- ICON --}}
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <lord-icon
                                        src="https://cdn.lordicon.com/sbnjyzil.json"
                                        trigger="loop"
                                        colors="primary:#0d6efd,secondary:#0d6efd"
                                        style="width:70px;height:70px"
                                    >
                                    </lord-icon>
                                </div>
                            </div>
                        </div>

                        {{-- TITLE --}}
                        <div class="mb-2">
                            <span class="badge bg-primary-subtle text-primary px-3 py-2 rounded-pill mb-3">
                                Konfirmasi Pengiriman
                            </span>

                            <h3
                                class="fw-bold mb-2"
                                id="ModalKonfirmasiKirimLabel"
                            >
                                Kirim Pendaftaran Sekarang?
                            </h3>

                            <p class="text-muted mb-0 lh-lg px-lg-3">
                                Pastikan seluruh data calon siswa, orang tua/wali,
                                alamat, pendidikan, dan berkas yang Anda isi sudah benar.
                                Periksa kembali sebelum mengirim pendaftaran.
                            </p>
                        </div>

                        {{-- WARNING --}}
                        <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <i class="ri-information-line text-primary fs-20"></i>
                                </div>

                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        Sebelum Mengirim
                                    </h6>

                                    <p class="text-muted mb-0 fs-13">
                                        Setelah dikirim, pendaftaran akan memasuki
                                        proses verifikasi panitia PPDB. Perubahan
                                        data selanjutnya mengikuti ketentuan yang
                                        berlaku di sekolah.
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">
                        <button
                            type="button"
                            class="btn btn-light rounded-pill px-4"
                            data-bs-dismiss="modal"
                        >
                            <i class="ri-arrow-go-back-line me-1"></i>
                            Periksa Kembali
                        </button>

                        <button
                            type="button"
                            class="btn btn-primary rounded-pill px-4"
                            wire:click="kirimPendaftaran"
                            wire:loading.attr="disabled"
                            wire:target="kirimPendaftaran"
                        >
                            <span wire:loading.remove wire:target="kirimPendaftaran">
                                <i class="ri-send-plane-line me-1"></i>
                                Ya, Kirim Pendaftaran
                            </span>

                            <span wire:loading wire:target="kirimPendaftaran">
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Mengirim...
                            </span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @endif

    <script>
        document.addEventListener('show-modal', function (event) {
            const modalId = event.detail?.modalId;
            const modalElement = document.getElementById(modalId);

            if (!modalElement) {
                return;
            }

            bootstrap.Modal.getOrCreateInstance(modalElement).show();
        });
    </script>
</div>
