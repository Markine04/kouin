@extends('dashboard.layout-admin.base')
@section('content')


    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <h1 class="app-page-title">Création de plan</h1>
            <div class="row g-4 settings-section">
                <div class="col-4 col-md-2"></div>
                <div class="col-4 col-md-8">
                    <div class="app-card app-card-settings shadow-sm p-4">

                        <div class="app-card-body">
                            <form class="settings-form" action="{{route('plan-abonnement.store')}}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Libelle</label>
                                    <input type="text" name="name" class="form-control" id="name">
                                </div>

                                <div class="mb-3">
                                    <label for="prix" class="form-label">Prix</label>
                                    <input type="text" name="prix" class="form-control" id="prix">
                                </div>

                                <div class="mb-3">
                                    <label for="periode_id" class="form-label">Par</label>
                                    <select class="js-example-basic-single form-control" name="periode_id" id="periode_id">
                                        <option value="">Sélectionner la periode de paiement</option>
                                        @foreach ($periodes as $period)
                                            <option value="{{$period->id}}">{{$period->libelle}}</option>
                                        @endforeach
                                    </select>
                                    {{-- <input type="text" name="prix" class="form-control" id="prix"> --}}
                                </div>

                                <div class="mb-3">
                                    <label for="summernote" class="form-label">Advantages</label>
                                    <textarea name="description" type="text" id="summernote" cols="90" rows="80"></textarea>
                                </div>


                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">Activer</label>
                                </div>

                                <button type="submit" class="btn app-btn-primary" >Save Changes</button>
                            </form>
                        </div><!--//app-card-body-->

                    </div><!--//app-card-->
                </div>
                <div class="col-4 col-md-2"></div>
            </div><!--//row-->

        </div><!--//container-fluid-->
    </div><!--//app-content-->
@endsection
