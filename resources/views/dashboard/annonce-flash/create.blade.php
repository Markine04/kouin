<form class="settings-form" action="{{ route('annonceFlash.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="titre" class="form-label">Titre</label>
        <input type="text" name="titre" class="form-control" id="titre" placeholder="Titre annonce">
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea name="description" id="description" class="form-control" cols="30" rows="80" placeholder="detail de l'annonce"></textarea>
    </div>

    <div class="mb-3">
        <label for="contact" class="form-label">Contact</label>
        <input type="text" name="contact" class="form-control" id="contact" placeholder="0101010120">
    </div>

    <div class="mb-3">
        <label for="salaire" class="form-label">Salaire</label>
        <input type="text" name="salaire" class="form-control" id="salaire" placeholder="10.000fr">
    </div>
    
    <div class="mb-3">
        <label for="ville" class="form-label">Ville</label>
        <input type="text" name="ville" class="form-control" id="ville" placeholder="Abidjan">
    </div>
    <div class="mb-3">
        <label for="lieu_precis" class="form-label">Lieu précis</label>
        <input type="text" name="lieu_precis" class="form-control" id="lieu_precis" placeholder="Yopougon">
    </div>

    <div class= "text-center"> 
    <button type="submit" class="btn app-btn-primary">Enregistrer</button>
    </div>
</form>
