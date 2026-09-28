{{-- Stop trying to control. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="card-title mb-0">Laporan Mutasi Bank</h5>
                {{-- <p class="mb-0">Transaksi akan ditampilkan dari semua petugas untuk memastikan penghitungan yang akurat dan terkini.</p> --}}
            </div>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#filterTabungan" aria-controls="filterTabungan"><i class="ri-filter-3-line align-bottom me-1"></i> Fliters</button>
                    <button data-bs-toggle="modal" data-bs-target="#ExportTabunganSiswa" wire:click.prevent="" class="btn btn-soft-success"><i class="ri-file-excel-2-line"></i> Export</button>
                    {{-- <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTransaksi" aria-controls="offcanvasTransaksi" class="btn btn-primary shadow-none"><i class="ri-file-excel-2-line"></i> Transaksi</button> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-0">
            <div class="col-xxl-12 col-sm-6">
                <div class="search-box">
                    <input type="text" class="form-control search" wire:model.debounce.300ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
            <div class="col-xxl-4 col-sm-6">
                
            </div>
        </div>
        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
            <div class="table-responsive">
                @php
                    $saldo = 0;
                @endphp
                <table class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase text start" scope="col" style="width: 200px;">Tanggal</th>
                            <th class="text-uppercase text-start">Petugas</th>
                            <th class="text-uppercase text-start" style="min-width: 500px;">Deskripsi Transaksi</th>
                            <th class="text-uppercase text-center">Debit</th>
                            <th class="text-uppercase text-center">Kredit</th>
                            <th class="text-uppercase text-center">Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $item)
                            <tr class="text-center">
                                <td class="text-start">{{ $loop->iteration }}.</td>
                                <td class="text-start" style="white-space: nowrap;">{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal_transaksi, 'd F Y H:i:s') }}</td>
                                <td class="text-start">{{ $item->ms_pengguna->nama }}</td>
                                <td class="text-start">{{ $item->deskripsi }}</td>
                                <td>
                                    <span class="fs-14 text-success">
                                        {{ $item->posisi === 'debit' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fs-14 text-danger">
                                        {{ $item->posisi === 'kredit' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        // Perhitungan saldo
                                        if ($item->posisi === 'debit') {
                                            $saldo += $item->nominal; // Tambahkan saldo
                                        } elseif ($item->posisi === 'kredit') {
                                            $saldo -= $item->nominal; // Kurangi saldo
                                        }
                                    @endphp
                                    <span class="fs-14 text-info">
                                        RP{{ number_format($saldo, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @empty
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
                    <tfoot>
                        <tr class="">
                            <td></td>
                            <td class="text-uppercase">Total Debet</td>
                            <td colspan="1" class="text-end">
                                <span class="fs-14 text-success">
                                    RP{{ number_format($totalDebit, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr class="">
                            <td></td>
                            <td class="text-uppercase">Total Kredit</td>
                            <td colspan="1" class="text-end">
                                <span class="fs-14 text-danger">
                                    RP{{ number_format($totalKredit, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr class="">
                            <td></td>
                            <td class="text-uppercase">Total Saldo</td>
                            <td colspan="1" class="text-end">
                                <span class="fs-14 text-info">
                                    RP{{ number_format($totalSaldo, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>