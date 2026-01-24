<div class="">
    <div class="row g-3 mb-3">
        <div class="col-xxl-8 col-sm-6">
            <div class="search-box">
                <input type="text" class="form-control search" 
                    wire:model.debounce.300ms="search" 
                    placeholder="cari kategori...">
                <i class="ri-search-line search-icon"></i>
            </div>
        </div>
        <div class="col-xxl-4 col-sm-6">
            <button type="button" 
                    class="btn btn-primary w-100"
                    data-bs-toggle="modal" 
                    data-bs-target="#ModalTambahKategoriKoperasi"
                    wire:click="$emit('showCreateKategoriKoperasi', {{ $selectedJenjang ?? 'null' }})">
                <i class="ri-add-fill me-1 align-bottom"></i> Kategori
            </button>
        </div>
    </div>

    <div class="col-xl-12">
        <div class="mt-4">
            <div class="live-preview">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover nowrap align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase text-center" width="50px">No</th>
                                <th class="text-uppercase">Kategori Produk</th>
                                <th class="text-uppercase">Status</th>
                                <th class="text-uppercase" width="160px">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($kategori as $index => $kat)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>
                                        <span class="fw-medium">
                                            {{ $kat->nama_kategori_produk_koperasi }}
                                        </span>
                                        <p class="text-muted mb-0">{{ $kat->deskripsi ?? '-' }}</p>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            {{ $kat->status_kategori_produk_koperasi === 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                            {{ ucfirst($kat->status_kategori_produk_koperasi) }}
                                        </span>
                                    </td>

                                    <td>
                                        <div class="hstack gap-2">

                                            {{-- Tombol Edit Kategori --}}
                                            <button class="btn btn-sm btn-primary d-inline-flex align-items-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalEditKategoriKoperasi"
                                                    title="Edit Kategori"
                                                    wire:click.prevent="$emit('loadDataKategoriKoperasi', {{ $kat->ms_kategori_produk_koperasi_id }})">
                                                <i class="ri-quill-pen-line align-bottom me-1"></i> Edit
                                            </button>

                                            {{-- Tombol Hapus Kategori --}}
                                            <button class="btn btn-sm btn-soft-danger d-inline-flex align-items-center"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#ModalDeleteKategoriKoperasi"
                                                    title="Hapus Kategori"
                                                    wire:click.prevent="$emit('confirmDeleteKategoriKoperasi', {{ $kat->ms_kategori_produk_koperasi_id }})">
                                                <i class="ri-delete-bin-5-line align-bottom me-1"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted">Tidak ada kategori koperasi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</div>