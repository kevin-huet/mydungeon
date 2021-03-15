
import './styles/app.css';
import $ from 'jquery';
import Chart from 'chart.js';
import {
    Modal,
    Dropdown
} from 'materialize-css';


$(document).ready(function() {

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

    let ctx = document.getElementById('player_notation');
    let myDoughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
            datasets: [{
                label: '# of Votes',
                data: [12, 19, 3, 5, 2, 3],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
        }
    });


    M.Modal.init($('.modal'), {});
    M.Dropdown.init($('.dropdown-trigger'), { constrainWidth: false, coverTrigger: false});

    $('.show-group').click(function(event) {
        event.preventDefault();
        event.stopPropagation();
        $.ajax({
            url: $(this).attr('href'),
            type: 'GET',
            dataType: 'html',
        }).done(function (data) {
            $('.modal-content').html(data);
            $('#modal1').modal('open');
        }).fail(function (jqXHR) {
            console.log(jqXHR.responseText);
        });
    });

    $('.send-group-request').click(function(event) {
        event.preventDefault();
        event.stopPropagation();
        $.ajax({
            url: $(this).attr('href'),
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
