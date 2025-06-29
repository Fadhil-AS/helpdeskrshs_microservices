$(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('body').on('click', '.reset-password-btn', function(e) {
        e.preventDefault();

        var userId = $(this).data('id');
        var userName = $(this).data('name');
        var url = `/humas/userComplaint/${userId}/reset`;

        var confirmation = confirm(`Anda yakin ingin mereset password untuk user "${userName}"?`);

        if (confirmation) {
            $.ajax({
                url: url,
                type: 'PUT',
                success: function(response) {
                    alert(response.message);

                    if (response.success) {
                        location.reload();
                    }
                },
                error: function(xhr) {
                    alert('Terjadi error. Tidak dapat menghubungi server, silakan coba lagi.');
                }
            });
        }
    });
});
