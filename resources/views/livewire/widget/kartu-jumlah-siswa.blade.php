<div class="col-md-6">
    <div class="card card-animate">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <h4 class="card-title mb-0 flex-grow-1">Total Siswa</h4>
                    <h2 class="mt-4 ff-secondary fw-semibold text-primary">
                        {{ number_format($jumlahSiswa) }}
                    </h2>
                    <p class="mb-0 text-muted">
                        Jenjang: <strong>{{ $namaJenjang }}</strong> <br>
                        Tahun Ajar: <strong>{{ $namaTahunAjar }}</strong>
                    </p>
                </div>
                <div>
                    <div class="avatar-sm flex-shrink-0">
                        <span class="avatar-title bg-primary rounded-circle fs-2">
                            <i class="bx bx-group"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div><!-- end card body -->
    </div> <!-- end card-->
</div> <!-- end col-->
