<div wire:ignore.self style="min-width: 700px" class="offcanvas offcanvas-end bg-light" id="offcanvasHistori" aria-labelledby="offcanvasHistoriLabel">
    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">
            <!-- Kiri -->
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-file-list-3-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Riwayat Pembayaran
                    </h5>
                    <small class="text-muted">
                        {{ $namaSiswaCurrent ?? 'Siswa' }}
                    </small>
                </div>
            </div>
            <!-- Kanan -->
            <button type="button"
                class="btn btn-light btn-icon rounded-circle shadow-none"
                data-bs-dismiss="offcanvas">
                <i class="ri-close-line fs-18"></i>
            </button>
        </div>
    </div>
    <div class="offcanvas-body">
        <div class="row g-3 mb-3">
            <div class="col-12">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-body">
                        <div class="live-preview">
                            <!-- Jika Jenjang atau Tahun Ajar belum dipilih -->
                            @if (!$selectedJenjang || !$selectedTahunAjar)
                            <div class="text-center py-4">
                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                    colors="primary:#405189,secondary:#08a88a" style="width:75px;height:75px">
                                </lord-icon>
                                <h5 class="mt-2">Silakan Pilih Jenjang dan Tahun Ajar</h5>
                                <p class="text-muted mb-0">Untuk melihat data kelas, harap pilih Jenjang dan Tahun Ajar terlebih
                                    dahulu.</p>
                            </div>
                            @else
                            <div class="table-responsive">
                                <table class="table table-hover table-nowrap align-middle">
                                    <tbody>
                                    @forelse ($historis as $transaksi)
                                        @php
                                            $dibatalkan = $transaksi->status_transaksi === 'dibatalkan';
                                        @endphp

                                        {{-- HEADER TRANSAKSI --}}
                                        <tr class="{{ $dibatalkan ? 'bg-danger-subtle' : 'bg-light' }}">
                                            <td colspan="6">
                                                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">

                                                    {{-- KIRI (INFO) --}}
                                                    <div>
                                                        <div class="fw-bold {{ $dibatalkan ? 'text-danger text-decoration-line-through' : '' }}">
                                                            {{
                                                                \App\Http\Controllers\HelperController::formatTanggalIndonesia(
                                                                    $transaksi->tanggal_transaksi,
                                                                    'd F Y'
                                                                )
                                                            }}
                                                            oleh {{ $transaksi->ms_pengguna->nama }}

                                                            @if ($dibatalkan)
                                                                <span class="badge bg-danger ms-2">
                                                                    Dibatalkan
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <small class="{{ $dibatalkan ? 'text-danger text-decoration-line-through' : 'text-muted' }} d-block">
                                                            {{ $transaksi->deskripsi }}
                                                        </small>

                                                        @if ($transaksi->infaq > 0)
                                                            <div class="{{ $dibatalkan ? 'text-danger text-decoration-line-through' : 'text-success' }} small">
                                                                Infaq:
                                                                Rp{{ number_format($transaksi->infaq, 0, ',', '.') }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- KANAN (ACTION) --}}
                                                    <div class="d-flex align-items-center gap-2">

                                                        {{-- DETAIL --}}
                                                        <li
                                                            class="list-inline-item detail mb-0"
                                                            data-bs-toggle="tooltip"
                                                            data-bs-trigger="hover"
                                                            data-bs-placement="top"
                                                            title="Detail Transaksi"
                                                        >
                                                            <a
                                                                href="#detailTransaksiTagihan"
                                                                data-bs-toggle="modal"
                                                                wire:click.prevent="$emit(
                                                                    'loadDetailTransaksiTagihan',
                                                                    {{ $transaksi->ms_transaksi_tagihan_siswa_id }}
                                                                )"
                                                                class="btn btn-info btn-sm rounded-pill px-3"
                                                            >
                                                                <i class="ri-eye-line me-1"></i>
                                                                <span>Detail</span>
                                                            </a>
                                                        </li>

                                                        {{-- WHATSAPP --}}
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm rounded-pill px-3 {{ $dibatalkan ? 'btn-muted disabled' : 'btn-success' }}"
                                                            @if ($dibatalkan) disabled @endif
                                                            @if (!$dibatalkan)
                                                                wire:click="kirimWhatsapp({{ $transaksi->ms_transaksi_tagihan_siswa_id }})"
                                                            @endif
                                                        >
                                                            <i class="mdi mdi-whatsapp me-1"></i>
                                                            Pesan
                                                        </button>

                                                        {{-- PRINT --}}
                                                        <button
                                                            type="button"
                                                            class="btn btn-sm rounded-pill px-3 {{ $dibatalkan ? 'btn-muted disabled' : 'btn-danger' }}"
                                                            @if ($dibatalkan) disabled @endif
                                                            @if (!$dibatalkan)
                                                                wire:click="cetakTransaksi({{ $transaksi->ms_transaksi_tagihan_siswa_id }})"
                                                            @endif
                                                        >
                                                            <i class="ri-printer-line me-1"></i>
                                                            Cetak
                                                        </button>

                                                        {{-- MORE --}}
                                                        <div class="dropdown">
                                                            <button
                                                                type="button"
                                                                class="btn btn-sm rounded-pill btn-light"
                                                                data-bs-toggle="dropdown"
                                                                aria-expanded="false"
                                                            >
                                                                <i class="ri-more-2-fill fs-12"></i>
                                                            </button>

                                                            <ul class="dropdown-menu dropdown-menu-end">

                                                                @if (!$dibatalkan)

                                                                    {{-- EDIT --}}
                                                                    <li>
                                                                        <a
                                                                            href="#loadHistoriTransaksi"
                                                                            data-bs-toggle="modal"
                                                                            class="dropdown-item"
                                                                            wire:click.prevent="$emit(
                                                                                'loadHistoriTransaksi',
                                                                                {{ $transaksi->ms_transaksi_tagihan_siswa_id }}
                                                                            )"
                                                                        >
                                                                            <i class="ri-quill-pen-line me-2"></i>
                                                                            Edit
                                                                        </a>
                                                                    </li>

                                                                    <li>
                                                                        <hr class="dropdown-divider">
                                                                    </li>

                                                                    {{-- BATALKAN --}}
                                                                    <li>
                                                                        <a
                                                                            href="#ModalDeleteTransaksi"
                                                                            data-bs-toggle="modal"
                                                                            class="dropdown-item text-danger"
                                                                            wire:click.prevent="$emit(
                                                                                'loadTransaksiDelete',
                                                                                {{ $transaksi->ms_transaksi_tagihan_siswa_id }}
                                                                            )"
                                                                        >
                                                                            <i class="ri-delete-bin-5-line me-2"></i>
                                                                            Batalkan
                                                                        </a>
                                                                    </li>

                                                                @else

                                                                    {{-- TRANSAKSI DIBATALKAN --}}
                                                                    <li>
                                                                        <span class="dropdown-item-text text-danger">
                                                                            <i class="ri-close-circle-line me-2"></i>
                                                                            Transaksi sudah dibatalkan
                                                                        </span>
                                                                    </li>

                                                                @endif

                                                            </ul>
                                                        </div>

                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                        
                                        <!-- DETAIL -->
                                        @if ($transaksi->dt_transaksi_tagihan_siswa->isNotEmpty())
                                            <tr>
                                                <td colspan="6" class="p-0">
                                                    <div class="p-3">
                                                        <table class="table table-nowrap table-sm align-middle mb-0">
                                                            <thead class="{{ $dibatalkan ? 'table-danger' : 'table-light' }}">
                                                                <tr>
                                                                    <th class="text-center" width="5%">No</th>
                                                                    <th width="25%">Tagihan</th>
                                                                    <th width="30%">Metode</th>
                                                                    <th width="20%" class="text-end">Jumlah</th>
                                                                </tr>
                                                            </thead>

                                                            <tbody>
                                                                @foreach ($transaksi->dt_transaksi_tagihan_siswa as $detail)
                                                                    <tr class="{{ $dibatalkan ? 'text-muted text-decoration-line-through' : '' }}">
                                                                        <td class="text-center">
                                                                            {{ $loop->iteration }}
                                                                        </td>

                                                                        <td>
                                                                            {{
                                                                                $detail->ms_tagihan_siswa->ms_jenis_tagihan_siswa->nama_jenis_tagihan_siswa
                                                                            }}
                                                                        </td>

                                                                        <td>
                                                                            {{ $transaksi->metode_pembayaran }}
                                                                        </td>

                                                                        <td class="text-end fs-12 fw-medium">
                                                                            Rp{{ number_format($detail->jumlah_bayar, 0, ',', '.') }}
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                {{-- TOTAL --}}
                                                                <tr class="{{ $dibatalkan ? 'text-danger' : '' }}">

                                                                    <td colspan="3" class="text-end fs-12 fw-semibold">
                                                                        Total
                                                                    </td>

                                                                    <td class="text-end fs-12 fw-semibold">
                                                                        @if ($dibatalkan)
                                                                            {{-- Total transaksi asli --}}
                                                                            <span class="text-decoration-line-through">
                                                                                Rp{{ number_format(
                                                                                    $transaksi->dt_transaksi_tagihan_siswa->sum('jumlah_bayar'),
                                                                                    0,
                                                                                    ',',
                                                                                    '.'
                                                                                ) }}
                                                                            </span>
                                                                        @else
                                                                            Rp{{ number_format(
                                                                                $transaksi->total_jumlah_dibayarkan,
                                                                                0,
                                                                                ',',
                                                                                '.'
                                                                            ) }}

                                                                        @endif
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="6">
                                                <div class="text-center py-4">
                                                    <h5 class="mb-1">Tidak ada data</h5>
                                                    <small class="text-muted">Data transaksi belum tersedia</small>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>