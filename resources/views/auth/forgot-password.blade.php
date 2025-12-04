@extends('layouts.layoutlogin')
<link href="{{asset('assets/signin.css')}}" rel="stylesheet">

@section('content')

    <style>
        .bd-placeholder-img {
        font-size: 1.125rem;
        text-anchor: middle;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        }

        @media (min-width: 768px) {
        .bd-placeholder-img-lg {
            font-size: 3.5rem;
        }
        }
        .button :hover{
            background-color: red;
        }
    </style>


    <div class="container">
        <div class="alert alert-warning alert-dismissible fade show" role="alert" style="transition-duration: 5s;">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}

          </div>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="row">
            <div class="col-4"></div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <center><img class="mb-2" src="{{asset('assets/img/logo-bg.png')}}" alt="" width="90" height="73"></center>

                    </div>
                    <div class="card-body">
                        <center>
                            <div class="row">
                                <div class="col">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full form-control" type="email" name="email" :value="old('email')" required autofocus />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2 alert alert-warning" data-bs-dismiss="alert" aria-label="Close"  />
                                </div>
                            </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="btn btn-success">
                                {{ __('Email Password Reset Link') }}
                            </button>
                        </div>
                    </center>
                    </div>
                </div>
            </div>
            <div class="col-4"></div>
        </div>
    </form>
</div>
@endsection
