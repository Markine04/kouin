    <!-- header-start -->

    <link rel="stylesheet" href="{{asset('assets/icomoon/fonts/style.css')}}">
    <link rel="stylesheet" href="{{asset('assets/icomoon/style.css')}}">
    <header>
        <div class="header-area ">
            <div id="sticky-header" class="main-header-area">
                <div class="container-fluid ">
                    <div class="header_bottom_border">
                        <div class="row align-items-center">
                            <div class="col-xl-3 col-lg-2">
                                <div class="logo">
                                    <a href="{{route('index')}}">
                                        <img src="{{ asset('assets/dist/img/logo.png') }}" alt="">
                                    </a>
                                </div>
                            </div>
                            <div class="col-xl-5 col-lg-6">
                                <div class="main-menu  d-none d-lg-block">
                                    <nav>
                                        <ul id="navigation">
                                            <li><a href="{{route('index')}}">home</a></li>
                                            <li><a href="{{route('jobs.index')}}">Toutes les offres</a></li>

                                            @if (Auth::check())
                                                @php
                                                    // $users = '';
                                                    $user = DB::table('users')->where('id',Auth::user()->id)->get();
                                                    // dd($user);
                                                @endphp
                                                @if ($user[0]->role_id==1)
                                            <li><a href="#">Candidats <i class="ti-angle-down"></i></a>
                                                <ul class="submenu">
                                                    <li><a href="{{route('candidatures.index')}}">Tous les candidats </a></li>
                                                    <li><a href="{{route('candidatures.create')}}">Créer un compte candidat </a></li>
                                                    <li><a href="elements.html">elements</a></li>
                                                </ul>
                                            </li>
                                            @endif
                                            @endif
                                            <li><a href="#">blog <i class="ti-angle-down"></i></a>
                                                <ul class="submenu">
                                                    <li><a href="blog.html">blog</a></li>
                                                    <li><a href="single-blog.html">single-blog</a></li>
                                                </ul>
                                            </li>
                                            <li><a href="contact.html">Contact</a></li>
                                            @if (Auth::check())
                                                @php
                                                    // $users = '';
                                                    $user = DB::table('users')->where('id',Auth::user()->id)->get();
                                                    // dd($user);
                                                @endphp
                                                @if ($user[0]->role_id==2)
                                                    <li><a href="#">Profil <i class="ti-angle-down"></i></a>
                                                        <ul class="submenu">
                                                            <li><a href="{{route('cvtheque.index')}}">CVthèque </a></li>
                                                            <li><a href="{{route('candidatures.show',['id'=>$user[0]->id])}}">Voir profil </a></li>
                                                            <li><a href="elements.html">elements</a></li>
                                                        </ul>
                                                    </li>
                                                @endif
                                            @endif
                                        </ul>
                                    </nav>
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 d-none d-lg-block">
                                <div class="Appointment">
                                    <div class="phone_num d-none d-xl-block">
                                        @if (Auth::user())
                                            @if ($user[0]->role_id==1)

                                            <a class="" href="{{ route('dashboard') }}">Tableau de bord</a>
                                            @endif
                                        @else
                                            <a class="" href="{{ route('login') }}">Connexion</a>
                                        @endif

                                    </div>
                                    <div class="d-none d-lg-block">
                                        @if (Auth::user())
                                            <a class="boxed-btn3" href="{{route('contenu.create')}}">Poster une offre</a>
                                        @else
                                            <button type="submit" class="boxed-btn3" data-toggle="modal" data-target="#exampleModalCenter">Poster une offre</button>
                                        @endif

                                    </div>
                                    <div class="d-none d-lg-block">
                                        @if (Auth::user())
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf

                                            <x-responsive-nav-link :href="route('logout')"
                                                    onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                                 <i class="fa fa-sign-out" style="font-size: 30px; color:beige;" data-toggle="tooltip" data-placement="bottom" title="Sé déconnecter"></i>
                                            </x-responsive-nav-link>
                                        </form>
                                       @endif

                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mobile_menu d-block d-lg-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- header-end -->

    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalCenterTitle">Enregistrement</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true"><span class="icon-close2"></span></span>
                </button>
            </div>
            <div class="modal-body">
                <br>
                @php
                    $title = ''
                @endphp
                <div class="row">
                    <div class="col-6">
                        <div class="card">
                            <a href="{{route('particulier',['title'=>$title='particulier'])}}" class="boxed-btn4">
                                <div class="card-body">
                                    Créer un compte particulier?
                                </div>
                            </a>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <a href="{{route('particulier',['title'=>$title='entreprise'])}}" class="boxed-btn4">
                                <div class="card-body">
                                    Créer un compte entreprise?
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                <br>
            </div>
            </div>
        </div>
    </div>
