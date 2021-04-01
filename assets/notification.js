

$(document).ready(function() {
    var self = this;
    var notiTabOpened = false;
    var notiCount = window.localStorage.getItem('notiCount');
    if(parseInt(notiCount, 10) > 0) {
        var nodeItems =  window.localStorage.getItem('nodeItems');
        $('.noti-count').html(notiCount);
        $('#nav-noti-count').css('display', 'inline-block');
    }

    $('#noti-tab').click(function() {
        notiTabOpened = true;
        if(notiCount) {
            $('#nav-noti-count').fadeOut('slow');
            $('.noti-title').css('display', 'inline-block');
        }
        $('.noti-container').toggle(300);
        return false;
    });

    $('#box-container').click(function() {
        $('.noti-container').hide();
        notiTabOpened = false;
    });

    $('.noti-container').click(function(evt) {
        evt.stopPropagation();
        return false;
    });

    $('.noti-body .noti-text').on('click', function(evt) {
        addClickListener(evt);
    });

    var addClickListener = function(evt) {
        evt.stopPropagation();
        if(!$(evt.currentTarget).hasClass('has-read')) {
            notiCount--;
            window.localStorage.setItem('notiCount', notiCount);
            $('.noti-count').html(notiCount);
            if(notiCount == 0) {
                $('.noti-title').hide();
            }
            $(evt.currentTarget).addClass('has-read');
        }
    }

    $('.noti-footer').click(function() {
        notiCount = 0;
        window.localStorage.setItem('notiCount', notiCount);
        $('.noti-title').hide();
        $('.noti-text').addClass('has-read');
    });

    window.setInterval(function() {
        var randomStr = Date();
        var childItem = $('<li>').attr('class', 'noti-text').append("Shekhar Kumar commented on " + randomStr);
        childItem = Array.prototype.slice.call(childItem);

        $('.noti-body').prepend(childItem);
        $('.noti-body .noti-text').on('click', function(evt) {
            addClickListener(evt);
        });

        notiCount++;
        $('.noti-count').html(notiCount);

        if(notiTabOpened) {
            $('.noti-title').css('display', 'inline-block');
        } else {
            $('#nav-noti-count').css('display', 'inline-block');
        }

        window.localStorage.setItem('notiCount', notiCount);
        if(window.localStorage.getItem('nodeItems')) {
            childItem.concat(window.localStorage.getItem('nodeItems'));
        }
        window.localStorage.setItem('nodeItems', childItem);
    }, 10000);
});

