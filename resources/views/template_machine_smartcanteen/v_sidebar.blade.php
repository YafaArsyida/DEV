 <div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
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
                    <a href="{{ route('smartCanteen.dashboard') }}"
                    class="nav-link menu-link {{ request()->routeIs('smartCanteen.dashboard') ? 'active' : '' }}">
                        <i class="mdi mdi-speedometer"></i>
                        <span data-key="t-jenis-tagihan">Dashboard</span>
                    </a>
                </li>

                <li class="menu-title"><span data-key="t-administrasi">Administrasi</span></li>
                
                <li class="nav-item">
                    <a href="{{ route('smartCanteen.administrasi.produk') }}"
                    class="nav-link menu-link {{ request()->routeIs('smartCanteen.administrasi.produk') ? 'active' : '' }}">
                        <i class="mdi mdi-school-outline"></i>
                        <span data-key="t-kelas-siswa">Master Produk</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-administrasi">Transaksi</span></li>
                <li class="nav-item">
                    <a href="{{ route('smartCanteen.transaksi.produk') }}"
                    class="nav-link menu-link {{ request()->routeIs('smartCanteen.transaksi.produk') ? 'active' : '' }}">
                        <i class="mdi mdi-school-outline"></i>
                        <span data-key="t-kelas-siswa">Pembelian Produk</span>
                    </a>
                </li>
                <li class="menu-title"><span data-key="t-administrasi">Laporan</span></li>
                <li class="nav-item">
                    <a href="{{ route('akuntansi.konfigurasi') }}"
                    class="nav-link menu-link {{ request()->routeIs('akuntansi.konfigurasi') ? 'active' : '' }}">
                        <i class="mdi mdi-school-outline"></i>
                        <span data-key="t-kelas-siswa">Laporan Keuangan</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>