$(document).on('ready', function() {
    console.log($('#job_id').val());

    var categoryObj = {
        _jForm: $('#jobs_edit_form'),
        init: function() {
            var jQuestions = $(".jsQuestions");
            this.initPickers();

            this.addEvents();
            this.initValidations();

        },
        onSelectChange: function(e) {},
        initValidations: function() {
            var me = this;
            if (this._jForm.length > 0) {
                var config = YouRHired.Utils.getValidationConfig({
                    ignore: [],
                    rules: {
                        title: 'required',
                        description: 'required',
                        created_by: 'required',
                        // subject_filter: 'required',
                        // question_set_id: 'required',
                        working_hours: 'required',
                        number_of_vacancies: 'required',
                        gender_preference: 'required',
                        minimum_age_group: 'required',
                        // education_qualification: 'required',
                        // job_benefits: 'required',
                        minimum_age_group: {
                            'required': true,
                            max: function() {
                                if ($('#maximumAgeGroup').val() !== "") {
                                    return $('#maximumAgeGroup').val();
                                }
                            }
                        },
                        maximum_age_group: {
                            'required': true,
                            min: function() {
                                if ($('#minimumAgeGroup').val() !== "") {
                                    return $('#minimumAgeGroup').val();
                                }
                            }
                        },
                        minimum_salary: {
                            'required': true,
                            max: function() {
                                if ($('#maximumSalary').val() !== "") {
                                    return $('#maximumSalary').val();
                                }
                            }
                        },
                        maximum_salary: {
                            'required': true,
                            min: function() {
                                if ($('#minimumSalary').val() !== "") {
                                    return $('#minimumSalary').val();
                                }
                            }
                        },
                        organisation_name: 'required',
                        organisation_description: 'required',
                        organisation_additional_contact_details: 'required',
                        organisation_name: 'required',
                        email_filter: {
                            required: false,
                            email: true
                        }
                    },
                    messages: {
                        title: "Please enter a job title.",
                        description: 'Please enter a job description.',
                        created_by: 'Please select a user.',
                        subject_filter: "Please provide a subject filter.",
                        question_set_id: 'Please provide a question set.',
                        working_hours: 'Please provide the working hours.',
                        number_of_vacancies: 'Please provide the number of vacancies.',
                        gender_preference: 'Please provide the gender preference.',
                        // minimum_age_group: 'Please provide the minimum age group.',
                        education_qualification: 'Please provide the education qualification.',
                        // job_benefits: 'Please provide the job benefits.',
                        minimum_salary: {
                            'required': 'Please provide the minimum salary.',
                            'max': 'Please provide valid minimum salary.'
                        },
                        maximum_salary: {
                            'required': 'Please provide the maximum salary.',
                            'min': 'Please provide valid maximum salary.'
                        },
                        minimum_age_group: {
                            'required': 'Please provide the minimum age group.',
                            'max': 'Please provide valid minimum age group.'
                        },
                        maximum_age_group: {
                            'required': 'Please provide the maximum age group.',
                            'max': 'Please provide valid maximum age group.'
                        },
                        organisation_name: 'Please provide the organisation name.',
                        organisation_description: 'Please provide the organisation description.',
                        organisation_additional_contact_details: 'Please provide the organisation additional contact details.',
                        organisation_name: 'Please provide the organisation name.',
                        email_filter: {
                            email: "Please provide a valid email address"
                        }
                    },
                    submitHandler: function() {
                        me.onFormSubmit();
                    },
                });
                this._jForm.validate(config);
            }
        },
        initPickers: function() {
            if ($("#jobs_edit_form").length > 0) {

                var jStartDateTime = $(".jsEditStartDateTime"),
                    jEndDateTime = $(".jsEditEndDateTime"),
                    curEl,
                    dtEndPicker;
                jStartDateTime.flatpickr({
                    allowInput: false,
                    altInput: true,
                    altFormat: "d-m-Y H:i",
                    dateFormat: "Y-m-d H:i:S",
                    // defaultDate: [$('#txtHiddenStartDateTime').val()],
                    enableTime: true,
                    // minDate: 'today',
                    altInputClass: '',
                    maxDate: new Date().fp_incr(60), // 14 days from now});
                    onChange: onStartDateTimeChange,
                    onReady: onFlatPickrReady
                });
                dtEndPicker = jEndDateTime.flatpickr({
                    allowInput: false,
                    altInput: true,
                    altFormat: "d-m-Y H:i",
                    dateFormat: "Y-m-d H:i:S",
                    enableTime: true,
                    // defaultDate: [$('#txtHiddenEndDateTime').val()],
                    // minDate: 'today',
                    altInputClass: '',
                    onReady: onFlatPickrReady,
                    maxDate: new Date().fp_incr(60) // 14 days from now});
                });

                function onFlatPickrReady(dateObj, dateStr, instance) {
                    // Add a clear button
                    var clear = $('<div class="flatpickr-clear"><button class="btn btn_blue btn_xs">Clear</button></div>')
                        .on('click', function() {
                            instance.clear();
                            instance.close();
                        })
                        .appendTo($(instance.calendarContainer));
                }

                function onStartDateTimeChange(selectedDates, dateStr, instance) {
                    dtEndPicker.set("minDate", dateStr);
                }

                function onEndDateTimeChange() {}

                $("#editJobStatus").on("change", function(e) {
                    var status = '0';
                    $(".jsStatusLabel").html(
                        e.target.checked ?
                        "Active" :
                        "Paused");

                    if (e.target.checked) {
                        status = '1';
                    }
                    var formData = {
                        'status': status
                    }
                    $.ajax({
                        url: '/jobs/change-status/' + $('#job_id').val(),
                        type: "POST",
                        dataType: "json",
                        data: formData,
                        success: function(response) {

                        }
                    })
                })
            }
        },
        onFormSubmit() {

            if (this._jForm.valid()) {



                var $form = this._jForm;
                var unindexed_array = $form.serializeArray();
                var indexed_array = {};


                jQuery.map(unindexed_array, function(n) {
                    indexed_array[n['name']] = n['value'];
                });
                var formData = {};

                formData = indexed_array;
                // formData.city = user_city;


                var user_city = $('.jsUserCity').val();
                var cityData = [];
                var country_name = $('.jsUserCountry').find(':selected').attr('data-country');
                var preferred_city = $('.jsPreferredCity').val();
                var preferredCityData = [];

                formData.country_name = country_name;

                if (user_city !== null) {

                    $.each(user_city, function(key, value) {

                        var city_id = $('.jsUserCity option[value="' + value + '"]').attr('id');

                        var data = {
                            name: value,
                            id: city_id
                        }
                        cityData.push(
                            data
                        );

                    });

                    formData.city = cityData;
                }


                // if (preferred_city !== null) {
                //     $.each(preferred_city, function(key, value) {

                //         var city_id = $('.jsPreferredCity option[value="' + value + '"]').attr('id');

                //         var data2 = {
                //             name: value,
                //             id: city_id
                //         }
                //         preferredCityData.push(
                //             data2
                //         );

                //     });

                // }
                //console.log($('#job_id').val());
                formData.PreferredCity = preferredCityData;
                //alert($('#job_id').val());
                // alert('1');
                // return;

                swal({
                    title: "Are you sure!",
                    type: "error",
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes!",
                    showCancelButton: true,
                    cancelButtonText: "No",
                    text: "",
                    buttons: {
                        confirm: {
                            text: "Yes",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        },
                        cancel: {
                            text: "No",
                            value: false,
                            visible: true,
                            className: "btn-danger",
                            closeModal: true,
                        }
                    }
                }).then((isConfirmed) => {
                    if (isConfirmed) {
                        $.ajax({
                            url: '/jobs/' + $('#job_id').val(),
                            type: "PUT",
                            dataType: "json",
                            data: formData,
                            success: function(response) {
                                console.log(response.data.success);
                                $('.alert-success').html(response.data.success);
                                location.href = '/jobs';
                            }
                        });
                    }
                })
            }
        },
        changeJobStatus: function() {

            $("#editJobStatus").on('change', function(e) {

                $.ajax({
                    url: '/jobs/change-status' + $('#job_id').val(),
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    success: function(response) {
                        console.log(response.data.success);
                        $('.alert-success').html(response.data.success);
                        location.href = '/jobs';

                    }
                })
            });
        },
        addEvents: function() {
            $(".jsUserCity").select2({
                placeholder: "Cities",
            });
            $("#editJobStatus").on('change', function() {
                var status;
                if ($(this).is(':checked')) {
                    status = 1
                } else {
                    status = 0;
                }
                $.ajax({
                    url: '/jobs/change-status/' + $('#job_id').val(),
                    type: "POST",
                    dataType: "json",
                    data: { 'status': status },
                    success: function(response) {
                        console.log(response.data.success);
                        $('.alert-success').html(response.data.success);
                        location.href = '/jobs';
                    }
                })
            });

            $('#city').on('select2:select', function(e) {
                var city = $("#city").select2().find(":selected").data("select2-id");
                console.log(city);
                //$("#city").val();

            });

        }
    };

    categoryObj.init();

});