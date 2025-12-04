
@extends('layouts.app')
@section('content')
<!-- bradcam_area  -->
<div class="bradcam_area bradcam_bg_1">
    <div class="container">
        <div class="row">
            <div class="col-xl-12">
                <div class="bradcam_text">
                    @if ($type_offre)
                       <h3>{{count($offres_search)}} offres trouvées</h3>
                    @elseif ($formation_search)
                       <h3>{{count($formation_search)}} offres trouvées</h3>
                    @elseif ($search!='')
                        <h3>{{count($offres_search)}} offres trouvées</h3>
                    @else
                    <h3>{{DB::table('offres')->count()}}+ Offres Disponibles</h3>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div> <br><br>
<!--/ bradcam_area  -->
<div class="container">
    @php
        $typeOffres = DB::table('type_offres')->where('is_active',1)->get();
        $formations = DB::table('formations')->where('is_active',1)->get();
    @endphp
    <form action="" method="get">
    <div class=" row">

            @csrf
            <div class="col-3">
                <input type="text" class="form-control single_field w100" placeholder="Recherche..." name="search">
            </div>
            <div class="col-3">
                <select class="js-example-basic-single single_field form-control w-100 serch_cat d-flex justify-content-end" name="type_offre">
                    <option value="">Selectionner le Type d'offre</option>
                    @foreach ($typeOffres as $typeOffre)
                        <option value="{{$typeOffre->id}}"{{($type_offre==$typeOffre->id)? 'selected':''}}>{{$typeOffre->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                <select class="js-example-basic-single single_field form-control w-100 serch_cat d-flex justify-content-end" name="formation">
                    <option value="">Selectionner le Type d'offre</option>
                    @foreach ($formations as $formation)
                        <option value="{{$formation->id}}"{{($formation_search==$formation->id)? 'selected':''}}>{{$formation->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                <input type="submit" class="btn btn-primary" value="Rechercher">
            </div>
            <br><br><br>

    </div>
</form>
</div>
<!-- job_listing_area_start  -->
<div class="job_listing_area plus_padding">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="recent_joblist_wrap">
                    <div class="recent_joblist white-bg ">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h4>Liste des offres </h4>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="serch_cat d-flex justify-content-end">
                                    <select>
                                        <option data-display="Most Recent">Most Recent</option>
                                        <option value="1">Marketer</option>
                                        <option value="2">Wordpress </option>
                                        <option value="4">Designer</option>
                                    </select>
                                </div>
                            </div> --}}
                        </div>
                    </div>
                </div>

                <div class="job_lists m-0">
                    <div class="row">
                        @foreach ($offres_search as $offre)
                            @php
                                $offres = DB::table('offres')->join('users','offres.user_id','users.id')
                                ->join('pays','users.pays_id','pays.id')
                                ->where('offres.id',$offre->id)
                                ->get(['offres.id as id_offre','offres.libelle','type_offre_id','pays.name as pays_name',
                                'date_expiration','lieu_poste']);
                                    // dd($offres);
                            @endphp
                            <div class="col-lg-12 col-md-12">
                                
                                <div class="single_jobs white-bg d-flex justify-content-between">
                                    <div class="badge bg-dark" style="margin-bottom: 60px;">
                                        {{$offre->code_offre}}
                                    </div>
                                    <div class="jobs_left d-flex align-items-center">
                                        <div class="thumb">
                                            <img src="{{asset('assets/dist/img/svg_icon/1.svg')}}" alt="">
                                        </div>
                                        <div class="jobs_conetent">
                                            <a href="job_details.html"><h4>{{$offre->libelle}}</h4></a>
                                            <div class="links_locat d-flex align-items-center">
                                                <div class="location">
                                                  <p> <i class="fa fa-map-marker"></i> {{$offres[0]->lieu_poste}}, {{$offres[0]->pays_name}}</p>
                                                </div>
                                                <div class="location">
                                                   <p><i class="fa fa-clock-o"></i> {{DB::table('type_offres')->where('id',$offre->type_offre_id)->get()[0]->name}}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="jobs_right">
                                        <div class="apply_now">
                                            <a class="heart_mark" href="#" id="like"> <i class="fa fa-heart"></i> </a>
                                            <a href="{{route('jobs-detail.index',['id'=>$offre->id])}}" class="boxed-btn3">Voir maintenant </a>
                                        </div>
                                        <div class="date">
                                            <p>Date d'expiration: {{date('j M, Y',strtotime($offre->date_expiration))}}&nbsp;&nbsp; <i class="fa fa-eye"> {{DB::table('consulters')->where('offres_id',$offre->id)->count()}}</i></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    {{-- <li><a href="#"> <i class="ti-angle-left"></i> </a></li> --}}
                                    <li class="page-item" tabindex="-1" aria-disabled="true">{{$offres_search->links()}}</li>
                                    {{-- <li><a href="#"><span>02</span></a></li>
                                    <li><a href="#"> <i class="ti-angle-right"></i> </a></li> --}}
                                </ul>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- job_listing_area_end  -->
@endsection


