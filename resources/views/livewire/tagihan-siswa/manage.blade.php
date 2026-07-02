<div>
    <div wire:ignore.self class="offcanvas offcanvas-top" id="offcanvasManage" aria-labelledby="offcanvasManageLabel" style="min-height:100vh;">
        <div class="offcanvas-header border-0" style="background: linear-gradient(135deg, #405189 0%, #08a88a 100%);">
            <div>
                <h5 class="offcanvas-title text-white fw-semibold" id="offcanvasManageLabel">Kelola Tagihan Siswa</h5>
                <p class="text-white-50 mb-0">Gunakan filter dan pilihan cepat untuk mengelola tagihan secara efisien.</p>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <div class="row g-4">
                <div class="col-xxl-9">
                    <div class="card shadow-sm">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                                <div>
                                    <h4 class="card-title mb-1">Daftar Tagihan</h4>
                                    <p class="text-muted mb-0">Pilih tagihan untuk hapus massal atau lakukan edit nominal.</p>
                                </div>
                                <div class="d-flex flex-wrap gap-2 align-items-center">
                                    <div class="alert alert-secondary alert-label-icon rounded-label shadow-sm mb-0">
                                        <i class="ri-check-double-line label-icon"></i>
                                        <strong>{{ count($TagihanSelected) }}</strong> dipilih
                                    </div>
                                    <button type="button" class="btn btn-soft-danger" wire:click="HapusTagihan" {{ count($TagihanSelected)===0 ? 'disabled' : '' }}>
                                        <i class="ri-delete-bin-2-line"></i>
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </div><!-- end card header -->
                        <div class="card-body pt-3">
                            <div class="row g-3 mb-4">
                                <div class="col-lg-4">
                                    <label class="form-label small text-muted text-uppercase fw-semibold mb-2">Kategori</label>
                                    <select wire:model="selectedKategori" style="cursor: pointer" class="form-select"
                                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                        title="Pilih Kategori">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($select_kategori as $kategori)
                                        <option value="{{ $kategori->ms_kategori_tagihan_siswa_id }}">{{ $kategori->nama_kategori_tagihan_siswa }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-8">
                                    <label class="form-label small text-muted text-uppercase fw-semibold mb-2">Cari Tagihan</label>
                                    <div class="search-box">
                                        <input type="text" class="form-control search" wire:model.debounce.300ms="search"
                                            placeholder="Cari nama siswa, jenis, atau kategori...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="live-preview">
                                <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
                                @if (!$ms_jenjang_id || !$ms_tahun_ajar_id)
                                <div class="text-center py-4">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#405189,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                                    <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih dahulu.</p>
                                </div>
                                @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-nowrap align-middle" style="width:100%">
                                        <thead class="table-light">
                                            <tr style="white-space: nowrap;">
                                                <th scope="col" style="width: 50px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkAll"
                                                            wire:model="TagihanSelectAll">
                                                    </div>
                                                </th>
                                                <th class="text-uppercase text-center" style="width: 50px;">Hapus</th>
                                                <th class="text-uppercase" style="width: 50px;">NO</th>
                                                <th class="text-uppercase">Siswa</th>
                                                <th class="text-uppercase">Kelas</th>
                                                <th class="text-uppercase">Jenis Tagihan</th>
                                                <th class="text-uppercase">Kategori</th>
                                                <th class="text-uppercase text-center">Estimasi</th>
                                                <th class="text-uppercase text-center">Dibayarkan</th>
                                                <th class="text-uppercase text-center">Kekurangan</th>
                                                <th class="text-uppercase">Jatuh Tempo</th>
                                                <th class="text-uppercase">Status</th>
                                                <th class="text-uppercase">Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tagihans as $item)
                                            <tr>
                                                <th scope="row">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox"
                                                            wire:key="{{ $item->ms_tagihan_siswa_id }}"
                                                            wire:model.live="TagihanSelected"
                                                            value="{{ $item->ms_tagihan_siswa_id }}">
                                                    </div>
                                                </th>
                                                <td class="text-center">
                                                    <a href="#ModalAksiDelete" data-bs-toggle="modal" class="text-danger d-inline-block remove-item-btn"
                                                        wire:click.prevent="$emit('loadTagihanDelete', {{ $item->ms_tagihan_siswa_id }})"
                                                        data-bs-trigger="hover" data-bs-placement="top" title="Hapus Tagihan">
                                                        <i class="ri-delete-bin-5-fill fs-14"></i>
                                                    </a>
                                                </td>
                                                <td>{{ $loop->iteration }}.</td>
                                                <td class="text-start">
                                                    <span class="fw-medium">
                                                    {{ $item->ms_penempatan_siswa->ms_siswa->nama_siswa }}
                                                    </span>
                                                </td>
                                                <td>{{ $item->ms_penempatan_siswa->ms_kelas->nama_kelas }}</td>
                                                <td>{{ $item->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}</td>
                                                <td>{{ $item->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa }}</td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-semibold text-info">
                                                    RP{{ number_format($item->jumlah_tagihan_siswa, 0, ',', '.') }}
                                                </td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-semibold text-success">
                                                    RP{{ number_format($item->total_bayar ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-semibold text-danger">
                                                    RP{{ number_format($item->jumlah_tagihan_siswa - ($item->total_bayar ?? 0), 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo, 'd F Y') }}</td>
                                                <td class="{{ $item->status === 'Belum Dibayar' ? 'text-warning' : '' }} {{ $item->status === 'Masih Dicicil' ? 'text-info' : '' }} {{ $item->status === 'Lunas' ? 'text-success' : '' }}">
                                                    <i class="ri-{{ $item->status === 'Belum Dibayar' ? 'time-line' : ($item->status === 'Masih Dicicil' ? 'money-dollar-circle-line' : 'checkbox-circle-line') }} fs-17 align-middle"></i>
                                                    {{ $item->status }}
                                                </td>
                                                <td>
                                                    <a href="#ModalAksiEdit" data-bs-toggle="modal" class="btn btn-primary btn-sm rounded-pill px-3" title="Edit Tagihan" 
                                                        wire:click="$emit('loadTagihanEdit', {{ $item->ms_tagihan_siswa_id }})">
                                                        <i class="ri-mark-pen-line me-1"></i> Edit
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="13">
                                                    <div class="noresult text-center py-3">
                                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                            colors="primary:#405189,secondary:#08a88a"
                                                            style="width:75px;height:75px">
                                                        </lord-icon>
                                                        <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                                        <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" class="text-end">TOTAL</td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-semibold text-info">
                                                        Rp{{ number_format($totalEstimasi, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-semibold text-success">
                                                        Rp{{ number_format($totalDibayarkan, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-semibold text-danger">
                                                        Rp{{ number_format($totalKekurangan, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    {{-- {{ $tagihans->links() }} --}}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3">
                    <div class="card shadow-sm sticky-top" style="top: 1.5rem;">
                        <div class="card-header border-0">
                            <h5 class="card-title mb-0">Edit Nominal Tagihan by Check</h5>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label">Nominal</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control" wire:model.defer="jumlahTagihan"
                                        aria-label="Amount">
                                </div>
                                <div class="form-text">Perbarui nilai tagihan sebelum menyimpan perubahan.</div>
                            </div>
                            <div class="d-grid">
                                <button type="button" class="btn btn-primary rounded-pill px-3" wire:click="editTagihan">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>