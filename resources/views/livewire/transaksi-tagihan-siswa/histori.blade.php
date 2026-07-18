<div wire:ignore.self class="offcanvas offcanvas-end bg-light" id="offcanvasHistori" aria-labelledby="offcanvasHistoriLabel">
    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">
            <!-- Kiri -->
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-file-chart-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Riwayat Pembayaran
                    </h5>
                    <small class="text-muted">
                        {{ $namaSiswaCurrent ?? 'Siswa' }}
                    </small>
                </div>
            </div>
            <!-- Kanan -->
            <button type="button"
                class="btn btn-light btn-icon rounded-circle shadow-none"
                data-bs-dismiss="offcanvas">
                <i class="ri-close-line fs-18"></i>
            </button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body">
                        <div class="live-preview">
                            <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
                            @if (!$selectedJenjang || !$selectedTahunAjar)
                            <div class="text-center py-4">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                    colors="primary:#405189,secondary:#08a88a" style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                                <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih
                                    dahulu.</p>
                            </div>
                            @else
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap align-middle">
                                    <tbody>
                                        @forelse ($historis as $transaksi)
                                        <!-- HEADER TRANSAKSI -->
                                        <tr class="bg-light">
                                            <td colspan="6">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        
                                                    <!-- KIRI (INFO) -->
                                                    <div>
                                                        <div class="fw-bold">
                                                            {{
                                                            \App\Http\Controllers\HelperController::formatTanggalIndonesia($transaksi->tanggal_transaksi,
                                                            'd F Y') }} | {{ $transaksi->ms_pengguna->nama }}
                                                        </div>
                        
                                                        <small class="text-muted d-block">
                                                            {{ $transaksi->deskripsi }}
                                                        </small>
                        
                                                        @if ($transaksi->infaq > 0)
                                                        <div class="text-success small">
                                                            Infaq: RP{{ number_format($transaksi->infaq, 0, ',', '.') }}
                                                        </div>
                                                        @endif
                                                    </div>
                        
                                                    <!-- KANAN (ACTION) -->
                                                    <div class="d-flex align-items-center gap-2">
                                                        <!-- WA -->
                                                        <button
                                                            class="btn btn-sm rounded-pill px-3 btn-success"
                                                            wire:click="kirimWhatsapp({{ $transaksi->ms_transaksi_tagihan_siswa_id }})">
                                                            <i class="mdi mdi-whatsapp me-1"></i>
                                                            Pesan
                                                        </button>

                                                        <!-- PRINT -->
                                                        <button
                                                            class="btn btn-sm rounded-pill px-3 btn-danger"
                                                            wire:click="cetakTransaksi({{ $transaksi->ms_transaksi_tagihan_siswa_id }})">
                                                            <i class="ri-printer-line me-1"></i>
                                                            Cetak
                                                        </button>

                                                        <!-- MORE -->
                                                        <div class="dropdown">
                                                            <button
                                                                class="btn btn-sm rounded-pill btn-light"
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false">
                                                                <i class="ri-more-2-fill fs-12"></i>
                                                            </button>

                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <a
                                                                        href="#loadHistoriTransaksi"
                                                                        data-bs-toggle="modal"
                                                                        class="dropdown-item"
                                                                        wire:click.prevent="$emit('loadHistoriTransaksi', {{ $transaksi->ms_transaksi_tagihan_siswa_id }})">

                                                                        <i class="ri-quill-pen-line me-2"></i>
                                                                        Edit
                                                                    </a>
                                                                </li>

                                                                <li><hr class="dropdown-divider"></li>
                                                                <li>
                                                                    <a
                                                                        href="#ModalDeleteTransaksi"
                                                                        data-bs-toggle="modal"
                                                                        class="dropdown-item text-danger"
                                                                        wire:click.prevent="$emit('loadTransaksiDelete', {{ $transaksi->ms_transaksi_tagihan_siswa_id }})">

                                                                        <i class="ri-delete-bin-5-line me-2"></i>
                                                                        Hapus
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                        
                                        <!-- DETAIL -->
                                        @if($transaksi->dt_transaksi_tagihan_siswa->isNotEmpty())
                                        <tr>
                                            <td colspan="6" class="p-0">
                                                <div class="p-3">
                                                    <table class="table table-nowrap table-sm align-middle mb-0">
                                                        <thead class="table-light">
                                                            <tr>
                                                                <th class="text-center" width="5%">No</th>
                                                                <th width="25%">Tagihan</th>
                                                                <th width="30%">Metode</th>
                                                                {{-- <th width="20%">Petugas</th> --}}
                                                                <th width="20%" class="text-end">Jumlah</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($transaksi->dt_transaksi_tagihan_siswa as $detail)
                                                            <tr>
                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                <td>
                                                                    {{
                                                                    $detail->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa
                                                                    }}
                                                                </td>
                                                                <td>{{ $transaksi->metode_pembayaran }}</td>
                                                                {{-- <td>{{ $transaksi->ms_pengguna->nama }}</td> --}}
                                                                <td class="text-end text-success fs-12 fw-medium">
                                                                    RP{{ number_format($detail->jumlah_bayar, 0, ',', '.') }}
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            <tr>
                                                                <td colspan="3" class="text-end fs-12 fw-medium"> Total </td>
                                                                <td class="text-end fs-12 fw-medium text-success"> RP{{
                                                                    number_format($transaksi->total_jumlah_dibayarkan, 0, ',',
                                                                    '.') }} </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        @endif
                        
                                        @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="text-center py-4">
                                                    <h5 class="mb-1">Tidak ada data</h5>
                                                    <small class="text-muted">Data transaksi belum tersedia</small>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>