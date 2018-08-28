function destroy(url, message) {
    message = message || 'Do you really want to delete this item?';
    if(!confirm(message)) return;
    $.ajax({
        url: url,
        method: 'post',
        data: {_method: 'delete'},
        success: function (result) {
            $('.content').html(result);
        }
    })
}
