<div class="tab-pane active" id="tabSiswaKelas" role="tabpanel">
    <div class="row">
        <div class="col-xxl-4 pe-1">
            <div class="card">
                <div class="card-body p-4">
                    <div class="d-flex">
                        <div class="flex-grow-1">
                            <h4>Transaksi Pendapatan</h4>
                            {{-- <div class="hstack gap-3 flex-wrap">
                                <div><a href="#" class="text-primary d-block">{{ $ms_penempatan_siswa_id }}-TemanSekolah</a></div>
                                <div class="vr"></div>
                                <div class="text-muted">EduCard : <span class="text-warning fw-medium">{{ $educard ?? 'Belum ada' }}</span></div>
                                <div class="vr"></div>
                                <div class="text-muted">Kelas : <span class="text-body fw-medium">{{ $nama_kelas ?? 'Belum ada' }}</span></div>
                                <div class="vr"></div>
                                <div class="text-muted">Telepon : <span class="text-body fw-medium">{{ $telepon ?? 'Tidak tersedia' }}</span></div>
                                <div class="vr"></div>
                                <div class="text-muted">Virtual Akun : <span class="text-body fw-medium">9888838383838</span></div>
                            </div> --}}
                        </div>
                    </div>
                    <div class="mt-2 text-muted">
                        <p>Silakan mencatat transaksi <b>pendapatan</b> berdasarkan akun pendapatan yang telah tersedia.</p>
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
                                        <p class="text-muted mb-1">Pendapatan :</p>
                                        <h5 class="mb-0">Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end col -->
                    </div>
                    <div class="row mt-4">        
                        <!-- Pilihan Akun Pendapatan -->
                        <div class="col-lg-12 mb-3">
                            <label for="kode_akun" class="form-label">Akun Pendapatan</label>
                            <select id="kode_akun" wire:model.defer="kode_akun" class="form-select">
                                <option value="402">Pendapatan Donasi</option>
                                <option value="404">Pendapatan BOS</option>
                                {{-- @foreach ($select_kelas as $item)    
                                <option value="{{ $item->kode_akun }}">{{ $item->nama_kelas }} | {{ $item->ms_jenjang->nama_jenjang }} | {{ $item->ms_tahun_ajar->nama_tahun_ajar }}</option>
                                @endforeach --}}
                            </select>
                            {{-- @error('kode_akun') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror --}}
                        </div>
                
                        <!-- Input Nominal Pendapatan -->
                        <div class="col-lg-6 mb-3">
                            <label for="nominal_pendapatan" class="form-label">Nominal Pendapatan</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="addon-wrapping">Rp</span>
                                <input type="number" id="nominal_pendapatan" class="form-control" placeholder="Masukkan nominal" wire:model.defer="nominal_pendapatan" aria-describedby="addon-wrapping">
                                {{-- @error('nominal_pendapatan') <span class="text-danger">{{ $message }}</span> @enderror --}}
                            </div>
                        </div>
                
                        <!-- Pilihan Akun Tujuan -->
                        <div class="col-lg-6 mb-3">
                            <label for="kode_penampungan" class="form-label">Akun Tujuan</label>
                            <select id="kode_penampungan" wire:model.defer="kode_penampungan" class="form-select">
                                <option value="kas_tunai">Kas Tunai</option>
                                <option value="rekening_bank">Rekening Bank</option>
                            </select>
                            {{-- @error('kode_penampungan') 
                                <footer class="text-danger mt-0">{{ $message }}</footer>
                            @enderror --}}
                        </div>
                        
                        <!-- Input Deskripsi Transaksi -->
                        <div class="col-lg-12 mb-3">
                            <label for="deskripsi_transaksi" class="form-label">Deskripsi Transaksi</label>
                            <input type="text" id="deskripsi_transaksi" class="form-control" wire:model.defer="deskripsi_transaksi" placeholder="Deskripsi transaksi (opsional)">
                            {{-- @error('deskripsi_transaksi') <span class="text-danger">{{ $message }}</span> @enderror --}}
                        </div>
                
                        <!-- Tombol Simpan -->
                        <div class="hstack gap-2 justify-content-end d-print-none">
                            <button wire:click="refreshTransaksiPendapatan" class="btn btn-success">
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
                <div class="card-body p-4">
                    <div class="row g-4 align-items-center mb-2">
                        <div class="col-sm-4">
                            <p class="text-muted mb-2 text-uppercase fw-semibold">
                                Data Pendapatan
                            </p>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-borderless table-hover text-center table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="table-active">
                                    <th style="width: 50px;" class="text-uppercase">NO</th>
                                    <th class="text-uppercase" scope="col" style="width: 50px;">hapus</th>
                                    <th class="text-start text-uppercase" scope="col" style="width: 150px;">tanggal</th>
                                    <th class="text-uppercase" scope="col">petugas</th>
                                    <th class="text-start text-uppercase" scope="col">Deskripsi transaksi</th>
                                    <th class="text-uppercase text-center" scope="col">pendapatan</th>
                                    <th class="text-uppercase text-center">Saldo</th>
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
                                        <a href="#ModalDeleteEduPay" data-bs-toggle="modal" class="btn btn-soft-danger d-inline-flex align-items-center gap-1" 
                                        wire:click.prevent="" data-bs-trigger="hover" data-bs-placement="top" title="Hapus Transaksi EduPay">
                                            <i class="ri-delete-bin-5-line align-bottom"></i>
                                        </a>
                                    </td>
                        
                                    <!-- Kolom tanggal transaksi -->
                                    <td class="text-start">{{ \Carbon\Carbon::parse($item->tanggal_transaksi)->format('d M Y') }}</td>
                        
                                    <!-- Kolom nama petugas -->
                                    <td>{{ $item->ms_pengguna->nama ?? 'Tidak Diketahui' }}</td>
                        
                                    <!-- Kolom deskripsi transaksi -->
                                    <td class="text-start">{{ $item->deskripsi ?? '-' }}</td>
                        
                                    <!-- Kolom nominal pendapatan -->
                                    <td class="text-center">
                                        <span class="fs-14 text-success">
                                        Rp {{ number_format($item->nominal, 0, ',', '.') }}
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
                                        <a href="#editTransaksiEduPay" data-bs-toggle="modal" wire:click.prevent=" " 
                                            class="btn btn-primary d-inline-flex align-items-center gap-1">
                                            <i class="ri-quill-pen-line align-bottom"></i>
                                            <span>Edit Transaksi</span>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">
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
    <!--end row-->
</div>