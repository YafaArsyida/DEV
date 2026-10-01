<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">
            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">

                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-check-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Jurnal Hari Ini
                        </h5>
                    </div>

                </div>
            </div>

            {{-- ACTION --}}
            <div class="flex-shrink-0">
                {{-- Filter / action dapat ditambahkan di sini --}}
            </div>

        </div>

    </div>

    {{-- BODY --}}
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-centered table-hover align-middle table-nowrap">
                <thead class="table-light">
                    <tr>
                        <th class="text-start">Tanggal</th>
                        <th>Nomor Jurnal</th>
                        <th class="text-start">Deskripsi</th>
                        <th class="text-start">Petugas</th>
                        <th class="text-end">Nominal</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($transaksiJurnal as $jurnal)
                        @php
                            $nominal = optional(
                                $jurnal->akuntansi_jurnal_detail
                                    ->firstWhere('posisi', 'debit')
                            )->nominal ?? 0;
                        @endphp
                        <tr>
                            <td>
                                {{ $jurnal->tanggal_transaksi
                                    ? \Carbon\Carbon::parse($jurnal->tanggal_transaksi)->format('d/m/Y')
                                    : '-' }}
                            </td>

                            <td>
                                {{ $jurnal->nomor_jurnal }}
                            </td>

                            <td>
                                {{ $jurnal->deskripsi }}
                            </td>

                            <td>
                                {{ $jurnal->ms_pengguna->nama ?? '-' }}
                            </td>

                            <td class="text-end">
                                <span class="fs-12 fw-medium">
                                    Rp{{ number_format($nominal, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ri-file-list-3-line fs-24 d-block mb-2"></i>
                                    Tidak ada data jurnal.
                                </div>
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
                        data
                    </div>
                    <div>
                        {{ $transaksiJurnal->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>