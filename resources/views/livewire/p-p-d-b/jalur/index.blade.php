<div class="card">

    {{-- HEADER --}}
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-git-branch-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Jalur Pendaftaran
                        </h5>

                        <small class="text-muted">
                            Kelola jalur seleksi, kuota, dan biaya pendaftaran PPDB.
                        </small>
                    </div>
                </div>
            </div>

            @if ($selectedJenjang && $selectedTahunAjar && $selectedPeriode)
                <div class="d-flex gap-2 flex-wrap">
                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4"
                        data-bs-toggle="modal"
                        data-bs-target="#ModalAddJalur"
                        wire:click="$emit(
                            'showCreateJalur',
                            {{ $selectedPeriode }}
                        )"
                    >
                        <i class="ri-add-line me-1"></i>
                        Tambah Jalur
                    </button>
                </div>
            @endif

        </div>
    </div>

    {{-- BODY --}}
    <div class="card-body">

        @if (!$selectedJenjang || !$selectedTahunAjar)

            {{-- EMPTY STATE PARAMETER --}}
            <div class="text-center py-5">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                        <i class="ri-filter-3-line"></i>
                    </div>
                </div>

                <h6 class="fw-semibold mb-1">
                    Pilih Jenjang dan Tahun Ajaran
                </h6>

                <p class="text-muted mb-0">
                    Pilih jenjang dan tahun ajaran terlebih dahulu
                    untuk melihat jalur pendaftaran PPDB.
                </p>
            </div>

        @else

            {{-- FILTER --}}
            <div class="row g-3 mb-3">

                {{-- PENCARIAN --}}
                <div class="col-12 col-lg-9">
                    <label
                        for="searchDataJalur"
                        class="form-label small text-muted text-uppercase fw-medium mb-2"
                    >
                        Pencarian
                    </label>

                    <div class="search-box">
                        <input
                            type="text"
                            id="searchDataJalur"
                            class="form-control search"
                            wire:model.debounce.300ms="search"
                            placeholder="Cari kode jalur, nama jalur, atau periode..."
                        >

                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>

                {{-- FILTER PERIODE --}}
                <div class="col-12 col-lg-3">
                    <label
                        for="filterPeriodeJalur"
                        class="form-label small text-muted text-uppercase fw-medium mb-2"
                    >
                        Periode PPDB
                    </label>

                    <select
                        id="filterPeriodeJalur"
                        wire:model="selectedPeriode"
                        class="form-select"
                        style="cursor: pointer;"
                        title="Pilih Periode PPDB"
                    >
                        <option value="">Semua Periode</option>

                        @foreach ($select_periode as $periode)
                            <option value="{{ $periode->ppdb_periode_id }}">
                                {{ $periode->nama_periode }}
                            </option>
                        @endforeach
                    </select>
                </div>

            </div>

            {{-- TABLE --}}
            <div class="table-responsive">
                <table class="table table-hover table-nowrap align-middle">
                    <thead class="table-light">
                        <tr class="text-uppercase">
                            <th class="text-center" width="50">
                                No
                            </th>

                            <th class="text-center">hapus</th>
                            <th>
                                Kode Jalur
                            </th>

                            <th>
                                Nama Jalur
                            </th>

                            <th>
                                Periode
                            </th>

                            <th class="text-center">
                                Kuota
                            </th>

                            <th class="text-end">
                                Biaya Pendaftaran
                            </th>

                            <th class="text-center">
                                Status
                            </th>

                            <th class="text-center">
                                Aksi
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($jalurs as $jalur)
                            <tr wire:key="jalur-{{ $jalur->ppdb_jalur_id }}">

                                {{-- NOMOR --}}
                                <td class="text-center">
                                    {{ $jalurs->firstItem() + $loop->index }}
                                </td>

                                    {{-- HAPUS --}}
                                <td class="text-center">
                                    <a
                                        href="#ModalDeleteJalur"
                                        data-bs-toggle="modal"
                                        class="text-danger d-inline-block remove-item-btn"
                                        wire:click.prevent="$emit(
                                            'confirmDeleteJalur',
                                            {{ $jalur->ppdb_jalur_id }}
                                        )"
                                        data-bs-trigger="hover"
                                        data-bs-placement="top"
                                        title="Hapus Jalur"
                                    >
                                        <i class="ri-delete-bin-5-fill fs-14"></i>
                                    </a>
                                </td>

                                {{-- KODE --}}
                                <td>
                                    <span class="fw-semibold">
                                        {{ $jalur->kode_jalur }}
                                    </span>
                                </td>

                                {{-- NAMA JALUR --}}
                                <td>
                                    <div class="fw-semibold">
                                        {{ $jalur->nama_jalur }}
                                    </div>

                                    @if ($jalur->deskripsi)
                                        <small class="text-muted">
                                            {{ \Illuminate\Support\Str::limit(
                                                strip_tags($jalur->deskripsi),
                                                70
                                            ) }}
                                        </small>
                                    @endif
                                </td>

                                {{-- PERIODE --}}
                                <td>
                                    <div class="fw-medium">
                                        {{ $jalur->ppdb_periode?->nama_periode ?? '-' }}
                                    </div>

                                    <small class="text-muted">
                                        {{ $jalur->ppdb_periode?->ms_jenjang?->nama_jenjang ?? '-' }}
                                    </small>
                                </td>

                                {{-- KUOTA --}}
                                <td class="text-center">
                                    {{ number_format($jalur->kuota ?? 0, 0, ',', '.') }}
                                </td>

                                {{-- BIAYA --}}
                                <td class="text-end fw-medium fs-12">
                                    Rp{{ number_format(
                                        (float) $jalur->biaya_pendaftaran,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </td>

                                {{-- STATUS --}}
                                <td class="text-center">
                                    @if ($jalur->status === 'Aktif')
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
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        {{-- DETAIL --}}
                                        <a
                                            href="#ModalDetailJalur"
                                            data-bs-toggle="modal"
                                            class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                            title="Detail Jalur"
                                            wire:click.prevent="$emit(
                                                'loadDetailJalur',
                                                {{ $jalur->ppdb_jalur_id }}
                                            )"
                                        >
                                            <i class="ri-eye-line me-1"></i>
                                            Detail
                                        </a>

                                        {{-- EDIT --}}
                                        <a
                                            href="#ModalEditJalur"
                                            data-bs-toggle="modal"
                                            class="btn btn-primary btn-sm rounded-pill px-3"
                                            title="Edit Jalur"
                                            wire:click="$emit(
                                                'loadDataJalur',
                                                {{ $jalur->ppdb_jalur_id }}
                                            )"
                                        >
                                            <i class="ri-mark-pen-line me-1"></i>
                                            Edit
                                        </a>
                                    </div>
                                </td>

                            </tr>
                        @empty

                            {{-- EMPTY STATE DATA --}}
                            <tr>
                                <td colspan="8" class="text-center py-5">

                                    <div class="avatar-md mx-auto mb-3">
                                        <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                            <i class="ri-git-branch-line"></i>
                                        </div>
                                    </div>

                                    <h6 class="fw-semibold mb-1">
                                        Belum Ada Jalur Pendaftaran
                                    </h6>

                                    <p class="text-muted mb-0">
                                        Belum ada jalur pendaftaran untuk
                                        jenjang dan tahun ajaran ini,
                                        atau data tidak ditemukan.
                                    </p>

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
                            {{ $jalurs->firstItem() ?? 0 }}
                        </span>
                        -
                        <span class="fw-semibold">
                            {{ $jalurs->lastItem() ?? 0 }}
                        </span>
                        dari
                        <span class="fw-semibold">
                            {{ $jalurs->total() }}
                        </span>
                        data jalur
                    </div>

                    <div>
                        {{ $jalurs->links() }}
                    </div>

                </div>
            </div>

        @endif

    </div>
</div>