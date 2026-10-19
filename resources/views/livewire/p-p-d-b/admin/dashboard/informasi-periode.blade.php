
<div class="flex-grow-1">
    @if ($periode)
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-md flex-shrink-0">
                <div class="avatar-title bg-primary-subtle
                            text-primary rounded-3 fs-3">
                    <i class="mdi mdi-calendar-check-outline"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">
                    {{ $periode->nama_periode }}
                </h5>

                <p class="text-muted mb-0">
                    Periode pendaftaran
                    {{ $periode->tanggal_mulai
                        ? \Carbon\Carbon::parse($periode->tanggal_mulai)
                            ->translatedFormat('d F Y')
                        : '-' }}
                    –
                    {{ $periode->tanggal_selesai
                        ? \Carbon\Carbon::parse($periode->tanggal_selesai)
                            ->translatedFormat('d F Y')
                        : '-' }}
                </p>

                <div class="small text-muted mt-1">
                    Kuota:
                    {{ number_format($kuota, 0, ',', '.') }}
                    calon siswa
                </div>
            </div>
        </div>
    @else
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-md flex-shrink-0">
                <div class="avatar-title bg-secondary-subtle
                            text-secondary rounded-3 fs-3">
                    <i class="mdi mdi-calendar-remove-outline"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">
                    Belum Ada Periode PPDB
                </h5>

                <p class="text-muted mb-0">
                    @if (!$selectedJenjang || !$selectedTahunAjar)
                        Silakan pilih jenjang dan tahun ajar
                        untuk melihat informasi periode.
                    @else
                        Belum ada periode aktif untuk jenjang
                        dan tahun ajar yang dipilih.
                    @endif
                </p>
            </div>
        </div>
    @endif
</div>
