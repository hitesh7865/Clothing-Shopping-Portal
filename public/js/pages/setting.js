(function() {

    var settingsObj = {
        init: function() {
            this.setDefaults();
            this.initValidation();
            this.addEvents();
        },

        setDefaults: function() {
            this.jReminderToggle = $(".jsReminderToggle");
            this.jReminderInput = $("[name='send_reminder_email_days']");
            this.jCandidateSettingsForm = $('#candidate-settings');
            this.jOrganizationSettingsForm = $("#organization-settings");
            this.jProfileForm = $("#user-profile");
            this.jGenrateApiToken = $('#genrateApiToken');
        },
        initValidation: function() {
            var me = this;
            if (this.jCandidateSettingsForm.length > 0) {
                var config = YouRHired.Utils.getValidationConfig({
                    ignore: [],
                    rules: {
                        send_reminder_email_days: {
                            number: true,
                            required: function(element) {
                                return me.jReminderToggle.is(":checked");
                            },
                            noDecimal: true
                        }

                    },
                    messages: {
                        send_reminder_email_days: {
                            number: "Please provide days in numeric e.g 3",
                            required: "Please provide a value e.g 3",
                            noDecimal: "Please provide days in whole number only :) "
                        }
                    }
                });

                this.jCandidateSettingsForm.validate(config);
            }

            if (this.jOrganizationSettingsForm.length > 0) {


                var config = YouRHired.Utils.getValidationConfig({
                    ignore: [],
                    rules: {
                        name: {
                            required: true
                        },
                        email: {
                            required: false,
                            email: true
                        },
                        website: {
                            required: false,
                            url: true
                        },
                        telephone: {
                            "customTel": true,

                            maxlength: 12,
                        }


                    },
                    messages: {
                        name: {
                            required: "Please provide a organization name"
                        },
                        email: {
                            email: "Please enter a valid email address"
                        },
                        website: {
                            url: "Please enter a valid website url e.g https://www.wikipedia.org"
                        },
                        telephone: {
                            maxlength: "Max allowed length is 12, please truncate spaces if any"
                        }
                    }
                });

                this.jOrganizationSettingsForm.validate(config);
            }

            if (this.jProfileForm.length > 0) {


                var config = YouRHired.Utils.getValidationConfig({
                    ignore: [],
                    rules: {
                        fullname: {
                            required: true
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        new_password: {
                            required: false
                        },
                        confirm_password: {
                            required: false,
                            equalTo: "#new_password"
                        }


                    },
                    messages: {
                        fullname: {
                            required: "Please provide your complete name"
                        },
                        email: {
                            required: 'Please provide a email address',
                            email: "Please enter a valid email address"
                        },
                        confirm_password: {
                            equalTo: "Please repeat your new password"
                        }
                    }
                });

                this.jProfileForm.validate(config);
            }
        },

        onReminderToggleChange: function(e) {

            if (e.target.checked) {
                this.jReminderInput.removeAttr("disabled");
                this.jReminderInput.val("3");
            } else {
                this.jReminderInput.attr("disabled", true).val("");
            }
            this.jCandidateSettingsForm.valid();
        },
        genrateApiToken: function(e) {
            $.ajax({
                url: 'genrate_api_token',
                data: { "_token": $('#csrfToken').val() },
                type: "POST",
                dataType: "json",
                success: function(data) {
                    $('#apiKey').val(data.data.api_key);
                }
            });
        },
        addEvents: function() {
            this.jReminderToggle.on("change", $.proxy(this.onReminderToggleChange, this));
            this.jReminderToggle.on("change", $.proxy(this.onReminderToggleChange, this));
            this.jGenrateApiToken.on('click', $.proxy(this.genrateApiToken, this));
        }
    };
    settingsObj.init();
})();