<div wire:ignore.self class="offcanvas offcanvas-top" id="kuitansiTabungan" aria-labelledby="kuitansiTransaksiLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="kuitansiTransaksiLabel">Format Kuitansi Tabungan</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row justify-content-center">
            <div class="col-xxl-4">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0">Kuitansi Tabungan</h4>   
                        <div class="ms-auto"> <!-- Menambahkan ms-auto untuk mendorong ke kanan -->
                            <div class="dropdown">
                                @if ($selectedJenjang)
                                    @if (!$kuitansi)
                                        <a href="#createKuitansiTabungan" data-bs-toggle="modal" class="btn btn-ghost-secondary btn-icon shadow-none" wire:click="$emit('createKuitansiTabungan', {{ $selectedJenjang }})">
                                            <i class="ri-settings-5-line fs-20" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Setting Kuitansi"></i>
                                        </a>
                                    @else
                                    <a href="#editKuitansiTabungan" data-bs-toggle="modal" class="btn btn-ghost-secondary btn-icon shadow-none" 
                                    wire:click="$emit('loadKuitansiTabungan', {{ $kuitansi->ms_kuitansi_transaksi_tabungan_id }}, {{ $selectedJenjang }})">
                                        <i class="ri-quill-pen-line fs-20" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Kuitansi"></i>
                                    </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($kuitansi)
                    <div class="card-body p-4 bg-white p-1">
                        <!-- HEADER -->
                        <div class="text-center text-black mb-2" style="font-family: 'Times New Roman', Times, serif; font-size: 18pt;">
                            <img src="{{ Storage::url($kuitansi->logo) }}" alt="Logo" class="img-fluid" style="height: 80px;">
                            <p class="mt-2 mb-0">{{ $kuitansi->nama_institusi }}</p>
                            <p style="font-family: 'Times New Roman', Times, serif; font-size: 14pt;">
                                {{ $kuitansi->alamat }}<br>
                                {{ $kuitansi->kontak }}
                            </p>
                            <p class="mt-2 mb-0"><b>{{ $kuitansi->judul }}</b></p>
                            <p class="my-0"><b>TABUNGAN TUNAI</b></p>
                            <p class="mt-4 mb-0"><b>RP9.XXX.XXX</b></p>
                            <p class="fst-italic" style="font-family: 'Times New Roman', Times, serif; font-size: 14pt;">
                                deskripsi transaksi
                            </p>
                        </div>

                        <div style="font-family: 'Times New Roman', Times, serif; font-size: 14pt;" class="px-4 m-2 text-black">
                            <table cellpadding="0" class="m-2">
                                <tr>
                                    <td width="12%">Siswa</td>
                                    <td width="88%">: nama siswa</td>
                                </tr>
                                <tr>
                                    <td>Kelas</td>
                                    <td>: nama kelas</td>
                                </tr>
                                <tr class="fw-semibold">
                                    <td>Saldo</td>
                                    <td>: RP9.XXX.XXX</td>
                                </tr>
                            </table> 
                             <!-- Catatan -->
                            <div class="px-2 pe-4" style="font-family: 'Times New Roman', Times, serif; font-size: 14pt;">
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
