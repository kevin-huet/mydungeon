import $ from "jquery";

$(document).isReady(function () {

    setInterval(ajaxNotificationRequest, 60000);
    $(document).on('click', '#count_request', function(){
        ajaxNotificationRequest();
    });

    function ajaxNotificationRequest() {
        let that = $(this);
        console.log("request send")
        $.ajax({
            url: $('#request_url').data('url'),
            type: "POST",
            dataType: "json",
            data: {
            },
            async: true,
            success: function (data)
            {
                console.log("success: "+data)
                $('#request_result').text(data);
            }
        });
        return false;
    }

    $('.send-group-request').click(function(event) {
        event.preventDefault();
        event.stopPropagation();
        $.ajax({
            url: $(this).data('href'),
            type: 'GET',
            dataType: 'html',
        }).done(function (data) {
            $('.modal-content').html(data);
            $('#modal1').modal('open', {
            });
        }).fail(function (jqXHR) {
            console.log(jqXHR.responseText);
        });
    });
});