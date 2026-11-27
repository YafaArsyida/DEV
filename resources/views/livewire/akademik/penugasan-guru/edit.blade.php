<div>
    <div wire:ignore.self class="modal fade" id="ModalEditPenugasanGuru" tabindex="-1"
        aria-labelledby="ModalEditPenugasanGuruLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden d-flex flex-column">
                <div class="modal-header border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="mdi mdi-account-edit-outline"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" id="ModalEditPenugasanGuruLabel">Ubah Penugasan Guru</h5>
                            <small class="text-muted">Perbarui pengampu, kelas, mapel, atau status penugasan.</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-light btn-icon rounded-circle ms-auto"
                        data-bs-dismiss="modal" aria-label="Tutup">
                        <i class="ri-close-line fs-18"></i>
                    </button>
                </div>

                <form class="d-flex flex-column overflow-hidden" wire:submit.prevent="updatePenugasanGuru">
                    <div class="modal-body">
                        @if ($contextIsValid)
                            <div class="mb-3">
                                <label for="edit_penugasan_guru" class="form-label">Guru Pengampu <span class="text-danger">*</span></label>
                                <select id="edit_penugasan_guru" class="form-select @error('ms_pegawai_id') is-invalid @enderror"
                                    wire:model.defer="ms_pegawai_id">
                                    <option value="">Pilih guru</option>
                                    @foreach ($teachers as $teacher)
                                        <option value="{{ $teacher->ms_pegawai_id }}">
                                            {{ $teacher->nama_pegawai }}{{ $teacher->nip ? ' - ' . $teacher->nip : '' }}{{ $teacher->status === 'Aktif' ? '' : ' (nonaktif; penugasan ini)' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ms_pegawai_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="edit_penugasan_kelas" class="form-label">Kelas <span class="text-danger">*</span></label>
                                    <select id="edit_penugasan_kelas" class="form-select @error('ms_kelas_id') is-invalid @enderror"
                                        wire:model.defer="ms_kelas_id">
                                        <option value="">Pilih kelas</option>
                                        @foreach ($classes as $class)
                                            <option value="{{ $class->ms_kelas_id }}">{{ $class->nama_kelas }}</option>
                                        @endforeach
                                    </select>
                                    @error('ms_kelas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="edit_penugasan_mapel" class="form-label">Mata Pelajaran <span class="text-muted fw-normal">(Opsional)</span></label>
                                    <select id="edit_penugasan_mapel" class="form-select @error('ms_mata_pelajaran_id') is-invalid @enderror"
                                        wire:model.defer="ms_mata_pelajaran_id">
                                        <option value="">Tanpa mata pelajaran</option>
                                        @foreach ($subjects as $subject)
                                            <option value="{{ $subject->ms_mata_pelajaran_id }}">
                                                {{ $subject->nama_mapel }}{{ $subject->kode_mapel ? ' (' . $subject->kode_mapel . ')' : '' }}{{ $subject->status === 'AKTIF' ? '' : ' (nonaktif; penugasan ini)' }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('ms_mata_pelajaran_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="edit_penugasan_tahun_ajar" class="form-label">Tahun Ajaran</label>
                                <input type="text" id="edit_penugasan_tahun_ajar" class="form-control"
                                    value="{{ $tahunAjar?->nama_tahun_ajar ?? '-' }}" readonly>
                                <small class="text-muted">Mengikuti filter tahun ajaran yang dipilih.</small>
                                @error('ms_tahun_ajar_id') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-3">
                                <label for="edit_penugasan_status" class="form-label">Status</label>
                                <select id="edit_penugasan_status" class="form-select @error('status') is-invalid @enderror"
                                    wire:model.defer="status">
                                    <option value="AKTIF">Aktif</option>
                                    <option value="NONAKTIF">Nonaktif</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="edit_penugasan_deskripsi" class="form-label">Keterangan <span class="text-muted fw-normal">(Opsional)</span></label>
                                <textarea id="edit_penugasan_deskripsi" rows="3"
                                    class="form-control @error('deskripsi') is-invalid @enderror"
                                    wire:model.defer="deskripsi" placeholder="Keterangan tambahan"></textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">Parameter akademik tidak valid. Tutup dan pilih ulang jenjang serta tahun ajar.</div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4"
                            wire:loading.attr="disabled" wire:target="updatePenugasanGuru" {{ !$contextIsValid ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="updatePenugasanGuru"><i class="ri-save-3-line me-1"></i> Simpan Perubahan</span>
                            <span wire:loading wire:target="updatePenugasanGuru"><span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
