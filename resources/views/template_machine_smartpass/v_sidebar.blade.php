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
            
                <!-- ================= DASHBOARD ================= -->
                <li class="nav-item">
                    <a href="{{ route('smartPass.dashboard') }}"
                        class="nav-link menu-link {{ request()->routeIs('smartPass.dashboard') ? 'active' : '' }}">
                        <i class="mdi mdi-view-dashboard-outline"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
            
                <!-- ================= OPERASIONAL ================= -->
                <li class="menu-title"><span>Operasional</span></li>
            
                <li class="nav-item">
                    <a href="{{ route('smartPass.presensi.pegawai') }}"
                        class="nav-link menu-link {{ request()->routeIs('smartPass.presensi.pegawai*') ? 'active' : '' }}">
                        <i class="mdi mdi-card-account-details-outline"></i>
                        <span>Presensi Pegawai</span>
                    </a>
                </li>
            
                {{-- <li class="nav-item">
                    <a href="{{ route('smartPass.presensi.pegawai') }}"
                        class="nav-link menu-link {{ request()->routeIs('smartPass.presensi.pegawai*') ? 'active' : '' }}">
                        <i class="mdi mdi-card-account-details"></i>
                        <span>Presensi Siswa</span>
                    </a>
                </li>
             --}}
                <!-- ================= MASTER DATA ================= -->
                <li class="menu-title"><span>Adminnistrasi</span></li>
            
                {{-- <li class="nav-item">
                    <a href="{{ route('administrasi.kelas-siswa') }}"
                        class="nav-link menu-link {{ request()->routeIs('administrasi.kelas-siswa*') ? 'active' : '' }}">
                        <i class="mdi mdi-account-school-outline"></i>
                        <span>Data Siswa</span>
                    </a>
                </li> --}}
            
                <li class="nav-item">
                    <a href="{{ route('smartPass.administrasi.pegawai') }}"
                        class="nav-link menu-link {{ request()->routeIs('smartPass.administrasi.pegawai*') ? 'active' : '' }}">
                        <i class="mdi mdi-account-tie-outline"></i>
                        <span>Kepegawaian</span>
                    </a>
                </li>
            
                <!-- ================= LAPORAN ================= -->
                <li class="menu-title"><span>Laporan</span></li>
            
                <li class="nav-item">
                    <a href="{{ route('smartPass.laporan.pegawai') }}"
                        class="nav-link menu-link {{ request()->routeIs('smartPass.laporan.pegawai*') ? 'active' : '' }}">
                        <i class="mdi mdi-file-chart-outline"></i>
                        <span>Presensi Pegawai</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('smartPass.laporan-fingerspot.pegawai') }}"
                        class="nav-link menu-link {{ request()->routeIs('smartPass.laporan-fingerspot.pegawai*') ? 'active' : '' }}">
                        <i class="mdi mdi-file-chart-outline"></i>
                        <span>FingerSpot Pegawai</span>
                    </a>
                </li>
            
                <li class="nav-item">
                    <a href="{{ route('smartPass.laporan.pegawai') }}"
                        class="nav-link menu-link {{ request()->routeIs('smartPass.laporan.pegawai*') ? 'active' : '' }}">
                        <i class="mdi mdi-file-chart"></i>
                        <span>Presensi Siswa</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>