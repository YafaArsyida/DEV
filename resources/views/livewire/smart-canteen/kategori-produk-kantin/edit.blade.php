<div wire:ignore.self class="modal fade" id="ModalEditKategori" tabindex="-1" aria-labelledby="ModalEditKategoriLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-calendar-event-line">
                            </i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-1">
                            Perbarui Kategori
                        </h5>
                        {{-- <small>
                            Tambahkan agenda kegiatan generasi penerus dengan pengaturan tingkat, jadwal, dan lokasi.
                        </small> --}}
                    </div>
                </div>
                <button type="button" class="btn btn-light btn-icon rounded-circle" data-bs-dismiss="modal">
                    <i class="ri-close-line fs-18"></i>
                </button>    
            </div>

            <form wire:submit.prevent="update">
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Nama kategori -->
                        <div class="col-lg-8">
                            <label for="nama_kategori_produk_kantin" class="form-label">Nama Kategori</label>
                            <input type="text" wire:model.defer="nama_kategori_produk_kantin" id="nama_kategori_produk_kantin"
                                   class="form-control" placeholder="Makanan, Minuman, Snack..." />
                            @error('nama_kategori_produk_kantin')
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>

                        <!-- Pilih Icon -->
                        <div class="col-lg-4">
                            <label class="form-label">Pilih Icon</label>
                            <div class="dropdown">
                                <button class="btn dropdown-toggle border shadow-none w-100 d-flex align-items-center justify-content-between"
                                        type="button" data-bs-toggle="dropdown">
                                    <div class="d-flex align-items-center">
                                        <i class="{{ $icon ?: 'mdi mdi-shape-outline' }} me-2 fs-5"></i> 
                                        <span>{{ $icon ? ucfirst(str_replace(['mdi mdi-', '-'], ['',' '], $icon)) : 'Pilih Icon' }}</span>
                                    </div>
                                </button>
                                <ul class="dropdown-menu w-100 border shadow-sm" style="--bs-dropdown-link-hover-bg: transparent;">
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)" 
                                        wire:click="$set('icon','mdi mdi-food')">
                                            <i class="mdi mdi-food me-2 fs-5"></i> Makanan Umum
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)" 
                                        wire:click="$set('icon','mdi mdi-hamburger')">
                                            <i class="mdi mdi-hamburger me-2 fs-5"></i> Burger
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)" 
                                        wire:click="$set('icon','mdi mdi-pizza')">
                                            <i class="mdi mdi-pizza me-2 fs-5"></i> Pizza
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)" 
                                        wire:click="$set('icon','mdi mdi-bread-slice')">
                                            <i class="mdi mdi-bread-slice me-2 fs-5"></i> Roti / Snack
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)" 
                                        wire:click="$set('icon','mdi mdi-noodles')">
                                            <i class="mdi mdi-noodles me-2 fs-5"></i> Mie / Cepat Saji
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)" 
                                        wire:click="$set('icon','mdi mdi-food-steak')">
                                            <i class="mdi mdi-food-steak me-2 fs-5"></i> Siomay / Bakso
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)" 
                                        wire:click="$set('icon','mdi mdi-bottle-soda')">
                                            <i class="mdi mdi-bottle-soda me-2 fs-5"></i> Minuman Botol
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)" 
                                        wire:click="$set('icon','mdi mdi-cup-water')">
                                            <i class="mdi mdi-cup-water me-2 fs-5"></i> Minuman Gelas
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)" 
                                        wire:click="$set('icon','mdi mdi-ice-cream')">
                                            <i class="mdi mdi-ice-cream me-2 fs-5"></i> Es Krim
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center" href="javascript:void(0)" 
                                        wire:click="$set('icon','mdi mdi-cookie')">
                                            <i class="mdi mdi-cookie me-2 fs-5"></i> Snack Manis
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-lg-12">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <input type="text" wire:model.defer="deskripsi" id="deskripsi" class="form-control"
                                   placeholder="Keterangan singkat kategori..." />
                            @error('deskripsi')
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer border-0 px-4 pb-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">
                        <i class="ri-close-line me-1"></i>
                        Tutup
                    </button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="ri-save-3-line me-1"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
