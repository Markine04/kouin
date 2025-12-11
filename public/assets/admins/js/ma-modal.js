
$(document).on('click', 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]',
    function () {
        var title = $(this).data('title');
        if ($(this).data('type') != 'statistic') {
            var size = ($(this).data('size') == '') ? 'md' : $(this).data('size');
            var url = $(this).data('url');
            $("#commonModal #myLargeModalLabel").html(title);
            $("#commonModal .modal-dialog").addClass('modal-' + size);
            $.ajax({
                url: url,
                success: function (data) {
                    $('#commonModal .modal-body').html(data);
                    $("#commonModal").modal({
                        backdrop: 'static'
                    }).modal('show');
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
        } else {
            var url = $(this).data('url');
            $.ajax({
                url: url,
                success: function (data) {
                    $("#myModal .modal-dialog").addClass('fadeIn');
                    $('#myModal #view').html(data);
                    $("#myModal").modal({
                        backdrop: 'static'
                    }).modal('show');
                },
                error: function (data) {
                    data = data.responseJSON;
                    show_toastr('Error', data.error, 'error')
                }
            });
        }
    });