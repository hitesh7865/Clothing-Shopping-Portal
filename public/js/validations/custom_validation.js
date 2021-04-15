


    // $.validator.addMethod('less', function(value, element, param) {
    //     return this.optional(element) || value <= $(param).val();
    // }, 'Invalid value');

  $.validator.addMethod('greater', function(value, element, param) {
        return (value >= $(param).val());
}, 'Invalid value');

