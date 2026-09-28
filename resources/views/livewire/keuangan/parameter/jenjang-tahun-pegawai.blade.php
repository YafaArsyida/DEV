<div class="mt-3 mt-lg-0">
    <div class="row g-3 mb-0 align-items-center">
        <div class="col-sm-auto">
            <div class="input-group">
                <select wire:model="selectedJenjang" style="cursor: pointer" class="form-select border-0 dash-filter-picker shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Jenjang">
                    {{-- <option value="" selected disabled>Pilih Jenjang</option> --}}
                    @foreach ($select_jenjang as $item)
                        <option value="{{ $item->ms_jenjang_id }}">{{ $item->nama_jenjang }}</option>
                    @endforeach
                </select>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class=" ri-government-line"></i>
                </div>
            </div>
        </div>
         <!--end col-->
        <div class="col-sm-auto">
            <div class="input-group">
                <select wire:model="selectedTahunAjar" style="cursor: pointer" class="form-select border-0 dash-filter-picker shadow" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Tahun Ajar">
                    {{-- <option value="" selected disabled>Pilih Tahun Ajar</option> --}}
                    @foreach ($select_tahun_ajar as $item)
                        <option value="{{ $item->ms_tahun_ajar_id }}">{{ $item->nama_tahun_ajar }}</option>
                    @endforeach
                </select>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-calendar-2-line"></i>
                </div>
            </div>
        </div>
        <!--end col-->
        <div class="col-auto">
            <div class="input-group">
                <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasPegawai" aria-controls="offcanvasPegawai" class="btn btn-primary shadow-none">
                  Pilih Pegawai
                </button>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-user-follow-line"></i>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="offcanvas offcanvas-end" id="offcanvasPegawai" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" aria-labelledby="offcanvasPegawaiLabel">
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
                            Data Pegawai
                        </h5>
                        {{-- <small class="text-muted">
                            Lakukan pencarian data siswa 
                        </small> --}}
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
            <div class="row g-3 mb-3">
                <div class="col-xxl-12 col-sm-12">
                    <div class="search-box">
                        <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
            </div>
            <div class="col-xl-12 mt-3">
                <div class="live-preview">
                    <!-- Jika Jenjang belum dipilih -->
                    @if (!$selectedJenjang)
                        <div class="text-center py-4">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                colors="primary:#405189,secondary:#08a88a"
                                style="width:75px;height:75px">
                            </lord-icon>
                            <h5 class="mt-2">Silakan Pilih Jenjang</h5>
                            <p class="text-muted mb-0">Untuk melihat data pegawai, harap pilih Jenjang terlebih dahulu.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-nowrap table-bordered table-striped table-hover nowrap align-middle" style="width:100%">
                                <thead class="table-light">
                                    <tr class="text-uppercase">
                                        <th class="text-center">no</th>
                                        <th>Pegawai</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pegawais as $pegawai)
                                        <tr>
                                            <td class="text-center">{{ $loop->iteration }}</td>
                                            <td>
                                                <span class="fw-medium" style="white-space: nowrap;">
                                                    {{ $pegawai->nama_pegawai }}
                                                </span>
                                                <p class="text-muted mb-0">
                                                    {{ $pegawai->ms_jabatan->nama_jabatan ?? 'Tidak ada jabatan' }}
                                                </p>
                                            </td>
                                            <td class="text-center">
                                                <a title="Pilih Pegawai" wire:click="$emit('pegawaiSelected', {{ $pegawai->ms_pegawai_id }})" class="btn btn-primary btn-sm rounded-pill px-3" data-bs-dismiss="offcanvas">
                                                    <i class="ri-checkbox-circle-line me-1"></i> Pilih
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <!-- Jika Tidak Ada Data Pegawai -->
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
                            {{-- PAGINATION --}}
                            <div class="mt-3">
                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                    <div class="text-muted fs-13">
                                        Menampilkan
                                        <span class="fw-semibold">
                                            {{ $pegawais->firstItem() ?? 0 }}
                                        </span>
                                        -
                                        <span class="fw-semibold">
                                            {{ $pegawais->lastItem() ?? 0 }}
                                        </span>
                                        dari
                                        <span class="fw-semibold">
                                            {{ $pegawais->total() }}
                                        </span>
                                        data pegawai
                                    </div>
                                    <div>
                                        {{ $pegawais->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('livewire:load', function () {
            Livewire.emit('parameterUpdated', @json($selectedJenjang), @json($selectedTahunAjar));
        });
    </script>
</div>
