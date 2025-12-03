<div class="col-xl-8">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Produk Terlaris</h4>

            <!-- Select Periode -->
            <div class="flex-shrink-0">
                <select wire:model="selectedPeriode" class="form-select form-select-sm">
                    <option value="today">Hari Ini</option>
                    <option value="yesterday">Kemarin</option>
                    <option value="this_month">Bulan Ini</option>
                    <option value="3_months">3 Bulan Terakhir</option>
                    <option value="6_months">6 Bulan Terakhir</option>
                </select>
            </div>
        </div>

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
                        @foreach($produks as $index => $p)
                            <tr>
                                <td>{{ $produks->firstItem() + $index }}.</td>
                                <td>{{ $p->ms_produk_kantin->nama_produk_kantin ?? '-' }}</td>
                                <td class="text-center">
                                    <span class="fw-medium fs-14">
                                        Rp{{ number_format($p->ms_produk_kantin->harga ?? 0, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-medium fs-14 text-primary">
                                        {{ $p->total_terjual }} item
                                    </span>
                                </td>
                                <td class="text-center">
                                    <span class="fw-medium fs-14 text-success">
                                        Rp{{ number_format($p->total_pendapatan, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="mt-3 d-flex justify-content-end">
            {{ $produks->links() }}
        </div>
    </div>

</div>