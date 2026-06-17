{{-- Stop trying to control. --}}
<div class="card">
    <div class="card-header border-0 pb-0">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h5 class="card-title mb-0">Laporan Transaksi SmartCanteen</h5>
                {{-- <p class="mb-0">Transaksi akan ditampilkan dari semua petugas untuk memastikan penghitungan yang akurat dan terkini.</p> --}}
            </div>
            <div class="flex-shrink-0">
                <div class="d-flex gap-2 flex-wrap">
                    <button wire:click="cetakLaporan" class="btn btn-danger d-inline-flex align-items-center gap-1">
                        <i class="ri-printer-line align-bottom"></i>
                        <span>Cetak Laporan</span>
                    </button>

                    <button data-bs-toggle="modal" data-bs-target="#ExportLaporanExcel" class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button>
                    {{-- @if($selectedPetugas)             --}}
                    <button type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasSettlement" aria-controls="offcanvasSettlement" class="btn btn-info d-inline-flex align-items-center gap-1">
                        <i class="ri-history-line align-bottom"></i>
                        <span>Riwayat Settlement</span>
                    </button>
                    {{-- @endif --}}
                    {{-- <button data-bs-toggle="modal" data-bs-target="#ExportEduPay" wire:click.prevent="showExportEduPay"  class="btn btn-soft-success"><i class="ri-file-excel-2-line pb-0"></i> Export</button> --}}
                </div>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3 align-items-end mb-3">
           <div class="col-xxl-2 col-sm-6">
                <label class="form-label">Petugas</label>
            
                @if(auth()->user()->peran === 'kantin')
                {{-- TAMPIL READONLY --}}
                <input type="text" class="form-control" value="{{ auth()->user()->nama }}" readonly>
                @else
                {{-- TAMPIL DROPDOWN --}}
                <select wire:model="selectedPetugas" class="form-select">
                    <option value="">-- Semua Petugas --</option>
                    @foreach ($select_petugas as $petugas)
                    <option value="{{ $petugas->ms_pengguna_id }}">
                        {{ $petugas->nama }}
                    </option>
                    @endforeach
                </select>
                @endif
            </div>

            <div class="col-xxl-2 col-sm-6">
                <label for="selectJenis" class="form-label">Jenis Pembeli</label>
                <select id="selectJenis" wire:model="selectedJenis" class="form-select" style="cursor: pointer">
                    <option value="">Semua</option>
                    <option value="siswa">Siswa</option>
                    <option value="pegawai">Pegawai</option>
                </select>
            </div>
            
            <!-- Input Pencarian -->
            <div class="col-xxl-4 col-sm-6">
                <label for="searchInput" class="form-label fw-semibold">Pencarian</label>
                <div class="position-relative">
                    <input type="text" id="searchInput" 
                        class="form-control ps-4" 
                        wire:model.debounce.300ms="search" 
                        placeholder="Cari nama, deskripsi, atau lainnya...">
                    <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                </div>
            </div>

            <!-- Filter Periode -->
            <div class="col-xxl-4 col-sm-6">
                <label class="form-label fw-semibold">Periode</label>
                <div class="d-flex align-items-center gap-2">
                    <input type="date" id="startDate" class="form-control" wire:model="startDate">
                    <span class="text-muted">–</span>
                    <input type="date" id="endDate" class="form-control" wire:model="endDate">
                    <button type="button" class="btn btn-soft-secondary" wire:click="resetTanggal" title="Reset Tanggal">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>

        </div>
        <!--end row-->
        {{-- DATA --}}
        <div class="live-preview">
            <div class="table-responsive">
                <table id="tabelSmartCanteen" class="table table-hover nowrap align-middle" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th class="text-uppercase">No</th>
                            <th class="text-uppercase text start" scope="col" style="width: 200px;">Tanggal</th>
                            <th class="text-uppercase">Pembeli</th>
                            <th class="text-uppercase" scope="col">Transaksi</th>
                            <th class="text-uppercase text-center">Petugas</th>
                            <th class="text-uppercase text-center">Nominal</th>
                            <th class="text-uppercase text-center">Settlement</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($laporan as $index => $item)
                        <tr class="text-center">
                            <td class="text-start">
                                {{ ($laporan->currentPage() - 1) * $laporan->perPage() + $index + 1 }}.
                            </td>
                            <td class="text-uppercase text-start">
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal_transaksi) }}
                            </td>
                            <td class="text-start" style="white-space: nowrap;">
                                @if ($item->user_type == 'siswa')
                                    {{ ucfirst($item->ms_siswa->nama_siswa) }}
                                    <p class="fs-12 mb-0 text-muted">
                                        {{ $item->ms_penempatan_siswa->ms_kelas->nama_kelas ?? '' }}
                                    </p>
                                @elseif ($item->user_type == 'pegawai')
                                    {{ ucfirst($item->ms_pegawai->nama_pegawai) }}
                                    <p class="fs-12 mb-0 text-muted text-primary">
                                        {{ $item->ms_pegawai->ms_jabatan->nama_jabatan ?? '' }}
                                    </p>
                                @else
                                    <span class="text-muted">Umum</span>
                                @endif
                            </td>
                            <td class="text-start">
                                <span class="fs-14">
                                    {!! 'RP' . number_format($item->total_transaksi, 0, ',', '.') . ' - <i>' . ucfirst($item->metode_pembayaran) . '</i>' !!}
                                </span>
                                <p class="text-muted mb-0">{{ $item->deskripsi ?? '' }}</p>
                            </td>
                            <td style="white-space: nowrap;">{{ $item->ms_pengguna->nama ?? '-' }}</td>
                            <td>
                                <span class="fs-14 text-success">
                                    RP{{ number_format($item->total_transaksi, 0, ',', '.') }}
                                </span>
                            </td>
                            <!-- 🔥 STATUS SETTLEMENT -->
                            <td style="white-space: nowrap;" class="text-center">
                                @if($item->status_settlement === 'sudah')
                                    <span class="text-success fw-semibold">
                                        <i class="ri-check-line me-1"></i> Sudah Disettlement
                                    </span>
                                @elseif($item->status_settlement === 'belum')
                                    <span class="text-warning fw-semibold">
                                        <i class="ri-timer-line me-1"></i> Menunggu Settlement
                                    </span>
                                @else
                                    <span class="text-primary fw-semibold">
                                        <i class="ri-check-double-line me-1"></i> Langsung Masuk Kantin
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
                            <td class="text-end">
                                <span class="fs-14 fw-semibold text-success">
                                    RP{{ number_format($totalTransaksi, 0, ',', '.') }}
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
    <div class="modal fade zoomIn" id="ExportLaporanExcel" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true" wire:ignore.self>
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
                            Apakah Anda yakin ingin mengekspor laporan SmartCanteen? Data yang diekspor akan sesuai dengan tabel yang ditampilkan.
                        </p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button class="btn btn-primary" id="konfirmasiExportLaporan" data-bs-dismiss="modal">Ya, Export!</button>
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
                var table = document.getElementById("tabelSmartCanteen"); // ganti sesuai kebutuhan
        
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(workbook, "Laporan-SmartCanteen.xlsx");
            }, 1000); // 1000 ms = 1 detik
        });
    </script>
</div>