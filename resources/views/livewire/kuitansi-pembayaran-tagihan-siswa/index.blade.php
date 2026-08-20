<div wire:ignore.self class="offcanvas offcanvas-top bg-light" id="kuitansiTransaksi" aria-labelledby="kuitansiTransaksiLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">
            <!-- Kiri -->
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-file-chart-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Format Kuitansi Pembayaran
                    </h5>
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
        <div class="row justify-content-center">
            <div class="col-xxl-4">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">
                            {{-- TITLE --}}
                            <div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                                            <i class="ri-file-paper-2-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            Kuitansi Pembayaran Siswa
                                        </h5>

                                        <small class="text-muted">
                                            Atur format dan isi kuitansi pembayaran.
                                        </small>
                                    </div>

                                </div>
                            </div>

                            {{-- ACTION --}}
                            <div class="d-flex gap-2 flex-wrap">
                                @if ($selectedJenjang)
                                    @if (!$kuitansi)
                                        <button
                                            type="button"
                                            class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#createKuitansiTransaksi"
                                            wire:click="$emit('createKuitansiTransaksi', {{ $selectedJenjang }})">

                                            <i class="ri-add-line"></i>
                                            <span>Buat Kuitansi</span>
                                        </button>

                                    @else
                                        <button
                                            type="button"
                                            class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editKuitansiTransaksi"
                                            wire:click="$emit('loadKuitansiTransaksi', {{ $kuitansi->ms_kuitansi_pembayaran_tagihan_siswa_id }}, {{ $selectedJenjang }})">

                                            <i class="ri-quill-pen-line"></i>
                                            <span>Edit Kuitansi</span>
                                        </button>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($kuitansi)
                    <div class="card-body p-4 bg-white p-1">
                        <!-- Header -->
                        <div class="text-center text-black mb-2" style="font-family: 'Times New Roman', Times, serif; font-size: 18pt;">
                            <img src="{{ Storage::url($kuitansi->logo) }}" alt="Logo" class="img-fluid" style="height: 80px;">
                            <p class="mt-2 mb-0">{{ $kuitansi->nama_institusi }}</p>
                            <p style="font-family: 'Times New Roman', Times, serif; font-size: 14pt;">
                                {{ $kuitansi->alamat }}<br>
                                {{ $kuitansi->kontak }}
                            </p>
                            <p class="mt-2 mb-0"><b>{{ $kuitansi->judul }}</b></p>
                            <p class="my-0"><b>Tunai/Online</b></p>
                        </div>
                        
                        <div style="font-family: 'Times New Roman', Times, serif; font-size: 14pt;" class="px-4 m-2 text-black">
                            <table cellpadding="0" class="m-2">
                                <tr>
                                    <td width="12%">Siswa</td>
                                    <td width="88%">: nama siswa sekolah jenjang</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>: nama kelas sekolah jenjang</td>
                                </tr>
                            </table> 
                            <table cellpadding="0" style="font-family: 'Times New Roman', Times, serif; font-size: 14pt; width: 95%;">
                                <thead>
                                    <tr style="background-color: #f0f0f0;">
                                        <td width="10%" style="text-align: center; border-bottom: 2px solid #000;">No</td>
                                        <td width="60%" style="text-align: left; border-bottom: 2px solid #000;">Transaksi</td>
                                        <td width="30%" style="text-align: right; border-bottom: 2px solid #000;">Nominal</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td class="text-center"> 1</td>
                                        <td>Uang Pondok Agustus</td>
                                        <td class="text-end">Rp10.000</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"> 2</td>
                                        <td>Uang Pondok Juli</td>
                                        <td class="text-end">Rp10.000</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center"> 3</td>
                                        <td>Uang Pondok Oktober</td>
                                        <td class="text-end">Rp10.000</td>
                                    </tr>
                                    <tr>
                                        <td class="text-center">4</td>
                                        <td>Daftar Ulang</td>
                                        <td class="text-end">Rp1.250.000</td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" style="border-top: 2px solid #000; font-weight: bold; text-align: right;"><b>Total</b></td>
                                        <td style="border-top: 2px solid #000; font-weight: bold; text-align: right;"><b>Rp1.280.000</b></td>
                                    </tr>
                                </tfoot>
                            </table> 
                             <!-- Catatan -->
                            <div class="my-4 px-2 pe-4" style="font-family: 'Times New Roman', Times, serif; font-size: 14pt;">
                                <p align='center'>{{ $kuitansi->pesan }}</p>
                                <p align='center'>{{ $kuitansi->tempat }}, {{ now()->format('d-m-Y') }}</p>
                            </div>

                            <div class="pt-4 px-2 text-center" style="font-family: 'Times New Roman', Times, serif; font-size: 14pt;">
                                <p class="mb-0">Nama Petugas S.Kom</p>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="card-body p4 bg-white mb-2 text-center">
                        <h3 class="p-4 text-black" style="font-family: 'Times New Roman', Times, serif; font-size: 12pt;">Template Kuitansi belum di atur untuk jenjang ini.</h3>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
