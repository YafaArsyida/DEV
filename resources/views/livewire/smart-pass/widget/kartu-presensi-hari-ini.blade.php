<div class="col-xl-7">
    <div class="card card-height-100">
        <div class="card-header align-items-center d-flex">
            <h4 class="card-title mb-0 flex-grow-1">Presensi Hari Ini</h4>

            <!-- Select Jenis -->
            <div class="flex-shrink-0">
                <select class="form-select form-select-sm">
                    <option value="all">Semua</option>
                    <option value="siswa">Siswa</option>
                    <option value="pegawai">Pegawai</option>
                </select>
            </div>
        </div>

        <div class="card-body">
            <div class="table-responsive table-card">
                <table class="table table-centered table-hover align-middle table-nowrap mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="50px">No</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th class="text-center">Waktu Scan</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>1.</td>
                            <td><span class="fw-medium">Ahmad Fauzi</span></td>
                            <td><span class="badge bg-primary">Siswa</span></td>
                            <td class="text-center">
                                <span class="fw-medium text-dark">06:55</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Masuk</span>
                            </td>
                        </tr>

                        <tr>
                            <td>2.</td>
                            <td><span class="fw-medium">Rina Pratiwi</span></td>
                            <td><span class="badge bg-primary">Siswa</span></td>
                            <td class="text-center">
                                <span class="fw-medium text-dark">07:02</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Masuk</span>
                            </td>
                        </tr>

                        <tr>
                            <td>3.</td>
                            <td><span class="fw-medium">Budi Santoso</span></td>
                            <td><span class="badge bg-warning text-dark">Pegawai</span></td>
                            <td class="text-center">
                                <span class="fw-medium text-dark">06:45</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success">Masuk</span>
                            </td>
                        </tr>

                        <tr>
                            <td>4.</td>
                            <td><span class="fw-medium">Siti Aminah</span></td>
                            <td><span class="badge bg-warning text-dark">Pegawai</span></td>
                            <td class="text-center">
                                <span class="fw-medium text-dark">07:10</span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-danger">Terlambat</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination Dummy -->
            <div class="mt-3 d-flex justify-content-end">
                <nav>
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><span class="page-link">Prev</span></li>
                        <li class="page-item active"><span class="page-link">1</span></li>
                        <li class="page-item"><span class="page-link">2</span></li>
                        <li class="page-item"><span class="page-link">3</span></li>
                        <li class="page-item"><span class="page-link">Next</span></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>