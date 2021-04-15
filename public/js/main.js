// Header sticky
(function () {
    // variables
    var pageEl = $('.page'),
        headerEl = $('header');

    // actions
    function onScroll() {
        if (pageEl.scrollTop() > 164) {
            headerEl.addClass('header-bg');
        } else {
            headerEl.removeClass('header-bg');
        }
    }

    // toastr
    toastr.options = {
        "closeButton": true,
        "debug": true,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-top-center",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "15000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    // listeners
    pageEl.scroll($.throttle(50, onScroll));
})();

$(document).on("ready", function () {
    setTimeout(function () {
        YouRHired.Utils.showLoader(false);
    }, 0);

});
$('#signupform').validate({
    ignore: [],
    rules: {
        name: 'required',
        email: {
            required: true,
            email: true
        },
        phone: 'required',
        url: {
            required: true,
            url: true
        }
    },
    messages: {
        name: "Please enter name",

        email: {
            required: " Please enter email",
            email: "Please enter a valid email address" //Please enter a valid email address
        },
        phone: "Please enter phone number",
        url: {
            required: " Please enter url",
            url: "Please enter a valid url"
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
        $('input[type="submit"]').addClass('disabled');
        form.submit();
        $('input[type="submit"]').removeClass('disabled');
        $('.loading').addClass('g-hidden');
    }
});
