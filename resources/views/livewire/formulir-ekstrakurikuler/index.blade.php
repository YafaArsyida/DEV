<div class="container">
    {{-- Header --}}
    <div class="row justify-content-center mb-5">
        <div class="col-lg-8 text-center">
            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2 mb-3">
                <i class="ri-pencil-line align-middle me-1"></i>
                Pendaftaran Ekstrakurikuler
            </span>

            <h2 class="fw-semibold mb-3">
                Temukan Kegiatan yang
                <span class="text-danger">Kamu Sukai</span>
            </h2>

            <p class="text-muted mb-0">
                Pilih siswa dan kegiatan ekstrakurikuler yang ingin diikuti.
                Yuk, mulai perjalanan untuk mengembangkan minat dan bakat!
            </p>

        </div>
    </div>
    {{-- Form Card --}}
    <div class="row justify-content-center">
        <div class="col-lg-7 col-xl-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4 p-lg-5">
                    {{-- Step 1 --}}
                    <div class="mb-4">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                    <span class="fw-semibold">01</span>
                                </div>
                            </div>

                            <div>
                                <h5 class="mb-1 fw-semibold">
                                    Pilih Siswa
                                </h5>
                                <p class="text-muted mb-0 fs-13">
                                    Cari nama siswa yang akan mengikuti ekskul.
                                </p>
                            </div>
                        </div>

                        {{-- Search Siswa --}}
                        <div class="position-relative">
                            <label class="form-label text-muted fs-13">
                                Nama Siswa
                            </label>

                            <div class="input-group">
                                <span class="input-group-text bg-white">
                                    <i class="ri-search-line text-muted"></i>
                                </span>
                                <input type="text" class="form-control" placeholder="Ketik nama siswa..." wire:model.debounce.300ms="search">
                            </div>


                            {{-- Search Result --}}
                            @if (!empty($search))
                                <div class="list-group mt-2 shadow-sm rounded-3 overflow-hidden">
                                    @forelse ($siswa as $item)
                                        <button type="button"
                                            class="list-group-item list-group-item-action p-3"
                                            wire:click="siswaSelected('{{ $item->ms_penempatan_siswa_id }}')">

                                            <div class="d-flex align-items-center">

                                                <div class="avatar-xs me-3">
                                                    <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                                        <i class="ri-user-line"></i>
                                                    </div>
                                                </div>

                                                <div class="text-start">

                                                    <h6 class="fs-12 fw-medium mb-1">
                                                        {{ $item->ms_siswa->nama_siswa }}
                                                    </h6>

                                                    <small class="text-muted">
                                                        Kelas {{ $item->ms_kelas->nama_kelas }}
                                                    </small>
                                                </div>
                                            </div>
                                        </button>
                                    @empty
                                        <div class="list-group-item text-muted text-center py-3">
                                            <i class="ri-user-search-line fs-20 d-block mb-1"></i>
                                            Siswa tidak ditemukan
                                        </div>
                                    @endforelse
                                </div>
                            @endif
                        </div>

                        {{-- Selected Student --}}
                        @if (!empty($nama_siswa))
                            <div class="alert alert-danger-subtle border-0 rounded-3 mt-3 mb-0">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-xs me-3">
                                        <div class="avatar-title bg-danger text-white rounded-circle">
                                            <i class="ri-user-check-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <small class="text-muted d-block">
                                            Siswa terpilih
                                        </small>

                                        <strong class="text-dark">
                                            {{ $nama_siswa }}
                                        </strong>

                                        <small class="text-muted">
                                            · Kelas {{ $nama_kelas }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Divider --}}
                    <div class="border-top my-4"></div>

                    {{-- Step 2 --}}
                    <div class="mb-4">

                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                    <span class="fw-semibold">02</span>
                                </div>
                            </div>

                            <div>
                                <h5 class="mb-1 fw-semibold">
                                    Pilih Ekstrakurikuler
                                </h5>

                                <p class="text-muted mb-0 fs-13">
                                    Pilih kegiatan yang paling sesuai dengan minat.
                                </p>
                            </div>
                        </div>


                        {{-- ================================================= --}}
                        {{-- KONDISI 1 & 3 : SELECT EKSKUL --}}
                        {{-- ================================================= --}}

                        <select
                            wire:model="selectedEkstrakurikuler"
                            class="form-select form-select-lg"
                            @disabled($sudahTerdaftar)
                        >

                            @if (!$sudahTerdaftar)
                                <option value="">
                                    Pilih ekstrakurikuler
                                </option>
                            @endif

                            @foreach ($select_ekstrakurikuler as $item)
                                <option value="{{ $item->ms_ekstrakurikuler_id }}">
                                    {{ $item->nama_ekstrakurikuler }}
                                </option>
                            @endforeach

                        </select>


                        {{-- ================================================= --}}
                        {{-- KONDISI 2 : BERHASIL MENDAFTAR --}}
                        {{-- ================================================= --}}

                        @if ($registrationSuccess)

                            <div class="card border-0 shadow-sm rounded-4 mt-4 overflow-hidden">

                                <div class="card-body p-4 text-center">

                                    {{-- Success Icon --}}
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                            <i class="ri-checkbox-circle-fill fs-1"></i>
                                        </div>
                                    </div>

                                    <h5 class="fw-bold mb-2">
                                        Pendaftaran Berhasil!
                                    </h5>

                                    <p class="text-muted mb-4">
                                        Siswa berhasil terdaftar pada ekstrakurikuler.
                                    </p>

                                    <div class="bg-light rounded-3 p-3 text-start">

                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">
                                                Nama Siswa
                                            </span>

                                            <span class="fw-semibold text-end">
                                                {{ $nama_siswa }}
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted">
                                                Kelas
                                            </span>

                                            <span class="fw-semibold">
                                                {{ $nama_kelas }}
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <span class="text-muted">
                                                Ekstrakurikuler
                                            </span>

                                            <span class="fw-semibold text-danger">
                                                {{ $nama_ekstrakurikuler_terdaftar }}
                                            </span>
                                        </div>

                                    </div>

                                    <div class="alert alert-success border-0 mt-3 mb-0 text-start">
                                        <i class="ri-information-line me-1"></i>

                                        Pendaftaran siswa telah berhasil disimpan.
                                    </div>

                                </div>

                            </div>


                        {{-- ================================================= --}}
                        {{-- KONDISI 3 : SUDAH TERDAFTAR SEBELUMNYA --}}
                        {{-- ================================================= --}}

                        @elseif ($sudahTerdaftar)

                            <div class="alert alert-info border-0 rounded-3 mt-3 mb-0">

                                <div class="d-flex align-items-start gap-2">

                                    <i class="ri-information-fill fs-5"></i>

                                    <div>
                                        <div class="fw-semibold">
                                            Siswa sudah terdaftar
                                        </div>

                                        <div class="fs-13 mt-1">
                                            <strong>{{ $nama_siswa }}</strong>
                                            telah terdaftar pada ekstrakurikuler
                                            <strong>
                                                {{ $nama_ekstrakurikuler_terdaftar }}
                                            </strong>.
                                        </div>
                                    </div>

                                </div>

                            </div>

                        @endif

                    </div>


                    {{-- ================================================= --}}
                    {{-- SUBMIT HANYA JIKA BELUM TERDAFTAR --}}
                    {{-- ================================================= --}}

                    @if (!$sudahTerdaftar)

                        <button
                            type="button"
                            wire:click="daftar"
                            wire:loading.attr="disabled"
                            class="btn btn-danger btn-lg rounded-pill w-100"
                        >

                            <span wire:loading.remove>
                                <i class="ri-checkbox-circle-line me-1 align-middle"></i>
                                Daftar Sekarang
                            </span>

                            <span wire:loading>
                                <span class="spinner-border spinner-border-sm me-1"></span>
                                Menyimpan pendaftaran...
                            </span>

                        </button>

                    @endif
                </div>
            </div>
        </div>
    </div>
</div>