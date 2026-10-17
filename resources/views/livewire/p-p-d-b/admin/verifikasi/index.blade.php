
<div>
    <div class="card border-0 shadow-sm rounded-4">
        {{-- Header --}}
        <div class="card-header bg-transparent border-bottom">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-search-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">Antrean Verifikasi</h5>
                        <small class="text-muted">
                            Periksa data dan dokumen calon siswa sebelum memberikan keputusan.
                        </small>
                    </div>
                </div>

                <span class="badge bg-warning-subtle text-warning rounded-pill px-3 py-2">
                    <i class="ri-time-line me-1"></i>
                    {{ number_format($jumlahMenunggu) }} Menunggu
                </span>
            </div>
        </div>

        <div class="card-body">
            {{-- Filter --}}
            <div class="row g-3 mb-4">
                {{-- Pencarian --}}
                <div class="col-xl-5 col-md-6">
                    <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                        Pencarian
                    </label>

                    <div class="search-box">
                        <input
                            type="search"
                            class="form-control search"
                            placeholder="Nama atau nomor pendaftaran"
                            wire:model.debounce.400ms="cari">

                        <i class="mdi mdi-magnify search-icon"></i>
                    </div>
                </div>

                {{-- Status --}}
                <div class="col-xl-3 col-md-6">
                    <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                        Status Verifikasi
                    </label>

                    <select class="form-select" wire:model="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="pengecekan">Pengecekan</option>
                        <option value="perbaikan">Perlu Perbaikan</option>
                        <option value="diterima">Diterima</option>
                    </select>
                </div>

                {{-- Kelengkapan dokumen --}}
                <div class="col-xl-2 col-md-6">
                    <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                        Kelengkapan Dokumen
                    </label>

                    <select class="form-select" wire:model="filterDokumen">
                        <option value="">Semua Dokumen</option>
                        <option value="lengkap">Lengkap</option>
                        <option value="belum_lengkap">Belum Lengkap</option>
                    </select>
                </div>

                {{-- Reset --}}
                <div class="col-xl-2 col-md-6 d-flex align-items-end">
                    <button
                        type="button"
                        class="btn btn-light w-100"
                        wire:click="resetFilter">
                        <i class="ri-refresh-line me-1"></i>
                        Reset Filter
                    </button>
                </div>
            </div>

            {{-- Indikator loading --}}
            <div
                wire:loading.delay
                wire:target="cari,filterStatus,filterDokumen,resetFilter,gotoPage,nextPage,previousPage"
                class="text-muted small mb-3">
                <span class="spinner-border spinner-border-sm me-1"></span>
                Memuat antrean verifikasi...
            </div>

            {{-- Tabel --}}
            <div class="table-responsive">
                <table class="table table-hover table-nowrap align-middle">
                    <thead class="table-light">
                        <tr class="text-uppercase">
                            <th class="text-center" style="width: 60px;">No.</th>
                            <th>Calon Siswa</th>
                            <th>Nomor Pendaftaran</th>
                            <th>Jalur</th>
                            <th>Dokumen</th>
                            <th>Tanggal Dikirim</th>
                            <th>Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($antrean as $item)
                            <tr wire:key="verifikasi-{{ $item->ppdb_pendaftaran_id }}">
                                {{-- Nomor --}}
                                <td class="text-center text-muted">
                                    {{ $antrean->firstItem() + $loop->index }}
                                </td>

                                {{-- Calon siswa --}}
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-sm flex-shrink-0">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                                <i class="ri-user-line fs-18"></i>
                                            </div>
                                        </div>

                                        <div>
                                            <h6 class="mb-1 fw-semibold">
                                                {{ $item->ppdb_calon_siswa->nama_siswa ?? 'Nama belum tersedia' }}
                                            </h6>

                                            <small class="text-muted">
                                                Calon peserta didik
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Nomor pendaftaran --}}
                                <td>
                                    <span class="fw-medium">
                                        {{ $item->nomor_pendaftaran }}
                                    </span>
                                </td>

                                {{-- Jalur --}}
                                <td>
                                    <span>
                                        {{ $item->ppdb_jalur->nama_jalur ?? '-' }}
                                    </span>
                                </td>

                                {{-- Dokumen --}}
                                <td>
                                    @php
                                        $jumlahBerkas = $item->ppdb_berkas->count();
                                    @endphp

                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="ri-file-list-3-line text-primary fs-18"></i>

                                        <span class="fw-medium">
                                            {{ $jumlahBerkas }} berkas
                                        </span>
                                    </div>

                                    <small class="text-muted">
                                        Dokumen terunggah
                                    </small>
                                </td>

                                {{-- Tanggal dikirim --}}
                                <td>
                                    <span>
                                        {{ $item->updated_at
                                            ? $item->updated_at->format('d M Y')
                                            : '-' }}
                                    </span>
                                </td>

                                {{-- Status --}}
                                <td>
                                    @php
                                        $statusTampilan = [
                                            'draft' => [
                                                'label' => 'Draft',
                                                'warna' => 'secondary',
                                                'ikon' => 'ri-draft-line',
                                            ],
                                            'pengecekan' => [
                                                'label' => 'Pengecekan',
                                                'warna' => 'warning',
                                                'ikon' => 'ri-time-line',
                                            ],
                                            'perbaikan' => [
                                                'label' => 'Perlu Perbaikan',
                                                'warna' => 'danger',
                                                'ikon' => 'ri-error-warning-line',
                                            ],
                                            'diterima' => [
                                                'label' => 'Diterima',
                                                'warna' => 'success',
                                                'ikon' => 'ri-checkbox-circle-line',
                                            ],
                                        ];

                                        $tampilan = $statusTampilan[$item->status] ?? [
                                            'label' => ucfirst($item->status ?? 'Tidak diketahui'),
                                            'warna' => 'secondary',
                                            'ikon' => 'ri-information-line',
                                        ];
                                    @endphp

                                    <span class="badge bg-{{ $tampilan['warna'] }}-subtle text-{{ $tampilan['warna'] }} rounded-pill px-3 py-2">
                                        <i class="{{ $tampilan['ikon'] }} me-1"></i>
                                        {{ $tampilan['label'] }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button
                                            type="button"
                                            class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                            data-bs-toggle="offcanvas"
                                            data-bs-target="#offcanvasPemeriksaanPendaftaran"
                                            title="Periksa Dokumen Pendaftaran"
                                            wire:click.prevent="$emit('bukaPemeriksaan', {{ $item->ppdb_pendaftaran_id }})">

                                            <i class="ri-eye-line align-bottom me-1"></i>
                                            Periksa
                                        </button>

                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title bg-light text-muted rounded-circle fs-1">
                                            <i class="ri-file-search-line"></i>
                                        </div>
                                    </div>

                                    <h6 class="fw-semibold mb-1">
                                        Pendaftar tidak ditemukan
                                    </h6>

                                    <p class="text-muted mb-3">
                                        Tidak ada pendaftar yang sesuai dengan pencarian atau filter.
                                    </p>

                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light"
                                        wire:click="resetFilter">
                                        <i class="ri-refresh-line me-1"></i>
                                        Reset Filter
                                    </button>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Footer dan pagination --}}
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mt-3">
                <div class="text-muted small">
                    Menampilkan
                    <span class="fw-medium">{{ $antrean->firstItem() ?? 0 }}</span>
                    –
                    <span class="fw-medium">{{ $antrean->lastItem() ?? 0 }}</span>
                    dari
                    <span class="fw-medium">{{ $antrean->total() }}</span>
                    pendaftar.
                </div>

                <div>
                    {{ $antrean->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
