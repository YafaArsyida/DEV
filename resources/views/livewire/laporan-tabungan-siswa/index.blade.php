{{-- Stop trying to control. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="card-title mb-0">Laporan Transaksi Tabungan Siswa</h5>
                {{-- <p class="mb-0">Transaksi akan ditampilkan dari semua petugas untuk memastikan penghitungan yang akurat dan terkini.</p> --}}
            </div>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button type="button" class="btn btn-info" data-bs-toggle="offcanvas" data-bs-target="#filterTabungan" aria-controls="filterTabungan"><i class="ri-filter-3-line align-bottom me-1"></i> Fliters</button>
                    <button data-bs-toggle="modal" data-bs-target="#ExportTabunganSiswa" wire:click.prevent="showExportTabunganSiswa" class="btn btn-soft-success"><i class="ri-file-excel-2-line"></i> Export</button>
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
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase text start" scope="col" style="width: 200px;">Tanggal</th>
                            <th class="text-uppercase">Siswa</th>
                            <th class="text-uppercase" scope="col">Transaksi</th>
                            <th class="text-uppercase text-center">Petugas</th>
                            <th class="text-uppercase text-center">Kredit</th>
                            <th class="text-uppercase text-center">Debit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($laporan as $item)
                            <tr class="text-center">
                                <td class="text-start">{{ $loop->iteration }}.</td>
                                <td class="text-uppercase text-start">
                                    {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal) }}
                                </td>
                                <td class="text-start" style="white-space: nowrap;">
                                    {{ ucfirst($item->ms_siswa->nama_siswa) }}
                                    <p class="fs-12 mb-0 text-muted">{{ $item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? ''}}</p>
                                </td>
                                <td class="text-start">
                                    <span class="fs-14">
                                        {!! 'RP' . number_format($item->nominal, 0, ',', '.') . ' - <i>' . ucfirst($item->jenis_transaksi) . '</i>' !!}
                                    </span>
                                    <p class="text-muted mb-0">{{ $item->deskripsi ?? '' }}</p>
                                </td>
                                <td style="white-space: nowrap;">{{ $item->ms_pengguna->nama ?? '-' }}</td>
                                <td>
                                    <span class="fs-14 text-success">
                                        {{ $item->jenis_transaksi === 'setoran' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
                                    </span>
                                </td>
                                <td>
                                    <span class="fs-14 text-danger">
                                        {{ $item->jenis_transaksi === 'penarikan' ? 'RP' . number_format($item->nominal, 0, ',', '.') : '-' }}
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
                            <td class="text-uppercase">Total Kredit</td>
                            <td colspan="1" class="text-end">
                                <span class="fs-14 text-success">
                                    RP{{ number_format($totalKredit, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        <tr class="">
                            <td></td>
                            <td class="text-uppercase">Total Debit</td>
                            <td colspan="1" class="text-end">
                                <span class="fs-14 text-danger">
                                    RP{{ number_format($totalDebit, 0, ',', '.') }}
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