<div class="row">
    <div class="col-xxl-12 col-sm-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body">
                <div class="row g-3 align-items-end mb-3">
                     <div class="col-xxl-3 col-sm-6">
                        <label class="form-label small text-muted text-uppercase fw-medium mb-2">Rekening</label>
                        <select class="form-select" wire:model="selectedRekening">
                            <option value="">Pilih Rekening</option>
                            @foreach ($jenisAkunRekening as $rekening)
                                <option value="{{ $rekening->kode_rekening }}">
                                    {{ $rekening->kode_rekening }} - {{ $rekening->nama_rekening }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-xxl-5 col-sm-12">
                        <label for="searchTagihan" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                        <div class="search-box">
                            <input type="text" id="searchTagihan" class="form-control search" wire:model.debounce.300ms="search"
                                placeholder="Cari nomor jurnal atau deskripsi...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>

                    <div class="col-xxl-4 col-sm-6">
                        <label class="form-label small text-muted text-uppercase fw-medium mb-2">Periode</label>
                        <div class="d-flex align-items-center gap-2">
                            <input type="date" id="startDate" class="form-control" wire:model="startDate" value="{{ $startDate }}">
                            <span class="text-muted">–</span>
                            <input type="date" id="endDate" class="form-control" wire:model="endDate" value="{{ $endDate }}">
                            <div class="col-auto">
                                <button type="button" class="btn btn-soft-secondary btn-icon rounded-circle" wire:click="resetTanggal" title="Reset Tanggal">
                                    <i class="ri-refresh-line fs-16"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($selectedRekeningData)
    <div class="col-xxl-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                    <div>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-sm">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                    <i class="ri-bank-line"></i>
                                </div>
                            </div>

                            <div>
                                <h5 class="fw-bold mb-1">{{ $selectedRekeningData->nama_rekening }}</h5>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <span class="badge bg-primary-subtle text-primary">{{ $selectedRekeningData->kode_rekening }}</span>
                                    <span class="text-muted fs-13">Posisi normal: {{ $selectedRekeningData->posisi_normal }}</span>
                                </div>
                            </div>
                        </div>

                        @if ($selectedRekeningData->deskripsi)
                            <p class="text-muted mb-0 mt-2 ms-lg-5">{{ $selectedRekeningData->deskripsi }}</p>
                        @endif
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <div class="d-flex gap-2 flex-wrap">
                            <button type="button"
                                class="btn btn-success rounded-pill px-4"
                                data-bs-toggle="modal"
                                data-bs-target="#ModalExportBukuBesar">

                                <i class="ri-file-excel-2-line me-1"></i>
                                Excel
                            </button>
                            {{-- CETAK --}}
                            @if ($selectedJenjang)
                                <button type="button" wire:click="cetakLaporan"
                                    class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-1">
                                    <i class="ri-printer-line"></i>
                                    <span>Cetak</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="live-preview">
                    <div class="table-responsive">
                    {{-- <div class="table-responsive" style="max-height: 500px;" data-simplebar> --}}
                        @php $saldo = $saldoAwalHalaman; @endphp
                        <table id="Data" class="table table-hover table-nowrap align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-uppercase">No</th>
                                    <th class="text-uppercase text-start">Tanggal</th>
                                    <th class="text-uppercase text-start">Nomor Jurnal</th>
                                    <th class="text-uppercase text-start">Petugas</th>
                                    <th class="text-uppercase text-start">Deskripsi Transaksi</th>
                                    <th class="text-uppercase text-center">Debit</th>
                                    <th class="text-uppercase text-center">Kredit</th>
                                    <th class="text-uppercase text-center">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="table-secondary fw-medium text-center">
                                    <td colspan="7">
                                        <i class="ri-wallet-3-line me-1"></i>
                                        Saldo Awal
                                    </td>
                                    <td class="fs-12 text-end">Rp{{ number_format($saldoAwalHalaman, 0, ',', '.') }}</td>
                                </tr>

                                @forelse ($transaksiJurnal as $key => $transaksi)
                                    <tr>
                                        <td class="text-start">{{ $transaksiJurnal->firstItem() + $key }}.</td>
                                        <td class="text-start">{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($transaksi->akuntansi_jurnal->tanggal_transaksi, 'd F Y H:i:s') }}</td>
                                        <td class="text-start">{{ $transaksi->akuntansi_jurnal->nomor_jurnal }}</td>
                                        <td class="text-start">{{ optional($transaksi->akuntansi_jurnal->ms_pengguna)->nama }}</td>
                                        <td class="text-start">{{ $transaksi->akuntansi_jurnal->deskripsi }}</td>
                                        <td class="text-end">
                                            <span class="fs-12 text-success">
                                                {{ $transaksi->posisi === 'debit' ? 'Rp' . number_format($transaksi->nominal, 0, ',', '.') : '-' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <span class="fs-12 text-danger">
                                                {{ $transaksi->posisi === 'kredit' ? 'Rp' . number_format($transaksi->nominal, 0, ',', '.') : '-' }}
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            @php
                                                if ($selectedRekeningData->posisi_normal === 'debit') {
                                                    if ($transaksi->posisi === 'debit') {
                                                        $saldo += $transaksi->nominal;
                                                    } else {
                                                        $saldo -= $transaksi->nominal;
                                                    }
                                                } else {
                                                    if ($transaksi->posisi === 'kredit') {
                                                        $saldo += $transaksi->nominal;
                                                    } else {
                                                        $saldo -= $transaksi->nominal;
                                                    }
                                                }
                                            @endphp
                                            <span class="fs-12">Rp{{ number_format($saldo, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Tidak ada data transaksi</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="text-muted fs-13">
                                    Menampilkan
                                    <span class="fw-semibold">
                                        {{ $transaksiJurnal->firstItem() ?? 0 }}
                                    </span>
                                    -
                                    <span class="fw-semibold">
                                        {{ $transaksiJurnal->lastItem() ?? 0 }}
                                    </span>
                                    dari
                                    <span class="fw-semibold">
                                        {{ $transaksiJurnal->total() }}
                                    </span>
                                    transaksi
                                </div>
                                <div>
                                    {{ $transaksiJurnal->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade zoomIn" id="ModalExportBukuBesar" tabindex="-1"
            aria-labelledby="exportBukuBesarLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                    {{-- CLOSE BUTTON --}}
                    <div class="modal-header border-0 pb-0">
                        <button type="button"
                            class="btn btn-light btn-icon rounded-circle ms-auto"
                            data-bs-dismiss="modal"
                            aria-label="Close">

                            <i class="ri-close-line fs-18"></i>
                        </button>
                    </div>

                    {{-- BODY --}}
                    <div class="modal-body px-4 pb-5 pt-2 text-center">
                        {{-- ICON --}}
                        <div class="mb-4">
                            <div class="avatar-xl mx-auto">
                                <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                    <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json"
                                    trigger="loop" colors="primary:#405189,secondary:#0ab39c"
                                    style="width:70px;height:70px">
                                    </lord-icon>
                                </div>
                            </div>
                        </div>

                        {{-- TITLE --}}
                        <div class="mb-2">
                            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill mb-3">
                                Export Excel
                            </span>

                            <h3 class="fw-bold mb-2" id="exportBukuBesarLabel">
                                Export Buku Besar?
                            </h3>

                            <p class="text-muted mb-0 lh-lg px-lg-4">
                                Apakah Anda yakin ingin mengekspor Buku Besar
                                <strong class="text-dark">
                                    {{ $selectedRekeningData->nama_rekening ?? '-' }}
                                </strong>
                                ke Excel?
                            </p>

                        </div>

                        {{-- INFORMATION REKENING --}}
                        <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                            <div class="d-flex align-items-start gap-3">
                                <div class="flex-shrink-0">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                            <i class="ri-book-2-line fs-18"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-grow-1">
                                    <h6 class="fw-semibold mb-1">
                                        {{ $selectedRekeningData->nama_rekening ?? 'Rekening belum dipilih' }}
                                    </h6>

                                    <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                        <span class="badge bg-primary-subtle text-primary">
                                            {{ $selectedRekeningData->kode_rekening ?? '-' }}
                                        </span>

                                        <span class="text-muted fs-13">
                                            Posisi normal:
                                            {{ $selectedRekeningData->posisi_normal ?? '-' }}
                                        </span>
                                    </div>

                                    <p class="text-muted mb-0 fs-13">
                                        Data Buku Besar rekening ini akan diekspor ke Excel.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- FOOTER --}}
                    <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i>
                            Batal
                        </button>

                        <button type="button"
                            class="btn btn-success rounded-pill px-4" id="konfirmasiExportBukuBesar"
                            data-kode-rekening="{{ $selectedRekeningData->kode_rekening ?? '' }}"
                            data-nama-rekening="{{ $selectedRekeningData->nama_rekening ?? '' }}"
                            data-bs-dismiss="modal">

                            <i class="ri-file-excel-2-line me-1"></i>
                            Ya, Export
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.getElementById('konfirmasiExportBukuBesar')
                .addEventListener('click', function () {

                    alertify.success("Menyiapkan Dokumen Excel");

                    const kodeRekening = this.dataset.kodeRekening;
                    const namaRekening = this.dataset.namaRekening;

                    setTimeout(function () {

                        const table = document.getElementById('Data');

                        if (!table) {
                            alertify.error("Tabel Buku Besar tidak ditemukan.");
                            return;
                        }

                        const workbook = XLSX.utils.table_to_book(table, {
                            sheet: 'Buku Besar'
                        });

                        // Bersihkan nama rekening agar aman digunakan sebagai nama file
                        const namaFile = namaRekening
                            .replace(/[\\/:*?"<>|]/g, '')
                            .replace(/\s+/g, '-');

                        XLSX.writeFile(
                            workbook,
                            `Buku-Besar-${kodeRekening}-${namaFile}-{{ date('Y-m-d') }}.xlsx`
                        );

                    }, 500);
                });
        </script>
    </div>
    @else
    <div class="col-xxl-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body text-center py-5">
                <div class="noresult text-center py-3">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                        colors="primary:#405189,secondary:#08a88a"
                        style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Pilih satu rekening untuk menampilkan Buku Besar.</h5>
                    <p class="text-muted mb-0">Gunakan filter rekening dan periode untuk melihat transaksi dan saldo berjalan.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>