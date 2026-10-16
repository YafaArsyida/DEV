
<div>
    <div
        wire:ignore.self
        class="modal fade"
        id="ModalDetailPendaftar"
        tabindex="-1"
        aria-labelledby="ModalDetailPendaftarLabel"
        aria-hidden="true">

        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0">

                <div class="modal-header">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-user-search-line"></i>
                            </div>
                        </div>

                        <div>
                            <h5 class="modal-title mb-1" id="ModalDetailPendaftarLabel">
                                Detail Pendaftar
                            </h5>
                            <p class="text-muted small mb-0">
                                Ringkasan informasi calon siswa baru
                            </p>
                        </div>
                    </div>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Tutup">
                    </button>
                </div>

                <div class="modal-body">
                    @if (!$pendaftaran)
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary mb-3"></div>
                            <p class="text-muted mb-0">
                                Memuat informasi pendaftar...
                            </p>
                        </div>
                    @else
                        @php
                            $siswa = $pendaftaran->ppdb_calon_siswa;
                            $orangTua = $pendaftaran->ppdb_orangtua;
                            $alamat = $pendaftaran->ppdb_alamat;
                            $pendidikan = $pendaftaran->ppdb_pendidikan;

                            $statusLabel = [
                                'draft' => 'Draft',
                                'pengecekan' => 'Pengecekan',
                                'diterima' => 'Diterima',
                                'perbaikan' => 'Perlu Perbaikan',
                            ];

                            $statusClass = [
                                'draft' => 'bg-secondary-subtle text-secondary',
                                'pengecekan' => 'bg-warning-subtle text-warning',
                                'diterima' => 'bg-success-subtle text-success',
                                'perbaikan' => 'bg-danger-subtle text-danger',
                            ];
                        @endphp

                        {{-- Informasi pendaftaran --}}
                        <div class="card border rounded-4 shadow-none mb-4">
                            <div class="card-body">
                                <div class="d-flex flex-column flex-md-row justify-content-between gap-3">
                                    <div>
                                        <div class="text-muted small mb-1">
                                            Nomor Pendaftaran
                                        </div>
                                        <h5 class="mb-2">
                                            {{ $pendaftaran->nomor_pendaftaran ?? '-' }}
                                        </h5>

                                        <div class="text-muted small">
                                            <i class="ri-calendar-line me-1"></i>
                                            {{ $pendaftaran->created_at?->translatedFormat('d F Y, H:i') ?? '-' }}
                                        </div>
                                    </div>

                                    <div class="text-md-end">
                                        <span class="badge rounded-pill {{ $statusClass[$pendaftaran->status] ?? 'bg-secondary-subtle text-secondary' }}">
                                            {{ $statusLabel[$pendaftaran->status] ?? ucfirst($pendaftaran->status ?? '-') }}
                                        </span>

                                        <div class="mt-2 fw-medium">
                                            {{ $pendaftaran->ppdb_jalur->nama_jalur ?? '-' }}
                                        </div>
                                        <div class="text-muted small">
                                            {{ $pendaftaran->ppdb_periode->nama_periode ?? '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Data calon siswa --}}
                        <div class="border rounded-4 mb-4 overflow-hidden">
                            <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                        <i class="ri-user-3-line fs-18"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">Data Calon Siswa</h6>
                                    <p class="text-muted small mb-0">
                                        Identitas calon peserta didik
                                    </p>
                                </div>
                            </div>

                            <div class="p-3">
                                @if ($siswa)
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Nama Lengkap</div>
                                            <div class="fw-medium">{{ $siswa->nama_lengkap ?? '-' }}</div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">NIK</div>
                                            <div>{{ $siswa->nik ?? '-' }}</div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Tempat, Tanggal Lahir</div>
                                            <div>
                                                {{ $siswa->tempat_lahir ?? '-' }},
                                                {{ $siswa->tanggal_lahir
                                                    ? \Carbon\Carbon::parse($siswa->tanggal_lahir)->translatedFormat('d F Y')
                                                    : '-' }}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Jenis Kelamin</div>
                                            <div>{{ $siswa->jenis_kelamin ?? '-' }}</div>
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">
                                        Data calon siswa belum diisi.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Data orang tua dan wali --}}
                        <div class="border rounded-4 mb-4 overflow-hidden">
                            <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                                <div class="avatar-sm">
                                    <div class="avatar-title bg-success-subtle text-success rounded-circle">
                                        <i class="ri-parent-line fs-18"></i>
                                    </div>
                                </div>
                                <div>
                                    <h6 class="mb-1">Data Orang Tua / Wali</h6>
                                    <p class="text-muted small mb-0">
                                        Informasi keluarga dan kontak
                                    </p>
                                </div>
                            </div>

                            <div class="p-3">
                                @if ($orangTua)
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Nama Ayah</div>
                                            <div>{{ $orangTua->nama_ayah ?? '-' }}</div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Telepon Ayah</div>
                                            <div>{{ $orangTua->telepon_ayah ?? '-' }}</div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Nama Ibu</div>
                                            <div>{{ $orangTua->nama_ibu ?? '-' }}</div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Telepon Ibu</div>
                                            <div>{{ $orangTua->telepon_ibu ?? '-' }}</div>
                                        </div>

                                        @if (filled($orangTua->nama_wali))
                                            <div class="col-12">
                                                <hr class="my-0">
                                            </div>

                                            <div class="col-md-6">
                                                <div class="text-muted small mb-1">Nama Wali</div>
                                                <div>{{ $orangTua->nama_wali }}</div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="text-muted small mb-1">Telepon Wali</div>
                                                <div>{{ $orangTua->telepon_wali ?? '-' }}</div>
                                            </div>
                                        @endif
                                    </div>
                                @else
                                    <p class="text-muted mb-0">
                                        Data orang tua belum diisi.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Alamat --}}
                        @if ($alamat)
                            <div class="border rounded-4 mb-4 overflow-hidden">
                                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                            <i class="ri-map-pin-line fs-18"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Alamat</h6>
                                        <p class="text-muted small mb-0">
                                            Domisili calon siswa
                                        </p>
                                    </div>
                                </div>

                                <div class="p-3">
                                    <div class="text-muted small mb-1">Alamat Lengkap</div>
                                    <div>
                                        {{ $alamat->alamat_lengkap ?? '-' }}
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Pendidikan --}}
                        @if ($pendidikan)
                            <div class="border rounded-4 mb-4 overflow-hidden">
                                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle">
                                            <i class="ri-graduation-cap-line fs-18"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Riwayat Pendidikan</h6>
                                        <p class="text-muted small mb-0">
                                            Informasi sekolah asal
                                        </p>
                                    </div>
                                </div>

                                <div class="p-3">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">Nama Sekolah</div>
                                            <div>{{ $pendidikan->nama_sekolah ?? '-' }}</div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="text-muted small mb-1">NISN</div>
                                            <div>{{ $pendidikan->nisn ?? '-' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Berkas --}}
                        @if ($pendaftaran->ppdb_berkas->isNotEmpty())
                            <div class="border rounded-4 mb-4 overflow-hidden">
                                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-danger-subtle text-danger rounded-circle">
                                            <i class="ri-file-list-3-line fs-18"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Berkas Pendaftaran</h6>
                                        <p class="text-muted small mb-0">
                                            Berkas yang telah diunggah
                                        </p>
                                    </div>
                                </div>

                                <div class="list-group list-group-flush">
                                    @foreach ($pendaftaran->ppdb_berkas as $berkas)
                                        <div class="list-group-item px-3 py-3">
                                            <div class="d-flex align-items-center gap-3">
                                                <i class="ri-file-text-line fs-20 text-muted"></i>
                                                <div class="flex-grow-1">
                                                    <div class="fw-medium">
                                                        {{ $berkas->nama_berkas ?? $berkas->jenis_berkas ?? 'Berkas' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Jawaban tambahan --}}
                        @if (count($jawabanTambahan))
                            <div class="border rounded-4 mb-2 overflow-hidden">
                                <div class="d-flex align-items-center gap-3 p-3 border-bottom">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-info-subtle text-info rounded-circle">
                                            <i class="ri-question-answer-line fs-18"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <h6 class="mb-1">Informasi Tambahan</h6>
                                        <p class="text-muted small mb-0">
                                            Jawaban pertanyaan pendaftaran
                                        </p>
                                    </div>
                                </div>

                                <div class="p-3">
                                    @foreach ($jawabanTambahan as $jawaban)
                                        @if (filled($jawaban->jawaban))
                                            <div class="{{ !$loop->last ? 'mb-3' : '' }}">
                                                <div class="text-muted small mb-1">
                                                    {{ $jawaban->ppdb_pertanyaan->pertanyaan ?? $jawaban->ppdb_pertanyaan->nama_pertanyaan ?? 'Pertanyaan' }}
                                                </div>
                                                <div>{{ $jawaban->jawaban }}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
