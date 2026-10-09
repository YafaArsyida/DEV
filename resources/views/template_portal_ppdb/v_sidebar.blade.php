<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        <a href="{{ route('ppdb.dashboard') }}" class="logo logo-dark">
            <span class="logo-sm"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="30"></span>
            <span class="logo-lg"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="40"></span>
        </a>
        <a href="{{ route('ppdb.dashboard') }}" class="logo logo-light">
            <span class="logo-sm"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="30"></span>
            <span class="logo-lg"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="40"></span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover" aria-label="Perkecil navigasi">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid" style="max-width: 100%">
            <div id="two-column-menu"></div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>Menu Pendaftar</span></li>

                <li class="nav-item">
                    <a href="{{ route('ppdb.dashboard') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.dashboard') ? 'active' : '' }}">
                        <i class="ri-dashboard-line"></i><span>Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ppdb.pendaftaran.data-siswa') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.data-siswa') ? 'active' : '' }}">
                        <i class="ri-user-3-line"></i><span>Data Siswa</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ppdb.pendaftaran.orang-tua') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.orang-tua') ? 'active' : '' }}">
                        <i class="ri-parent-line"></i><span>Orang Tua/Wali</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ppdb.pendaftaran.alamat') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.alamat') ? 'active' : '' }}">
                        <i class="ri-map-pin-line"></i><span>Alamat</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ppdb.pendaftaran.pendidikan') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.pendidikan') ? 'active' : '' }}">
                        <i class="ri-school-line"></i><span>Pendidikan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ppdb.pendaftaran.berkas') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.berkas') ? 'active' : '' }}">
                        <i class="ri-file-paper-2-line"></i><span>Berkas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ppdb.pendaftaran.review') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.review') ? 'active' : '' }}">
                        <i class="ri-clipboard-check-line"></i><span>Review</span>
                    </a>
                </li>

                <li class="menu-title"><span>Informasi</span></li>
                <li class="nav-item">
                    <a href="{{ route('ppdb.status') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.status') ? 'active' : '' }}">
                        <i class="ri-badge-check-line"></i><span>Status Pendaftaran</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('ppdb.bukti-pendaftaran') }}" class="nav-link menu-link {{ request()->routeIs('ppdb.bukti-pendaftaran') ? 'active' : '' }}">
                        <i class="ri-file-list-3-line"></i><span>Bukti Pendaftaran</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
