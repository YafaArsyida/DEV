<div>
    @if (! $hasParameters)
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <div class="avatar-md mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-24">
                        <i class="ri-filter-3-line"></i>
                    </span>
                </div>
                <h5 class="mt-2">Pilih Parameter Akademik</h5>
                <p class="text-muted mb-0">
                    Pilih jenjang, tahun ajar, dan periode akademik yang valid serta dapat diakses untuk melihat jadwal mengajar.
                </p>
            </div>
        </div>
    @else
        {{-- RINGKASAN --}}
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small text-uppercase fw-medium mb-1">Total Jadwal</div>
                        <h4 class="fw-bold mb-0">{{ $summary['total'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small text-uppercase fw-medium mb-1">Aktif</div>
                        <h4 class="fw-bold text-success mb-0">{{ $summary['aktif'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small text-uppercase fw-medium mb-1">Belum Berlaku</div>
                        <h4 class="fw-bold text-warning mb-0">{{ $summary['belum_berlaku'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small text-uppercase fw-medium mb-1">Berakhir</div>
                        <h4 class="fw-bold text-secondary mb-0">{{ $summary['berakhir'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small text-uppercase fw-medium mb-1">Guru</div>
                        <h4 class="fw-bold mb-0">{{ $summary['guru'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-xl-2">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small text-uppercase fw-medium mb-1">Kelas</div>
                        <h4 class="fw-bold mb-0">{{ $summary['kelas'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        {{-- PENGATURAN TAMPILAN --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
            <div class="card-header border-bottom bg-white">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-calendar-schedule-line"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1">Pengaturan Jadwal Mengajar</h5>
                            <small class="text-muted">
                                {{ $namaJenjang ?: '-' }}
                                <span class="text-muted mx-1">/</span>{{ $namaTahunAjar ?: '-' }}
                                <span class="text-muted mx-1">/</span>{{ $namaPeriode ?: '-' }}
                            </small>
                        </div>
                    </div>

                    @if ($canManage)
                        <button type="button" class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-2"
                            data-bs-toggle="modal" data-bs-target="#ModalAddJadwal"
                            wire:click.prevent="$emit('showCreateJadwal', {{ (int) $selectedJenjang }}, {{ (int) $selectedTahunAjar }}, {{ (int) $selectedPeriode }})">
                            <i class="ri-add-line"></i>
                            <span>Tambah Jadwal</span>
                        </button>
                    @endif
                </div>
            </div>

            <div class="card-body p-3 p-lg-4">
                {{-- MODE TAMPILAN --}}
                <ul class="nav nav-pills nav-justified gap-1 flex-wrap mb-3">
                    <li class="nav-item">
                        <button type="button" class="nav-link rounded-pill {{ $modeTampilan === 'kelas' ? 'active' : '' }}"
                            wire:click="setModeTampilan('kelas')">
                            <i class="ri-building-line me-1"></i> Per Kelas
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link rounded-pill {{ $modeTampilan === 'guru' ? 'active' : '' }}"
                            wire:click="setModeTampilan('guru')">
                            <i class="ri-user-line me-1"></i> Per Guru
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link rounded-pill {{ $modeTampilan === 'semua' ? 'active' : '' }}"
                            wire:click="setModeTampilan('semua')">
                            <i class="ri-layout-grid-line me-1"></i> Seluruh Sekolah
                        </button>
                    </li>
                </ul>

                <div class="row g-3 align-items-end">
                    @if ($modeTampilan === 'kelas')
                        <div class="col-xl-3 col-md-6">
                            <label class="form-label small text-muted text-uppercase fw-medium mb-2">Kelas</label>
                            <select wire:model="selectedKelas" class="form-select">
                                <option value="">Semua Kelas</option>
                                @foreach ($classOptions as $class)
                                    <option value="{{ $class->ms_kelas_id }}">{{ $class->nama_kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                    @elseif ($modeTampilan === 'guru')
                        <div class="col-xl-3 col-md-6">
                            <label class="form-label small text-muted text-uppercase fw-medium mb-2">Guru</label>
                            <select wire:model="selectedGuru" class="form-select">
                                <option value="">Semua Guru</option>
                                @foreach ($teacherOptions as $teacher)
                                    <option value="{{ $teacher->ms_pegawai_id }}">{{ $teacher->nama_pegawai }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="{{ $modeTampilan === 'semua' ? 'col-xl-7' : 'col-xl-5' }} col-md-6">
                        <label for="searchJadwal" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                        <div class="search-box">
                            <input type="search" id="searchJadwal" class="form-control search"
                                wire:model.debounce.300ms="search"
                                placeholder="Cari guru, kelas, mata pelajaran, atau keterangan...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>

                    <div class="col-xl-2 col-md-6">
                        <button type="button" wire:click="resetFilter" class="btn btn-soft-secondary w-100">
                            <i class="ri-refresh-line me-1"></i> Reset
                        </button>
                    </div>
                </div>

                {{-- FILTER HARI --}}
                <div class="border-top pt-3 mt-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="text-muted small text-uppercase fw-medium me-1">Hari</span>
                        <button type="button"
                            class="btn btn-sm rounded-pill {{ $selectedHari === '' ? 'btn-primary' : 'btn-soft-secondary' }}"
                            wire:click="setHari('')">Semua</button>
                        @foreach ($hariOptions as $value => $label)
                            <button type="button"
                                class="btn btn-sm rounded-pill {{ $selectedHari === $value ? 'btn-primary' : 'btn-soft-secondary' }}"
                                wire:click="setHari('{{ $value }}')">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div wire:loading.delay wire:target="search, selectedKelas, selectedGuru, setHari, setModeTampilan, resetFilter"
                    class="text-muted small mt-3">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Memuat jadwal...
                </div>
            </div>
        </div>

        {{-- MATRIKS JADWAL --}}
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="card-header bg-white border-bottom py-3 px-3 px-lg-4">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2">
                    <div>
                        <h5 class="fw-bold mb-1">Jadwal Mingguan</h5>
                        <p class="text-muted small mb-0">
                            Menampilkan {{ $totalFiltered }} jadwal sesuai filter.
                            Klik kartu jadwal untuk melihat detail.
                        </p>
                    </div>
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                            @if ($modeTampilan === 'kelas')
                                <i class="ri-building-line me-1"></i> Per Kelas
                            @elseif ($modeTampilan === 'guru')
                                <i class="ri-user-line me-1"></i> Per Guru
                            @else
                                <i class="ri-layout-grid-line me-1"></i> Seluruh Sekolah
                            @endif
                        </span>
                        @if ($selectedHari !== '')
                            <span class="badge bg-light text-dark rounded-pill px-3 py-2">
                                <i class="ri-calendar-line me-1"></i> {{ $hariOptions[$selectedHari] ?? $selectedHari }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="card-body p-3 p-lg-4">
                @include('livewire.akademik.jadwal-mengajar._matrix', [
                    'clickable' => true,
                    'showTeacher' => $modeTampilan !== 'guru',
                ])

                <div class="border-top pt-3 mt-3">
                    <div class="d-flex align-items-center gap-2 text-muted small">
                        <i class="ri-information-line fs-16"></i>
                        <span>Jadwal divalidasi terhadap bentrok guru, bentrok kelas, waktu, dan rentang tanggal.</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL DETAIL --}}
        <div wire:ignore.self class="modal fade" id="ModalDetailJadwal" tabindex="-1"
            aria-labelledby="ModalDetailJadwalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    <div class="modal-header bg-primary-subtle border-0 p-3 p-lg-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm flex-shrink-0">
                                <span class="avatar-title rounded-3 bg-primary text-white">
                                    <i class="ri-calendar-event-line fs-4"></i>
                                </span>
                            </div>
                            <div>
                                <h5 class="modal-title fw-bold mb-1" id="ModalDetailJadwalLabel">Detail Jadwal Mengajar</h5>
                                <p class="text-muted small mb-0">Informasi lengkap kegiatan pembelajaran</p>
                            </div>
                        </div>
                        <button type="button" class="btn btn-light btn-icon rounded-circle ms-auto"
                            data-bs-dismiss="modal" aria-label="Tutup">
                            <i class="ri-close-line fs-18"></i>
                        </button>
                    </div>

                    <div class="modal-body p-3 p-lg-4">
                        <div class="mb-4">
                            <div class="text-muted small text-uppercase fw-medium mb-2">Mata Pelajaran</div>
                            <h5 class="fw-bold text-dark mb-0">{{ $detail['mapel'] ?? '-' }}</h5>
                            <div class="small text-muted">{{ $detail['kode_mapel'] ?? '-' }}</div>
                        </div>

                        <div class="bg-light rounded-3 p-3">
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="avatar-xs flex-shrink-0">
                                    <span class="avatar-title bg-white text-primary rounded-2"><i class="ri-user-line"></i></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small mb-1">Guru Pengajar</div>
                                    <div class="fw-medium text-dark">{{ $detail['guru'] ?? '-' }}</div>
                                    <div class="small text-muted">{{ $detail['nip'] ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="avatar-xs flex-shrink-0">
                                    <span class="avatar-title bg-white text-primary rounded-2"><i class="ri-community-line"></i></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small mb-1">Kelas</div>
                                    <div class="fw-medium text-dark">{{ $detail['kelas'] ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="avatar-xs flex-shrink-0">
                                    <span class="avatar-title bg-white text-primary rounded-2"><i class="ri-government-line"></i></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small mb-1">Jenjang / Tahun Ajar / Periode</div>
                                    <div class="fw-medium text-dark">
                                        {{ $detail['jenjang'] ?? '-' }}
                                        <span class="text-muted mx-1">/</span>{{ $detail['tahun_ajar'] ?? '-' }}
                                        <span class="text-muted mx-1">/</span>{{ $detail['periode'] ?? '-' }}
                                    </div>
                                    <div class="small text-muted">Status penugasan: {{ $detail['penugasan_status'] ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3 mb-3">
                                <div class="avatar-xs flex-shrink-0">
                                    <span class="avatar-title bg-white text-primary rounded-2"><i class="ri-calendar-line"></i></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small mb-1">Hari dan Rentang Tanggal</div>
                                    <div class="fw-medium text-dark">{{ $detail['hari'] ?? '-' }}</div>
                                    <div class="small text-muted">{{ $detail['tanggal'] ?? '-' }}</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-start gap-3">
                                <div class="avatar-xs flex-shrink-0">
                                    <span class="avatar-title bg-white text-primary rounded-2"><i class="ri-time-line"></i></span>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="text-muted small mb-1">Waktu Mengajar</div>
                                    <div class="fw-medium text-dark">{{ $detail['jam'] ?? '-' }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <span class="text-muted small">Status Jadwal</span>
                            <span class="badge rounded-pill {{ ($detail['status'] ?? '') === 'AKTIF' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }}">
                                {{ $detail['status_label'] ?? '-' }}
                            </span>
                        </div>

                        <div class="mt-3">
                            <div class="text-muted small text-uppercase fw-medium mb-1">Keterangan</div>
                            <p class="mb-0 text-dark">{{ $detail['deskripsi'] ?? '-' }}</p>
                        </div>
                    </div>

                    <div class="modal-footer border-top p-3">
                        <button type="button" class="btn btn-light rounded-pill px-3" data-bs-dismiss="modal">
                            Tutup
                        </button>

                        @if ($canManage && $detailId)
                            <button type="button" class="btn btn-soft-danger rounded-pill px-3"
                                data-bs-toggle="modal" data-bs-target="#ModalDeleteJadwal" data-bs-dismiss="modal"
                                wire:click="$emit('confirmDeleteJadwal', {{ $detailId }}, {{ (int) $selectedJenjang }}, {{ (int) $selectedTahunAjar }}, {{ (int) $selectedPeriode }})">
                                <i class="ri-delete-bin-6-line me-1"></i> Hapus
                            </button>
                            <button type="button" class="btn btn-primary rounded-pill px-3"
                                data-bs-toggle="modal" data-bs-target="#ModalEditJadwal" data-bs-dismiss="modal"
                                wire:click="$emit('loadJadwal', {{ $detailId }}, {{ (int) $selectedJenjang }}, {{ (int) $selectedTahunAjar }}, {{ (int) $selectedPeriode }})">
                                <i class="ri-edit-line me-1"></i> Ubah
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
