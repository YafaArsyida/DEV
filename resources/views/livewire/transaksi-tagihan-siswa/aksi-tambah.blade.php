<div wire:ignore.self class="offcanvas offcanvas-top bg-light" id="offcanvasAddTagihan" tabindex="-1" aria-labelledby="offcanvasAddTagihanLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom bg-white px-4 py-3 shadow-sm">
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
                        Buat Tagihan Siswa
                    </h5>
                    <small class="text-muted">
                        Tambah Tagihan Siswa : {{ $namaSiswa }}
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
        <div class="row">
            <div class="col-xxl-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

                            {{-- TITLE --}}
                            <div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                                            <i class="ri-file-list-3-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            Daftar Jenis Tagihan
                                        </h5>
                                        <small class="text-muted">
                                            Tentukan jenis tagihan dan nominal untuk setiap pilihan.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- ACTION --}}
                            <div class="d-flex align-items-center">
                                <div class="border rounded-pill px-3 py-2 bg-light">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-check-double-line text-success fs-5"></i>
                                        <span class="text-muted">Dipilih</span>
                                        <span class="badge bg-success rounded-pill px-3">
                                            {{ $tagihanSelected ? count($tagihanSelected) : 0 }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row g-3 mb-4">
                            <div class="col-xxl-2 col-sm-6"> 
                                <label for="filterKategoriTagihan" class="form-label small text-muted text-uppercase fw-semibold mb-2">Kategori</label>
                                <select wire:model.live="selectedKategoriTagihan" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                                    <option value="">Semua Kategori</option>
                                    @foreach ($select_kategori as $kategori)
                                        <option value="{{ $kategori->ms_kategori_tagihan_siswa_id }}">{{ $kategori->nama_kategori_tagihan_siswa }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-xxl-10 col-sm-6">
                                <label for="searchJenisTagihan" class="form-label small text-muted text-uppercase fw-semibold mb-2">Pencarian</label>
                                <div class="search-box">
                                    <input type="text" class="form-control search" wire:model.live.debounce.300ms="searchJenisTagihan" placeholder="cari nama, deskripsi atau lainnya...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        </div>
                        <div class="live-preview">
                            <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
                            @if (!$ms_jenjang_id || !$ms_tahun_ajar_id)
                            <div class="text-center py-4">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                    colors="primary:#405189,secondary:#08a88a"
                                    style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                                <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih dahulu.</p>
                            </div>
                            @else
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap align-middle" style="width:100%">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col" style="width: 50px;">
                                                {{-- <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" wire:model="selectAllTagihan">
                                                </div> --}}
                                            </th>
                                            <th class="text-uppercase">Jenis Tagihan</th>
                                            <th class="text-uppercase">Kategori</th>
                                            <th class="text-uppercase">nominal tagihan</th>
                                            <th class="text-uppercase">Jatuh Tempo</th>
                                            <th class="text-uppercase text-center">Cicilan</th>
                                            <th class="text-uppercase text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($tagihans as $item)
                                        <tr class="{{ $item['ms_tagihan_siswa_id'] ? 'table-info' : '' }}">
                                            <th scope="col" style="width: 50px;">
                                                <div class="form-check">
                                                    <input
                                                        class="form-check-input"
                                                        type="checkbox"
                                                        wire:key="tagihan-{{ $item['ms_jenis_tagihan_siswa_id'] }}"
                                                        wire:model.live="tagihanSelected"
                                                        value="{{ $item['ms_jenis_tagihan_siswa_id'] }}"
                                                        @disabled($item['sudah_ditetapkan'])
                                                    >    
                                                </div>
                                            </th>
                                            <td>{{ $item['nama_jenis_tagihan_siswa'] }}</td>
                                            <td>
                                                {{ $item['nama_kategori_tagihan_siswa'] }}
                                            </td>
                                            <td>
                                                @if($item['sudah_ditetapkan'])
                                                    <span class="fs-12 fw-medium">
                                                        Rp{{ number_format($item['jumlah_tagihan_siswa'] ?? 0, 0, ',', '.') }}
                                                    </span>
                                                @else
                                                    <div class="input-group input-group-sm">
                                                        <span class="input-group-text">
                                                            Rp
                                                        </span>
                                                        <input class="form-control fw-medium fs-12" onkeyup="formatTagihan(this)" wire:model.live.debounce.300ms="jumlahTagihan.{{ $item['ms_jenis_tagihan_siswa_id'] }}">
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item['tanggal_jatuh_tempo'], 'd F Y') }}</td>
                                            <td class="text-center">{{ $item['cicilan_status'] }}</td>
                                            <td style="width: 100px;" class="text-center">
                                                @if(!$item['sudah_ditetapkan'])
                                                <span class="badge bg-warning-subtle text-warning">
                                                    Belum Ditambahkan
                                                </span>

                                                @elseif($item['status_tagihan'] == 'Belum Dibayar')
                                                    <span class="badge bg-danger-subtle text-danger">
                                                        Belum Dibayar
                                                    </span>
                                                @elseif($item['status_tagihan'] == 'Masih Dicicil')
                                                    <span class="badge bg-info-subtle text-info">
                                                        Masih Dicicil
                                                    </span>
                                                @else
                                                    <span class="badge bg-success-subtle text-success">
                                                        Lunas
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7">
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
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="d-flex flex-column flex-sm-row gap-2 justify-content-end">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="offcanvas">
                        <i class="ri-close-line me-1"></i>
                        Tutup
                    </button>
                    <button type="button" wire:click.prevent="createTagihan" wire:loading.attr="disabled" class="btn btn-primary rounded-pill px-4">
                        <i class="ri-save-3-line me-1"></i>
                        Simpan Tagihan
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