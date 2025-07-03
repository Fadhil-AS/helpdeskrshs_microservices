$(document).ready(function() {
    let searchTimeout;

    function fetchData(url, data = {}) {
        $.ajax({
            url: url,
            data: data,
            success: function(response) {
                $('#unit-kerja-container').html(response);
            },
            error: function() {
                $('#unit-kerja-container').html('<div class="text-center p-4 text-danger">Gagal memuat data.</div>');
            }
        });
    }

    $('#search-unit-kerja').on('keyup', function() {
        clearTimeout(searchTimeout);
        const query = $(this).val();
        const baseUrl = $(this).data('url');

        searchTimeout = setTimeout(function() {
            fetchData(baseUrl, { search: query });
        }, 500);
    });

    $(document).on('click', '#unit-kerja-container .pagination a', function(event) {
        event.preventDefault();
        const url = $(this).attr('href');
        fetchData(url);
    });

    $(document).on('click', '.parent-row', function(event) {
        if ($(event.target).closest('a, button').length > 0) {
            return;
        }

        if (!$('#search-unit-kerja').val()) {
            $(this).find('.toggle-icon').toggleClass('expanded');
            var childClass = $(this).data('child');
            $('.' + childClass).toggleClass('hidden-row');
        }
    });
});
