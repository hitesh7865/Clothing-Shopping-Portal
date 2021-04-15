(function() {

    var Utils = {
        init: function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.validator.addMethod("customTel", function(value) {
                var pattern = /^(?=.*[0-9])[- +()0-9]+$/;

                return pattern.test(value);
            }, 'Please enter a valid telephone number');

            $.validator.addMethod("noDecimal", function(value, element) {
                return !(value % 1);
            }, "Days in part not allowed :)");

            this._registerHelpers();
        },
        getUrlVars: function() {
            var vars = {},
                hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                if (hash.length == 1) {
                    continue;
                }
                vars[hash[0]] = hash[1];
                // vars[hash[0]] = hash[1];
            }
            return vars;
        },
        getDefaultDTConfig: function(config) {
            return {
                dom: '<"datatable__header g-cleared"f>rt<"datatable__footer g-cleared"p><"clear">',
                stateSave: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search"
                },
                processing: true,
                serverSide: true,
                ajax: config.api
            }
        },
        showLoader: function(bShow) {
            if (bShow) {
                $(".loader").fadeIn();
            } else {
                $(".loader").fadeOut();
            }
        },
        showPopup: function(jPopup) {
            $.magnificPopup.open({
                items: {
                    src: jPopup, // can be a HTML string, jQuery object, or CSS selector
                    type: 'inline'
                },
                removeOverlay: 600,
                fixedBgPos: true,
                overflowY: 'auto',
                preloader: false,
                mainClass: 'my-mfp-slide-bottom'
            });
        },
        hidePopup: function() {
            $.magnificPopup.close();
        },
        getValidationConfig: function(config) {
            var defaultConfig = {
                highlight: function(element, errorClass) {
                    $(element).addClass('has-error');
                },
                unhighlight: function(element, errorClass) {
                    $(element).removeClass('has-error');
                },
                errorPlacement: function(error, element) {
                    // error.insertAfter(element);
                    if (element.attr("name") == "checkbox_group[]") {
                        error.insertAfter("#checkbox-error");
                    } else {
                        var allChilds = element.parent().children();
                        var lastChild = allChilds[allChilds.length - 1];

                        error.insertAfter(lastChild);
                    }
                },
                submitHandler: function(form) {
                    debugger;
                    var jForm = $(form);
                    jForm.find('input[type="submit"]').addClass('disabled');
                    form.submit();
                    jForm.find('input[type="submit"]').removeClass('disabled');
                    $('.loading').addClass('g-hidden');
                }
            };

            return $.extend(defaultConfig, config);
        },
        sendRequest: function(config) {
            if (config.skipLoader == undefined || config.skipLoader == false) {
                YouRHired.Utils.showLoader(true);
            }

            var jqxhr = $.ajax({
                url: config.url,
                type: config.type ?
                    config.type : "POST",
                dataType: "JSON",
                data: config.data != undefined ?
                    config.data : null
            }).done(function(response) {
                if (config.onDone) {
                    config.onDone(response);
                }

            }).fail(function() {
                if (config.onFail) {
                    config.onFail();
                }

            }).always(function() {
                YouRHired.Utils.showLoader(false);
                if (config.onAlways) {
                    config.onAlways();
                }
            });
        },
        _registerHelpers: function() {
            Handlebars.registerHelper('ifCond', function(v1, operator, v2, options) {

                switch (operator) {
                    case '==':
                        return (v1 == v2) ?
                            options.fn(this) :
                            options.inverse(this);
                    case '===':
                        return (v1 === v2) ?
                            options.fn(this) :
                            options.inverse(this);
                    case '!=':
                        return (v1 != v2) ?
                            options.fn(this) :
                            options.inverse(this);
                    case '!==':
                        return (v1 !== v2) ?
                            options.fn(this) :
                            options.inverse(this);
                    case '<':
                        return (v1 < v2) ?
                            options.fn(this) :
                            options.inverse(this);
                    case '<=':
                        return (v1 <= v2) ?
                            options.fn(this) :
                            options.inverse(this);
                    case '>':
                        return (v1 > v2) ?
                            options.fn(this) :
                            options.inverse(this);
                    case '>=':
                        return (v1 >= v2) ?
                            options.fn(this) :
                            options.inverse(this);
                    case '&&':
                        return (v1 && v2) ?
                            options.fn(this) :
                            options.inverse(this);
                    case '||':
                        return (v1 || v2) ?
                            options.fn(this) :
                            options.inverse(this);
                    default:
                        return options.inverse(this);
                }
            });

            Handlebars.registerHelper('times', function(n, block) {
                var accum = '';
                for (var i = 0; i < n; ++i)
                    accum += block.fn(i);
                return accum;
            });
        }
    };

    Utils.init();
    window.YouRHired.Utils = Utils;

    $(document).on("shown.bs.dropdown", ".dropdown", function() {
        // calculate the required sizes, spaces
        var $ul = $(this).children(".dropdown-menu");
        var $button = $(this).children(".dropdown-toggle");
        var ulOffset = $ul.offset();
        // how much space would be left on the top if the dropdown opened that direction
        var spaceUp = (ulOffset.top - $button.height() - $ul.height()) - $(window).scrollTop();
        // how much space is left at the bottom
        var spaceDown = $(window).scrollTop() + $(window).height() - (ulOffset.top + $ul.height());
        // switch to dropup only if there is no space at the bottom AND there is space at the top, or there isn't either but it would be still better fit
        if (spaceDown < 0 && (spaceUp >= 0 || spaceUp > spaceDown))
            $(this).addClass("dropup");
    }).on("hidden.bs.dropdown", ".dropdown", function() {
        // always reset after close
        $(this).removeClass("dropup");
    });
})();