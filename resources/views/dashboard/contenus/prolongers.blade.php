<form action="{{route('prolongation.store',['id'=>$offres->id])}}" method="post">
    @csrf
    {{-- <input type="hidden" name=""> --}}
    {{-- {{$offres->id}} --}}
    
    {{-- @php
        $dates = DB::table('offres')->where('id',$offres->id)->get();
    @endphp
    @foreach ($dates as $date) --}}
    <input type="date" class="form-control" name="date_expiration">

    {{-- @endforeach --}}


    <center><button type="submit" class="btn app-btn-primary" >Save Changes</button></center>  

</form>