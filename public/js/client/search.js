$('#filter').click(function (event) {
    event.preventDefault();
    $('#search').val($('#header-search').val());
    $(this).parents('form').submit();
});

$(document).on('change', '#city', function () {
    var id = $(this).val();
    var url = $(this).attr('data-url');
    console.log(id);
    $.ajax({
        url: url,
        method: 'get',
        dataType: 'json',
        data: {id: id},
        success: function (result) {
            $('#province').empty();
            $.each(result, function (index, option) {
                option = $('<option></option>')
                            .attr('value', index)
                            .text(option);
                $('#province').append(option);
            });
            $('#province').prop('selectedIndex', 0);
        }
    })
});

function setKeyValue(key, value) {
    $('#sortKey').val(key);
    $('#sortValue').val(value);
}

$('#sort-select').change(function () {
    $('#sort').val($(this).val());
    $('#search').val($('#header-search').val());
    $('#filter-form').submit();
});
