<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    {{-- HEADER --}}
    <div class="card-header bg-white">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-route-line"></i>
                </div>
            </div>

            <div>
                <h5 class="fw-bold mb-1">Jalur Pendaftaran</h5>
                <small class="text-muted">
                    Pilih jalur sesuai ketentuan PPDB dan ketersediaan kuota.
                </small>
            </div>
        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">
        <div class="card border rounded-4 shadow-sm">
            <div class="card-header bg-transparent border-bottom">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-list-check"></i>
                        </div>
                    </div>
                    <div>
                        <h6 class="fw-semibold mb-1">Pilihan Jalur</h6>
                        <small class="text-muted">
                            Pilih salah satu jalur pendaftaran yang tersedia.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row g-3">
                    @forelse ($jalurTersedia as $jalur)
                        @php
                            $terpilih = (string) $jalurTerpilih ===
                                (string) $jalur->ppdb_jalur_id;

                            $kuotaPenuh = $jalur->kuota !== null
                                && $jalur->jumlah_pendaftar >= $jalur->kuota;

                            // Kuota null berarti tidak dibatasi.
                            $kuotaTersedia = !$kuotaPenuh;

                            // Pilihan yang sudah dipilih tetap dipertahankan.
                            $radioDisabled = $kuotaPenuh && !$terpilih;
                        @endphp

                        <div class="col-12">
                            <label
                                for="jalur-bantu-{{ $jalur->ppdb_jalur_id }}"
                                class="border rounded-3 p-3 d-flex gap-3 h-100
                                    {{ $terpilih ? 'border-primary bg-primary-subtle' : '' }}
                                    {{ !$kuotaTersedia ? 'opacity-75' : '' }}">

                                <input
                                    type="radio"
                                    id="jalur-bantu-{{ $jalur->ppdb_jalur_id }}"
                                    class="form-check-input mt-1"
                                    name="jalur_pendaftaran"
                                    value="{{ $jalur->ppdb_jalur_id }}"
                                    wire:model="jalurTerpilih"
                                    @disabled($radioDisabled)
                                >

                                <div class="flex-grow-1 min-w-0">
                                    <div class="d-flex justify-content-between align-items-start gap-2 flex-wrap mb-2">
                                        <span class="fw-semibold">
                                            {{ $jalur->nama_jalur }}
                                        </span>

                                        @if ($kuotaTersedia)
                                            <span class="badge bg-success-subtle text-success">
                                                Kuota tersedia
                                            </span>
                                        @else
                                            <span class="badge bg-danger-subtle text-danger">
                                                Kuota penuh
                                            </span>
                                        @endif
                                    </div>

                                    <p class="text-muted small mb-2">
                                        {{ $jalur->deskripsi ?: 'Jalur pendaftaran sesuai ketentuan sekolah.' }}
                                    </p>

                                    <small class="text-muted">
                                        <i class="ri-group-line me-1"></i>
                                        Kuota {{ number_format($jalur->kuota, 0, ',', '.') }} siswa
                                        <span class="mx-1">·</span>
                                        {{ number_format($jalur->jumlah_pendaftar, 0, ',', '.') }} pendaftar
                                    </small>
                                </div>
                            </label>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <div class="avatar-md mx-auto mb-3">
                                    <div class="avatar-title bg-secondary-subtle text-secondary rounded-circle fs-3">
                                        <i class="ri-route-line"></i>
                                    </div>
                                </div>

                                <h6 class="fw-semibold mb-1">
                                    Belum Ada Jalur Pendaftaran
                                </h6>
                                <p class="text-muted small mb-0">
                                    Tidak ada jalur aktif pada periode PPDB yang dipilih.
                                </p>
                            </div>
                        </div>
                    @endforelse
                </div>

                @error('jalurTerpilih')
                    <div class="text-danger small mt-3">
                        {{ $message }}
                    </div>
                @enderror
            </div>
        </div>
    </div>
</div>