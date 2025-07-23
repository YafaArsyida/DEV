<div wire:ignore.self class="offcanvas offcanvas-top" id="suratTagihan" aria-labelledby="suratTagihanLabel" style="min-height:100vh;">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title" id="suratTagihanLabel">Format Surat Tagihan</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="row justify-content-center">
            <div class="col-xxl-6">
                <div class="card">
                    <div class="card-header align-items-center d-flex">
                        <h4 class="card-title mb-0">Surat Tagihan Siswa</h4>   
                        <div class="ms-auto"> <!-- Menambahkan ms-auto untuk mendorong ke kanan -->
                            <div class="dropdown">
                                @if ($selectedJenjang)
                                    @if (!$surat)
                                        <a href="#createSuratTagihan" data-bs-toggle="modal" class="btn btn-ghost-secondary btn-icon shadow-none" wire:click="$emit('createSuratTagihan', {{ $selectedJenjang }})">
                                            <i class="ri-settings-5-line fs-20" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Setting Surat"></i>
                                        </a>
                                    @else
                                    <a href="#editSuratTagihan" data-bs-toggle="modal" class="btn btn-ghost-secondary btn-icon shadow-none" 
                                    wire:click="$emit('loadSuratTagihan', {{ $surat->ms_surat_tagihan_siswa_id }}, {{ $selectedJenjang }})">
                                        <i class="ri-quill-pen-line fs-20" data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-placement="top" title="Edit Surat"></i>
                                    </a>
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                    @if ($surat)
                    <div class="card-body p-4 bg-white mb-2">
                        <div class="text-center mb-0">
                            @if($surat->foto_kop)
                                <img src="{{ Storage::url($surat->foto_kop) }}" alt="Kop Surat" class="img-fluid" style="max-width: 100%; height: auto;">
                            @else
                                <h3 class="text-black">Foto kop belum diunggah</h3>
                            @endif
                        </div>
                        <!-- Garis pertama -->
                        <div class="line" style="height: 3px; background-color: black; margin: 0 auto; width: 95%;"></div>
                        <!-- Garis kedua -->
                        <div class="line" style="height: 1px; background-color: black; margin: 2px auto; width: 95%;"></div>

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
                    <div class="card-body p-4 bg-white">
                        <div class="text-center mb-0">
                            <img src="{{ Storage::url($surat->foto_kop) }}" alt="Kop Surat" class="img-fluid" style="max-width: 100%; height: auto;">
                        </div>
                        <!-- Garis pertama -->
                        <div class="line" style="height: 3px; background-color: black; margin: 0 auto; width: 95%;"></div>
                        <!-- Garis kedua -->
                        <div class="line" style="height: 1px; background-color: black; margin: 2px auto; width: 95%;"></div>

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
                    <div class="card-body p4 bg-white mb-2 text-center">
                        <h3 class="p-4 text-black" style="font-family: 'Times New Roman', Times, serif; font-size: 12pt;">Template surat belum di atur untuk jenjang ini.</h3>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
