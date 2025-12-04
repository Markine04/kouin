<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="">
        <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
        <meta name="generator" content="Hugo 0.84.0">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="{{asset('assets/dist/css/bootstrap.min.css')}}" rel="stylesheet">
        <link href="{{asset('assets/dist/css/bootstrap.css')}}" rel="stylesheet">

            <!-- CSS here -->
        {{-- <link rel="stylesheet" href="{{asset('assets/dist/css/bootstrap.min.css')}}"> --}}
        <link rel="stylesheet" href="{{asset('assets/dist/css/owl.carousel.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dist/css/magnific-popup.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dist/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dist/css/themify-icons.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dist/css/nice-select.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dist/css/flaticon.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dist/css/gijgo.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dist/css/animate.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dist/css/slicknav.css')}}">

        <link rel="stylesheet" href="{{asset('assets/dist/css/style.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dist/css/select2.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dist/css/jquery-ui.css')}}">
        {{-- <link rel="stylesheet" href="{{asset('assets/lte/dist/css/adminlte.min.css')}}"> --}}

        <link rel="stylesheet" href="{{asset('assets/lte/plugins/toastr/toastr.min.css')}}">


        <link rel="stylesheet" href="{{asset('assets/modal/fonts/icomoon/style.css')}}">

        <!-- Bootstrap CSS -->
        {{-- <link rel="stylesheet" href="css/bootstrap.min.css"> --}}

        <!-- Style -->
        <link rel="stylesheet" href="{{asset('assets/modal/css/style.css')}}">


        <!-- Scripts -->
        {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    </head>
    <style>
        a {
            text-decoration :none;
        }
    </style>
    <body>


        {{-- <div class="min-h-screen bg-gray-100 dark:bg-gray-900"> --}}
            @include('layouts.head')

            @php
                if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
                    $ip = $_SERVER['HTTP_CLIENT_IP'];
                }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
                    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
                }else{
                    $ip = $_SERVER['REMOTE_ADDR'];
                }
                // dd($ip)
            @endphp

                <div class="row">
                    <div class="col-9"></div>
                    <div class="col-3">
                            @if (session()->has('success'))
                                <div class="alert alert-success toastrDefaultSuccess" role="alert" style="z-index:-1">
                                    <strong>{{session()->get('success')}}</strong>
                                </div>
                            @endif
                            @if (session()->has('error'))
                                <div class="alert alert-warning toastrDefaultWarning" role="alert" style="z-index:-1">
                                    <button class="close" data-dismiss="alert" data-delay="5000" type="button" aria-label="close">&times;</button>
                                    <strong>{{session()->get('error')}}</strong>
                                </div>
                            @endif
                    </div>
                </div>
            {{-- @endif --}}
            @yield('content')


            @include('layouts.footer')
        {{-- </div> --}}


           <!-- Modal Popup Bid -->

    <div class="modal fade bd-example-modal-lg" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myLargeModalLabel"></h4>
                    <button class="btn-close" type="button" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body"></div>
            </div>
        </div>
    </div>
    
    </body>

    <!-- link that opens popup -->
    <!-- JS here -->
    <script src="{{asset('assets/dist/js/vendor/modernizr-3.5.0.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/vendor/jquery-1.12.4.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/isotope.pkgd.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/ajax-form.js')}}"></script>
    <script src="{{asset('assets/dist/js/waypoints.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/jquery.counterup.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/imagesloaded.pkgd.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/scrollIt.js')}}"></script>
    <script src="{{asset('assets/dist/js/jquery.scrollUp.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/wow.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/nice-select.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/jquery.slicknav.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/jquery.magnific-popup.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/plugins.js')}}"></script>
    <script src="{{asset('assets/dist/js/gijgo.min.js')}}"></script>

    <!--contact js-->
    <script src="{{asset('assets/dist/js/contact.js')}}"></script>
    <script src="{{asset('assets/dist/js/jquery.ajaxchimp.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/jquery.form.js')}}"></script>
    <script src="{{asset('assets/dist/js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/mail-script.js')}}"></script>

    <script src="{{asset('assets/dist/js/range.js')}}"></script>
    {{-- <script src="{{asset('assets/dist/js/main.js')}}"></script> --}}

    <script src="{{asset('assets/dist/js/jquery-3.7.1.js')}}"></script>
    <script src="{{asset('assets/dist/js/jquery.slim.js')}}"></script>

    <script src="{{asset('assets/modal/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('assets/modal/js/popper.min.js')}}"></script>
    <script src="{{asset('assets/modal/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/bootstrap.bundle.min.js')}}"></script>


    <script src="{{asset('assets/modal/js/main.js')}}"></script>


    <script src="{{asset('assets/dist/js/jquery.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/jquery-ui.js')}}"></script>
    <script src="{{asset('assets/dist/js/select2.min.js')}}"></script>
    <script src="{{asset('assets/dist/js/select2.full.min.js')}}"></script>
    {{-- <script src="{{asset('assets/lte/dist/js/adminlte.js')}}"></script> --}}


    <script src="{{asset('assets/lte/plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <!-- Toastr -->
    <script src="{{asset('assets/lte/plugins/toastr/toastr.min.js')}}"></script>


    <script src="{{asset('assets/custom.js') }}"></script>
    <script src="{{ asset('assets/jquery-3.6.0.min.js') }}"></script>
    <script src="{{asset('assets/jquery.ui.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('.js-example-basic-single').select2({
                // placeholder: 'test...'
            });
        });

        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });

        //if(session()->has('success')){
         //  $('.toastrDefaultSuccess').click(function() {
         //  toastr.success('hgcjhkb')
        //   });
        //}
        //if (session()->has('error')){
            // $('.toastrDefaultSuccess').click(function() {
            // toastr.success('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            // });
            // $('.toastrDefaultInfo').click(function() {
            // toastr.info('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            // });
            // $('.toastrDefaultError').click(function() {
            // toastr.error('Lorem ipsum dolor sit amet, consetetur sadipscing elitr.')
            // });
            //$('.toastrDefaultWarning').click(function() {
            //toastr.warning('gfvcvbnb')
            //});
        //}


        $(document).ready(function () {
            let lv = 0;
            $("#like").on("click", function () {

                if (lv == 0) {
                    $('#like').html(
                        '<i class="fa-regular fa-heart"></i>');
                    lv = 1;
                } else {
                    $('#like').html(
                        '<i class="fa-solid fa-heart" style="color: #f52e4b;"></i>');
                    lv = 0
                }
            });
        });
    </script>
</html>
