<div>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        {{-- HEADER --}}
        <div class="card-header">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

                {{-- TITLE --}}
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-wallet-3-line"></i>
                            </div>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Saldo
                            </h5>
                            {{-- <small class="text-muted">
                                Lihat saldo tabungan setiap siswa, lakukan ekspor data, atau kosongkan saldo berdasarkan kelas.
                            </small> --}}
                        </div>
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="d-flex gap-2 flex-wrap">

                    @if ($selectedKelas)
                        <button
                            type="button"
                            class="btn btn-light rounded-pill text-primary px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#withdrawTabunganSiswa"
                            wire:click.prevent="$emit('withdrawTabunganSiswa', {
                                selectedKelas: '{{ $selectedKelas }}',
                                selectedJenjang: '{{ $selectedJenjang }}',
                                selectedTahunAjar: '{{ $selectedTahunAjar }}'
                            })">

                            <i class="ri-delete-bin-6-line"></i>
                            <span>Kosongkan</span>
                        </button>
                        <button
                            type="button"
                            class="btn btn-danger rounded-pill px-4 d-inline-flex align-items-center gap-1"
                            data-bs-toggle="modal"
                            data-bs-target="#ExportSaldoTabunganSiswa">

                            <i class="ri-file-excel-2-line"></i>
                            <span>Cetak</span>
                        </button>
                    @endif

                    <button
                        type="button"
                        class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1"
                        data-bs-toggle="modal"
                        data-bs-target="#ExportSaldo">

                        <i class="ri-file-excel-2-line"></i>
                        <span>Excel</span>
                    </button>

                </div>

            </div>
        </div>
        <div class="card-body">
            <div class="row g-3 align-items-end mb-3">
                <!-- Dropdown Kelas -->
                <div class="col-xxl-4 col-sm-6">
                    <label for="selectKelas" class="form-label">Kelas</label>
                    <select id="selectKelas" wire:model="selectedKelas" class="form-select" style="cursor: pointer"
                        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Kelas">
                        <option value="">Semua Kelas</option>
                        @foreach ($select_kelas as $item)
                        <option value="{{ $item->ms_kelas_id }}">{{ $item->nama_kelas }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Input Pencarian -->
                <div class="col-xxl-8 col-sm-6">
                    <label for="searchData" class="form-label">Pencarian</label>
                    <div class="search-box">
                        <input type="text" id="searchData" class="form-control search" wire:model.debounce.300ms="search"
                            placeholder="Cari nama, deskripsi, atau lainnya...">
                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
            </div>
            <!--end row-->
            {{-- DATA --}}
            <div class="live-preview">
                <div class="table-responsive">
                    <table id="saldoTabungan" class="table table-hover table-nowrap align-middle" style="width:100%">
                        <thead class="table-light text-uppercase">
                            <tr>
                                <th style="width: 30px;">NO</th>
                                <th>Siswa</th>
                                <th>Kelas</th>
                                <th class="text-uppercase text-center">Saldo Tabungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($siswas as $key => $item)
                            <tr>
                                <td class="text-start">{{ $siswas->firstItem() + $key }}.</td>
                                <td>
                                    {{ $item->ms_siswa->nama_siswa }}
                                </td>
                                <td>
                                    {{ $item->ms_kelas->nama_kelas ?? '' }}
                                </td>
                                <td class="text-center">
                                    <span class="fs-12 fw-medium text-primary">
                                        Rp{{ number_format($item->ms_siswa->ms_saldo_tabungan->saldo_tabungan ?? 0, 0, ',',
                                        '.') }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4">
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
                        <tfoot>
                            <tr class="fw-semibold">
                                <td></td>
                                <td></td>
                                <td class="text-uppercase text-start">TOTAL</td>
                                <td class="text-center">
                                    <span class="fs-12 text-primary">
                                        Rp{{ number_format($totalSaldo, 0, ',', '.') }}</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted fs-13">
                                Menampilkan
                                <span class="fw-semibold">
                                    {{ $siswas->firstItem() ?? 0 }}
                                </span>
                                -
                                <span class="fw-semibold">
                                    {{ $siswas->lastItem() ?? 0 }}
                                </span>
                                dari
                                <span class="fw-semibold">
                                    {{ $siswas->total() }}
                                </span>
                                data
                            </div>
                            <div>
                                {{ $siswas->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- end data --}}
        </div>
    </div>
    {{-- MODAL --}}
    <div class="modal fade zoomIn" id="ExportSaldo" tabindex="-1" aria-labelledby="exportRecordLabel" aria-hidden="true">
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
                            Apakah Anda yakin ingin mengekspor saldo Tabungan Siswa
                            <strong>
                                {{ $selectedKelas ? 'kelas ' . $namaKelas : 'Semua Kelas' }}
                            </strong>?
                            Data yang diekspor akan sesuai dengan kelas yang ditampilkan.
                        </p>
                        <div class="hstack gap-2 justify-content-center remove">
                            <button class="btn btn-link link-success fw-medium text-decoration-none shadow-none" data-bs-dismiss="modal">
                                <i class="ri-close-line me-1 align-middle"></i> Batal
                            </button>
                            <button
                                class="btn btn-primary"
                                id="konfirmasiSaldoTabungan"
                                data-bs-dismiss="modal"
                                data-kelas="{{ $selectedKelas ? $namaKelas : 'Semua-Kelas' }}">
                                Ya, Export!
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('konfirmasiSaldoTabungan').addEventListener('click', function () {
            alertify.success("Menyiapkan Dokumen");
            const namaKelas = this.dataset.kelas;

            // Tambahkan delay 1 detik
            setTimeout(function () {
                // Ambil elemen tabel berdasarkan ID
                var table = document.getElementById("saldoTabungan"); // ganti sesuai kebutuhan
        
                // Konversi tabel ke format Excel
                var workbook = XLSX.utils.table_to_book(table, { sheet: "Sheet1" });
                
                // Simpan file Excel
                XLSX.writeFile(
                    workbook,
                    `Laporan-Saldo-Tabungan-${namaKelas}-{{ date('Y-m-d') }}.xlsx`
                );
            }, 1000); // 1000 ms = 1 detik
        });
    </script>

</div>