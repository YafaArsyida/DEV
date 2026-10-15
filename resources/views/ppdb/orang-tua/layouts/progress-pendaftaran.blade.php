@php
    $steps = [
        1 => 'Pilih Jalur',
        2 => 'Data Siswa',
        3 => 'Orang Tua/Wali',
        4 => 'Alamat',
        5 => 'Pendidikan',
        6 => 'Berkas',
        7 => 'Review',
        8 => 'Pengiriman',
    ];

    $totalStep = count($steps);
    $stepAktif = max(1, min($stepAktif ?? 1, $totalStep));
    $persentase = round(($stepAktif / $totalStep) * 100);
@endphp

<div class="card border shadow-none rounded-4 mb-4">
    <div class="card-body">

        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 mb-3">
            <div>
                <h6 class="fw-semibold mb-1">Progress Pendaftaran</h6>
                <small class="text-muted">
                    Lengkapi setiap langkah untuk menyelesaikan pendaftaran.
                </small>
            </div>

            <span class="text-primary fw-semibold">
                {{ $persentase }}%
            </span>
        </div>

        <div
            class="progress rounded-pill"
            style="height: 8px;"
            role="progressbar"
            aria-label="Progress pendaftaran"
            aria-valuenow="{{ $persentase }}"
            aria-valuemin="0"
            aria-valuemax="100"
        >
            <div
                class="progress-bar bg-primary"
                style="width: {{ $persentase }}%;"
            ></div>
        </div>

        <div class="d-flex flex-wrap gap-3 mt-3">
            @foreach ($steps as $nomor => $nama)
                @php
                    $selesai = $nomor < $stepAktif;
                    $aktif = $nomor === $stepAktif;
                @endphp

                <div class="d-flex align-items-center gap-2
                    {{ $selesai ? 'text-success' : ($aktif ? 'text-primary' : 'text-muted') }}">

                    <div class="avatar-xs">
                        <div class="avatar-title rounded-circle fs-12
                            {{ $selesai
                                ? 'bg-success-subtle text-success'
                                : ($aktif
                                    ? 'bg-primary text-white'
                                    : 'bg-light text-muted') }}">

                            @if ($selesai)
                                <i class="ri-check-line"></i>
                            @else
                                {{ str_pad($nomor, 2, '0', STR_PAD_LEFT) }}
                            @endif
                        </div>
                    </div>

                    <span class="small {{ $aktif ? 'fw-semibold' : '' }}">
                        {{ $nama }}
                    </span>
                </div>
            @endforeach
        </div>

    </div>
</div>