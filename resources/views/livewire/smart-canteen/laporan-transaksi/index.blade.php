{{-- Stop trying to control. --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-store-2-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Laporan Transaksi SmartCanteen
                        </h5>
                        <small class="text-muted">
                            Lihat dan kelola riwayat transaksi SmartCanteen secara lengkap.
                        </small>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">

                    {{-- CETAK --}}
                    <button wire:click="cetakLaporan"
                        type="button" class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-1">
                        <i class="ri-printer-line"></i>
                        <span>Cetak</span>
                    </button>

                    {{-- EXCEL --}}
                    <button type="button"
                        class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal" data-bs-target="#ExportLaporanExcel">
                        <i class="ri-file-excel-2-line"></i>
                        <span>Excel</span>
                    </button>

                    {{-- RIWAYAT SETTLEMENT --}}
                    <button type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasSettlement" aria-controls="offcanvasSettlement"
                        class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1">
                        <i class="ri-history-line"></i>
                        <span>Settlement</span>
                    </button>

                </div>
            </div>

        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 mb-3">
            {{-- PETUGAS --}}
            <div class="col-xxl-3 col-sm-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                    Petugas
                </label>

                @if (auth()->user()->peran === 'kantin')
                    <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                @else
                    <select wire:model="selectedPetugas" class="form-select" style="cursor: pointer">
                        <option value="">-- Semua Petugas --</option>
                        @foreach ($select_petugas as $petugas)
                            <option value="{{ $petugas->ms_pengguna_id }}">
                                {{ $petugas->nama }}
                            </option>
                        @endforeach
                    </select>
                @endif
            </div>

             {{-- PENCARIAN --}}
            {{-- <div class="col-xxl-2 col-sm-6">
                <label for="searchInput" class="form-label small text-muted text-uppercase fw-medium mb-2">
                    Pencarian
                </label>

                <div class="search-box">
                    <input type="text" id="searchInput" class="form-control search"
                        wire:model.debounce.300ms="search" placeholder="...">
                    <i class="ri-search-line search-icon"></i>
                </div>
            </div> --}}

            {{-- JENJANG / UNIT SUMBER DANA --}}
            <div class="col-xxl-3 col-sm-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                    Sumber Dana
                </label>

                <select wire:model="selectedJenjang" class="form-select" style="cursor: pointer">
                    <option value="">Semua</option>
                    @foreach ($select_jenjang as $jenjang)
                        <option value="{{ $jenjang->ms_jenjang_id }}">
                            {{ $jenjang->nama_jenjang }}
                        </option>
                    @endforeach
                    <option value="umum">Umum</option>
                </select>
            </div>

            {{-- STATUS SETTLEMENT --}}
            <div class="col-xxl-2 col-sm-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">
                    Settlement
                </label>

                <select wire:model="selectedSettlement" class="form-select" style="cursor: pointer">
                    <option value="">Semua Status</option>
                    <option value="belum">Belum Settlement</option>
                    <option value="sudah">Sudah Settlement</option>
                </select>
            </div>

            {{-- PERIODE --}}
            <div class="col-xxl-4 col-sm-6">
                <label
                    class="form-label small text-muted text-uppercase fw-medium mb-2">
                    Periode
                </label>

                <div class="d-flex align-items-center gap-2">
                    <input type="date" id="startDate" class="form-control" wire:model="startDate">
                    <span class="text-muted flex-shrink-0">–</span>
                    <input type="date" id="endDate" class="form-control" wire:model="endDate">
                    <button type="button" class="btn btn-soft-secondary flex-shrink-0" wire:click="resetTanggal" title="Reset Tanggal">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>

        </div>
        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
            <div class="table-responsive">
                <table id="tabelSmartCanteen" class="table table-hover table-nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase text start" scope="col" style="width: 200px;">Tanggal</th>
                            <th class="text-uppercase">Pembeli</th>
                            <th class="text-uppercase" scope="col">Metode</th>
                            <th class="text-uppercase text-center">Petugas</th>
                            <th class="text-uppercase text-center">Nominal</th>
                            <th class="text-uppercase text-center">Settlement</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($laporan as $index => $item)
                        <tr class="text-center">
                            <td class="text-start">
                                {{ ($laporan->currentPage() - 1) * $laporan->peRpage() + $index + 1 }}.
                            </td>
                            <td class="text-uppercase text-start">
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal_transaksi, 'd F Y') }}
                            </td>
                            <td class="text-start">
                                @if ($item->user_type == 'siswa')
                                    {{ ucfirst($item->ms_siswa->nama_siswa) }}
                                @elseif ($item->user_type == 'pegawai')
                                    {{ ucfirst($item->ms_pegawai->nama_pegawai) }}
                                    {{-- <p class="fs-12 mb-0 text-muted text-primary">
                                        {{ $item->ms_pegawai->ms_jabatan->nama_jabatan ?? '' }}
                                    </p> --}}
                                @else
                                    <span class="text-muted">Umum</span>
                                @endif
                            </td>
                            <td class="text-start fs-12 fw-medium">
                                {{ $item->metode_pembayaran }}
                            </td>
                            <td>{{ $item->ms_pengguna->nama ?? '-' }}</td>
                            <td class="fs-12 fw-medium text-center">
                                Rp{{ number_format($item->total_transaksi, 0, ',', '.') }}
                            </td>
                            <!-- 🔥 STATUS SETTLEMENT -->
                            <td class="text-center">
                                @if($item->status_settlement === 'sudah')
                                    <span class="text-success fw-semibold">
                                        <i class="ri-check-line me-1"></i> Sudah
                                    </span>
                                @elseif($item->status_settlement === 'belum')
                                    <span class="text-warning fw-semibold">
                                        <i class="ri-timer-line me-1"></i> Menunggu
                                    </span>
                                @else
                                    <span class="text-primary fw-semibold">
                                        <i class="ri-check-double-line me-1"></i> Langsung
                                    </span>
                                @endif
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
                        <tr>
                            <td colspan="4"></td>
                            <td class="fw-semibold text-uppercase">Total</td>
                            <td class="text-center">
                                <span class="fs-12 fw-semibold">
                                    Rp{{ number_format($totalTransaksi, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <div class="mt-3">
                    {{ $laporan->links() }}
                </div>
            </div>
        </div>
    </div>
    {{-- MODAL --}}
    <div class="modal fade zoomIn"
        id="ExportLaporanExcel"
        tabindex="-1"
        aria-labelledby="exportLaporanLabel"
        aria-hidden="true"
        wire:ignore.self>

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
                                <lord-icon
                                    src="https://cdn.lordicon.com/fjvfsqea.json"
                                    trigger="loop"
                                    colors="primary:#405189,secondary:#0ab39c"
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

                        <h3 class="fw-bold mb-2" id="exportLaporanLabel">
                            Export Laporan SmartCanteen?
                        </h3>

                        <p class="text-muted mb-0 lh-lg px-lg-4">
                            Apakah Anda yakin ingin mengekspor
                            <strong class="text-dark">
                                laporan transaksi SmartCanteen
                            </strong>
                            ke Excel?
                        </p>
                    </div>

                    {{-- INFORMATION --}}
                    <div class="alert alert-light border rounded-4 text-start mt-4 mb-0">
                        <div class="d-flex align-items-start gap-3">

                            <div class="flex-shrink-0">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                        <i class="ri-file-list-3-line fs-18"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-grow-1">
                                <h6 class="fw-semibold mb-1">
                                    Data Laporan
                                </h6>

                                <p class="text-muted mb-2 fs-13">
                                    Data yang diekspor akan mengikuti
                                    filter dan tabel yang sedang ditampilkan.
                                </p>

                                <div class="d-flex flex-wrap align-items-center gap-2">

                                    <span class="badge bg-primary-subtle text-primary">
                                        SmartCanteen
                                    </span>

                                    @if ($selectedJenjang === 'umum')
                                        <span class="badge bg-warning-subtle text-warning">
                                            Sumber Dana: Umum
                                        </span>
                                    @elseif (!empty($selectedJenjang))
                                        <span class="badge bg-primary-subtle text-primary">
                                            Sumber Dana Terpilih
                                        </span>
                                    @else
                                        <span class="badge bg-secondary-subtle text-secondary">
                                            Semua Sumber Dana
                                        </span>
                                    @endif

                                    @if (!empty($selectedSettlement))
                                        <span class="badge bg-info-subtle text-info">
                                            Settlement Terfilter
                                        </span>
                                    @endif

                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 pt-0 px-4 pb-4 justify-content-center">

                    <button type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">

                        <i class="ri-close-line me-1"></i>
                        Batal
                    </button>

                    <button type="button"
                        class="btn btn-success rounded-pill px-4"
                        id="konfirmasiExportLaporan"
                        data-bs-dismiss="modal">

                        <i class="ri-file-excel-2-line me-1"></i>
                        Ya, Export
                    </button>

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
                var table = document.getElementById("tabelSmartCanteen"); // ganti sesuai kebutuhan
        
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-SmartCanteen.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>