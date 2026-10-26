
<div
    wire:ignore.self
    class="offcanvas offcanvas-top bg-light"
    tabindex="-1"
    id="offcanvasEditPendaftaran"
    aria-labelledby="offcanvasEditPendaftaranLabel"
    style="height: 100vh;">

    {{-- HEADER --}}
    <div class="offcanvas-header border-bottom bg-white px-3 px-md-4 py-3">
        <div class="d-flex align-items-center gap-3 w-100">

            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-edit-box-line"></i>
                </div>
            </div>

            <div class="flex-grow-1 min-w-0">
                <h5
                    class="fw-semibold mb-1"
                    id="offcanvasEditPendaftaranLabel">
                    Edit Data Pendaftaran
                </h5>

                <p class="text-muted small mb-0">
                    @if ($pendaftaranId)
                        Perbarui data calon siswa, orang tua/wali, dan dokumen pendaftaran.
                    @else
                        Memuat data pendaftaran...
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

        {{-- NAVIGASI TAB --}}
        <div class="bg-white border-bottom px-2 px-md-4 py-3">
            <ul
                class="nav nav-pills nav-justified gap-1 flex-wrap mb-0"
                id="tabEditPendaftaran"
                role="tablist">

                {{-- TAB JALUR --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'jalur' ? 'active' : '' }}"
                        id="edit-tab-jalur"
                        role="tab"
                        aria-controls="edit-content-jalur"
                        aria-selected="{{ $tabAktif === 'jalur' ? 'true' : 'false' }}"
                        wire:click="pindahTab('jalur')">
                        <i class="ri-route-line me-1"></i>
                        Jalur
                    </button>
                </li>

                {{-- TAB SISWA --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'siswa' ? 'active' : '' }}"
                        id="edit-tab-siswa"
                        role="tab"
                        aria-controls="edit-content-siswa"
                        aria-selected="{{ $tabAktif === 'siswa' ? 'true' : 'false' }}"
                        wire:click="pindahTab('siswa')">
                        <i class="ri-user-line me-1"></i>
                        Siswa
                    </button>
                </li>

                {{-- TAB ORANG TUA / WALI --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'orang-tua' ? 'active' : '' }}"
                        id="edit-tab-orang-tua"
                        role="tab"
                        aria-controls="edit-content-orang-tua"
                        aria-selected="{{ $tabAktif === 'orang-tua' ? 'true' : 'false' }}"
                        wire:click="pindahTab('orang-tua')">
                        <i class="ri-team-line me-1"></i>
                        Orang Tua / Wali
                    </button>
                </li>

                {{-- TAB ALAMAT --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'alamat' ? 'active' : '' }}"
                        id="edit-tab-alamat"
                        role="tab"
                        aria-controls="edit-content-alamat"
                        aria-selected="{{ $tabAktif === 'alamat' ? 'true' : 'false' }}"
                        wire:click="pindahTab('alamat')">
                        <i class="ri-map-pin-line me-1"></i>
                        Alamat
                    </button>
                </li>

                {{-- TAB PENDIDIKAN --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'pendidikan' ? 'active' : '' }}"
                        id="edit-tab-pendidikan"
                        role="tab"
                        aria-controls="edit-content-pendidikan"
                        aria-selected="{{ $tabAktif === 'pendidikan' ? 'true' : 'false' }}"
                        wire:click="pindahTab('pendidikan')">
                        <i class="ri-graduation-cap-line me-1"></i>
                        Pendidikan
                    </button>
                </li>

                {{-- TAB BERKAS --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'berkas' ? 'active' : '' }}"
                        id="edit-tab-berkas"
                        role="tab"
                        aria-controls="edit-content-berkas"
                        aria-selected="{{ $tabAktif === 'berkas' ? 'true' : 'false' }}"
                        wire:click="pindahTab('berkas')">
                        <i class="ri-folder-line me-1"></i>
                        Berkas
                    </button>
                </li>

                {{-- TAB PERTANYAAN TAMBAHAN --}}
                <li class="nav-item" role="presentation">
                    <button
                        type="button"
                        class="nav-link rounded-pill {{ $tabAktif === 'pertanyaan' ? 'active' : '' }}"
                        id="edit-tab-pertanyaan"
                        role="tab"
                        aria-controls="edit-content-pertanyaan"
                        aria-selected="{{ $tabAktif === 'pertanyaan' ? 'true' : 'false' }}"
                        wire:click="pindahTab('pertanyaan')">
                        <i class="ri-question-answer-line me-1"></i>
                        Pertanyaan Tambahan
                    </button>
                </li>

            </ul>
        </div>

        {{-- KONTEN TAB --}}
        <div
            class="tab-content p-3 p-md-4"
            id="contentEditPendaftaran">

            {{-- LOADING SAAT PINDAH TAB --}}
            <div
                wire:loading.flex
                wire:target="pindahTab"
                class="align-items-center justify-content-center text-center py-5"
                style="min-height: 300px;">

                <div>
                    <div
                        class="spinner-border text-primary mb-3"
                        role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>

                    <p class="text-muted mb-0">
                        Memuat formulir pendaftaran...
                    </p>
                </div>
            </div>

            {{-- LOADING SAAT MEMUAT DATA PENDAFTAR --}}
            <div
                wire:loading.flex
                wire:target="bukaEditPendaftaran"
                class="align-items-center justify-content-center text-center py-5"
                style="min-height: 300px;">

                <div>
                    <div
                        class="spinner-border text-primary mb-3"
                        role="status">
                        <span class="visually-hidden">Memuat...</span>
                    </div>

                    <p class="text-muted mb-0">
                        Memuat data pendaftaran...
                    </p>
                </div>
            </div>

            {{-- FORM EDIT --}}
            <div
                wire:loading.remove
                wire:target="pindahTab,bukaEditPendaftaran">

                <div class="tab-content">

                    {{-- TAB 1: JALUR --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'jalur' ? 'show active' : '' }}"
                        id="edit-content-jalur"
                        role="tabpanel"
                        aria-labelledby="edit-tab-jalur"
                        tabindex="0">

                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-jalur')
                    </div>

                    {{-- TAB 2: SISWA --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'siswa' ? 'show active' : '' }}"
                        id="edit-content-siswa"
                        role="tabpanel"
                        aria-labelledby="edit-tab-siswa"
                        tabindex="0">

                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-siswa')
                    </div>

                    {{-- TAB 3: ORANG TUA / WALI --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'orang-tua' ? 'show active' : '' }}"
                        id="edit-content-orang-tua"
                        role="tabpanel"
                        aria-labelledby="edit-tab-orang-tua"
                        tabindex="0">

                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-orang-tua')
                    </div>

                    {{-- TAB 4: ALAMAT --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'alamat' ? 'show active' : '' }}"
                        id="edit-content-alamat"
                        role="tabpanel"
                        aria-labelledby="edit-tab-alamat"
                        tabindex="0">

                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-alamat')
                    </div>

                    {{-- TAB 5: PENDIDIKAN --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'pendidikan' ? 'show active' : '' }}"
                        id="edit-content-pendidikan"
                        role="tabpanel"
                        aria-labelledby="edit-tab-pendidikan"
                        tabindex="0">

                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-pendidikan')
                    </div>

                    {{-- TAB 6: BERKAS --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'berkas' ? 'show active' : '' }}"
                        id="edit-content-berkas"
                        role="tabpanel"
                        aria-labelledby="edit-tab-berkas"
                        tabindex="0">

                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-berkas')
                    </div>

                    {{-- TAB 7: PERTANYAAN TAMBAHAN --}}
                    <div
                        class="tab-pane fade {{ $tabAktif === 'pertanyaan' ? 'show active' : '' }}"
                        id="edit-content-pertanyaan"
                        role="tabpanel"
                        aria-labelledby="edit-tab-pertanyaan"
                        tabindex="0">

                        @include('livewire.p-p-d-b.admin.pendaftar.partial.tab-pertanyaan')
                    </div>

                </div>
            </div>

        </div>
    </div>

    {{-- FOOTER --}}
    <div class="border-top bg-white p-3">
        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">

            <div class="text-muted small">
                <i class="ri-information-line me-1"></i>
                Periksa kembali perubahan sebelum menyimpan data pendaftaran.
            </div>

            <div class="d-flex gap-2 flex-shrink-0">

                {{-- TUTUP --}}
                <button
                    type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="offcanvas"
                    wire:loading.attr="disabled"
                    wire:target="simpanPerubahan">

                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>

                {{-- SIMPAN PERUBAHAN --}}
                <button
                    type="button"
                    class="btn btn-primary rounded-pill px-4"
                    wire:click="simpanPerubahan"
                    wire:loading.attr="disabled"
                    wire:target="simpanPerubahan">

                    <span wire:loading.remove wire:target="simpanPerubahan">
                        <i class="ri-save-line me-1"></i>
                        Simpan Perubahan
                    </span>

                    <span wire:loading wire:target="simpanPerubahan">
                        <span
                            class="spinner-border spinner-border-sm me-1"
                            role="status"
                            aria-hidden="true"></span>
                        Menyimpan...
                    </span>
                </button>

            </div>
        </div>
    </div>

</div>
