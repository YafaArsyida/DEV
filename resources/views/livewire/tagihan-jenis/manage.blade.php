<div>
    <div wire:ignore.self class="offcanvas offcanvas-top bg-light" id="offcanvasManage" aria-labelledby="offcanvasManageLabel"
        style="min-height:100vh;">
        <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-start w-100">
                <!-- Kiri -->
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                            <i class="ri-file-chart-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Kelola Jenis Tagihan
                        </h5>
                        <small class="text-muted">
                            Gunakan filter dan pilihan cepat untuk mengelola jenis tagihan secara efisien.
                        </small>
                    </div>
                </div>
                <!-- Kanan -->
                <button type="button"
                    class="btn btn-light btn-icon rounded-circle shadow-none"
                    data-bs-dismiss="offcanvas">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>
        </div>
        <div class="offcanvas-body">
            <div class="row g-4">
                <div class="col-xxl-9">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header">
                            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

                                {{-- TITLE --}}
                                <div>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar-sm">
                                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                                <i class="ri-file-list-3-line"></i>
                                            </div>
                                        </div>

                                        <div>
                                            <h5 class="fw-bold mb-1">
                                                Daftar Tagihan
                                            </h5>
                                            <small class="text-muted">
                                                Pilih tagihan untuk hapus massal atau lakukan edit nominal.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                {{-- ACTION --}}
                                <div class="d-flex gap-2 flex-wrap align-items-center">
                                    <div class="border rounded-pill px-3 py-2 bg-light">
                                        <div class="d-flex align-items-center gap-2">
                                            <i class="ri-check-double-line text-success fs-5"></i>
                                            <span class="text-muted">Dipilih</span>
                                            <span class="badge bg-success rounded-pill px-3">
                                                {{ count($TagihanSelected) }}
                                            </span>
                                        </div>
                                    </div>

                                    <button
                                        type="button"
                                        class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#ModalAksiDeleteMultiple"
                                        {{ count($TagihanSelected) === 0 ? 'disabled' : '' }}>
                                        <i class="ri-delete-bin-2-line"></i>
                                        <span>Hapus</span>
                                    </button>
                                </div>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-3">
                                <div class="col-lg-2 col-sm-6">
                                    <label class="form-label small text-muted text-uppercase fw-medium mb-2">Kelas</label>
                                    <select wire:model="selectedKelas" style="cursor: pointer" class="form-select"
                                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                        title="Pilih Kelas">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($select_kelas as $kelas)
                                        <option value="{{ $kelas->ms_kelas_id }}">{{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-10 col-sm-6">
                                    <label class="form-label small text-muted text-uppercase fw-medium mb-2">Cari</label>
                                    <div class="search-box">
                                        <input type="text" class="form-control search" wire:model.debounce.300ms="search"
                                            placeholder="cari nama, deskripsi atau lainnya...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="live-preview">
                                <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
                                @if (!$selectedJenjang || !$selectedTahunAjar)
                                <div class="text-center py-4">
                                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                        colors="primary:#405189,secondary:#08a88a" style="width:75px;height:75px">
                                    </lord-icon>
                                    <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                                    <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar
                                        terlebih dahulu.</p>
                                </div>
                                @else
                                <div class="table-responsive">
                                    <table class="table table-hover table-nowrap align-middle" style="width:100%">
                                        <thead class="table-light">
                                            <tr class="text-uppercase">
                                                <th scope="col" style="width: 50px;">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkAll"
                                                            wire:model="TagihanSelectAll">
                                                    </div>
                                                </th>
                                                <th class="text-center" style="width: 50px;">Hapus</th>
                                                <th style="width: 50px;">NO</th>
                                                <th>Siswa</th>
                                                <th>Kelas</th>
                                                <th>Jenis Tagihan</th>
                                                <th>Kategori</th>
                                                <th class="text-center">Estimasi</th>
                                                <th class="text-center">Dibayarkan</th>
                                                <th class="text-center">Kekurangan</th>
                                                <th class="text-center">Jatuh Tempo</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Edit</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($tagihans as $key => $item)
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
                                                <td>{{ $tagihans->firstItem() + $key }}.</td>   
                                                <td class="text-start">
                                                    <span class="fw-medium">
                                                        {{ $item->ms_penempatan_siswa->ms_siswa->nama_siswa }}
                                                    </span>
                                                </td>
    
                                                <td>
                                                    {{ $item->ms_penempatan_siswa->ms_kelas->nama_kelas }}
                                                </td>
    
                                                <td class="text-start">
                                                    <span class="fw-medium">
                                                        {{ $item->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}
                                                    </span>
                                                </td>
    
                                                <td>
                                                    {{
                                                    $item->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa
                                                    }}
                                                </td>
    
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-primary">
                                                        Rp{{ number_format($item->jumlah_tagihan_siswa, 0, ',', '.') }}
                                                    </span>
                                                </td>
    
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-success">
                                                        Rp{{ number_format($item->total_bayar ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </td>
    
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-danger">
                                                        Rp{{ number_format(($item->jumlah_tagihan_siswa -
                                                        ($item->total_bayar ?? 0)), 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    {{
                                                        \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->ms_jenis_tagihan_siswa->tanggal_jatuh_tempo,
                                                        'd F Y') }}
                                                </td>
                                                <td class="text-center
                                                                {{ $item->status === 'Belum Dibayar' ? 'text-warning' : '' }}
                                                                {{ $item->status === 'Masih Dicicil' ? 'text-primary' : '' }}
                                                                {{ $item->status === 'Lunas' ? 'text-success' : '' }}">
                                                    <i
                                                        class="ri-{{ $item->status === 'Belum Dibayar' ? 'time-line' : ($item->status === 'Masih Dicicil' ? 'money-dollar-circle-line' : 'checkbox-circle-line') }} fs-17 align-middle"></i>
                                                    {{ $item->status }}
                                                </td>
                                                <td class="text-center">
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
                                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json"
                                                            trigger="loop" colors="primary:#405189,secondary:#08a88a"
                                                            style="width:75px;height:75px">
                                                        </lord-icon>
                                                        <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                                        <p class="text-muted mb-0">Kami telah mencari keseluruhan data,
                                                            namun tidak ditemukan hasil yang sesuai.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="7" class="text-end">TOTAL</td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-primary">
                                                        Rp{{ number_format($totalEstimasi, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-success">
                                                        Rp{{ number_format($totalDibayarkan, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="fs-12 fw-medium text-danger">
                                                        Rp{{ number_format($totalKekurangan, 0, ',', '.') }}
                                                    </span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="mt-3">
                                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                            <div class="text-muted fs-13">
                                                Menampilkan
                                                <span class="fw-medium">
                                                    {{ $tagihans->firstItem() ?? 0 }}
                                                </span>
                                                -
                                                <span class="fw-medium">
                                                    {{ $tagihans->lastItem() ?? 0 }}
                                                </span>
                                                dari
                                                <span class="fw-medium">
                                                    {{ $tagihans->total() }}
                                                </span>
                                                data tagihan
                                            </div>
                                            <div>
                                                {{ $tagihans->links() }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3">
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden sticky-top">
                        <div class="card-header">
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                        <i class="ri-calendar-event-line">
                                        </i>
                                    </div>
                                </div>
                                <div>
                                    <h5 class="fw-bold mb-1">
                                        Edit Nominal Tagihan by Check
                                    </h5>
                                </div>
                            </div>
                        </div><!-- end card header -->
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label">Nominal</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" 
                                        class="form-control" 
                                        wire:model.defer="jumlahTagihan"
                                        aria-label="Amount"
                                        onkeyup="formatTagihan(this)">
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
    <div class="modal fade zoomIn"
        id="ModalAksiDeleteMultiple"
        tabindex="-1"
        aria-hidden="true"
        wire:ignore.self>

        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

                {{-- HEADER --}}
                <div class="modal-header border-0 pb-0">
                    <button
                        type="button"
                        class="btn btn-light btn-icon rounded-circle ms-auto"
                        data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ri-close-line fs-18"></i>
                    </button>
                </div>

                {{-- BODY --}}
                <div class="modal-body px-4 pb-5 pt-2 text-center">

                    {{-- ICON --}}
                    <div class="mb-4">
                        <div class="avatar-xl mx-auto">
                            <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                <lord-icon
                                    src="https://cdn.lordicon.com/gsqxdxog.json"
                                    trigger="loop"
                                    colors="primary:#dc3545,secondary:#f06548"
                                    style="width:70px;height:70px">
                                </lord-icon>
                            </div>
                        </div>
                    </div>

                    {{-- TITLE --}}
                    <div class="mb-2">

                        <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill mb-3">
                            Konfirmasi Hapus
                        </span>

                        <h3 class="fw-bold mb-2">
                            Hapus {{ count($TagihanSelected) }} Tagihan?
                        </h3>

                        <p class="text-muted mb-0 lh-lg px-lg-4">
                            Anda akan menghapus
                            <strong>{{ count($TagihanSelected) }}</strong>
                            tagihan yang dipilih.
                            Tindakan ini bersifat permanen dan tidak dapat dibatalkan.
                        </p>

                    </div>

                    {{-- INFORMATION --}}
                    <div class="alert alert-warning border rounded-4 text-start mt-4 mb-0">

                        <div class="d-flex align-items-start gap-3">

                            <div class="flex-shrink-0">
                                <i class="ri-error-warning-line text-warning fs-20"></i>
                            </div>

                            <div>
                                <h6 class="fw-semibold mb-1">
                                    Perhatian
                                </h6>

                                <p class="text-muted mb-0 fs-13">
                                    Pastikan tagihan yang dipilih sudah benar sebelum
                                    melanjutkan proses penghapusan.
                                </p>
                            </div>

                        </div>

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">

                        <i class="ri-close-line me-1"></i>
                        Batal

                    </button>

                    <button
                        type="button"
                        class="btn btn-danger rounded-pill px-4"
                        wire:click="HapusTagihan">

                        <i class="ri-delete-bin-2-line me-1"></i>
                        Ya, Hapus Semua

                    </button>

                </div>

            </div>
        </div>
    </div>
</div>
<script>
    function formatTagihan(el) {
        let angka = el.value.replace(/\D/g, '');
        el.value = new Intl.NumberFormat('id-ID').format(angka);
    }
</script>