<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pop-up Verifikasi Nomor HP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\navbar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\styleTicketing.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\imageLacak.css') }}">
    <link rel="stylesheet" href="{{ asset('assets\css\Ticketing\styleModal.css') }}">
</head>

<body>
    <!-- Modal untuk Nomor HP -->
    <div class="modal fade" id="nomorHpModal" tabindex="-1" aria-labelledby="nomorHpModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="nomorHpModalLabel">Verifikasi Nomor Telepon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="text-muted">Untuk melanjutkan, silakan masukkan nomor telepon aktif Anda.</p>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone-fill"></i></span>
                        <input type="tel" class="form-control" id="inputNomorHp" placeholder="Contoh: 081234567890"
                            required>
                    </div>
                    <small id="nomorHpHelp" class="form-text text-muted mt-1 d-block">
                        Harus terdiri dari 9-15 digit angka.
                    </small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-simpan" onclick="simpanNomorHp()">Verifikasi</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var nomorHpModal = new bootstrap.Modal(document.getElementById('nomorHpModal'));
            nomorHpModal.show();

            const inputNomor = document.getElementById('inputNomorHp');
            inputNomor.addEventListener('input', function (e) {
                e.target.value = e.target.value.replace(/[^0-9]/g, '');
            });
        });

        function simpanNomorHp() {
            const inputElement = document.getElementById('inputNomorHp');
            const helpText = document.getElementById('nomorHpHelp');
            const nomorHp = inputElement.value;

            if (nomorHp.length >= 9 && nomorHp.length <= 15) {
                console.log('Nomor HP yang diverifikasi:', nomorHp);
                var modalInstance = bootstrap.Modal.getInstance(document.getElementById('nomorHpModal'));
                modalInstance.hide();

                inputElement.classList.remove('is-invalid');
                helpText.classList.remove('text-danger');
                helpText.classList.add('text-muted');

            } else {
                inputElement.classList.add('is-invalid');
                helpText.classList.add('text-danger');
                helpText.classList.remove('text-muted');
                console.log('Nomor HP tidak valid. Panjang harus 9-15 digit.');
            }
        }
    </script>
</body>

</html>
