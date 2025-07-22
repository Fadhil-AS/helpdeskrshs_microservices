<!-- Navbar -->

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-lg d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <a class="navbar-brand" href="dashboardUnitKerja.html">
            <img src="{{ asset('assets/images/logoRSHS.png') }}" alt="Logo" height="40" />
        </a>

        <div class="collapse navbar-collapse d-none d-lg-flex justify-content-between">
            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('admin.dashboard*') ? 'active' : '' }}"
                        href="{{ route('admin.dashboard') }}">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('unitKerja.dashboard*') ? 'active' : '' }}"
                        href="{{ route('unitKerja.dashboard') }}">Tabel Unit Kerja</a>
                </li>
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
                        <p class="dropdown-item-text px-3">{{ session('user')->name ?? 'Pengguna' }}</p>
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
