
<form class="settings-form" action="{{ route('niveau-etudes.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="name" class="form-label">Libelle</label>
        <input type="text" name="name" class="form-control" id="name">
    </div>

    <div class="form-check form-switch mb-3">
        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
        <label class="form-check-label" for="is_active">Activer</label>
    </div>

    <center>
        <button type="submit" class="btn app-btn-primary">Save Changes</button>
    </center>
</form>