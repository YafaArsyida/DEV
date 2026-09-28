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
                {{-- DASHBOARD --}}
                <li class="nav-item">

                    <a href="{{ route('akademik.dashboard') }}"
                        class="nav-link menu-link {{ request()->routeIs('akademik.dashboard') ? 'active' : '' }}">

                        <i class="mdi mdi-speedometer"></i>

                        <span data-key="t-dashboard">
                            Dashboard
                        </span>

                    </a>

                </li>


                {{-- Master --}}
                <li class="menu-title">
                    <span data-key="t-akademik">
                        Master
                    </span>
                </li>

                {{-- JENJANG --}}
                <li class="nav-item">
                    <a href="{{ route('akademik.jenjang') }}"
                        class="nav-link menu-link {{ request()->routeIs('akademik.jenjang') ? 'active' : '' }}">
                        <i class="mdi mdi-school-outline"></i>
                        <span data-key="t-jenjang">
                            Jenjang
                        </span>
                    </a>
                </li>

                {{-- TAHUN AJARAN --}}
                <li class="nav-item">
                    <a href="{{ route('akademik.tahun-ajaran') }}"
                        class="nav-link menu-link {{ request()->routeIs('akademik.tahun-ajaran') ? 'active' : '' }}">
                        <i class="mdi mdi-calendar-outline"></i>
                        <span data-key="t-tahun-ajaran">
                            Tahun Ajaran
                        </span>
                    </a>
                </li>

                {{-- KELAS --}}
                <li class="nav-item">
                    <a href="{{ route('akademik.kelas') }}"
                        class="nav-link menu-link {{ request()->routeIs('akademik.kelas') ? 'active' : '' }}">
                        <i class="mdi mdi-home-outline"></i>
                        <span data-key="t-kelas">
                            Kelas
                        </span>
                    </a>
                </li>

                {{-- Mata Pelajaran --}}
                <li class="nav-item">
                    <a href="{{ route('akademik.kelas') }}"
                        class="nav-link menu-link {{ request()->routeIs('akademik.kelas') ? 'active' : '' }}">
                        <i class="mdi mdi-book-open-outline"></i>
                        <span data-key="t-kelas">
                            Mata Pelajaran
                        </span>
                    </a>
                </li>

                {{-- SISWA --}}
                <li class="nav-item">
                    <a href="{{ route('akademik.siswa') }}"
                        class="nav-link menu-link {{ request()->routeIs('akademik.siswa') ? 'active' : '' }}">
                        <i class="mdi mdi-account-school-outline"></i>
                        <span data-key="t-siswa">
                            Siswa
                        </span>
                    </a>
                </li>

                {{-- PENEMPATAN SISWA --}}
                <li class="nav-item">
                    <a href="{{ route('akademik.penempatan-siswa') }}"
                        class="nav-link menu-link {{ request()->routeIs('akademik.penempatan-siswa') ? 'active' : '' }}">
                        <i class="mdi mdi-google-classroom"></i>
                        <span data-key="t-penempatan">
                            Penempatan Siswa
                        </span>
                    </a>
                </li>

                {{-- Guru & Pengajar --}}
                <li class="menu-title">
                    <span data-key="t-akademik">
                        Guru & Pengajar
                    </span>
                </li>
                {{-- DATA GURU --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-account-tie-outline"></i>
                        <span data-key="t-data-guru">
                            Data Guru
                        </span>
                    </a>
                </li>

                {{-- WALI KELAS --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-account-star-outline"></i>
                        <span data-key="t-wali-kelas">
                            Wali Kelas
                        </span>
                    </a>
                </li>

                {{-- PENGAMPU MATA PELAJARAN --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-book-account-outline"></i>
                        <span data-key="t-pengampu-mata-pelajaran">
                            Pengampu Mata Pelajaran
                        </span>
                    </a>
                </li>

                {{-- EKSTRAKURIKULER --}}
                <li class="menu-title">
                    <span data-key="t-ekstrakurikuler">
                        Ekstrakurikuler
                    </span>
                </li>

                {{-- DATA EKSTRAKURIKULER --}}
                <li class="nav-item">
                    <a href="{{ route('akademik.ekstrakurikuler') }}"
                        class="nav-link menu-link {{ request()->routeIs('akademik.ekstrakurikuler') ? 'active' : '' }}">
                        <i class="mdi mdi-run"></i>
                        <span data-key="t-data-ekstrakurikuler">
                            Data Ekstrakurikuler
                        </span>
                    </a>
                </li>

                {{-- PENEMPATAN SISWA --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-account-multiple-plus-outline"></i>
                        <span data-key="t-penempatan-ekstrakurikuler">
                            Penempatan Ekstrakurikuler
                        </span>
                    </a>
                </li>

                {{-- KEGIATAN --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-calendar-check-outline"></i>
                        <span data-key="t-kegiatan-ekstrakurikuler">
                            Kegiatan
                        </span>
                    </a>
                </li>

                {{-- KEHADIRAN --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-clipboard-check-outline"></i>
                        <span data-key="t-kehadiran-ekstrakurikuler">
                            Kehadiran
                        </span>
                    </a>
                </li>

                {{-- PENILAIAN --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-star-check-outline"></i>
                        <span data-key="t-penilaian-ekstrakurikuler">
                            Penilaian
                        </span>
                    </a>
                </li>
                {{-- MATA PELAJARAN --}}
                <li class="menu-title">
                    <span data-key="t-mata-pelajaran">
                        Mata Pelajaran
                    </span>
                </li>

                {{-- PENGAMPU GURU --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-account-tie-outline"></i>
                        <span data-key="t-pengampu-guru">
                            Pengampu Guru
                        </span>
                    </a>
                </li>

                {{-- JADWAL --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-calendar-clock-outline"></i>
                        <span data-key="t-jadwal">
                            Jadwal
                        </span>
                    </a>
                </li>

                {{-- NILAI --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-clipboard-text-outline"></i>
                        <span data-key="t-nilai">
                            Nilai
                        </span>
                    </a>
                </li>

                {{-- RAPOR --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-file-document-edit-outline"></i>
                        <span data-key="t-rapor">
                            Rapor
                        </span>
                    </a>
                </li>

                {{-- LAPORAN AKADEMIK --}}
                <li class="nav-item">
                    <a href="javascript:void(0);"
                        class="nav-link menu-link">
                        <i class="mdi mdi-chart-box-outline"></i>
                        <span data-key="t-laporan-akademik">
                            Laporan Akademik
                        </span>
                    </a>
                </li>
                {{-- LAPORAN --}}
                <li class="menu-title">
                    <span data-key="t-laporan">
                        Laporan
                    </span>
                </li>

                <li class="nav-item">
                    <a href="{{ route('akademik.laporan') }}"
                        class="nav-link menu-link {{ request()->routeIs('akademik.laporan') ? 'active' : '' }}">
                        <i class="mdi mdi-file-chart-outline"></i>
                        <span data-key="t-laporan-akademik">
                            Laporan Akademik
                        </span>
                    </a>
                </li>
    
                {{-- @endif --}}
            </ul>
        </div>
        <!-- Sidebar -->
    </div>

    <div class="sidebar-background"></div>
</div>