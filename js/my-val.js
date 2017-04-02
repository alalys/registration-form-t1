$(document).ready(function () {
    //validation rules
    $("#val").validate({
        rules: {
            "name": {
                required: true,
                minlength: 3
            },
            "lastname": {
                required: true,
                minlength: 3
            },
            "phone": {
                required: true,
                number: true,
                minlength: 9
            },
            "email": {
                required: true,
                email: true
            }
        }
    });
});