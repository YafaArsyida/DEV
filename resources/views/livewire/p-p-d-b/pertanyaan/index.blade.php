
<div>
    <div class="card">

        {{-- HEADER --}}
        <div class="card-header">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="avatar-sm">
                            <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                <i class="ri-questionnaire-line"></i>
                            </div>
                        </div>

                        <div>
                            <h5 class="fw-bold mb-1">
                                Pertanyaan PPDB
                            </h5>
                            <small class="text-muted">
                                Kelola pertanyaan kuisioner yang harus diisi calon siswa.
                            </small>
                        </div>
                    </div>
                </div>

                @if ($selectedJenjang && $selectedTahunAjar && $selectedPeriode)
                    <div>
                        <button
                            type="button"
                            class="btn btn-primary rounded-pill px-4"
                            data-bs-toggle="modal"
                            data-bs-target="#ModalAddPertanyaan"
                            wire:click="$emit(
                                'showCreatePertanyaan',
                                {{ $selectedPeriode }}
                            )"
                        >
                            <i class="ri-add-line me-1"></i>
                            Tambah Pertanyaan
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
                        untuk melihat pertanyaan PPDB.
                    </p>
                </div>

            @else

                {{-- FILTER --}}
                <div class="row g-3 mb-3">

                    {{-- PENCARIAN --}}
                    <div class="col-12 col-lg-9">
                        <label
                            for="searchDataPertanyaan"
                            class="form-label small text-muted text-uppercase fw-medium mb-2"
                        >
                            Pencarian
                        </label>

                        <div class="search-box">
                            <input
                                type="text"
                                id="searchDataPertanyaan"
                                class="form-control search"
                                wire:model.debounce.300ms="search"
                                placeholder="Cari pertanyaan atau nama periode..."
                            >

                            <i class="ri-search-line search-icon"></i>
                        </div>
                    </div>

                    {{-- FILTER PERIODE --}}
                    <div class="col-12 col-lg-3">
                        <label
                            for="filterPeriodePertanyaan"
                            class="form-label small text-muted text-uppercase fw-medium mb-2"
                        >
                            Periode PPDB
                        </label>

                        <select
                            id="filterPeriodePertanyaan"
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

                                <th class="text-center">
                                    Hapus
                                </th>

                                <th>
                                    Pertanyaan
                                </th>

                                <th>
                                    Jenis Jawaban
                                </th>

                                <th>
                                    Periode
                                </th>

                                <th class="text-center">
                                    Wajib Diisi
                                </th>

                                <th class="text-center">
                                    Status
                                </th>

                                <th class="text-center">
                                    Urutan
                                </th>

                                <th class="text-center">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($pertanyaans as $pertanyaan)
                                <tr wire:key="pertanyaan-{{ $pertanyaan->ppdb_pertanyaan_id }}">

                                    {{-- NOMOR --}}
                                    <td class="text-center">
                                        {{ $pertanyaans->firstItem() + $loop->index }}
                                    </td>

                                    {{-- HAPUS --}}
                                    <td class="text-center">
                                        <a
                                            href="#ModalDeletePertanyaan"
                                            data-bs-toggle="modal"
                                            class="text-danger d-inline-block remove-item-btn"
                                            wire:click.prevent="$emit(
                                                'confirmDeletePertanyaan',
                                                {{ $pertanyaan->ppdb_pertanyaan_id }}
                                            )"
                                            title="Hapus Pertanyaan"
                                        >
                                            <i class="ri-delete-bin-5-fill fs-14"></i>
                                        </a>
                                    </td>

                                    {{-- PERTANYAAN --}}
                                    <td style="min-width: 280px; max-width: 400px; white-space: normal;">
                                        <div class="fw-semibold">
                                            {{ $pertanyaan->pertanyaan }}
                                        </div>

                                        @if ($pertanyaan->jenis === 'pilihan')
                                            <small class="text-muted">
                                                {{ count($pertanyaan->opsi_jawaban ?? []) }}
                                                opsi jawaban
                                            </small>
                                        @endif
                                    </td>

                                    {{-- JENIS JAWABAN --}}
                                    <td>
                                        @switch($pertanyaan->jenis)
                                            @case('teks')
                                                Teks Singkat
                                                @break

                                            @case('textarea')
                                                Teks Panjang
                                                @break

                                            @case('pilihan')
                                                Pilihan
                                                @break

                                            @case('ya_tidak')
                                                Ya / Tidak
                                                @break

                                            @case('angka')
                                                Angka
                                                @break

                                            @default
                                                {{ ucfirst($pertanyaan->jenis) }}
                                        @endswitch
                                    </td>

                                    {{-- PERIODE --}}
                                    <td>
                                        <div class="fw-medium">
                                            {{ $pertanyaan->ppdb_periode?->nama_periode ?? '-' }}
                                        </div>

                                        <small class="text-muted">
                                            {{ $pertanyaan->ppdb_periode?->ms_jenjang?->nama_jenjang ?? '-' }}
                                        </small>
                                    </td>

                                    {{-- WAJIB DIISI --}}
                                    <td class="text-center">
                                        @if ($pertanyaan->wajib_diisi)
                                            <span class="badge bg-primary-subtle text-primary">
                                                Wajib
                                            </span>
                                        @else
                                            <span class="badge bg-light text-muted">
                                                Opsional
                                            </span>
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td class="text-center">
                                        @if (strtolower($pertanyaan->status) === 'aktif')
                                            <span class="badge bg-success-subtle text-success">
                                                Aktif
                                            </span>
                                        @else
                                            <span class="badge bg-secondary-subtle text-secondary">
                                                Tidak Aktif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- URUTAN --}}
                                    <td class="text-center">
                                        {{ $pertanyaan->urutan }}
                                    </td>

                                    {{-- AKSI --}}
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center gap-2">

                                            {{-- DETAIL --}}
                                            <a
                                                href="#ModalDetailPertanyaan"
                                                data-bs-toggle="modal"
                                                class="btn btn-soft-primary btn-sm rounded-pill px-3"
                                                title="Detail Pertanyaan"
                                                wire:click.prevent="$emit(
                                                    'loadDetailPertanyaan',
                                                    {{ $pertanyaan->ppdb_pertanyaan_id }}
                                                )"
                                            >
                                                <i class="ri-eye-line me-1"></i>
                                                Detail
                                            </a>

                                            {{-- EDIT --}}
                                            <a
                                                href="#ModalEditPertanyaan"
                                                data-bs-toggle="modal"
                                                class="btn btn-primary btn-sm rounded-pill px-3"
                                                title="Edit Pertanyaan"
                                                wire:click="$emit(
                                                    'loadDataPertanyaan',
                                                    {{ $pertanyaan->ppdb_pertanyaan_id }}
                                                )"
                                            >
                                                <i class="ri-edit-line me-1"></i>
                                                Edit
                                            </a>

                                        </div>
                                    </td>

                                </tr>
                            @empty

                                {{-- EMPTY STATE DATA --}}
                                <tr>
                                    <td colspan="9" class="text-center py-5">

                                        <div class="avatar-md mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                                <i class="ri-questionnaire-line"></i>
                                            </div>
                                        </div>

                                        <h6 class="fw-semibold mb-1">
                                            Belum Ada Pertanyaan
                                        </h6>

                                        <p class="text-muted mb-0">
                                            Belum ada pertanyaan kuisioner untuk
                                            jenjang dan tahun ajaran ini,
                                            atau data tidak ditemukan.
                                        </p>

                                        @if ($selectedPeriode)
                                            <small class="text-muted d-block mt-1">
                                                Periode:
                                                {{ $select_periode->firstWhere(
                                                    'ppdb_periode_id',
                                                    $selectedPeriode
                                                )?->nama_periode ?? '-' }}
                                            </small>
                                        @endif

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
                                {{ $pertanyaans->firstItem() ?? 0 }}
                            </span>
                            -
                            <span class="fw-semibold">
                                {{ $pertanyaans->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="fw-semibold">
                                {{ $pertanyaans->total() }}
                            </span>
                            data pertanyaan
                        </div>

                        <div>
                            {{ $pertanyaans->links() }}
                        </div>

                    </div>
                </div>

            @endif

        </div>
    </div>
</div>
