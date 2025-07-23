<div class="card">
    <div class="card-header border-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1"> Siswa</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    @if ($siswaSelected)
                        <button href="#ModalDeleteSiswa" data-bs-toggle="modal" class="btn btn-soft-danger" wire:click.prevent="$emit('confirmBulkDelete', {{ json_encode($siswaSelected) }})">
                            <i class="ri-delete-bin-2-line"></i> Hapus {{ count($siswaSelected) }}
                        </button>
                    @endif
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportSiswa" wire:click.prevent="$emit('showImportSiswa', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"  class="btn btn-soft-warning"><i class="ri-bank-card-line fs-17"></i> Update EduCard</button>
                    <button data-bs-toggle="modal" data-bs-target="#ModalImportSiswa" wire:click.prevent="$emit('showImportSiswa', {{ $selectedJenjang }}, {{ $selectedTahunAjar }})"  class="btn btn-soft-success"><i class="ri-whatsapp-line fs-17"></i> Update Nomor WhatsApp</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            <div class="col-xxl-8 col-sm-6">
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
             <div class="col-xxl-4 col-sm-6"> 
                <select wire:model="selectedKelas" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                    <option value="">Semua Kelas</option>
                    @foreach ($select_kelas as $item)    
                    <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                    @endforeach
                </select>
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
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" width="50px">no</th>
                            <th scope="col" style="width: 50px;">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="checkAll" wire:model="selectAll">
                                </div>
                            </th>
                            <th class="text-uppercase">siswa</th>
                            {{-- <th class="text-uppercase">L/P</th> --}}
                            <th class="text-uppercase">kelas</th>
                            <th class="text-uppercase">telepon</th>
                            <th class="text-uppercase">Tabungan</th>
                            <th class="text-uppercase">EduCard smart tap card</th>
                            <th class="text-uppercase">EduPay smart payment</th>
                            <th class="text-uppercase">TapCash edupay for cash</th>
                            <th class="text-uppercase">aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($siswas as $key => $item)
                        <tr>
                            <td>{{ $siswas->firstItem() + $key }}.</td> 
                            <td scope="row">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" wire:key="{{ $item->ms_penempatan_siswa_id }}" wire:model.live="siswaSelected" value="{{ $item->ms_penempatan_siswa_id }}">
                                </div>
                            </td>
                            <td>{{ $item->ms_siswa->nama_siswa }}</td>
                            <td>{{ $item->ms_siswa->telepon }} <span class="badge bg-success-subtle text-success">WhatsApp</span></td>
                            <td>{{ $item->ms_kelas->nama_kelas }}</td>
                            <td> <!-- Menampilkan saldo tabungan -->
                                <span class="fs-14 fw-semibold text-success">
                                    Rp{{ number_format($item->ms_siswa->saldo_tabungan(), 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                @if($item->ms_siswa->ms_educard)
                                    {{ $item->ms_siswa->ms_educard->kode_kartu }}
                                @else
                                    <em>Belum memiliki kartu</em>
                                @endif
                            </td>
                            <td> <!-- Menampilkan saldo tabungan -->
                                <span class="fs-14 fw-semibold text-warning">
                                    Rp{{ number_format($item->ms_siswa->saldo_edupay(), 0, ',', '.') }}
                                </span>
                            </td>
                            <td>
                                <ul class="list-inline hstack gap-2 mb-0">
                                    {{-- <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Detail Tagihan">
                                        <a href="#ModalDetailTagihan" data-bs-toggle="modal" class="text-info d-inline-block detail-item-btn" wire:click.prevent="$emit('showDetailTagihan', {{ $item->ms_penempatan_siswa_id }})">
                                            <i class="ri-eye-line fs-17 align-middle"></i> Tagihan
                                        </a>
                                    </li> --}}
                                    <li class="list-inline-item detail" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Detail Siswa">
                                        <a href="#ModalDetailSiswa" data-bs-toggle="modal" class="text-info d-inline-block detail-item-btn" wire:click.prevent="$emit('showDetailSiswa', {{ $item->ms_penempatan_siswa_id }})">
                                            <i class="ri-eye-line fs-17 align-middle"></i> Detail
                                        </a>
                                    </li>

                                    <li class="list-inline-item edit" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Siswa">
                                        <a href="#ModalEditSiswa" data-bs-toggle="modal" class="text-success d-inline-block edit-item-btn" wire:click.prevent="$emit('loadDataSiswa', {{ $item->ms_penempatan_siswa_id }})">
                                            <i class="ri-pencil-line fs-17 align-middle"></i> Edit
                                        </a>
                                    </li>
                                    <li class="list-inline-item delete" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Siswa">
                                        <a href="#ModalDeleteSiswa" data-bs-toggle="modal" class="text-danger d-inline-block delete-item-btn" wire:click.prevent="$emit('confirmDeleteSiswa', {{ $item->ms_penempatan_siswa_id }})">
                                            <i class="ri-delete-bin-5-line fs-17 align-middle"></i>
                                        </a>
                                    </li>
                                </ul>
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
                {{ $siswas->links() }}
            </div>
            @endif
        </div>
        {{-- DATA --}}
    </div>
</div>