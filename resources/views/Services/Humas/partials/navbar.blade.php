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
            {{-- Navigasi di tengah dengan link aktif otomatis --}}
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('humas.pelaporan-humas') ? 'active' : '' }}" href="{{ route('humas.pelaporan-humas') }}">Daftar Pelaporan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('humas.unit-kerja-humas') ? 'active' : '' }}" href="{{ route('humas.unit-kerja-humas') }}">Unit Kerja</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('humas.direksi-humas') ? 'active' : '' }}" href="{{ route('humas.direksi-humas') }}">Direksi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('humas.data-referensi-humas') ? 'active' : '' }}" href="{{ route('humas.data-referensi-humas') }}">Data Referensi</a>
                </li>
            </ul>

            <div class="dropdown">
                <a href="#" class="profile-dropdown-toggle" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person"></i>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2">
                    <li><h6 class="dropdown-header">Selamat Datang,</h6></li>
                    <li><p class="dropdown-item-text px-3">{{ Auth::user()->name ?? 'Nama Pengguna' }}</p></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a href="#" class="dropdown-item">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Logout</span>
                        </a>
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
        <ul class="navbar-nav text-center">
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('humas.pelaporan-humas') ? 'active' : '' }}" href="{{ route('humas.pelaporan-humas') }}">Daftar Pelaporan</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('humas.unit-kerja-humas') ? 'active' : '' }}" href="{{ route('humas.unit-kerja-humas') }}">Unit Kerja</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('humas.direksi-humas') ? 'active' : '' }}" href="{{ route('humas.direksi-humas') }}">Direksi</a></li>
            <li class="nav-item"><a class="nav-link {{ request()->routeIs('humas.data-referensi-humas') ? 'active' : '' }}" href="{{ route('humas.data-referensi-humas') }}">Data Referensi</a></li>
        </ul>

        <div class="mt-auto pt-3">
            <hr class="my-3" />
            <div class="text-center">
                <p class="fw-bold mb-3">{{ Auth::user()->name ?? 'Nama Pengguna' }}</p>
                <a href="#" class="btn btn-danger w-100">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
            </div>
        </div>
    </div>
</div>
