<div wire:ignore.self class="offcanvas offcanvas-top bg-light" id="suratTagihan" aria-labelledby="suratTagihanLabel" style="min-height:100vh;">
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
                        Format Surat Tagihan Siswa
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
            <div class="col-xxl-6">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    <div class="card-header">
                        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-4">

                            {{-- TITLE --}}
                            <div>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="avatar-sm">
                                        <div class="avatar-title bg-warning-subtle text-warning rounded-circle fs-20">
                                            <i class="ri-mail-send-line"></i>
                                        </div>
                                    </div>

                                    <div>
                                        <h5 class="fw-bold mb-1">
                                            Surat Tagihan Siswa
                                        </h5>
                                        <small class="text-muted">
                                            Atur format dan isi surat tagihan yang akan dicetak maupun dikirim kepada wali siswa.
                                        </small>
                                    </div>
                                </div>
                            </div>

                            {{-- ACTION --}}
                            <div class="d-flex gap-2 flex-wrap">

                                @if ($selectedJenjang)
                                    @if (!$surat)
                                        <button
                                            type="button"
                                            class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#createSuratTagihan"
                                            wire:click="$emit('createSuratTagihan', {{ $selectedJenjang }})">

                                            <i class="ri-add-line"></i>
                                            <span>Buat Surat</span>
                                        </button>
                                    @else
                                        <button
                                            type="button"
                                            class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editSuratTagihan"
                                            wire:click="$emit('loadSuratTagihan', {{ $surat->ms_surat_tagihan_siswa_id }}, {{ $selectedJenjang }})">

                                            <i class="ri-quill-pen-line"></i>
                                            <span>Edit Surat</span>
                                        </button>
                                    @endif
                                @endif

                            </div>

                        </div>
                    </div>
                    @if ($surat)
                    <div class="card-body p-4 bg-white">
                        <div class="text-center mb-0">
                            @if($surat->foto_kop)
                                <img src="{{ Storage::url($surat->foto_kop) }}" alt="Kop Surat" class="img-fluid" style="max-width: 100%; height: auto;">
                            @else
                                <h3 class="text-black">Foto kop belum diunggah</h3>
                            @endif
                        </div>
                        {{-- <!-- Garis pertama -->
                        <div class="line" style="height: 3px; background-color: black; margin: 0 auto; width: 95%;"></div>
                        <!-- Garis kedua -->
                        <div class="line" style="height: 1px; background-color: black; margin: 2px auto; width: 95%;"></div> --}}

                        <div class="p-4 text-black" style="font-family: 'Times New Roman', Times, serif; font-size: 12pt;">
                            <p class="text-end mb-0">{{ $surat->tempat_tanggal }}</p>
                            <table cellpadding="1" class="mb-3">
                                <tr>
                                    <td width="12%"><b>No</b></td>
                                    <td width="88%">: {{ $surat->nomor_surat }}</td>
                                </tr>
                                <tr>
                                    <td><b>Lampiran</b></td>
                                    <td>: {{ $surat->lampiran }}</td>
                                </tr>
                                <tr>
                                    <td><b>Hal</b></td>
                                    <td>: {!! $surat->hal !!}</td>
                                </tr>
                            </table>      
                            <!-- Yth. -->
                            <p>
                                Kepada Yth.<br>
                                Bapak/Ibu Wali Murid Ananda <i>'nama siswa'</i><br>
                                Kelas '<b>nama kelas</b>'
                            </p>
                            <p>{!! $surat->salam_pembuka !!}</p>
                            <p align="justify" style="text-indent: 40px;">{!! $surat->pembuka !!}</p>
                            <p align="justify" style="text-indent: 40px;">{!! $surat->isi !!}</p>
                            @if ($surat->rincian)
                                <p align="justify" style="text-indent: 40px;">{!! $surat->rincian !!}<b>Rp9XX.XXX</b> dengan rincian terlampir.</p>
                            @endif
                            <table cellpadding="1" class="mb-3">
                                <tr>
                                    <td>{!! $surat->panduan !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! $surat->instruksi_1 !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! $surat->instruksi_2 !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! $surat->instruksi_3 !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! $surat->instruksi_4 !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! $surat->instruksi_5 !!}</td>
                                </tr>
                            </table>    
                            <p align="justify" style="text-indent: 40px;">{!! $surat->penutup !!}</p>
                            <p>{!! $surat->salam_penutup !!}</p>
                            <table>
                                <tr>
                                <td width="75%" align="left"></td>
                                <td width="25%" align="left">{!! $surat->jabatan !!}</td>
                                </tr>
                                <tr>
                                <td width="75%" align="left"></td>
                                <td width="25%" align="left"><img src='{{ Storage::url($surat->tanda_tangan) }}' width="150px"></td>
                                </tr>
                                <tr>
                                <td width="75%" align="left"></td>
                                <td width="25%" align="left">{!! $surat->nama_petugas !!}</td>
                                </tr>
                                <tr>
                                <td width="75%" align="left"></td>
                                <td width="25%" align="left">{!! $surat->nomor_petugas !!}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <hr>
                    <div class="card-body p-4 bg-white">
                        <div class="text-center mb-0">
                            <img src="{{ Storage::url($surat->foto_kop) }}" alt="Kop Surat" class="img-fluid" style="max-width: 100%; height: auto;">
                        </div>
                        <!-- Garis pertama -->
                        {{-- <div class="line" style="height: 3px; background-color: black; margin: 0 auto; width: 95%;"></div> --}}
                        <!-- Garis kedua -->
                        {{-- <div class="line" style="height: 1px; background-color: black; margin: 2px auto; width: 95%;"></div> --}}

                        <div class="p-4 text-black" style="font-family: 'Times New Roman', Times, serif; font-size: 12pt;">
                            <p class="text-black"><b>Rincian Tagihan Administrasi Sekolah</b></p>
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>Tagihan</th>
                                        <th>Estimasi</th>
                                        <th>Dibayarkan</th>
                                        <th>Kekurangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>TRANSPORT JULI</td>
                                        <td>Rp20.000</td>
                                        <td>Rp0</td>
                                        <td>Rp20.000</td>
                                    </tr>
                                    <tr>
                                        <td>FEBRUARI</td>
                                        <td>Rp100.000</td>
                                        <td>Rp0</td>
                                        <td>Rp100.000</td>
                                    </tr>
                                    <tr>
                                        <td>JANUARI</td>
                                        <td>Rp921.000</td>
                                        <td>Rp91.000</td>
                                        <td>Rp830.000</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p class="mt-3"><b>Total Kekurangan: Rp950.000</b></p>
                            <table class="mb-5 pb-5" style="font-family: 'Times New Roman', serif; font-size: 12px;" cellpadding="1" class="mb-3">
                                <tr>
                                    <td>{!! $surat->catatan_1 !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! $surat->catatan_2 !!}</td>
                                </tr>
                                <tr>
                                    <td>{!! $surat->catatan_3 !!}</td>
                                </tr>
                            </table>                              
                        </div>
                    </div>
                    @else
                    <div class="card-body py-5">
                        <div class="text-center">

                            <div class="avatar-lg mx-auto mb-4">
                                <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-1">
                                    <i class="ri-file-warning-line"></i>
                                </div>
                            </div>

                            <h5 class="fw-semibold mb-2">
                                Template Surat Belum Tersedia
                            </h5>

                            <p class="text-muted mb-4 mx-auto" style="max-width: 500px;">
                                Belum ada template surat tagihan untuk jenjang yang dipilih.
                                Silakan buat atau atur template terlebih dahulu agar surat tagihan
                                dapat dicetak maupun dikirim kepada wali siswa.
                            </p>

                            @if($selectedJenjang)
                                <button
                                    type="button"
                                    class="btn btn-primary rounded-pill px-4"
                                    data-bs-toggle="modal"
                                    data-bs-target="#createSuratTagihan"
                                    wire:click="$emit('createSuratTagihan', {{ $selectedJenjang }})">
                                    <i class="ri-add-line me-1"></i>
                                    Buat Template Surat
                                </button>
                            @endif

                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
