<div>
    <div class="card">
        {{-- HEADER --}}
        <div class="card-header">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                {{-- TITLE --}}
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-calendar-event-line"></i>
                            </div>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Periode Pendaftaran
                            </h5>

                            <small class="text-muted">
                                Kelola periode PPDB dan jadwal penerimaan siswa jenjang <strong>{{ $namaJenjang ?: 'Jenjang belum dipilih' }} · {{ $namaTahunAjar ?: 'Tahun ajaran belum dipilih' }} </strong>.
                            </small>
                        </div>
                    </div>
                </div>

                {{-- ACTION --}}
                @if ($selectedJenjang && $selectedTahunAjar)
                    <div class="d-flex gap-2 flex-wrap">
                        <button
                            type="button"
                            class="btn btn-primary rounded-pill px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalAddPeriode"
                            wire:click.prevent="$emit(
                                'showCreatePeriode',
                                {{ $selectedJenjang }},
                                {{ $selectedTahunAjar }}
                            )"
                        >
                            <i class="ri-add-line me-1"></i>
                            Tambah Periode
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- PENCARIAN --}}
        <div class="card-body px-0 pt-2 pb-3">
            <div class="row g-3 mb-3">
                <div class="col-xxl-12 col-sm-12">
                    <label
                        for="searchPeriode"
                        class="form-label small text-muted
                            text-uppercase fw-medium mb-2"
                    >
                        Pencarian
                    </label>

                    <div class="search-box">
                        <input
                            type="text"
                            id="searchPeriode"
                            class="form-control search"
                            wire:model.debounce.300ms="search"
                            placeholder="Cari nama periode, keterangan, atau lainnya..."
                        >

                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>
            </div>
            {{-- DATA --}}
            <div class="live-preview">
                @if (!$selectedJenjang || !$selectedTahunAjar)
                    <div class="text-center py-4">
                        <lord-icon
                            src="https://cdn.lordicon.com/msoeawqm.json"
                            trigger="loop"
                            colors="primary:#405189,secondary:#08a88a"
                            style="width:75px;height:75px"
                        >
                        </lord-icon>

                        <h5 class="mt-2">
                            Silakan Pilih Jenjang dan Tahun Ajar
                        </h5>

                        <p class="text-muted mb-0">
                            Untuk melihat data periode PPDB, harap pilih
                            Jenjang dan Tahun Ajar terlebih dahulu.
                        </p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover table-nowrap align-middle">
                            <thead class="table-light">
                                <tr class="text-uppercase">
                                    <th width="30px">No</th>
                                    <th class="text-center">hapus</th>
                                    <th>Nama Periode</th>
                                    <th>Jenjang</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Jadwal Pendaftaran</th>
                                    <th>Gelombang</th>
                                    <th>Status</th>
                                    <th class="text-end">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($periodes as $periode)
                                    <tr>
                                        {{-- NO --}}
                                        <td>
                                            {{ ($periodes->firstItem() ?? 0) + $loop->index }}.
                                        </td>

                                        {{-- HAPUS --}}
                                        <td class="text-center">
                                            <a
                                                href="#ModalDeletePeriode"
                                                data-bs-toggle="modal"
                                                class="text-danger d-inline-block remove-item-btn"
                                                wire:click.prevent="$emit(
                                                    'confirmDeletePeriode',
                                                    {{ $periode->ppdb_periode_id }}
                                                )"
                                                data-bs-trigger="hover"
                                                data-bs-placement="top"
                                                title="Hapus Periode"
                                            >
                                                <i class="ri-delete-bin-5-fill fs-14"></i>
                                            </a>
                                        </td>

                                        {{-- NAMA PERIODE --}}
                                        <td>
                                            <h6 class="mb-1 fw-semibold">
                                                {{ $periode->nama_periode }}
                                            </h6>

                                            <small class="text-muted">
                                                {{ $periode->deskripsi ?: 'Periode penerimaan siswa baru' }}
                                            </small>
                                        </td>

                                        {{-- JENJANG --}}
                                        <td>
                                            {{ $periode->ms_jenjang->nama_jenjang ?? 'Tidak diketahui' }}
                                        </td>

                                        {{-- TAHUN AJARAN --}}
                                        <td>
                                            {{ $periode->ms_tahun_ajar->nama_tahun_ajar ?? 'Tidak diketahui' }}
                                        </td>

                                        {{-- JADWAL PENDAFTARAN --}}
                                        <td>
                                            <div>
                                                {{ $periode->tanggal_mulai
                                                    ? \Carbon\Carbon::parse($periode->tanggal_mulai)->translatedFormat('d M Y')
                                                    : '-' }}
                                                –
                                                {{ $periode->tanggal_selesai
                                                    ? \Carbon\Carbon::parse($periode->tanggal_selesai)->translatedFormat('d M Y')
                                                    : '-' }}
                                            </div>

                                            @if ($periode->tanggal_mulai && $periode->tanggal_selesai)
                                                <small class="text-muted">
                                                    {{ \Carbon\Carbon::parse($periode->tanggal_mulai)
                                                        ->diffInDays(\Carbon\Carbon::parse($periode->tanggal_selesai)) + 1 }}
                                                    hari
                                                </small>
                                            @endif
                                        </td>

                                        {{-- JUMLAH GELOMBANG --}}
                                        <td>
                                            {{ $periode->ppdb_gelombang_count }} gelombang
                                        </td>

                                        {{-- STATUS --}}
                                        <td>
                                            @if ($periode->status === 'aktif')
                                                <span class="badge bg-success-subtle text-success">
                                                    Aktif
                                                </span>
                                            @else
                                                <span class="badge bg-secondary-subtle text-secondary">
                                                    Tidak Aktif
                                                </span>
                                            @endif
                                        </td>

                                        {{-- AKSI --}}
                                        <td>
                                            <div class="d-flex justify-content-center gap-2">
                                                {{-- DETAIL --}}
                                                <a href="#ModalDetailPeriode"
                                                    data-bs-toggle="modal"
                                                    class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                                    title="Detail Periode"
                                                    wire:click.prevent="$emit('loadDetailPeriode', {{ $periode->ppdb_periode_id }})">
                                                    <i class="ri-eye-line me-1"></i>
                                                    Detail
                                                </a>

                                                {{-- EDIT --}}
                                                <a href="#ModalEditPeriode"
                                                    data-bs-toggle="modal"
                                                    class="btn btn-primary btn-sm rounded-pill px-3"
                                                    title="Edit Periode"
                                                    wire:click="$emit('loadDataPeriode', {{ $periode->ppdb_periode_id }})">
                                                    <i class="ri-mark-pen-line me-1"></i>
                                                    Edit
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="noresult text-center py-4">
                                                <i class="ri-calendar-event-line fs-1 text-muted"></i>

                                                <h5 class="mt-2">
                                                    Belum Ada Periode Pendaftaran
                                                </h5>

                                                <p class="text-muted mb-0">
                                                    Tidak ditemukan periode PPDB yang sesuai
                                                    dengan pilihan atau pencarian.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div class="text-muted fs-13">
                                Menampilkan
                                <span class="fw-semibold">
                                    {{ $periodes->firstItem() ?? 0 }}
                                </span>
                                -
                                <span class="fw-semibold">
                                    {{ $periodes->lastItem() ?? 0 }}
                                </span>
                                dari
                                <span class="fw-semibold">
                                    {{ $periodes->total() }}
                                </span>
                                data periode
                            </div>

                            <div>
                                {{ $periodes->links() }}
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>