<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">

        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">

                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-star-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Produk Terlaris
                        </h5>
                    </div>

                </div>
            </div>

            {{-- FILTER --}}
            <div class="flex-shrink-0">
                <select
                    wire:model="selectedPeriode"
                    class="form-select form-select-sm rounded-pill px-3"
                >
                    <option value="today">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="this_month">Bulan Ini</option>
                    <option value="3_months">3 Bulan Terakhir</option>
                    <option value="6_months">6 Bulan Terakhir</option>
                </select>
            </div>

        </div>

    </div>

    {{-- BODY --}}
    <div class="card-body">

        <div class="table-responsive table-card">

            <table class="table table-centered table-hover align-middle table-nowrap mb-0">

                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th class="text-center">Harga</th>
                        <th class="text-center">Terjual</th>
                        <th class="text-center">Pendapatan</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($produks as $index => $p)

                        <tr>

                            <td>
                                {{ $produks->firstItem() + $index }}
                            </td>

                            <td>
                                {{ $p->ms_produk_kantin->nama_produk_kantin ?? '-' }}
                            </td>

                            <td class="text-center fw-medium fs-12">
                                Rp{{ number_format($p->ms_produk_kantin->harga ?? 0, 0, ',', '.') }}
                            </td>

                            <td class="text-center">
                                <span class="fw-medium fs-12 text-primary">
                                    {{ $p->total_terjual }} item
                                </span>
                            </td>

                            <td class="text-center">
                                <span class="fw-medium fs-12 text-success">
                                    Rp{{ number_format($p->total_pendapatan, 0, ',', '.') }}
                                </span>
                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                Data tidak tersedia
                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

    {{-- PAGINATION --}}
    <div class="mt-3 d-flex justify-content-end">
        {{ $produks->links() }}
    </div>

</div>
