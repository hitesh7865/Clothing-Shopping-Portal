$(document).on('ready', function () {
    /*
    Login form validation
    */
    if ($('#loginform').length > 0) {
        $('#loginform').validate({
            ignore: [],
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: 'required'
            },
            messages: {
                email: {
                    required: " Please enter email",
                    email: "Please enter a valid email address"
                },
                password: {
                    required: 'Please enter your password'
                }
            },
            highlight: function (element, errorClass) {
                $(element).addClass('has-error');
            },
            unhighlight: function (element, errorClass) {
                $(element).removeClass('has-error');
            },
            errorPlacement: function (error, element) {
                // error.insertAfter(element);
                if (element.attr("name") == "checkbox_group[]") {
                    error.insertAfter("#checkbox-error");
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form) {
                var jForm = $(form);
                jForm.find('input[type="submit"]').addClass('disabled');
                form.submit();
                jForm.find('input[type="submit"]').removeClass('disabled');
                $('.loading').addClass('g-hidden');
            }
        });
    }

});