{{-- Because she competes with no one, no one can compete with her. --}}
<div class="card">
    <div class="card-header align-items-center d-flex">
        <h4 class="card-title mb-0">Pesan Tagihan Siswa</h4>   
        <div class="ms-auto"> <!-- Menambahkan ms-auto untuk mendorong ke kanan -->
            <div class="dropdown">
                @if ($selectedJenjang)
                    <button class="btn btn-primary" data-bs-toggle="offcanvas" data-bs-target="#suratTagihan" aria-controls="suratTagihan" wire:click="$emit('refreshSurat', {{ $selectedJenjang }})"><i class="ri-file-paper-2-line me-1 align-bottom"></i> Surat Tagihan</button>
                    @if (!$pesans)
                        <button data-bs-target="#createPesanTagihanSiswa" data-bs-toggle="modal" wire:click="$emit('createPesanTagihanSiswa', {{ $selectedJenjang }})" class="btn btn-soft-success shadow-none"><i class="ri-whatsapp-line align-bottom me-1"></i> Setting WhatsApp</button>
                    @else
                    <button data-bs-target="#ModalEditTagihanSiswa" data-bs-toggle="modal" wire:click="$emit('loadPesanTagihanSiswa', {{ $ms_pesan_id }})" class="btn btn-soft-success shadow-none"><i class="ri-whatsapp-line align-bottom me-1"></i> Edit WhatsApp</button>
                    @endif
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        @if (!$selectedJenjang)
            <div class="text-center py-4">
                <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                    colors="primary:#405189,secondary:#08a88a"
                    style="width:75px;height:75px">
                </lord-icon>
                <h5 class="mt-2">Silakan Pilih Jenjang</h5>
                <p class="text-muted mb-0">Untuk melihat pesan, harap pilih Jenjang terlebih dahulu.</p>
            </div>
        @else
        <div class="user-chat card mb-0">
            <div class="position-relative">
                <div class="position-relative">
                    <div class="p-3 user-chat-topbar">
                        <div class="row align-items-center">
                            <div class="col-sm-12 col-12">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0 d-block d-lg-none me-3">
                                        <a href="javascript: void(0);" class="user-chat-remove fs-18 p-1"><i class="ri-arrow-left-s-line align-bottom"></i></a>
                                    </div>
                                    <div class="flex-grow-1 overflow-hidden">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 chat-user-img online user-own-img align-self-center me-3 ms-0">
                                                <img src="{{asset('assets')}}/images/users/avatar-2.jpg" class="rounded-circle avatar-xs" alt="">
                                                <span class="user-status"></span>
                                            </div>
                                            <div class="flex-grow-1 overflow-hidden">
                                                <h5 class="text-truncate mb-0 fs-16"><a class="text-reset username" data-bs-toggle="offcanvas" href="#userProfileCanvasExample" aria-controls="userProfileCanvasExample">Tata Usaha Sekolah</a></h5>
                                                <p class="text-truncate text-muted fs-14 mb-0 userStatus"><small>Online</small></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end chat user head -->
                    <div class="chat-conversation p-3 p-lg-4" style="height: auto">
                        <ul class="list-unstyled chat-conversation-list chat-sm" id="users-conversation">
                            <li class="chat-list left">
                                <div class="conversation-list">
                                    <div class="chat-avatar">
                                        <img src="{{asset('assets')}}/images/users/avatar-2.jpg" alt="">
                                    </div>
                                    <div class="user-chat-content">
                                        <div class="ctext-wrap">
                                            <div class="ctext-wrap-content">
                                            @if ($pesans)
                                                <p class="mb-0 ctext-content"><b>{{ $pesans->judul }}</b></p>
                                                <br>
                                                <p>
                                                {{ $pesans->salam_pembuka }}</p>
                                                <p>{{ $pesans->kalimat_pembuka }}</p>
                                                <div class="mb-3">
                                                    <label for="detail_transaksi" class="form-label">Detail Tagihan (bawaan sistem)</label>
                                                    <p>Kami informasikan bahwa <b>Tagihan Sekolah</b> atas nama siswa <b>Yafa Arsyida</b> kelas <b>2 A Umar</b> masih perlu diselesaikan. Berikut adalah rincian tagihannya : </p>
                                                    <table cellpadding="1" class="mb-3">
                                                        <tr>
                                                            <td><b>- SPP FEBRUARI : Rp100.XXX</b></td>
                                                        </tr>
                                                        <tr class="mb-3">
                                                            <td><b>- Transport MARET : Rp50.XXX</b></td>
                                                        </tr>
                                                    </table> 
                                                    <p><b>Total Tagihan Rp9XX.XXX</b></p>  
                                                </div>
                                                <div class="mb-3">
                                                    {{-- <label for="detail_transaksi" class="form-label">Instruksi (sama seperti template surat)</label> --}}
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
                                                </div>
                                                <p>{{ $pesans->kalimat_penutup }}</p>
                                                <p>{{ $pesans->salam_penutup }}</p>
                                                {{-- <br> --}}
                                                <p>Tata Usaha - Anto Pramesti</p>
                                                <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                                                {{-- <p>17 April 2025</p> --}}
                                                @else
                                                    <p>Tidak ada data yang ditemukan untuk jenjang ini.</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="conversation-name"><small class="text-muted time">09:07 am</small> <span class="text-success check-message-icon"><i class="ri-check-double-line align-bottom"></i></span></div>
                                    </div>
                                </div>
                            </li>
                            <!-- chat-list -->

                            <li class="chat-list right">
                                <div class="conversation-list">
                                    <div class="user-chat-content">
                                        <div class="ctext-wrap">
                                            <div class="ctext-wrap-content">
                                                <p class="mb-0 ctext-content">Waalaikumussalam Wr.Wb.</p>
                                                <p class="mb-0 ctext-content">Baik Terimakasih Pemberitahuannyuuii</p>
                                            </div>
                                        </div>
                                        <div class="conversation-name"><small class="text-muted time">09:08 am</small> <span class="text-success check-message-icon"><i class="ri-check-double-line align-bottom"></i></span></div>
                                    </div>
                                </div>
                            </li>
                            <!-- chat-list -->
                        </ul>
                    </div>
                </div>
                <div class="chat-input-section p-3 p-lg-4">
                    <div class="row g-0 align-items-center">
                        <div class="col-auto">
                            <div class="chat-input-links me-2">
                                <div class="links-list-item">
                                    <button type="button" class="btn btn-link text-decoration-none emoji-btn" id="emoji-btn">
                                        <i class="bx bx-smile align-middle"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <div class="col">
                            <div class="chat-input-feedback">
                                Please Enter a Message
                            </div>
                            <input type="text" class="form-control chat-input bg-light border-light" id="chat-input" placeholder="Type your message..." autocomplete="off">
                        </div>
                        <div class="col-auto">
                            <div class="chat-input-links ms-2">
                                <div class="links-list-item">
                                    <button type="submit" class="btn btn-primary chat-send waves-effect waves-light shadow">
                                        <i class="ri-send-plane-2-fill align-bottom"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
