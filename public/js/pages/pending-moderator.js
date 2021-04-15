$(document).on('ready', function() {



    var categoryObj = {


        init: function() {
            var jQuestions = $(".jsQuestions");
            this.initPickers();
            this.initDataTable();
            this.addEvents();

            // $('.js-mailboxes').tagEditor({
            //     initialTags: [
            //         'tag1', 'tag2', 'tag3'
            //     ],
            //     sortable: true
            // });
            $(".jsMailboxes").select2({
                placeholder: "Mailboxes to scrap from"
            });

            $(".job-read-only-form .select2-search__field").attr('readonly', true);
            // $(".jsJobCategory").select2({
            //     placeholder: "Job Category",
            //     maximumSelectionLength: 1
            // });
            $(".jsJobWorkingHour").select2({
                placeholder: "Working hours",
                maximumSelectionLength: 1
            });
            $(".jsTags").select2({
                placeholder: "Job Tags",
            });
            $(".jsUserCity").select2({
                placeholder: "Cities",
            });
            jQuestions.select2({
                placeholder: "Create Questions"
            });
            // jQuestions.on("select2:change", $.proxy(this.onSelectChange, this));
            jQuestions.on("change", $.proxy(this.onSelectChange, this));

        },
        onSelectChange: function(e) {},
        initPickers: function() {
            if ($(".category").length > 0) {
                var jStartDateTime = $(".jsStartDateTime"),
                    jEndDateTime = $(".jsEndDateTime"),
                    curEl,
                    dtEndPicker;

                jStartDateTime.flatpickr({
                    allowInput: false,
                    altInput: true,
                    altFormat: "d-m-Y H:i",
                    dateFormat: "Y-m-d H:i:S",
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

                $("#status").on("change", function(e) {
                    $(".jsStatusLabel").html(
                        e.target.checked ?
                        "Active" :
                        "Paused");
                })
            }
        },
        initDataTable: function() {

            if ($(".page_pending-moderator").length > 0) {
                var filterTpl = Handlebars.compile($("#category-filter-detail-template").html());
                $('.js-pending-jobs-listing').DataTable({
                    dom: '<"datatable__header g-cleared"f>rt<"datatable__footer g-cleared"p><"clear">',
                    stateSave: true,
                    language: {
                        search: "_INPUT_",
                        searchPlaceholder: "Search"
                    },
                    processing: true,
                    serverSide: true,
                    ajax: '/pending-moderator/jobs',
                    columns: [{
                        data: 'title',
                        name: 'title'
                    }, {
                        data: 'subject_filter',
                        name: 'subject_filter'
                    }, {
                        data: 'mailboxes',
                        name: 'mailboxes',
                        orderable: false,
                        searchable: false
                    }, {
                        data: 'status',
                        name: 'status'
                    }, {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }],
                    "createdRow": function(row, data, index) {
                        var html = "";
                        //console.log(data);
                        var status = getStatusNameByKeyId(data["status"]);
                        html = "<div class='status status_" + (
                            status.toLowerCase()).replace(" ", "-") + "'>" + status + "</div>";
                        $('td', row).eq(3).html(html);

                        html = filterTpl(data);
                        $('td', row).eq(1).html(html);
                        // $('td', row).eq(0).addClass("datatable__detail datatable__detail_first");
                        // if (data['parent_id'] != null) {
                        //     $('td', row).eq(0).html(jBtn + data['subject']);
                        // }

                    }
                });

                function getStatusNameByKeyId(id) {

                    id = id.toString();
                    console.log(id);

                    switch (id) {
                        case "1":
                            return "Active";
                        case "2":
                            return "Paused";
                        case "3":
                            return "Pending";
                        default:
                            return "Paused";
                    }
                }

            }
        },
        
        addEvents: function() {
            $('#rejectbtn').on('click', function(e){
                e.preventDefault();
                swal({
                    title: "Write Reason For reject!",
                    showCancelButton: true,
                    closeOnConfirm: false,
                    animation: "slide-from-top",
                    inputPlaceholder: "Write something",
                    content: {

                        element: "input",
      
                        attributes: {
      
                        placeholder: "Enter your Reason",
    
                        type: "text",

                        required: "required"
      
                        }
                    },
                    buttons: {
                        confirm: {
                            text: 'Submit',
                            closeModal: false
                        }
                    }
                }).then((result)=>{
                    if(result){              
                        $('#reason').val(result);
                        $('#js-accept-job').submit();
                    }
                }
                )
               return false;
            })
            
        }
    }

    categoryObj.init();

});