@extends('template_ppdb.v_template')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Daftar Ulang</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0"><li class="breadcrumb-item">PPDB</li><li class="breadcrumb-item active">Daftar Ulang</li></ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4"><div class="card"><div class="card-body"><p class="text-muted mb-2">Diterima</p><h4 class="mb-0">0 <small class="text-muted fs-13">calon siswa</small></h4></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><p class="text-muted mb-2">Belum Konfirmasi</p><h4 class="mb-0">0 <small class="text-muted fs-13">calon siswa</small></h4></div></div></div>
            <div class="col-md-4"><div class="card"><div class="card-body"><p class="text-muted mb-2">Sudah Daftar Ulang</p><h4 class="mb-0">0 <small class="text-muted fs-13">calon siswa</small></h4></div></div></div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-1">Konfirmasi Calon Siswa Diterima</h5>
                        <p class="text-muted mb-0">Pantau status konfirmasi dan proses daftar ulang.</p>
                    </div>
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-5"><label class="form-label">Cari calon siswa</label><input class="form-control" placeholder="Nama atau nomor pendaftaran" disabled></div>
                            <div class="col-md-4"><label class="form-label">Status daftar ulang</label><select class="form-select" disabled><option>Semua status</option></select></div>
                            <div class="col-md-3 d-flex align-items-end"><button type="button" class="btn btn-light w-100" disabled><i class="ri-filter-3-line me-1"></i> Filter</button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light"><tr><th>No. Pendaftaran</th><th>Nama Calon Siswa</th><th>Jenjang</th><th>Batas Konfirmasi</th><th>Status</th><th>Aksi</th></tr></thead>
                                <tbody><tr><td colspan="6" class="text-center py-5 text-muted">Data calon siswa yang diterima akan ditampilkan di sini.</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
