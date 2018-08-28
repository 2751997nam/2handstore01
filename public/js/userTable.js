function show(url, id) {
    $.ajax({
        url: url,
        method: 'GET',
        data: {id: id},
        success: function (result) {
            $('#modal-body').html(result);
        }
    });
}

function block(url, id, message) {
    message = message || 'Do you really want to block this user?';
    if(!confirm(message)) return;
    var option = $('.btn-warning').attr('data-option');
    $.ajax({
        url: url,
        method: 'POST',
        data: {id: id, option: option},
        success: function (result) {
            $('.table-user').html(result);
        }
    });
}

function showOption(url, target) {
    $('.show-option').removeClass('btn-warning');
    $(target).addClass('btn-warning');
    ajaxGet(url, '.table-user');
}
