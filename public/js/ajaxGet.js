function ajaxGet(url, content) {
    content = content || '.content';
    console.log(content);
    $.ajax({
        url: url,
        method: 'GET',
        success: function (result) {
            $(content).html(result);
        },
        error: function(xhr, status, error) {
            alert(error);
        },
    });
}

$(document).on('click', 'a.page-link', function (event) {
    event.preventDefault();
    var url = $(this).attr('href');
    var content = url.indexOf('admin/users') != -1 ? '.table-user' : '.content';
    ajaxGet(url, content);
});
