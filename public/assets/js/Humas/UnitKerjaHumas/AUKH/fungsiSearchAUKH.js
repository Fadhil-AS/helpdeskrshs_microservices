$(document).ready(function() {
    let adminSearchTimeout;

    function fetchAdminData(url, data = {}) {
        $.ajax({
            url: url,
            data: data,
            success: function(response) {
                $('#admin-table-container').html(response);
            },
            error: function() {
                $('#admin-table-container').html('<div class="text-center p-4 text-danger">Gagal memuat data admin.</div>');
            }
        });
    }

    $('#search-admin-input').on('keyup', function() {
        clearTimeout(adminSearchTimeout);
        const query = $(this).val();
        const url = $(this).data('url');

        adminSearchTimeout = setTimeout(function() {
            fetchAdminData(url, { search_admin: query });
        }, 500);
    });

    $(document).on('click', '#admin-table-container .pagination a', function(event) {
        event.preventDefault();
        const url = $(this).attr('href');

        $.ajax({
            url: url,
            success: function(response) {
                $('#admin-table-container').html(response);
            }
        });
    });
});
