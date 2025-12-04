{{-- @extends('dashboard.layout-admin.base')
@section('content')


    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <h1 class="app-page-title">Creer un type d'offre</h1>
            <div class="row g-4 settings-section">
                <div class="col-4 col-md-2"></div>
                <div class="col-4 col-md-8">
                    <div class="app-card app-card-settings shadow-sm p-4">

                        <div class="app-card-body"> --}}
                            <form class="settings-form" action="{{route('typeOffre.store')}}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="name" class="form-label">Libelle</label>
                                    <input type="text" name="name" class="form-control" id="name">
                                </div>

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                                    <label class="form-check-label" for="is_active">Activer</label>
                                </div>

                               <center>
                                <button type="submit" class="btn app-btn-primary" >Save Changes</button>
                                </center> 
                            </form>
                        {{-- </div><!--//app-card-body-->

                    </div><!--//app-card-->
                </div>
                <div class="col-4 col-md-2"></div>
            </div><!--//row-->

        </div><!--//container-fluid-->
    </div><!--//app-content-->
@endsection --}}
