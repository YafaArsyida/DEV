{{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
<div wire:ignore.self class="modal fade" id="editSiswaEkstrakurikuler" tabindex="-1" aria-labelledby="ModalAddSiswa" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0 pb-0 p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                            <i class="ri-trophy-line fs-20"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Kelola Ekstrakurikuler
                        </h5>
                        <small class="text-muted">
                            Atur ekstrakurikuler yang diikuti siswa
                        </small>
                    </div>
                </div>

                <button
                    class="btn btn-light btn-icon rounded-circle"
                    data-bs-dismiss="modal">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <form wire:submit.prevent="update">
                <div class="modal-body p-4">
                    {{-- HERO SISWA --}}
                    <div class="bg-light rounded-4 p-4 mb-4">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start gap-3">
                            <div class="flex-grow-1">
                                <span class="badge bg-primary-subtle text-primary rounded-pill px-3 py-2 mb-2">
                                    Peserta Ekstrakurikuler
                                </span>

                                <h2 class="fw-bold mb-2">
                                    {{ $nama_siswa }}
                                </h2>

                                <div class="d-flex flex-wrap gap-4 text-muted fs-13">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-graduation-cap-line text-primary fs-16"></i>
                                        <div>
                                            <div class="fw-semibold text-body">
                                                {{ $nama_kelas }}
                                            </div>
                                        </div>
                                    </div>

                                    @if($telepon)
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="ri-smartphone-line text-info fs-16"></i>
                                        <div>
                                            <div class="fw-semibold text-body">
                                                {{ $telepon }}
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3">
                        @foreach($select_ekstrakurikuler as $item)
                            <div class="col-lg-6">
                                <label for="ekskul_{{ $item->ms_ekstrakurikuler_id }}" class="w-100">
                                    <div class="card pricing-box mb-0 cursor-pointer">
                                        <div class="card-body bg-light m-2 p-4 rounded-3">
                                            {{-- Header --}}
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="form-check mb-0 flex-grow-1">
                                                    <input
                                                        class="form-check-input"
                                                        type="radio"
                                                        name="ms_ekstrakurikuler_id"
                                                        id="ekskul_{{ $item->ms_ekstrakurikuler_id }}"
                                                        wire:model="ms_ekstrakurikuler_id"
                                                        value="{{ $item->ms_ekstrakurikuler_id }}">

                                                    <label class="form-check-label fs-12 fw-semibold ms-2"
                                                        for="ekskul_{{ $item->ms_ekstrakurikuler_id }}">
                                                        {{ $item->nama_ekstrakurikuler }}
                                                    </label>

                                                </div>

                                                <div class="text-end">
                                                    <h3 class="fw-semibold mb-0">
                                                        Rp{{ number_format($item->biaya, 0, ',', '.') }}
                                                    </h3>
                                                </div>

                                            </div>

                                            {{-- Deskripsi --}}
                                            <p class="text-muted mb-3 small"
                                            title="{{ $item->deskripsi }}">
                                                {{ \Illuminate\Support\Str::limit($item->deskripsi, 150) }}
                                            </p>

                                            {{-- Informasi --}}
                                            <ul class="list-unstyled vstack gap-2 mb-0">

                                                <li>
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 text-success me-2">
                                                            <i class="ri-checkbox-circle-fill"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            Kuota <strong>{{ $item->kuota }}</strong> siswa
                                                        </div>
                                                    </div>
                                                </li>

                                                <li>
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 text-primary me-2">
                                                            <i class="ri-team-line"></i>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <strong>{{ $item->total_peserta ?? 0 }}</strong> peserta
                                                        </div>
                                                    </div>
                                                </li>

                                            </ul>

                                        </div>

                                    </div>

                                </label>

                            </div>

                        @endforeach
                    </div>

                    @error('ms_ekstrakurikuler_id')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="ri-save-3-line me-1"></i>
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>