<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">

        <div class="d-flex align-items-center gap-3">

            <div class="avatar-sm">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-bar-chart-2-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">
                    Pembayaran Siswa
                </h5>
            </div>

        </div>

    </div>

    {{-- BODY --}}
    <div class="card-body">

        <div class="px-2 py-2">

            @forelse ($kategoriData as $item)

                <p class="{{ !$loop->first ? 'mt-3' : '' }} mb-1">
                    {{ $item['nama_kategori_tagihan_siswa'] }}

                    <span class="float-end">
                        {{ $item['presentase'] }}%
                    </span>
                </p>

                <div class="progress mt-2" style="height: 6px;">

                    <div
                        class="progress-bar progress-bar-striped bg-primary"
                        role="progressbar"
                        style="width: {{ $item['presentase'] }}%"
                        aria-valuenow="{{ $item['presentase'] }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                    </div>

                </div>

            @empty

                <div class="text-center py-4">
                    <div class="avatar-sm mx-auto mb-3">
                        <div class="avatar-title bg-light text-muted rounded-circle fs-20">
                            <i class="ri-file-list-3-line"></i>
                        </div>
                    </div>

                    <p class="text-muted mb-0">
                        Belum ada data kategori tagihan siswa.
                    </p>
                </div>

            @endforelse

            {{-- PAGINATION --}}
            @if ($kategoriData->hasPages())
                <div class="mt-4">
                    {{ $kategoriData->links() }}
                </div>
            @endif

        </div>

    </div>

</div>