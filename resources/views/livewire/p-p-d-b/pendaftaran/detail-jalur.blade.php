<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
             <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-git-branch-line"></i>
                        </div>
                    </div>
    
                    <div>
                        <h5 class="fw-bold mb-1">
                            Pilihan Jalur Pendaftaran
                        </h5>
                        <small>
                            Informasi pilihan jalur pendaftaran Anda.
                        </small>
                    </div>
                </div>
            </div>

            @if ($bolehUbahData)
                @if (! $modeEdit)
                    <button
                        type="button"
                        wire:click="edit"
                        wire:loading.attr="disabled"
                        class="btn btn-outline-primary rounded-pill px-3">

                        <i class="ri-edit-line me-1"></i>
                        Ubah Data
                    </button>
                @else
                    <span class="badge bg-warning-subtle text-warning
                                rounded-pill px-3 py-2">
                        <i class="ri-edit-line me-1"></i>
                        Mode Edit
                    </span>
                @endif
            @else
                <span class="badge bg-light text-muted
                            border rounded-pill px-3 py-2">
                    <i class="ri-lock-line me-1"></i>
                    Tidak Dapat Diubah
                </span>
            @endif
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body p-3 p-md-4">
        @if (! $modeEdit)
            {{-- MODE DETAIL --}}
            <div wire:key="mode-detail-jalur-{{ $pendaftaranId }}">
                <div class="row g-3">

                    {{-- NOMOR PENDAFTARAN --}}
                    <div class="col-12">
                        <div class="p-3 bg-light rounded-3">
                            <div class="text-muted small mb-1">
                                Nomor Pendaftaran
                            </div>

                            <div class="fw-bold text-primary">
                                {{ $pendaftaran->nomor_pendaftaran }}
                            </div>
                        </div>
                    </div>

                    {{-- JALUR PENDAFTARAN --}}
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">
                            Jalur Pendaftaran
                        </div>

                        <div class="fw-semibold">
                            {{ $pendaftaran->ppdb_jalur->nama_jalur
                                ?? 'Belum memilih jalur' }}
                        </div>
                    </div>

                    {{-- GELOMBANG PENDAFTARAN --}}
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">
                            Gelombang Pendaftaran
                        </div>

                        <div class="fw-semibold">
                            {{ $pendaftaran->ppdb_gelombang->nama_gelombang
                                ?? 'Belum tersedia' }}
                        </div>
                    </div>

                    {{-- TANGGAL PENDAFTARAN --}}
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">
                            Tanggal Pendaftaran
                        </div>

                        <div class="fw-semibold">
                            {{ $pendaftaran->created_at
                                ? $pendaftaran->created_at->translatedFormat('d F Y')
                                : '-' }}
                        </div>
                    </div>

                    {{-- STATUS PENDAFTARAN --}}
                    <div class="col-md-6">
                        <div class="text-muted small mb-1">
                            Status Pendaftaran
                        </div>

                        <div class="fw-semibold">
                            {{ $pendaftaran->status ?: 'Belum tersedia' }}
                        </div>
                    </div>

                </div>

                {{-- NAVIGASI MODE DETAIL --}}
                <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">
                    <a href="{{ route('ppdb.dashboard') }}" class="btn btn-light border rounded-pill px-4">
                        <i class="ri-arrow-left-line me-1"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('ppdb.pendaftaran.data-siswa') }}" class="btn btn-primary rounded-pill px-4">
                        Data Calon Siswa
                        <i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>
            </div>
        @else
            {{-- MODE EDIT --}}
            <div wire:key="mode-edit-jalur-{{ $pendaftaranId }}">

                <div class="border rounded-4 p-3 p-md-4 mb-4">
                    <div class="d-flex align-items-start gap-3">

                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="ri-git-branch-line fs-20"></i>
                            </div>
                        </div>

                        <div class="flex-grow-1 min-w-0">
                            <div class="text-muted small mb-1">
                                Jalur yang Dipilih Saat Ini
                            </div>

                            <h6 class="fw-bold mb-1">
                                {{ $pendaftaran->ppdb_jalur->nama_jalur
                                    ?? 'Belum memilih jalur' }}
                            </h6>

                            <div class="text-muted small">
                                Gelombang:
                                {{ $pendaftaran->ppdb_gelombang->nama_gelombang
                                    ?? 'Belum tersedia' }}
                            </div>
                        </div>

                    </div>
                </div>
                @php
                    $urutanJalur = [
                        'REGULER' => 1,
                        'AFIRMASI' => 2,
                        'PRESTASI' => 3,
                    ];

                    $jalurTersedia = $jalurTersedia
                        ->sortBy(function ($jalur) use ($urutanJalur) {
                            $kode = strtoupper($jalur->kode_jalur ?? '');

                            foreach ($urutanJalur as $nama => $urutan) {
                                if (str_contains($kode, $nama)) {
                                    return $urutan;
                                }
                            }

                            return 99;
                        })
                        ->values();
                @endphp

                @if ($jalurTersedia->isNotEmpty())
                    <div class="row g-3">
                        @foreach ($jalurTersedia as $jalur)
                            @php
                                $kode = strtoupper($jalur->kode_jalur ?? '');

                                $ikon = match (true) {
                                    str_contains($kode, 'REGULER') => 'ri-user-add-line',
                                    str_contains($kode, 'PRESTASI') => 'ri-medal-line',
                                    str_contains($kode, 'AFIRMASI') => 'ri-hand-heart-line',
                                    default => 'ri-git-branch-line',
                                };

                                $warna = match (true) {
                                    str_contains($kode, 'PRESTASI') => 'warning',
                                    str_contains($kode, 'AFIRMASI') => 'success',
                                    default => 'primary',
                                };

                                $terpilih = (string) $jalurTerpilih ===
                                    (string) $jalur->ppdb_jalur_id;

                                $kuotaPenuh = $jalur->kuota !== null &&
                                    $jalur->jumlah_pendaftar >= $jalur->kuota;

                                // Pilihan saat ini tetap dapat dipertahankan.
                                $radioDisabled = $kuotaPenuh && ! $terpilih;
                            @endphp

                            <div
                                class="col-md-6 col-xl-4 d-flex"
                                wire:key="jalur-edit-{{ $jalur->ppdb_jalur_id }}"
                            >
                                <label
                                    for="jalur-edit-{{ $jalur->ppdb_jalur_id }}"
                                    class="border rounded-4 p-3 d-flex flex-column flex-grow-1 w-100
                                        {{ $terpilih ? 'border-primary bg-primary-subtle' : 'border-light-subtle' }}
                                        {{ $radioDisabled ? 'opacity-75' : '' }}"
                                    style="cursor: {{ $radioDisabled ? 'not-allowed' : 'pointer' }};"
                                >
                                    {{-- IKON DAN RADIO --}}
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-3">
                                        <div class="avatar-sm flex-shrink-0">
                                            <div class="avatar-title bg-{{ $warna }}-subtle text-{{ $warna }} rounded-3 fs-20">
                                                <i class="{{ $ikon }}"></i>
                                            </div>
                                        </div>

                                        <input
                                            type="radio"
                                            class="form-check-input flex-shrink-0"
                                            name="jalurTerpilih"
                                            id="jalur-edit-{{ $jalur->ppdb_jalur_id }}"
                                            value="{{ $jalur->ppdb_jalur_id }}"
                                            wire:model="jalurTerpilih"
                                            @disabled($radioDisabled)
                                        >
                                    </div>

                                    {{-- IDENTITAS JALUR --}}
                                    <h6 class="fw-bold mb-2">
                                        {{ $jalur->nama_jalur }}
                                    </h6>

                                    <p class="text-muted small mb-3">
                                        {{ $jalur->deskripsi ?: 'Informasi jalur belum tersedia.' }}
                                    </p>

                                    {{-- INFORMASI BIAYA DAN KUOTA --}}
                                    <div class="mt-auto pt-3 border-top">
                                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                                            <span class="text-muted small">
                                                Biaya pendaftaran
                                            </span>

                                            <span class="fw-semibold small text-end">
                                                @if ($jalur->biaya_pendaftaran === null)
                                                    Belum ditentukan
                                                @elseif ((float) $jalur->biaya_pendaftaran === 0.0)
                                                    Gratis
                                                @else
                                                    Rp{{ number_format((float) $jalur->biaya_pendaftaran, 0, ',', '.') }}
                                                @endif
                                            </span>
                                        </div>

                                        <div class="d-flex justify-content-between align-items-start gap-3">
                                            <span class="text-muted small">
                                                Kuota
                                            </span>

                                            <span class="fw-semibold small text-end">
                                                @if ($jalur->kuota === null)
                                                    Tidak dibatasi
                                                @else
                                                    {{ number_format($jalur->kuota, 0, ',', '.') }} siswa
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    {{-- STATUS JALUR --}}
                                    <div class="mt-3 pt-3 border-top">
                                        @if ($terpilih)
                                            <span class="text-primary small fw-semibold">
                                                <i class="ri-checkbox-circle-fill me-1"></i>
                                                Pilihan saat ini
                                            </span>
                                        @elseif ($kuotaPenuh)
                                            <span class="text-danger small fw-semibold">
                                                <i class="ri-close-circle-line me-1"></i>
                                                Kuota telah terpenuhi
                                            </span>
                                        @else
                                            <span class="text-muted small">
                                                <i class="ri-checkbox-blank-circle-line me-1"></i>
                                                Pilih jalur ini
                                            </span>
                                        @endif
                                    </div>
                                </label>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-5">
                        <div class="avatar-md mx-auto mb-3">
                            <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                <i class="ri-git-branch-line"></i>
                            </div>
                        </div>

                        <h6 class="fw-semibold mb-1">Belum Ada Jalur Tersedia</h6>
                        <p class="text-muted small mb-0">
                            Saat ini belum ada jalur pendaftaran yang dapat dipilih.
                        </p>
                    </div>
                @endif

                {{-- TOMBOL AKSI MODE EDIT --}}
                <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">
                    <button
                        type="button"
                        class="btn btn-light border rounded-pill px-4"
                        wire:click="batalEdit"
                        wire:loading.attr="disabled"
                        wire:target="batalEdit,perbaruiJalur"
                    >
                        <i class="ri-arrow-left-line me-1"></i>
                        Batal
                    </button>

                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4"
                        wire:click="perbaruiJalur"
                        wire:loading.attr="disabled"
                        wire:target="batalEdit,perbaruiJalur"
                        @disabled(!$jalurTerpilih)
                    >
                        <span wire:loading.remove wire:target="perbaruiJalur">
                            <i class="ri-save-line me-1"></i>
                            Simpan Perubahan
                        </span>

                        <span wire:loading wire:target="perbaruiJalur">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Memproses...
                        </span>
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>