$(document).ready(function() {
    $('#filterStatus').on('change', function() {
        $('#filterForm').submit();
    });


    $('#resetFilter').on('click', function() {
        var resetUrl = $(this).data('url');
        window.location.href = resetUrl;
    });
});
