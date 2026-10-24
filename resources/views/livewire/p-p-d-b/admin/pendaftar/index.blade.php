<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

            {{-- TITLE --}}
            <div>
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-user-add-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Data Pendaftar PPDB
                        </h5>

                        <small class="text-muted">
                            Kelola data pendaftar, verifikasi berkas, dan bantu calon siswa melakukan pendaftaran.
                        </small>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            @if ($selectedJenjang && $selectedTahunAjar && $periode)
                <div class="d-flex gap-2 flex-wrap">

                    {{-- BANTU PENDAFTARAN --}}
                    <button
                        type="button"
                        class="btn btn-primary rounded-pill px-4"
                        data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasBantuPendaftaran"
                        aria-controls="offcanvasBantuPendaftaran"
                        wire:click="$emit('bukaBantuPendaftaran', {{ $periode->ppdb_periode_id }})">

                        <i class="ri-user-add-line me-1"></i>
                        Bantu Pendaftaran
                    </button>

                </div>
            @endif
        </div>
    </div>


    <div class="card-body">
        {{-- Filter --}}
        <div class="row g-3 mb-4">
            <div class="col-xl-6 col-md-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">Pencarian</label>

                <div class="search-box">
                    <input
                        type="search"
                        class="form-control search"
                        placeholder="Nama atau nomor pendaftaran"
                        wire:model.debounce.400ms="search">

                    <i class="mdi mdi-magnify search-icon"></i>
                </div>
            </div>

            <div class="col-xl-2 col-md-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">Sumber</label>
                <select class="form-select" wire:model="filterSumber">
                    <option value="">Semua Sumber</option>
                    <option value="online">Online</option>
                    <option value="dibantu_panitia">Dibantu Panitia</option>
                </select>
            </div>

            <div class="col-xl-2 col-md-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">Status</label>

                <select class="form-select" wire:model="filterStatus">
                    <option value="">Semua Status</option>
                    <option value="draft">Draft</option>
                    <option value="pengecekan">Pengecekan</option>
                    <option value="perbaikan">Perlu Perbaikan</option>
                    <option value="diterima">Diterima</option>
                </select>
            </div>

            <div class="col-xl-2 col-md-6">
                <label class="form-label small text-muted text-uppercase fw-medium mb-2">Jalur</label>

                <select class="form-select" wire:model="filterJalur">
                    <option value="">Semua Jalur</option>

                    @foreach ($daftarJalur as $jalur)
                        <option value="{{ $jalur->ppdb_jalur_id }}">
                            {{ $jalur->nama_jalur }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Indikator loading --}}
        <div wire:loading.delay wire:target="search,filterStatus,filterJalur,filterSumber,gotoPage,nextPage,previousPage"
            class="text-muted small mb-3">
            <span class="spinner-border spinner-border-sm me-1"></span>
            Memuat data pendaftar...
        </div>

        {{-- Tabel --}}
        <div class="table-responsive">
            <table class="table table-hover table-nowrap align-middle">
                <thead class="table-light">
                    <tr class="text-uppercase">
                        <th class="text-center" style="width: 60px;">No.</th>
                        <th>Calon Siswa</th>
                        <th>Nomor Pendaftaran</th>
                        <th>Jalur</th>
                        <th>Sumber</th>
                        <th>Akun</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($pendaftar as $item)
                        <tr wire:key="pendaftar-{{ $item->ppdb_pendaftaran_id }}">
                            <td class="text-center text-muted">
                                {{ $pendaftar->firstItem() + $loop->index }}
                            </td>

                            <td class="fw-medium">
                                {{ $item->ppdb_calon_siswa->nama_siswa ?? 'Data siswa belum lengkap' }}
                            </td>
                            <td>
                                {{ $item->nomor_pendaftaran }}
                            </td>
                            <td>
                                {{ $item->ppdb_jalur->nama_jalur ?? '-' }}
                            </td>

                            <td>
                                @if ($item->ms_pengguna?->peran === 'ORANG_TUA')
                                    Orang Tua
                                @elseif ($item->pembuat)
                                    Panitia
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                @if ($item->ms_pengguna)
                                    {{ $item->ms_pengguna->email ?? '-' }}
                                @elseif ($item->pembuat)
                                    {{ $item->pembuat->email ?? '-' }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->created_at, 'd F Y') }}
                            </td>

                           
                            <td class="text-nowrap
                                {{ $item->status === 'draft' ? 'text-secondary' : '' }}
                                {{ $item->status === 'pengecekan' ? 'text-warning' : '' }}
                                {{ $item->status === 'diterima' ? 'text-success' : '' }}
                                {{ $item->status === 'perbaikan' ? 'text-danger' : '' }}">

                                <i class="ri-{{
                                    $item->status === 'draft'
                                        ? 'draft-line'
                                        : ($item->status === 'pengecekan'
                                            ? 'time-line'
                                            : ($item->status === 'diterima'
                                                ? 'check-double-line'
                                                : 'edit-line'))
                                }} fs-17 align-middle me-1"></i>

                                {{ [
                                    'draft' => 'Draft',
                                    'pengecekan' => 'Pengecekan',
                                    'diterima' => 'Diterima',
                                    'perbaikan' => 'Perlu Perbaikan',
                                ][$item->status] ?? '-' }}
                            </td>
                            
                            <td>
                                <div class="d-flex justify-content-center align-items-center gap-2 flex-nowrap">

                                    {{-- Detail --}}
                                    <a
                                        href="#ModalDetailPendaftar"
                                        data-bs-toggle="modal"
                                        class="btn btn-soft-primary btn-sm rounded-pill px-3 text-nowrap"
                                        title="Detail Pendaftar"
                                        wire:click.prevent="$emit('loadDetailPendaftar', {{ $item->ppdb_pendaftaran_id }})">
                                        <i class="ri-eye-line me-1"></i>
                                        Detail
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

                                    {{-- RESET PASSWORD --}}
                                    @if ($item->ms_pengguna && $item->ms_pengguna->peran === 'ORANG_TUA')
                                        <button
                                            type="button"
                                            class="btn btn-soft-danger btn-sm rounded-pill px-3 text-nowrap"
                                            title="Reset Password"
                                            data-bs-toggle="modal"
                                            data-bs-target="#ModalKonfirmasiReset"
                                            wire:click="$emit(
                                                'resetPassword',
                                                {{ $item->ms_pengguna->ms_pengguna_id }}
                                            )">

                                            <i class="ri-lock-unlock-line me-1"></i>
                                            Reset
                                        </button>
                                    @endif

                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center py-5">
                                <div class="avatar-md mx-auto mb-3">
                                    <div class="avatar-title bg-light text-muted rounded-circle fs-24">
                                        <i class="ri-user-search-line"></i>
                                    </div>
                                </div>

                                <h6 class="mb-1">Pendaftar tidak ditemukan</h6>

                                <p class="text-muted mb-3">
                                    Tidak ada data yang sesuai dengan pencarian atau filter.
                                </p>

                                @if ($search || $filterStatus || $filterJalur || $filterSumber)
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-light"
                                        wire:click="resetFilter">
                                        Reset Filter
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
                        data pendaftar
                    </div>
                    <div>
                        {{ $pendaftar->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>