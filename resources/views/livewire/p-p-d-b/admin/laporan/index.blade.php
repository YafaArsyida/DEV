
<div class="card border-0 shadow-sm rounded-4 mb-4">

    {{-- HEADER --}}
    <div class="card-header bg-transparent border-bottom p-3 p-lg-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3">

            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-file-chart-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Laporan Penerimaan Siswa Baru
                        </h5>

                        <small class="text-muted">
                            Daftar calon siswa yang diterima serta pencetakan kartu aljabar dan formulir pendaftaran.
                        </small>
                    </div>
                </div>
            </div>

            {{-- INFO PERIODE --}}
            @if ($selectedJenjang && $selectedTahunAjar && $periode)
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">
                        <i class="ri-checkbox-circle-line me-1"></i>
                        Periode Aktif
                    </span>
                </div>
            @endif

        </div>
    </div>

    <div class="card-body">

        {{-- PARAMETER BELUM DIPILIH --}}
        @if (!$selectedJenjang || !$selectedTahunAjar)
            <div class="text-center py-4">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                        <i class="ri-filter-3-line"></i>
                    </div>
                </div>

                <h6 class="mb-1">Pilih Parameter Laporan</h6>

                <p class="text-muted mb-0">
                    Silakan pilih jenjang dan tahun ajaran terlebih dahulu
                    untuk menampilkan laporan penerimaan siswa baru.
                </p>
            </div>
        {{-- PERIODE TIDAK TERSEDIA --}}
        @elseif (!$periode)

            <div class="text-center py-4">
                <div class="avatar-md mx-auto mb-3">
                    <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-24">
                        <i class="ri-calendar-close-line"></i>
                    </div>
                </div>

                <h6 class="mb-1">Periode PPDB Tidak Tersedia</h6>

                <p class="text-muted mb-0">
                    Tidak ditemukan periode PPDB aktif untuk kombinasi
                    jenjang dan tahun ajaran yang dipilih.
                </p>
            </div>

        @else            
            {{-- PENCARIAN DAN FILTER JALUR --}}
            <div class="row g-3 mb-3">

                {{-- PENCARIAN --}}
                <div class="col-xxl-8 col-md-12">
                    <label
                        for="searchData"
                        class="form-label small text-muted text-uppercase fw-medium mb-2">
                        Pencarian
                    </label>

                    <div class="search-box">
                        <input
                            type="text"
                            id="searchData"
                            class="form-control search"
                            placeholder="Cari nama atau nomor pendaftaran..."
                            wire:model.debounce.400ms="search">

                        <i class="ri-search-line search-icon"></i>
                    </div>
                </div>

                {{-- FILTER JALUR --}}
                <div class="col-xxl-4 col-md-6">
                    <label
                        for="filterJalur"
                        class="form-label small text-muted text-uppercase fw-medium mb-2">
                        Jalur Pendaftaran
                    </label>

                    <select
                        id="filterJalur"
                        class="form-select"
                        wire:model="filterJalur">

                        <option value="">Semua Jalur</option>

                        @foreach ($daftarJalur as $jalur)
                            <option value="{{ $jalur->ppdb_jalur_id }}">
                                {{ $jalur->nama_jalur }}
                            </option>
                        @endforeach

                    </select>
                </div>

                {{-- RESET FILTER --}}
                {{-- @if ($search || $filterJalur !== '')
                    <div class="col-12">
                        <button
                            type="button"
                            class="btn btn-light rounded-pill px-3"
                            wire:click="resetFilter">

                            <i class="ri-refresh-line me-1"></i>
                            Reset Filter
                        </button>
                    </div>
                @endif --}}

            </div>
            {{-- END FILTER --}}

            {{-- LOADING --}}
            <div
                wire:loading.delay
                wire:target="search,filterJalur,gotoPage,nextPage,previousPage"
                class="text-muted small mb-3">

                <span class="spinner-border spinner-border-sm me-1"></span>
                Memuat laporan penerimaan...
            </div>

            {{-- TABEL --}}
            <div class="live-preview">
                <div class="table-responsive">
                    <table class="table table-hover table-nowrap align-middle">

                        <thead class="table-light">
                            <tr class="text-uppercase">
                                <th class="text-center" style="width: 60px;">
                                    No.
                                </th>
                                <th>Calon Siswa</th>
                                <th>Nomor Pendaftaran</th>
                                <th>Jalur</th>
                                <th>Status</th>
                                <th class="text-center" style="min-width: 280px;">
                                    Aksi
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($pendaftar as $item)

                                <tr wire:key="laporan-{{ $item->ppdb_pendaftaran_id }}">

                                    {{-- NOMOR --}}
                                    <td class="text-center text-muted">
                                        {{ $pendaftar->firstItem() + $loop->index }}
                                    </td>

                                    {{-- CALON SISWA --}}
                                    <td class="fw-medium">
                                        {{ $item->ppdb_calon_siswa->nama_siswa ?? 'Data siswa belum lengkap' }}
                                    </td>

                                    {{-- NOMOR PENDAFTARAN --}}
                                    <td>
                                        {{ $item->nomor_pendaftaran }}
                                    </td>

                                    {{-- JALUR --}}
                                    <td>
                                        {{ $item->ppdb_jalur->nama_jalur ?? '-' }}
                                    </td>

                                    {{-- STATUS PENDAFTARAN --}}
                                    <td class="text-nowrap text-success">
                                        <i class="ri-check-double-line fs-17 align-middle me-1"></i>
                                        Diterima
                                    </td>

                                    {{-- AKSI --}}
                                    <td>
                                        <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">

                                            {{-- CETAK KARTU ALJABAR --}}
                                            <a
                                                href="{{ route('ppdb.kartu-aljabar.pdf', [
                                                    'pendaftaranId' => $item->ppdb_pendaftaran_id,
                                                ]) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-outline-danger btn-sm rounded-pill px-3 text-nowrap"
                                                title="Cetak Kartu Aljabar"
                                            >
                                                <i class="ri-printer-line align-bottom me-1"></i>
                                                Kartu
                                            </a>

                                            {{-- CETAK FORMULIR PENDAFTARAN --}}
                                            <a
                                                href="{{ route('ppdb.formulir-pendaftaran.pdf', [
                                                    'pendaftaranId' => $item->ppdb_pendaftaran_id,
                                                ]) }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="btn btn-danger btn-sm rounded-pill px-3 text-nowrap"
                                                title="Cetak Formulir Pendaftaran">

                                                <i class="ri-printer-line me-1"></i>
                                                Formulir
                                            </a>
                                            {{-- Kontak WhatsApp --}}
                                            @php
                                                $orangTua = $item->ppdb_orangtua;

                                                $kontakWhatsApp = [
                                                    'Ayah' => $orangTua->telepon_ayah ?? null,
                                                    'Ibu' => $orangTua->telepon_ibu ?? null,
                                                    'Wali' => $orangTua->telepon_wali ?? null,
                                                ];

                                                $kontakTersedia = collect($kontakWhatsApp)
                                                    ->filter(fn ($nomor) => filled($nomor));
                                            @endphp
                                            <div class="dropdown">
                                                <button
                                                    type="button"
                                                    class="btn btn-soft-success btn-sm rounded-pill px-3 text-nowrap"
                                                    data-bs-toggle="dropdown"
                                                    aria-expanded="false"
                                                    title="Hubungi orang tua">
                                                    <i class="ri-whatsapp-line me-1"></i>
                                                    Hubungi
                                                    <i class="ri-arrow-down-s-line ms-1"></i>
                                                </button>

                                                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                                                    <li>
                                                        <h6 class="dropdown-header">Hubungi Orang Tua</h6>
                                                    </li>

                                                    @forelse ($kontakTersedia as $label => $nomor)
                                                        @php
                                                            $nomorBersih = preg_replace('/\D/', '', $nomor);

                                                            if (str_starts_with($nomorBersih, '0')) {
                                                                $nomorBersih = '62' . substr($nomorBersih, 1);
                                                            } elseif (str_starts_with($nomorBersih, '8')) {
                                                                $nomorBersih = '62' . $nomorBersih;
                                                            }
                                                        @endphp

                                                        @if ($nomorBersih)
                                                            <li>
                                                                <a
                                                                    href="https://wa.me/{{ $nomorBersih }}"
                                                                    target="_blank"
                                                                    rel="noopener noreferrer"
                                                                    class="dropdown-item py-2">
                                                                    <i class="ri-whatsapp-line text-success me-2"></i>
                                                                    WhatsApp {{ $label }}
                                                                </a>
                                                            </li>
                                                        @endif
                                                    @empty
                                                        <li>
                                                            <span class="dropdown-item-text text-muted small">
                                                                Nomor kontak belum tersedia.
                                                            </span>
                                                        </li>
                                                    @endforelse
                                                </ul>
                                            </div>

                                        </div>
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="text-center py-5">

                                        <div class="avatar-md mx-auto mb-3">
                                            <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                                <i class="ri-file-search-line"></i>
                                            </div>
                                        </div>

                                        <h6 class="mb-1">
                                            Data Penerimaan Belum Tersedia
                                        </h6>

                                        <p class="text-muted mb-3">
                                            @if (trim($search) !== '')
                                                Tidak ditemukan calon siswa diterima
                                                yang sesuai dengan pencarian.
                                            @else
                                                Belum ada calon siswa yang diterima
                                                pada periode PPDB ini.
                                            @endif
                                        </p>

                                        @if (trim($search) !== '')
                                            <button
                                                type="button"
                                                class="btn btn-sm btn-light rounded-pill px-3"
                                                wire:click="resetFilter">

                                                <i class="ri-refresh-line me-1"></i>
                                                Reset Pencarian
                                            </button>
                                        @endif

                                    </td>
                                </tr>

                            @endforelse
                        </tbody>

                    </table>
                    {{-- PAGINATION --}}
                    <div class="mt-3">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                            <div class="text-muted fs-13">
                                Menampilkan
                                <span class="fw-semibold">
                                    {{ $pendaftar->firstItem() ?? 0 }}
                                </span>
                                -
                                <span class="fw-semibold">
                                    {{ $pendaftar->lastItem() ?? 0 }}
                                </span>
                                dari
                                <span class="fw-semibold">
                                    {{ $pendaftar->total() }}
                                </span>
                                calon siswa diterima
                            </div>

                            <div>
                                {{ $pendaftar->links() }}
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
