$(document).on("ready", function () {
    var candidateObj = {
        _pageEl: $(".page_applications"),
        init: function () {
            this.setDefaults();
            this.initDataTable();
            this.initValidations();
            this.addEvents();
        },
        setDefaults: function () {
            this.jAppTitle = $(".jsAppTitle");
            this.jAppStatus = $(".jsAppStatus");
            this.jAppType = $(".jsAppType");
            this.jAppId = $(".jsQueryAppId");
            this.jFetchAllBtn = $(".js-fetch-all")
            if ($(".page_applications").length > 0) {

                this.template = Handlebars.compile($("#application-detail-template").html());;
            }
            // Handlebars.registerHelper('decode_answers', function (items, options) {
            //     return items;
            // });

        },
        initDataTable: function () {
            var me = this;
            if ($(".page_applications").length == 0) {
                return;
            }

            var contentTpl = Handlebars.compile($("#application-content-template").html());
            var userTpl = Handlebars.compile($("#application-user-template").html());
            // Datatable config setup in advance
            $(".js-applications-listing").on('preXhr.dt', function (e, settings, data) {
                data['app_title'] = me.jAppTitle.val();
                data['app_status'] = me.jAppStatus.val();
                data['app_type'] = me.jAppType.val();
                data['app_id'] = me.jAppId.val();
            });

            var applicationListingConfig = window.YouRHired.Utils.getDefaultDTConfig({
                    api: '/api/applications'
                }),
                applicationDt;

            var jBtn = "<i class='detail g-icon g-icon_open js-detail-toggle'> </i>";
            var columnConfig = {
                "columns": [{
                    data: 'subject',
                    name: 'subject',
                    orderable: true,
                    sortable: true
                }, {
                    data: 'title',
                    name: 'title'
                }, {
                    data: 'from_email',
                    name: 'from_email'
                }, {
                    data: 'rating_tpl',
                    name: 'rating_tpl'
                }, {
                    data: 'current_status',
                    name: 'current_status'
                }, {
                    data: 'updated_at',
                    name: 'updated_at'
                }, {
                    data: "actions",
                    name: "actions",
                    orderable: false,
                    sortable: false
                }],
                order: [
                    [1, 'asc']
                ],
                "createdRow": function (row, data, index) {
                    var carbonateSyncedFlagEl = $(row).find('.carbonate-synced');
                    if(carbonateSyncedFlagEl.length > 0) {
                        $(row).addClass('carbonate-hired');
                    }
                    $('td', row).eq(0).addClass("datatable__detail datatable__detail_first");
                    if (data['attachments']) {
                        data['attachments'] = JSON.parse(data['attachments']);
                    }
                    var html = contentTpl(data);
                    // $('td', row).eq(0).html(html);
                    // if ([3, 4, 5, 6].indexOf(data['status']) != -1) {
                    $('td', row).eq(0).html(jBtn + html);
                    // }

                    var userHtml = userTpl(data);
                    $('td', row).eq(2).html(userHtml);

                }
            };

            $.extend(applicationListingConfig, applicationListingConfig, columnConfig);

            this.applicationDt = $('.js-applications-listing').DataTable(applicationListingConfig);

        },
        _getParsedTextEmail: function (data) {
            var text = data.text;
            var startText = "Content-Transfer-Encoding: quoted-printable";
            var endText = 'Content-Type: text/html; charset="UTF-8"';
            if (text.indexOf("Content-Transfer-Encoding: quoted-printable") != -1) {
                text = text.substr((text.indexOf(startText) + startText.length + 1)); // To skip the \n
                text = text.substr(0, text.indexOf(endText) - 1); // To skip the \n
                text = text.substr(0, text.lastIndexOf("\n") - 1);
            }
            data.text = text;
            return data;
        },

        _getParsedHTMLEmail: function (data) {
            var text = data.text;
            var startText = "Content-Transfer-Encoding: quoted-printable";
            var endText = "\n";
            var textLength = text.length;;
            if (text.indexOf("Content-Transfer-Encoding: quoted-printable") != -1) {
                text = text.substr((text.lastIndexOf(startText) + startText.length + 1)); // To skip the \n
                text = text.substr(0, text.lastIndexOf(endText) - 1); // To skip the \n
            } else if (text.indexOf("Content-Transfer-Encoding: 7bit") != -1) {
                // Yahoo with attachments
                startText = "Content-Transfer-Encoding: 7bit";
                endText = "------=_Part_";
                text = text.substr((text.lastIndexOf(startText) + startText.length + 1)); // To skip the \n
                text = text.substr(0, text.indexOf(endText) - 1); // To skip the \n
                // text = text.substr(0, text.lastIndexOf("\n") - 1);
            } else if (text.indexOf('Content-Type: text/html; charset="UTF-8"') != -1) {
                startText = 'Content-Type: text/html; charset="UTF-8"';
                text = text.substr((text.lastIndexOf(startText) + startText.length + 1)); // To skip the \n
                text = text.substr(0, text.lastIndexOf(endText) - 1); // To skip the \n
            }
            // data.text = text;
            data.text = "<pre>" + text + "</pre>";
            return data;
        },
        onRemind: function (e) {
            var jTarget = $(e.currentTarget),
                me = this;
            var data = {
                "app_id": jTarget.data("app-id")
            };
            YouRHired.Utils.sendRequest({
                url: "/api/send-reminder",
                type: "POST",
                data: data,
                onDone: function (response) {
                    me.applicationDt.ajax.reload(null, false);
                    toastr.success("Reminder sent");
                }
            });
        },

        initValidations: function () {},
        addEvents: function () {
            var me = this;
            $('.js-applications-listing tbody').on('click', '.js-detail-toggle', function () {
                var tr = $(this).closest('tr');
                var row = me.applicationDt.row(tr);
                var data = row.data();
                if (row.child.isShown()) {
                    row.child.hide();
                    tr.removeClass('shown');
                    tr.find(".js-detail-toggle").removeClass("g-icon_close").addClass("g-icon_open");

                    // This row is already open - close it

                } else {
                    tr.addClass('shown');
                    tr.find(".js-detail-toggle").removeClass("g-icon_open").addClass("g-icon_close");
                    // Open this row
                    $.ajax({
                        url: "/api/application-activity/" + data["id"],
                        dataType: "json"
                    }).done(function (response) {
                        response = me._getParsedHTMLEmail(response);
                        row.child(me.template(response)).show();
                    });

                }
            });

            $('.js-applications-listing tbody').on('click', '.jsRemind', $.proxy(this.onRemind, this));
            $('.js-applications-listing tbody').on('click', '.jsEditApplication', $.proxy(this._onApplicationEdit, this));
            $('.js-applications-listing tbody').on('click', '.jsCarbonateSync', $.proxy(this._onClickCarbonateSync, this));

            $(document.body).on("click", ".jsUpdateCandidateStatus", $.proxy(this._onStatusUpdateClick, this));
            $(document.body).on("click", ".jsSendEmail", $.proxy(this._onSendHiringRejectionEmail, this));
            $(document.body).on("click", ".jsContinue", $.proxy(this._onContinueHiringRejection, this));
            $(document.body).on('click', '.jsSaveApplicationEdit', $.proxy(this._onSaveApplication, this));
            $(document.body).on("click", ".jsCancelApplicationEdit", $.proxy(this._onCancelEditApplication, this));
            $(document.body).on("click", ".jsSubmitAuth", $.proxy(this._onSubmitAuthRequest, this));
            $(document.body).on("click", ".jsPerformCarbonateSync", $.proxy(this._performUserSync, this));
            $(document.body).on("click", ".jsSwitchAccount", $.proxy(this._switchCarbonateAccount, this));

            this.jAppTitle.on("change", $.proxy(this.onFilterChange, this));
            this.jAppStatus.on("change", $.proxy(this.onFilterChange, this));
            this.jAppType.on("change", $.proxy(this.onFilterChange, this));

            this.jFetchAllBtn.on("click", function () {
                me.jFetchAllBtn.attr("disabled", true);

                me.fetchAllApplications(function () {
                    me.jFetchAllBtn.removeAttr("disabled");
                    toastr.success("Candidates are being fetched in the background.");
                });
                setTimeout(function () {
                    console.log("Updating Candidate Listing");
                    me.applicationDt.ajax.reload(null, false);
                }, (1000 * 15));
                setTimeout(function () {
                    console.log("Updating Candidate Listing");
                    me.applicationDt.ajax.reload(null, false);
                }, (1000 * 40));
            })
        },
        _onContinueHiringRejection: function () {
            var me = this;
            if (this.appId !== undefined) {
                var data = {
                    id: this.appId,
                    status: this.status,
                    _method: 'PUT'
                };

                YouRHired.Utils.sendRequest({
                    url: "/candidates/ " + data.id,
                    data: data,
                    onDone: function (response) {
                        $.magnificPopup.close();
                        me.applicationDt.ajax.reload(null, false);
                        toastr.success("Application status updated");
                    }
                });
            }
        },
        _onCancelEditApplication: function () {
            $.magnificPopup.close();
        },
        _onApplicationEdit: function (e) {
            var jTarget = $(e.target);
            var jPopup = $(".jsEditApplicationPopup");
            this.appId = jTarget.data("app-id");
            jPopup.find("input[name='name']").val(jTarget.data("name"));
            jPopup.find('select option[value="' + jTarget.data("rating") + '"]').prop("selected", true);
            YouRHired.Utils.showPopup(jPopup);
        },
        _onSaveApplication: function (e) {
            var me = this;
            var jPopup = $(".jsEditApplicationPopup");
            if (this.appId !== undefined) {
                var data = {
                    id: this.appId,
                    _method: 'PUT'
                };

                data['name'] = jPopup.find("[name='name']").val();
                data['rating'] = jPopup.find("[name='rating']").val();

                YouRHired.Utils.sendRequest({
                    url: "/candidates/ " + data.id,
                    data: data,
                    onDone: function (response) {
                        $.magnificPopup.close();
                        me.applicationDt.ajax.reload(null, false);
                        toastr.success("Application detail updated");
                        delete this.appId;
                    }
                });
            }
        },
        _onSendHiringRejectionEmail: function () {
            var me = this;
            var jPopup = (this.status == "6") ?
                $(".jsHireEmailPopup") :
                $(".jsRejectionEmailPopup");
            if (this.appId !== undefined) {
                var data = {
                    id: this.appId,
                    status: this.status,
                    _method: 'PUT'
                };

                data['subject'] = jPopup.find("[name='subject']").val();
                data['content'] = jPopup.find("[name='content']").val();

                YouRHired.Utils.sendRequest({
                    url: "/candidates/ " + data.id,
                    data: data,
                    onDone: function (response) {
                        $.magnificPopup.close();
                        me.applicationDt.ajax.reload(null, false);
                        toastr.success("Application status updated");
                    }
                });
            }
        },
        _onStatusUpdateClick: function (e) {
            var jTarget = $(e.currentTarget),
                me = this;
            if (jTarget.parent().hasClass("disabled")) {
                e.preventDefault();
                e.stopPropagation();
                return;
            }
            var data = {
                id: jTarget.data("app-id"),
                status: jTarget.data("status-id"),
                title: jTarget.data("title"),
                _method: 'PUT'
            };

            this.appId = data.id;
            this.status = data.status;

            if (data.status == "6" || data.status == "5") {
                var jPopup = (data.status == "6") ?
                    $('.jsHireEmailPopup') :
                    $('.jsRejectionEmailPopup');
                if (data.status == "5") {
                    jPopup.find("[name='subject']").val("Application status for job : " + data.title);
                } else {
                    jPopup.find("[name='subject']").val("Wohoo! Hiring confirmation for the job : " + data.title);
                }

                YouRHired.Utils.showPopup(jPopup);

            } else {
                this._updateStatus(data);
            }

        },
        _updateStatus: function (data) {
            var me = this;
            YouRHired.Utils.sendRequest({
                url: "/candidates/ " + data.id,
                data: data,
                onDone: function (response) {
                    me.applicationDt.ajax.reload(null, false);
                    toastr.success("Application status updated");
                }
            });
        },
        onFilterChange: function (e) {
            var currentUrl = window.location.href;
            var params = YouRHired.Utils.getUrlVars();
            params['status'] = this.jAppStatus.val();
            params['title'] = this.jAppTitle.val();
            params['type'] = this.jAppType.val();

            var url = location.origin + "/candidates?" + $.param(params);
            location.href = url;
        },
        fetchAllApplications: function (callback) {
            $.ajax({
                url: "/fetch-all",
                dataType: "json"
            }).done(function (response) {
                if (callback) {
                    callback(response);
                }
            });
        },
        _onClickCarbonateSync: function (e) {
            var me = this;
            var authType = 'carbonateAuth';
            var applicantId = $(e.target).closest('tr').attr('id');
            $(".jsCarbonateAuthenticationPopup").find('[name=applicantId]').val(applicantId);
            $(".jsCarbonateAccountSwitchPopup").find('[name=applicantId]').val(applicantId);
            YouRHired.Utils.sendRequest({
                url: "/check-cookie",
                data: {cookieName: authType},
                onDone: function (response) {
                    if(response.valid == 1) {
                        var jPopup = $(".jsCarbonateAccountSwitchPopup");
                        $('#carbonateUserName').html(response.carbonateUser);
                        $('#carbonateUserEmail').html(response.carbonateUserEmail);
                    } else {
                        var jPopup = $(".jsCarbonateAuthenticationPopup");
                    }
                    YouRHired.Utils.showPopup(jPopup);
                }
            });
        },
        _switchCarbonateAccount: function(e) {
            var jTarget = $(e.target);
            var jPopup = jTarget.closest('.popup');
            YouRHired.Utils.hidePopup();
            if(jPopup.hasClass('jsCarbonateAccountSwitchPopup')) {
                var showPopup = $(".jsCarbonateAuthenticationPopup");
                YouRHired.Utils.showPopup(showPopup);
                YouRHired.Utils.sendRequest({
                    url: '/delete-cookie',
                    data: {},
                    onDone: function (response) {
                    
                    }
                });
            }
        },
        _onSubmitAuthRequest: function(e) {
            var contentParent = $(e.target).closest('.white-popup');
            var jForm = contentParent.find('form');
            var authapp = jForm.find('[name="authapp"]').val();
            var email = jForm.find('[name="email"]').val();
            var password = jForm.find('[name="password"]').val();
            var data = {};
            var url = "";
            var targetId = "";
            var me = this;
            if(authapp == "carbonate" && email != "" && password != "") {
                url = "/authenticate-carbonate";
                data = {email: email, password: password};
            }
            if(url != "") {
                YouRHired.Utils.sendRequest({
                    url: url,
                    data: data,
                    onDone: function (response) {
                        if(response.valid == 1 || response.valid == true) {
                            //perform user sync
                            me._performUserSync(e);
                        } else {
                            toastr.error("Authentication failed!");
                        }
                    }
                });
            }
        },
        _performUserSync: function(e) {
            var jTarget = $(e.target);
            var thy = this;
            var applicantId = $(e.target).closest('.popup').find('[name=applicantId]').val();
            if(applicantId != "") {
                YouRHired.Utils.sendRequest({
                    url: '/carbonate-applicant-sync',
                    data: {applicant_id: applicantId},
                    onDone: function (response) {
                        if(response.status == 1) {
                            YouRHired.Utils.hidePopup();
                            $('.js-applications-listing tbody tr#'+applicantId).find('.jsCarbonateSync').addClass('g-hidden');
                            $('.js-applications-listing tbody tr#'+applicantId).addClass('carbonate-hired');
                            toastr.success("Applicant added to Carbonate!");
                        } else {
                            toastr.error(response.message);
                        }
                    }
                });
            }
        }

    };
    candidateObj.init();
});