<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-transparent border-bottom">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-user-search-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Semua Pendaftar</h5>
                <small class="text-muted">
                    Kelola dan periksa data calon siswa baru.
                </small>
            </div>
        </div>
    </div>

    <div class="card-body">
        {{-- Filter --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-6 col-md-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>

                <div class="search-box">
                    <input
                        type="search"
                        class="form-control search"
                        placeholder="Nama atau nomor pendaftaran"
                        wire:model.debounce.400ms="search">

                    <i class="mdi mdi-magnify search-icon"></i>
                </div>
            </div>

            <div class="col-xl-2 col-md-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">Sumber</label>
                <select class="form-select" wire:model="filterSumber">
                    <option value="">Semua Sumber</option>
                    <option value="online">Online</option>
                    <option value="dibantu_panitia">Dibantu Panitia</option>
                </select>
            </div>

            <div class="col-xl-2 col-md-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">Status</label>

                <select class="form-select" wire:model="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="pengecekan">Pengecekan</option>
                    <option value="perbaikan">Perlu Perbaikan</option>
                    <option value="diterima">Diterima</option>
                </select>
            </div>

            <div class="col-xl-2 col-md-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">Jalur</label>

                <select class="form-select" wire:model="filterJalur">
                    <option value="">Semua Jalur</option>

                    @foreach ($daftarJalur as $jalur)
                        <option value="{{ $jalur->ppdb_jalur_id }}">
                            {{ $jalur->nama_jalur }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Indikator loading --}}
        <div wire:loading.delay wire:target="search,filterStatus,filterJalur,filterSumber,gotoPage,nextPage,previousPage"
            class="text-muted small mb-3">
            <span class="spinner-border spinner-border-sm me-1"></span>
            Memuat data pendaftar...
        </div>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr class="text-uppercase">
                        <th class="text-center" style="width: 60px;">No.</th>
                        <th>Calon Siswa</th>
                        <th>Nomor Pendaftaran</th>
                        <th>Jalur</th>
                        <th>Sumber Pendaftaran</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th class="text-center" style="width: 110px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pendaftar as $item)
                        <tr wire:key="pendaftar-{{ $item->ppdb_pendaftaran_id }}">
                            <td class="text-center text-muted">
                                {{ $pendaftar->firstItem() + $loop->index }}
                            </td>

                            <td class="fw-medium">
                                {{ $item->ppdb_calon_siswa->nama_siswa ?? 'Data siswa belum lengkap' }}
                            </td>
                            <td>
                                {{ $item->nomor_pendaftaran }}
                            </td>
                            <td>
                                {{ $item->ppdb_jalur->nama_jalur ?? '-' }}
                            </td>

                            <td>
                                @if ($item->ms_pengguna)
                                    @if ($item->ms_pengguna->peran === 'ORANG_TUA')
                                        Orang Tua {{ $item->ms_pengguna->nama ?? '-' }}
                                    @elseif ($item->ms_pengguna->peran != 'ORANG_TUA')
                                        Dibantu Panitia {{ $item->ms_pengguna->nama ?? '-' }}
                                    @else
                                        {{ $item->ms_pengguna->peran ?? 'Tidak diketahui' }}
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->created_at, 'd F Y') }}
                            </td>

                           
                            <td class="text-nowrap
                                {{ $item->status === 'draft' ? 'text-secondary' : '' }}
                                {{ $item->status === 'pengecekan' ? 'text-warning' : '' }}
                                {{ $item->status === 'diterima' ? 'text-success' : '' }}
                                {{ $item->status === 'perbaikan' ? 'text-danger' : '' }}">

                                <i class="ri-{{
                                    $item->status === 'draft'
                                        ? 'draft-line'
                                        : ($item->status === 'pengecekan'
                                            ? 'time-line'
                                            : ($item->status === 'diterima'
                                                ? 'check-double-line'
                                                : 'edit-line'))
                                }} fs-17 align-middle me-1"></i>

                                {{ [
                                    'draft' => 'Draft',
                                    'pengecekan' => 'Pengecekan',
                                    'diterima' => 'Diterima',
                                    'perbaikan' => 'Perlu Perbaikan',
                                ][$item->status] ?? '-' }}
                            </td>


                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    {{-- Detail --}}
                                    <a href="#ModalDetailPendaftar" data-bs-toggle="modal" class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                        title="Detail Pendaftar" wire:click.prevent="$emit('loadDetailPendaftar', {{ $item->ppdb_pendaftaran_id }} )">
                                        <i class="ri-eye-line me-1"></i> Detail
                                    </a>
                                    {{-- WA --}}
                                    
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="avatar-md mx-auto mb-3">
                                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                        <i class="ri-user-search-line"></i>
                                    </div>
                                </div>

                                <h6 class="mb-1">Pendaftar tidak ditemukan</h6>

                                <p class="text-muted mb-3">
                                    Tidak ada data yang sesuai dengan pencarian atau filter.
                                </p>

                                @if ($search || $filterStatus || $filterJalur || $filterSumber)
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light"
                                        wire:click="resetFilter">
                                        Reset Filter
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination dinamis --}}
        @if ($pendaftar->total() > 0)
            <div class="d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3 mt-4">
                <div class="text-muted small">
                    Menampilkan {{ $pendaftar->firstItem() }}–{{ $pendaftar->lastItem() }}
                    dari {{ $pendaftar->total() }} pendaftar
                </div>

                <div>
                    {{ $pendaftar->links() }}
                </div>
            </div>
        @endif
    </div>
</div>