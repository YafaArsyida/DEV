<div class="mt-3 mt-lg-0">
    <div class="row g-3 mb-0 align-items-center">
        <div class="col-auto">
            <span class="fs-14 text-info">
                Saldo Kas : Rp{{ number_format($saldoKas, 0, ',', '.') }}
            </span>
        </div>
        <div class="col-auto">
            <span class="fs-14 text-warning">
                Saldo Bank : Rp{{ number_format($saldoBank, 0, ',', '.') }}
            </span>
        </div>
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
                <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSiswa" aria-controls="offcanvasSiswa" class="btn btn-primary shadow-none">
                  Data Siswa
                </button>
                <div class="input-group-text bg-primary border-primary text-white">
                    <i class="ri-user-follow-line"></i>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="offcanvas offcanvas-end" id="offcanvasSiswa" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" aria-labelledby="offcanvasSiswaLabel">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasSiswaLabel">Data Siswa Tersedia</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
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
            <div class="col-xl-12">
                    <div class="mt-4">
                    <div class="live-preview">
                    <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
                    @if (!$selectedJenjang || !$selectedTahunAjar)
                        <div class="text-center py-4">
                            <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                colors="primary:#405189,secondary:#08a88a"
                                style="width:75px;height:75px">
                            </lord-icon>
                            <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                            <p class="text-muted mb-0">Untuk melihat data siswa, harap pilih Jenjang dan Tahun Ajar terlebih dahulu.</p>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover nowrap align-middle" style="width:100%">
                                <thead class="table-light">
                                    <tr>
                                        <th class="text-uppercase">#</th>
                                        <th class="text-uppercase">Siswa</th>
                                        <th class="text-uppercase">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($siswa as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div>
                                                    <h5 class="fs-13 mb-0">{{ $item->ms_siswa->nama_siswa }}</h5>
                                                    <p class="fs-12 mb-0 text-muted">{{ $item->ms_kelas->nama_kelas }}</p>
                                                </div>
                                            </td>
                                            <td>
                                                <ul class="list-inline hstack gap-2 mb-0">
                                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Siswa">
                                                        <button wire:click="$emit('siswaSelected', {{ $item->ms_penempatan_siswa_id }})" class="btn btn-primary d-inline-block detail-item-btn" data-bs-dismiss="offcanvas">
                                                            <i class="ri-checkbox-circle-line align-bottom"></i> Pilih
                                                        </button>
                                                    </li>
                                                    
                                                </ul>
                                            </td>
                                        </tr>
                                    @empty
                                        <!-- Jika Tidak Ada Data Siswa -->
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
                            {{ $siswa->links() }}
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
