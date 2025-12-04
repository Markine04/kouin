<form action="{{route('experience.destroy',['id'=>$experience])}}" method="post">
    @csrf
    <h4>Voulez-vous vraiment supprimer cette experience?</h4> 

    <center>
        <button type="reset" class="btn btn-danger">NON</button>
        <button type="submit" class="btn btn-success">OUI</button>
    </center>
</form>