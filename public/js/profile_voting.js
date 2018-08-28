$(document).ready(function () {
    var my_vote = $('.stars').attr('my-vote');
    if (my_vote) {
        $('input[value=' + my_vote + ']').prop('checked', true);
    }
    $('input[name=star]').click(function () {
        var stars = $(this).val();
        $.post({
            url: $(this).parent().attr('data-url'),
            data: {
                stars: stars
            },
            headers: {
                'X-CSRF-TOKEN': $('input[name=_token]').val()
            },
            success: function(data) {
                if (!data['error']) {
                    $('#avg').text(data['avg']);
                    $('#votes').text(data['votes']);
                }
            }
        });
    });
});
