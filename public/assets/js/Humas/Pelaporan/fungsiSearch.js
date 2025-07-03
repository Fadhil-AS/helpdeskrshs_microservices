$(document).ready(function() {
    let searchTimeout;

    function fetchData(url, data) {
        $.ajax({
            url: url,
            data: data,
            success: function(response) {
                $('#tabel-pengaduan-container').html(response);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error:", textStatus, errorThrown);
                $('#tabel-pengaduan-container').html('<p class="text-center text-danger">Gagal memuat data.</p>');
            }
        });
    }

    $('#search-input').on('keyup', function() {
        clearTimeout(searchTimeout);

        const query = $(this).val();

        searchTimeout = setTimeout(function() {
            fetchData(searchUrl, { search: query });
        }, 500);
    });

    $(document).on('click', '.pagination a', function(event) {
        event.preventDefault();
        const url = $(this).attr('href');
        fetchData(url);
    });
});
