<div wire:ignore.self class="offcanvas offcanvas-top bg-light" id="kuitansiTabungan" aria-labelledby="kuitansiTransaksiLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom px-4 py-3 shadow-sm">
        <div class="d-flex justify-content-between align-items-start w-100">
            <!-- Kiri -->
            <div class="d-flex align-items-center gap-3">
                <div class="avatar-sm">
                    <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-18">
                        <i class="ri-wallet-3-line"></i>
                    </div>
                </div>

                <div>
                    <h5 class="fw-bold mb-1">
                        Format Kuitansi Tabungan
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
                                            <i class="ri-wallet-3-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            Kuitansi Tabungan
                                        </h5>

                                        <small class="text-muted">
                                            Atur format dan isi kuitansi transaksi tabungan.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- ACTION --}}
                            <div class="d-flex gap-2 flex-wrap">
                                @if ($selectedJenjang)

                                    @if (!$kuitansi)
                                        <button
                                            type="button" class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal" data-bs-target="#createKuitansiTabungan"
                                            wire:click="$emit('createKuitansiTabungan', {{ $selectedJenjang }})">

                                            <i class="ri-add-line"></i>
                                            <span>Buat Kuitansi</span>
                                        </button>

                                    @else
                                        <button
                                            type="button" class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal" data-bs-target="#editKuitansiTabungan"
                                            wire:click="$emit('loadKuitansiTabungan', {{ $kuitansi->ms_kuitansi_transaksi_tabungan_id }}, {{ $selectedJenjang }})">

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
