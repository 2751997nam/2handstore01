$(document).ready(function() {
    $('.loadmore').click(function () {
        var $this = $(this);
        $('.loading').show();
        var next = parseInt($(this).attr('data-page')) + 1;
        var url = $(this).attr('data-url');
        $(this).attr('data-page', next);
        $.get({
            url: url,
            data: {
                page: next
            },
            success: function (data) {
                $('.loading').hide();
                if (data['lastpage'] != undefined && data['lastpage'] == true) {
                    $this.hide();
                    return;
                }
                $('.new_products').append(data);
            },
        });
    });
    $('.categories__carousel').owlCarousel({
        loop: true,
        responsiveClass: true,
        dots: false,
        responsive: {
            0: {
                items: 1,
                nav: true
            },
            600: {
                items: 3,
                nav: false
            },
            1000: {
                items: 8,
                nav: true,
                loop: false,
            }
        }
    });

    $('.trending__carousel').owlCarousel({
        loop: true,
        responsiveClass: true,
        dots: false,
        responsive: {
            0: {
                items: 1,
                nav: true
            },
            600: {
                items: 3,
                nav: false
            },
            1000: {
                items: 6,
                nav: true,
                margin: 10,
                loop: false,
            }
        }
    });
})
