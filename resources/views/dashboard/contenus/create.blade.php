<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>

    <!-- ✅ Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- ✅ Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}
    {{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet" /> --}}

    <!-- ✅ Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.css" rel="stylesheet">
    <script src="{{ asset('assets/admins/js/jquery-3.7.1.js') }}"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });
            $('.select2-multiple').select2({
                width: '100%',
                placeholder: 'Sélectionnez un ou plusieurs niveaux'
            });

            $('.summernote').summernote({
                height: 200,
                placeholder: 'Décrivez ici le profil du poste, les missions et les compétences requises...'
            });
        });
    </script>
    <style>
        body {
            background-color: #f5f7fa;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0px 3px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: 500;
        }
    </style>
</head>

<body>
    <div class="container my-5">
        <div class="card p-4">
            <h3 class="mb-4 text-center text-primary">🧾 Insertion d'une offre d'emploi</h3>

            <form class="settings-form" action="{{ route('contenu.store') }}" method="POST">
                @csrf
                <input type="hidden" name="is_active" value="off">
                <input type="hidden" name="date_publication" value="{{ date('Y-m-d H:i:s') }}">
                <input type="hidden" name="code_annonce" value="{{ $code }}">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="code_annonce">Code annonce</label>
                        <input type="text" class="form-control" name="code_annonce" placeholder="EX: ANN-001"
                            value="{{ $code }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nom_recruteur">Nom du recruteur</label>
                        @if (Auth::user()->role_id == 1)
                        <input type="text" name="entreprises" class="form-control" id="setting-input-1"
                            value="{{ Auth::user()->name }}" required>
                        @else
                        <input type="text" name="entreprises" class="form-control" id="setting-input-1"
                            value="{{ DB::table('entreprises')->where('id', Auth::user()->id)->get()[0]->name }}"
                            required>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="libelle">Titre du poste <label style="color:red;">*</label></label>
                        <input type="text" class="form-control" name="libelle" id="libelle"
                            placeholder="Titre du poste..." required>
                    </div>
                    @php
                    $typeOffres = DB::table('type_offres')->get();
                    $formations = DB::table('secteurs_activite')->get();
                    $level_students = DB::table('level_students')->orderBy('id', 'DESC')->get();
                    @endphp
                    <div class="col-md-6 mb-3">
                        <label for="type_offre">Type d'offre <label style="color:red;">*</label></label>

                        <select class="form-select select2" name="type_offre" id="type_offre" required>
                            <option value="">-- Selectionner le Type d'offre --</option>
                            @foreach ($typeOffres as $typeOffre)
                            <option value="{{ $typeOffre->id }}">{{ $typeOffre->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="formation">Domaine de formation du candidat <label
                                style="color:red;">*</label></label>
                        <select class="form-select select2" name="formation" id="formation" required>
                            <option value="">-- Choisir un domaine --</option>
                            @foreach ($formations as $formation)
                            <option value="{{ $formation->id }}">{{ $formation->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="level_student">Niveau d'étude <label style="color:red;">*</label></label>
                        <select class="form-select select2-multiple" name="level_student[]" id="level_student" multiple
                            required>
                            <option value="">selectionner le niveau d'etude</option>
                            @foreach ($level_students as $level_student)
                            <option value="{{ $level_student->id }}">{{ $level_student->libelle }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="annee_experience">Année d'expérience (en chiffres)</label>
                        <input type="number" class="form-control" name="annee_experience" id="annee_experience"
                            min="0" placeholder="Ex: 3">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="lieu_poste">Lieu du poste <label style="color:red;">*</label></label>
                        <input type="text" class="form-control" name="lieu_poste" id="lieu_poste"
                            placeholder="Ex: Abidjan" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="lieu_precis">Lieu précis du poste</label>
                        <input type="text" class="form-control" name="lieu_precis_poste" id="lieu_precis"
                            placeholder="Ex: Cocody, Angré">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date_publication">Date de publication</label>
                        <input type="text" class="form-control" name="date_publication" id="date_publication"
                            value="{{ date('d-m-Y') }}" disabled>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="date_expiration">Date d'expiration</label>
                        <input type="date" class="form-control" name="date_expiration" id="date_expiration">
                    </div>

                    <div class="col-12 mb-3">
                        <label for="description">Description du poste</label>
                        <textarea class="form-control summernote" name="detail_offre" id="description"></textarea>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="profil_poste">Profil du poste recherché <label style="color:red;">*</label></label>
                        <textarea class="form-control summernote" name="profil_poste" id="profil_poste" required></textarea>
                    </div>

                    <div class="col-12 mb-3">
                        <label for="dossiercandidature">Dossier de candidature <label style="color:red;">*</label></label>
                        <textarea class="form-control summernote" name="dossiercandidature" id="dossiercandidature" required></textarea>
                    </div>
                </div>

                <div class="text-center">
                    <a
                        href="{{ route('contenu.index') }}" class="btn btn-primary">Retour</a> &nbsp; &nbsp;
                    <button type="submit" class="btn btn-success px-5">Enregistrer l'offre</button>
                </div>
            </form>
        </div>
    </div>




    <!-- ✅ jQuery -->

</body>

</html>