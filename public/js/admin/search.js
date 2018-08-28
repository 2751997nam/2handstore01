$(document).on('focusin', '#input-search', function () {
    $(this).val('');
});

$(document).on('keypress', '#search', function (event) {
    if(event.which == 13) {
        (event).preventDefault();
        let url = $(this).attr('action');
        let search = $('#input-search').val();
        if (url.includes('admin/dasboard') 
            || url.includes('admin/categories') 
            || url.includes('admin/sliders')
            || url.includes('admin/config')) {
                return;
        }
        let content = url.includes('admin/users') != -1 ? '.content' : '.table-user';
        $.ajax({
            url: url,
            method: 'get',
            data: {search: search},
            success: function(result) {
                console.log(content);
                $(content).html(result);
            }
        });

        return;
    }
});
