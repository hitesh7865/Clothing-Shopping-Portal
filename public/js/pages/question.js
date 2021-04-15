(function() {

    var questionObj = {
        init: function() {
            this.setDefaults();
            this.initDataTable();
            this.initValidations();
            this.addEvents();

            this.updateOptionState();
        },
        setDefaults: function() {
            this.jJobsListing = $(".jsJobsListing");
            this.jQuestionStatus = $(".jsQuestionStatus");
            this.jOptions = $(".jsOptions");
            this.jQuestionSets = $(".jsQuestionSets");
            this.jOptionsWrapper = $(".jsOptionsWrapper");
            this.jPositives = $(".jsPositives");
            this.jNegatives = $(".jsNegatives");

            this.jQuestionType = $(".jsQuestionType");
            this.jOptions.select2({
                tags: true
            });
            this.jQuestionSets.select2({
                tags: false
            });
            this.jPositives.select2({
                tags: true
            });
            this.jNegatives.select2({
                tags: true
            });
        },
        initValidations: function() {
            var me = this;
            if ($('.js-question-form').length > 0) {
                var config = YouRHired.Utils.getValidationConfig({
                    ignore: [],
                    rules: {
                        question: 'required',
                        "options[]": {
                            minlength: 2,
                            "required": function(element) {
                                return (me.jQuestionType.val() == 1 || me.jQuestionType.val() == 2) ? true : false;
                            }
                        }
                    },
                    messages: {
                        question: "Please enter a valid question to ask",
                        "options[]": {
                            "required": "Please create atleast two options",
                            minlength: "Please provide atleast two options"
                        }
                    }
                });

                $('.js-question-form').validate(config);
            }
        },
        initDataTable: function() {
            if ($(".page_questions").length > 0) {
                var self = this;
                $(".js-questions-listing").on('preXhr.dt', function(e, settings, data) {
                    data['category'] = self.jJobsListing.val();
                    data['status'] = self.jQuestionStatus.val();
                });

                var questionListingConfig = window.YouRHired.Utils.getDefaultDTConfig({
                    api: '/api/questions'
                });
                var columnConfig = {
                    "columns": [{
                        data: 'question',
                        name: 'question'
                    }, {
                        data: 'type',
                        name: 'type'
                    }, {
                        data: 'options',
                        name: 'options'
                    }, {
                        data: 'positives',
                        name: 'positives'
                    }, {
                        data: 'negatives',
                        name: 'negatives'
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }]
                };

                $.extend(questionListingConfig, questionListingConfig, columnConfig);
                $('.js-questions-listing').DataTable(questionListingConfig);
            }
        },
        onFilterChange: function() {
            var currentUrl = window.location.href;
            var params = YouRHired.Utils.getUrlVars();
            params['category'] = this.jJobsListing.val();
            params['status'] = this.jQuestionStatus.val();

            var url = location.origin + "/questions?" + $.param(params);
            location.href = url;
        },
        onOptionsChange: function(e) {
            var jOptions = this.jOptions.val();
            this.jPositives.val(null);
            this.jPositives.find("option").remove();
            this.jNegatives.find("option").remove();
            for (var i = 0; i < jOptions.length; i++) {
                this.jPositives.append(new Option(jOptions[i], jOptions[i], false, false));
                this.jNegatives.append(new Option(jOptions[i], jOptions[i], false, false));
            }
        },
        onQuestionTypeChange: function() {
            this.updateOptionState();
        },
        updateOptionState: function() {
            if (this.jQuestionType.val() == "3" || this.jQuestionType.val() == "4") {
                this.jOptionsWrapper.hide(); // Hide the parent
                this.jOptions.val(null).trigger("change"); // Clear any options created
            } else {

                this.jOptionsWrapper.show();
            }
        },
        addEvents: function() {
            this.jJobsListing.on("change", $.proxy(this.onFilterChange, this));
            this.jQuestionStatus.on("change", $.proxy(this.onFilterChange, this));

            this.jQuestionType.on("change", $.proxy(this.onQuestionTypeChange, this));

            this.jOptions.on("change", $.proxy(this.onOptionsChange, this));
        }
    };

    questionObj.init();

})();