
<div wire:ignore.self
    class="modal fade"
    id="ModalDetailPenugasanGuru"
    tabindex="-1"
    aria-labelledby="ModalDetailPenugasanGuruLabel"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="mdi mdi-account-cog-outline fs-18"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1" id="ModalDetailPenugasanGuruLabel">
                            Detail Penugasan Guru
                        </h5>
                        <small class="text-muted">
                            Informasi guru pengampu, mata pelajaran, kelas, dan status penugasan.
                        </small>
                    </div>
                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal"
                    aria-label="Tutup">
                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            {{-- BODY --}}
            <div class="modal-body">

                @if ($penugasanDetail)

                    {{-- PROFILE --}}
                    <div class="text-center mb-4">
                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="mdi mdi-account-cog-outline fs-24"></i>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-1">
                            {{ $penugasanDetail->ms_pegawai?->nama_pegawai ?: '-' }}
                        </h5>

                        <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2">
                            {{ $penugasanDetail->ms_pegawai?->nip ?: 'NIP belum diisi' }}
                        </span>
                    </div>

                    {{-- INFORMASI PENUGASAN --}}
                    <div class="mb-3">

                        {{-- MATA PELAJARAN --}}
                        <div class="border rounded-4 p-3 mb-3">
                            <div class="text-muted small mb-1">
                                Mata Pelajaran
                            </div>
                            <div class="fw-medium text-dark">
                                <i class="ri-book-open-line text-primary me-2"></i>
                                {{ $penugasanDetail->ms_mata_pelajaran?->nama_mapel ?? 'Tanpa mata pelajaran' }}
                                @if ($penugasanDetail->ms_mata_pelajaran?->kode_mapel)
                                    <span class="text-muted">({{ $penugasanDetail->ms_mata_pelajaran->kode_mapel }})</span>
                                @endif
                            </div>
                        </div>

                        {{-- KELAS --}}
                        <div class="border rounded-4 p-3 mb-3">
                            <div class="text-muted small mb-1">
                                Kelas
                            </div>
                            <div class="fw-medium text-dark">
                                <i class="ri-building-4-line text-info me-2"></i>
                                {{ $penugasanDetail->ms_kelas?->nama_kelas ?: '-' }}
                            </div>
                        </div>

                        {{-- JENJANG --}}
                        <div class="border rounded-4 p-3 mb-3">
                            <div class="text-muted small mb-1">
                                Jenjang Pendidikan
                            </div>
                            <div class="fw-medium text-dark">
                                <i class="ri-graduation-cap-line text-primary me-2"></i>
                                {{ $penugasanDetail->ms_kelas?->ms_jenjang?->nama_jenjang ?: '-' }}
                            </div>
                        </div>

                        {{-- TAHUN AJARAN --}}
                        <div class="border rounded-4 p-3 mb-3">
                            <div class="text-muted small mb-1">
                                Tahun Ajaran
                            </div>
                            <div class="fw-medium text-dark">
                                <i class="ri-calendar-line text-success me-2"></i>
                                {{ $penugasanDetail->ms_tahun_ajar?->nama_tahun_ajar
                                    ?? $penugasanDetail->ms_kelas?->ms_tahun_ajar?->nama_tahun_ajar
                                    ?? '-' }}
                            </div>
                        </div>

                        {{-- STATUS --}}
                        <div class="border rounded-4 p-3 mb-3">
                            <div class="text-muted small mb-1">
                                Status
                            </div>
                            <div class="fw-medium text-dark">
                                <span class="badge bg-{{ $penugasanDetail->status === 'AKTIF' ? 'success' : 'secondary' }}-subtle text-{{ $penugasanDetail->status === 'AKTIF' ? 'success' : 'secondary' }}">
                                    {{ $penugasanDetail->status === 'AKTIF' ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </div>
                        </div>

                        {{-- JUMLAH JADWAL --}}
                        <div class="border rounded-4 p-3">
                            <div class="text-muted small mb-1">
                                Jadwal Mengajar
                            </div>
                            <div class="fw-medium text-dark">
                                <i class="ri-calendar-schedule-line text-info me-2"></i>
                                {{ $jumlahJadwal }} jadwal
                            </div>
                        </div>

                    </div>

                    {{-- DESKRIPSI --}}
                    <div class="border rounded-4 p-3">
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <div class="avatar-sm flex-shrink-0">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                    <i class="ri-file-text-line"></i>
                                </div>
                            </div>

                            <div>
                                <div class="fw-semibold text-dark">
                                    Keterangan Penugasan
                                </div>
                                <small class="text-muted">
                                    Catatan tambahan mengenai penugasan.
                                </small>
                            </div>
                        </div>

                        <div class="text-muted small text-break">
                            {{ $penugasanDetail->deskripsi ?: 'Tidak ada keterangan penugasan.' }}
                        </div>
                    </div>

                @endif

            </div>

            {{-- FOOTER --}}
            <div class="modal-footer">
                <button type="button"
                    class="btn btn-light rounded-pill px-4"
                    data-bs-dismiss="modal">
                    <i class="ri-close-line me-1"></i>
                    Tutup
                </button>
            </div>

        </div>
    </div>
</div>
