<div wire:ignore.self class="modal fade" id="ModalDetailSettlement" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Detail Settlement</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                {{-- Gambaran Settlement --}}
                @if($settlement)
                    <div class="alert alert-secondary mb-3">
                        <div><strong>Tanggal Settlement:</strong> {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($settlement->tanggal_transaksi, 'd F Y') }}</div>
                        <div><strong>Total Settlement:</strong> Rp {{ number_format($settlement->total_settlement) }}</div>
                        <div><strong>Metode:</strong> {{ ucfirst($settlement->metode_pembayaran) }}</div>
                        <div><strong>Petugas TU:</strong> {{ $settlement->ms_pengguna->nama ?? '-' }}</div>
                        @if($settlement->deskripsi)
                            <div><strong>Deskripsi:</strong> {{ $settlement->deskripsi }}</div>
                        @endif
                    </div>
                @endif
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Pelanggan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($detailList as $i => $d)
                        <tr class="text-center">
                            <td>{{ $i + 1 }}.</td>

                            {{-- Tanggal --}}
                            <td style="white-space: nowrap;" class="text-start text-uppercase">
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($d->tanggal_transaksi, 'd F Y') }}
                            </td>

                            {{-- Jenis + Nama Pengguna + Deskripsi --}}
                            <td class="text-start">
                                <span class="fw-semibold text-dark">
                                    {{ ucfirst($d->user_type) }} -
                                    @if ($d->user_type === 'siswa')
                                        {{ $d->ms_siswa->nama_siswa ?? '-' }}
                                    @elseif ($d->user_type === 'pegawai')
                                        {{ $d->ms_pegawai->nama_pegawai ?? '-' }}
                                    @else
                                        -
                                    @endif
                                </span>

                                <p class="text-muted mb-0">
                                    {{ $d->deskripsi ?: '-' }}
                                </p>
                            </td>

                            {{-- Total --}}
                            <td class="text-end">
                                <span class="fw-bold fs-14 text-success">
                                    RP{{ number_format($d->total_transaksi, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>

                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">
                                Tidak ada data transaksi
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>
