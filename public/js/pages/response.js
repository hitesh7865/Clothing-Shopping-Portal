$(document).on("ready", function () {
    var responseObj = {
        _pageEl: $(".page_response"),
        init: function () {
            this.setDefaults();
            this.addEvents();
        },
        setDefaults: function () {
            this.jSelectRange = $(".jsSelectRange");
            this.jSelectTags = $(".jsSelectTags");

            // this.jSelectRange.select2();
            this.jSelectTags.select2();
        },
        addEvents: function () {
            var me = this;
        }
    };
    responseObj.init();
});