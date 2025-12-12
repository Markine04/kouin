<form class="settings-form" action="{{ route('secteurActivites.destroy') }}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{ $secteurActivites->id }}">
    <h4>Voulez-vous vraiment supprimer ce secteur d'activité?</h4>
    <div class= "text-center">
        <button type="reset" class="btn app-btn-primary">Non</button>
        <button type="submit" class="btn app-btn-secondary">Oui</button>
    </div>

</form>
