<div
    wire:ignore.self
    class="modal fade"
    id="ModalDetailPertanyaan"
    tabindex="-1"
    aria-labelledby="ModalDetailPertanyaanLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
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
                        <h5 class="fw-bold mb-1" id="ModalDetailPertanyaanLabel">
                            Detail Pertanyaan PPDB
                        </h5>
                        <small class="text-muted">
                            Informasi pertanyaan, jenis jawaban, dan pengaturan pengisian formulir pendaftaran.
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

            {{-- BODY --}}
            <div class="modal-body px-4 pt-2 pb-4">

                @if ($pertanyaanDetail)

                    {{-- IDENTITAS PERTANYAAN --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Identitas Pertanyaan
                            </h6>
                            <small class="text-muted">
                                Informasi utama pertanyaan yang ditampilkan kepada calon siswa atau orang tua.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">
                                    Teks Pertanyaan
                                </label>

                                <div
                                    class="form-control bg-light text-break"
                                    style="min-height: 80px; height: auto; white-space: pre-line;"
                                >{{ filled($pertanyaanDetail->pertanyaan) ? $pertanyaanDetail->pertanyaan : '-' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Jenis Pertanyaan
                                </label>

                                <div class="form-control bg-light d-flex align-items-center">
                                    @switch($pertanyaanDetail->jenis)
                                        @case('teks')
                                            <i class="ri-text me-2 text-primary"></i>
                                            Jawaban Singkat
                                            @break

                                        @case('textarea')
                                            <i class="ri-file-text-line me-2 text-primary"></i>
                                            Jawaban Panjang
                                            @break

                                        @case('pilihan')
                                            <i class="ri-list-radio me-2 text-primary"></i>
                                            Pilihan Jawaban
                                            @break

                                        @case('ya_tidak')
                                            <i class="ri-checkbox-circle-line me-2 text-primary"></i>
                                            Ya / Tidak
                                            @break

                                        @case('angka')
                                            <i class="ri-hashtag me-2 text-primary"></i>
                                            Angka
                                            @break

                                        @default
                                            {{ ucfirst(str_replace('_', ' ', $pertanyaanDetail->jenis ?? '-')) }}
                                    @endswitch
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Urutan Pertanyaan
                                </label>

                                <div class="form-control bg-light">
                                    {{ $pertanyaanDetail->urutan ?? '-' }}
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- OPSI JAWABAN --}}
                    @if ($pertanyaanDetail->jenis === 'pilihan')
                        <section class="mb-4">
                            <div class="mb-3">
                                <h6 class="fw-semibold mb-1">
                                    Opsi Jawaban
                                </h6>
                                <small class="text-muted">
                                    Pilihan yang dapat dipilih oleh pendaftar.
                                </small>
                            </div>

                            @php
                                $opsiJawaban = $pertanyaanDetail->opsi_jawaban;

                                if (is_string($opsiJawaban)) {
                                    $opsiJawaban = json_decode($opsiJawaban, true);
                                }

                                $opsiJawaban = is_array($opsiJawaban)
                                    ? $opsiJawaban
                                    : [];
                            @endphp

                            @if (count($opsiJawaban))
                                <div class="border rounded-4 overflow-hidden">
                                    @foreach ($opsiJawaban as $index => $opsi)
                                        <div
                                            class="d-flex align-items-start gap-3 p-3 {{ !$loop->last ? 'border-bottom' : '' }}"
                                            wire:key="detail-opsi-{{ $pertanyaanDetail->ppdb_pertanyaan_id }}-{{ $index }}"
                                        >
                                            <div class="avatar-xs flex-shrink-0">
                                                <div class="avatar-title bg-light text-primary rounded-circle">
                                                    <i class="ri-radio-button-line"></i>
                                                </div>
                                            </div>

                                            <div class="flex-grow-1 text-break">
                                                {{ $opsi }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="border rounded-4 p-3 text-muted">
                                    Belum ada opsi jawaban yang dikonfigurasi.
                                </div>
                            @endif
                        </section>
                    @endif

                    {{-- PENGATURAN --}}
                    <section class="mb-4">
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Pengaturan Pertanyaan
                            </h6>
                            <small class="text-muted">
                                Ketentuan pengisian dan status pertanyaan pada formulir PPDB.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">
                                    Wajib Diisi
                                </label>

                                <div class="form-control bg-light d-flex align-items-center">
                                    @if ($pertanyaanDetail->wajib_diisi)
                                        <span class="text-success fw-medium">
                                            <i class="ri-checkbox-circle-line me-1"></i>
                                            Ya, wajib diisi
                                        </span>
                                    @else
                                        <span class="text-muted fw-medium">
                                            <i class="ri-indeterminate-circle-line me-1"></i>
                                            Tidak wajib
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Status Pertanyaan
                                </label>

                                <div class="form-control bg-light d-flex align-items-center">
                                    @if (strtolower((string) $pertanyaanDetail->status) === 'aktif')
                                        <span class="text-success fw-medium">
                                            <i class="ri-checkbox-circle-line me-1"></i>
                                            Aktif
                                        </span>
                                    @else
                                        <span class="text-muted fw-medium">
                                            <i class="ri-close-circle-line me-1"></i>
                                            Nonaktif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- PERIODE PPDB --}}
                    <section>
                        <div class="mb-3">
                            <h6 class="fw-semibold mb-1">
                                Periode PPDB
                            </h6>
                            <small class="text-muted">
                                Periode dan tahun ajaran tempat pertanyaan ini digunakan.
                            </small>
                        </div>

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">
                                    Nama Periode
                                </label>

                                <div class="form-control bg-light text-break">
                                    {{ $pertanyaanDetail->ppdb_periode?->nama_periode ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Jenjang
                                </label>

                                <div class="form-control bg-light text-break">
                                    {{ $pertanyaanDetail->ppdb_periode?->ms_jenjang?->nama_jenjang ?: '-' }}
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Tahun Ajaran
                                </label>

                                <div class="form-control bg-light text-break">
                                    {{ $pertanyaanDetail->ppdb_periode?->ms_tahun_ajar?->nama_tahun_ajar ?: '-' }}
                                </div>
                            </div>
                        </div>
                    </section>

                @else
                    {{-- EMPTY STATE --}}
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                <i class="ri-questionnaire-line"></i>
                            </div>
                        </div>

                        <h6 class="fw-semibold mb-1">
                            Data Pertanyaan Tidak Ditemukan
                        </h6>

                        <p class="text-muted mb-0">
                            Pertanyaan yang dipilih tidak tersedia atau telah dihapus.
                        </p>
                    </div>
                @endif

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button
                    type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal"
                >
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>