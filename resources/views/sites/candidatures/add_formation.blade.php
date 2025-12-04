<form action="{{route('store-formation')}}" method="post">
    @csrf
    <label for="libelle">Libelle</label>
    <input type="text" name="libelle" class="form-control" id=""> <br>

    <label for="date_debut">Date debut</label>
    <input type="date" name="date_debut" class="form-control" id="date_debut"> <br>

    <label for="date_fin">Date fin </label>
    <input type="date" name="date_fin" class="form-control" id="date_fin"> <br>

    <center>
        <input type="submit" class="boxed-btn4" value="Enregistrer">
    </center>
</form>