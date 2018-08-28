function readURL(input) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function(e) {
            $('#uploading-img').attr('src', e.target.result).css({
                width: '100px',
                height: '100px'
            })
        }
    
        reader.readAsDataURL(input.files[0]);
    }
}
    
$('#image').change(function() {
    readURL(this);
});

$('input[type=radio][name=is_parent]').click(function () {
    if($(this).val() == true) $('.select-parent').attr('hidden', true);
    else $('.select-parent').removeAttr('hidden');
});
