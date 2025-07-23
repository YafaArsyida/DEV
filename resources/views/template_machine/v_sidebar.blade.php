 <div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index.html" class="logo logo-dark">
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
                        <a href="{{ route('dashboard.index') }}"
                        class="nav-link menu-link {{ request()->routeIs('dashboard.index') ? 'active' : '' }}">
                            <i class="mdi mdi-speedometer"></i>
                            <span data-key="t-jenis-tagihan">Dashboard</span>
                        </a>
                    </li>
                @if ($peran === 'tata usaha' || $peran === 'superadmin')
                    {{-- TATA USAHA --}}
                    <!-- ADMINISTRASI -->
                    <li class="menu-title"><span data-key="t-administrasi">Administrasi</span></li>

                    <!-- Single Menus -->
                    <li class="nav-item">
                        <a href="{{ route('administrasi.kelas-siswa') }}"
                        class="nav-link menu-link {{ request()->routeIs('administrasi.kelas-siswa') ? 'active' : '' }}">
                            <i class="mdi mdi-school-outline"></i>
                            <span data-key="t-kelas-siswa">Kelas Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('administrasi.ekstrakurikuler-siswa') }}"
                        class="nav-link menu-link {{ request()->routeIs('administrasi.ekstrakurikuler-siswa') ? 'active' : '' }}">
                            <i class="mdi mdi-school-outline"></i>
                            <span data-key="t-kelas-siswa">Ekstrakurikuler Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('keuangan.konfigurasi-tagihan-siswa') }}"
                        class="nav-link menu-link {{ request()->routeIs('keuangan.konfigurasi-tagihan-siswa') ? 'active' : '' }}">
                            <i class="mdi mdi-cog-outline"></i>
                            <span data-key="t-konfigurasi-tagihan">Konfigurasi Tagihan Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('keuangan.tagihan-siswa') }}"
                        class="nav-link menu-link {{ request()->routeIs('keuangan.tagihan-siswa') ? 'active' : '' }}">
                            <i class="mdi mdi-receipt-text"></i>
                            <span data-key="t-tagihan-siswa">Tagihan Siswa</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('keuangan.tagihan-jenis') }}"
                        class="nav-link menu-link {{ request()->routeIs('keuangan.tagihan-jenis') ? 'active' : '' }}">
                            <i class="mdi mdi-format-list-bulleted"></i>
                            <span data-key="t-jenis-tagihan">Jenis Tagihan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('administrasi.manajemen-kepegawaian') }}"
                        class="nav-link menu-link {{ request()->routeIs('administrasi.manajemen-kepegawaian') ? 'active' : '' }}">
                            <i class="mdi mdi-account-outline"></i>
                            <span data-key="t-data-pegawai">Data Pegawai</span>
                        </a>
                    </li>

                    {{-- @php
                        $administrasiPegawai = request()->routeIs('administrasi.jadwal-kerja-pegawai') ||
                                                        request()->routeIs('administrasi.konfigurasi-absen-pegawai');
                    @endphp --}}


                    <!-- Collapse Menu: Administrasi Pegawai -->
                    <li class="nav-item">
                        <a class="nav-link menu-link"
                        href="#sidebarAdministrasiPegawai"
                        data-bs-toggle="collapse"
                        role="button"
                        aria-expanded=""
                        aria-controls="sidebarAdministrasiPegawai">
                            <i class="mdi mdi-card-account-details-outline"></i>
                            <span data-key="t-administrasi-pegawai">Administrasi Pegawai</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarAdministrasiPegawai">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href=""
                                    class="nav-link"
                                    data-key="t-jadwal-kerja">Jadwal Kerja Pegawai</a>
                                </li>
                                <li class="nav-item">
                                    <a href=""
                                    class="nav-link"
                                    data-key="t-konfigurasi-absen">Konfigurasi Absen Pegawai</a>
                                </li>
                            </ul>
                        </div>
                    </li>


                    <li class="menu-title"><span data-key="t-transaksi">Transaksi</span></li>

                    <!-- Tagihan Siswa -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('transaksi.tagihan-siswa') ? 'active' : '' }}"
                             href="{{ route('transaksi.tagihan-siswa') }}">
                            <i class="mdi mdi-cash-register"></i>
                            <span data-key="t-tagihan-siswa">Tagihan Siswa</span>
                        </a>
                    </li>
                    
                    @php
                        $isTransaksiSiswaActive = request()->routeIs('transaksi.tabungan-siswa') || request()->routeIs('transaksi.edupay-siswa');
                    @endphp
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $isTransaksiSiswaActive ? 'active' : '' }}" 
                        href="#sidebarTransaksiSiswa" 
                        data-bs-toggle="collapse" 
                        role="button" 
                        aria-expanded="{{ $isTransaksiSiswaActive ? 'true' : 'false' }}" 
                        aria-controls="sidebarTransaksiSiswa">
                            <i class="mdi mdi-school"></i>
                            <span data-key="t-transaksi-siswa">Transaksi Siswa</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $isTransaksiSiswaActive ? 'show' : '' }}" id="sidebarTransaksiSiswa">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('transaksi.tabungan-siswa') }}" 
                                    class="nav-link {{ request()->routeIs('transaksi.tabungan-siswa') ? 'active' : '' }}" 
                                    data-key="t-tabungan-siswa">
                                    Tabungan Siswa
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('transaksi.edupay-siswa') }}" 
                                    class="nav-link {{ request()->routeIs('transaksi.edupay-siswa') ? 'active' : '' }}" 
                                    data-key="t-edupay-siswa">
                                    EduPay Siswa
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                
                     <!-- Transaksi Pegawai -->
                     <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarTransaksiPegawai" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarTransaksiPegawai">
                            <i class="mdi mdi-account-cash-outline"></i>
                            <span data-key="t-transaksi-pegawai">Transaksi Pegawai</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarTransaksiPegawai">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-key="t-tabungan-pegawai">Tabungan Pegawai</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-key="t-edupay-pegawai">EduPay Pegawai</a>
                                </li>
                            </ul>
                        </div>
                    </li>  
                    <!-- Penggajian Pegawai -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span data-key="t-gaji-pegawai">Pennggajian Pegawai</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('transaksi.pendapatan-lainnya') ? 'active' : '' }}"
                           href="{{ route('transaksi.pendapatan-lainnya') }}">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span data-key="t-gaji-pegawai">Transaksi Pendapatan</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('transaksi.pengeluaran') ? 'active' : '' }}" 
                            href="{{ route('transaksi.pengeluaran') }}">
                            <i class="mdi mdi-cash-multiple"></i>
                            <span data-key="t-gaji-pegawai">Transaksi Pengeluaran</span>
                        </a>
                    </li>
                     
                    <li class="menu-title"><span data-key="t-laporan">Laporan</span></li>

                    <!-- Pembayaran Tagihan Siswa -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('laporan.pembayaran-tagihan-siswa') ? 'active' : '' }}"
                            href="{{ route('laporan.pembayaran-tagihan-siswa') }}">
                            <i class="mdi mdi-receipt-text"></i> 
                            <span data-key="t-pembayaran-tagihan">Pembayaran Tagihan Siswa</span>
                        </a>
                    </li>
                    
                    <!-- Tunggakan Tagihan Siswa -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('laporan.tagihan-siswa') ? 'active' : '' }}"
                            href="{{ route('laporan.tagihan-siswa') }}">
                            <i class="mdi mdi-alert-circle-outline"></i> 
                            <span data-key="t-tunggakan-tagihan">Tunggakan Tagihan Siswa</span>
                        </a>
                    </li>

                    @php
                        $laporanSiswa = request()->routeIs('laporan.tabungan-siswa') || request()->routeIs('laporan.edupay-siswa');
                    @endphp

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ $laporanSiswa ? 'active' : '' }}" 
                        href="#sidebarLaporanSiswa" 
                        data-bs-toggle="collapse" 
                        role="button" 
                        aria-expanded="{{ $laporanSiswa ? 'true' : 'false' }}" 
                        aria-controls="sidebarLaporanSiswa">
                            <i class="mdi mdi-school-outline"></i>
                            <span data-key="t-laporan-siswa">Laporan Siswa</span>
                        </a>
                        <div class="collapse menu-dropdown {{ $laporanSiswa ? 'show' : '' }}" id="sidebarLaporanSiswa">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('laporan.tabungan-siswa') }}" 
                                    class="nav-link {{ request()->routeIs('laporan.tabungan-siswa') ? 'active' : '' }}" 
                                    data-key="t-tabungan-siswa">
                                    Tabungan Siswa
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('laporan.edupay-siswa') }}" 
                                    class="nav-link {{ request()->routeIs('laporan.edupay-siswa') ? 'active' : '' }}" 
                                    data-key="t-edupay-siswa">
                                    EduPay Siswa
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                    
                    <!-- Rekapitulasi Keuangan Siswa -->
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('laporan.rekapitulasi-keuangan') ? 'active' : '' }}"
                            href="{{ route('laporan.rekapitulasi-keuangan') }}">
                            <i class="mdi mdi-chart-bar-stacked"></i> 
                            <span data-key="t-rekapitulasi-keuangan">Rekapitulasi Keuangan Siswa</span>
                        </a>
                    </li>
                    
                    <!-- Laporan Pegawai: Tabungan & EduPay -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="#sidebarLaporanPegawai" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarLaporanPegawai">
                            <i class="mdi mdi-account-tie-outline"></i>
                            <span data-key="t-laporan-pegawai">Laporan Pegawai</span>
                        </a>
                        <div class="collapse menu-dropdown" id="sidebarLaporanPegawai">
                            <ul class="nav nav-sm flex-column">
                                <li class="nav-item">
                                    <a href="{{ route('laporan.tabungan-siswa') }}" class="nav-link" data-key="t-tabungan-pegawai">Tabungan Pegawai</a>
                                </li>
                                <li class="nav-item">
                                    <a href="{{ route('laporan.edupay-siswa') }}" class="nav-link" data-key="t-edupay-pegawai">EduPay Pegawai</a>
                                </li>
                                <li class="nav-item">
                                    <a href="#" class="nav-link" data-key="t-honor-pegawai">Penggajian Pegawai</a>
                                </li>
                            </ul>
                        </div>
                    </li>     

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('akuntansi.laporan-pendapatan') ? 'active' : '' }}"
                           href="{{ route('akuntansi.laporan-pendapatan') }}">
                            <i class="mdi mdi-cash-multiple"></i> 
                            <span data-key="t-pendapatan-unit">Laporan Pendapatan</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('akuntansi.laporan-pengeluaran') ? 'active' : '' }}"
                           href="{{ route('akuntansi.laporan-pengeluaran') }}">
                            <i class="mdi mdi-cash-multiple"></i> 
                            <span data-key="t-pengeluaran-unit">Laporan Pengeluaran</span>
                        </a>
                    </li>
                    
                    <!-- Laporan Rekapitulasi Keuangan -->
                    <li class="menu-title"><span data-key="t-laporan-rekapitulasi-keuangan">Laporan Akuntansi</span></li>
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('akuntansi.laporan-jurnal-umum') ? 'active' : '' }}"
                           href="{{ route('akuntansi.laporan-jurnal-umum') }}">
                            <i class="mdi mdi-book-open-outline"></i> 
                            <span data-key="t-jurnal-umum">Jurnal Keuangan</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('akuntansi.laporan-buku-besar') ? 'active' : '' }}"
                           href="{{ route('akuntansi.laporan-buku-besar') }}">
                            <i class="mdi mdi-book"></i> 
                            <span data-key="t-buku-besar">Laporan Buku Besar</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('akuntansi.laporan-laba-rugi') ? 'active' : '' }}"
                           href="{{ route('akuntansi.laporan-laba-rugi') }}">
                            <i class="mdi mdi-chart-bar"></i> 
                            <span data-key="t-laba-rugi">Laporan Laba Rugi</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ request()->routeIs('akuntansi.laporan-neraca') ? 'active' : '' }}"
                           href="{{ route('akuntansi.laporan-neraca') }}">
                            <i class="mdi mdi-scale-balance"></i> 
                            <span data-key="t-neraca">Laporan Neraca</span>
                        </a>
                    </li>
                    
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="">
                            <i class="mdi mdi-cash"></i> 
                            <span data-key="t-arus-kas">Laporan Arus Kas</span>
                        </a>
                    </li>

                    <li class="menu-title"><span data-key="t-menu">sistem</span></li>
                    <!-- Jenjang & Tahun Ajaran -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('sistem.jenjang-tahun-ajar') }}">
                            <i class="mdi mdi-calendar-outline"></i> <span data-key="t-tahun-ajaran">Jenjang & Tahun Ajaran</span>
                        </a>
                    </li>
    
                    <!-- Akses Petugas -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('sistem.pengguna-jenjang') }}">
                            <i class="mdi mdi-account-key-outline"></i> <span data-key="t-pengguna">Akses Petugas</span>
                        </a>
                    </li>
    
                    <!-- Dokumen Administrasi -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="{{ route('sistem.dokumen-administrasi') }}">
                            <i class="mdi mdi-file-document-outline"></i> <span data-key="t-dokumen-administrasi">Dokumen Administrasi</span>
                        </a>
                    </li>
    
                    {{-- <!-- Dokumen Surat -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="">
                            <i class="mdi mdi-email-outline"></i> <span data-key="t-dokumen-surat">Dokumen Surat</span>
                        </a>
                    </li> --}}
    
                    <!-- Menu & Sub-Menu -->
                    <li class="nav-item">
                        <a class="nav-link menu-link" href="menu-submenu.html">
                            <i class="mdi mdi-view-list-outline"></i> <span data-key="t-menu-submenu">Menu & Sub-Menu</span>
                        </a>
                    </li>               
                @endif
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>