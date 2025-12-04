@extends('layouts.app')
@section('content')
    <!-- bradcam_area  -->
    <div class="bradcam_area bradcam_bg_1">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text">
                        <h3>Cvthèques</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div  style="z-index: 2; position:absolute; padding-left:990px;">
        <button class="boxed-btn3" data-toggle="modal" data-target="#cvthequeCenter"> <i class='fa fa-plus'></i> Ajouter un CV</button>
    </div>

  <div class="featured_candidates_area candidate_page_padding">
    <div class="container">
        <div class="row">
            @foreach ($Cvtheques as $Cvtheque)
                <div class="col-md-6 col-lg-3">
                    <div class="single_candidates text-center">
                        <div class="thumb">
                            {{-- <img src="{{asset('assets/dist/img/candiateds/1.png')}}" alt=""> --}}
                            <i class="fa fa-file-pdf-o" style="font-size: 100px; color:rgb(0, 23, 128);"></i>
                        </div>
                        <a href="#"><h4>{{$Cvtheque->libelle}}</h4></a>
                        <p>Description: {{$Cvtheque->description}}</p>
                        <p>Ajouter le: {{date('d/m/Y',strtotime($Cvtheque->created_at))}}</p>
                        <hr>
                            <ul class="blog-info-link text-center">
                                <li><a href="{{asset('assets/pdf/cvtheques/'.$Cvtheque->files)}}" target="_bank"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<i class="fa fa-eye" style="font-size: 25px; color:green;"> </i></a></li>
                                <li><a href="#"><i class="fa fa-edit" style="font-size: 25px; color:orange;"> </i></a></li>
                                <li><a href="{{route('cvtheque.delete',['id'=>$Cvtheque->id])}}"><i class="fa fa-trash" style="font-size: 25px; color:red;"> </i></a></li>
                            </ul>
                    </div>
                </div>
            @endforeach

        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="pagination_wrap">
                    <ul>
                        <li><a href="#"> <i class="ti-angle-left"></i> </a></li>
                        <li><a href="#"><span>01</span></a></li>
                        <li><a href="#"><span>02</span></a></li>
                        <li><a href="#"> <i class="ti-angle-right"></i> </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>
  <!-- ================ contact section end ================= -->
    <!-- footer start -->

    <div class="modal fade" id="cvthequeCenter" tabindex="-1" role="dialog" aria-labelledby="cvthequeCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content rounded-0">
                <div class="modal-header">
                    <h5 class="modal-title" id="cvthequeCenterTitle">Cvtheque</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><span class="icon-close2"></span></span>
                    </button>
                </div>
                <div class="modal-body">
                    <br>
                    <form action="{{route('cvtheque.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <p>Titre au CV <label for="" style="color:red;">*</label>: <input type="text" class="form-control" name="libelle" id=""></p>
                        <p>Description (facultatif):<input type="text" class="form-control"name="description" id=""></p>
                        <p>Fichier <label for="" style="color:red;">*</label>:<input type="file" class="form-control" name="fichiers" id=""></p> <br>
                        <center><p><input type="submit" class="btn btn-primary" value="Enregistrer"></p></center>
                    </form>
                    <br>
                </div>
            </div>
        </div>
    </div>
@endsection



