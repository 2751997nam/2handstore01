$(document).ready(function () {
    var channel = $('channel').text();

    var pusher = new Pusher('350836dd3d57ea3538c6', {
        cluster: 'ap1',
        encrypted: true
    });

    var channel = pusher.subscribe('order-chanel' + channel);
    channel.bind('App\\Events\\OrderEvent', function (data) {
        var element = `<a class="notify_link" href="${data['link']}">${data['message']}</a>`;
        $('.notify').append(element);
        setTimeout(function () {
            $('.notify_link').remove();
        }, 20000);
    });
});
