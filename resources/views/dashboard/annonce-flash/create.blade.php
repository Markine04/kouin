<form class="settings-form" action="{{ route('annonceFlash.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" name="titre" class="form-control" id="titre">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" cols="30" rows="80"></textarea>
    </div>

    <div class="mb-3">
        <label for="contact" class="form-label">Contact</label>
        <input type="text" name="contact" class="form-control" id="contact">
    </div>

    <div class="mb-3">
        <label for="salaire" class="form-label">Salaire</label>
        <input type="text" name="salaire" class="form-control" id="salaire">
    </div>

    {{-- <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
        <label class="form-check-label" for="is_active">Activer</label>
    </div> --}}

    <div class= "text-center"> 
    <button type="submit" class="btn app-btn-primary">Enregistrer</button>
    </div>
</form>
