<div class="col-xl-8">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Jurnal Kantin</h4>
            <div class="flex-shrink-0">
                <select wire:model="periode" wire:change="resetPage" class="form-select form-select-sm">
                    <option value="hari_ini">Hari Ini</option>
                    <option value="kemarin">Kemarin</option>
                    <option value="bulan_ini">Bulan Ini</option>
                </select>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive table-card">
                @php
                $saldo = 0;
                @endphp
                <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="">No</th>
                            <th class="text-start">Tanggal</th>
                            <th class="">Nomor Jurnal</th>
                            <th class="text-start">Deskripsi Transaksi</th>
                            <th class="text-start">Petugas</th>
                            <th class="text-center">Nominal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($transaksiJurnal as $transaksi)
                        <tr>

                            {{-- NO --}}
                            <td class="text-start">
                                {{ $transaksiJurnal->firstItem() + $loop->index }}.
                            </td>

                            {{-- TANGGAL --}}
                            <td>
                                {{ $transaksi->tanggal_transaksi
                                    ? \Carbon\Carbon::parse($transaksi->tanggal_transaksi)
                                        ->format('d/m/Y')
                                    : '-' }}
                            </td>
                            <td>
                                {{ $transaksi->nomor_jurnal }}
                            </td>

                            {{-- PETUGAS --}}
                            <td>
                                {{ $transaksi->deskripsi }}
                            </td>

                            <td>
                                {{ $transaksi->ms_pengguna->nama ?? '-' }}
                            </td>

                            @php
                                $nominal = optional(
                                    $transaksi->akuntansi_jurnal_detail->firstWhere('posisi', 'debit')
                                )->nominal ?? 0;
                            @endphp

                            <td class="text-center">
                                <span class="fs-12 fw-medium">
                                    Rp{{ number_format($nominal, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Tidak ada transaksi.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                {{-- PAGINATION --}}
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="text-muted fs-13">
                            Menampilkan
                            <span class="fw-semibold">
                                {{ $transaksiJurnal->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $transaksiJurnal->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $transaksiJurnal->total() }}
                            </span>
                            data kelas
                        </div>
                        <div>
                            {{ $transaksiJurnal->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><!-- end col -->