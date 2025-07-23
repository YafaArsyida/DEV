<div class="card">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"> Navigasi Kelas</h5>
        </div>
    </div>
    <div class="card-body border border-dashed border-end-0 border-start-0">
        <div class="row g-3">
            <div class="col-xxl-12 col-sm-12">
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
        </div>
        <!--end row-->
    </div>
    <div class="card-body">
        <div class="live-preview">
            <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
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
                <!-- Tabel Data Kelas -->
                <div class="table-responsive">
                    <table class="table table-hover nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase" width="50px">NO</th>
                                <th class="text-uppercase">kelas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelass as $item)
                                <tr>
                                    <td>#{{ $item->urutan }}</td>
                                    <td>
                                        <div>
                                            <h5 class="fs-13 mb-0">{{ $item->nama_kelas }}</h5>
                                            <p class="fs-12 mb-0 text-muted">{{ $item->deskripsi }}</p>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <!-- Jika Tidak Ada Data Kelas -->
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
                    <!-- Pagination -->
                    {{ $kelass->links() }}
                </div>
            @endif
        </div>
    </div>
</div>