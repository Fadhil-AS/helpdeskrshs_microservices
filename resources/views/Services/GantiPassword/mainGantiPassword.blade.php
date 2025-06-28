@extends('Services.GantiPassword.layouts.headingGantiPassword')

<body>
    <!-- Navbar -->
    <nav class="navbar sticky-top navbar-expand-lg bg-body-tertiary">
        <div class="container-lg d-flex justify-content-between align-items-center">
            <!-- Logo -->
            <a class="navbar-brand" href="">
                <img src="{{ asset('assets/images/logoRSHS.png') }}" alt="Logo" height="40" />
            </a>
        </div>
    </nav>

    <!-- Hiasan Sudut -->
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan top-right" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-left" />
    <img src="{{ asset('assets/images/Hiasan_Layar.png') }}" class="hiasan bottom-right" />

    <div class="login-container">
        <div class="login-card text-center">
            <h4 class="mb-1" style="color: #00796B;">Ganti Password</h4>
            <p class="subtitle mb-4">Ganti password dengan mengisi form dibawah.</p>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('auth.login.submit') }}">
                @csrf
                {{-- Field Password Baru --}}
                <div class="mb-3 text-start">
                    <label for="newPass" class="form-label">Password baru</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-lg" id="newPass" name="NEWPASS"
                            placeholder="Masukkan Password Baru" required>
                        <span class="input-group-text password-addon" id="toggleNewPassword">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>

                {{-- Field Konfirmasi Password --}}
                <div class="mb-4 text-start">
                    <label for="confPass" class="form-label">Konfirmasi password</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-lg" id="confPass" name="CONFPASS"
                            placeholder="Konfirmasi Password" required>
                        <span class="input-group-text password-addon" id="toggleConfPassword">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-login btn-lg text-white">
                        Simpan <i class="bi bi-box-arrow-in-right ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/js/GantiPassword/fungsiPassword.js') }}"></script>

</body>

</html>
