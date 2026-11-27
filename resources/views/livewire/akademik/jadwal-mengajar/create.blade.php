<div>
    <div wire:ignore.self class="modal fade" id="ModalAddJadwal" tabindex="-1"
        aria-labelledby="ModalAddJadwalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden d-flex flex-column">
                <div class="modal-header border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm flex-shrink-0">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-calendar-event-line"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="fw-bold mb-1" id="ModalAddJadwalLabel">Tambah Jadwal Mengajar</h5>
                            <small class="text-muted">Pilih penugasan guru lalu atur hari, jam, dan rentang tanggal.</small>
                        </div>
                    </div>
                    <button type="button" class="btn btn-light btn-icon rounded-circle ms-auto"
                        data-bs-dismiss="modal" aria-label="Tutup">
                        <i class="ri-close-line fs-18"></i>
                    </button>
                </div>

                <form class="d-flex flex-column overflow-hidden" wire:submit.prevent="save">
                    <div class="modal-body">
                        @error('context')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        @error('jadwal')
                            <div class="alert alert-danger">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="ri-error-warning-line fs-18 mt-1"></i>
                                    <div>{{ $message }}</div>
                                </div>
                            </div>
                        @enderror

                        @if ($contextIsValid)
                            <div class="alert alert-primary bg-primary-subtle border-0 rounded-3 mb-4">
                                <div class="d-flex align-items-start gap-2">
                                    <i class="ri-information-line fs-18 mt-1"></i>
                                    <div>
                                        <div class="fw-semibold">Penugasan sesuai parameter terpilih</div>
                                        <div class="small">Guru, kelas, dan mata pelajaran diambil dari data penugasan guru yang aktif.</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="create_jadwal_penugasan" class="form-label">Penugasan Guru <span class="text-danger">*</span></label>
                                <select id="create_jadwal_penugasan"
                                    class="form-select @error('ms_penugasan_guru_id') is-invalid @enderror"
                                    wire:model.defer="ms_penugasan_guru_id">
                                    <option value="">Pilih guru - kelas - mata pelajaran</option>
                                    @foreach ($assignments as $assignment)
                                        <option value="{{ $assignment->ms_penugasan_guru_id }}">
                                            {{ $assignment->ms_pegawai?->nama_pegawai ?? '-' }}
                                            - {{ $assignment->ms_kelas?->nama_kelas ?? '-' }}
                                            - {{ $assignment->ms_mata_pelajaran?->nama_mapel ?? '-' }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('ms_penugasan_guru_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                @if ($assignments->isEmpty())
                                    <div class="form-text text-warning">Belum ada penugasan guru aktif pada parameter akademik ini.</div>
                                @endif
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-4">
                                    <label for="create_jadwal_hari" class="form-label">Hari <span class="text-danger">*</span></label>
                                    <select id="create_jadwal_hari"
                                        class="form-select @error('hari') is-invalid @enderror"
                                        wire:model.defer="hari">
                                        @foreach ($hariOptions as $value => $label)
                                            <option value="{{ $value }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    @error('hari') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="create_jadwal_mulai" class="form-label">Jam Mulai <span class="text-danger">*</span></label>
                                    <input type="time" id="create_jadwal_mulai"
                                        class="form-control @error('jam_mulai') is-invalid @enderror"
                                        wire:model.defer="jam_mulai">
                                    @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="create_jadwal_selesai" class="form-label">Jam Selesai <span class="text-danger">*</span></label>
                                    <input type="time" id="create_jadwal_selesai"
                                        class="form-control @error('jam_selesai') is-invalid @enderror"
                                        wire:model.defer="jam_selesai">
                                    @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label for="create_jadwal_tanggal_mulai" class="form-label">Tanggal Mulai <span class="text-danger">*</span></label>
                                    <input type="date" id="create_jadwal_tanggal_mulai"
                                        class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                        wire:model.defer="tanggal_mulai">
                                    @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="create_jadwal_tanggal_selesai" class="form-label">Tanggal Selesai <span class="text-danger">*</span></label>
                                    <input type="date" id="create_jadwal_tanggal_selesai"
                                        class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                        wire:model.defer="tanggal_selesai">
                                    @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="create_jadwal_status" class="form-label">Status</label>
                                <select id="create_jadwal_status"
                                    class="form-select @error('status') is-invalid @enderror"
                                    wire:model.defer="status">
                                    <option value="AKTIF">Aktif</option>
                                    <option value="NONAKTIF">Nonaktif</option>
                                </select>
                                @error('status') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div>
                                <label for="create_jadwal_deskripsi" class="form-label">Keterangan <span class="text-muted fw-normal">(Opsional)</span></label>
                                <textarea id="create_jadwal_deskripsi" rows="3"
                                    class="form-control @error('deskripsi') is-invalid @enderror"
                                    wire:model.defer="deskripsi" placeholder="Keterangan tambahan"></textarea>
                                @error('deskripsi') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        @else
                            <div class="alert alert-warning mb-0">
                                Pilih jenjang, tahun ajar, dan periode akademik yang valid sebelum menambahkan jadwal.
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4"
                            wire:loading.attr="disabled" wire:target="save" {{ ! $contextIsValid ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="save"><i class="ri-save-3-line me-1"></i> Simpan Jadwal</span>
                            <span wire:loading wire:target="save"><span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
