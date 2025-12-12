@extends('dashboard.layout-admin.base')
@section('content')
<div class="app-content pt-3 p-md-3 p-lg-4">
    <div class="container-xl">

        <div class="row g-3 mb-4 align-items-center justify-content-between">
            <div class="col-auto">
                <h1 class="app-page-title mb-0">Validations</h1>
            </div>
            <div class="col-auto">
                 <div class="page-utilities">
                    <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                        {{-- <div class="col-auto">
                            <form class="table-search-form row gx-1 align-items-center">
                                <div class="col-auto">
                                    <input type="text" id="search-orders" name="searchorders" class="form-control search-orders" placeholder="Search">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" class="btn app-btn-secondary">Search</button>
                                </div>
                            </form>

                        </div><!--//col--> --}}
                        {{-- <div class="col-auto">

                            <select class="form-select w-auto" >
                                  <option selected value="option-1">All</option>
                                  <option value="option-2">This week</option>
                                  <option value="option-3">This month</option>
                                  <option value="option-4">Last 3 months</option>

                            </select>
                        </div> --}}
                        {{-- <div class="col-auto">
                            <a class="btn app-btn-secondary" href="#">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-download me-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"/>
                                    <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"/>
                                </svg>
                                Download CSV
                            </a>
                        </div> --}}
                        {{-- <div class="col-auto">
                            <a class="btn app-btn-secondary" href="{{route('validations.create')}}">
                                <i class="fa fa-plus" style="color: blue"></i>
                                Ajouter
                            </a>
                        </div> --}}
                    </div><!--//row-->
                </div><!--//table-utilities-->
            </div><!--//col-auto-->
        </div><!--//row-->

        <div class="tab-content" id="orders-table-tab-content">
            <div class="tab-pane fade show active" id="orders-all" role="tabpanel" aria-labelledby="orders-all-tab">
                <div class="app-card app-card-orders-table shadow-sm mb-5">
                    <div class="app-card-body">
                        <div class="table-responsive">
                            <table class="table app-table-hover mb-0 text-left">
                                <thead>
                                    <tr>
                                        <th class="cell">Id</th>
                                        <th class="cell">Libellé</th>
                                        <th class="cell">Créer par</th>
                                        <th class="cell">Date</th>
                                        <th class="cell">Status</th>
                                        <th class="cell">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($validations as $item)
                                        <tr>
                                            <td class="cell">{{$item->id}}</td>
                                            <td class="cell"><span class="truncate"> <a href="{{route('contenu.show',['id'=>$item->id])}}">{{$item->libelle}}</a> </span></td>

                                            <td class="cell">{{DB::table('users')->where('id',$item->user_id)->get()[0]->name }} -
                                                {{-- {{DB::table('entreprises')->where('user_id',$item->user_id)->get()[0]->name }} --}}
                                            </td>
                                            <td class="cell">
                                                <span>{{date('d-m',strtotime($item->created_at))}}</span>
                                                <span class="note">{{date('H:i',strtotime($item->created_at))}}</span>
                                            </td>
                                            <td class="cell">
                                                @switch($item->is_active)
                                                    @case('1')
                                                    <span class="badge bg-success">Activé</span>

                                                        @break
                                                    @case('0')
                                                    <span class="badge bg-warning">En attente de validation par l'admin</span>

                                                    @default

                                                @endswitch
                                            </td>
                                            <td class="cell">
                                                @if ($item->is_active==1)
                                                    <a class="btn-sm app-btn-secondary" href="#"><i class="fa fa-trash" style="color:red;"></i></a></td>
                                                @else
                                                    <a class="btn-sm app-btn-secondary" href="{{route('validations.valide',['id'=>$item->id])}}"><i class="fa fa-check" style="color:rgb(12, 146, 83);"></i></a>
                                                    <a class="btn-sm app-btn-secondary" href="#"><i class="fa fa-trash" style="color:red;"></i></a></td>

                                                @endif
                                        </tr>
                                    @endforeach


                                </tbody>
                            </table>
                        </div><!--//table-responsive-->

                    </div><!--//app-card-body-->
                </div><!--//app-card-->

                <nav class="app-pagination">


                        <ul class="pagination justify-content-center">
                            {{ $validations->links() }}
                        </ul>
                    </nav><!--//app-pagination-->

            </div><!--//tab-pane-->
        </div><!--//tab-content-->
    </div><!--//container-fluid-->
</div><!--//app-content-->

@endsection
