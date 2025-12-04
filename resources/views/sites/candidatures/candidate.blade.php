@extends('layouts.app')
@section('content')
    <!-- bradcam_area  -->
    <div class="bradcam_area bradcam_bg_1">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text">
                        <h3>Candidats</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ bradcam_area  -->
<br>
    <div class="container">
        <div class="single_candidates text-center">

        <div class="row">
            <div class="col-2"></div>
            <div class="col-8 ">
                <form action="" method="get">
                    @csrf
                    <div class="row">

                    <div class="col-auto">
                        {{-- <input type="text" class="form-control" name="" id=""> --}}
                        <select name="formations" id="" class="js-example-basic-multiple form-control">
                            <option value="">Sélectionner la formation</option>
                            @foreach ($formations as $item)
                                <option value="{{$item->id}}" {{$formats==$item->id?'selected':''}}>{{$item->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <select name="year_exp" id="" class="js-example-basic-multiple form-control">
                            <option value="">Sélectionner année exp.</option>
                            @foreach ($years_experiences as $experience)
                                <option value="{{$experience->id}}"{{$year_exp==$experience->id?'selected':''}}>{{$experience->libelle}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-auto">
                        <input type="submit" class="boxed-btn3" value="Rechercher">
                    </div>
                </div>
                </form>

            </div>
            <div class="col-2"></div>
        </div>
        </div>
    </div>

    <!-- featured_candidates_area_start  -->
    <div class="featured_candidates_area candidate_page_padding">
        <div class="container">
            <div class="row">
                @foreach ($users as $user)
                    <div class="col-md-6 col-lg-3">
                        <div class="single_candidates text-center">
                            <div class="thumb">
                                <img src="{{asset('assets/dist/img/candiateds/1.png')}}" alt="">
                            </div>
                            <a href="#"><h4>{{$user->name}}</h4></a>
                            <p>Software Engineer</p>
                            <p>10 ans</p>
                            <p><a href="#" class="boxed-btn4">Voir son profil</a></p>
                        </div>
                    </div>
                @endforeach
                
                {{-- <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/2.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/3.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/4.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">

                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/5.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/6.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/7.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/8.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/9.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/10.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/3.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            <img src="{{asset('assets/dist/img/candiateds/4.png')}}" alt="">
                        </div>
                        <a href="#"><h4>Markary Jondon</h4></a>
                        <p>Software Engineer</p>
                    </div>
                </div> --}}
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="pagination_wrap">
                        <ul>
                            <li><a href="#"> <i class="ti-angle-left"></i> </a></li>
                            <li><a href="#"><span>01</span></a></li>
                            <li><a href="#"><span>02</span></a></li>
                            <li><a href="#"> <i class="ti-angle-right"></i> </a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- featured_candidates_area_end  -->
@endsection
