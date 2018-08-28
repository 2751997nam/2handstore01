$(document).ready(function () {
    $('.item-thumb').mouseenter(function () {
        var show = $(this).attr('src');
        var index = parseInt($('.item-thumb').index(this));
        $('.dot').removeClass('active');
        index++;
        $('.dot:nth-child(' + index + ')').addClass('active');
        $('#show').attr('src', show);
    });
    $('.suggested-carousel').owlCarousel({
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
                loop: true,
            }
        }
    });
    $('.product-carousel').owlCarousel({
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
                nav: false,
                margin: 10,
                loop: false,
            }
        }
    });
    $('.show-more').click(function() {
        console.log(1);
        $(this).remove();
        $('.product-description-content.mb-0').height('auto');
    });

    $('.order-product').click(function () {
        $('.form-order').submit();
    });
});

function comment(url, element, bottom) {
    bottom = bottom || false;
    var position = bottom === true ? $('.comments')[0].scrollHeight : $('.comments').scrollTop();
    console.log(position);
    var parent_id = $('#parent_id').val();
    var content = $(element).siblings('textarea').val();
    $(element).siblings('textarea').val('');
    $.ajax({
        url: url,
        method: 'post',
        data: {parent_id: parent_id, content: content},
        success: function (result) {
            $('#comments').html(result);
            $('.comments').delay(50).scrollTop(position);
        }
    })
}

function reply(element) {
    $("#parent_id").val($(element).attr('data-id'));
    $(element).parents('.comment').append($('#comment-editor').prop('hidden', false));
}

function deleteComment(url, id) {
    let position = $('.comments').scrollTop();
    $.ajax({
        url: url,
        method: 'post',
        data: {id: id, _method: 'delete'},
        success: function (result) {
            $('#comments').html(result);
            $('.comments').delay(50).scrollTop(position);
        }
    })
}

function report(url) {
    let type = $('#type').val();
    let content = $('#report-content').val();
    $.ajax({
        url: url,
        method: 'POST',
        data: {type: type, content: content},
        success: function (result) {
            $('#type').val(1);
            $('#report-content').val('');
        }
    });
}
