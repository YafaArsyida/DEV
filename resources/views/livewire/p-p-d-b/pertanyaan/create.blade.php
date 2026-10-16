
<div
    wire:ignore.self
    class="modal fade"
    id="ModalAddPertanyaan"
    tabindex="-1"
    aria-labelledby="ModalAddPertanyaanLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header border-0 px-4 pt-4 pb-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-questionnaire-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1" id="ModalAddPertanyaanLabel">
                            Tambah Pertanyaan
                        </h5>

                        <small class="text-muted">
                            Susun pertanyaan kuisioner untuk calon siswa.
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

            <form
                class="d-flex flex-column overflow-hidden"
                wire:submit.prevent="save"
            >
                {{-- BODY --}}
                <div class="modal-body px-4 pt-2 pb-4">

                    <div class="d-flex justify-content-end mb-3">
                        <small class="text-muted">
                            <span class="text-danger">*</span>
                            Wajib diisi
                        </small>
                    </div>

                    {{-- PERIODE --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Periode PPDB
                            </h6>

                            <small class="text-muted">
                                Pertanyaan akan digunakan pada periode ini.
                            </small>
                        </div>

                        <label
                            for="create_periode_pertanyaan"
                            class="form-label"
                        >
                            Periode
                        </label>

                        <input
                            type="text"
                            id="create_periode_pertanyaan"
                            class="form-control"
                            value="{{ $periode->nama_periode ?? 'Belum dipilih' }}"
                            readonly
                        >

                        @if ($periode)
                            <small class="text-muted d-block mt-2">
                                {{ $periode->ms_jenjang?->nama_jenjang ?? '-' }}
                                <span class="mx-1">·</span>
                                {{ $periode->ms_tahun_ajar?->nama_tahun_ajar ?? '-' }}
                            </small>
                        @endif

                        @error('ppdb_periode_id')
                            <div class="text-danger fs-12 mt-1">
                                {{ $message }}
                            </div>
                        @enderror
                    </section>

                    {{-- ISI PERTANYAAN --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Isi Pertanyaan
                            </h6>

                            <small class="text-muted">
                                Tentukan pertanyaan dan jenis jawaban yang diharapkan.
                            </small>
                        </div>

                        {{-- TEKS PERTANYAAN --}}
                        <div class="mb-3">
                            <label
                                for="create_pertanyaan"
                                class="form-label"
                            >
                                Pertanyaan
                                <span class="text-danger">*</span>
                            </label>

                            <textarea
                                id="create_pertanyaan"
                                wire:model.defer="pertanyaan"
                                class="form-control @error('pertanyaan') is-invalid @enderror"
                                rows="3"
                                maxlength="2000"
                                placeholder="Contoh: Dari mana Anda memperoleh informasi tentang sekolah kami?"
                            ></textarea>

                            @error('pertanyaan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- JENIS JAWABAN --}}
                        <div class="mb-3">
                            <label
                                for="create_jenis_pertanyaan"
                                class="form-label"
                            >
                                Jenis Jawaban
                                <span class="text-danger">*</span>
                            </label>

                            <select
                                id="create_jenis_pertanyaan"
                                wire:model="jenis"
                                class="form-select @error('jenis') is-invalid @enderror"
                            >
                                <option value="teks">Teks Singkat</option>
                                <option value="textarea">Teks Panjang</option>
                                <option value="pilihan">Pilihan Jawaban</option>
                                <option value="ya_tidak">Ya / Tidak</option>
                                <option value="angka">Angka</option>
                            </select>

                            @error('jenis')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror

                            <small class="text-muted d-block mt-2">
                                Pilih format jawaban yang paling sesuai dengan pertanyaan.
                            </small>
                        </div>

                        {{-- PREVIEW FORMAT JAWABAN --}}
                        <div class="bg-light rounded-3 p-3 mb-3">
                            <small class="text-muted d-block mb-2">
                                <i class="ri-eye-line me-1"></i>
                                Gambaran jawaban calon siswa
                            </small>

                            @if ($jenis === 'teks')
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Jawaban singkat..."
                                    disabled
                                >
                            @elseif ($jenis === 'textarea')
                                <textarea
                                    class="form-control"
                                    rows="3"
                                    placeholder="Tuliskan jawaban..."
                                    disabled
                                ></textarea>
                            @elseif ($jenis === 'ya_tidak')
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input
                                            type="radio"
                                            class="form-check-input"
                                            disabled
                                        >
                                        <label class="form-check-label">Ya</label>
                                    </div>

                                    <div class="form-check">
                                        <input
                                            type="radio"
                                            class="form-check-input"
                                            disabled
                                        >
                                        <label class="form-check-label">Tidak</label>
                                    </div>
                                </div>
                            @elseif ($jenis === 'angka')
                                <input
                                    type="number"
                                    class="form-control"
                                    placeholder="Masukkan angka..."
                                    disabled
                                >
                            @elseif ($jenis === 'pilihan')
                                <div class="d-flex flex-column gap-2">
                                    @forelse ($opsi_jawaban as $index => $opsi)
                                        <div class="form-check" wire:key="preview-opsi-{{ $index }}">
                                            <input
                                                type="radio"
                                                class="form-check-input"
                                                disabled
                                            >

                                            <label class="form-check-label">
                                                {{ filled($opsi) ? $opsi : 'Opsi ' . ($index + 1) }}
                                            </label>
                                        </div>
                                    @empty
                                        <small class="text-muted">
                                            Tambahkan opsi jawaban di bawah.
                                        </small>
                                    @endforelse
                                </div>
                            @endif
                        </div>

                        {{-- OPSI PILIHAN --}}
                        @if ($jenis === 'pilihan')
                            <div class="border rounded-4 p-3">
                                <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
                                    <div>
                                        <h6 class="fw-semibold mb-1">
                                            Opsi Jawaban
                                        </h6>

                                        <small class="text-muted">
                                            Minimal 2 dan maksimal 20 opsi.
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

                                @foreach ($opsi_jawaban as $index => $opsi)
                                    <div
                                        class="d-flex align-items-start gap-2 mb-2"
                                        wire:key="opsi-jawaban-{{ $index }}"
                                    >
                                        <div class="flex-grow-1">
                                            <input
                                                type="text"
                                                wire:model.defer="opsi_jawaban.{{ $index }}"
                                                class="form-control @error('opsi_jawaban.' . $index) is-invalid @enderror"
                                                placeholder="Opsi {{ $index + 1 }}"
                                                maxlength="255"
                                            >

                                            @error('opsi_jawaban.' . $index)
                                                <div class="text-danger fs-12 mt-1">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>

                                        <button
                                            type="button"
                                            class="btn btn-light btn-icon text-danger"
                                            wire:click="removeOpsi({{ $index }})"
                                            title="Hapus opsi"
                                            @if (count($opsi_jawaban) <= 1) disabled @endif
                                        >
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </div>
                                @endforeach

                                @error('opsi_jawaban')
                                    <div class="text-danger fs-12 mt-2">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        @endif
                    </section>

                    {{-- PENGATURAN --}}
                    <section>
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Pengaturan Pertanyaan
                            </h6>

                            <small class="text-muted">
                                Atur kewajiban pengisian dan urutan tampil.
                            </small>
                        </div>

                        <div class="row g-3">

                            {{-- WAJIB DIISI --}}
                            <div class="col-12">
                                <div class="border rounded-3 p-3">
                                    <div class="form-check form-switch mb-0">
                                        <input
                                            type="checkbox"
                                            id="create_wajib_diisi"
                                            class="form-check-input"
                                            role="switch"
                                            wire:model="wajib_diisi"
                                        >

                                        <label
                                            for="create_wajib_diisi"
                                            class="form-check-label fw-medium"
                                        >
                                            Wajib diisi calon siswa
                                        </label>
                                    </div>

                                    <small class="text-muted d-block mt-1">
                                        Jika dinonaktifkan, calon siswa boleh melewati pertanyaan ini.
                                    </small>

                                    @error('wajib_diisi')
                                        <div class="text-danger fs-12 mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            {{-- URUTAN --}}
                            <div class="col-12 col-md-6">
                                <label
                                    for="create_urutan_pertanyaan"
                                    class="form-label"
                                >
                                    Urutan Tampil
                                    <span class="text-danger">*</span>
                                </label>

                                <input
                                    type="number"
                                    id="create_urutan_pertanyaan"
                                    wire:model.defer="urutan"
                                    class="form-control @error('urutan') is-invalid @enderror"
                                    min="1"
                                    step="1"
                                >

                                @error('urutan')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            {{-- STATUS --}}
                            <div class="col-12 col-md-6">
                                <label
                                    for="create_status_pertanyaan"
                                    class="form-label"
                                >
                                    Status
                                    <span class="text-danger">*</span>
                                </label>

                                <select
                                    id="create_status_pertanyaan"
                                    wire:model.defer="status"
                                    class="form-select @error('status') is-invalid @enderror"
                                >
                                    <option value="aktif">Aktif</option>
                                    <option value="nonaktif">Tidak Aktif</option>
                                </select>

                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                    </section>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal"
                    >
                        <i class="ri-close-line me-1"></i>
                        Batal
                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary rounded-pill px-4"
                        wire:loading.attr="disabled"
                        wire:target="save"
                    >
                        <span wire:loading.remove wire:target="save">
                            <i class="ri-save-3-line me-1"></i>
                            Simpan Pertanyaan
                        </span>

                        <span wire:loading wire:target="save">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Menyimpan...
                        </span>
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>
