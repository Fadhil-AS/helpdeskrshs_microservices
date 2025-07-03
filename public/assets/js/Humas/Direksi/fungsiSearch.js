$(document).ready(function() {
    let searchTimeout;

    function fetchData(url, data = {}) {
        $.ajax({
            url: url,
            data: data,
            success: function(response) {
                $('#direksi-table-container').html(response);
            },
            error: function() {
                $('#direksi-table-container').html('<div class="text-center p-4 text-danger">Gagal memuat data.</div>');
            }
        });
    }

    $('#search-direksi-input').on('keyup', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        const url = $(this).data('url');

        searchTimeout = setTimeout(function() {
            fetchData(url, { search: query });
        }, 500);
    });

    $(document).on('click', '#direksi-table-container .pagination a', function(event) {
        event.preventDefault();
        const url = $(this).attr('href');
        fetchData(url);
    });
});
