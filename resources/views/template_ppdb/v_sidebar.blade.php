<div class="app-menu navbar-menu">
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
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="nav-item">
                    <a href="{{ route('ppdb.admin.dashboard') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.admin.dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i><span>Dashboard</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ppdb.admin.pengaturan') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.admin.pengaturan') ? 'active' : '' }}">
                        <i class="mdi mdi-cog-outline"></i>
                        <span>Pengaturan PPDB</span>
                    </a>
                </li>

                <li class="menu-title"><span>OPERASIONAL PPDB</span></li>

                <li class="nav-item">
                    <a href="{{ route('ppdb.admin.pendaftar') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.admin.pendaftar') ? 'active' : '' }}">
                        <i class="mdi mdi-account-group-outline"></i>
                        <span>Semua Pendaftar</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ppdb.admin.bantu-pendaftaran') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.admin.bantu-pendaftaran') ? 'active' : '' }}">
                        <i class="mdi mdi-account-plus-outline"></i>
                        <span>Bantu Pendaftaran</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ppdb.admin.verifikasi') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.admin.verifikasi') ? 'active' : '' }}">
                        <i class="mdi mdi-file-search-outline"></i>
                        <span>
                            Verifikasi
                            <small class="d-block text-muted">Pemeriksaan data dan dokumen</small>
                        </span>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('ppdb.admin.pengumuman') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.admin.pengumuman') ? 'active' : '' }}">
                        <i class="mdi mdi-bullhorn-outline"></i>
                        <span>
                            Pengumuman
                            <small class="d-block text-muted">Publikasi hasil seleksi</small>
                        </span>
                    </a>
                </li>

                {{-- <li class="nav-item">
                    <a href="{{ route('ppdb.admin.daftar-ulang') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.admin.daftar-ulang') ? 'active' : '' }}">
                        <i class="mdi mdi-account-check-outline"></i>
                        <span>
                            Daftar Ulang
                            <small class="d-block text-muted">Konfirmasi calon siswa diterima</small>
                        </span>
                    </a>
                </li> --}}

                <li class="nav-item">
                    <a href="{{ route('ppdb.admin.laporan') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.admin.laporan') ? 'active' : '' }}">
                        <i class="mdi mdi-chart-box-outline"></i>
                        <span>Laporan</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
