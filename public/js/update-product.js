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
        this.on("removedfile", function (file) {
            $.post({
                url: $('#my-dropzone').attr('delete-url'),
                data: {id: file.name, _token: $('[name="_token"]').val()},
                dataType: 'json',
                success: function (data) {
                    alert('OK');
                }
            });
        });

        var myDropzone = this;

        $.get($('#my-dropzone').attr('get-url'), function(data){
            $.each(data, function (key, value) {
                var file = {name: value.id, size: 50000};
                myDropzone.options.addedfile.call(myDropzone, file);
                myDropzone.options.thumbnail.call(myDropzone, file, value.url);
            });
        });

        $('.newpost__save').click(function () {
            if (myDropzone.getQueuedFiles().length > 0) {                        
                myDropzone.processQueue();  
            } else {       
                $('#new_product').submit();
            }  
        });
    },
    success: function (file, done) {
        if(done.error == undefined){
            $('#imageData').val(done);
            $('#new_product').submit();
        }
    }
};
$(document).on('change', '#city', function () {
    var id = $(this).val();
    var url = $(this).attr('data-url');
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
