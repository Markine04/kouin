<form action="{{route('skill.destroy',['id'=>$skill])}}" method="post">
    @csrf
    <h4>Voulez-vous vraiment supprimer cette competence?</h4> 

    <center>
        <button type="reset" class="btn btn-danger">NON</button>
        <button type="submit" class="btn btn-success">OUI</button>
    </center>
</form>