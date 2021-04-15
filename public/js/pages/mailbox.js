$(document).on('ready', function () {

    var mailboxObj = {
        _pageEl: $(".mailboxes"),
        _jHostEl: null,
        _jConType: null,
        init: function () {
            this._jHostEl = this._pageEl.find("[name='imap_host']");
            this._jConType = this._pageEl.find(".jsConnectionType");
            this._jConStatus = this._pageEl.find("[name='imap_connection_status']");
            this._jActive = this._pageEl.find("[name='status']");
            this.updateHostByEl(this._jConType);
            this.initDataTable();
            this.initValidations();
            this.addEvents();
        },

        initDataTable: function () {

            if ($(".page_mailboxes").length > 0) {
                var filterTpl = Handlebars.compile($("#mailbox-filter-detail-template").html());
                $('.js-mailboxes-listing').DataTable({
                    dom: '<"datatable__header g-cleared"f>rt<"datatable__footer g-cleared"p><"clear">',
                    stateSave: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '/api/mailboxes',
                    columns: [{
                        data: 'imap_name',
                        name: 'imap_name'
                    }, {
                        data: 'imap_host',
                        name: 'imap_host'
                    }, {
                        data: 'status',
                        name: 'status'
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }],
                    "createdRow": function (row, data, index) {
                        var html = "";
                        var status = getStatusNameByKeyId(data["status"]);
                        html = "<div class='status status_" + (
                            status.toLowerCase()).replace(" ", "-") + "'>" + status + "</div>";
                        $('td', row).eq(2).html(html);

                        // Combine Host + User
                        html = filterTpl(data);
                        $('td', row).eq(1).html(html);
                    }
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
        initValidations: function () {
            if ($('#mailbox-form').length > 0) {
                $('#mailbox-form').validate({
                    ignore: [],
                    rules: {
                        imap_name: 'required',
                        imap_host: 'required',
                        imap_user: 'required',
                        imap_password: 'required'

                    },
                    messages: {
                        imap_name: "Please enter a suitable name",
                        imap_host: "Please enter your HOST",
                        imap_user: "Please enter your IMAP username",
                        imap_password: "Please enter your IMAP password"
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
        },
        addEvents: function () {
            $(".jsTestConnection").on("click", $.proxy(this.onTestConnection, this));
            $(".jsConnectionType").on("change", $.proxy(this.onConnectionChange, this));
            $("#status").on("change", function (e) {
                $(".jsStatusLabel").html(
                    e.target.checked ?
                    "Active" :
                    "Paused");
            })
        },
        onTestConnection: function () {
            YouRHired.Utils.showLoader(true);

            var jForm = $("#mailbox-form");
            var data = {},
                me = this;
            var jqxhr = $.ajax({
                url: "/api/imap/test",
                type: "POST",
                dataType: "JSON",
                data: jForm.serialize()
            }).done(function (response) {
                if (response.status == true) {
                    me._jConStatus.val(1);
                    me._jActive.removeAttr("disabled");
                    toastr.success("Yay! Test was successfull :)");
                } else {
                    me._jConStatus.val(0);
                    me._jActive.attr("disabled", true);
                    // toastr.error(response.message);
                    toastr.error("Oops! Failed to connect with specified IMAP account. Please make sure the credentials are correct . For more info visit <a href='/faq' target='_blank'>Guide</a>");
                }

            }).fail(function (response) {
                me._jConStatus.val(0);
                me._jActive.attr("disabled", true);
                toastr.error(response.message);
                // toastr.error("Oops! Failed to connect with specified IMAP account. Please make sure the credentials are correct . For more info visit <a href='/faq' target='_blank'>Guide</a>");

            }).always(function () {
                YouRHired.Utils.showLoader(false);
            });
        },
        onConnectionChange: function (e) {
            this.updateHostByEl($(e.target));
            this._jConStatus.val(0);
            this._jActive.removeAttr("checked");
            this._jActive.attr("disabled", true);
        },
        updateHostByEl: function (jEl) {
            var jTargetVal = jEl.find(":selected").data("connection");
            this._jHostEl.val(jTargetVal);
        }
    };

    mailboxObj.init();

});