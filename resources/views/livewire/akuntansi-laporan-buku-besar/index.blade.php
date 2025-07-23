<div class="row">
    <div class="col-xxl-12 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-xxl-8 col-sm-6">
                        <div class="search-box">
                            <input type="text" class="form-control search" wire:model.debounce.500ms="search" placeholder="cari nama, deskripsi atau lainnya...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-xxl-4 col-sm-6">
                        {{-- <select wire:model="selectedBulan" wire:change="$emit('bulanUpdated', $event.target.value)" class="form-select">
                            <option value="">Semua Periode</option>
                            @foreach ($select_bulan as $bulan)
                                <option value="{{ $bulan['value'] }}">{{ $bulan['name'] }}</option>
                            @endforeach
                        </select> --}}
                        <div class="row g-2 align-items-center">
                            <!-- Label di sisi kiri -->
                            <div class="col-auto">
                                <label for="startDate" class="form-label text-muted text-uppercase fs-12 fw-medium mb-0">Periode </label>
                            </div>
                            <!-- Input tanggal di sisi kanan -->
                            <div class="col">
                                <div class="row g-2 align-items-center">
                                    <div class="col-lg">
                                        <input type="date" id="startDate" class="form-control" wire:model="startDate" placeholder="0">
                                    </div>
                                    <div class="col-lg">
                                        <input type="date" id="endDate" class="form-control" wire:model="endDate" placeholder="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- looping akun rekening --}}
    @foreach ($jenisAkunRekening as $rekening)
    {{-- class agar modal mau menimpa --}}
    <div class="col-xxl-12">
        <div class="card">
            <div class="card-header border-0 pb-0">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h5 class="card-title mb-0">
                            {{ $rekening->nama_rekening }} - {{ $rekening->kode_rekening }} posisi normal {{ $rekening->posisi_normal }}
                        </h5> 
                        <p class="mb-0">{{ $rekening->deskripsi }}</p>
                    </div>
                    <div class="flex-shrink-0">
                        <div class="d-flex gap-2 flex-wrap">
                            <button data-bs-toggle="modal" data-bs-target="#ExportModal{{ $rekening->kode_rekening }}" class="btn btn-soft-success">
                                <i class="ri-file-excel-2-line pb-0"></i> Export
                            </button>
                        </div>
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                {{-- DATA --}}
                <div class="live-preview">
                    <div id="table-{{ $rekening->kode_rekening }}" class="table-responsive" style="max-height: 500px;" data-simplebar>
                        @php
                            $saldo = 0;
                        @endphp
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-uppercase">No</th>
                                    <th class="text-uppercase text-start" style="width: 200px;">Tanggal</th>
                                    <th class="text-uppercase text-start">Petugas</th>
                                    <th class="text-uppercase text-start" style="min-width: 500px;">Deskripsi Transaksi</th>
                                    <th class="text-uppercase text-center">Debit</th>
                                    <th class="text-uppercase text-center">Kredit</th>
                                    <th class="text-uppercase text-center">Saldo</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- Looping Jurnal Detail --}}
                                @if (isset($transaksiJurnal[$rekening->kode_rekening]))
                                    @foreach ($transaksiJurnal[$rekening->kode_rekening] as $index => $transaksi)
                                        <tr>
                                            <td class="text-start">{{ $loop->iteration }}.</td>
                                            <td class="text-start" style="white-space: nowrap;">{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($transaksi->tanggal_transaksi, 'd F Y H:i:s') }}</td>
                                            <td class="text-start">{{ $transaksi->ms_pengguna->nama }}</td>                                                
                                            <td class="text-start">{{ $transaksi->deskripsi }}</td>
                                            <td class="text-center">
                                                <span class="fs-14 {{ $rekening->posisi_normal === 'debit' ? 'text-success' : 'text-danger' }}">
                                                    {{ $transaksi->posisi === 'debit' ? 'RP' . number_format($transaksi->nominal, 0, ',', '.') : '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="fs-14 {{ $rekening->posisi_normal === 'kredit' ? 'text-success' : 'text-danger' }}">
                                                    {{ $transaksi->posisi === 'kredit' ? 'RP' . number_format($transaksi->nominal, 0, ',', '.') : '-' }}
                                                </span>
                                            </td>
                                            <td class="text-center">
                                                @php
                                                    // Perhitungan saldo
                                                    if ($rekening->posisi_normal === 'debit') {
                                                        // Jika posisi normal debit
                                                        if ($transaksi->posisi === 'debit') {
                                                            $saldo += $transaksi->nominal; // Tambahkan saldo
                                                        } elseif ($transaksi->posisi === 'kredit') {
                                                            $saldo -= $transaksi->nominal; // Kurangi saldo
                                                        }
                                                    } elseif ($rekening->posisi_normal === 'kredit') {
                                                        // Jika posisi normal kredit
                                                        if ($transaksi->posisi === 'kredit') {
                                                            $saldo += $transaksi->nominal; // Tambahkan saldo
                                                        } elseif ($transaksi->posisi === 'debit') {
                                                            $saldo -= $transaksi->nominal; // Kurangi saldo
                                                        }
                                                    }
                                                @endphp
                                                <span class="fs-14 text-info">
                                                    RP{{ number_format($saldo, 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7" class="text-center">Tidak ada data</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
        </div>
        {{-- modal --}}
        <div class="modal fade zoomIn" id="ExportModal{{ $rekening->kode_rekening }}" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-5 text-center">
                        <lord-icon src="https://cdn.lordicon.com/fjvfsqea.json" trigger="loop" colors="primary:#405189,secondary:#f06548" style="width:90px;height:90px"></lord-icon>
                        <div class="mt-4 text-center">
                            <h4 class="fs-semibold">Konfirmasi Export</h4>
                            <p class="text-muted fs-14 mb-4 pt-1">
                                Apakah Anda yakin ingin mengekspor laporan {{ $rekening->nama_rekening }}? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
                            </p>
                            <div class="hstack gap-2 justify-content-center remove">
                                <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                    <i class="ri-close-line me-1 align-middle"></i> Batal
                                </button>
                                <button class="btn btn-primary export-btn" data-table-id="Tabel-{{ $rekening->kode_rekening }}" data-bs-dismiss="modal">Ya, Export!</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Gunakan event delegation
            document.body.addEventListener('click', function (event) {
                if (event.target.classList.contains('export-btn')) {
                    const tableId = event.target.getAttribute('data-table-id');
                    const table = document.getElementById(tableId);

                    if (!table) {
                        alertify.error("Tabel tidak ditemukan.");
                        return;
                    }

                    alertify.success("Menyiapkan Dokumen");
                    setTimeout(function () {
                        try {
                            // Konversi tabel ke format Excel
                            const workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                            // Simpan file Excel
                            XLSX.writeFile(workbook, `Laporan-${tableId}.xlsx`);
                            alertify.success("Dokumen berhasil diunduh.");
                        } catch (error) {
                            alertify.error("Terjadi kesalahan: " + error.message);
                        }
                    }, 1000);
                }
            });
        });

    </script>
</div>