@extends('layouts.app')
@section('content')
    <script>
        $('#button').click(function() {
            $('#images').click();
        });
    </script>
    <!-- bradcam_area  -->
    <div class="bradcam_area bradcam_bg_1">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text">
                        <h3>Profil de {{ Auth::user()->name }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ bradcam_area  -->
    <!-- ================ contact section start ================= -->
    {{-- <section class="contact-section section_padding">
    <div class="container">
      <div class="d-none d-sm-block mb-5 pb-4">
        <div id="map" style="height: 480px;"></div>
        <script>
          function initMap() {
            var uluru = {lat: -25.363, lng: 131.044};
            var grayStyles = [
              {
                featureType: "all",
                stylers: [
                  { saturation: -90 },
                  { lightness: 50 }
                ]
              },
              {elementType: 'labels.text.fill', stylers: [{color: '#ccdee9'}]}
            ];
            var map = new google.maps.Map(document.getElementById('map'), {
              center: {lat: -31.197, lng: 150.744},
              zoom: 9,
              styles: grayStyles,
              scrollwheel:  false
            });
          }

        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDpfS1oRGreGSBU5HHjMmQ3o5NLw7VdJ6I&callback=initMap"></script>

      </div>


      <div class="row">
        <div class="col-12">
          <h2 class="contact-title">Get in Touch</h2>
        </div>
        <div class="col-lg-8">
          <form class="form-contact contact_form" action="contact_process.php" method="post" id="contactForm" novalidate="novalidate">
            <div class="row">
              <div class="col-12">
                <div class="form-group">

                    <textarea class="form-control w-100" name="message" id="message" cols="30" rows="9" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" placeholder = 'Enter Message'></textarea>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" name="name" id="name" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" placeholder = 'Enter your name'>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="form-group">
                  <input class="form-control" name="email" id="email" type="email" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'" placeholder = 'Enter email address'>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group">
                  <input class="form-control" name="subject" id="subject" type="text" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Subject'" placeholder = 'Enter Subject'>
                </div>
              </div>
            </div>
            <div class="form-group mt-3">
              <button type="submit" class="button button-contactForm btn_4 boxed-btn">Send Message</button>
            </div>
          </form>
        </div>
        <div class="col-lg-4">
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-home"></i></span>
            <div class="media-body">
              <h3>Buttonwood, California.</h3>
              <p>Rosemead, CA 91770</p>
            </div>
          </div>
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-tablet"></i></span>
            <div class="media-body">
              <h3>00 (440) 9865 562</h3>
              <p>Mon to Fri 9am to 6pm</p>
            </div>
          </div>
          <div class="media contact-info">
            <span class="contact-info__icon"><i class="ti-email"></i></span>
            <div class="media-body">
              <h3>support@colorlib.com</h3>
              <p>Send us your query anytime!</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section> --}}
    <div class="featured_candidates_area candidate_page_padding">

        <div class="container">
            <div class="row">
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">Profil</h3>
                        </div>
                        <div class="card-body">
                            <div class="thumb text-center">
                                <img src="{{ asset('assets/dist/img/candiateds/1.png') }}" alt="">
                                {{-- <input type="file" name="" id="images" style="display: none;"> --}}

                                {{-- <button id="images">
                            <i class="fa fa-pencil" style="z-index: 2; position:absolute; padding-top:90px;">
                            </i>
                        </button> --}}
                            </div> <br>
                            <h3 class="text-center">{{ $users[0]->name }}</h3>
                            <center>
                                <label class="text-center">{{ $users[0]->profession }}</label>
                            </center>
                            <form action="{{route('profil.store')}}" method="post">
                              @csrf
                                Nom & prenom :
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ $users[0]->name }}"> <br>
                                Email :
                                <input type="text" class="form-control" name="email" id="email"
                                    value="{{ $users[0]->email }}"><br>

                                Profession :
                                <input type="text" class="form-control" name="profession" id="profession"
                                    value="{{ $users[0]->profession }}"> <br>

                                Situation Matrimoniale:
                                <input type="text" class="form-control" name="situation_matri" id="situation_matri"
                                    value="{{ $users[0]->situation_matri }}"><br>

                                Téléphone :
                                <input type="text" class="form-control" name="telephone" id="telephone"
                                    value="{{ $users[0]->telephone }}"><br>

                                Ville & Quartier :
                                <input type="text" class="form-control" name="lieu_habitation" id="lieu_habitation"
                                    value="{{ $users[0]->lieu_habitation }}"><br>

                                <hr>
                                <p class="text-center">
                                    <input type="submit" class="boxed-btn4" value="Modifier" id="">

                                </p>
                            </form>

                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">Compétences </h3>
                        </div>
                        <div class="card-body">
                            @if (isset($skills))
                                @foreach ($skills as $skill)
                                    {{ $skill->libelle }} <br>
                                    @if ($skill->date_debut == '' && $skill->date_fin == '')
                                    @else
                                        <i>De {{ date('m-Y', strtotime($skill->date_debut)) }} à
                                            {{ date('m-Y', strtotime($skill->date_fin)) }}</i>
                                    @endif
                                    <a data-ajax-popup="true" data-size="ms" data-title="Ajouter information"
                                        data-url="{{ route('skill.delete', ['id' => $skill->id]) }}" role="button"><i
                                            class="fa fa-trash"
                                            style="color: red; font-size:20px; margin-left:50px;"></i></a>
                                    <hr>
                                @endforeach
                            @else
                            @endif
                            <a class="boxed-btn4" data-ajax-popup="true" data-size="md" data-title="Ajouter information"
                                data-url="{{ route('add-skill', [Auth::user()->id]) }}" role="button" class="sc-button"><i
                                    class="fa fa-plus"></i> Ajouter</a>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="text-center">Expérience Professionnelle</h4>
                        </div>
                        <div class="card-body">
                            @if (isset($experiences))
                                @foreach ($experiences as $experi)
                                    {{ $experi->libelle }} <br>
                                    Tache: {{ $experi->tache }} <br>
                                    <i>De {{ date('m-Y', strtotime($experi->date_debut)) }} à
                                        {{ date('m-Y', strtotime($experi->date_fin)) }}</i>
                                    <a data-ajax-popup="true" data-size="ms" data-title="Ajouter information"
                                        data-url="{{ route('experience.delete', ['id' => $experi->id]) }}" role="button"><i
                                            class="fa fa-trash"
                                            style="color: red; font-size:20px; margin-left:50px;"></i></a>
                                    <hr>
                                @endforeach
                            @else
                            @endif
                            <a class="boxed-btn4" data-ajax-popup="true" data-size="md" data-title="Ajouter information"
                                data-url="{{ route('add-experience', [Auth::user()->id]) }}" role="button"
                                class="sc-button"><i class="fa fa-plus"></i> Ajouter</a>
                        </div>
                    </div>
                </div>

                <div class="col-3">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="text-center">Formations </h3>
                        </div>
                        <div class="card-body">
                            @if (isset($formations))
                                @foreach ($formations as $format)
                                    {{ $format->libelle }} <br>
                                    <i>De {{ date('m-Y', strtotime($format->date_debut)) }} à
                                        {{ date('m-Y', strtotime($format->date_fin)) }}</i>
                                    <a data-ajax-popup="true" data-size="ms" data-title="Ajouter information"
                                        data-url="{{ route('formation.delete', ['id' => $format->id]) }}" role="button"><i
                                            class="fa fa-trash"
                                            style="color: red; font-size:20px; margin-left:50px;"></i></a>
                                    <hr>
                                @endforeach
                            @else
                            @endif
                            <a class="boxed-btn4" data-ajax-popup="true" data-size="md" data-title="Ajouter information"
                                data-url="{{ route('add-formation', [Auth::user()->id]) }}" role="button"
                                class="sc-button"><i class="fa fa-plus"></i> Ajouter</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br><br>
        <!-- ================ contact section end ================= -->
    @endsection
