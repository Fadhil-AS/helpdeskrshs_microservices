$(document).ready(function() {
    $('#searchInput').on('keyup', function() {
        let query = $(this).val();
        let url = $(this).data('search-url');

        $.ajax({
            url: url,
            type: "GET",
            data: {
                'query': query
            },
            success: function(data) {
                $('#direksiTableBody').html(data);
                if (query.length > 0) {
                    $('#pagination-container').hide();
                } else {
                     $('#pagination-container').show();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("AJAX Error: " + textStatus, errorThrown);
            }
        });
    });
});
