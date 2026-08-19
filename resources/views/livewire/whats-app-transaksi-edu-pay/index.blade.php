{{-- Success is as dangerous as failure. --}}
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-header">
        <div class="d-flex align-items-center flex-wrap gap-3">
            {{-- HEADER --}}
            <div class="flex-grow-1">
                <div class="d-flex align-items-center gap-3">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title bg-primary-subtle text-primary rounded-circle fs-20">
                            <i class="ri-bank-card-line"></i>
                        </div>
                    </div>

                    <div>
                        <h5 class="fw-bold mb-1">
                            Pesan Transaksi EduPay
                        </h5>

                        <small class="text-muted">
                            Kelola pesan WhatsApp untuk transaksi EduPay siswa.
                        </small>
                    </div>
                </div>
            </div>

            {{-- ACTION --}}
            @if ($selectedJenjang)
            <div class="flex-shrink-0">
                <div class="d-flex align-items-center flex-wrap gap-2">

                    {{-- KUITANSI EDUPAY --}}
                    <button type="button"
                        class="btn btn-primary rounded-pill px-4 d-inline-flex align-items-center gap-1"
                        data-bs-toggle="offcanvas" data-bs-target="#kuitansiEduPay"
                        aria-controls="kuitansiEduPay"
                        wire:click="$emit('kuitansiEduPay', {{ $selectedJenjang }})">

                        <i class="ri-file-paper-2-line align-bottom"></i>
                        <span>Kuitansi EduPay</span>
                    </button>

                    {{-- WHATSAPP --}}
                    @if (!$pesans)

                        <button
                            type="button"
                            class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1 shadow-none"
                            data-bs-target="#createPesanEduPay"
                            data-bs-toggle="modal"
                            wire:click="$emit('createPesanEduPay', {{ $selectedJenjang }})">

                            <i class="ri-whatsapp-line align-bottom"></i>
                            <span>Setting WhatsApp</span>
                        </button>

                    @else

                        <button
                            type="button"
                            class="btn btn-success rounded-pill px-4 d-inline-flex align-items-center gap-1 shadow-none"
                            data-bs-target="#loadPesanEduPay"
                            data-bs-toggle="modal"
                            wire:click="$emit('loadPesanEduPay', {{ $ms_pesan_id }})">

                            <i class="ri-whatsapp-line align-bottom"></i>
                            <span>Edit WhatsApp</span>
                        </button>

                    @endif
                </div>
            </div>
            @endif

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
                                                <p>Kami informasikan bahwa <b>Transaksi EduPay</b> atas nama siswa <b>Yafa Arsyida</b> telah berhasil. Berikut adalah rincian transaksinya :  </p>
                                                <p><b>TopUp/Tarik Tunai - Rp. 50.XXX,</b></p>
                                                <p><b>Saldo EduPay - Rp. 500.XXX,</b></p>
                                                <p><b>Top Up untuk pembayaran SPP Sepxxxx, SPP Oktxxx</b></p>
                                                <p>{{ $pesans->kalimat_penutup }}</p>
                                                <p>{{ $pesans->salam_penutup }}</p>
                                                {{-- <br> --}}
                                                <p>Tata Usaha - Anto Pramesti</p>
                                                <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
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
