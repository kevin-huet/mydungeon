
import './styles/app.css';
import './styles/alert.css';

import $ from 'jquery';
import Chart from 'chart.js';
import {
    Modal,
    Dropdown,
    Sidenav
} from 'materialize-css';
import * as data from './test.json';


$(document).ready(function() {

    console.log(data);

    M.Modal.init($('.modal'), {});
    M.Dropdown.init($('.dropdown-trigger'), { constrainWidth: false, coverTrigger: false});
    M.Sidenav.init($('.sidenav'));
    $('.show-group').click(function(event) {
        event.preventDefault();
        event.stopPropagation();
        $.ajax({
            url: $(this).data('href'),
            type: 'GET',
            dataType: 'html',
        }).done(function (data) {
            $('.modal-content').html(data);
            $('#modal1').modal('open');
        }).fail(function (jqXHR) {
            console.log(jqXHR.responseText);
        });
    });
});
