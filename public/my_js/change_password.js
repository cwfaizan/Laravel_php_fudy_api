$(document).ready(function () {
    $('#button_change_password').on('click', function () {
        $.ajax({
            type: "POST",
            url: '/change-password',
            data: $('#change_password_form').serialize(),
            beforeSend: function () {
                $('p').text('');
            },
            statusCode: {
                422: function (response) {
                    $.each(response.responseJSON.errors, function (key, value) {
                        $('#error_' + key).text(value[0]);
                    });
                }
            },
            success: function (response) {
                if (response.success) {
                    $('#change_password_form').trigger("reset");
                    $('p').text('');
                    $('#change_password_status').text(response.data.status[0]);
                }
            },
            error: function (response) {
                console.log(response);
            },
            complete: function (e) { }
        });
    });
});