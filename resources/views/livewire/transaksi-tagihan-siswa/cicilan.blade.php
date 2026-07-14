<div wire:ignore.self class="modal fade" id="ModalAksiBayar" tabindex="-1" aria-labelledby="ModalAksiBayarLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Cicilan Tagihan Siswa
                        </h5>
                        {{-- <small>
                            Tambahkan agenda kegiatan generasi penerus dengan pengaturan tingkat, jadwal, dan lokasi.
                        </small> --}}
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18">
                    </i>
                </button>    
            </div>
            <div class="modal-body">
                <table class="table mb-0">
                    <tbody>
                        @if($tagihan)
                        <tr>
                            <th scope="row" style="width: 150px;">Tagihan</th>
                            <td>
                                <span class="fw-medium">
                                    {{ $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }}
                                    -
                                    <i>{{
                                        $tagihan->ms_jenis_tagihan_siswa->ms_kategori_tagihan_siswa->nama_kategori_tagihan_siswa
                                        }}</i>
                                </span>
                                <p class="text-muted mb-0">
                                    Rp{{ number_format($tagihan->jumlah_tagihan_siswa, 0, ',', '.') }}
                                </p>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Dibayarkan</th>
                            <td>
                                <span class="text-success">
                                    Rp{{ number_format($tagihan->total_bayar ?? 0, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>

                        <tr>
                            <th scope="row">Kekurangan</th>
                            <td>
                                <span class="text-danger">
                                    Rp{{ number_format(
                                    $tagihan->jumlah_tagihan_siswa - ($tagihan->total_bayar ?? 0),
                                    0, ',', '.'
                                    ) }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Jumlah Bayar</th>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control @error('jumlah_bayar') is-invalid @enderror"
                                        wire:model.defer="jumlah_bayar"
                                        onkeyup="formatTagihan(this)"
                                        aria-label="Amount">
                                </div>
                                @error('jumlah_bayar')
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                                @enderror
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
                @if($tagihan)
                <button type="button" class="btn btn-primary rounded-pill px-4" wire:click="masukKeranjang({{ $tagihan->ms_tagihan_siswa_id }})">
                    <i class="ri-save-3-line me-1"></i>
                    Simpan
                </button>
                @endif
            </div>
        </div>
    </div>
</div>
<script>
    function formatTagihan(el) {
        let angka = el.value.replace(/\D/g, '');
        el.value = new Intl.NumberFormat('id-ID').format(angka);
    }
</script>