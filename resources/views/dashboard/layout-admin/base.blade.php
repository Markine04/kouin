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
    {{-- <link id="theme-style" rel="stylesheet" href="{{asset('assets/admins/css/portal.css')}}"> --}}
    {{-- <link id="theme-style" rel="stylesheet" href="{{ asset('assets/dist/css/select2.min.css') }}"> --}}
    <link id="theme-style" rel="stylesheet" href="{{ asset('assets/dist/css/jquery-ui.css') }}">
    {{-- <link rel="stylesheet" href="{{ asset('assets/lte/plugins/summernote/summernote-bs4.min.css') }}"> --}}

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
<script defer src="{{ asset('assets/admins/plugins/fontawesome/js/all.min.js') }}"></script>
<!-- Javascript -->
<script src="{{ asset('assets/admins/plugins/popper.min.js') }}"></script>
<script src="{{ asset('assets/admins/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
<!-- Charts JS -->
<script src="{{ asset('assets/admins/plugins/chart.js/chart.min.js') }}"></script>
<script src="{{ asset('assets/admins/js/index-charts.js') }}"></script>
<!-- Page Specific JS -->
{{-- <script src="{{ asset('assets/admins/assets/js/app.js') }}"></script> --}}
<script src="{{ asset('assets/dist/js/jquery.slim.js') }}"></script>
{{-- <script src="{{ asset('assets/dist/js/jquery.min.js') }}"></script> --}}
<script src="{{ asset('assets/dist/js/jquery-ui.js') }}"></script>
<script src="{{ asset('assets/dist/js/select2.min.js') }}"></script>
{{-- <script src="{{ asset('assets/dist/js/select2.full.min.js') }}"></script> --}}
<script src="{{ asset('assets/lte/plugins/summernote/summernote-bs4.min.js') }}"></script>
{{-- <script src="{{ asset('assets/lte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script> --}}

<script src="{{ asset('assets/custom.js') }}"></script>
<script src="{{ asset('assets/admins/js/jquery-3.7.1.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

{{-- <script src="{{ asset('assets/jquery.ui.min.js') }}"></script> --}}
{{-- 
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
    })
</script> --}}

</html>
