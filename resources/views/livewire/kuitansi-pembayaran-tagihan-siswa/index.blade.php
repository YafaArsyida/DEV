<div wire:ignore.self class="offcanvas offcanvas-top" id="kuitansiTransaksi" aria-labelledby="kuitansiTransaksiLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="kuitansiTransaksiLabel">Format Kuitansi Pembayaran</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row justify-content-center">
            <div class="col-xxl-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0">Kuitansi Pembayaran Siswa</h4>   
                        <div class="ms-auto"> <!-- Menambahkan ms-auto untuk mendorong ke kanan -->
                            <div class="dropdown">
                                @if ($selectedJenjang)
                                    @if (!$kuitansi)
                                        <a href="#createKuitansiTransaksi" data-bs-toggle="modal" class="btn btn-ghost-secondary btn-icon shadow-none" wire:click="$emit('createKuitansiTransaksi', {{ $selectedJenjang }})">
                                            <i class="ri-settings-5-line fs-20" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Setting Kuitansi"></i>
                                        </a>
                                    @else
                                    <a href="#editKuitansiTransaksi" data-bs-toggle="modal" class="btn btn-ghost-secondary btn-icon shadow-none" 
                                    wire:click="$emit('loadKuitansiTransaksi', {{ $kuitansi->ms_kuitansi_pembayaran_id }}, {{ $selectedJenjang }})">
                                        <i class="ri-quill-pen-line fs-20" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Kuitansi"></i>
                                    </a>
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
                                <p align='justify'>{{ $kuitansi->pesan }}</p>
                                <p>{{ $kuitansi->tempat }}, 26-05-2025</p>
                            </div>

                            <div class="pt-4 px-2" style="font-family: 'Times New Roman', Times, serif; font-size: 14pt;">
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
