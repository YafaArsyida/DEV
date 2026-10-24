<div
    wire:ignore.self
    class="offcanvas offcanvas-top bg-light"
    tabindex="-1"
    id="offcanvasPemeriksaanPendaftaran"
    aria-labelledby="offcanvasPemeriksaanPendaftaranLabel"
    style="height: 100vh;">

    {{-- HEADER ASLI --}}
    <div class="offcanvas-header border-bottom bg-white px-3 px-md-4 py-3">
        <div class="d-flex align-items-center gap-3 w-100">

            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-user-search-line"></i>
                </div>
            </div>

            <div class="flex-grow-1 min-w-0">
                <h5
                    class="fw-semibold mb-1"
                    id="offcanvasPemeriksaanPendaftaranLabel">
                    Pemeriksaan Pendaftaran
                </h5>

                <p class="text-muted small mb-0">
                    @if ($pendaftaran)
                        {{ $pendaftaran->nomor_pendaftaran ?? '-' }}
                        &middot;
                        {{ $pendaftaran->ppdb_calon_siswa->nama_siswa ?? 'Calon siswa' }}
                    @else
                        Periksa data dan dokumen calon siswa
                    @endif
                </p>
            </div>

            <button
                type="button"
                class="btn-close flex-shrink-0 ms-auto"
                data-bs-dismiss="offcanvas"
                aria-label="Tutup">
            </button>

        </div>
    </div>

    {{-- AREA UTAMA --}}
    <div class="offcanvas-body p-0 d-flex flex-column">

        @if (!$pendaftaran)
            <div class="text-center py-5">
                <div class="spinner-border text-primary mb-3"></div>
                <p class="text-muted mb-0">
                    Memuat informasi pendaftar...
                </p>
            </div>
        @else
            @php
                $statusLabel = [
                    'draft' => 'Draft',
                    'pengecekan' => 'Pengecekan',
                    'diterima' => 'Diterima',
                    'perbaikan' => 'Perlu Perbaikan',
                ];

                $statusClass = [
                    'draft' => 'bg-secondary-subtle text-secondary',
                    'pengecekan' => 'bg-warning-subtle text-warning',
                    'diterima' => 'bg-success-subtle text-success',
                    'perbaikan' => 'bg-danger-subtle text-danger',
                ];
            @endphp

            {{-- INFORMASI PENDAFTARAN --}}
            <div class="bg-white border-bottom p-3 p-md-4">
                <div class="card border rounded-4 shadow-none mb-0">
                    <div class="card-body p-3 p-md-4">
                        <div class="row align-items-center g-3">

                            <div class="col-12 col-md">
                                <div class="text-muted small mb-1">
                                    Nomor Pendaftaran
                                </div>

                                <h5 class="text-primary fw-bold mb-2">
                                    {{ $pendaftaran->nomor_pendaftaran ?? '-' }}
                                </h5>

                                <div class="d-flex align-items-center gap-2 text-muted small">
                                    <i class="ri-calendar-line fs-16"></i>
                                    <span>
                                        {{\App\Http\Controllers\HelperController::formatTanggalIndonesia($pendaftaran->created_at, 'd F Y') }}
                                    </span>
                                </div>
                            </div>

                            <div class="col-12 col-md-auto">
                                <div class="d-flex flex-column gap-2">
                                    <div>
                                        <span class="badge rounded-pill px-3 py-2
                                            {{ $statusClass[$pendaftaran->status] ?? 'bg-secondary-subtle text-secondary' }}">
                                            {{ $statusLabel[$pendaftaran->status] ?? ucfirst($pendaftaran->status ?? '-') }}
                                        </span>
                                    </div>

                                    <div class="fw-semibold">
                                        {{ $pendaftaran->ppdb_jalur->nama_jalur ?? '-' }}
                                    </div>

                                    <div class="text-muted small">
                                        {{ $pendaftaran->ppdb_periode->nama_periode ?? '-' }}
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            {{-- NAVIGASI TAB --}}
            <div class="bg-white border-bottom px-2 px-md-4 py-3">
                <ul
                    class="nav nav-pills nav-justified gap-1 flex-wrap mb-0"
                    id="tabPemeriksaanPendaftaran"
                    role="tablist">

                    {{-- SISWA --}}
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link active rounded-pill"
                            id="tab-siswa"
                            data-bs-toggle="tab"
                            data-bs-target="#konten-siswa"
                            type="button"
                            role="tab"
                            aria-controls="konten-siswa"
                            aria-selected="true">
                            <i class="ri-user-line me-1"></i>
                            Siswa
                        </button>
                    </li>

                    {{-- ORANG TUA / WALI --}}
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link rounded-pill"
                            id="tab-orang-tua"
                            data-bs-toggle="tab"
                            data-bs-target="#konten-orang-tua"
                            type="button"
                            role="tab"
                            aria-controls="konten-orang-tua"
                            aria-selected="false">
                            <i class="ri-team-line me-1"></i>
                            Orang Tua / Wali
                        </button>
                    </li>

                    {{-- ALAMAT --}}
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link rounded-pill"
                            id="tab-alamat"
                            data-bs-toggle="tab"
                            data-bs-target="#konten-alamat"
                            type="button"
                            role="tab"
                            aria-controls="konten-alamat"
                            aria-selected="false">
                            <i class="ri-map-pin-line me-1"></i>
                            Alamat
                        </button>
                    </li>

                    {{-- PENDIDIKAN --}}
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link rounded-pill"
                            id="tab-pendidikan"
                            data-bs-toggle="tab"
                            data-bs-target="#konten-pendidikan"
                            type="button"
                            role="tab"
                            aria-controls="konten-pendidikan"
                            aria-selected="false">
                            <i class="ri-graduation-cap-line me-1"></i>
                            Pendidikan
                        </button>
                    </li>

                    {{-- BERKAS --}}
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link rounded-pill"
                            id="tab-berkas"
                            data-bs-toggle="tab"
                            data-bs-target="#konten-berkas"
                            type="button"
                            role="tab"
                            aria-controls="konten-berkas"
                            aria-selected="false">
                            <i class="ri-folder-line me-1"></i>
                            Berkas
                        </button>
                    </li>

                    {{-- TAMBAHAN --}}
                    <li class="nav-item" role="presentation">
                        <button
                            class="nav-link rounded-pill"
                            id="tab-tambahan"
                            data-bs-toggle="tab"
                            data-bs-target="#konten-tambahan"
                            type="button"
                            role="tab"
                            aria-controls="konten-tambahan"
                            aria-selected="false">
                            <i class="ri-questionnaire-line me-1"></i>
                            Tambahan
                        </button>
                    </li>

                </ul>
            </div>

            {{-- KONTEN TAB --}}
            <div class="tab-content p-3 p-md-4" id="kontenTabPemeriksaan">
                <div
                    class="tab-pane fade show active"
                    id="konten-siswa"
                    role="tabpanel"
                    aria-labelledby="tab-siswa"
                    tabindex="0">

                    @livewire(
                        'p-p-d-b.admin.verifikasi.data-siswa',
                        ['pendaftaranId' => $pendaftaran->getKey()],
                        key('pemeriksaan-siswa-' . $pendaftaran->getKey())
                    )
                </div>

                <div
                    class="tab-pane fade"
                    id="konten-orang-tua"
                    role="tabpanel"
                    aria-labelledby="tab-orang-tua"
                    tabindex="0">

                    @livewire(
                        'p-p-d-b.admin.verifikasi.data-orang-tua',
                        ['pendaftaranId' => $pendaftaran->getKey()],
                        key('pemeriksaan-orang-tua-' . $pendaftaran->getKey())
                    )
                </div>

                <div
                    class="tab-pane fade"
                    id="konten-alamat"
                    role="tabpanel"
                    aria-labelledby="tab-alamat"
                    tabindex="0">
                    {{-- Komponen Livewire data alamat --}}

                    @livewire(
                        'p-p-d-b.admin.verifikasi.data-alamat',
                        ['pendaftaranId' => $pendaftaran->getKey()],
                        key('pemeriksaan-alamat-' . $pendaftaran->getKey())
                    )
                </div>

                <div class="tab-pane fade"
                    id="konten-pendidikan"
                    role="tabpanel"
                    aria-labelledby="tab-pendidikan"
                    tabindex="0">
                    {{-- Komponen Livewire data pendidikan --}}
                    @livewire(
                        'p-p-d-b.admin.verifikasi.data-pendidikan',
                        ['pendaftaranId' => $pendaftaran->getKey()],
                        key('pemeriksaan-pendidikan-' . $pendaftaran->getKey())
                    )
                </div>

                <div
                    class="tab-pane fade"
                    id="konten-berkas"
                    role="tabpanel"
                    aria-labelledby="tab-berkas"
                    tabindex="0">
                    {{-- Komponen Livewire berkas --}}
                    @livewire(
                        'p-p-d-b.admin.verifikasi.data-berkas',
                        ['pendaftaranId' => $pendaftaran->getKey()],
                        key('pemeriksaan-berkas-' . $pendaftaran->getKey())
                    )
                </div>

                <div
                    class="tab-pane fade"
                    id="konten-tambahan"
                    role="tabpanel"
                    aria-labelledby="tab-tambahan"
                    tabindex="0">

                    @livewire(
                        'p-p-d-b.admin.verifikasi.data-tambahan',
                        ['pendaftaranId' => $pendaftaran->getKey()],
                        key('pemeriksaan-tambahan-' . $pendaftaran->getKey())
                    )

                </div>

            </div>

            {{-- FOOTER KEPUTUSAN --}}
            <div class="bg-white border-top p-3 p-md-4 mt-auto">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                    {{-- INFORMASI --}}
                    <div class="d-flex align-items-start gap-2">
                        <i class="ri-information-line text-muted fs-18 mt-1"></i>

                        <div>
                            <div class="fw-semibold small">
                                Pemeriksaan Pendaftaran
                            </div>
                            <small class="text-muted">
                                Pastikan data dan berkas telah diperiksa sebelum mengambil keputusan.
                            </small>
                        </div>
                    </div>

                    {{-- TOMBOL KEPUTUSAN --}}
                    <div class="d-flex flex-column flex-sm-row gap-2 flex-shrink-0">
                        {{-- MINTA PERBAIKAN --}}
                        <button
                            type="button"
                            class="btn btn-soft-danger rounded-pill px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalKonfirmasiPerbaikan"
                            wire:click="$emit('bukaPerbaikan', {{ $pendaftaranId }})"
                            @if (!$pendaftaran || $pendaftaran->status !== 'pengecekan')
                                disabled
                            @endif
                        >
                            <i class="ri-edit-line me-1"></i>
                            Minta Perbaikan
                        </button>

                        {{-- TERIMA PENDAFTARAN --}}
                        <button
                            type="button"
                            class="btn btn-success rounded-pill px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalKonfirmasiDiterima"
                            wire:click="$emit('bukaDiterima', {{ $pendaftaranId }})"
                            @if (
                                !$pendaftaran ||
                                !in_array($pendaftaran->status, ['pengecekan', 'perbaikan'])
                            )
                                disabled
                            @endif
                        >
                            <i class="ri-checkbox-circle-line me-1"></i>
                            Terima Pendaftaran
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>