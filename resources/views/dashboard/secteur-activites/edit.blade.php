<form class="settings-form" action="{{ route('secteurActivites.update') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{$secteurActivites->id}}">

    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" name="titre" class="form-control" id="titre" value="{{$secteurActivites->titre}}">
    </div>

    {{-- <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
        <label class="form-check-label" for="is_active">Activer</label>
    </div> --}}

    <div class= "text-center"> 
    <button type="submit" class="btn app-btn-primary">Mettre à jour</button>
    </div>

</form>