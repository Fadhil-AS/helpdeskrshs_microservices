<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pop-up Daftar Laporan</title>
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
    <!-- Modal untuk Daftar Laporan -->
    <div class="modal fade" id="daftarLaporanModal" tabindex="-1" aria-labelledby="daftarLaporanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="daftarLaporanModalLabel">Pilih Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="laporanListContainer" class="list-group mb-3">
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Batal</button>
                    <nav aria-label="Page navigation">
                        <ul class="pagination page-tabel mb-0" id="paginationContainer">
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // --- DATA DUMMY ---
        const allLaporan = [
            { id: 'TIKET-001', status: 'Selesai', tgl: '3 hari lalu', judul: 'Masalah koneksi internet di gedung A.' },
            { id: 'TIKET-002', status: 'Dalam Proses', tgl: '1 hari lalu', judul: 'Permintaan instalasi software baru.' },
            { id: 'TIKET-003', status: 'Baru', tgl: '2 jam lalu', judul: 'Layar monitor bergaris.' },
            { id: 'TIKET-004', status: 'Selesai', tgl: '5 hari lalu', judul: 'Reset password akun email.' },
            { id: 'TIKET-005', status: 'Selesai', tgl: '1 minggu lalu', judul: 'Printer tidak terdeteksi.' },
            { id: 'TIKET-006', status: 'Dalam Proses', tgl: 'Kemarin', judul: 'Pembaruan data pada sistem internal.' },
            { id: 'TIKET-007', status: 'Baru', tgl: '10 menit lalu', judul: 'Tidak bisa login ke aplikasi.' },
        ];

        // --- PENGATURAN PAGINATION ---
        let currentPage = 1;
        const itemsPerPage = 3;
        let modalInstance = null;

        // --- FUNGSI-FUNGSI ---
        function renderLaporan(page = 1) {
            currentPage = page;
            const container = document.getElementById('laporanListContainer');
            container.innerHTML = ''; // Kosongkan container

            const startIndex = (page - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const paginatedItems = allLaporan.slice(startIndex, endIndex);

            paginatedItems.forEach(laporan => {
                const statusClass = laporan.status === 'Selesai' ? 'status-selesai' : (laporan.status === 'Dalam Proses' ? 'status-proses' : 'status-baru');
                const itemHtml = `
                    <a href="#" class="list-group-item list-group-item-action" onclick="pilihLaporan('${laporan.id}')">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 fw-bold">${laporan.id}</h6>
                            <small class="text-muted">${laporan.tgl}</small>
                        </div>
                        <p class="mb-1">${laporan.judul}</p>
                        <span class="status-badge ${statusClass}">${laporan.status}</span>
                    </a>`;
                container.innerHTML += itemHtml;
            });
        }

        function renderPagination() {
            const paginationContainer = document.getElementById('paginationContainer');
            paginationContainer.innerHTML = '';
            const pageCount = Math.ceil(allLaporan.length / itemsPerPage);

            const isFirstPage = currentPage === 1;
            const isLastPage = currentPage === pageCount;

            // Tombol "Pertama" (<<)
            paginationContainer.innerHTML += `
                <li class="page-item ${isFirstPage ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(1)" aria-label="First">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>`;

            // Tombol "Sebelumnya" (<)
            paginationContainer.innerHTML += `
                <li class="page-item ${isFirstPage ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage - 1})" aria-label="Previous">
                        <span aria-hidden="true">&lsaquo;</span>
                    </a>
                </li>`;

            // Tombol Halaman Angka
            for (let i = 1; i <= pageCount; i++) {
                const activeClass = i === currentPage ? 'active' : '';
                paginationContainer.innerHTML += `
                    <li class="page-item ${activeClass}">
                        <a class="page-link" href="#" onclick="changePage(${i})">${i}</a>
                    </li>`;
            }

            // Tombol "Berikutnya" (>)
            paginationContainer.innerHTML += `
                <li class="page-item ${isLastPage ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${currentPage + 1})" aria-label="Next">
                        <span aria-hidden="true">&rsaquo;</span>
                    </a>
                </li>`;

            // Tombol "Terakhir" (>>)
            paginationContainer.innerHTML += `
                <li class="page-item ${isLastPage ? 'disabled' : ''}">
                    <a class="page-link" href="#" onclick="changePage(${pageCount})" aria-label="Last">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>`;
        }

        function changePage(page) {
            event.preventDefault();
            const pageCount = Math.ceil(allLaporan.length / itemsPerPage);
            if (page < 1 || page > pageCount) {
                return;
            }
            renderLaporan(page);
            renderPagination();
        }

        function pilihLaporan(ticketId) {
            event.preventDefault();
            console.log('Laporan yang dipilih:', ticketId);
            if(modalInstance) modalInstance.hide();
            alert('Anda memilih laporan ' + ticketId + '.');
        }

        document.addEventListener('DOMContentLoaded', function () {
            modalInstance = new bootstrap.Modal(document.getElementById('daftarLaporanModal'));

            renderLaporan(1);
            renderPagination();

            modalInstance.show();
        });
    </script>
</body>
</html>
