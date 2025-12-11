@extends('dashboard.layout-admin.base')
@section('content')
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">{{$offres->libelle}}</h1>
                </div><!--//col-auto-->
            </div><!--//row-->
            
            <div class="row g-4">
                {{-- @foreach($search_contenu as $offre) --}}
                    <div class="col-sm-12 col-md-12 col-xl-12 col-xxl-12">
                        <div class="app-card app-card-doc shadow-sm h-100">
                            <div class="app-card-thumb-holder p-3">
                                <span class="icon-holder">
                                    <i class="fas fa-file-alt text-file"></i>
                                </span>

                                    @switch($offres->is_active)
                                        @case('0')
                                            <span class="badge bg-warning" title="Accueil">EN ATTENTE </span>
                                        @break
                                        @case('1')
                                            <span class="badge bg-success">NOUVEAU </span>
                                        @break
                                        @case('2')
                                            <span class="badge bg-danger">EXPIRER </span>
                                        @break
                                        @case('3')
                                            <span class="badge bg-warning">PROLONGER </span>
                                        @break

                                        @default


                                    @endswitch
                                <a class="app-card-link-mask" href="#file-link"></a>
                            </div>
                            <div class="app-card-body p-3 has-card-actions">

                                <h4 class="app-doc-title truncate mb-0"><a href="#file-link">{{$offres->libelle}}</a></h4>
                                <div class="app-doc-meta">
                                    <ul class="list-unstyled mb-0">
                                        <li><span class="text-muted">Code:</span> {{$offres->code_offre}}</li>
                                        <li><span class="text-muted">Type Offre:</span> {{DB::table('type_offres')->where('id',$offres->type_offre_id)->get()[0]->name}}</li>
                                        <li><span class="text-muted">Formation:</span> {{($offres->formation_id== '')? '' :DB::table('secteurs_activite')->where('id',$offres->formation_id)->get()[0]->nom}}</li>
                                        <li><span class="text-muted">Année Expérience:</span> {{$offres->annee_experience}}</li>
                                        
                                        <li><span class="text-muted">Date ajout:</span> {{date('d/m/Y',strtotime($offres->date_publication))}}</li>
                                        <li><span class="text-muted">Date suppression:</span> {{date('d/m/Y',strtotime($offres->date_expiration))}}</li>

                                    </ul>
                                </div><!--//app-doc-meta-->
                                <br>
                                <h5>Description du poste</h5>
                                <p>{!!$offres->detail_offre!!}</p>
                                <h5>Profil du poste</h5>
                                <p>{!!$offres->profil_poste!!}</p>
                                <h5>Dossier de candidature</h5>
                                <p>{!!$offres->dossier_candidature!!}</p>

                            </div><!--//app-card-body-->

                        </div><!--//app-card-->
                    </div>
                {{-- @endforeach --}}

            </div><!--//row-->
            {{-- <nav class="app-pagination mt-5">
                <ul class="pagination justify-content-center">
                    <li class="page-item active"><a class="page-link" href="#">{{$search_contenu->links()}}</a></li>
                </ul>
            </nav><!--//app-pagination--> --}}
        </div><!--//container-fluid-->
    </div><!--//app-content-->
@endsection



