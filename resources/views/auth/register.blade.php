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
        <div class="row">
            <div class="col-3"></div>
            <div class="col-6">
                <div class="card">
                    <div class="card-header">
                        <center><img class="mb-4" src="{{asset('assets/img/logo-bg.png')}}" alt="" width="90" height="73"></center>
                       </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="row">
                                <div class="col">
                                    <label for="name">Nom</label>
                                    <input id="name" class="block mt-1 w-full form-control" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                                    <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                                </div>
                                <div class="col">
                                    <label for="prenom">Prenoms</label>
                                    <input id="prenom" class="block mt-1 w-full form-control" type="text"  name="prenom" :value="old('prenom')" required autofocus autocomplete="prenom" />
                                    <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                                </div>
                            </div><br>
                            <div class="row">
                                <div class="col">
                                    <label for="email">email</label>
                                    <input id="email" class="block mt-1 w-full form-control" type="email" name="email" :value="old('email')" required autofocus autocomplete="email" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col">
                                    <label for="password">Mot de passe</label>
                                    <input id="password" class="block mt-1 w-full form-control" type="text"  name="password" :value="old('password')" required autocomplete="new-password" />
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>
                                <div class="col">
                                    <label for="password_confirmation">Confirmer Mot de passe</label>
                                    <input id="password_confirmation" class="block mt-1 w-full form-control" type="text"  name="password_confirmation" :value="old('password_confirmation')" required autocomplete="new-password"/>
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>
                            </div>
                            <div class="flex items-center justify-end mt-4">
                                <a class="text-gray" href="{{ route('login') }}">
                                    {{ __('Avez-vous déja un compte?') }}
                                </a> <br><br>
                                {{-- w-100 btn btn-lg btn-danger --}}
                            <center> <button class="ms-4 btn btn-lg btn-success text-center" type="submit">
                                    Enregistrer
                                </button></center>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-3"></div>

        </div>

    </div>
        {{-- <center> --}}
            {{-- <div class="row">
                <div class="col-2"> </div>
                <div class="col-8">
                <div class="card">
                    <div class="card-body">
                            <form method="POST" action="{{ route('register') }}">
                                @csrf

                                <!-- Name -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="name">Nom</label>
                                            <input id="name" class="block mt-1 w-full" type="text" class="form-control" name="name" :value="old('name')" required autofocus autocomplete="name" />
                                            <x-input-error :messages="$errors->get('nom')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="name">Prenoms</label>
                                            <input id="name" class="block mt-1 w-full" type="text" class="form-control" name="prenom" :value="old('prenom')" required autofocus autocomplete="name" />
                                            <x-input-error :messages="$errors->get('prenom')" class="mt-2" />
                                        </div>
                                    </div>

                                </div>



                                <!-- Email Address -->
                                <div class="mt-4">
                                    <x-input-label for="email" :value="__('Email')" />
                                    <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <!-- Password -->
                                <div class="mt-4">
                                    <x-input-label for="password" :value="__('Password')" />

                                    <x-text-input id="password" class="block mt-1 w-full"
                                                    type="password"
                                                    name="password"
                                                    required autocomplete="new-password" />

                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <!-- Confirm Password -->
                                <div class="mt-4">
                                    <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                    <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                                    type="password"
                                                    name="password_confirmation" required autocomplete="new-password" />

                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                                </div>

                                <div class="flex items-center justify-end mt-4">
                                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('login') }}">
                                        {{ __('Already registered?') }}
                                    </a>

                                    <x-primary-button class="ms-4">
                                        {{ __('Register') }}
                                    </x-primary-button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-2"></div>
            </div> --}}
        {{-- </center> --}}
@endsection
