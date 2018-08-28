$(document).ready(function () {
    $('.delete').click(function () {
        
        let msg = $(this).attr('data-msg');
        let id = $(this).attr('data-id');
        let url = $(this).attr('data-url');

        if (confirm(msg) == false) {
            return false;
        }

        $.ajax({
            url: url + '/' + id,
            type: 'DELETE',
            data: {
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (result) {
                if(result.status == 'success'){
                    $('#slide' + id).fadeOut(500, function () {
                        $(this).remove();
                    });
                }
            }
        });
    });

    $('.zoom').click(function () {
        $('#image-zoom img').attr('src', $(this).children('img').attr('src'));
        $('#image-zoom').modal();
    });
});

function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('.image img').attr('src', e.target.result);
        }

        reader.readAsDataURL(input.files[0]);
    }
}

$('.image-input').change(function () {
    readURL(this);
});
