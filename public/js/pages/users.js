$(document).on('ready', function() {

    var users = {
        _pageEl: $(".users"),
        _jForm: $("#jsCreateUserForm"),
        init: function() {
            this.initDataTable();
            this.initValidations();
            this.addEvents();
        },


        initValidations: function() {
            // alert(this._jForm.length);
            var me = this;
            if (this._jForm.length > 0) {
                var config = YouRHired.Utils.getValidationConfig({
                    ignore: [],
                    rules: {

                        name: 'required',
                        user_name: 'required',
                        email: {
                            required: true,
                            email: true
                        },
                        // phone: 'required',

                    },
                    messages: {
                        name: "Name is required.",
                        user_name: "User name is required.",
                        email: {
                            email: "Please provide a valid email address",
                            required: "Email address is required."
                        },
                        phone: "Phone number is required."
                    }

                });

                this._jForm.validate(config);
            }
        },


        initDataTable: function() {
            if ($(".page_users").length > 0) {

                var filterTpl = Handlebars.compile($("#category-filter-detail-template").html());
                $('.js-users-listing').DataTable({
                    dom: '<"datatable__header g-cleared"f>rt<"datatable__footer g-cleared"p><"clear">',
                    stateSave: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '/get-users',
                    columns: [{
                            data: 'fullname',
                            name: 'fullname',
                            orderable: true,
                            searchable: true
                        },
                        {
                            data: 'user_name',
                            name: 'user_name',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'email',
                            name: 'email',
                            orderable: false,
                            searchable: true
                        },
                        {
                            data: 'phone',
                            name: 'phone',
                            orderable: false,
                            searchable: false
                        },
                        {

                            data: 'status',
                            name: 'status',
                            orderable: false,
                            searchable: false

                        },
                        {

                            data: 'action',
                            name: 'action',
                            className: 'action-button',
                            orderable: false,
                            searchable: false

                        },

                    ],
                    "createdRow": function(row, data, index) {}
                });



                function getStatusNameByKeyId(id) {
                    id = id.toString();
                    // return id;
                    switch (id) {
                        case "1":
                            return "Active";
                        case "2":
                            return "Paused";
                        default:
                            return "Paused";
                    }
                }

            }
        },

        addEvents: function() {

            $("#jsUserStatus").on("change", function(e) {
                $(".jsUserStatusLabel").html(
                    e.target.checked ?
                    "Active" :
                    "In Active");
            });



            // $("#delete-user-call").on("click", function () {
            //     alert('2222');
            //     $("#delete-user").submit();
            //     return false;
            // });
        }
    };

    users.init();

});