<div class="tab-pane active" id="tabSiswaKelas" role="tabpanel">
    <div class="row">
        <div class="col-xxl-4 pe-1">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h4>Transaksi Pendapatan Lainnya</h4>
                        </div>
                    </div>
                    <div class="mt-2 text-muted">
                        <p>Silakan mencatat transaksi pendapatan berdasarkan akun pendapatan yang telah tersedia.</p>
                    </div>
                    <div class="row mt-4">
                        <div class="col-lg-12 col-sm-12">
                            <div class="p-2 border border-dashed rounded">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm me-2">
                                        <div class="avatar-title rounded bg-transparent text-info fs-24">
                                            <i class="ri-inbox-archive-fill"></i>
                                        </div>
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-1">Pendapatan Lainnya :</p>
                                        <h5 class="mb-0">RP{{ number_format($totalPendapatanLainnya, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <div class="row mt-4">        
                        <!-- Pilihan Akun -->
                        <div class="col-lg-12 mb-3">
                            <label for="kode_rekening" class="form-label">Jenis Transaksi Pendapatan</label>
                            <select wire:model="kode_rekening" style="cursor: pointer" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Transaksi">
                                <option value="">Pilih Transaksi</option>
                                @foreach ($select_transaksi as $item)    
                                <option value="{{ $item->kode_rekening }}">{{ $item->nama_rekening }}</option>
                                @endforeach
                            </select>
                            @error('kode_rekening') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                
                        <!-- Input Nominal -->
                        <div class="col-lg-6 mb-3">
                            <label for="nominal" class="form-label">Nominal</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">RP</span>
                                <input type="number" id="nominal" class="form-control" placeholder="Masukkan nominal, minimal RP 1.000" wire:model.defer="nominal" aria-describedby="addon-wrapping">
                                @error('nominal') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                
                        <!-- Pilihan Akun Tujuan -->
                        <div class="col-lg-6 mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Penerimaan</label>
                            <select id="metode_pembayaran" wire:model.defer="metode_pembayaran" class="form-select">
                                <option value="tunai">Kas Tunai</option>
                                <option value="bank">Transfer ke Rekening Sekolah</option>
                            </select>
                            @error('metode_pembayaran') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror
                        </div>
                
                        <!-- Tombol Simpan -->
                        <div class="hstack gap-2 justify-content-end d-print-none mt-4">
                            <input type="text" id="deskripsi" class="form-control" wire:model.defer="deskripsi" placeholder="Deskripsi transaksi (opsional)">
                            @error('deskripsi') <span class="text-danger">{{ $message }}</span> @enderror
                            <button wire:click="simpanTransaksi" class="btn btn-success">
                                <i class="ri-save-line align-bottom me-1"></i> Simpan
                            </button>
                        </div>
                    </div>                    
                </div>                
            </div><!-- end card -->
        </div>
        <!--end col-->
        <div class="col-xxl-8 ps-0">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">Data Transaksi Pendapatan Lainnya</h5>
                        @if ($selectedJenjang && $selectedTahunAjar)
                        <div class="flex-shrink-0">
                            <div class="d-flex gap-2 flex-wrap">
                                <button wire:click="cetakLaporan" class="btn btn-danger d-inline-flex align-items-center gap-1">
                                    <i class="ri-printer-line align-bottom"></i>
                                    <span>Cetak Laporan</span>
                                </button>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-end mb-3">
                        <!-- Dropdown Transaksi -->
                        <div class="col-xxl-2 col-md-6">
                            <label for="selectRekening" class="form-label">Jenis Transaksi</label>
                            <select id="selectRekening" wire:model="selectedRekening" class="form-select" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Pilih Jenis Transaksi">
                                <option value="">Semua Transaksi</option>
                                @foreach ($select_transaksi as $item)
                                    <option value="{{ $item->kode_rekening }}">{{ $item->nama_rekening }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Input Pencarian -->
                        <div class="col-xxl-6 col-md-6">
                            <label for="searchInput" class="form-label">Pencarian</label>
                            <div class="position-relative">
                                <input type="text" id="searchInput" class="form-control ps-4" wire:model.debounce.300ms="search" placeholder="Cari nama, deskripsi, atau lainnya...">
                                <i class="ri-search-line position-absolute top-50 start-0 translate-middle-y ms-2 text-muted"></i>
                            </div>
                        </div>

                        <!-- Filter Periode -->
                        <div class="col-xxl-4">
                            <div class="row">
                                <div class="col-12">
                                    <label class="form-label">Periode</label>
                                </div>
                                <div class="col-md-6">
                                    <input type="date" id="startDate" class="form-control" wire:model="startDate" placeholder="Mulai">
                                </div>
                                <div class="col-md-6">
                                    <input type="date" id="endDate" class="form-control" wire:model="endDate" placeholder="Sampai">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="live-preview">
                        <div class="table-responsive">
                            <table class="table table-borderless table-hover text-center table-nowrap align-middle mb-0">
                                <thead class="table-light">
                                    <tr class="table-active">
                                        <th style="width: 50px;" class="text-uppercase">NO</th>
                                        <th class="text-uppercase" scope="col" style="width: 50px;">hapus</th>
                                        <th class="text-start text-uppercase" scope="col" style="width: 150px;">tanggal</th>
                                        <th class="text-start text-uppercase" scope="col">transaksi</th>
                                        <th class="text-uppercase text-center" scope="col">petugas</th>
                                        <th class="text-uppercase text-center" scope="col">nominal</th>
                                        <th class="text-uppercase text-center">total</th>
                                        <th class="text-start text-uppercase">aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    $saldo = 0;
                                @endphp
                                @forelse ($data as $item)
                                    <tr>
                                        <!-- Kolom nomor urut -->
                                        <td style="width: 50px">{{ $loop->iteration }}.</td>
                                        <!-- Kolom hapus -->
                                        <td>
                                            <a href="#deletePendapatanLainnya" data-bs-toggle="modal" class="btn btn-sm btn-soft-danger d-inline-flex align-items-center gap-1" 
                                            wire:click.prevent="$emit('confirmDeletePendapatanLainnya', {{ $item->ms_pendapatan_lainnya_id }})" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Transaksi">
                                                <i class="ri-delete-bin-5-line align-bottom"></i>
                                            </a>
                                        </td>
                            
                                        <!-- Kolom tanggal transaksi -->
                                        <td class="text-uppercase text-start">
                                            {{ \App\Http\Controllers\HelperController::formatTanggalIndonesia($item->tanggal) }}
                                        </td>
                                        <td class="text-start">
                                            <span class="fs-14">
                                                {!! 'RP' . number_format($item->nominal, 0, ',', '.') . ' - <i>' . ucfirst($item->akuntansi_rekening->nama_rekening) . '</i>' !!}
                                            </span>
                                            <p class="text-muted mb-0">{{ $item->deskripsi ?? '' }}</p>
                                        </td>
                                        <td>
                                            <span class="fs-14">
                                                {{ $item->metode_pembayaran }}
                                            </span>
                                            <p class="text-muted mb-0">{{ $item->ms_pengguna->nama ?? 'Tidak Diketahui' }}</p>
                                        </td>
                                        <!-- Kolom nominal pendapatan -->
                                        <td class="text-center">
                                            <span class="fs-14 text-success">
                                            RP{{ number_format($item->nominal, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            @php
                                                // Perhitungan saldo
                                                $saldo += $item->nominal; // Tambahkan saldo
                                            @endphp
                                            <span class="fs-14 text-info">
                                                RP{{ number_format($saldo, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <!-- Kolom aksi -->
                                        <td class="text-start">
                                            <a href="#editPendapatanLainnya" data-bs-toggle="modal" wire:click.prevent="$emit('editPendapatanLainnya', {{ $item->ms_pendapatan_lainnya_id }})" 
                                                class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1">
                                                <i class="ri-quill-pen-line align-bottom"></i>
                                                <span>Edit Transaksi</span>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8">
                                            <div class="noresult text-center py-3">
                                                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                    colors="primary:#405189,secondary:#08a88a"
                                                    style="width:75px;height:75px">
                                                </lord-icon>
                                                <h5 class="mt-2">Maaf, Tidak Ada Data yang Ditemukan</h5>
                                                <p class="text-muted mb-0">Kami telah mencari keseluruhan data, namun tidak ditemukan hasil yang sesuai.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table><!--end table-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</div>