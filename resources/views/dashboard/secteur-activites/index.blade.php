 @extends('dashboard.layout-admin.base')
@section('content')
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Secteurs d'Activités</h1>
                </div>
                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">

                            {{-- <div class="col-auto">
                                <a class="btn app-btn-secondary" href="#">
                                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-download me-1"
                                        fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd"
                                            d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                        <path fill-rule="evenodd"
                                            d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                    </svg>
                                    Download CSV
                                </a>
                            </div> --}}
                            <div class="col-auto">
                                <a class="btn app-btn-secondary" data-ajax-popup="true" data-size="md"
                                    data-title="Ajouter un Secteur d'Activité" data-url="{{ route('secteurActivites.create') }}"
                                    role="button" class="sc-button"><i class="fa fa-plus"></i> <span> Ajouter</span></a>

                            </div>
                        </div><!--//row-->
                    </div><!--//table-utilities-->
                </div><!--//col-auto-->
            </div><!--//row-->
            <form action="{{ route('secteurActivites.index') }}" method="GET">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <label for="search" class="col-form-label">Recherche :</label>
                    </div>

                    <div class="col-lg-7 col-sm-12 col-md-12">
                        <input type="text" name="search" id="search" class="form-control"
                            value="{{ request('search') }}" placeholder="Rechercher...">
                    </div>

                    <div class="col-lg-3 col-sm-12 col-md-12">
                        <input type="submit" class="btn btn-primary text-white" value="Rechercher">
                    </div>
                </div>
            </form>



            <p></p>

            <div class="tab-content" id="orders-table-tab-content">
                <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
                    <div class="app-card app-card-orders-table shadow-sm mb-5">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left">
                                    <thead>
                                        <tr>
                                            <th class="cell">Titres</th>
                                            <th class="cell">Créer le</th>
                                            <th class="cell">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($secteurActivites as $item)
                                            <tr>
                                                <td class="cell">{{ $item->nom }}</td>
                                                
                                                <td class="cell"><span class="note">
                                                            {{ date('d-m-y', strtotime($item->created_at)) }}</span>
                                                </td>
                                                <td class="cell">
                                                    <a data-ajax-popup="true" data-size="md"
                                                        data-title="Modifier ce secteur d\'activite"
                                                        data-url="{{ route('secteurActivites.edit', ['id' => $item->id]) }}"
                                                        role="button"><i class="fa fa-edit"
                                                            style="color:rgb(12, 146, 83); font-size:20px;"></i>
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a data-ajax-popup="true" data-size="md"
                                                        data-title="Supprimer ce secteur d\'activite"
                                                        data-url="{{ route('secteurActivites.delete', ['id' => $item->id]) }}"
                                                        role="button"><i class="fa fa-trash"
                                                            style="color:red; font-size:20px;"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach


                                    </tbody>
                                </table>
                            </div><!--//table-responsive-->

                        </div><!--//app-card-body-->
                    </div><!--//app-card-->
                    <nav class="app-pagination">


                        <ul class="pagination justify-content-center">
                            {{ $secteurActivites->links() }}
                        </ul>
                    </nav><!--//app-pagination-->

                </div><!--//tab-pane-->
            </div>

        </div><!--//container-fluid-->
    </div><!--//app-content-->
@endsection










