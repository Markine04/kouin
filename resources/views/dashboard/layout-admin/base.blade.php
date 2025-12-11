<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">

<head>
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="Portal - Bootstrap 5 Admin Dashboard Template For Developers">
    <meta name="author" content="Xiaoying Riley at 3rd Wave Media">
    <link rel="shortcut icon" href="favicon.ico">

    <!-- App CSS -->
    <link id="theme-style" rel="stylesheet" href="{{ asset('assets/admins/css/portal.css') }}">

</head>

<body class="app">
    @include('dashboard.layout-admin.header')
    <div class="app-wrapper">
        @yield('content')
        @include('dashboard.layout-admin.foot')
    </div><!--//app-wrapper-->



    <!-- Modal Popup Bid -->

     <div class="modal fade bd-example-modal-lg" id="commonModal" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"></h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>

</body>
<!-- FontAwesome JS-->
<script defer src="{{ asset('assets/admins/js/all.min.js') }}"></script>
<script src="{{ asset('assets/admins/js/bootstrap.min.js') }}"></script>
{{-- <script src="{{ asset('assets/js/jquery-3.6.0.min.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/ma-modal.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/notify/notify-script.js') }}"></script> --}}
{{-- <script src="{{ asset('assets/js/script.js') }}"></script> --}}
    <script src="{{ asset('assets/admins/js/index-charts.js') }}"></script> 
    <script src="{{ asset('assets/admins/js/app.js') }}"></script> 



<!-- Javascript -->





<script>
    $(document).ready(function() {
        $('.js-example-basic-single').select2();
    });

    $(document).ready(function() {
        $('.js-example-basic-multiple').select2();
    });

    $(function() {
        // Summernote
        $('#summernote').summernote()

        // CodeMirror
        CodeMirror.fromTextArea(document.getElementById("codeMirrorDemo"), {
            mode: "htmlmixed",
            theme: "monokai"
        });
    });


    @if(session('success'))
    $.notify({
        message: "{{ session('success') }}"
    },
        {
        type: 'success',
        allow_dismiss: true,
        placement: { from: 'top', align: 'right' },
        delay: 3000,
        timer: 500,
        z_index: 9999,
        animate: { enter: 'animated fadeInDown', exit: 'animated fadeOutUp' }
    });
@endif

@if(session('error'))
    $.notify({
        message: "{{ session('error') }}"
    },{
        type: 'danger',
        allow_dismiss: true,
        placement: { from: 'top', align: 'right' },
        delay: 3000,
        timer: 500,
        z_index: 9999,
        animate: { enter: 'animated fadeInDown', exit: 'animated fadeOutUp' }
    });
@endif

@if(session('warning'))
    $.notify({
        message: "{{ session('warning') }}"
    },{
        type: 'warning',
        allow_dismiss: true,
        placement: { from: 'top', align: 'right' },
        delay: 3000,
        timer: 500,
        z_index: 9999,
        animate: { enter: 'animated fadeInDown', exit: 'animated fadeOutUp' }
    });
@endif
</script>


</html>
