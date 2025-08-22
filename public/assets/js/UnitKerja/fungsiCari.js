$(document).ready(function() {

    let searchTimeout = null; // Variabel untuk menyimpan timer

    // Fungsi untuk mengambil data baru melalui AJAX
    function fetchData(url) {
        $.ajax({
            url: url,
            success: function(data) {
                // Ganti konten tabel dan paginasi dengan data baru dari server
                $('#complaint-table-body').html(data.table);
                $('#pagination-links').html(data.pagination);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Terjadi error saat mengambil data: " + textStatus, errorThrown);
            }
        });
    }

    // Event listener untuk input pencarian
    $('#search-input').on('keyup', function() {
        // Hapus timer sebelumnya jika pengguna masih mengetik
        clearTimeout(searchTimeout);

        const form = $('#filter-form');
        // Buat URL lengkap dengan parameter filter & pencarian
        const url = form.attr('action') + '?' + form.serialize();

        // Atur timer baru. Request hanya akan dikirim 500ms setelah pengguna berhenti mengetik.
        // Ini mencegah pengiriman request untuk setiap huruf yang diketik.
        searchTimeout = setTimeout(function() {
            fetchData(url);
        }, 500);
    });

    // Event listener untuk klik pada link pagination
    // Menggunakan 'event delegation' karena elemen pagination akan diganti secara dinamis
    $(document).on('click', '#pagination-links .pagination a', function(e) {
        e.preventDefault(); // Mencegah browser me-reload halaman
        const url = $(this).attr('href');
        fetchData(url);
    });

});
