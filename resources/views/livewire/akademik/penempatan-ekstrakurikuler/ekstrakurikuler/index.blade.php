<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-trophy-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Data Ekstrakurikuler
                        </h5>
                        <small class="text-muted">Kelola daftar ekstrakurikuler pada jenjang terpilih.</small>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            @if ($selectedJenjang)
            <div class="d-flex gap-2 flex-wrap">
                <button type="button" class="btn btn-primary rounded-pill px-4"
                    data-bs-toggle="modal" data-bs-target="#ModalAddEkstrakurikuler"
                    wire:click.prevent="$emit('createEkstrakurikuler', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})">
                    <i class="ri-add-line me-1"></i>Ekstrakurikuler Baru
                </button>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-12 col-sm-12">
                <label for="searchEkstrakurikuler" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchEkstrakurikuler" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari Ekstrakurikuler...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
        </div>
        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
            <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
            @if (!$selectedJenjang)
                <div class="text-center py-4">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                        colors="primary:#405189,secondary:#08a88a"
                        style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Silakan Pilih Jenjang</h5>
                    <p class="text-muted mb-0">Untuk melihat data ekstrakurikuler, harap pilih Jenjang terlebih dahulu.</p>
                </div>
            @else
                <!-- Tabel Data Ekstrakurikuler -->
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr class="text-uppercase">
                                <th class="text-center">no</th>
                                <th>ekstrakurikuler</th>
                                <th>biaya</th>
                                <th class="text-center">kuota</th>
                                <th class="text-center">aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($data as $item)
                                <tr>
                                    <td class="text-center">{{ $loop->iteration }}</td>
                                    
                                    <td>
                                        <span class="fw-medium">
                                            {{ $item->nama_ekstrakurikuler }}
                                        </span>
                                        {{-- <p class="text-muted mb-0">{{ $item->deskripsi }}</p> --}}
                                    </td>
                                    <td class="">
                                        <span class="fw-medium fs-12">
                                            Rp{{ number_format($item->biaya, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                            $kuota = max((int) ($item->kuota ?? 0), 0);
                                            $terisi = (int) ($item->ms_penempatan_ekstrakurikuler_count ?? 0);
                                            $progressKuota = $kuota > 0
                                                ? min((int) round(($terisi / $kuota) * 100), 100)
                                                : 0;
                                        @endphp
                                        <div class="d-flex flex-column gap-1 mx-4" style="min-width: 60px">
                                            <span class="small fw-semibold text-end">{{ $progressKuota }}%</span>
                                            <div class="progress" role="progressbar"
                                                aria-label="Kapasitas {{ $item->nama_ekstrakurikuler }}"
                                                aria-valuenow="{{ $progressKuota }}" aria-valuemin="0" aria-valuemax="100"
                                                style="height: 6px">
                                                <div class="progress-bar bg-primary" style="width: {{ $progressKuota }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            {{-- Edit --}}
                                            <button
                                                class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                                data-bs-toggle="modal"
                                                data-bs-target="#editEkstrakurikuler"
                                                title="Edit Ekstrakurikuler"
                                                wire:click.prevent="$emit('loadEkstrakurikuler', {{ $item->ms_ekstrakurikuler_id }})">
                                                <i class="ri-mark-pen-line me-1"></i> Edit
                                            </button>
                                             {{-- Detail --}}
                                            <button
                                                class="btn btn-primary btn-sm rounded-pill px-3"
                                                data-bs-toggle="offcanvas"
                                                data-bs-target="#detailEkstrakurikuler"
                                                title="Detail Ekstrakurikuler"
                                                wire:click.prevent="$emit('detailEkstrakurikuler', {{ $item->ms_ekstrakurikuler_id }})">
                                                <i class="ri-user-line me-1"></i> Detail
                                            </button>
                                        </div>
                                    </td>                                  
                                </tr>
                            @empty
                                <!-- Jika Tidak Ada Data Ekstrakurikuler -->
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
                                    {{ $data->firstItem() ?? 0 }}
                                </span>
                                -
                                <span class="fw-semibold">
                                    {{ $data->lastItem() ?? 0 }}
                                </span>
                                dari
                                <span class="fw-semibold">
                                    {{ $data->total() }}
                                </span>
                                data ekstrakurikuler
                            </div>
                            <div>
                                {{ $data->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
        {{-- DATA --}}
    </div>
</div>