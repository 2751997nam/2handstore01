$(document).ready(function () {
    $('.quantity').click(function () {
        var id = $(this).attr('data-id');
        var quantity = $(this).attr('data-quantity');
        $('input[name=quantity]').val(quantity);
        $('input[name=id]').val(id);
        $('.modal').modal();
    });
});
