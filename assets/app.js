
import './styles/app.css';
import $ from 'jquery';
import {
    Modal,
    Dropdown
} from 'materialize-css';


$(document).ready(function() {
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
