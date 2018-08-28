$(document).ready(function(){
    $('.delete').click(function(){
        
        let id = $(this).parent('td').attr('data-id');
        let url = $(this).attr('data-url');
        let msg = $(this).attr('data-msg');

        if(confirm(msg) == false){
            return false;
        }

        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(result) {
                if(result.status == 'success'){
                    $('#product' + id).fadeOut(500, function(){
                        $(this).remove();
                    });
                }
            }
        });
    });

    $('.show').click(function(){
        
        let id = $(this).parent('td').attr('data-id');
        let url = $(this).attr('data-url');

        $.ajax({
            url: url,
            type: 'GET',
            success: function(result) {
                console.log(result);
                $('.modal').modal();
            }
        });
    });
});

