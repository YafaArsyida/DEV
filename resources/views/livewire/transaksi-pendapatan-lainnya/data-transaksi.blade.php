<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex align-items-center flex-wrap gap-3">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-bank-card-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Riwayat Transaksi Pendapatan Lainnya
                        </h5>
                        {{-- <small class="text-muted">
                            Riwayat transaksi tabungan siswa berdasarkan periode yang dipilih.
                        </small> --}}
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                {{-- <button data-bs-toggle="modal" data-bs-target="#ExportLaporan" class="btn rounded-pill px-4 btn-soft-success">
                    <i class="ri-file-excel-2-line pb-0"></i> Export
                </button>
                <button wire:click="cetakLaporan" class="btn rounded-pill px-4 btn-danger">
                    <i class="ri-printer-line align-bottom"></i>
                    <span>Cetak Laporan</span>
                </button> --}}
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
                <label for="selectRekening" class="form-label small text-muted text-uppercase fw-medium mb-2">Jenis Transaksi</label>
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
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                <div class="search-box">
                    <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table id="data" class="table table-hover table-nowrap align-middle">
                <thead class="table-light">
                    <tr class="text-uppercase text-center">
                        <th style="width: 50px;">NO</th>
                        <th style="width: 50px;">hapus</th>
                        <th class="text-start">tanggal</th>
                        <th class="text-start">transaksi</th>
                        <th>petugas</th>
                        <th>metode</th>
                        <th>nominal</th>
                        <th>total</th>
                        <th class="text-start">aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="table-secondary fw-medium text-center">
                        <td colspan="6">
                            <i class="ri-wallet-3-line me-1"></i>
                            Pendapatan Sebelum Periode
                        </td>

                        <td class="text-center text-success">-</td>
                        <td>
                            <span class="fs-12">
                                Rp{{ number_format($saldoAwal, 0, ',', '.') }}
                            </span>
                        </td>

                        <td></td>
                    </tr>
                    @forelse ($data as $item)
                    @php
                        $dibatalkan = $item->status_transaksi === 'dibatalkan';

                        $bolehDibatalkan = !$dibatalkan;

                        $titlePembatalan = $bolehDibatalkan
                            ? 'Batalkan Transaksi'
                            : 'Transaksi sudah dibatalkan';

                        $bolehEdit = !$dibatalkan;

                        $tooltipEdit = $bolehEdit
                            ? 'Edit Transaksi'
                            : 'Transaksi sudah dibatalkan';
                    @endphp

                    <tr class="text-center {{ $dibatalkan ? 'table-danger' : '' }}">

                        {{-- Kolom nomor urut --}}
                        <td style="width: 50px">
                            {{ $loop->iteration }}.
                        </td>

                        {{-- Kolom pembatalan --}}
                        <td>
                            @if ($bolehDibatalkan)
                                <a href="#deletePendapatanLainnya"
                                    data-bs-toggle="modal"
                                    class="text-danger d-inline-block remove-item-btn"
                                    wire:click.prevent="$emit(
                                        'confirmDeletePendapatanLainnya',
                                        {{ $item->transaksi_pendapatan_lainnya_id }}
                                    )"
                                    data-bs-trigger="hover"
                                    data-bs-placement="top"
                                    title="{{ $titlePembatalan }}">

                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </a>
                            @else
                                <a href="javascript:void(0);"
                                    class="text-muted d-inline-block remove-item-btn"
                                    aria-disabled="true"
                                    tabindex="-1"
                                    data-bs-trigger="hover"
                                    data-bs-placement="top"
                                    title="{{ $titlePembatalan }}">

                                    <i class="ri-delete-bin-5-fill fs-14"></i>
                                </a>
                            @endif
                        </td>

                        {{-- Kolom tanggal transaksi --}}
                        <td class="text-uppercase text-start">
                            <div class="fw-medium">
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal, 'd F Y') }}
                            </div>

                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($item->tanggal)->format('H:i') }}
                            </small>
                        </td>

                        {{-- Kolom detail transaksi --}}
                        <td class="text-start">

                            <span class="fs-12 fw-medium
                                {{ $dibatalkan ? 'text-muted text-decoration-line-through' : '' }}">

                                {!! 'Rp' . number_format($item->nominal, 0, ',', '.') . ' - <i>' .
                                    ucfirst($item->akuntansi_rekening->nama_rekening) . '</i>' !!}
                            </span>

                            @if ($dibatalkan)
                                <span class="badge bg-danger ms-1">
                                    Dibatalkan
                                </span>
                            @endif

                            <p class="text-muted mb-0">
                                {{ $item->deskripsi ?? '' }}
                            </p>

                        </td>

                        {{-- Petugas --}}
                        <td>
                            {{ $item->ms_pengguna->nama ?? 'Tidak Diketahui' }}
                        </td>

                        {{-- Metode pembayaran --}}
                        <td>
                            {{ $item->metode_pembayaran }}
                        </td>

                        {{-- Kolom nominal pendapatan --}}
                        <td class="text-center">

                            <span class="fs-12 fw-medium
                                {{ $dibatalkan
                                    ? 'text-muted text-decoration-line-through'
                                    : 'text-success'
                                }}">

                                Rp{{ number_format($item->nominal, 0, ',', '.') }}

                            </span>

                        </td>

                        {{-- Saldo --}}
                        <td>
                            <span class="fs-12 fw-medium">
                                Rp{{ number_format($item->saldo, 0, ',', '.') }}
                            </span>
                        </td>

                        {{-- Kolom aksi --}}
                        <td class="text-start">

                            @if ($bolehEdit)

                                <a href="#editPendapatanLainnya"
                                    data-bs-toggle="modal"
                                    wire:click.prevent="$emit(
                                        'editPendapatanLainnya',
                                        {{ $item->transaksi_pendapatan_lainnya_id }}
                                    )"
                                    class="btn btn-primary btn-sm rounded-pill px-3"
                                    data-bs-trigger="hover"
                                    data-bs-placement="top"
                                    title="{{ $tooltipEdit }}">

                                    <i class="ri-mark-pen-line me-1"></i>
                                    <span>Edit</span>

                                </a>

                            @else

                                <a href="javascript:void(0);"
                                    class="btn btn-secondary btn-sm rounded-pill px-3 disabled"
                                    aria-disabled="true"
                                    tabindex="-1"
                                    data-bs-trigger="hover"
                                    data-bs-placement="top"
                                    title="{{ $tooltipEdit }}"
                                    style="pointer-events: none;">

                                    <i class="ri-mark-pen-line me-1"></i>
                                    <span>Edit</span>

                                </a>

                            @endif

                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="9">
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
    <div class="modal fade zoomIn" id="ExportLaporan" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true"
        wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 text-center">
                    <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop"
                        colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                    <div class="mt-4 text-center">
                        <h4 class="fs-semibold">Konfirmasi Export</h4>
                        <p class="text-muted fs-14 mb-4 pt-1">
                            Apakah Anda yakin ingin mengekspor laporan Pendapatan Lainnya? Data yang diekspor akan
                            sesuai dengan tabel yang ditampilkan.
                        </p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none"
                                data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button class="btn btn-primary rounded-pill px-4" id="konfirmasiExportLaporan" data-bs-dismiss="modal">Ya,
                                Export!</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('konfirmasiExportLaporan').addEventListener('click', function () {
            alertify.success("Menyiapkan Dokumen");
            // Tambahkan delay 1 detik
            setTimeout(function () {
                // Ambil elemen tabel berdasarkan ID
                var table = document.getElementById("data");
                
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Data Transaksi Pendapatan Lainnya.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>