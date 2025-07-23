{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
<div class="card-header border-0">
    <div class="row g-4 align-items-center">
        <div class="col-xxl-12 col-sm-12">
            <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSiswa" aria-controls="offcanvasSiswa">Data Siswa</button>
            <div wire:ignore.self class="offcanvas offcanvas-end" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasSiswa" aria-labelledby="offcanvasSiswaLabel">
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
                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped table-hover nowrap align-middle" style="width:100%">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="text-uppercase">#</th>
                                                <th class="text-uppercase">Siswa</th>
                                                <th style="width:100px" class="text-uppercase">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($siswa as $item)
                                                <tr>
                                                    <td>{{ $loop->iteration }}</td>
                                                    <td>
                                                        <div>
                                                            <h5 class="fs-13 mb-0">{{ $item->nama_siswa }}</h5>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <ul class="list-inline hstack gap-2 mb-0">
                                                            <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Siswa">
                                                                <button wire:click="selectSiswa({{ $item->ms_siswa_id }})" class="btn btn-sm btn-primary" data-bs-dismiss="offcanvas">
                                                                    <i class="ri-settings-3-line fs-17 align-middle"></i> Pilih
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
