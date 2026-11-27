<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header border-bottom">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                        <i class="mdi mdi-account-cog-outline"></i>
                    </div>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Penugasan Guru</h5>
                    <small class="text-muted">Atur guru pengampu, mata pelajaran, kelas, dan tahun ajaran.</small>
                    {{-- @if ($hasParameters)
                        <div class="small text-primary mt-1">
                            <i class="ri-government-line me-1"></i>{{ $namaJenjang }}
                            <span class="text-muted mx-1">/</span>{{ $namaTahunAjar }}
                        </div>
                    @endif --}}
                </div>
            </div>

            @if ($hasParameters)
                <button type="button" class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-2"
                    data-bs-toggle="modal" data-bs-target="#ModalAddPenugasanGuru"
                    wire:click.prevent="$emit('showCreatePenugasanGuru', {{ (int) $selectedJenjang }}, {{ (int) $selectedTahunAjar }})">
                    <i class="ri-add-line"></i>
                    <span>Penugasan Baru</span>
                </button>
            @endif
        </div>
    </div>

    <div class="card-body">
        @if ($hasParameters)
            <div class="row g-3 mb-3">
                <div class="col-12 col-lg-6">
                    <label for="searchPenugasanGuru" class="form-label small text-muted text-uppercase fw-medium mb-2">
                        Pencarian
                    </label>
                    <div class="search-box">
                        <input type="search" id="searchPenugasanGuru" class="form-control search"
                            wire:model.debounce.300ms="search"
                            placeholder="Cari guru, kelas, mata pelajaran, atau tahun ajaran...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="filterKelasPenugasanGuru" class="form-label small text-muted text-uppercase fw-medium mb-2">
                        Kelas
                    </label>
                    <select id="filterKelasPenugasanGuru" class="form-select"
                        wire:model="selectedKelas">
                        <option value="">Semua kelas</option>
                        @foreach ($classFilterOptions as $class)
                            <option value="{{ $class->ms_kelas_id }}">{{ $class->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label for="filterGuruPenugasanGuru" class="form-label small text-muted text-uppercase fw-medium mb-2">
                        Guru
                    </label>
                    <select id="filterGuruPenugasanGuru" class="form-select"
                        wire:model="selectedGuru">
                        <option value="">Semua guru</option>
                        @foreach ($teacherFilterOptions as $teacher)
                            <option value="{{ $teacher->ms_pegawai_id }}">
                                {{ $teacher->nama_pegawai }}{{ $teacher->nip ? ' - ' . $teacher->nip : '' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div wire:loading.delay wire:target="search, selectedKelas, selectedGuru, updateParameters" class="text-muted small mb-3">
                <span class="spinner-border spinner-border-sm me-1"></span>
                Memuat data penugasan...
            </div>

            <div class="live-preview">
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" width="48">No.</th>
                                <th scope="col" class="text-center">Hapus</th>
                                <th scope="col">Guru</th>
                                <th scope="col">Mata Pelajaran</th>
                                <th scope="col">Kelas</th>
                                <th scope="col">Tahun Ajaran</th>
                                <th scope="col">Status</th>
                                <th scope="col" class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($assignments as $assignment)
                                <tr wire:key="penugasan-guru-{{ $assignment->ms_penugasan_guru_id }}">
                                    <td>{{ ($assignments->firstItem() ?? 0) + $loop->index }}.</td>
                                    <td class="text-center">
                                        <a href="#ModalDeletePenugasanGuru" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn"
                                            wire:click.prevent="$emit('confirmDeletePenugasanGuru', {{ $assignment->ms_penugasan_guru_id }}, {{ (int) $selectedJenjang }}, {{ (int) $selectedTahunAjar }})"
                                            data-bs-trigger="hover" data-bs-placement="top" title="Hapus Penugasan">
                                            <i class="ri-delete-bin-5-fill fs-14"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $assignment->ms_pegawai->nama_pegawai }}</span>
                                        <p class="text-muted mb-0">{{ $assignment->ms_pegawai->nip ?: 'NIP belum diisi' }}</p>
                                    </td>
                                    <td>
                                        <span class="fw-medium">{{ $assignment->ms_mata_pelajaran?->nama_mapel ?? 'Tanpa mata pelajaran' }}</span>
                                        <p class="text-muted mb-0">
                                            {{ $assignment->ms_mata_pelajaran?->kode_mapel ?: ($assignment->ms_mata_pelajaran ? 'Tanpa kode' : 'Wali kelas / tugas khusus') }}
                                        </p>
                                    </td>
                                    <td>{{ $assignment->ms_kelas->nama_kelas }}</td>
                                    <td>
                                        <span class="fw-medium">
                                            {{ $assignment->ms_tahun_ajar?->nama_tahun_ajar
                                                ?? $assignment->ms_kelas?->ms_tahun_ajar?->nama_tahun_ajar
                                                ?? '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $assignment->status === 'AKTIF' ? 'success' : 'secondary' }}-subtle text-{{ $assignment->status === 'AKTIF' ? 'success' : 'secondary' }}">
                                            {{ $assignment->status === 'AKTIF' ? 'Aktif' : 'Nonaktif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="#ModalDetailPenugasanGuru" data-bs-toggle="modal"
                                                class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                                title="Detail Penugasan"
                                                wire:click.prevent="$emit('loadDetailPenugasanGuru', {{ $assignment->ms_penugasan_guru_id }}, {{ (int) $selectedJenjang }}, {{ (int) $selectedTahunAjar }})">
                                                <i class="ri-eye-line me-1"></i> Detail
                                            </a>
                                            <a href="#ModalEditPenugasanGuru" data-bs-toggle="modal"
                                                class="btn btn-primary btn-sm rounded-pill px-3"
                                                wire:click="$emit('loadPenugasanGuru', {{ $assignment->ms_penugasan_guru_id }}, {{ (int) $selectedJenjang }}, {{ (int) $selectedTahunAjar }})">
                                                <i class="ri-pencil-line me-1"></i> Ubah
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="text-center py-4">
                                            <div class="avatar-md mx-auto mb-3">
                                                <span class="avatar-title rounded-circle bg-light text-muted fs-24">
                                                    <i class="ri-user-settings-line"></i>
                                                </span>
                                            </div>
                                            <h5 class="mt-2">Belum Ada Penugasan Guru</h5>
                                            <p class="text-muted mb-0">
                                                Buat penugasan pertama untuk {{ $namaJenjang }} tahun ajaran {{ $namaTahunAjar }}.
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-semibold">{{ $assignments->firstItem() ?? 0 }}</span>
                            –
                            <span class="fw-semibold">{{ $assignments->lastItem() ?? 0 }}</span>
                            dari
                            <span class="fw-semibold">{{ $assignments->total() }}</span>
                            penugasan
                        </div>
                        {{ $assignments->links() }}
                    </div>
                </div>
            </div>
        @else
            <div class="text-center py-4">
                <div class="avatar-md mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-primary-subtle text-primary fs-24">
                        <i class="ri-filter-3-line"></i>
                    </span>
                </div>
                <h5 class="mt-2">Pilih Jenjang dan Tahun Ajar</h5>
                <p class="text-muted mb-0">Pilih parameter yang valid dan dapat diakses untuk mengelola penugasan guru.</p>
            </div>
        @endif
    </div>
</div>
