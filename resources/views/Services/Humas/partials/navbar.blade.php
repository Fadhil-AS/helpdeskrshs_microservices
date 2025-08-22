<nav class="navbar navbar-expand-lg bg-body-tertiary sticky-top">
    <div class="container-lg">
        <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
            <img src="{{ asset('assets/images/logoRSHS.png') }}" alt="Logo" height="40" />
        </a>

        <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu"
            aria-controls="mobileMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse d-none d-lg-flex justify-content-between" id="navbarContent">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                @if (session('role') === 'humas')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('humas.pelaporan-humas*') ? 'active' : '' }}"
                            href="{{ route('humas.pelaporan-humas') }}">Daftar Pelaporan</a>
                    </li>

                    {{-- Dropdown Manajemen Data --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs(['humas.unit-kerja-humas*', 'humas.direksi-humas*', 'humas.data-referensi-humas*']) ? 'active' : '' }}"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Manajemen Data
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{ request()->routeIs('humas.unit-kerja-humas*') ? 'active' : '' }}"
                                    href="{{ route('humas.unit-kerja-humas') }}">Unit Kerja</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('humas.direksi-humas*') ? 'active' : '' }}"
                                    href="{{ route('humas.direksi-humas') }}">Direksi</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('humas.data-referensi-humas*') ? 'active' : '' }}"
                                    href="{{ route('humas.data-referensi-humas') }}">Data Referensi</a></li>
                        </ul>
                    </li>

                    {{-- Dropdown Pengaturan Sistem --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle {{ request()->routeIs('humas.data-nomor-humas-rshs*', 'humas.upload*', 'humas.pengaturan-ssd-humas*') ? 'active' : '' }}"
                            href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Pengaturan Sistem
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item {{ request()->routeIs('humas.data-nomor-humas-rshs*') ? 'active' : '' }}"
                                    href="{{ route('humas.data-nomor-humas-rshs') }}">Data Nomor WA</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('humas.upload*') ? 'active' : '' }}"
                                    href="{{ route('humas.upload') }}">Pengaturan Chatbot</a></li>
                            <li><a class="dropdown-item {{ request()->routeIs('humas.pengaturan-ssd-humas*') ? 'active' : '' }}"
                                    href="{{ route('humas.pengaturan-ssd-humas') }}">Pengaturan SSD</a></li>
                        </ul>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('humas.data-referensi-humas*') ? 'active' : '' }}"
                            href="{{ route('humas.chatbot') }}">Data Referensi</a>
                    </li> --}}
                @elseif (session('role') === 'unit_kerja')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('unitKerja.dashboard*') ? 'active' : '' }}"
                            href="{{ route('unitKerja.dashboard') }}">Daftar Pelaporan</a>
                    </li>
                @elseif (session('role') === 'direksi')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                @elseif (session('role') === 'spi')
                    {{-- <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}"
                            href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li> --}}
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('spi.pelaporan-SPI*') ? 'active' : '' }}"
                            href="{{ route('spi.pelaporan-SPI') }}">Daftar Pelaporan</a>
                    </li>
                @endif
            </ul>

            <div class="dropdown">
                <a href="#" class="profile-dropdown-toggle" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2">
                    <li>
                        <h6 class="dropdown-header">Selamat Datang,</h6>
                    </li>
                    <li>
                        <p class="dropdown-item-text px-3">{{ session('user')->USERNAME ?? 'Pengguna' }}</p>
                    </li>
                    <li>
                        <hr class="dropdown-divider">
                    </li>
                    <li>
                        <!-- Tombol Logout yang benar -->
                        <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="mobileMenu" aria-labelledby="mobileMenuLabel">
    <div class="offcanvas-header">
        <img src="{{ asset('assets/images/logoRSHS.png') }}" alt="Logo" height="40" />
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column justify-content-between">
        <ul class="navbar-nav text-center gap-2">
            {{-- <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('humas.pelaporan-humas') ? 'active' : '' }}" href="{{ route('humas.pelaporan-humas') }}">Daftar Pelaporan</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('humas.unit-kerja-humas') ? 'active' : '' }}" href="{{ route('humas.unit-kerja-humas') }}">Unit Kerja</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('humas.direksi-humas') ? 'active' : '' }}" href="{{ route('humas.direksi-humas') }}">Direksi</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('humas.data-referensi-humas') ? 'active' : '' }}" href="{{ route('humas.data-referensi-humas') }}">Data Referensi</a></li> --}}
            @if (session('role') === 'humas')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('humas.pelaporan-humas*') ? 'active' : '' }}"
                        href="{{ route('humas.pelaporan-humas') }}">Daftar Pelaporan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('humas.unit-kerja-humas*') ? 'active' : '' }}"
                        href="{{ route('humas.unit-kerja-humas') }}">Unit Kerja</a>
                </li>
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('humas.user-complaint*') ? 'active' : '' }}"
                        href="{{ route('humas.user-complaint.index') }}">Admin Unit Kerja</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('humas.direksi-humas*') ? 'active' : '' }}"
                        href="{{ route('humas.direksi-humas') }}">Direksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('humas.data-referensi-humas*') ? 'active' : '' }}"
                        href="{{ route('humas.data-referensi-humas') }}">Data Referensi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('humas.data-nomor-humas-rshs*') ? 'active' : '' }}"
                        href="{{ route('humas.data-nomor-humas-rshs') }}">Data Nomor WA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">Pengaturan Chatbot</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="">Pengaturan SSD</a>
                </li>
            @elseif (session('role') === 'unit_kerja')
                {{-- <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li> --}}
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('unitKerja.dashboard*') ? 'active' : '' }}"
                        href="{{ route('unitKerja.dashboard') }}">Tabel Unit Kerja</a>
                </li>
            @elseif (session('role') === 'direksi')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
            @elseif (session('role') === 'spi')
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('spi.pelaporan-SPI*') ? 'active' : '' }}"
                        href="{{ route('spi.pelaporan-SPI') }}">Daftar Pelaporan</a>
                </li>
            @endif
        </ul>

        <div class="mt-auto pt-3">
            <hr class="my-3" />
            <div class="text-center">
                <p class="fw-bold mb-3">{{ session('user')->USERNAME ?? 'Pengguna' }}</p>
                <form method="POST" action="{{ route('auth.logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="bi bi-box-arrow-right me-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
