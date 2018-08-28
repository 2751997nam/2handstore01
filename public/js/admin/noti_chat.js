$(document).ready(function () {
    var count = 0;
    window.Echo.private('user.1')
        .listen('MessagePosted', function (data) {
            $('.noti-num').removeClass('d-none');
            $('.noti-num').text(++count);
        });
});
