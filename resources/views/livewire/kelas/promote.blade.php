<div wire:ignore.self class="modal fade" id="ModalPromoteKelas" tabindex="-1" aria-labelledby="ModalPromoteKelasLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Naikkan Siswa
                            @if ($tahunAjarBerikut)
                                <small class="text-muted">ke Tahun Ajar: {{ $tahunAjarBerikut->nama_tahun_ajar }}</small>
                            @else
                                <small class="text-danger">(Tahun ajar berikutnya tidak ditemukan)</small>
                            @endif
                        </h5>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18">
                    </i>
                </button>
            </div>

            <div class="modal-body pb-0">
                <div class="row">
                    <div class="col-xxl-12">
                        <!-- Pencarian dan Pilihan Kelas -->
                        <div class="row g-3 align-items-end mb-3">
                        
                            <!-- Kotak Pencarian -->
                            <div class="col-xxl-8 col-sm-6">
                                <label for="searchSiswa" class="form-label">Pencarian Siswa</label>
                                <div class="search-box">
                                    <input type="text" id="searchSiswa" class="form-control search" wire:model.debounce.300ms="searchSiswa"
                                        placeholder="Cari nama, deskripsi, atau lainnya...">
                                    <i class="ri-search-line search-icon"></i>
                                </div>
                            </div>
                        
                            <!-- Dropdown Pilihan Kelas -->
                            <div class="col-xxl-4 col-sm-6">
                                <label for="kelasTujuan" class="form-label">Kelas Tujuan</label>
                                <select id="kelasTujuan" wire:model="kelasTujuan" style="cursor: pointer" class="form-select"
                                    data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas Tujuan">
                                    <option value="">Pilih Kelas Tujuan</option>
                                    @foreach ($select_kelas as $kelas)
                                    <option value="{{ $kelas->ms_kelas_id }}">{{ $kelas->nama_kelas }}</option>
                                    @endforeach
                                </select>
                            </div>
                        
                        </div>
                        
                        <!-- Tabel Data Siswa -->
                        <div class="live-preview">
                            <!-- Jika Jenjang atau Tahun Ajar Belum Dipilih -->
                            @if (!$selectedJenjang || !$selectedTahunAjar)
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
                                                        <input class="form-check-input" type="checkbox" id="checkAll" wire:model="selectAll">
                                                    </div>
                                                </th>
                                                <th class="text-uppercase">Siswa</th>
                                                <th class="text-uppercase">Kelas</th>
                                                <th class="text-uppercase">Status</th>
                                                <th class="text-uppercase">Batal</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($siswas as $item)
                                                <tr>
                                                    <th scope="row">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" wire:key="{{ $item->ms_penempatan_siswa_id }}" wire:model="siswaSelected" value="{{ $item->ms_penempatan_siswa_id }}">
                                                        </div>
                                                    </th>
                                                    <td>{{ $item->ms_siswa->nama_siswa }}</td>
                                                    <td>{{ $item->ms_kelas->nama_kelas }}</td>
                                                    <td class="{{ $item->sudahDinaikkan($tahunAjarBerikut->ms_tahun_ajar_id) ? 'text-danger' : 'text-success' }}">
                                                        <i class="ri-{{ $item->sudahDinaikkan($tahunAjarBerikut->ms_tahun_ajar_id) ? 'close' : 'checkbox' }}-circle-line fs-17 align-middle"></i>
                                                        {{ $item->sudahDinaikkan($tahunAjarBerikut->ms_tahun_ajar_id) ? 'Sudah Dinaikkan' : 'Belum Dinaikkan' }}
                                                    </td>
                                                    <td>
                                                        @if ($item->sudahDinaikkan($tahunAjarBerikut->ms_tahun_ajar_id))
                                                            <button type="button" class="btn btn-primary rounded-pill btn-sm px-4" wire:click="batalNaikKelasSiswa({{ $item->ms_siswa->ms_siswa_id }})">
                                                                <i class="ri-close-circle-line align-bottom"></i> Batal
                                                            </button>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>

                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4">
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

            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
                <button type="button" class="btn btn-primary rounded-pill px-4" wire:click="pindahkanSiswa" {{ count($siswaSelected) === 0 ? 'disabled' : '' }}>
                    <i class="ri-save-3-line me-1"></i>
                    Pindahkan ({{ count($siswaSelected) }})
                </button>
            </div>

            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                <button type="button" class="btn btn-primary" wire:click="naikKelasSiswa" {{ count($siswaSelected) === 0 ? 'disabled' : '' }}>
                    Naikkan ({{ count($siswaSelected) }})
                </button>
            </div>
        </div>
    </div>
</div>