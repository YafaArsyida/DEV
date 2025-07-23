<div>
    {{-- If you look to others for fulfillment, you will never truly be fulfilled. --}}
    <div wire:ignore.self class="modal fade" id="editSiswaEkstrakurikuler" tabindex="-1" aria-labelledby="ModalAddSiswa" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-light p-3">
                    <h5 class="modal-title">edit data ekstrakurikuler siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <label class="form-label">Nama Siswa</label>
                                <p>{{ $nama_siswa }}</p>
                            </div>

                            
                            <div class="col-4">
                                <label class="form-label">Kelas</label>
                                <p>{{ $nama_kelas }}</p>
                            </div>

                            <div class="col-lg-4">
                                <label class="form-label">Tanggal Pendaftaran</label>
                                <p>{{ $created_at }}</p>
                            </div>

                           <div class="col-12">
                                <label class="form-label">Ekskul yang Diikuti</label>
                                <div class="d-flex flex-column gap-2">
                                    @foreach ($select_ekstrakurikuler as $item)
                                        <div class="d-flex justify-content-between align-items-center border rounded p-2 shadow-sm">
                                            <div class="form-check m-0">
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    id="edit_ekskul_{{ $item->ms_ekstrakurikuler_id }}"
                                                    wire:model="ms_ekstrakurikuler_id"
                                                    value="{{ $item->ms_ekstrakurikuler_id }}"
                                                    @if(in_array($item->ms_ekstrakurikuler_id, $selectedEkstrakurikuler)) checked @endif
                                                >
                                                <label class="form-check-label ms-2" for="edit_ekskul_{{ $item->ms_ekstrakurikuler_id }}">
                                                    {{ $item->nama_ekstrakurikuler }}
                                                </label>
                                            </div>
                                            <div class="text-success fw-medium fs-14">
                                                Rp{{ number_format($item->biaya, 0, ',', '.') }}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @error('ms_ekstrakurikuler_id') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <a href="javascript:void(0);" class="btn btn-link link-success shadow-none fw-medium" data-bs-dismiss="modal"><i class="ri-close-line me-1 align-middle"></i> Tutup</a>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
