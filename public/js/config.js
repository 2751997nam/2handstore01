function readURL(input, image) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $(image).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}
$(document).ready(function () {
    $('.logo').change(function () {
        readURL(this, '#logo');
    });
    $('.favicon').change(function () {
        readURL(this, '#favicon');
    });
});
