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
                <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf
                   {{-- <center> --}}
                    {{-- <h1 class="h3 mb-3 fw-normal">Please sign in</h1> --}}
                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-4">
                            <div class="card">
                                <div class="card-body">
                                    <center>
                                        <img class="mb-2" src="{{asset('assets/img/logo-bg.png')}}" alt="" width="90" height="73">

                                        <div class="form-floating">
                                            <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                                            <x-input-error :messages="$errors->get('email')" class="mt-2 alert alert-warning" data-bs-dismiss="alert" aria-label="Close" />
                                            <label for="floatingInput">Adresse Email</label>
                                        </div>
                                        <br>
                                        <div class="form-floating">
                                            <input type="password" class="form-control" name="password" id="floatingPassword" placeholder="Password">
                                            <x-input-error :messages="$errors->get('password')" class="mt-2 alert alert-warning" data-bs-dismiss="alert" aria-label="Close" />
                                            <label for="floatingPassword">Mot de passe</label>
                                        </div>
                                    </center>
                                    <br>
                                    <div class="checkbox mb-3">
                                        <label>
                                            <input type="checkbox" value="remember-me"> Remember me
                                        </label> &nbsp;&nbsp;
                                        @if (Route::has('password.request'))
                                            <a  href="{{ route('password.request') }}">
                                                {{ __('Forgot your password?') }}
                                            </a>
                                        @endif
                                    </div>

                                    <button class="w-100 btn btn-lg btn-primary" type="submit">Se Connecter</button><br><br>
                                    <a href="{{route('register')}}" class="w-100 btn btn-lg btn-danger" type="submit">S'Enregister</a>

                                </div>
                                {{-- <div class="card-footer">
                                    <center><p class="mt-3 mb-3 text-muted">&copy; 2024</p></center>

                                </div> --}}
                            </div>
                        </div>
                        <div class="col-4"></div>

                    </div>

            </form>
        </div>

@endsection
