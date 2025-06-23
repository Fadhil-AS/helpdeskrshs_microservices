<!-- Navbar -->

<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container-lg d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <a class="navbar-brand" href="dashboardUnitKerja.html">
            <img src="{{ asset('assets/images/logoRSHS.png') }}" alt="Logo" height="40" />
        </a>

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
</nav>
