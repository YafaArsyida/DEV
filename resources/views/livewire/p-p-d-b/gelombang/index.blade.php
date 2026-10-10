
<div>
    <div class="card">
        {{-- HEADER --}}
        <div class="card-header">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-calendar-check-line"></i>
                            </div>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Gelombang Pendaftaran
                            </h5>

                            <small class="text-muted">
                                Kelola jadwal, kuota, dan biaya setiap gelombang PPDB.
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
                            data-bs-target="#ModalAddGelombang"
                            wire:click="$emit(
                                'showCreateGelombang',
                                {{ $selectedPeriode }}
                            )"
                        >
                            <i class="ri-add-line me-1"></i>
                            Tambah Gelombang
                        </button>
                    </div>
                @endif
            </div>
        </div>

        {{-- BODY --}}
        <div class="card-body">
            @if (!$selectedJenjang || !$selectedTahunAjar)
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
                        Pilih jenjang dan tahun ajaran terlebih dahulu untuk melihat gelombang pendaftaran.
                    </p>
                </div>
            @else
                <div class="row g-3 mb-3">
                    {{-- PENCARIAN --}}
                    <div class="col-12 col-lg-9">
                        <label
                            for="searchData"
                            class="form-label small text-muted text-uppercase fw-medium mb-2"
                        >
                            Pencarian
                        </label>

                        <div class="search-box">
                            <input
                                type="text"
                                id="searchData"
                                class="form-control search"
                                wire:model.debounce.300ms="search"
                                placeholder="Cari nama gelombang atau periode..."
                            >

                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>

                    {{-- FILTER PERIODE --}}
                    <div class="col-12 col-lg-3">
                        <label
                            for="filterPeriode"
                            class="form-label small text-muted text-uppercase fw-medium mb-2"
                        >
                            Periode PPDB
                        </label>

                        <select
                            id="filterPeriode"
                            wire:model="selectedPeriode"
                            class="form-select"
                            style="cursor: pointer;"
                            title="Pilih Periode PPDB"
                        >
                            <option value="">Semua Periode</option>

                            @foreach ($select_periode as $item)
                                <option value="{{ $item->ppdb_periode_id }}">
                                    {{ $item->nama_periode }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- TABLE --}}
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr class="text-uppercase">
                                <th class="text-center" width="30px">No</th>
                                <th class="text-center">hapus</th>
                                <th>Nama Gelombang</th>
                                <th>Periode</th>
                                <th>Jadwal Pendaftaran</th>
                                <th class="text-center">Kuota</th>
                                <th class="text-end">Biaya Pendaftaran</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" width="150">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($gelombangs as $gelombang)
                                <tr>
                                    <td class="text-center">
                                        {{ $gelombangs->firstItem() + $loop->index }}
                                    </td>

                                    {{-- HAPUS --}}
                                    <td class="text-center">
                                        <a
                                            href="#ModalDeleteGelombang"
                                            data-bs-toggle="modal"
                                            class="text-danger d-inline-block remove-item-btn"
                                            wire:click.prevent="$emit(
                                                'confirmDeleteGelombang',
                                                {{ $gelombang->ppdb_gelombang_id }}
                                            )"
                                            data-bs-trigger="hover"
                                            data-bs-placement="top"
                                            title="Hapus Gelombang"
                                        >
                                            <i class="ri-delete-bin-5-fill fs-14"></i>
                                        </a>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">
                                            {{ $gelombang->nama_gelombang }}
                                        </div>
                                    </td>

                                    <td>
                                        <div class="fw-medium">
                                            {{ $gelombang->ppdb_periode?->nama_periode ?? '-' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $gelombang->ppdb_periode?->ms_jenjang?->nama_jenjang ?? '-' }}
                                        </small>
                                    </td>

                                    <td>
                                        <div>
                                            {{ $gelombang->tanggal_mulai
                                                ? \Carbon\Carbon::parse($gelombang->tanggal_mulai)->translatedFormat('d M Y')
                                                : '-' }}
                                            –
                                            {{ $gelombang->tanggal_selesai
                                                ? \Carbon\Carbon::parse($gelombang->tanggal_selesai)->translatedFormat('d M Y')
                                                : '-' }}
                                        </div>

                                        @if ($gelombang->tanggal_mulai && $gelombang->tanggal_selesai)
                                            <small class="text-muted">
                                                {{ \Carbon\Carbon::parse($gelombang->tanggal_mulai)
                                                    ->diffInDays(\Carbon\Carbon::parse($gelombang->tanggal_selesai)) + 1 }}
                                                hari
                                            </small>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        {{ number_format($gelombang->kuota ?? 0, 0, ',', '.') }}
                                    </td>

                                    <td class="text-end fw-medium fs-12">
                                        Rp{{ number_format((float) $gelombang->biaya_pendaftaran, 0, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        @if ($gelombang->status === 'aktif')
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
                                                href="#ModalDetailGelombang"
                                                data-bs-toggle="modal"
                                                class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                                title="Detail Gelombang"
                                                wire:click.prevent="$emit(
                                                    'loadDetailGelombang',
                                                    {{ $gelombang->ppdb_gelombang_id }}
                                                )"
                                            >
                                                <i class="ri-eye-line me-1"></i>
                                                Detail
                                            </a>

                                            {{-- EDIT --}}
                                            <a
                                                href="#ModalEditGelombang"
                                                data-bs-toggle="modal"
                                                class="btn btn-primary btn-sm rounded-pill px-3"
                                                title="Edit Gelombang"
                                                wire:click="$emit(
                                                    'loadDataGelombang',
                                                    {{ $gelombang->ppdb_gelombang_id }}
                                                )"
                                            >
                                                <i class="ri-mark-pen-line me-1"></i>
                                                Edit
                                            </a>

                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-5">
                                        <div class="avatar-md mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                                <i class="ri-calendar-check-line"></i>
                                            </div>
                                        </div>

                                        <h6 class="fw-semibold mb-1">
                                            Belum Ada Gelombang
                                        </h6>

                                        <p class="text-muted mb-0">
                                            Belum ada gelombang pendaftaran untuk jenjang dan tahun ajaran ini, atau data tidak ditemukan.
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
                                {{ $gelombangs->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $gelombangs->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $gelombangs->total() }}
                            </span>
                            data gelombang
                        </div>

                        <div>
                            {{ $gelombangs->links() }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>