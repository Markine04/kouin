@extends('dashboard.layout-admin.base')
@section('content')
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">

            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0">Plan Abonnements</h1>
                </div>
                <div class="col-auto">
                    <div class="page-utilities">
                        <div class="row g-2 justify-content-start justify-content-md-end align-items-center">
                            <div class="col-auto">
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
                            </div>
                            <div class="col-auto">
                                <a class="btn app-btn-secondary" href="{{ route('plan-abonnement.create') }}">
                                    <i class="fa fa-plus" style="color: blue"></i>
                                    Ajouter
                                </a>
                            </div>
                        </div><!--//row-->
                    </div><!--//table-utilities-->
                </div><!--//col-auto-->
            </div><!--//row-->


            <!--//tab-content-->
            <main>
                <div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
                    @foreach ($plans as $plan)
                        <div class="col-4">

                            <div class="card mb-4 rounded-3 shadow-sm">
                                <div class="card-header py-3">
                                    <h4 class="my-0 fw-normal">{{ $plan->name }}</h4>
                                </div>
                                <div class="card-body">
                                    <h1 class="card-title pricing-card-title">{{ $plan->prix }}<small
                                            class="text-muted fw-light">/Fcfa</small></h1>
                                    <ul class="list-unstyled mt-3 mb-4">
                                        <li>{{ $plan->description }}</li>
                                    </ul>
                                    <button type="button" class="w-100 btn btn-lg btn-outline-primary">S'Abonner a l'offre {{ $plan->name }}</button>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>
            </main>
        </div><!--//container-fluid-->
    </div><!--//app-content-->
@endsection
