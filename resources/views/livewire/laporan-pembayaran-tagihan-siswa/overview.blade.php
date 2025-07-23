{{-- If your happiness depends on money, you will never be happy with yourself. --}}
<div class="card mb-1">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <h5 class="card-title mb-0 flex-grow-1">Rangkuman Pembayaran Tagihan Siswa</h5>
            @if ($selectedJenjang && $selectedTahunAjar)
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button data-bs-toggle="modal" data-bs-target="#ExportOverviewPembayaran" wire:click.prevent="showExportOverviewPembayaran"  class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                </div>
            </div>
            @endif
        </div>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <div class="row g-2 align-items-center">
                <!-- Label di sisi kiri -->
                <div class="col-auto">
                    <label for="startDate" class="form-label text-muted text-uppercase fs-12 fw-medium mb-0">Periode </label>
                </div>
                <!-- Input tanggal di sisi kanan -->
                <div class="col">
                    <div class="row g-2 align-items-center">
                        <div class="col-lg">
                            <input type="date" class="form-control" wire:model="startDate" placeholder="0">
                        </div>
                        <div class="col-lg">
                            <input type="date" class="form-control" wire:model="endDate" placeholder="0">
                        </div>
                    </div>
                </div>
                <div class="col-auto">
                    <button type="button" class="btn btn-soft-secondary btn-icon rounded-circle" wire:click="resetTanggal" title="Reset Tanggal">
                        <i class="ri-refresh-line fs-16"></i>
                    </button>    
                </div>
            </div>
        </div>        
        {{-- DATA --}}
        <div class="live-preview">
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
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase">Periode</th>
                            <th class="text-uppercase text-end">Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($months as $month)
                        <tr>
                            <td style="width: 50px">{{ $loop->iteration }}.</td>
                            <td class="text-uppercase">{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($month['bulan'], 'F Y') }}</td>
                            <td class="text-end">
                                <span class="fs-14 text-success">Rp {{ number_format($month['total'], 0, ',', '.') }}</span>
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
                        @php
                            $totalMethods = array_sum(array_column($methods, 'total'));
                        @endphp
                        <tr class="">
                            <td></td>
                            <td class="text-start"><strong>TOTAL</strong></td>
                            <td class="text-end">
                                <span class="fs-14 text-success">Rp {{ number_format($totalMethods, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase">Metode</th>
                            <th class="text-uppercase text-end">Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($methods as $item)
                        <tr>
                            <td style="width: 50px">{{ $loop->iteration }}.</td>
                            <td class="text-uppercase">{{ $item['metode'] }}</td>
                            <td class="text-end">
                                <span class="fs-14 text-success">Rp {{ number_format($item['total'], 0, ',', '.') }}</span>
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
                        @php
                            $totalMonths = array_sum(array_column($months, 'total'));
                        @endphp
                        <tr class="">
                            <td></td>
                            <td class="text-start"><strong>TOTAL</strong></td>
                            <td class="text-end">
                                <span class="fs-14 text-success">Rp {{ number_format($totalMonths, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase">Kelas</th>
                            <th class="text-uppercase text-end">Pembayaran</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($classes as $class)
                        <tr>
                            <td style="width: 50px">{{ $loop->iteration }}.</td>
                            <td class="text-uppercase">{{ $class['nama_kelas'] }}</td>
                            <td class="text-end">
                                <span class="fs-14 text-success">Rp {{ number_format($class['total'], 0, ',', '.') }}</span>
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
                        @php
                            $totalClasses = array_sum(array_column($classes, 'total'));
                        @endphp
                        <tr class="">
                            <td></td>
                            <td class="text-start"><strong>TOTAL</strong></td>
                            <td class="text-end">
                                <span class="fs-14 text-success">Rp {{ number_format($totalClasses, 0, ',', '.') }}</span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            @endif
        </div>

        {{-- end data --}}
    </div>
</div>

