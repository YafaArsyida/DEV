<div>
    <div wire:ignore.self class="offcanvas offcanvas-top" id="offcanvasHistori" aria-labelledby="offcanvasHistoriLabel"
        style="min-height:100vh;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasHistoriLabel">Riwayat Pembayaran Tagihan Siswa</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row g-3 mb-3">
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
                        <table class="table table-hover align-middle">
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
                                                    'd F Y
                                                    H:i:s') }}
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
                                            <div class="d-flex justify-content-end gap-2 flex-wrap">
                
                                                <!-- DELETE -->
                                                <a href="#ModalDeleteTransaksi" data-bs-toggle="modal"
                                                    class="btn btn-sm btn-soft-danger"
                                                    wire:click.prevent="$emit('loadTransaksiDelete', {{ $transaksi->ms_transaksi_tagihan_siswa_id }})">
                                                    <i class="ri-delete-bin-5-line"></i>
                                                    <span>Hapus</span>
                                                </a>
                
                                                <!-- EDIT -->
                                                <a href="#loadHistoriTransaksi" data-bs-toggle="modal"
                                                    class="btn btn-sm btn-primary d-flex align-items-center gap-1"
                                                    wire:click.prevent="$emit('loadHistoriTransaksi', {{ $transaksi->ms_transaksi_tagihan_siswa_id }})">
                                                    <i class="ri-quill-pen-line"></i>
                                                    <span>Edit</span>
                                                </a>
                
                                                <!-- WA -->
                                                <button class="btn btn-sm btn-success"
                                                    wire:click="kirimWhatsapp({{ $transaksi->ms_transaksi_tagihan_siswa_id }})">
                                                    <i class="mdi mdi-whatsapp"></i>
                                                    <span>Pesan</span>
                                                </button>
                
                                                <!-- PRINT -->
                                                <button class="btn btn-sm btn-danger"
                                                    wire:click="cetakTransaksi({{ $transaksi->ms_transaksi_tagihan_siswa_id }})">
                                                    <i class="ri-printer-line"></i>
                                                    <span>Cetak</span>
                                                </button>
                
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                
                                <!-- DETAIL -->
                                @if($transaksi->dt_transaksi_tagihan_siswa->isNotEmpty())
                                <tr>
                                    <td colspan="6" class="p-0">
                                        <div class="p-3">
                                            <table class="table table-sm align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th class="text-center" width="5%">No</th>
                                                        <th width="25%">Tagihan</th>
                                                        <th width="30%">Metode</th>
                                                        <th width="20%">Petugas</th>
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
                                                        <td>{{ $transaksi->ms_pengguna->nama }}</td>
                                                        <td class="text-end text-success fw-medium fs-14">
                                                            RP{{ number_format($detail->jumlah_bayar, 0, ',', '.') }}
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                    <tr>
                                                        <td colspan="4" class="text-end fw-bold fs-14"> Total </td>
                                                        <td class="text-end fw-bold fs-14 text-success"> RP{{
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