const url = $('#my-dropzone').attr('data-url');

Dropzone.options.myDropzone = {
    url: url,
    uploadMultiple: true,
    method: 'POST',
    parallelUploads: 5,
    maxFilesize: 16,
    maxFiles: 5,
    resizeWidth: 500,
    autoProcessQueue: false,
    resizeHeight: 500,
    resizeQuality: 0.8,
    addRemoveLinks: true,
    withCredentials: true,
    paramName: 'file',
    headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val(),
        'X-Requested-With': 'XMLHttpRequest',
    },
    init: function () {
        var myDropzone = this;
        $('.newpost__save').click(function () {
            myDropzone.processQueue();
        });
    },
    success: function (file, done) {
        if (done.error == undefined) {
            $('#imageData').val(done);
            $('#new_product').submit();
        }
    }
};

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
