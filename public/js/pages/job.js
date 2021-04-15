$(document).on('ready', function () {

    var mailboxObj = {
        _pageEl: $(".jobs"),
        init: function () {
            this.initDataTable();
            this.initValidations();
            this.addEvents();
        },

        initDataTable: function () {},
        initValidations: function () {
            var me = this;
            if ($('.js-category-form').length > 0) {
                var config = YouRHired.Utils.getValidationConfig({
                    ignore: [],
                    rules: {
                        title: 'required',
                        subject_filter: 'required',
                        email_filter: {
                            required: false,
                            email: true
                        }
                    },
                    messages: {
                        title: "Please enter a job title",
                        subject_filter: "Please provide a subject filter",
                        email_filter: {
                            email: "Please provide a valid email address"
                        }
                    },
                    
                });

                $('.js-category-form').validate(config);
            }
        },
       
        addEvents: function () {}
    };

    mailboxObj.init();

});