<form action="{{route('formation.destroy',['id'=>$formation])}}" method="post">
    @csrf
    <h4>Voulez-vous vraiment supprimer cette formation?</h4> 

    <center>
        <button type="reset" class="btn btn-danger">NON</button>
        <button type="submit" class="btn btn-success">OUI</button>
    </center>
</form>