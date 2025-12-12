@extends('dashboard.layout-admin.base')
@section('content')
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Annonce Flashs</h1>
                </div>
                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">

                            <div class="col-auto">
                                <a class="btn app-btn-secondary" data-ajax-popup="true" data-size="md"
                                    data-title="Ajouter une annonce flash" data-url="{{ route('annonceFlash.create') }}"
                                    role="button" class="sc-button"><i class="fa fa-plus"></i> <span> Ajouter</span></a>

                            </div>
                        </div><!--//row-->
                    </div><!--//table-utilities-->
                </div><!--//col-auto-->
            </div><!--//row-->

            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="row">
                    <div class="col-lg-8 col-sm-12 col-md-12">
                       <label for="search" class="form-label">Recherche : </label> 
                        <input type="text" name="search" id="search" class="form-control" placeholder="Rechercher">
                    </div>
                    <div class="col-lg-4 col-sm-12 col-md-12">
                        <input type="submit" class="btn btn-primary text-white" value="Rechercher">
                    </div>
                </div>
                <p></p>
            </div><!--//app-card-->

            <div class="tab-content" id="orders-table-tab-content">
                <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
                    <div class="app-card app-card-orders-table shadow-sm mb-5">
                        <div class="app-card-body">
                            <div class="table-responsive">
                                <table class="table app-table-hover mb-0 text-left">
                                    <thead>
                                        <tr>
                                            <th class="cell">Titres</th>
                                            <th class="cell">Contacts</th>
                                            <th class="cell">Salaires</th>
                                            <th class="cell">Descriptions</th>
                                            <th class="cell">Enreg. par</th>
                                            <th class="cell">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($Flashs as $item)
                                            <tr>
                                                <td class="cell">{{ $item->titre }}</td>
                                                <td class="cell"><span class="truncate">{{ $item->contact }}</span></td>
                                                <td class="cell"><span class="truncate">{{ $item->salaire }}</span></td>

                                                <td class="cell"><span class="truncate">{{ $item->description }}</span>
                                                </td>
                                                <td class="cell">
                                                    <span
                                                        class="note">{{ DB::table('users')->where('id', $item->user_enreg)->get()[0]->name }}</span>
                                                    <span class="note">
                                                        @if ($item->created_at == null)
                                                            {{ date('d-m-y', strtotime($item->updated_at)) }}
                                                        @else
                                                            {{ date('d-m-y', strtotime($item->created_at)) }}
                                                        @endif

                                                    </span>
                                                    <span class="note">
                                                        @if ($item->created_at == null)
                                                            {{ date('H:i', strtotime($item->updated_at)) }}
                                                        @else
                                                            {{ date('H:i', strtotime($item->created_at)) }}
                                                        @endif

                                                    </span>
                                                </td>
                                                <td class="cell">
                                                    <a data-ajax-popup="true" data-size="md"
                                                        data-title="Modifier cet annonce flash"
                                                        data-url="{{ route('annonceFlash.edit', ['id' => $item->id]) }}"
                                                        role="button"><i class="fa fa-edit"
                                                            style="color:rgb(12, 146, 83); font-size:20px;"></i>
                                                    </a>
                                                    &nbsp;&nbsp;
                                                    <a data-ajax-popup="true" data-size="md"
                                                        data-title="Supprimer cet annonce flash"
                                                        data-url="{{ route('annonceFlash.delete', ['id' => $item->id]) }}"
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
                            {{ $Flashs->links() }}
                        </ul>
                    </nav><!--//app-pagination-->

                </div><!--//tab-pane-->
            </div><!--//tab-content-->
        </div><!--//container-fluid-->
    </div><!--//app-content-->
@endsection
