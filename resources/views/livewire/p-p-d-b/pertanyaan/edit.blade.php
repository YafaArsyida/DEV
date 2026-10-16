<div
    wire:ignore.self
    class="modal fade"
    id="ModalEditPertanyaan"
    tabindex="-1"
    aria-labelledby="ModalEditPertanyaanLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-edit-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1" id="ModalEditPertanyaanLabel">
                            Edit Pertanyaan PPDB
                        </h5>
                        <small class="text-muted">
                            Perbarui teks pertanyaan, jenis jawaban, dan pengaturan pengisian.
                        </small>
                    </div>
                </div>

                <button
                    type="button"
                    class="btn btn-light btn-icon rounded-circle"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                >
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- FORM --}}
            <form wire:submit.prevent="update">
                <div class="modal-body px-4 pt-2 pb-4">

                    {{-- PERIODE --}}
                    <div class="card border rounded-4 shadow-none mb-4">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-sm flex-shrink-0">
                                    <div class="avatar-title bg-light text-primary rounded-circle fs-20">
                                        <i class="ri-calendar-event-line"></i>
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">
                                        Periode PPDB
                                    </h6>

                                    @if ($periode)
                                        <div class="fw-medium">
                                            {{ $periode->nama_periode }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $periode->ms_jenjang?->nama_jenjang ?: '-' }}
                                            &middot;
                                            {{ $periode->ms_tahun_ajar?->nama_tahun_ajar ?: '-' }}
                                        </small>
                                    @else
                                        <small class="text-muted">
                                            Periode akan ditampilkan setelah data pertanyaan dimuat.
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TEKS PERTANYAAN --}}
                    <div class="mb-4">
                        <label for="edit-pertanyaan" class="form-label fw-medium">
                            Teks Pertanyaan
                            <span class="text-danger">*</span>
                        </label>

                        <textarea
                            id="edit-pertanyaan"
                            class="form-control @error('pertanyaan') is-invalid @enderror"
                            rows="3"
                            wire:model.defer="pertanyaan"
                            placeholder="Masukkan teks pertanyaan..."
                        ></textarea>

                        @error('pertanyaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- JENIS PERTANYAAN --}}
                    <div class="mb-4">
                        <label for="edit-jenis" class="form-label fw-medium">
                            Jenis Pertanyaan
                            <span class="text-danger">*</span>
                        </label>

                        <select
                            id="edit-jenis"
                            class="form-select @error('jenis') is-invalid @enderror"
                            wire:model="jenis"
                        >
                            <option value="teks">Jawaban Singkat</option>
                            <option value="textarea">Jawaban Panjang</option>
                            <option value="pilihan">Pilihan Jawaban</option>
                            <option value="ya_tidak">Ya / Tidak</option>
                            <option value="angka">Angka</option>
                        </select>

                        @error('jenis')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        <small class="text-muted d-block mt-2">
                            Pilih bentuk jawaban yang sesuai dengan informasi yang dibutuhkan sekolah.
                        </small>
                    </div>

                    {{-- OPSI JAWABAN --}}
                    @if ($jenis === 'pilihan')
                        <div class="card border rounded-4 shadow-none mb-4">
                            <div class="card-header bg-transparent d-flex align-items-center justify-content-between gap-3">
                                <div>
                                    <h6 class="fw-semibold mb-1">
                                        Opsi Jawaban
                                    </h6>
                                    <small class="text-muted">
                                        Tambahkan minimal 2 pilihan jawaban.
                                    </small>
                                </div>

                                <button
                                    type="button"
                                    class="btn btn-soft-primary btn-sm rounded-pill flex-shrink-0"
                                    wire:click="addOpsi"
                                    @if (count($opsi_jawaban) >= 20) disabled @endif
                                >
                                    <i class="ri-add-line me-1"></i>
                                    Tambah
                                </button>
                            </div>

                            <div class="card-body">
                                @foreach ($opsi_jawaban as $index => $opsi)
                                    <div
                                        class="mb-3"
                                        wire:key="edit-opsi-{{ $selectedPertanyaanId }}-{{ $index }}"
                                    >
                                        <label class="form-label">
                                            Opsi {{ $index + 1 }}
                                        </label>

                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="ri-radio-button-line"></i>
                                            </span>

                                            <input
                                                type="text"
                                                class="form-control @error('opsi_jawaban.' . $index) is-invalid @enderror"
                                                wire:model.defer="opsi_jawaban.{{ $index }}"
                                                placeholder="Masukkan opsi jawaban..."
                                            >

                                            <button
                                                type="button"
                                                class="btn btn-light text-danger"
                                                wire:click="removeOpsi({{ $index }})"
                                                title="Hapus opsi"
                                            >
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </div>

                                        @error('opsi_jawaban.' . $index)
                                            <div class="text-danger small mt-1">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                @endforeach

                                @error('opsi_jawaban')
                                    <div class="text-danger small mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <small class="text-muted">
                                    Maksimal 20 opsi. Setiap opsi harus memiliki teks.
                                </small>
                            </div>
                        </div>
                    @endif

                    {{-- PREVIEW --}}
                    <div class="card border rounded-4 shadow-none mb-4">
                        <div class="card-header bg-transparent">
                            <h6 class="fw-semibold mb-1">
                                Pratinjau Jawaban
                            </h6>
                            <small class="text-muted">
                                Gambaran bentuk jawaban yang akan dilihat pendaftar.
                            </small>
                        </div>

                        <div class="card-body">
                            <div class="fw-medium mb-3">
                                {{ filled($pertanyaan) ? $pertanyaan : 'Teks pertanyaan akan ditampilkan di sini.' }}
                                @if ($wajib_diisi)
                                    <span class="text-danger">*</span>
                                @endif
                            </div>

                            @switch($jenis)
                                @case('teks')
                                    <input
                                        type="text"
                                        class="form-control"
                                        placeholder="Jawaban singkat..."
                                        disabled
                                    >
                                    @break

                                @case('textarea')
                                    <textarea
                                        class="form-control"
                                        rows="3"
                                        placeholder="Tuliskan jawaban..."
                                        disabled
                                    ></textarea>
                                    @break

                                @case('pilihan')
                                    @forelse ($opsi_jawaban as $index => $opsi)
                                        <div class="form-check mb-2">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                disabled
                                            >
                                            <label class="form-check-label">
                                                {{ filled($opsi) ? $opsi : 'Opsi jawaban ' . ($index + 1) }}
                                            </label>
                                        </div>
                                    @empty
                                        <small class="text-muted">
                                            Belum ada opsi jawaban.
                                        </small>
                                    @endforelse
                                    @break

                                @case('ya_tidak')
                                    <div class="d-flex flex-wrap gap-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" disabled>
                                            <label class="form-check-label">Ya</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" disabled>
                                            <label class="form-check-label">Tidak</label>
                                        </div>
                                    </div>
                                    @break

                                @case('angka')
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="Masukkan angka..."
                                        disabled
                                    >
                                    @break
                            @endswitch
                        </div>
                    </div>

                    {{-- PENGATURAN --}}
                    <div class="row g-3">
                        <div class="col-12">
                            <div class="form-check form-switch">
                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    id="edit-wajib-diisi"
                                    wire:model="wajib_diisi"
                                >

                                <label class="form-check-label fw-medium" for="edit-wajib-diisi">
                                    Wajib diisi oleh pendaftar
                                </label>

                                <small class="text-muted d-block mt-1">
                                    Jika diaktifkan, pendaftar harus menjawab pertanyaan ini sebelum mengirim formulir.
                                </small>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label for="edit-urutan" class="form-label fw-medium">
                                Urutan Pertanyaan
                                <span class="text-danger">*</span>
                            </label>

                            <input
                                id="edit-urutan"
                                type="number"
                                min="1"
                                class="form-control @error('urutan') is-invalid @enderror"
                                wire:model.defer="urutan"
                            >

                            @error('urutan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="edit-status" class="form-label fw-medium">
                                Status
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="edit-status"
                                class="form-select @error('status') is-invalid @enderror"
                                wire:model.defer="status"
                            >
                                <option value="aktif">Aktif</option>
                                <option value="nonaktif">Nonaktif</option>
                            </select>

                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary rounded-pill px-4"
                        wire:loading.attr="disabled"
                        wire:target="update"
                    >
                        <span wire:loading.remove wire:target="update">
                            <i class="ri-save-line me-1"></i>
                            Simpan Perubahan
                        </span>

                        <span wire:loading wire:target="update">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>