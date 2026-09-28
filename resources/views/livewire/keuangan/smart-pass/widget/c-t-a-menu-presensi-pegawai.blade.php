<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <!-- Alert CTA -->
        <div class="alert alert-primary border-0 rounded-0 m-0 d-flex align-items-center" role="alert">
            <i class="bx bx-cart-alt text-primary me-2 fs-4"></i>
            <div class="flex-grow-1 text-truncate">
                Mulai proses <strong>Presensi Pegawai</strong> untuk hari ini.
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('smartPass.presensi.pegawai') }}" class="fw-semibold text-decoration-underline text-primary">
                    Buka Presensi
                </a>
            </div>
        </div>

        <!-- Body CTA -->
        <div class="row align-items-center">
            <div class="col-md-8">
                <div class="p-4">
                    <h5 class="fw-bold mb-2">Presensi Pegawai Berbasis Kartu</h5>
                    <p class="mb-3 text-muted">
                        Lakukan presensi masuk dan pulang pegawai dengan cepat
                        menggunakan kartu RFID. Status hadir, terlambat,
                        dan pulang tercatat otomatis.
                    </p>

                    <a href="{{ route('smartCanteen.transaksi.produk') }}" class="btn btn-primary btn-lg">
                        <i class="bx bx-log-in-circle align-middle me-1"></i>
                       Mulai Presensi Pegawai
                    </a>
                </div>
            </div>

            <div class="col-md-4 text-end">
                <img src="{{asset('assets')}}/images/user-illustarator-2.png" class="img-fluid" alt="">
            </div>
        </div>
    </div>
</div>