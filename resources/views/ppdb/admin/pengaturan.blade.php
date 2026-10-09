@extends('template_ppdb.v_template')

@section('content')
<div class="page-content">
    <div class="container-fluid" style="max-width: 100%">
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0">Pengaturan PPDB</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0"><li class="breadcrumb-item">PPDB</li><li class="breadcrumb-item active">Pengaturan PPDB</li></ol>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-8">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-1">Periode Pendaftaran</h5><p class="text-muted mb-0">Atur jadwal dan status penerimaan peserta didik baru.</p></div>
                    <div class="card-body">
                        <div class="alert alert-info"><i class="ri-information-line me-1"></i> Pengaturan ini hanya pratinjau dan belum tersimpan.</div>
                        <div class="row g-3">
                            <div class="col-12"><label class="form-label">Nama periode</label><input class="form-control" placeholder="Contoh: PPDB Tahun Ajaran 2026/2027" disabled></div>
                            <div class="col-md-6"><label class="form-label">Tanggal mulai pendaftaran</label><input type="date" class="form-control" disabled></div>
                            <div class="col-md-6"><label class="form-label">Tanggal akhir pendaftaran</label><input type="date" class="form-control" disabled></div>
                            <div class="col-md-6"><label class="form-label">Status periode</label><select class="form-select" disabled><option>Pilih status</option><option>Draf</option><option>Aktif</option><option>Selesai</option></select></div>
                            <div class="col-md-6"><label class="form-label">Kuota penerimaan</label><input type="number" class="form-control" placeholder="Masukkan kuota" disabled></div>
                        </div>
                        <div class="d-flex justify-content-end mt-4"><button type="button" class="btn btn-primary" disabled><i class="ri-save-line me-1"></i> Simpan Pengaturan</button></div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4">
                <div class="card">
                    <div class="card-header"><h5 class="card-title mb-0">Pengaturan Lainnya</h5></div>
                    <div class="card-body">
                        <a href="javascript:void(0);" class="d-flex align-items-center justify-content-between border-bottom py-3 text-body"><span><i class="ri-file-list-3-line me-2 text-primary"></i>Persyaratan Dokumen</span><i class="ri-arrow-right-s-line text-muted"></i></a>
                        <a href="javascript:void(0);" class="d-flex align-items-center justify-content-between border-bottom py-3 text-body"><span><i class="ri-school-line me-2 text-primary"></i>Jenjang dan Jalur</span><i class="ri-arrow-right-s-line text-muted"></i></a>
                        <a href="javascript:void(0);" class="d-flex align-items-center justify-content-between py-3 text-body"><span><i class="ri-question-answer-line me-2 text-primary"></i>Informasi Pendaftaran</span><i class="ri-arrow-right-s-line text-muted"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
