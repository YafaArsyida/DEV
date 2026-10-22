<div class="app-menu navbar-menu">
    <div class="navbar-brand-box">
        {{-- <a href="{{ route('ppdb.dashboard') }}" class="logo logo-dark">
            <span class="logo-sm"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="30"></span>
            <span class="logo-lg"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="40"></span>
        </a>
        <a href="{{ route('ppdb.dashboard') }}" class="logo logo-light">
            <span class="logo-sm"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="30"></span>
            <span class="logo-lg"><img src="{{ asset('assets/logo/logo.jpg') }}" alt="{{ config('app.name') }}" height="40"></span>
        </a> --}}
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
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover" aria-label="Perkecil navigasi">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid" style="max-width: 100%">
            <div id="two-column-menu"></div>
                <ul class="navbar-nav" id="navbar-nav">
                    <li class="menu-title">
                        <span>Menu Pendaftar</span>
                    </li>

                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a href="{{ route('ppdb.dashboard', ['jenjang' => request()->route('jenjang')]) }}"
                            class="nav-link menu-link {{ request()->routeIs('ppdb.dashboard') ? 'active' : '' }}">
                            <i class="ri-dashboard-line"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    {{-- Pilih Jalur --}}
                    <li class="nav-item">
                        <a href="{{ route('ppdb.pendaftaran.jalur', ['jenjang' => request()->route('jenjang')]) }}"
                            class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.jalur') ? 'active' : '' }}">
                            <i class="ri-route-line"></i>
                            <span>Pilih Jalur</span>
                        </a>
                    </li>

                    {{-- Data Siswa --}}
                    <li class="nav-item">
                        <a href="{{ route('ppdb.pendaftaran.data-siswa', ['jenjang' => request()->route('jenjang')]) }}"
                            class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.data-siswa') ? 'active' : '' }}">
                            <i class="ri-user-3-line"></i>
                            <span>Data Siswa</span>
                        </a>
                    </li>

                    {{-- Orang Tua/Wali --}}
                    <li class="nav-item">
                        <a href="{{ route('ppdb.pendaftaran.orang-tua', ['jenjang' => request()->route('jenjang')]) }}"
                            class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.orang-tua') ? 'active' : '' }}">
                            <i class="ri-parent-line"></i>
                            <span>Orang Tua/Wali</span>
                        </a>
                    </li>

                    {{-- Alamat --}}
                    <li class="nav-item">
                        <a href="{{ route('ppdb.pendaftaran.alamat', ['jenjang' => request()->route('jenjang')]) }}"
                            class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.alamat') ? 'active' : '' }}">
                            <i class="ri-map-pin-line"></i>
                            <span>Alamat</span>
                        </a>
                    </li>

                    {{-- Pendidikan --}}
                    <li class="nav-item">
                        <a href="{{ route('ppdb.pendaftaran.pendidikan', ['jenjang' => request()->route('jenjang')]) }}"
                            class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.pendidikan') ? 'active' : '' }}">
                            <i class="ri-school-line"></i>
                            <span>Pendidikan</span>
                        </a>
                    </li>

                    {{-- Berkas --}}
                    <li class="nav-item">
                        <a href="{{ route('ppdb.pendaftaran.berkas', ['jenjang' => request()->route('jenjang')]) }}"
                            class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.berkas') ? 'active' : '' }}">
                            <i class="ri-file-paper-2-line"></i>
                            <span>Berkas</span>
                        </a>
                    </li>

                    {{-- Review --}}
                    <li class="nav-item">
                        <a href="{{ route('ppdb.pendaftaran.review', ['jenjang' => request()->route('jenjang')]) }}"
                            class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.review') ? 'active' : '' }}">
                            <i class="ri-checkbox-circle-line"></i>
                            <span>Review</span>
                        </a>
                    </li>

                    <li class="menu-title">
                        <span>Informasi</span>
                    </li>

                    {{-- Status Pendaftaran --}}
                    <li class="nav-item">
                        <a href="{{ route('ppdb.pendaftaran.status', ['jenjang' => request()->route('jenjang')]) }}"
                            class="nav-link menu-link {{ request()->routeIs('ppdb.pendaftaran.status') ? 'active' : '' }}">
                            <i class="ri-check-double-line"></i>
                            <span>Status Pendaftaran</span>
                        </a>
                    </li>
                </ul>

        </div>
    </div>

    <div class="sidebar-background"></div>
</div>
