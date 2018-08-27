$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
    }
});

function toggleChat()
{
    $('.chat').toggleClass('toggle-chat');
    $('.arrow').toggleClass('arrow-up');
}

function scrollBottom()
{
    var boxChat = $('.box-chat');
    boxChat[0].scrollTop = boxChat[0].scrollHeight;
}

function appendMessage(content) {
    var message = `<div class="message-div">
                        <div class="col-sm-8 float-left">
                            <p class="message to-message">${ content }</p>
                        </div>
                    </div>`;
    $('.box-chat').append(message);
    scrollBottom();
}

function sendMessage() {
    var msgContent = $('#message-content').val();
    appendMessage(msgContent);
    var from = $('#from_id').val();
    var to = $('#to_id').val();
    $('#message-content').val('');
    var url = $('#message-content').attr('data-url');
    $.ajax({
        url: url, 
        method: 'post',
        data: {
            message: msgContent,
            from: from,
            to: to
        },
        success: function (result) {
        }
    });
}

$(document).on('keypress', '#message-content', function (event) {
    if (event.which == 13) {
        sendMessage();
    }
}) 

$(document).ready(function () {
    var count = parseInt($('#chat-num').text());
    $('.box-chat').click(function () {
        if(count == 0) return;
        var url = $(this).attr('data-url');
        $.ajax({
            method: 'post',
            url: url,
            success: function (result) {
                $('.chat-title').html('Chat(0)');
                count = 0;
            }
        })
    });
    scrollBottom();
    window.Echo.private('user.' + $('#channel-id').val())
        .listen('MessagePosted', (data) => {
            count = count + 1;
            var chat = 'Chat(' + count + ')';
            $('.chat-title').html(chat);
            var message = `<div class="message-div">
                                <div class="col-sm-8 float-right">
                                    <p class="message from-message">${ data.message.message }</p>
                                </div>
                            </div>`;
            $('.box-chat').append(message);
            scrollBottom();
        });
});
