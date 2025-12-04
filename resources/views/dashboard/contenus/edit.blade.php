@extends('dashboard.layout-admin.base')

@section('content')

    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <h1 class="app-page-title">Modifier cette offre</h1>
            <hr class="mb-4">
            <div class="row g-4 settings-section">
                <div class="col-12 col-md-4">
                    <h3 class="section-title">General</h3>
                    <div class="section-intro">Settings section intro goes here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. <a href="help.html">Learn more</a></div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="app-card app-card-settings shadow-sm p-4">

                        <div class="app-card-body">
                            <form class="settings-form" action="{{route('contenu.update',['id'=>$offres->id])}}" method="POST">
                                @csrf
                                <input type="hidden" name="is_active" value="off">
                                <input type="hidden" name="date_publication" value="{{$offres->date_publication}}">
                                <div class="mb-3">
                                    <label for="setting-input-1" class="form-label">Nom du recruteur<span class="ms-2" data-bs-container="body" data-bs-toggle="popover" data-bs-trigger="hover focus"  data-bs-placement="top" data-bs-content="This is a Bootstrap popover example. You can use popover to provide extra info."><svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-info-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8.93 6.588l-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533L8.93 6.588z"/>
                                        <circle cx="8" cy="4.5" r="1"/>
                                        </svg></span>
                                    </label>
                                    <input type="text" class="form-control" id="setting-input-1" value="{{DB::table('entreprises')->where('id',Auth::user()->id)->get()[0]->name}}" required>
                                </div>
                                <div class="mb-3">
                                    <label for="libelle" class="form-label">Titre du poste <label style="color:red;">*</label></label>
                                    <input type="text" class="form-control" name="libelle" id="libelle" value="{{$offres->libelle}}" required>
                                </div>
                                @php
                                    $typeOffres = DB::table('type_offres')->get();
                                    $formations = DB::table('formations')->get();
                                    // for ($i=0; $i <count($level_students) ; $i++) {
                                    //     # code...
                                    // }
                                    $level_students = DB::table('level_students')->orderBy('id','DESC')->get();
                                @endphp
                                <div class="mb-3">
                                    <label for="type_offre" class="form-label">Type d'offre <label style="color:red;">*</label></label>
                                    <select class="js-example-basic-single form-control" name="type_offre">
                                        <option value="">Selectionner le Type d'offre</option>
                                        @foreach ($typeOffres as $typeOffre)
                                            <option value="{{$typeOffre->id}}"{{($offres->type_offre_id==$typeOffre->id)? 'selected': ''}} >{{$typeOffre->name}}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="email" class="form-control" id="setting-input-3" value="hello@companywebsite.com"> --}}
                                </div>
                                <div class="mb-3">
                                    <label for="formation" class="form-label">Domaine de formation du candidat <label style="color:red;">*</label></label>
                                    <select class="js-example-basic-single form-control" name="formation" required>
                                        <option value="">Selectionner le domaine de formation</option>
                                        @foreach ($formations as $formation)
                                            <option value="{{$formation->id}}" {{($offres->formation_id==$formation->id)? 'selected': ''}}>{{$formation->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="level_student" class="form-label">Niveau d'etude <label style="color:red;">*</label></label>
                                    <select class="js-example-basic-multiple form-control" name="level_student[]" multiple="multiple">
                                        {{-- <option value="">selectionner le niveau d'etude</option> --}}
                                        @foreach ($level_students as $level_student)
                                            <option value="{{$level_student->id}}"{{($offres->level_student_id==$level_student->id)? 'selected' : ''}}>{{$level_student->name}}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="email" class="" id="setting-input-3" value="hello@companywebsite.com"> --}}
                                </div>
                                <div class="mb-3">
                                    <label for="annee_experience" class="form-label">Année d'experience (en chiffre)</label>
                                    <input type="number" class="form-control" name="annee_experience" id="annee_experience" value="{{$offres->annee_experience}}" >
                                </div>
                                <div class="mb-3">
                                    <label for="lieu_poste" class="form-label">Lieu du poste <label style="color:red;">*</label></label>
                                    <input type="text" class="form-control" id="lieu_poste" name="lieu_poste" value="{{$offres->lieu_poste}}">
                                </div>
                                <div class="mb-3">
                                    <label for="lieu_precis_poste" class="form-label">Lieu précis du poste</label>
                                    <input type="text" class="form-control" name="lieu_precis_poste" id="lieu_precis_poste" value="{{$offres->lieu_precis_poste}}">
                                </div>
                                <div class="mb-3">
                                    <label for="date_publication" class="form-label">Date de publication</label>
                                    <input type="text" class="form-control" id="date_publication" value="{{date('Y-m-d',strtotime($offres->date_publication))}}" disabled>
                                </div>
                                <div class="mb-3">
                                    <label for="date_expiration" class="form-label">Date d'expiration</label>
                                    <input type="text" class="form-control" id="date_expiration" name="date_expiration" value="{{date('Y-m-d',strtotime($offres->date_expiration))}}">
                                </div>
                                <div class="mb-3 w-100 form-group">
                                    <label for="detail_offre" class="form-label">Description de l'offre</label>
                                    <textarea name="detail_offre" class="form-control" id="detail_offre" rows="3">{{$offres->detail_offre}}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="profil_poste" class="form-label">Profil pour le poste <label style="color:red;">*</label></label>
                                    <textarea name="profil_poste" class="form-control" type="text" id="profil_poste" cols="90" rows="80">{{$offres->profil_poste}}</textarea>
                                </div>

                                <button type="submit" class="btn app-btn-primary" >Save Changes</button>
                            </form>
                        </div><!--//app-card-body-->

                    </div><!--//app-card-->
                </div>
            </div><!--//row-->
            <hr class="my-4">

        </div><!--//container-fluid-->
    </div><!--//app-content-->
@endsection
