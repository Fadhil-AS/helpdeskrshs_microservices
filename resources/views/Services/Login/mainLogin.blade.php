@extends('Services.Login.layouts.headingLogin')

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
            <img src="{{ asset('assets/images/iconRSHS.jpg') }}" alt="Logo RS Hasan Sadikin" class="logo-rs">
            <h4 class="mb-1" style="color: #00796B;">RS Hasan Sadikin Bandung</h4>
            <p class="subtitle mb-4">Sistem Informasi Pengaduan dan Manajemen</p>

            @if ($errors->any())
                <div class="alert alert-danger" role="alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('auth.login.submit') }}">
                @csrf
                <div class="mb-3 text-start">
                    <label for="USERNAME" class="form-label">Username</label>
                    <input type="text" class="form-control form-control-lg" id="username" name="USERNAME"
                        placeholder="Masukkan Username" value="{{ old('username') }}" required>
                </div>
                <div class="mb-4 text-start">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-lg" id="password" name="password"
                            placeholder="Masukkan Password" required>
                        <span class="input-group-text password-addon" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </span>
                    </div>

                    {{-- <div class="text-end mt-2">
                        <a href="{{ route('lupaPassword') }}" class="forgot-password-link" style="font-size: 0.9rem;">Lupa Password?</a>
                    </div> --}}
                </div>
                <div class="d-grid">
                    <button type="submit" class="btn btn-login btn-lg text-white">
                        Login <i class="bi bi-box-arrow-in-right ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('assets/js/Login/fungsiPassword.js') }}"></script>

</body>

</html>
