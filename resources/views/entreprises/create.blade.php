@extends('layouts.app')
@section('content')

    <!-- bradcam_area  -->
    <div class="bradcam_area bradcam_bg_1">
        <div class="container">
            <div class="row">
                <div class="col-xl-12">
                    <div class="bradcam_text">
                        <h3 style="font-size: 35px;">Créer votre compte entreprise</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--/ bradcam_area  -->
    <section class="blog_area single-post-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-2"></div>
                <div class="col-8 card">
                    <div class="card-header">
                        <center>Nouveau Compte entreprise</center>
                        </div>
                        <div class="card-body">
                            <form action="{{route('particulier.store')}}" method="post" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="1" class="form-label">Nom d'utilisateur</label>
                                            <input type="text" name="pseudo" class="form-control" id="1" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="2" class="form-label">Mot de passe</label>
                                            <input type="password" name="password" class="form-control" id="2" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="3" class="form-label">Confirmer mot de passe</label>
                                            <input type="password" name="confirm_password" class="form-control" id="3" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="7" class="form-label">Localisation de votre entreprise</label>
                                            <input type="text" name="localisation_entreprise" class="form-control" id="7" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="passeport" class="form-label">Pièce d'identité ou passeport du recruteur</label>
                                            <input type="file" name="piece_identite" class="form-control" id="passeport" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="logo" class="form-label">Logo de votre entreprise ou ONG</label>
                                            <input type="file" name="logo_entreprise" class="form-control" id="logo" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="RCCM" class="form-label">Registre commerce (RCCM)</label>
                                            <input type="file" name="rccm" class="form-control" id="RCCM" required>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="mb-3">
                                            <label for="4" class="form-label">Nom & Prenoms</label>
                                            <input type="text" name="nom_prenoms" class="form-control" id="4" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="5" class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control" id="5" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="6" class="form-label">Fonction</label>
                                            <input type="text" name="fonction" class="form-control" id="6" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="8" class="form-label">Contact telephoniques</label>
                                            <input type="text" name="contact_telephone" class="form-control" id="8" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nom_entreises" class="form-label">Nom entreprise</label>
                                            <input type="text" name="nom_entreprise" class="form-control" id="nom_entreises" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="Registre" class="form-label">N° Registre de commerce ou recepissé "ONG"</label>
                                            <input type="text" name="registre_commerce" class="form-control" id="Registre" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="contribuable" class="form-label">Compte contribuable</label>
                                            <input type="text" name="compte_contribuable" class="form-control" id="contribuable" required>
                                        </div>
                                    </div>


                                    <div class="mt-3 mb-3">
                                        <center>
                                            <input type="submit" class="boxed-btn4" value="Enregistrer">
                                        </center>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <div class="col-2"></div>
            </div>
        </div>
    </section>
@endsection
