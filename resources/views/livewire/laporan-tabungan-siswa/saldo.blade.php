{{-- Be like water. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="card-title mb-0">Saldo Tabungan Siswa</h5>
            </div>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    @if ($selectedKelas)
                    <button data-bs-toggle="modal" data-bs-target="#withdrawTabunganSiswa" wire:click.prevent="$emit('withdrawTabunganSiswa', {selectedKelas: '{{ $selectedKelas }}', selectedJenjang: '{{ $selectedJenjang }}', selectedTahunAjar: '{{ $selectedTahunAjar }}'})" class="btn btn-danger"><i class="ri-file-excel-2-line pb-0"></i> Kosongkan Tabungan</button>
                    @endif
                    <button data-bs-toggle="modal" data-bs-target="#ExportSaldoTabunganSiswa" wire:click.prevent="ExportSaldoTabunganSiswa"  class="btn btn-soft-success"><i class="ri-file-excel-2-line"></i> Export</button>
                </div>
            </div>
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
            <div class="table-responsive">
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase" style="width: 50px;">NO</th>
                            <th class="text-uppercase">Siswa</th>
                            <th class="text-uppercase text-center">Saldo Tabungan</th>
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
                                <td class="text-center">
                                    <span class="fs-14 text-info">
                                        RP{{ number_format($item->ms_siswa->saldo_tabungan_siswa() ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>
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
                    <tfoot>
                        <tr class="">
                            <td></td>
                            <td class="text-uppercase text-start">TOTAL</td>
                            <td class="text-center">
                                <span class="fs-14 text-info">
                                    Rp{{ number_format($totalSaldo, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        {{-- end data --}}
    </div>
</div>

