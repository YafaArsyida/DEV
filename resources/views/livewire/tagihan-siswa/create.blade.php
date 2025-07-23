<div>
    <div wire:ignore.self class="modal fade" id="ModalAddTagihan" tabindex="-1" aria-labelledby="ModalAddTagihanLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">Tambah Tagihan Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xxl-4">
                            <div class="card">
                                <div class="card-header align-items-center d-flex border-0">
                                    <h4 class="card-title mb-0 flex-grow-1">Data Siswa</h4>
                                    <div class="flex-shrink-0">
                                        <div class="mb-0 alert alert-secondary alert-dismissible alert-label-icon rounded-label shadow fade show" role="alert">
                                            <i class="ri-check-double-line label-icon"></i>
                                            <strong>{{ $siswaSelected ? count($siswaSelected) : 0 }}</strong> - siswa telah dipilih
                                        </div>
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <!-- Pencarian dan Pilihan Kelas -->
                                    <div class="row g-3 mb-3">
                                        <!-- Dropdown Pilihan Kelas -->
                                        <div class="col-xxl-4 col-sm-6"> 
                                            <select wire:model="selectedKelas" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                                                <option value="">Semua Kelas</option>
                                                @foreach ($select_kelas as $kelas)
                                                    <option value="{{ $kelas->ms_kelas_id }}">{{ $kelas->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <!-- Kotak Pencarian -->
                                        <div class="col-xxl-8 col-sm-6">
                                            <div class="search-box">
                                                <input type="text" class="form-control search" wire:model.debounce.300ms="searchSiswa" placeholder="Cari nama, deskripsi, atau lainnya...">
                                                <i class="ri-search-line search-icon"></i>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Tabel Data Siswa -->
                                    <div class="live-preview">
                                        <!-- Jika Jenjang atau Tahun Ajar Belum Dipilih -->
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
                                                <table class="table table-hover nowrap align-middle" style="width:100%">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th scope="col" style="width: 50px;">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="checkbox" wire:model="selectAllSiswa">
                                                                </div>
                                                            </th>
                                                            <th class="text-uppercase">Siswa</th>
                                                            <th class="text-uppercase">Tagihan</th>
                                                            <th class="text-uppercase text-center">Nominal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($siswas as $item)
                                                            <tr>
                                                                <th scope="col" style="width: 50px;">
                                                                    <div class="form-check">
                                                                        <input class="form-check-input" type="checkbox" wire:key="siswa-{{ $item->ms_penempatan_siswa_id }}" wire:model.defer="siswaSelected" value="{{ $item->ms_penempatan_siswa_id }}">
                                                                    </div>
                                                                </th>
                                                                <td class="text-start">
                                                                    <span class="fw-medium">
                                                                        {{ $item->ms_siswa->nama_siswa }}
                                                                    </span>
                                                                    <p class="text-muted mb-0">{{ $item->ms_kelas->nama_kelas }}</p>
                                                                </td>
                                                                <td>{{ $item->jumlah_jenis_tagihan_siswa() }} item</td>
                                                                <td class="text-center">
                                                                    <span class="fw-medium text-success">
                                                                        RP{{ number_format($item->total_tagihan_siswa(), 0, ',', '.') }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="5">
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
                                                <!-- Pagination -->
                                                {{ $siswas->links() }}
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-8">
                            <div class="card">
                                <div class="card-header align-items-center d-flex border-0">
                                    <h4 class="card-title mb-0 flex-grow-1">Jenis Tagihan</h4>
                                    <div class="flex-shrink-0">
                                        <!-- Secondary Alert -->
                                        <div class="mb-0 alert alert-secondary alert-dismissible alert-label-icon rounded-label shadow fade show" role="alert">
                                            <i class="ri-check-double-line label-icon"></i>
                                            <strong>{{ $tagihanSelected ? count($tagihanSelected) : 0 }}</strong> - tagihan telah dipilih
                                        </div>
                                    </div>
                                </div><!-- end card header -->
                                <div class="card-body">
                                    <div class="row g-3 mb-3">
                                        <div class="col-xxl-2 col-sm-6"> 
                                            <select wire:model="selectedKategoriTagihan" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                                                <option value="">Semua Kategori</option>
                                                @foreach ($select_kategori as $kategori)
                                                    <option value="{{ $kategori->ms_kategori_tagihan_siswa_id }}">{{ $kategori->nama_kategori_tagihan_siswa }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-xxl-10 col-sm-6">
                                            <div class="search-box">
                                                <input type="text" class="form-control search" wire:model.debounce.300ms="searchJenisTagihan" placeholder="cari nama, deskripsi atau lainnya...">
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
                                            <table class="table table-hover nowrap align-middle" style="width:100%">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th scope="col" style="width: 50px;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" wire:model="selectAllTagihan">
                                                            </div>
                                                        </th>
                                                        <th class="text-uppercase">Jenis Tagihan</th>
                                                        <th class="text-uppercase">Kategori</th>
                                                        <th class="text-uppercase">Tagihan</th>
                                                        <th class="text-uppercase">Jatuh Tempo</th>
                                                        <th class="text-uppercase">Cicilan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($jenis_tagihans as $item)
                                                    <tr>
                                                        <th scope="col" style="width: 50px;">
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="checkbox" wire:key="tagihan-{{ $item->ms_jenis_tagihan_siswa_id }}" wire:model.defer="tagihanSelected" value="{{ $item->ms_jenis_tagihan_siswa_id }}">
                                                            </div>
                                                        </th>
                                                        <td>{{ $item->nama_jenis_tagihan_siswa }}</td>
                                                        <td>{{ $item->nama_kategori_tagihan_siswa() }}</td>
                                                        <td>
                                                            <div class="input-group input-group-sm">
                                                                <span class="input-group-text">RP</span>
                                                                <input type="text" class="form-control" wire:model.defer="jumlahTagihan.{{ $item->ms_jenis_tagihan_siswa_id }}" aria-label="Amount">
                                                            </div>
                                                        </td>
                                                        <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal_jatuh_tempo, 'd F Y') }}</td>
                                                        <td>{{ $item->cicilan_status }}</td>
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
                                            {{ $jenis_tagihans->links() }}
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                    <button type="button" class="btn btn-primary" wire:click="createTagihan">Simpan Tagihan</button>
                </div>
            </div>
        </div>
    </div>
</div>
