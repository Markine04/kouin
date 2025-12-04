
@extends('layouts.app')
@section('content')

   <!-- bradcam_area  -->
    <div class="bradcam_area bradcam_bg_1">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text">
                        <h3>{{$jobs->libelle}}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ bradcam_area  -->


    @php
        $offres = DB::table('offres')->join('users','offres.user_id','users.id')
        ->join('pays','users.pays_id','pays.id')
        ->where('offres.id',$jobs->id)
        ->get(['offres.id as id_offre','offres.libelle','type_offre_id','pays.name as pays_name',
        'date_expiration','lieu_poste']);
        // dd($offres);
    @endphp
    <div class="job_details_area">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                <div class="thumb">
                                    <img src="{{asset('assets/dist/img/svg_icon/1.svg')}}" alt="">
                                </div>
                                <div class="jobs_conetent">
                                    <a href="#"><h4>{{$jobs->libelle}}</h4></a>
                                    <div class="links_locat d-flex align-items-center">
                                        <div class="location">
                                            <p> <i class="fa fa-map-marker"></i> {{$offres[0]->lieu_poste}}, {{$offres[0]->pays_name}}</p>
                                        </div>
                                        <div class="location">
                                            <p> <i class="fa fa-clock-o"></i> {{DB::table('type_offres')->where('id',$jobs->type_offre_id)->get()[0]->name}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="jobs_right">
                                <div class="apply_now">
                                    <a class="heart_mark" href="#" id="like"> <i class="ti-heart"></i> </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        <div class="single_wrap">
                            <h4>Description de l'offre</h4>
                            <p>{{$jobs->detail_offre}}</p>
                        </div>
                        <div class="single_wrap">
                            <h4>Responsibilité</h4>
                            <ul>
                                <li>The applicants should have experience in the following areas.
                                </li>
                                <li>Have sound knowledge of commercial activities.</li>
                                <li>Leadership, analytical, and problem-solving abilities.</li>
                                <li>Should have vast knowledge in IAS/ IFRS, Company Act, Income Tax, VAT.</li>
                            </ul>
                        </div>
                        <div class="single_wrap">
                            <h4>Qualifications</h4>
                            <ul>
                                <li>The applicants should have experience in the following areas.
                                </li>
                                <li>Have sound knowledge of commercial activities.</li>
                                <li>Leadership, analytical, and problem-solving abilities.</li>
                                <li>Should have vast knowledge in IAS/ IFRS, Company Act, Income Tax, VAT.</li>
                            </ul>
                        </div>
                        <div class="single_wrap">
                            <h4>Benefits</h4>
                            <p>There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing.</p>
                        </div>
                    </div>

                    <div class="apply_job_form white-bg">
                        <h4>Postuler maintenant!</h4>
                        <form action="{{route('postuler.index')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="offre_id" value="{{$jobs->id}}">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input_field">
                                        <input type="text" name="nom_prenoms" placeholder="Votre nom & prenom">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="input_field">
                                        <input type="text" name="email" placeholder="Email">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input_field">
                                        <input type="text" name="objets" placeholder="Objets">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="input-field">
                                          <input type="file" class="form-control" name="files_cv" placeholder="Ajouter votre CV ici">
                                      </div>
                                </div>
                                <br><br><br>
                                <div class="col-md-12">
                                    <div class="input_field">
                                        <textarea name="description" id="" cols="30" rows="10" placeholder="Coverletter"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="submit_btn">
                                        {{-- @if (Auth::check()) --}}
                                            @if(isset(Auth::user()->id))
                                                <button class="boxed-btn3 w-100" type="submit">Postuler</button>
                                            @else
                                                <a href="{{route('login')}}" class="boxed-btn3 w-100">Postuler</a>
                                            @endif
                                        {{-- @endif --}}

                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="job_sumary">
                        <div class="summery_header">
                            <h3>Résumé du poste</h3>
                        </div>
                        <div class="job_content">
                            <ul>
                                <li>Publié le: <span>{{date('j M, Y',strtotime($jobs->date_publication))}}</span></li>
                                <li>Vacancy: <span>2 Position</span></li>
                                <li>Salary: <span>50k - 120k/y</span></li>
                                <li>Location: <span>{{$offres[0]->lieu_poste}}, {{$offres[0]->pays_name}}</span></li>
                                <li>Nature de l'offre: <span> {{DB::table('type_offres')->where('id',$jobs->type_offre_id)->get()[0]->name}}</span></li>
                            </ul>
                        </div>
                    </div>
                    <div class="share_wrap d-flex">
                        <span>Share at:</span>
                        <ul>
                            <li><a href="#"> <i class="fa fa-facebook"></i></a> </li>
                            <li><a href="#"> <i class="fa fa-google-plus"></i></a> </li>
                            <li><a href="#"> <i class="fa fa-twitter"></i></a> </li>
                            <li><a href="#"> <i class="fa fa-envelope"></i></a> </li>
                        </ul>
                    </div>
                    <div class="job_location_wrap">
                        <div class="job_lok_inner">
                            <div id="map" style="height: 200px;"></div>
                            <script>
                              function initMap() {
                                var uluru = {lat: -25.363, lng: 131.044};
                                var grayStyles = [
                                  {
                                    featureType: "all",
                                    stylers: [
                                      { saturation: -90 },
                                      { lightness: 50 }
                                    ]
                                  },
                                  {elementType: 'labels.text.fill', stylers: [{color: '#ccdee9'}]}
                                ];
                                var map = new google.maps.Map(document.getElementById('map'), {
                                  center: {lat: -31.197, lng: 150.744},
                                  zoom: 9,
                                  styles: grayStyles,
                                  scrollwheel:  false
                                });
                              }

                            </script>
                            <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpfS1oRGreGSBU5HHjMmQ3o5NLw7VdJ6I&callback=initMap"></script>

                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
