$(document).on('ready', function () {

    if ($('.js-register-form').length > 0) {
        $('.js-register-form').validate({
            ignore: [],
            rules: {
                name: 'required',
                email: {
                    required: true,
                    email: true
                },
                organization_name: 'required',
                password: 'required',
                // organization_size: {
                //     required: false,
                //     number: true
                // }

            },
            messages: {
                name: "Please provide your name",
                email: {
                    required: "Please provide your email",
                    email: "Please enter a valid email"
                },
                organization_name: 'Please provide a company name',
                password: "Please provide a password",
                // organization_size: {
                //     number: "Please enter a valid employee strength"
                // }
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