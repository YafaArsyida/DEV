<div>
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
        <div class="card-header border-bottom bg-white">
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm flex-shrink-0">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                        <i class="ri-calendar-schedule-line"></i>
                    </div>
                </div>
                <div>
                    <h5 class="fw-bold mb-1">Jadwal Mengajar Saya</h5>
                    <small class="text-muted">
                        @if ($pegawai)
                            {{ $pegawai->nama_pegawai }}{{ $pegawai->nip ? ' - ' . $pegawai->nip : '' }}
                        @else
                            Data pegawai belum terhubung dengan akun ini.
                        @endif
                    </small>
                </div>
            </div>
        </div>
    </div>

    @if (! $pegawai)
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-body text-center py-5">
                <div class="avatar-md mx-auto mb-3">
                    <span class="avatar-title rounded-circle bg-warning-subtle text-warning fs-24">
                        <i class="ri-user-unfollow-line"></i>
                    </span>
                </div>
                <h5 class="mt-2">Akun Belum Terhubung ke Pegawai</h5>
                <p class="text-muted mb-0">
                    Hubungi administrator untuk menghubungkan akun pengguna dengan data pegawai/guru.
                </p>
            </div>
        </div>
    @else
        <div class="row g-3 mb-3">
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small text-uppercase fw-medium mb-1">Total Jadwal</div>
                        <h4 class="fw-bold mb-0">{{ $summary['total'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small text-uppercase fw-medium mb-1">Aktif</div>
                        <h4 class="fw-bold text-success mb-0">{{ $summary['aktif'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small text-uppercase fw-medium mb-1">Belum Berlaku</div>
                        <h4 class="fw-bold text-warning mb-0">{{ $summary['belum_berlaku'] }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-body p-3">
                        <div class="text-muted small text-uppercase fw-medium mb-1">Berakhir</div>
                        <h4 class="fw-bold text-secondary mb-0">{{ $summary['berakhir'] }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
            <div class="card-body p-3 p-lg-4">
                <div class="row g-3 align-items-end">
                    <div class="col-xl-5 col-md-6">
                        <label for="searchJadwalSaya" class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>
                        <div class="search-box">
                            <input type="search" id="searchJadwalSaya" class="form-control search"
                                wire:model.debounce.300ms="search"
                                placeholder="Cari kelas, mata pelajaran, atau keterangan...">
                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-6">
                        <button type="button" wire:click="resetFilter" class="btn btn-soft-secondary w-100">
                            <i class="ri-refresh-line me-1"></i> Reset
                        </button>
                    </div>
                </div>

                <div class="border-top pt-3 mt-3">
                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <span class="text-muted small text-uppercase fw-medium me-1">Hari</span>
                        <button type="button"
                            class="btn btn-sm rounded-pill {{ $selectedHari === '' ? 'btn-primary' : 'btn-soft-secondary' }}"
                            wire:click="setHari('')">Semua</button>
                        @foreach ($hariOptions as $value => $label)
                            <button type="button"
                                class="btn btn-sm rounded-pill {{ $selectedHari === $value ? 'btn-primary' : 'btn-soft-secondary' }}"
                                wire:click="setHari('{{ $value }}')">{{ $label }}</button>
                        @endforeach
                    </div>
                </div>

                <div wire:loading.delay wire:target="search, setHari, resetFilter" class="text-muted small mt-3">
                    <span class="spinner-border spinner-border-sm me-1"></span>
                    Memuat jadwal...
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom py-3 px-3 px-lg-4">
                <h5 class="fw-bold mb-1">Jadwal Mingguan</h5>
                <p class="text-muted small mb-0">Menampilkan {{ $totalFiltered }} jadwal Anda sesuai filter.</p>
            </div>
            <div class="card-body p-3 p-lg-4">
                @include('livewire.akademik.jadwal-mengajar._matrix', [
                    'clickable' => false,
                    'showTeacher' => false,
                ])
            </div>
        </div>
    @endif
</div>
