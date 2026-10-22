<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header bg-white border-bottom p-3 p-md-4">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-git-branch-line"></i>
                </div>
            </div>

            <div class="flex-grow-1 min-w-0">
                <h5 class="fw-bold mb-1 text-break">
                    Pilihan Jalur Pendaftaran
                </h5>
                <p class="text-muted small mb-0">
                    Informasi jalur dan gelombang pendaftaran Anda. 
                </p>
            </div>

            <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 flex-shrink-0">
                Langkah 1
            </span>
        </div>
    </div>

    <div class="card-body p-3 p-md-4">
        {{-- INFORMASI --}}
        <div class="d-flex align-items-start gap-2 mb-4">
            <i class="ri-information-line text-primary fs-18 mt-1"></i>

            <div>
                <h6 class="fw-semibold mb-1">
                    Pilih Jalur Pendaftaran
                </h6>
                <p class="text-muted small mb-0">
                    Bacalah deskripsi dan ketentuan setiap jalur sebelum
                    memilih. Pilihan akan digunakan untuk proses pendaftaran
                    calon siswa pada periode ini.
                </p>
            </div>
        </div>

        {{-- ERROR VALIDASI --}}
        @error('jalurTerpilih')
            <div class="alert alert-danger border-0 rounded-3 mb-4">
                <i class="ri-error-warning-line me-1"></i>
                {{ $message }}
            </div>
        @enderror

        {{-- DAFTAR JALUR --}}
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

                        // Jalur yang sudah dipilih tetap bisa dipertahankan.
                        $radioDisabled = $kuotaPenuh && ! $terpilih;
                    @endphp

                    {{-- KOLOM JALUR --}}
                    <div class="col-md-6 col-xl-4 d-flex" wire:key="jalur-{{ $jalur->ppdb_jalur_id }}">
                        <label
                            for="jalur-{{ $jalur->ppdb_jalur_id }}"
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
                                    id="jalur-{{ $jalur->ppdb_jalur_id }}"
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

            <div class="d-flex flex-column flex-sm-row justify-content-between gap-2 mt-4 pt-3 border-top">
                <a href="{{ route('ppdb.dashboard', [
                    'jenjang' => $periode->ms_jenjang->slug,
                ]) }}"
                    class="btn btn-light border rounded-pill px-4">
                    <i class="ri-arrow-left-line me-1"></i>
                    Kembali
                </a>

                <button
                    type="button"
                    class="btn btn-primary rounded-pill px-4"
                    wire:click="pilihJalur"
                    wire:loading.attr="disabled"
                    wire:target="pilihJalur"
                    @disabled(!$jalurTerpilih || $jalurTersedia->every(
                        fn ($jalur) =>
                            $jalur->kuota !== null &&
                            $jalur->jumlah_pendaftar >= $jalur->kuota
                    ))
                >
                    <span wire:loading.remove wire:target="pilihJalur">
                        Simpan &amp; Lanjutkan
                        <i class="ri-arrow-right-line ms-1"></i>
                    </span>

                    <span wire:loading wire:target="pilihJalur">
                        <span class="spinner-border spinner-border-sm me-1"></span>
                        Memproses...
                    </span>
                </button>
            </div>
        @else
            {{-- KOSONG --}}
            <div class="text-center py-5">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-2">
                        <i class="ri-git-branch-line"></i>
                    </div>
                </div>

                <h6 class="fw-semibold mb-2">
                    Belum Ada Jalur Pendaftaran
                </h6>

                <p class="text-muted small mb-0">
                    Belum ada jalur yang tersedia pada periode PPDB ini.
                    Silakan hubungi pihak sekolah jika membutuhkan informasi.
                </p>
            </div>

            <div class="text-end">
                <a href="{{ route('ppdb.dashboard') }}" class="btn btn-light border rounded-pill px-4">
                    Kembali ke Dashboard
                </a>
            </div>
        @endif
    </div>
</div>