<div wire:ignore.self class="modal fade" id="editSiswaEkstrakurikuler" tabindex="-1"
    aria-labelledby="ModalAddSiswa" aria-hidden="true">

    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">

            {{-- HEADER --}}
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">

                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-info-subtle text-info rounded-circle fs-20">
                            <i class="ri-trophy-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Kelola Ekstrakurikuler
                        </h5>
                        <small class="text-muted d-block">
                            Atur ekstrakurikuler yang diikuti siswa
                        </small>
                    </div>

                </div>

                <button type="button"
                    class="btn btn-light btn-icon rounded-circle ms-auto"
                    data-bs-dismiss="modal"
                    aria-label="Close">

                    <i class="ri-close-line fs-18"></i>
                </button>
            </div>

            <form wire:submit.prevent="update">

                {{-- BODY --}}
                <div class="modal-body px-4 px-lg-5 py-4">

                    {{-- PROFILE SISWA --}}
                    <div class="text-center mb-4">

                        <div class="avatar-lg mx-auto mb-3">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle">
                                <i class="ri-user-3-line fs-24"></i>
                            </div>
                        </div>

                        <h5 class="fw-bold mb-1">
                            {{ $nama_siswa ?: '-' }}
                        </h5>

                        <div class="d-flex justify-content-center flex-wrap gap-3 text-muted fs-13">

                            <div class="d-flex align-items-center gap-2">
                                <i class="ri-graduation-cap-line text-primary"></i>
                                <span class="fw-medium text-body">
                                    {{ $nama_kelas ?: '-' }}
                                </span>
                            </div>

                            @if ($telepon)
                                <div class="d-flex align-items-center gap-2">
                                    <i class="ri-smartphone-line text-info"></i>
                                    <span class="fw-medium text-body">
                                        {{ $telepon }}
                                    </span>
                                </div>
                            @endif

                        </div>

                    </div>

                    {{-- LABEL --}}
                    <div class="mb-3">
                        <h6 class="fw-semibold mb-1">
                            Pilih Ekstrakurikuler
                        </h6>

                        <p class="text-muted mb-0">
                            Pilih ekstrakurikuler yang akan diikuti siswa.
                        </p>
                    </div>

                    {{-- LIST EKSTRAKURIKULER --}}
                    <div class="row g-3">

                        @foreach ($select_ekstrakurikuler as $item)
                            <div class="col-lg-6">

                                <label
                                    for="ekskul_{{ $item->ms_ekstrakurikuler_id }}"
                                    class="w-100 mb-0">

                                    <div
                                        class="border rounded-4 p-3 h-100 cursor-pointer transition
                                        {{ $ms_ekstrakurikuler_id == $item->ms_ekstrakurikuler_id
                                            ? 'border-primary bg-primary-subtle shadow-sm'
                                            : 'border-light-subtle bg-light' }}">

                                        <div class="d-flex align-items-start gap-3">

                                            {{-- RADIO --}}
                                            <div class="form-check mt-1 flex-shrink-0">

                                                <input
                                                    class="form-check-input"
                                                    type="radio"
                                                    name="ms_ekstrakurikuler_id"
                                                    id="ekskul_{{ $item->ms_ekstrakurikuler_id }}"
                                                    wire:model="ms_ekstrakurikuler_id"
                                                    value="{{ $item->ms_ekstrakurikuler_id }}">

                                            </div>

                                            {{-- ICON --}}
                                            <div class="avatar-sm flex-shrink-0">
                                                <div
                                                    class="avatar-title rounded-circle
                                                    {{ $ms_ekstrakurikuler_id == $item->ms_ekstrakurikuler_id
                                                        ? 'bg-primary text-white'
                                                        : 'bg-info-subtle text-info' }}">

                                                    <i class="ri-trophy-line"></i>

                                                </div>
                                            </div>

                                            {{-- CONTENT --}}
                                            <div class="flex-grow-1 min-w-0">
                                                <div class="d-flex justify-content-between align-items-start gap-2">

                                                    <div>
                                                        <div class="fw-semibold fs-12 text-dark">
                                                            {{ $item->nama_ekstrakurikuler }}
                                                        </div>
                                                    </div>

                                                    <div class="text-end flex-shrink-0">
                                                        <div class="fs-12 fw-semibold text-dark">
                                                            Rp{{ number_format($item->biaya, 0, ',', '.') }}
                                                        </div>
                                                    </div>

                                                </div>

                                                {{-- DESKRIPSI --}}
                                                @if ($item->deskripsi)
                                                    <p class="text-muted fs-13 mt-2 mb-3"
                                                        title="{{ $item->deskripsi }}">
                                                        {{ \Illuminate\Support\Str::limit($item->deskripsi, 100) }}
                                                    </p>
                                                @endif

                                                {{-- INFORMASI --}}
                                                <div class="d-flex flex-wrap gap-3 text-muted fs-12">
                                                    <div class="d-flex align-items-center gap-1">
                                                        <i class="ri-group-line text-primary"></i>
                                                        <span>
                                                            {{ $item->total_peserta ?? 0 }} peserta
                                                        </span>
                                                    </div>

                                                    <div class="d-flex align-items-center gap-1">
                                                        <i class="ri-user-add-line text-success"></i>
                                                        <span>
                                                            Kuota {{ $item->kuota ?? 0 }}
                                                        </span>
                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </label>

                            </div>
                        @endforeach

                    </div>

                    @error('ms_ekstrakurikuler_id')
                        <div class="text-danger fs-13 mt-2">
                            {{ $message }}
                        </div>
                    @enderror

                </div>

                {{-- FOOTER --}}
                <div class="modal-footer border-0 pt-0 px-4 px-lg-5 pb-4">

                    <button type="button"
                        class="btn btn-light rounded-pill px-4"
                        data-bs-dismiss="modal">

                        <i class="ri-close-line me-1"></i>
                        Tutup

                    </button>

                    <button type="submit"
                        class="btn btn-primary rounded-pill px-4">

                        <i class="ri-save-3-line me-1"></i>
                        Simpan

                    </button>

                </div>

            </form>

        </div>
    </div>

</div>