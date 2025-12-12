<form class="settings-form" action="{{ route('annonceFlash.update') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{$flashs->id}}">
    <input type="hidden" name="created_at" value="{{$flashs->created_at}}">
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" name="titre" class="form-control" id="titre" value="{{$flashs->titre}}">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" cols="30" rows="80">{{$flashs->description}}</textarea>
    </div>

    <div class="mb-3">
        <label for="contact" class="form-label">Contact</label>
        <input type="text" name="contact" class="form-control" id="contact" value="{{$flashs->contact}}">
    </div>

    <div class="mb-3">
        <label for="salaire" class="form-label">Salaire</label>
        <input type="text" name="salaire" class="form-control" id="salaire" value="{{$flashs->salaire}}">
    </div>

    <div class="mb-3">
        <label for="ville" class="form-label">Ville</label>
        <input type="text" name="ville" class="form-control" id="ville" value="{{$flashs->ville}}">
    </div>

    <div class="mb-3">
        <label for="lieu_precis" class="form-label">Lieu précis</label>
        <input type="text" name="lieu_precis" class="form-control" id="lieu_precis" value="{{$flashs->lieu_precis}}">
    </div>

    {{-- <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
        <label class="form-check-label" for="is_active">Activer</label>
    </div> --}}

    <div class= "text-center"> 
    <button type="submit" class="btn app-btn-primary">Mettre à jour</button>
    </div>

</form>