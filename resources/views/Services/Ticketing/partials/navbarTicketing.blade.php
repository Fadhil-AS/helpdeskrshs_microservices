<!-- Navbar -->
<link rel="stylesheet" href="{{ asset('assets\css\Ticketing\navbar.css') }}">

<nav class="navbar sticky-top navbar-expand-lg bg-body-tertiary">
    <div class="container-lg d-flex justify-content-between align-items-center">
        <!-- Logo -->
        {{-- <a class="navbar-brand" href="{{ route('pasien.dashboard') }}">
            <img src="{{ asset('assets/images/logoRSHS.png') }}" alt="Logo" height="40" />
        </a> --}}
        <a class="navbar-brand" href="{{ url('/ticketing/pasien') }}">
            <img src="{{ asset('assets/images/logoRSHS.png') }}" alt="Logo" height="40" />
        </a>
    </div>
</nav>
