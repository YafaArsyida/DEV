<div wire:ignore.self class="modal fade"
    id="modalKoreksi" tabindex="-1"
    aria-labelledby="modalKoreksiLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            {{-- Header --}}
            <div class="modal-header border-0">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                            <i class="ri-mark-pen-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Koreksi Transaksi
                        </h5>

                        <small class="text-muted">
                            Ubah jumlah produk yang salah
                        </small>
                    </div>
                </div>

                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- Body --}}
            <div class="modal-body">
                @if ($transaksi)
                    {{-- Informasi transaksi --}}
                    <div class="bg-light rounded-3 p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">
                                    @if ($transaksi->user_type === 'siswa')
                                        {{ $transaksi->ms_siswa->nama_siswa ?? '-' }}
                                    @elseif ($transaksi->user_type === 'pegawai')
                                        {{ $transaksi->ms_pegawai->nama_pegawai ?? '-' }}
                                    @else
                                        Umum
                                    @endif
                                </div>

                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($transaksi->tanggal_transaksi)->format('d/m/Y H:i') }}
                                    •
                                    {{ $transaksi->metode_pembayaran }}
                                </small>
                            </div>
                            <span class="badge bg-success-subtle text-success rounded-pill">
                                Belum Settlement
                            </span>
                        </div>
                    </div>

                    {{-- Produk --}}
                    <div class="table-responsive">
                        <table class="table table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center" style="width: 130px;">
                                        Jumlah
                                    </th>
                                    <th class="text-end">
                                        Subtotal
                                    </th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($detailKoreksi as $i => $detail)
                                    <tr>
                                        <td>
                                            <div class="fw-medium">
                                                {{ $detail['produk'] }}
                                            </div>
                                        </td>

                                        <td class="text-end">
                                            Rp{{ number_format($detail['harga'], 0, ',', '.') }}
                                        </td>

                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-1">
                                                <button type="button" class="btn btn-sm btn-light"
                                                    wire:click="decrementQty({{ $i }})" title="Kurangi jumlah">
                                                    −
                                                </button>

                                                <input type="text" class="form-control form-control-sm text-center" style="width: 50px"
                                                    value="{{ $detail['jumlah'] }}" readonly>
                                                <button type="button" class="btn btn-sm btn-light"
                                                    wire:click="incrementQty({{ $i }})" title="Tambah jumlah">
                                                    +
                                                </button>
                                            </div>
                                        </td>

                                        <td class="text-end fw-medium">
                                            Rp{{ number_format($detail['harga'] * $detail['jumlah'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Ringkasan --}}
                    <div class="border-top mt-3 pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">
                                Total Awal
                            </span>

                            <span>
                                Rp{{ number_format($this->totalAwal, 0, ',', '.') }}
                            </span>
                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">
                                Total Setelah Koreksi
                            </span>

                            <span class="fw-semibold">
                                Rp{{ number_format($this->totalKoreksi, 0, ',', '.') }}
                            </span>
                        </div>

                        @if ($this->selisih < 0)
                            <div class="d-flex justify-content-between text-success fw-semibold">
                                <span>
                                    Saldo Dikembalikan
                                </span>

                                <span>
                                    Rp{{ number_format(abs($this->selisih), 0, ',', '.') }}
                                </span>
                            </div>

                        @elseif ($this->selisih > 0)
                            <div class="d-flex justify-content-between text-danger fw-semibold">
                                <span>
                                    Tambahan Saldo
                                </span>

                                <span>
                                    Rp{{ number_format($this->selisih, 0, ',', '.') }}
                                </span>

                            </div>
                        @else
                            <div class="d-flex justify-content-between text-muted">
                                <span>
                                    Perubahan
                                </span>

                                <span>
                                    Tidak ada perubahan
                                </span>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Footer --}}
            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Batal
                </button>

                @if ($transaksi)
                    <button
                        type="button"
                        class="btn btn-danger rounded-pill px-4"
                        wire:click.prevent="simpanKoreksi"
                        wire:loading.attr="disabled"
                        wire:target="simpanKoreksi"
                        @disabled($this->selisih == 0)
                    >
                        <span wire:loading.remove wire:target="simpanKoreksi">
                            <i class="ri-save-3-line me-1"></i>
                            Simpan Koreksi
                        </span>

                    </button>
                @endif

            </div>
        </div>
    </div>
</div>