<div>
    <div wire:ignore.self class="offcanvas offcanvas-top" id="offcanvasAddTagihan" aria-labelledby="offcanvasAddTagihanLabel" style="min-height:100vh;">
        <div class="offcanvas-header border-0 p-4" style="background: linear-gradient(135deg, #405189 0%, #08a88a 100%);">
            <div>
                <h5 class="offcanvas-title text-white fw-semibold" id="offcanvasAddTagihanLabel">Tambah Tagihan Siswa</h5>
                <p class="text-white-50 mb-0">Pilih siswa dan jenis tagihan, kemudian simpan untuk membuat tagihan baru.</p>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-4">
            <div class="row g-4">
                <div class="col-xxl-4">
                    <div class="card shadow-sm">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                                <div>
                                    <h5 class="card-title mb-1">Data Siswa</h5>
                                    <p class="text-muted mb-0">Pilih siswa dari daftar berdasarkan kelas dan pencarian.</p>
                                </div>
                                <div class="alert alert-secondary alert-label-icon rounded-label shadow-sm mb-0">
                                    <i class="ri-check-double-line label-icon"></i>
                                    <strong>{{ $siswaSelected ? count($siswaSelected) : 0 }}</strong> dipilih
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-4">
                                <div class="col-xxl-4 col-sm-6">
                                    <label for="filterKelas" class="form-label small text-muted text-uppercase fw-semibold mb-2">Kelas</label>
                                    <select id="filterKelas" wire:model="selectedKelas" style="cursor: pointer" class="form-select"
                                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                                        <option value="">Semua Kelas</option>
                                        @foreach ($select_kelas as $kelas)
                                            <option value="{{ $kelas->ms_kelas_id }}">{{ $kelas->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xxl-8 col-sm-6">
                                    <label for="searchSiswa" class="form-label small text-muted text-uppercase fw-semibold mb-2">Pencarian Siswa</label>
                                    <div class="search-box">
                                        <input type="text" id="searchSiswa" class="form-control search" wire:model.debounce.300ms="searchSiswa"
                                            placeholder="Cari nama siswa...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="live-preview">
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
                                                    <th class="text-uppercase">Kelas</th>
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
                                                        <td>{{ $item->ms_siswa->nama_siswa }}</td>
                                                        <td>{{ $item->ms_kelas->nama_kelas }}</td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3">
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

                <div class="col-xxl-8">
                    <div class="card shadow-sm">
                        <div class="card-header border-0 pb-0">
                            <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3">
                                <div>
                                    <h5 class="card-title mb-1">Data Jenis Tagihan</h5>
                                    <p class="text-muted mb-0">Tentukan jenis tagihan dan nominal untuk setiap pilihan.</p>
                                </div>
                                <div class="alert alert-secondary alert-label-icon rounded-label shadow-sm mb-0">
                                    <i class="ri-check-double-line label-icon"></i>
                                    <strong>{{ $tagihanSelected ? count($tagihanSelected) : 0 }}</strong> dipilih
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-3 mb-4">
                                <div class="col-xxl-3 col-sm-6">
                                    <label for="filterKategoriTagihan" class="form-label small text-muted text-uppercase fw-semibold mb-2">Kategori</label>
                                    <select id="filterKategoriTagihan" wire:model="selectedKategoriTagihan" style="cursor: pointer"
                                        class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top"
                                        title="Pilih Kategori">
                                        <option value="">Semua Kategori</option>
                                        @foreach ($select_kategori as $kategori)
                                            <option value="{{ $kategori->ms_kategori_tagihan_siswa_id }}">{{ $kategori->nama_kategori_tagihan_siswa }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-xxl-9 col-sm-6">
                                    <label for="searchJenisTagihan" class="form-label small text-muted text-uppercase fw-semibold mb-2">Pencarian</label>
                                    <div class="search-box">
                                        <input type="text" id="searchJenisTagihan" class="form-control search"
                                            wire:model.debounce.300ms="searchJenisTagihan" placeholder="Cari nama jenis tagihan atau deskripsi...">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="live-preview">
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
                                                    <th class="text-uppercase">Nominal Tagihan</th>
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
                                                        <td>{{ $item->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa }}</td>
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
                                                        <td colspan="6">
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
                        <button type="button" class="btn btn-link link-success fw-medium" data-bs-dismiss="offcanvas"><i class="ri-close-line me-1 align-middle"></i> Batal</button>
                        <button type="button" class="btn btn-primary" wire:click="createTagihan">Simpan Tagihan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
