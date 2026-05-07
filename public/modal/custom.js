/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 *
 */

"use strict";

// $(document).ready(function() {
//
// });

$(function () {
    if ($('.custom-scroll').length) {
        $(".custom-scroll").niceScroll();
        $(".custom-scroll-horizontal").niceScroll();
    }

    if ($('.activity-wrap').length) {
        $(".activity-wrap").niceScroll();
    }

    if ($(".select2").length > 0) {
        $(".select2").select2({
            disableOnMobile: false,
            nativeOnMobile: false
        });


    }

    common_bind_confirmation();


});

$(document).ready(function () {
    $(window).resize();

    $('[data-toggle="tooltip"]').tooltip();
    if ($('.dataTable').length) {
        $(".dataTable").dataTable({
            language: dataTabelLang,
        })
    }
    if ($('#dataTable2').length) {
        $("#dataTable2").dataTable({
            language: dataTabelLang,
            "columnDefs": [
                {"sortable": false, "targets": [1]}
            ]
        })
    }

    $('.timepicker').timepicker({
        showMeridian: false,
        minuteStep: 5,
    });

    $('#commonModal-right').on('shown.bs.modal', function () {
        $(document).off('focusin.modal');
    });


    $('.timepicker').timepicker({
        showMeridian: false,
        minuteStep: 5,
    });

    $('.dropdown-steady').click(function (e) {
        e.stopPropagation();
    });


    // for Choose file
    $(document).on('change', 'input[type=file]', function () {
        var fileclass = $(this).attr('data-filename');
        var finalname = $(this).val().split('\\').pop();
        $('.' + fileclass).html(finalname);
    });

    if ($(".summernote-simple").length) {
        $('.summernote-simple').summernote({
            dialogsInBody: !0,
            minHeight: 200,
            toolbar: [
                ['style', ['style']],
                ["font", ["bold", "italic", "underline", "clear", "strikethrough"]],
                ['fontname', ['fontname']],
                ['color', ['color']],
                ["para", ["ul", "ol", "paragraph"]],
            ]
        });
    }

});

function show_toastr(title, message, type) {
    var o, i;
    var icon = '';
    var cls = '';

    if (type == 'success') {
        icon = 'fas fa-check-circle';
        cls = 'success';
    } else {
        icon = 'fas fa-times-circle';
        cls = 'danger';
    }

    $.notify({icon: icon, title: " " + title, message: message, url: ""}, {
        element: "body",
        type: cls,
        allow_dismiss: !0,
        placement: {from: 'top', align: 'right'},
        offset: {x: 15, y: 15},
        spacing: 10,
        z_index: 1080,
        delay: 2500,
        timer: 2000,
        url_target: "_blank",
        mouse_over: !1,
        animate: {enter: o, exit: i},
        template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
    });
}

$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]', function () {
    var title = $(this).data('title');
    if ($(this).data('type') !='statistic') {
        var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
        var url = $(this).data('url');
        $("#commonModal #myLargeModalLabel").html(title);
        $("#commonModal .modal-dialog").addClass('modal-' + size);
        $.ajax({
            url: url,
            success: function (data) {
                $('#commonModal .modal-body').html(data);
                $("#commonModal").modal('show');
                daterange_set();
                taskCheckbox();
                common_bind("#commonModal");
                commonLoader();
            },
            error: function (data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error')
            }
        });
    }else{
        var url = $(this).data('url');
        $.ajax({
            url: url,
            success: function (data) {
                $("#myModal .modal-dialog").addClass('fadeIn');
                $('#myModal #view').html(data);
                $("#myModal").modal('show');
            },
            error: function (data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error')
            }
        });
    }



});



$(document).ready(function () {
    $('#form-register-step-1').submit(function (e) {
        e.preventDefault();

        var form_data = $(this).serialize()
        var parti = $('#parti').val();
        var profe = $('#profe').val();
        // var is_active = $('#k-'+id).val();
        // alert(id);
        // console.log($('meta[name="csrf-token"]').attr('content'));
        $.ajax({
                type: "POST",
                url: "register",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data:form_data,
                success: function (data) {
                        // console.log(data.message);

                    show_toastr('Success', data.message, 'success');
                    $('.login-form').empty()
                    $('.login-form').load('register-step-2-form');
                    $('.step-register-title').val(data.title);
                    $('#typClient').attr('value',data.last_insert_id);
                    // window.location.href = "register-step-2-form";

                    // $('#commonModal .modal-body').html(data);
                    // $("#commonModal").modal('show');
                    // daterange_set();
                    // taskCheckbox();
                    // common_bind("#commonModal");
                    // commonLoader();
                    // $('#rep').val('');
                },
                error: function (data) {
                    // console.log(data.status);
                    if(data.status == 422){
                        data = data.responseJSON;
                        // console.log(data.errors.typeClient[0]);
                        show_toastr('Error', data.errors.typeClient[0], 'error');
                    }
                }
            });
    });
});




$(document).ready(function () {
    $('.is_active').click(function (e) {
        var id = $(this).attr('id');
        $.ajax({
            type: "POST",
            url: "activer-un-type-de-plan-comptable",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{'id':id},
            success: function (data) {
                show_toastr('Success', data, 'success')
            },
            error: function (data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error')
            }
        });
    });
});

$(document).ready(function () {
    $('.ll').click(function (e) {


        var id = $(this).attr('id');
        // var is_active = $('#k-'+id).val();
        // alert(id);
        // console.log($('meta[name="csrf-token"]').attr('content'));
        $.ajax({
            type: "POST",
            url: "activer-un-type-monnaie",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{'id':id},
            success: function (data) {
                show_toastr('Success', data, 'success')

                // $('#commonModal .modal-body').html(data);
                // $("#commonModal").modal('show');
                // daterange_set();
                // taskCheckbox();
                // common_bind("#commonModal");
                // commonLoader();
                // $('#rep').val('');
            },
            error: function (data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error')
            }
        });
    });
});


$(document).ready(function () {
    $('.le').click(function (e) {


        var id = $(this).attr('id');
        // var is_active = $('#k-'+id).val();
        // alert(id);
        // console.log($('meta[name="csrf-token"]').attr('content'));
        $.ajax({
            type: "POST",
            url: "activer-un-type-pays",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data:{'id':id},
            success: function (data) {
                show_toastr('Success', data, 'success')

                // $('#commonModal .modal-body').html(data);
                // $("#commonModal").modal('show');
                // daterange_set();
                // taskCheckbox();
                // common_bind("#commonModal");
                // commonLoader();
                // $('#rep').val('');
            },
            error: function (data) {
                data = data.responseJSON;
                show_toastr('Error', data.error, 'error')
            }
        });
    });
});

    $(document).ready(function () {
        $('#form_response').submit(function (e) {
            e.preventDefault();
            // alert('ok')
            var form_data = $(this).serialize()
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data:form_data,
                success: function (data) {

                    $('#commonModal .modal-body').html(data);
                    $("#commonModal").modal('show');
                    daterange_set();
                    taskCheckbox();
                    common_bind("#commonModal");
                    commonLoader();
                    $('#rep').val('');
                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('Error', data.error, 'error')
                }
            });
        });
    });

    $(document).ready(function () {
        $('#reps').submit(function (e) {
            e.preventDefault();
            // alert('ok')
            var form_data = $(this).serialize()
            $.ajax({
                type: $(this).attr('method'),
                url: $(this).attr('action'),
                data:form_data,
                success: function (data) {

                    $('#commonModal .modal-body').html(data);
                    $("#commonModal").modal('show');
                    daterange_set();
                    taskCheckbox();
                    common_bind("#commonModal");
                    commonLoader();
                    $('#exampleFormControlTextarea1').val('');
                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('Error', data.error, 'error');
                }
            });
        });
    });







// Common Modal from right side
$(document).on('click', 'a[data-ajax-popup-right="false"], button[data-ajax-popup-right="false"], div[data-ajax-popup-right="false"], span[data-ajax-popup-right="false"]', function (e) {
    var url = $(this).data('url');

    $.ajax({
        url: url,
        cache: false,
        success: function (data) {
            $('#view').html(data);
            $("#myModal").modal('show');
            commonLoader();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
});

$(document).on('click', 'a[data-ajax-popup-over="true"], button[data-ajax-popup-over="true"], div[data-ajax-popup-over="true"]', function () {

    var validate = $(this).attr('data-validate');
    var id = '';
    if (validate) {
        id = $(validate).val();
    }

    var title = $(this).data('title');
    var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
    var url = $(this).data('url');

    $("#commonModalOver .modal-title").html(title);
    $("#commonModalOver .modal-dialog").addClass('modal-' + size);

    $.ajax({
        url: url + '?id=' + id,
        success: function (data) {
            $('#commonModalOver .modal-body').html(data);
            $("#commonModalOver").modal('show');
            taskCheckbox();
        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });

});



function arrayToJson(form) {
    var data = $(form).serializeArray();
    var indexed_array = {};

    $.map(data, function (n, i) {
        indexed_array[n['name']] = n['value'];
    });

    return indexed_array;
}

$(document).on("submit", "#commonModalOver form", function (e) {
    e.preventDefault();
    var data = arrayToJson($(this));
    data.ajax = true;

    var url = $(this).attr('action');
    $.ajax({
        url: url,
        data: data,
        type: 'POST',
        success: function (data) {
            show_toastr('Success', data.success, 'success');
            $(data.target).append('<option value="' + data.record.id + '">' + data.record.name + '</option>');
            $(data.target).val(data.record.id);
            $(data.target).trigger('change');
            $("#commonModalOver").modal('hide');

            $(".selectric").selectric({
                disableOnMobile: false,
                nativeOnMobile: false
            });

        },
        error: function (data) {
            data = data.responseJSON;
            show_toastr('Error', data.error, 'error')
        }
    });
});


function common_bind_confirmation() {
    $('[data-confirm]').each(function () {
        var me = $(this),
            me_data = me.data('confirm');

        me_data = me_data.split("|");
        me.fireModal({
            title: me_data[0],
            body: me_data[1],
            buttons: [
                {
                    text: me.data('confirm-text-yes') || 'Yes',
                    class: 'btn btn-sm btn-danger rounded-pill',
                    handler: function () {
                        eval(me.data('confirm-yes'));
                    }
                },
                {
                    text: me.data('confirm-text-cancel') || 'Cancel',
                    class: 'btn btn-sm btn-secondary rounded-pill',
                    handler: function (modal) {
                        $.destroyModal(modal);
                        eval(me.data('confirm-no'));
                    }
                }
            ]
        })
    });
}

function taskCheckbox() {
    var checked = 0;
    var count = 0;
    var percentage = 0;

    count = $("#check-list input[type=checkbox]").length;
    checked = $("#check-list input[type=checkbox]:checked").length;
    percentage = parseInt(((checked / count) * 100), 10);
    if (isNaN(percentage)) {
        percentage = 0;
    }
    $(".custom-label").text(percentage + "%");
    $('#taskProgress').css('width', percentage + '%');


    $('#taskProgress').removeClass('bg-warning');
    $('#taskProgress').removeClass('bg-primary');
    $('#taskProgress').removeClass('bg-success');
    $('#taskProgress').removeClass('bg-danger');

    if (percentage <= 15) {
        $('#taskProgress').addClass('bg-danger');
    } else if (percentage > 15 && percentage <= 33) {
        $('#taskProgress').addClass('bg-warning');
    } else if (percentage > 33 && percentage <= 70) {
        $('#taskProgress').addClass('bg-primary');
    } else {
        $('#taskProgress').addClass('bg-success');
    }
}

(function ($, window, i) {
    // Bootstrap 4 Modal
    $.fn.fireModal = function (options) {
        var options = $.extend({
            size: 'modal-md',
            center: false,
            animation: true,
            title: 'Modal Title',
            closeButton: false,
            header: true,
            bodyClass: '',
            footerClass: '',
            body: '',
            buttons: [],
            autoFocus: true,
            created: function () {
            },
            appended: function () {
            },
            onFormSubmit: function () {
            },
            modal: {}
        }, options);
        this.each(function () {
            i++;
            var id = 'fire-modal-' + i,
                trigger_class = 'trigger--' + id,
                trigger_button = $('.' + trigger_class);
            $(this).addClass(trigger_class);
            // Get modal body
            let body = options.body;
            if (typeof body == 'object') {
                if (body.length) {
                    let part = body;
                    body = body.removeAttr('id').clone().removeClass('modal-part');
                    part.remove();
                } else {
                    body = '<div class="text-danger">Modal part element not found!</div>';
                }
            }
            // Modal base template
            var modal_template = '   <div class="modal' + (options.animation == true ? ' fade' : '') + '" tabindex="-1" role="dialog" id="' + id + '">  ' +
                '     <div class="modal-dialog ' + options.size + (options.center ? ' modal-dialog-centered' : '') + '" role="document">  ' +
                '       <div class="modal-content">  ' +
                ((options.header == true) ?
                    '         <div class="modal-header">  ' +
                    '           <h5 class="modal-title mx-auto">' + options.title + '</h5>  ' +
                    ((options.closeButton == true) ?
                        '           <button type="button" class="close" data-dismiss="modal" aria-label="Close">  ' +
                        '             <span aria-hidden="true">&times;</span>  ' +
                        '           </button>  '
                        : '') +
                    '         </div>  '
                    : '') +
                '         <div class="modal-body text-center text-dark">  ' +
                '         </div>  ' +
                (options.buttons.length > 0 ?
                    '         <div class="modal-footer mx-auto">  ' +
                    '         </div>  '
                    : '') +
                '       </div>  ' +
                '     </div>  ' +
                '  </div>  ';
            // Convert modal to object
            var modal_template = $(modal_template);
            // Start creating buttons from 'buttons' option
            var this_button;
            options.buttons.forEach(function (item) {
                // get option 'id'
                let id = "id" in item ? item.id : '';
                // Button template
                this_button = '<button type="' + ("submit" in item && item.submit == true ? 'submit' : 'button') + '" class="' + item.class + '" id="' + id + '">' + item.text + '</button>';
                // add click event to the button
                this_button = $(this_button).off('click').on("click", function () {
                    // execute function from 'handler' option
                    item.handler.call(this, modal_template);
                });
                // append generated buttons to the modal footer
                $(modal_template).find('.modal-footer').append(this_button);
            });
            // append a given body to the modal
            $(modal_template).find('.modal-body').append(body);
            // add additional body class
            if (options.bodyClass) $(modal_template).find('.modal-body').addClass(options.bodyClass);
            // add footer body class
            if (options.footerClass) $(modal_template).find('.modal-footer').addClass(options.footerClass);
            // execute 'created' callback
            options.created.call(this, modal_template, options);
            // modal form and submit form button
            let modal_form = $(modal_template).find('.modal-body form'),
                form_submit_btn = modal_template.find('button[type=submit]');
            // append generated modal to the body
            $("body").append(modal_template);
            // execute 'appended' callback
            options.appended.call(this, $('#' + id), modal_form, options);
            // if modal contains form elements
            if (modal_form.length) {
                // if `autoFocus` option is true
                if (options.autoFocus) {
                    // when modal is shown
                    $(modal_template).on('shown.bs.modal', function () {
                        // if type of `autoFocus` option is `boolean`
                        if (typeof options.autoFocus == 'boolean')
                            modal_form.find('input:eq(0)').focus(); // the first input element will be focused
                        // if type of `autoFocus` option is `string` and `autoFocus` option is an HTML element
                        else if (typeof options.autoFocus == 'string' && modal_form.find(options.autoFocus).length)
                            modal_form.find(options.autoFocus).focus(); // find elements and focus on that
                    });
                }
                // form object
                let form_object = {
                    startProgress: function () {
                        modal_template.addClass('modal-progress');
                    },
                    stopProgress: function () {
                        modal_template.removeClass('modal-progress');
                    }
                };
                // if form is not contains button element
                if (!modal_form.find('button').length) $(modal_form).append('<button class="d-none" id="' + id + '-submit"></button>');
                // add click event
                form_submit_btn.click(function () {
                    modal_form.submit();
                });
                // add submit event
                modal_form.submit(function (e) {
                    // start form progress
                    form_object.startProgress();
                    // execute `onFormSubmit` callback
                    options.onFormSubmit.call(this, modal_template, e, form_object);
                });
            }
            $(document).on("click", '.' + trigger_class, function () {
                $('#' + id).modal(options.modal);
                return false;
            });
        });
    }

    // Bootstrap Modal Destroyer
    $.destroyModal = function (modal) {
        modal.modal('hide');
        modal.on('hidden.bs.modal', function () {
        });
    }
})(jQuery, this, 0);

var Charts = (function () {
    // Variable
    var $toggle = $('[data-toggle="chart"]');
    var mode = 'light';//(themeMode) ? themeMode : 'light';
    var fonts = {
        base: 'Open Sans'
    }

    // Colors
    var colors = {
        gray: {
            100: '#f6f9fc',
            200: '#e9ecef',
            300: '#dee2e6',
            400: '#ced4da',
            500: '#adb5bd',
            600: '#8898aa',
            700: '#525f7f',
            800: '#32325d',
            900: '#212529'
        },
        theme: {
            'default': '#172b4d',
            'primary': '#5e72e4',
            'secondary': '#f4f5f7',
            'info': '#11cdef',
            'success': '#2dce89',
            'danger': '#f5365c',
            'warning': '#fb6340'
        },
        black: '#12263F',
        white: '#FFFFFF',
        transparent: 'transparent',
    };


    // Methods

    // Chart.js global options
    function chartOptions() {

        // Options
        var options = {
            defaults: {
                global: {
                    responsive: true,
                    maintainAspectRatio: false,
                    defaultColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
                    defaultFontColor: (mode == 'dark') ? colors.gray[700] : colors.gray[600],
                    defaultFontFamily: fonts.base,
                    defaultFontSize: 13,
                    layout: {
                        padding: 0
                    },
                    legend: {
                        display: false,
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 16
                        }
                    },
                    elements: {
                        point: {
                            radius: 0,
                            backgroundColor: colors.theme['primary']
                        },
                        line: {
                            tension: .4,
                            borderWidth: 4,
                            borderColor: colors.theme['primary'],
                            backgroundColor: colors.transparent,
                            borderCapStyle: 'rounded'
                        },
                        rectangle: {
                            backgroundColor: colors.theme['warning']
                        },
                        arc: {
                            backgroundColor: colors.theme['primary'],
                            borderColor: (mode == 'dark') ? colors.gray[800] : colors.white,
                            borderWidth: 4
                        }
                    },
                    tooltips: {
                        enabled: true,
                        mode: 'index',
                        intersect: false,
                    }
                },
                doughnut: {
                    cutoutPercentage: 83,
                    legendCallback: function (chart) {
                        var data = chart.data;
                        var content = '';

                        data.labels.forEach(function (label, index) {
                            var bgColor = data.datasets[0].backgroundColor[index];

                            content += '<span class="chart-legend-item">';
                            content += '<i class="chart-legend-indicator" style="background-color: ' + bgColor + '"></i>';
                            content += label;
                            content += '</span>';
                        });

                        return content;
                    }
                }
            }
        }

        // yAxes
        Chart.scaleService.updateScaleDefaults('linear', {
            gridLines: {
                borderDash: [2],
                borderDashOffset: [2],
                color: (mode == 'dark') ? colors.gray[900] : colors.gray[300],
                drawBorder: false,
                drawTicks: false,
                drawOnChartArea: true,
                zeroLineWidth: 0,
                zeroLineColor: 'rgba(0,0,0,0)',
                zeroLineBorderDash: [2],
                zeroLineBorderDashOffset: [2]
            },
            ticks: {
                beginAtZero: true,
                padding: 10,
                callback: function (value) {
                    if (!(value % 10)) {
                        return value
                    }
                }
            }
        });

        // xAxes
        Chart.scaleService.updateScaleDefaults('category', {
            gridLines: {
                drawBorder: false,
                drawOnChartArea: false,
                drawTicks: false
            },
            ticks: {
                padding: 20
            },
            maxBarThickness: 10
        });

        return options;

    }

    // Parse global options
    function parseOptions(parent, options) {
        for (var item in options) {
            if (typeof options[item] !== 'object') {
                parent[item] = options[item];
            } else {
                parseOptions(parent[item], options[item]);
            }
        }
    }

    // Push options
    function pushOptions(parent, options) {
        for (var item in options) {
            if (Array.isArray(options[item])) {
                options[item].forEach(function (data) {
                    parent[item].push(data);
                });
            } else {
                pushOptions(parent[item], options[item]);
            }
        }
    }

    // Pop options
    function popOptions(parent, options) {
        for (var item in options) {
            if (Array.isArray(options[item])) {
                options[item].forEach(function (data) {
                    parent[item].pop();
                });
            } else {
                popOptions(parent[item], options[item]);
            }
        }
    }

    // Toggle options
    function toggleOptions(elem) {
        var options = elem.data('add');
        var $target = $(elem.data('target'));
        var $chart = $target.data('chart');

        if (elem.is(':checked')) {

            // Add options
            pushOptions($chart, options);

            // Update chart
            $chart.update();
        } else {

            // Remove options
            popOptions($chart, options);

            // Update chart
            $chart.update();
        }
    }

    // Update options
    function updateOptions(elem) {
        var options = elem.data('update');
        var $target = $(elem.data('target'));
        var $chart = $target.data('chart');

        // Parse options
        parseOptions($chart, options);

        // Toggle ticks
        toggleTicks(elem, $chart);

        // Update chart
        $chart.update();
    }

    // Toggle ticks
    function toggleTicks(elem, $chart) {

        if (elem.data('prefix') !== undefined || elem.data('prefix') !== undefined) {
            var prefix = elem.data('prefix') ? elem.data('prefix') : '';
            var suffix = elem.data('suffix') ? elem.data('suffix') : '';

            // Update ticks
            $chart.options.scales.yAxes[0].ticks.callback = function (value) {
                if (!(value % 10)) {
                    return prefix + value + suffix;
                }
            }

            // Update tooltips
            $chart.options.tooltips.callbacks.label = function (item, data) {
                var label = data.datasets[item.datasetIndex].label || '';
                var yLabel = item.yLabel;
                var content = '';

                if (data.datasets.length > 1) {
                    content += '<span class="popover-body-label mr-auto">' + label + '</span>';
                }

                content += '<span class="popover-body-value">' + prefix + yLabel + suffix + '</span>';
                return content;
            }

        }
    }


    // Events

    // Parse global options
    if (window.Chart) {
        parseOptions(Chart, chartOptions());
    }

    // Toggle options
    $toggle.on({
        'change': function () {
            var $this = $(this);

            if ($this.is('[data-add]')) {
                toggleOptions($this);
            }
        },
        'click': function () {
            var $this = $(this);

            if ($this.is('[data-update]')) {
                updateOptions($this);
            }
        }
    });


    // Return

    return {
        colors: colors,
        fonts: fonts,
        mode: mode
    };

})();


function loadConfirm() {
    $('[data-confirm]').each(function () {
        var me = $(this),
            me_data = me.data('confirm');

        me_data = me_data.split("|");
        me.fireModal({
            title: me_data[0],
            body: me_data[1],
            buttons: [
                {
                    text: me.data('confirm-text-yes') || 'Yes',
                    class: 'btn btn-sm btn-danger rounded-pill',
                    handler: function () {
                        eval(me.data('confirm-yes'));
                    }
                },
                {
                    text: me.data('confirm-text-cancel') || 'Cancel',
                    class: 'btn btn-sm btn-secondary rounded-pill',
                    handler: function (modal) {
                        $.destroyModal(modal);
                        eval(me.data('confirm-no'));
                    }
                }
            ]
        })
    });
}



function postAjax(url, data, cb) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var jdata = { _token: token };

    for (var k in data) {
        jdata[k] = data[k];
    }

    $.ajax({
        type: 'POST',
        url: url,
        data: jdata,
        success: function(data) {
            if (typeof(data) === 'object') {
                cb(data);
            } else {
                cb(data);
            }
        },
    });
}

function deleteAjax(url, data, cb) {
    var token = $('meta[name="csrf-token"]').attr('content');
    var jdata = { _token: token };

    for (var k in data) {
        jdata[k] = data[k];
    }

    $.ajax({
        type: 'DELETE',
        url: url,
        data: jdata,
        success: function(data) {
            if (typeof(data) === 'object') {
                cb(data);
            } else {
                cb(data);
            }
        },
    });
}

$(document).on('click', '.fc-day-grid-event', function(e) {
    // if (!$(this).hasClass('project')) {
    e.preventDefault();
    var event = $(this);
    var title = $(this).find('.fc-content .fc-title').html();
    var size = 'md';
    var url = $(this).attr('href');
    $("#commonModal .modal-title").html(title);
    $("#commonModal .modal-dialog").addClass('modal-' + size);
    $.ajax({
        url: url,
        success: function(data) {
            $('#commonModal .modal-body').html(data);
            $("#commonModal").modal('show');
            common_bind();
        },
        error: function(data) {
            data = data.responseJSON;
            toastrs('Error', data.error, 'error')
        }
    });
    // }
});
