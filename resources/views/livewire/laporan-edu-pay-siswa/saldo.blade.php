{{-- Be like water. --}}
<div class="sticky-side-div">
    <div class="card">
        <div class="card-header border-0 pb-0">
            <div class="d-flex align-items-center">
                <h5 class="card-title mb-0 flex-grow-1">Saldo EduPay Uang Digital</h5>
                <div class="flex-shrink-0">
                    <div class="d-flex gap-2 flex-wrap">
                        @if ($selectedKelas)
                        <button data-bs-toggle="modal" data-bs-target="#WithdrawEduPay" wire:click.prevent="$emit('withdrawEduPay', {selectedKelas: '{{ $selectedKelas }}', selectedJenjang: '{{ $selectedJenjang }}', selectedTahunAjar: '{{ $selectedTahunAjar }}'})" class="btn btn-danger"><i class="ri-file-excel-2-line pb-0"></i> Kosongkan Edupay</button>
                        @endif
                        <button data-bs-toggle="modal" data-bs-target="#ExportSaldoEdupaySiswa" wire:click.prevent="ExportSaldoEduPaySiswa"  class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end mb-3">
                <!-- Dropdown Kelas -->
                <div class="col-xxl-4 col-sm-6">
                    <label for="selectKelas" class="form-label">Kelas</label>
                    <select id="selectKelas" 
                            wire:model="selectedKelas" 
                            class="form-select" style="cursor: pointer"
                            data-bs-toggle="tooltip" data-bs-trigger="hover" 
                            data-bs-placement="top" title="Pilih Kelas">
                        <option value="">Semua Kelas</option>
                        @foreach ($select_kelas as $item)    
                            <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Input Pencarian -->
                <div class="col-xxl-8 col-sm-6">
                    <label for="searchInput" class="form-label">Pencarian</label>
                    <div class="position-relative">
                        <input type="text" id="searchInput" 
                            class="form-control ps-4" 
                            wire:model.debounce.300ms="search" 
                            placeholder="Cari nama, deskripsi, atau lainnya...">
                        <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                    </div>
                </div>
            </div>

            <!--end row-->
            {{-- DATA --}}
            <div class="live-preview">
                <div class="table-responsive">
                    <table class="table table-hover nowrap align-middle" style="width:100%">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase" style="width: 50px;">NO</th>
                                <th class="text-uppercase">Siswa</th>
                                <th class="text-uppercase">EduCard</th>
                                <th class="text-uppercase text-center">Saldo EduPay</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}.</td> 
                                    <td class="text-start" style="white-space: nowrap;">
                                        {{ ucfirst($item->nama_siswa) }}
                                        <p class="fs-12 mb-0 text-muted">{{ $item->ms_kelas->nama_kelas ?? ''}}</p>
                                    </td>
                                    <td style="white-space: nowrap;">
                                        @if($item->ms_siswa->ms_educard)
                                        <span class="fs-14 text-warning">    
                                            {{ $item->ms_siswa->ms_educard->kode_kartu }}
                                        </span>
                                        @else
                                            <em>Belum memiliki kartu</em>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <span class="fs-14 text-info">
                                            RP{{ number_format($item->ms_siswa->saldo_edupay_siswa() ?? 0, 0, ',', '.') }}
                                        </span>
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
                        <tfoot>
                            <tr class="">
                                <td></td>
                                <td></td>
                                <td class="text-uppercase text-start">TOTAL</td>
                                <td class="text-center">
                                    <span class="fs-14 text-info">
                                        RP{{ number_format($totalSaldo, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    {{-- {{ $siswas->links() }} --}}
                </div>
            </div>
            {{-- end data --}}
        </div>
    </div>
</div>

