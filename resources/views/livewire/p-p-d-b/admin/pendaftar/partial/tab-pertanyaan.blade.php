{{-- CARD: PERTANYAAN TAMBAHAN --}}
<div class="card border rounded-4 shadow-none">
    <div class="card-header bg-transparent">
        <div class="d-flex align-items-center gap-3">
            <div class="avatar-sm flex-shrink-0">
                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                    <i class="ri-questionnaire-line"></i>
                </div>
            </div>

            <div>
                <h6 class="mb-1 fw-semibold">
                    Informasi Tambahan
                </h6>

                <p class="text-muted small mb-0">
                    Lengkapi informasi tambahan yang ditetapkan
                    sekolah untuk periode pendaftaran ini.
                </p>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if (count($pertanyaan))
            <div class="row g-4">
                @foreach ($pertanyaan as $item)
                    <div
                        class="col-12"
                        wire:key="pertanyaan-create-{{ $item->ppdb_pertanyaan_id }}"
                    >
                        <label
                            for="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                            class="form-label fw-medium"
                        >
                            {{ $item->pertanyaan }}

                            @if ($item->wajib_diisi)
                                <span class="text-danger">*</span>
                            @else
                                <span class="text-muted small">
                                    (Opsional)
                                </span>
                            @endif
                        </label>

                        @switch($item->jenis)
                            @case('teks')
                                <input
                                    type="text"
                                    id="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                    class="form-control rounded-3 @error('jawaban.' . $item->ppdb_pertanyaan_id) is-invalid @enderror"
                                    wire:model.defer="jawaban.{{ $item->ppdb_pertanyaan_id }}"
                                    placeholder="Masukkan jawaban"
                                    maxlength="255"
                                >
                                @break

                            @case('textarea')
                                <textarea
                                    id="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                    class="form-control rounded-3 @error('jawaban.' . $item->ppdb_pertanyaan_id) is-invalid @enderror"
                                    wire:model.defer="jawaban.{{ $item->ppdb_pertanyaan_id }}"
                                    rows="3"
                                    maxlength="5000"
                                    placeholder="Tuliskan jawaban Anda"
                                ></textarea>
                                @break

                            @case('angka')
                                <input
                                    type="number"
                                    id="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                    class="form-control rounded-3 @error('jawaban.' . $item->ppdb_pertanyaan_id) is-invalid @enderror"
                                    wire:model.defer="jawaban.{{ $item->ppdb_pertanyaan_id }}"
                                    placeholder="Masukkan angka"
                                    step="any"
                                >
                                @break

                            @case('pilihan')
                                <select
                                    id="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                    class="form-select rounded-3 @error('jawaban.' . $item->ppdb_pertanyaan_id) is-invalid @enderror"
                                    wire:model.defer="jawaban.{{ $item->ppdb_pertanyaan_id }}"
                                >
                                    <option value="">Pilih jawaban</option>

                                    @foreach ($item->opsi_jawaban as $opsi)
                                        <option value="{{ $opsi }}">
                                            {{ $opsi }}
                                        </option>
                                    @endforeach
                                </select>
                                @break

                            @case('ya_tidak')
                                <div class="d-flex flex-wrap gap-4 pt-1">
                                    @foreach (['ya' => 'Ya', 'tidak' => 'Tidak'] as $nilai => $label)
                                        <div class="form-check">
                                            <input
                                                class="form-check-input"
                                                type="radio"
                                                id="jawaban-{{ $item->ppdb_pertanyaan_id }}-{{ $nilai }}"
                                                name="jawaban-{{ $item->ppdb_pertanyaan_id }}"
                                                value="{{ $nilai }}"
                                                wire:model.defer="jawaban.{{ $item->ppdb_pertanyaan_id }}"
                                            >

                                            <label
                                                class="form-check-label"
                                                for="jawaban-{{ $item->ppdb_pertanyaan_id }}-{{ $nilai }}"
                                            >
                                                {{ $label }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                                @break

                            @default
                                <div class="text-muted small">
                                    Jenis pertanyaan belum didukung.
                                </div>
                        @endswitch

                        @error('jawaban.' . $item->ppdb_pertanyaan_id)
                            <div class="invalid-feedback d-block">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5">
                <div class="avatar-lg mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-1">
                        <i class="ri-question-answer-line"></i>
                    </div>
                </div>

                <h6 class="fw-bold mb-2">
                    Belum Ada Pertanyaan Tambahan
                </h6>

                <p class="text-muted mb-0">
                    Tidak ada pertanyaan tambahan aktif
                    untuk periode PPDB ini.
                </p>
            </div>
        @endif

        {{-- PERNYATAAN KEBENARAN DATA --}}
        <div class="border-top border-dashed my-4"></div>

        <div class="form-check">
            <input
                type="checkbox"
                id="pernyataan-kebenaran"
                class="form-check-input @error('pernyataan') is-invalid @enderror"
                wire:model.defer="pernyataan"
            >

            <label
                class="form-check-label"
                for="pernyataan-kebenaran"
            >
                Orang tua/wali menyatakan bahwa seluruh data dan jawaban dalam formulir pendaftaran ini telah sesuai dengan informasi yang diberikan dan dapat dipertanggungjawabkan.
            </label>

            @error('pernyataan')
                <div class="invalid-feedback d-block">
                    {{ $message }}
                </div>
            @enderror
        </div>
    </div>
</div>