<div wire:ignore.self class="modal fade" id="detailSiswaEkstrakurikuler" tabindex="-1" aria-labelledby="detailSiswaEkstrakurikulerLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-light p-3">
                <h5 class="modal-title">Detail Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <label class="form-label">Nama Siswa</label>
                        <p>{{ $nama_siswa }}</p>
                    </div>

                    <div class="col-lg-4">
                        <label class="form-label">Tanggal Pendaftaran</label>
                        <p>{{ $created_at }}</p>
                    </div>

                    <div class="col-4">
                        <label class="form-label">Kelas</label>
                        <p>{{ $nama_kelas }}</p>
                    </div>

                   <div class="col-12">
                        <label class="form-label">Ekskul yang Diikuti</label>
                        @if (!empty($ekstrakurikulerSiswa) && count($ekstrakurikulerSiswa) > 0)
                            <ul class="list-group list-group-flush">
                                @foreach ($ekstrakurikulerSiswa as $penempatan)
                                    <li class="list-group-item px-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-semibold">{{ $penempatan->ms_ekstrakurikuler->nama_ekstrakurikuler ?? '-' }}</div>
                                                <div class="fw-medium fs-14 text-success">
                                                    Rp {{ number_format($penempatan->ms_ekstrakurikuler->biaya ?? 0, 0, ',', '.') }}
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">Belum ada data ekstrakurikuler.</p>
                        @endif
                    </div>


                </div>
            </div>


            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
            </div>
        </div>
    </div>
</div>