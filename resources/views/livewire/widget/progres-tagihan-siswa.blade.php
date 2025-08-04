<div class="col-xl-4">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Pembayaran Tagihan Siswa</h4>
        </div>
        <div class="card-body">                    
            <div class="px-2 py-2 mt-2">
                @forelse ($kategoriData as $item)
                <p class="{{ !$loop->first ? 'mt-3' : '' }} mb-1">
                    {{ $item['nama_kategori_tagihan_siswa'] }}
                    <span class="float-end">{{ $item['presentase'] }}%</span>
                </p>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar progress-bar-striped bg-primary"
                        role="progressbar"
                        style="width: {{ $item['presentase'] }}%"
                        aria-valuenow="{{ $item['presentase'] }}"
                        aria-valuemin="0"
                        aria-valuemax="100">
                    </div>
                </div>
                @empty
                <p class="text-muted">Belum ada data kategori tagihan siswa.</p>
                @endforelse

                <div class="mt-3">
                    {{ $kategoriData->links() }}
                </div>
            </div>

        </div>
    </div><!-- end card -->
</div> <!-- end col-->
