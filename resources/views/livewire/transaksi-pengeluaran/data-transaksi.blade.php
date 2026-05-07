<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <h5 class="card-title mb-0 flex-grow-1">Riwayat Transaksi Pengeluaran</h5>
            <div class="d-flex gap-2 flex-wrap">
                <button data-bs-toggle="modal" data-bs-target="#ExportLaporan" class="btn btn-soft-success">
                    <i class="ri-file-excel-2-line pb-0"></i> Export
                </button>
                <button wire:click="cetakLaporan" class="btn btn-danger d-inline-flex align-items-center gap-1">
                    <i class="ri-printer-line align-bottom"></i>
                    <span>Cetak Laporan</span>
                </button>
            </div>
            <div class="d-flex align-items-center gap-2">
                <input type="date" class="form-control" wire:model="startDate">
                <span class="text-muted">–</span>
                <input type="date" class="form-control" wire:model="endDate">
                <button type="button" class="btn btn-soft-secondary" wire:click="resetTanggal" title="Reset Tanggal">
                    <i class="ri-refresh-line"></i>
                </button>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
            <!-- Dropdown Transaksi -->
            <div class="col-xxl-4 col-md-6">
                <label for="selectRekening" class="form-label">Jenis Transaksi</label>
                <select id="selectRekening" wire:model="selectedRekening" class="form-select" data-bs-toggle="tooltip"
                    data-bs-trigger="hover" data-bs-placement="top" title="Pilih Jenis Transaksi">
                    <option value="">Semua Transaksi</option>
                    @foreach ($select_transaksi as $item)
                    <option value="{{ $item->kode_rekening }}">{{ $item->nama_rekening }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Input Pencarian -->
            <div class="col-xxl-8 col-md-6">
                <label for="searchInput" class="form-label">Pencarian</label>
                <div class="position-relative">
                    <input type="text" id="searchInput" class="form-control ps-4" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-borderless table-hover text-center table-nowrap align-middle mb-0">
                <thead class="table-light">
                    <tr class="table-active">
                        <th style="width: 50px;" class="text-uppercase">NO</th>
                        <th class="text-uppercase" scope="col" style="width: 50px;">hapus</th>
                        <th class="text-start text-uppercase" scope="col" style="width: 150px;">tanggal</th>
                        <th class="text-start text-uppercase" scope="col">transaksi</th>
                        <th class="text-uppercase text-center" scope="col">petugas</th>
                        <th class="text-uppercase text-center" scope="col">nominal</th>
                        <th class="text-uppercase text-center">total</th>
                        <th class="text-start text-uppercase">aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-secondary fw-semibold">
                        <td colspan="5">
                            <i class="ri-wallet-3-line me-1"></i>
                            Pendapatan Awal Periode
                        </td>

                        <td class="text-center text-success">-</td>

                        <td>
                            <span class="fs-14 text-info">
                                RP{{ number_format($saldoAwal, 0, ',', '.') }}
                            </span>
                        </td>

                        <td></td>
                    </tr>
                    @forelse ($data as $item)
                    <tr>
                        <!-- Kolom nomor urut -->
                        <td style="width: 50px">{{ $loop->iteration }}.</td>
                        <!-- Kolom hapus -->
                        <td>
                            <a href="#deletePengeluaran" data-bs-toggle="modal"
                                class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1"
                                wire:click.prevent="$emit('confirmDeletePengeluaran', {{ $item->ms_pengeluaran_id }})"
                                data-bs-trigger="hover" data-bs-placement="top" title="Hapus Transaksi">
                                <i class="ri-delete-bin-5-line align-bottom"></i>
                            </a>
                        </td>

                        <!-- Kolom tanggal transaksi -->
                        <td class="text-uppercase text-start">
                            {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal) }}
                        </td>
                        <td class="text-start">
                            <span class="fs-14">
                                {!! 'RP' . number_format($item->nominal, 0, ',', '.') . ' - <i>' .
                                    ucfirst($item->akuntansi_rekening->nama_rekening) . '</i>' !!}
                            </span>
                            <p class="text-muted mb-0">{{ $item->deskripsi ?? '' }}</p>
                        </td>
                        <td>
                            <span class="fs-14">
                                {{ $item->metode_pembayaran }}
                            </span>
                            <p class="text-muted mb-0">{{ $item->ms_pengguna->nama ?? 'Tidak Diketahui' }}</p>
                        </td>
                        <!-- Kolom nominal pendapatan -->
                        <td class="text-center">
                            <span class="fs-14 text-success">
                                RP{{ number_format($item->nominal, 0, ',', '.') }}
                            </span>
                        </td>
                        <td>
                            <span class="fs-14 text-info">
                                RP{{ number_format($item->saldo, 0, ',', '.') }}
                            </span>
                        </td>
                        <!-- Kolom aksi -->
                        <td class="text-start">
                            <a href="#editPengeluaran" data-bs-toggle="modal"
                                wire:click.prevent="$emit('editPengeluaran', {{ $item->ms_pengeluaran_id }})"
                                class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                <i class="ri-quill-pen-line align-bottom"></i>
                                <span>Edit Transaksi</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8">
                            <div class="noresult text-center py-3">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                    colors="primary:#405189,secondary:#08a88a" style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak
                                    ditemukan hasil yang sesuai.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <!--end table-->
        </div>
    </div>
</div>