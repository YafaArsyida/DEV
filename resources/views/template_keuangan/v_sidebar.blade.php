 <div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        {{-- <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{asset('assets')}}/logo/atas.jpg" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{asset('assets')}}/logo/atas.jpg" alt="" height="40">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{asset('assets')}}/logo/atas.jpg" alt="" height="30">
            </span>
            <span class="logo-lg">
                <img src="{{asset('assets')}}/logo/atas.jpg" alt="" height="40">
            </span>
        </a> --}}
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
            <span class="logo-sm">
                <span class="fw-bold fs-5">Teman</span>
            </span>
            <span class="logo-lg">
                <span class="fw-bold fs-4">TemanSekolah</span>
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index.html" class="logo logo-light">
            <span class="logo-sm">
                <span class="fw-bold fs-5">Teman</span>
            </span>
            <span class="logo-lg">
                <span class="fw-bold fs-4">TemanSekolah</span>
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid" style="max-width: 100%">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                @php
                    $peran = auth()->check() ? auth()->user()->peran : null;
                @endphp
                    <li class="nav-item">
                        <a href="{{ route('keuangan.dashboard') }}"
                        class="nav-link menu-link {{ request()->routeIs('keuangan.dashboard') ? 'active' : '' }}">

                            <i class="mdi mdi-speedometer"></i>

                            <span data-key="t-dashboard">
                                Dashboard
                            </span>
                        </a>
                    </li>
                    {{-- @if ($peran === 'tata usaha' || $peran === 'superadmin') --}}
                    <li class="menu-title"><span data-key="t-administrasi">Administrasi</span></li>

                    <li class="nav-item">
                        <a href="{{ route('keuangan.akuntansi.konfigurasi') }}"
                        class="nav-link menu-link {{ request()->routeIs('keuangan.akuntansi.konfigurasi') ? 'active' : '' }}">
                            <i class="mdi mdi-calculator-variant-outline"></i>
                            <span data-key="t-kelas-siswa">Konfigurasi Akuntansi</span>
                        </a>
                    </li>
                    <!-- Single Menus -->
                    <li class="nav-item">
                        <a href="{{ route('keuangan.administrasi.kelas-siswa') }}"
                        class="nav-link menu-link {{ request()->routeIs('keuangan.administrasi.kelas-siswa') ? 'active' : '' }}">
                            <i class="mdi mdi-school-outline"></i>
                            <span data-key="t-kelas-siswa">Kelas Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('keuangan.administrasi.ekstrakurikuler-siswa') }}"
                        class="nav-link menu-link {{ request()->routeIs('keuangan.administrasi.ekstrakurikuler-siswa') ? 'active' : '' }}">
                            <i class="mdi mdi-trophy-outline"></i>
                            <span data-key="t-kelas-siswa">Ekstrakurikuler Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('keuangan.tagihan.konfigurasi') }}"
                        class="nav-link menu-link {{ request()->routeIs('keuangan.tagihan.konfigurasi') ? 'active' : '' }}">
                            <i class="mdi mdi-credit-card-settings-outline"></i>
                            <span data-key="t-konfigurasi-tagihan">Konfigurasi Keuangan Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('keuangan.tagihan.siswa') }}"
                        class="nav-link menu-link {{ request()->routeIs('keuangan.tagihan.siswa') ? 'active' : '' }}">
                            <i class="mdi mdi-receipt-text"></i>
                            <span data-key="t-tagihan-siswa">Tagihan Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('keuangan.tagihan.jenis') }}"
                        class="nav-link menu-link {{ request()->routeIs('keuangan.tagihan.jenis') ? 'active' : '' }}">
                            <i class="mdi mdi-format-list-bulleted"></i>
                            <span data-key="t-jenis-tagihan">Jenis Tagihan</span>
                        </a>
                    </li>

                    <li class="menu-title"><span data-key="t-transaksi">Transaksi</span></li>

                    <!-- Tagihan Siswa -->
                    <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('keuangan.transaksi.tagihan-siswa') ? 'active' : '' }}"
                                href="{{ route('keuangan.transaksi.tagihan-siswa') }}">
                            <i class="mdi mdi-receipt-text-outline"></i>
                            <span data-key="t-tagihan-siswa">Tagihan Siswa</span>
                        </a>
                    </li>
                    
                    @php
                        $isTransaksiSiswaActive = request()->routeIs('keuangan.transaksi.tabungan-siswa') || request()->routeIs('keuangan.transaksi.edupay-siswa');
                    @endphp
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isTransaksiSiswaActive ? 'active' : '' }}" 
                        href="#sidebarTransaksiSiswa" 
                        data-bs-toggle="collapse" 
                        role="button" 
                        aria-expanded="{{ $isTransaksiSiswaActive ? 'true' : 'false' }}" 
                        aria-controls="sidebarTransaksiSiswa">
                            <i class="mdi mdi-bank-transfer"></i>
                            <span data-key="t-transaksi-siswa">Transaksi Siswa</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $isTransaksiSiswaActive ? 'show' : '' }}" id="sidebarTransaksiSiswa">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('keuangan.transaksi.tabungan-siswa') }}" 
                                    class="nav-link {{ request()->routeIs('keuangan.transaksi.tabungan-siswa') ? 'active' : '' }}" 
                                    data-key="t-tabungan-siswa">
                                    Tabungan Siswa
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('keuangan.transaksi.edupay-siswa') }}" 
                                    class="nav-link {{ request()->routeIs('keuangan.transaksi.edupay-siswa') ? 'active' : '' }}" 
                                    data-key="t-edupay-siswa">
                                    EduPay Siswa
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    @php
                        $isTransaksiPegawaiActive = request()->routeIs('keuangan.transaksi.edupay-pegawai') || request()->routeIs('keuangan.transaksi.tabungan-pegawai');
                    @endphp
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isTransaksiPegawaiActive ? 'active' : '' }}" 
                        href="#sidebarTransaksiPegawai" 
                        data-bs-toggle="collapse" 
                        role="button" 
                        aria-expanded="{{ $isTransaksiPegawaiActive ? 'true' : 'false' }}" 
                        aria-controls="sidebarTransaksiPegawai">
                            <i class="mdi mdi-briefcase-outline"></i>
                            <span data-key="t-transaksi-pegawai">Transaksi Pegawai</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $isTransaksiPegawaiActive ? 'show' : '' }}" id="sidebarTransaksiPegawai">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('keuangan.transaksi.tabungan-pegawai') }}" 
                                    class="nav-link {{ request()->routeIs('keuangan.transaksi.tabungan-pegawai') ? 'active' : '' }}" 
                                    data-key="t-tabungan-pegawai">
                                    Tabungan Pegawai
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('keuangan.transaksi.edupay-pegawai') }}" 
                                    class="nav-link {{ request()->routeIs('keuangan.transaksi.edupay-pegawai') ? 'active' : '' }}" 
                                    data-key="t-edupay-pegawai">
                                    EduPay Pegawai
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                
                    <!-- Penggajian Pegawai -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#">
                            <i class="mdi mdi-cash-check"></i>
                            <span data-key="t-gaji-pegawai">Pennggajian Pegawai</span>
                        </a>
                    </li>
                    <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('keuangan.transaksi.pendapatan-lainnya') ? 'active' : '' }}"
                                    href="{{ route('keuangan.transaksi.pendapatan-lainnya') }}">
                            <i class="mdi mdi-trending-up"></i>
                            <span data-key="t-gaji-pegawai">Transaksi Pendapatan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('keuangan.transaksi.pengeluaran') ? 'active' : '' }}" 
                            href="{{ route('keuangan.transaksi.pengeluaran') }}">
                            <i class="mdi mdi-trending-down"></i>
                            <span data-key="t-gaji-pegawai">Transaksi Pengeluaran</span>
                        </a>
                    </li>
                     
                    <li class="menu-title"><span data-key="t-laporan">Laporan Akademik/Jenjang</span></li>

                    <!-- Pembayaran Tagihan Siswa -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('keuangan.laporan.pembayaran.tagihan-siswa') ? 'active' : '' }}"
                            href="{{ route('keuangan.laporan.pembayaran.tagihan-siswa') }}">
                            <i class="mdi mdi-file-check-outline"></i>
                            <span data-key="t-pembayaran-tagihan">Pembayaran Siswa</span>
                        </a>
                    </li>
                    
                    <!-- Tunggakan Tagihan Siswa -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('keuangan.laporan.tagihan.siswa') ? 'active' : '' }}"
                            href="{{ route('keuangan.laporan.tagihan.siswa') }}">
                            <i class="mdi mdi-file-alert-outline"></i>
                            <span data-key="t-tunggakan-tagihan">Piutang Siswa</span>
                        </a>
                    </li>

                    @php
                        $laporanSiswa = request()->routeIs('keuangan.laporan.siswa.tabungan') || request()->routeIs('keuangan.laporan.siswa.edupay');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $laporanSiswa ? 'active' : '' }}" 
                        href="#sidebarLaporanSiswa" 
                        data-bs-toggle="collapse" 
                        role="button" 
                        aria-expanded="{{ $laporanSiswa ? 'true' : 'false' }}" 
                        aria-controls="sidebarLaporanSiswa">
                            <i class="mdi mdi-chart-box-outline"></i>
                            <span data-key="t-laporan-siswa">Laporan Siswa</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $laporanSiswa ? 'show' : '' }}" id="sidebarLaporanSiswa">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('keuangan.laporan.siswa.tabungan') }}" 
                                    class="nav-link {{ request()->routeIs('keuangan.laporan.siswa.tabungan') ? 'active' : '' }}" 
                                    data-key="t-tabungan-siswa">
                                    Tabungan Siswa
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('keuangan.laporan.siswa.edupay') }}" 
                                    class="nav-link {{ request()->routeIs('keuangan.laporan.siswa.edupay') ? 'active' : '' }}" 
                                    data-key="t-edupay-siswa">
                                    EduPay Siswa
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Rekapitulasi Keuangan Siswa -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('keuangan.laporan.rekapitulasi-keuangan-siswa') ? 'active' : '' }}"
                            href="{{ route('keuangan.laporan.rekapitulasi-keuangan-siswa') }}">
                            <i class="mdi mdi-chart-bar-stacked"></i>
                            <span data-key="t-rekapitulasi-keuangan">Rekapitulasi Pembayaran Siswa</span>
                        </a>
                    </li>
                    
                     @php
                        $laporanPegawai = request()->routeIs('keuangan.laporan.pegawai.tabungan') || request()->routeIs('keuangan.laporan.pegawai.edupay');
                    @endphp
                    <!-- Laporan Pegawai: Tabungan & EduPay -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $laporanPegawai ? 'active' : '' }}" 
                            href="#sidebarLaporanPegawai" data-bs-toggle="collapse" role="button" 
                            aria-expanded="{{ $laporanPegawai ? 'true' : 'false' }}" 
                            aria-controls="sidebarLaporanPegawai">
                            <i class="mdi mdi-finance"></i>
                            <span data-key="t-laporan-pegawai">Laporan Pegawai</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $laporanPegawai ? 'show' : '' }}" id="sidebarLaporanPegawai">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('keuangan.laporan.pegawai.tabungan') }}" 
                                    class="nav-link {{ request()->routeIs('keuangan.laporan.pegawai.tabungan') ? 'active' : '' }}" 
                                    data-key="t-tabungan-pegawai">
                                    Tabungan Pegawai
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('keuangan.laporan.pegawai.edupay') }}" 
                                    class="nav-link {{ request()->routeIs('keuangan.laporan.pegawai.edupay') ? 'active' : '' }}" 
                                    data-key="t-edupay-pegawai">
                                    EduPay Pegawai
                                    </a>
                                </li>
                                {{-- <li class="nav-item">
                                    <a href="#" class="nav-link" data-key="t-honor-pegawai">Penggajian Pegawai</a>
                                </li> --}}
                            </ul>
                        </div>
                    </li>     
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('keuangan.akuntansi.laporan.pendapatan') ? 'active' : '' }}"
                            href="{{ route('keuangan.akuntansi.laporan.pendapatan') }}">
                            <i class="mdi mdi-trending-up"></i>
                            <span data-key="t-pendapatan-unit">Laporan Pendapatan</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('keuangan.akuntansi.laporan.pengeluaran') ? 'active' : '' }}"
                            href="{{ route('keuangan.akuntansi.laporan.pengeluaran') }}">
                            <i class="mdi mdi-trending-down"></i>
                            <span data-key="t-pengeluaran-unit">Laporan Pengeluaran</span>
                        </a>
                    </li>
                    <!-- Laporan Rekapitulasi Keuangan -->
                    <li class="menu-title"><span data-key="t-laporan-rekapitulasi-keuangan">Laporan Akuntansi</span></li>
                    
                    <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('keuangan.akuntansi.laporan.jurnal-umum') ? 'active' : '' }}"
                                    href="{{ route('keuangan.akuntansi.laporan.jurnal-umum') }}">
                            <i class="mdi mdi-book-open-outline"></i> 
                            <span data-key="t-jurnal-umum">Jurnal Keuangan</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('keuangan.akuntansi.laporan.buku-besar') ? 'active' : '' }}"
                                    href="{{ route('keuangan.akuntansi.laporan.buku-besar') }}">
                            <i class="mdi mdi-book-outline"></i> 
                            <span data-key="t-buku-besar">Laporan Buku Besar</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('keuangan.akuntansi.laporan.laba-rugi') ? 'active' : '' }}"
                                    href="{{ route('keuangan.akuntansi.laporan.laba-rugi') }}">
                            <i class="mdi mdi-chart-bar"></i> 
                            <span data-key="t-laba-rugi">Laporan Laba Rugi</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                                <a class="nav-link menu-link {{ request()->routeIs('keuangan.akuntansi.laporan.neraca') ? 'active' : '' }}"
                                    href="{{ route('keuangan.akuntansi.laporan.neraca') }}">
                            <i class="mdi mdi-scale-balance"></i> 
                            <span data-key="t-neraca">Laporan Neraca</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                            <a class="nav-link menu-link {{ request()->routeIs('keuangan.akuntansi.laporan.arus-kas') ? 'active' : '' }}"
                                href="{{ route('keuangan.akuntansi.laporan.arus-kas') }}">
                            <i class="mdi mdi-cash"></i> 
                            <span data-key="t-arus-kas">Laporan Arus Kas</span>
                        </a>
                    </li>

                    <li class="menu-title"><span data-key="t-menu">sistem</span></li>
                    <!-- Jenjang & Tahun Ajaran -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('keuangan.sistem.jenjang-tahun-ajar') ? 'active' : '' }}" 
                            href="{{ route('keuangan.sistem.jenjang-tahun-ajar') }}">
                            <i class="mdi mdi-calendar-outline"></i> <span data-key="t-tahun-ajaran">Jenjang & Tahun Ajaran</span>
                        </a>
                    </li>
    
                    <!-- Akses Petugas -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('keuangan.sistem.pengguna-jenjang') ? 'active' : '' }}"
                            href="{{ route('keuangan.sistem.pengguna-jenjang') }}">
                            <i class="mdi mdi-account-key-outline"></i> <span data-key="t-pengguna">Akses Petugas</span>
                        </a>
                    </li>
    
                    <!-- Dokumen Administrasi -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('keuangan.sistem.dokumen-administrasi') ? 'active' : '' }}"
                            href="{{ route('keuangan.sistem.dokumen-administrasi') }}">
                            <i class="mdi mdi-file-document-outline"></i> <span data-key="t-dokumen-administrasi">Kuitansi dan Surat</span>
                        </a>
                    </li>
    
                {{-- @endif --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>