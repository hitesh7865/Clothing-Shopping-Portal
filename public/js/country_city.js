$(document).on('ready', function() {

    var users = {
        _pageEl: $(".users"),
        init: function() {
            this.loadCountries();
            this.addEvents();
            this.country = "";
            this.appendUserCity = false;
            this.appendPreferredCity = false;
            this.userCityArray = [];
            this.userPreferredCityArray = [];
            this.loadCity = 0;
        },

        loadCountries: function() {
            YouRHired.Utils.showLoader(true);
            var me = this;

            $.ajax({
                url: ' https://api.countrystatecity.in/v1/countries',
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSCAPI-KEY", "NUlkeFZrNzBBVEtYSDhvZjFqUkR2VjVuVnVDOTl4cEJ6Sm5DSE9veA==");
                },
                type: "GET",
                dataType: "json",
                success: function(response) {
                    YouRHired.Utils.showLoader(false);
                    $('#jsUserCountry,.jsUserPreferredCountry').find('option').remove();
                    $.each(response, function(key, value) {
                        if ($('.jsUserCountry').val() != "" && value.iso2 == $('.jsUserCountry').val()) {
                            $('.jsUserCountry,.jsUserPreferredCountry').append($("<option selected></option>")
                                .attr({ value: value.iso2, id: value.iso2, 'data-country': value.name })
                                .text(value.name));
                            // debugger;
                            me.loadCity = 1;
                        } else {
                            $('.jsUserCountry,.jsUserPreferredCountry').append($("<option></option>")
                                .attr({ value: value.iso2, id: value.iso2, 'data-country': value.name })
                                .text(value.name));
                        }

                    });
                    if (me.loadCity == 1) {
                        $('.jsUserCountry').trigger("change");
                        // me.loadCity = 0;

                    }
                }
            })
        },

        addEvents: function() {


            $('.jsUserRole').on('change', function() {
                // if ($(this).val() == 3) {
                //     $('.jsUserCountry,.jsUserCityWrap').css('display','none');
                // }
                // else{
                //     $('.jsUserCountry,.jsUserCityWrap').css('display','block');
                // }
            });

            $('.jsUserCountry,.jsUserPreferredCountry').on('change', $.proxy(this._showCities, this));
        },

        _showCities(e) {

            var cityClass;
            console.log($(e.target).attr('class').split(' ')[0]);

            if ($(e.target).attr('class').split(' ')[0] == "jsUserCountry") {
                cityClass = ".jsUserCity";
                this.country = $('.jsUserCountry').val();
            } else {
                cityClass = ".jsPreferredCity";
                this.country = $('.jsUserPreferredCountry').val();
            }
            var me = this;
            $.ajax({
                url: "https://api.countrystatecity.in/v1/countries/" + this.country + "/cities",
                beforeSend: function(request) {
                    request.setRequestHeader("X-CSCAPI-KEY", "NUlkeFZrNzBBVEtYSDhvZjFqUkR2VjVuVnVDOTl4cEJ6Sm5DSE9veA==");
                    YouRHired.Utils.showLoader(true);
                },
                type: "GET",
                dataType: "json",
                success: function(response) {
                    YouRHired.Utils.showLoader(false);
                    // $(cityClass).find($('.append').remove());
                    // debugger;
                    if (me.loadCity == 0) {
                        $('.jsUserCity,.jsPreferredCity').find('option').remove();
                        $('.jsUserCity').val(null).trigger('change');
                        me.loadCity = 0;
                    } else {
                        me.loadCity = 0;
                    }

                    if (cityClass == ".jsUserCity") {
                        //  me.appendUserCity = true;
                        me.userCityArray = []
                        $(".jsUserCity option[class='append']").remove();

                    } else {
                        me.userPreferredCityArray = [];
                        $(".jsPreferredCity option[class='append']").remove();
                    }

                    $.each(response, function(key, value) {
                        // console.log(value.id);
                        $(cityClass).append($("<option></option>")
                            .attr({ value: value.name }).addClass('append').attr('id', value.id)
                            .text(value.name));

                        if (cityClass == ".jsUserCity") {

                            me.userCityArray.push(value.id);

                        } else if (cityClass == ".jsPreferredCity") {

                            me.userPreferredCityArray.push(value.id);
                        }


                    });

                    $('.jsHiddenUserCity').val(me.userCityArray);
                    $('.jsHiddenPreferredCity').val(me.userPreferredCityArray);
                    // console.log('User City Array =>  '+me.userCityArray);
                    // console.log('User Preferred  Array =>  '+me.userPreferredCityArray);

                }
            })

        },
    };

    users.init();


});