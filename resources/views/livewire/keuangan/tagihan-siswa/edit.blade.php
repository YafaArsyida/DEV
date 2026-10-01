{{-- The whole world belongs to you. --}}
<div wire:ignore.self class="modal fade" id="ModalAksiEdit" tabindex="-1" aria-labelledby="ModalAksiEditLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-list-3-line">
                            </i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Perbarui Data Tagihan
                        </h5>
                        <small>
                            Perbarui informasi nominal tagihan siswa.
                        </small>
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18">
                    </i>
                </button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <tbody>
                        @if($tagihan)
                        <tr>
                            <th scope="row" style="width: 150px;">Nama Siswa</th>
                            <td>
                                {{ $nama_siswa }}
                            </td>
                        </tr>
                        <tr>
                            <th scope="row" style="width: 150px;">Jenis Tagihan</th>
                            <td>
                                {{ $tagihan->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa }} - <i>{{ $tagihan->nama_kategori_tagihan_siswa() }}</i>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Nominal Tagihan</th>
                            <td>
                                <div class="fw-medium fs-12">Rp{{ number_format($tagihan->jumlah_tagihan_siswa, 0, ',', '.') }}</div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Dibayarkan</th>
                            <td>
                                <div class="fw-medium fs-12 text-success">Rp{{ number_format($tagihan->jumlah_sudah_dibayar(), 0, ',', '.') }}</div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Kekurangan</th>
                            <td>
                                <div class="fw-medium fs-12 text-danger">
                                    Rp{{ number_format($tagihan->jumlah_tagihan_siswa - $tagihan->jumlah_sudah_dibayar(), 0, ',', '.') }}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">Perubahan Tagihan</th>
                            <td>
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text">Rp</span>
                                    <input class="form-control fw-medium fs-12 @error('jumlah_perubahan_tagihan') is-invalid @enderror"
                                        id="jumlah_perubahan_tagihan"
                                        type="text"
                                        wire:model.defer="jumlah_perubahan_tagihan"
                                        onkeyup="formatTagihan(this)">
                                </div>

                                @error('jumlah_perubahan_tagihan')
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
                <button type="button" class="btn btn-primary rounded-pill px-4" wire:click="aksiEdit({{ $tagihan->ms_tagihan_id }})">
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

