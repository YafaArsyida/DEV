<div
    wire:ignore.self
    class="offcanvas offcanvas-top bg-light"
    tabindex="-1"
    id="offcanvasBantuPendaftaran"
    aria-labelledby="offcanvasBantuPendaftaranLabel"
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

                    Bantu Pendaftaran
                    {{ $periode?->nama_periode ?? 'Periode Belum Dipilih' }}
                </h5>

                <p class="text-muted small mb-0">
                    @if ($periode)
                        Periode pendaftaran

                        {{ $periode->tanggal_mulai
                            ? \Carbon\Carbon::parse($periode->tanggal_mulai)->translatedFormat('d F Y')
                            : '-' }}

                        –

                        {{ $periode->tanggal_selesai
                            ? \Carbon\Carbon::parse($periode->tanggal_selesai)->translatedFormat('d F Y')
                            : '-' }}
                    @else
                        <i class="ri-information-line me-1"></i>
                        Silakan pilih periode pendaftaran terlebih dahulu.
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

    {{-- BODY --}}
    <div class="offcanvas-body p-0 d-flex flex-column">
        {{-- NAV --}}
        <div class="bg-white border-bottom px-2 px-md-4 py-3">
            <ul class="nav nav-pills nav-justified gap-1 flex-wrap mb-0" id="tabBantuPendaftaran" role="tablist">
                {{-- JALUR --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'jalur' ? 'active' : '' }}"
                        id="tab-jalur"
                        role="tab"
                        aria-controls="content-jalur"
                        aria-selected="{{ $tabAktif === 'jalur' ? 'true' : 'false' }}"
                        wire:click="pindahTab('jalur')">
                        <i class="ri-route-line me-1"></i>
                        Jalur

                        @if ($this->errorTabJalur())
                            <i
                                class="ri-error-warning-fill ms-1 text-danger"
                                title="Terdapat kesalahan pada jalur">
                            </i>
                        @endif
                    </button>
                </li>

                {{-- SISWA --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'siswa' ? 'active' : '' }}"
                        id="tab-siswa"
                        role="tab"
                        aria-controls="content-siswa"
                        aria-selected="{{ $tabAktif === 'siswa' ? 'true' : 'false' }}"
                        wire:click="pindahTab('siswa')">
                        <i class="ri-user-line me-1"></i>
                        Siswa

                        @if ($this->errorTabSiswa())
                            <i
                                class="ri-error-warning-fill ms-1 text-danger"
                                title="Terdapat kesalahan pada data siswa">
                            </i>
                        @endif
                    </button>
                </li>

                {{-- ORANG TUA / WALI --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'orang-tua' ? 'active' : '' }}"
                        id="tab-orang-tua"
                        role="tab"
                        aria-controls="content-orang-tua"
                        aria-selected="{{ $tabAktif === 'orang-tua' ? 'true' : 'false' }}"
                        wire:click="pindahTab('orang-tua')">
                        <i class="ri-team-line me-1"></i>
                        Orang Tua / Wali

                        @if ($this->errorTabOrangTua())
                            <i
                                class="ri-error-warning-fill ms-1 text-danger"
                                title="Terdapat kesalahan pada data orang tua">
                            </i>
                        @endif
                    </button>
                </li>

                {{-- ALAMAT --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'alamat' ? 'active' : '' }}"
                        id="tab-alamat"
                        role="tab"
                        aria-controls="content-alamat"
                        aria-selected="{{ $tabAktif === 'alamat' ? 'true' : 'false' }}"
                        wire:click="pindahTab('alamat')">
                        <i class="ri-map-pin-line me-1"></i>
                        Alamat

                        @if ($this->errorTabAlamat())
                            <i
                                class="ri-error-warning-fill ms-1 text-danger"
                                title="Terdapat kesalahan pada data alamat">
                            </i>
                        @endif
                    </button>
                </li>

                {{-- PENDIDIKAN --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'pendidikan' ? 'active' : '' }}"
                        id="tab-pendidikan"
                        role="tab"
                        aria-controls="content-pendidikan"
                        aria-selected="{{ $tabAktif === 'pendidikan' ? 'true' : 'false' }}"
                        wire:click="pindahTab('pendidikan')">
                        <i class="ri-graduation-cap-line me-1"></i>
                        Pendidikan

                        @if ($this->errorTabPendidikan())
                            <i
                                class="ri-error-warning-fill ms-1 text-danger"
                                title="Terdapat kesalahan pada data pendidikan">
                            </i>
                        @endif
                    </button>
                </li>

                {{-- BERKAS --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'berkas' ? 'active' : '' }}"
                        id="tab-berkas"
                        role="tab"
                        aria-controls="content-berkas"
                        aria-selected="{{ $tabAktif === 'berkas' ? 'true' : 'false' }}"
                        wire:click="pindahTab('berkas')">
                        <i class="ri-folder-line me-1"></i>
                        Berkas
                        
                        @if ($this->errorTabBerkas())
                            <i
                                class="ri-error-warning-fill ms-1 text-danger"
                                title="Terdapat kesalahan pada berkas">
                            </i>
                        @endif
                    </button>
                </li>

                {{-- PERTANYAAN TAMBAHAN --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'pertanyaan' ? 'active' : '' }}"
                        id="tab-pertanyaan"
                        role="tab"
                        aria-controls="content-pertanyaan"
                        aria-selected="{{ $tabAktif === 'pertanyaan' ? 'true' : 'false' }}"
                        wire:click="pindahTab('pertanyaan')">

                        <i class="ri-question-answer-line me-1"></i>
                        Pertanyaan Tambahan

                        @if ($this->errorTabPertanyaan())
                            <i
                                class="ri-error-warning-fill ms-1 text-danger"
                                title="Terdapat kesalahan pada pertanyaan tambahan">
                            </i>
                        @endif
                    </button>
                </li>
            </ul>
        </div>

        
        {{-- KONTEN TAB --}}
        <div class="tab-content p-3 p-md-4" id="contentBantuPendaftaran">

            {{-- LOADING SAAT PINDAH TAB --}}
            <div
                wire:loading.flex
                wire:target="pindahTab"
                class="align-items-center justify-content-center text-center py-5"
                style="min-height: 300px;"
            >
                <div>
                    <div
                        class="spinner-border text-primary mb-3"
                        role="status"
                    >
                        <span class="visually-hidden">Memuat...</span>
                    </div>

                    <p class="text-muted mb-0">
                        Memuat formulir pendaftaran...
                    </p>
                </div>
            </div>

            {{-- KONTEN FORM --}}
            <div wire:loading.remove wire:target="pindahTab">

                <div class="tab-content">

                    {{-- TAB 1: JALUR --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'jalur' ? 'show active' : '' }}"
                        id="content-jalur"
                        role="tabpanel"
                        aria-labelledby="tab-jalur"
                        tabindex="0"
                    >
                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-jalur')
                    </div>

                    {{-- TAB 2: SISWA --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'siswa' ? 'show active' : '' }}"
                        id="content-siswa"
                        role="tabpanel"
                        aria-labelledby="tab-siswa"
                        tabindex="0"
                    >
                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-siswa')
                    </div>

                    {{-- TAB 3: ORANG TUA / WALI --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'orang-tua' ? 'show active' : '' }}"
                        id="content-orang-tua"
                        role="tabpanel"
                        aria-labelledby="tab-orang-tua"
                        tabindex="0"
                    >
                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-orang-tua')
                    </div>

                    {{-- TAB 4: ALAMAT --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'alamat' ? 'show active' : '' }}"
                        id="content-alamat"
                        role="tabpanel"
                        aria-labelledby="tab-alamat"
                        tabindex="0"
                    >
                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-alamat')
                    </div>

                    {{-- TAB 5: PENDIDIKAN --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'pendidikan' ? 'show active' : '' }}"
                        id="content-pendidikan"
                        role="tabpanel"
                        aria-labelledby="tab-pendidikan"
                        tabindex="0"
                    >
                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-pendidikan')
                    </div>

                    {{-- TAB 6: BERKAS --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'berkas' ? 'show active' : '' }}"
                        id="content-berkas"
                        role="tabpanel"
                        aria-labelledby="tab-berkas"
                        tabindex="0"
                    >
                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-berkas')
                    </div>

                    {{-- TAB 7: PERTANYAAN TAMBAHAN --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'pertanyaan' ? 'show active' : '' }}"
                        id="content-pertanyaan"
                        role="tabpanel"
                        aria-labelledby="tab-pertanyaan"
                        tabindex="0"
                    >
                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-pertanyaan')
                    </div>

                </div>
            </div>

        </div>

    </div>

    {{-- FOOTER: SATU TOMBOL SIMPAN --}}
    <div class="border-top bg-white p-3">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

            <div class="text-muted small">
                <i class="ri-information-line me-1"></i>
                Simpan sebagai draft untuk dilanjutkan nanti.
            </div>

            <div class="d-flex gap-2 flex-shrink-0">
                <button
                    type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="offcanvas"
                    wire:loading.attr="disabled"
                    wire:target="simpanDraft">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>

                <button
                    type="button"
                    class="btn btn-primary rounded-pill px-4"
                    wire:click="simpanDraft"
                    wire:loading.attr="disabled"
                    wire:target="simpanDraft">

                    <span wire:loading.remove wire:target="simpanDraft">
                        <i class="ri-save-line me-1"></i>
                        Simpan Pendaftaran
                    </span>

                    <span wire:loading wire:target="simpanDraft">
                        <span class="spinner-border spinner-border-sm me-1"
                            role="status" aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>
            </div>

        </div>
    </div>

</div>