<!-- Navbar -->
<nav class="navbar sticky-top navbar-expand-lg bg-body-tertiary">
    <div class="container-lg d-flex justify-content-between align-items-center">
        <!-- Logo -->
        <a class="navbar-brand" href="dashboardAdmin.html">
            <img src="{{ asset('assets/images/logoRSHS.png') }}" alt="Logo" height="40" />
        </a>
        <!-- Tombol -->
        <a href="{{ route('humas.pelaporan-humas') }}"
            class="nav-link text-dark fw-semibold d-flex align-items-center nav-admin-link">
            Halaman Admin
            <i class="bi bi-arrow-right ms-2"></i> </a>
    </div>
</nav>

<script>
    fetch("navbarAdmin.html")
        .then((response) => response.text())
        .then((data) => {
            document.getElementById("navbar-admin").innerHTML = data;

            const currentPage = window.location.pathname.split("/").pop();
            const navLinks = document.querySelectorAll(".nav-link");
            navLinks.forEach((link) => {
                if (link.getAttribute("href") === currentPage) {
                    link.classList.add("active");
                }
            });
        })
        .catch((error) => console.error("Error loading the navbar:", error));
</script>
