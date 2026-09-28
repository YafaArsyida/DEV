<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
    
                    <div>
                        <h5 class="fw-bold mb-1">
                            Data Kelas
                        </h5>
                        <small>
                            Kelola penempatan siswa dalam kelas 
                        </small>
                    </div>
                </div>
            </div>
    
            {{-- ACTION --}}
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="d-flex gap-2 flex-wrap">
                {{-- TAMBAH --}}
                <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#ModalAddKelas"
                        wire:click.prevent="$emit('showCreateKelas', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})">
                    <i class="ri-add-line me-1"></i>Kelas Baru
                </button>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-12 col-sm-12">
                <label for="searchData" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
        </div>
        <!--end row-->
        {{-- DATA --}}
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
                <div class="table-responsive">
                    <!-- Tabel Data Kelas -->
                    <table class="table table-hover table-nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase" width="30px">no</th>
                                <th class="text-uppercase">kelas</th>
                                <th class="text-uppercase">siswa</th>
                                <th class="text-uppercase text-center">aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($kelass as $item)
                                <tr>
                                    <td>
                                        {{ ($kelass->firstItem() ?? 0) + $loop->index }}.
                                    </td>
                                    <td>
                                        <span class="fw-medium">
                                            {{ $item->nama_kelas }}
                                        </span>
                                        <p class="text-muted mb-0">{{ $item->deskripsi }}</p>
                                    </td>
                                    <td>{{ $item->ms_penempatan_siswa_count }} siswa</td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">

                                            {{-- DETAIL --}}
                                            <a href="#ModalDetailKelas"
                                                data-bs-toggle="modal"
                                                class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                                title="Detail Kelas"
                                                wire:click.prevent="$emit('loadDetailKelas', {
                                                    kelasId: {{ $item->ms_kelas_id }},
                                                    namaKelas: '{{ addslashes($item->nama_kelas) }}',
                                                    jenjang: {{ $item->ms_jenjang_id }},
                                                    tahunAjar: {{ $item->ms_tahun_ajar_id }}
                                                })">
                                                <i class="ri-eye-line me-1"></i>
                                                Detail
                                            </a>

                                            {{-- PINDAH --}}
                                            <a href="#ModalChangeKelas"
                                                data-bs-toggle="modal"
                                                class="btn btn-primary btn-sm rounded-pill px-3"
                                                title="Pindah Siswa"
                                                wire:click.prevent="$emit('showKelas', {
                                                    jenjang: {{ $item->ms_jenjang_id }},
                                                    tahunAjar: {{ $item->ms_tahun_ajar_id }},
                                                    kelasId: {{ $item->ms_kelas_id }}
                                                })">
                                                <i class="ri-arrow-left-right-line me-1"></i>
                                                Pindah
                                            </a>

                                            {{-- NAIK --}}
                                            <a href="#ModalPromoteKelas"
                                                data-bs-toggle="modal"
                                                class="btn btn-success btn-sm rounded-pill px-3"
                                                title="Naik Kelas"
                                                wire:click.prevent="$emit('showPromote', {
                                                    jenjang: {{ $item->ms_jenjang_id }},
                                                    tahunAjar: {{ $item->ms_tahun_ajar_id }},
                                                    kelasId: {{ $item->ms_kelas_id }}
                                                })">
                                                <i class="ri-graduation-cap-line me-1"></i>
                                                Naik
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <!-- Jika Tidak Ada Data Kelas -->
                                <tr>
                                    <td colspan="8">
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
                                    {{ $kelass->firstItem() ?? 0 }}
                                </span>
                                -
                                <span class="fw-semibold">
                                    {{ $kelass->lastItem() ?? 0 }}
                                </span>
                                dari
                                <span class="fw-semibold">
                                    {{ $kelass->total() }}
                                </span>
                                data kelas
                            </div>
                            <div>
                                {{ $kelass->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        {{-- DATA --}}
    </div>
</div>