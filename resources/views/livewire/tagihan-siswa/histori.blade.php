<div>
    <div wire:ignore.self class="offcanvas offcanvas-top" id="offcanvasHistori" aria-labelledby="offcanvasHistoriLabel" style="min-height:100vh;">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title" id="offcanvasHistoriLabel">Riwayat Pembayaran Tagihan Siswa</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="row g-3 mb-3">
                {{-- kosong --}}
            </div>
            <div class="live-preview">
                <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
                @if (!$selectedJenjang || !$selectedTahunAjar)
                <div class="text-center py-4">
                    <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                        colors="primary:#405189,secondary:#08a88a"
                        style="width:75px;height:75px">
                    </lord-icon>
                    <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                    <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih dahulu.</p>
                </div>
                @else
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle" style="width:100%">
                        <tbody>
                            @forelse ($historis as $transaksi)
                                @if($transaksi->dt_transaksi_tagihan_siswa->isNotEmpty())
                                    <tr>
                                        <td colspan="8" class="">
                                            <table class="table table-hover align-middle mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th style="width: 50px;" class="text-uppercase text-center">NO</th>
                                                        <th class="text-uppercase">Tanggal</th>
                                                        <th class="text-uppercase">Transaksi</th>
                                                        <th class="text-uppercase">Metode</th>
                                                        <th class="text-uppercase">Petugas</th>
                                                        <th class="text-uppercase text-end">Jumlah Bayar</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($transaksi->dt_transaksi_tagihan_siswa as $detail)
                                                        <tr>
                                                            <td class="text-center">{{ $loop->iteration }}</td>
                                                            <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($transaksi->tanggal_transaksi, 'd F Y H:i:s') }}</td>
                                                            <td>
                                                                {{ $detail->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}
                                                            </td>
                                                            <td>{{ $transaksi->metode_pembayaran }}</td>
                                                            <td>{{ $transaksi->ms_pengguna->nama }}</td>
                                                            {{-- <td> {{ $detail->ms_tagihan_siswa->nama_kategori_tagihan() }}</td> --}}
                                                            <td class="text-end">
                                                                <span class="fw-medium fs-14 text-success">
                                                                RP{{ number_format($detail->jumlah_bayar, 0, ',', '.') }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                @endif
                                <tr>
                                    <td class="text-center"></td>
                                    {{-- <td>{{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($transaksi->tanggal_transaksi, 'd F Y H:i:s') }}</td> --}}
                                    @if ($transaksi->infaq > 0)
                                        <td class="text-start text-success"> infaq : RP{{ number_format($transaksi->infaq, 0, ',', '.') }}</td>
                                    @else
                                        <td class="text-center"></td>
                                    @endif
                                    {{-- <td>petugas : {{ $transaksi->ms_pengguna->nama }}</td>
                                    <td>metode : {{ $transaksi->metode_pembayaran }}</td> --}}
                                    <td>{{ $transaksi->deskripsi }}</td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-3">
                                            <a href="#ModalDeleteTransaksi" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1" 
                                                wire:click.prevent="$emit('loadTransaksiDelete', {{ $transaksi->ms_transaksi_tagihan_siswa_id }})" 
                                                data-bs-trigger="hover" data-bs-placement="top" title="Hapus Transaksi">
                                                <i class="ri-delete-bin-5-line align-bottom"></i>
                                            </a>

                                            <!-- Tombol Kirim WhatsApp -->
                                            <a href="#editHistoriTagihan" data-bs-toggle="modal" wire:click.prevent="$emit('loadHistoriTransaksi', {{ $transaksi->ms_transaksi_tagihan_siswa_id }})" 
                                                class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                                <i class="ri-quill-pen-line align-bottom"></i>
                                                <span>Edit Transaksi</span>
                                            </a>

                                            <!-- Tombol Kirim WhatsApp -->
                                            <a href="" wire:click.prevent="kirimWhatsapp({{ $transaksi->ms_transaksi_tagihan_siswa_id }})" 
                                                class="btn btn-sm btn-success d-inline-flex align-items-center gap-1">
                                                <i class="ri-whatsapp-line align-bottom"></i>
                                                <span>Kirim WhatsApp</span>
                                            </a>

                                            <!-- Tombol Cetak -->
                                            <a wire:click="cetakTransaksi({{ $transaksi->ms_transaksi_tagihan_siswa_id }})" 
                                                class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1">
                                                <i class="ri-printer-line align-bottom"></i>
                                                <span>Cetak</span>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
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
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
