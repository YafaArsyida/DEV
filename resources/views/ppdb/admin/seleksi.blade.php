@extends('template_ppdb.v_template')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Seleksi Calon Siswa</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0"><li class="breadcrumb-item">PPDB</li><li class="breadcrumb-item active">Seleksi</li></ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-1">Daftar Calon Siswa</h5>
                        <p class="text-muted mb-0">Tinjau penilaian dan tetapkan hasil seleksi.</p>
                    </div>
                    <div class="card-body border-bottom">
                        <div class="row g-3">
                            <div class="col-md-5"><label class="form-label">Cari calon siswa</label><input class="form-control" placeholder="Nama atau nomor pendaftaran" disabled></div>
                            <div class="col-md-4"><label class="form-label">Status seleksi</label><select class="form-select" disabled><option>Semua status</option></select></div>
                            <div class="col-md-3 d-flex align-items-end"><button class="btn btn-light w-100" type="button" disabled><i class="ri-filter-3-line me-1"></i> Filter</button></div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light"><tr><th>No. Pendaftaran</th><th>Nama Calon Siswa</th><th>Nilai</th><th>Hasil</th><th>Aksi</th></tr></thead>
                                <tbody><tr><td colspan="5" class="text-center py-5 text-muted">Data calon siswa yang siap diseleksi akan ditampilkan di sini.</td></tr></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Kriteria Penilaian</h5></div>
                    <div class="card-body">
                        <div class="d-flex justify-content-between border-bottom py-2"><span>Nilai akademik</span><span class="text-muted">Belum diatur</span></div>
                        <div class="d-flex justify-content-between border-bottom py-2"><span>Hasil wawancara</span><span class="text-muted">Belum diatur</span></div>
                        <div class="d-flex justify-content-between py-2"><span>Kelengkapan berkas</span><span class="text-muted">Belum diatur</span></div>
                        <button type="button" class="btn btn-soft-primary w-100 mt-3" disabled>Atur Kriteria</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
