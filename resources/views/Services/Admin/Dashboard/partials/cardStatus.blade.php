<div class="container my-4">
    <div class="row g-3">

        {{-- 1. Card Open --}}
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body text-center">
                    <div class="fs-2 text-primary opacity-75">
                        <i class="bi bi-play-circle"></i>
                    </div>
                    <h2 class="card-title fw-bold mt-2 mb-1 text-primary">
                        {{ $countOpen ?? '0' }}
                    </h2>
                    <p class="mb-0 fw-semibold text-muted">Open</p>
                </div>
            </div>
        </div>

        {{-- 2. Card On Progress (Klarifikasi Belum) --}}
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body text-center">
                    <div class="fs-2 text-warning opacity-75">
                        <i class="bi bi-clock-history"></i>
                    </div>
                    <h2 class="card-title fw-bold mt-2 mb-1 text-warning">
                        {{ $countKlarifikasiBelum ?? '0' }}
                    </h2>
                    <p class="mb-0 fw-semibold text-muted">On Progress belum klarifikasi</p>
                </div>
            </div>
        </div>

        {{-- 3. Card On Progress (Klarifikasi Sudah) --}}
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body text-center">
                    <div class="fs-2 text-success opacity-75">
                        <i class="bi bi-check2-circle"></i>
                    </div>
                    <h2 class="card-title fw-bold mt-2 mb-1 text-success">
                        {{ $countKlarifikasiSudah ?? '0' }}
                    </h2>
                    <p class="mb-0 fw-semibold text-muted">On Progress sudah klarifikasi</p>
                </div>
            </div>
        </div>

        {{-- 4. Card Menunggu Konfirmasi --}}
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body text-center">
                    <div class="fs-2 text-info opacity-75">
                        <i class="bi bi-pause-circle"></i>
                    </div>
                    <h2 class="card-title fw-bold mt-2 mb-1 text-info">
                        {{ $countMenunggu ?? '0' }}
                    </h2>
                    <p class="mb-0 fw-semibold text-muted">Menunggu Konfirmasi</p>
                </div>
            </div>
        </div>

        {{-- 5. Card Close --}}
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body text-center">
                    <div class="fs-2 text-secondary opacity-75">
                        <i class="bi bi-archive"></i>
                    </div>
                    <h2 class="card-title fw-bold mt-2 mb-1 text-secondary">
                        {{ $countClose ?? '0' }}
                    </h2>
                    <p class="mb-0 fw-semibold text-muted">Close</p>
                </div>
            </div>
        </div>

        {{-- 6. Card Banding --}}
        <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card shadow border-0 h-100">
                <div class="card-body text-center">
                    <div class="fs-2 text-danger opacity-75">
                        <i class="bi bi-shield-exclamation"></i>
                    </div>
                    <h2 class="card-title fw-bold mt-2 mb-1 text-danger">
                        {{ $countBanding ?? '0' }}
                    </h2>
                    <p class="mb-0 fw-semibold text-muted">Banding</p>
                </div>
            </div>
        </div>

    </div>
</div>
