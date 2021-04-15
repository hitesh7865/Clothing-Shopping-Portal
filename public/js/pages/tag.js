$(document).on('ready', function() {

    var mailboxObj = {
        _pageEl: $(".tags"),
        init: function() {
            this.initDataTable();
            this.initValidations();
            this.addEvents();
        },

        initDataTable: function() {
            if ($(".page_tags").length > 0) {

                console.log('in tags');
                var filterTpl = Handlebars.compile($("#category-filter-detail-template").html());
                $('.js-tags-listing').DataTable({
                    dom: '<"datatable__header g-cleared"f>rt<"datatable__footer g-cleared"p><"clear">',
                    stateSave: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '/get-tags',
                    columns: [{
                        data: 'name',
                        name: 'name',
                        orderable: true,
                        searchable: true
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }],
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
        initValidations: function() {
            if ($('.js-tag-form').length > 0) {
                var config = YouRHired.Utils.getValidationConfig({
                    ignore: [],
                    rules: {
                        name: 'required',
                        maxlength: 250

                    },
                    messages: {
                        name: "Please enter a name",
                        maxlength: "Please enter no more than 250 characters"

                    }
                });

                $('.js-tag-form').validate(config);
            }
        },
        addEvents: function() {}
    };

    mailboxObj.init();

});