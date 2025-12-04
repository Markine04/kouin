<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;


class JobsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $jobs
       $search = (isset($_GET['search']))?$_GET['search']:'';
       $type_offre = (isset($_GET['type_offre']))?$_GET['type_offre']:'';
       $formation_search = (isset($_GET['formation']))?$_GET['formation']:'';
        // $offres = [];
        if (isset($formation_search)||isset($type_offre)||isset($search)) {
            $offres_search = DB::table('offres')->paginate(10);
        }else{
            $offres_search='';
        }
        foreach ($offres_search as $offre) {

            if ($formation_search== $offre->formation_id) {
                $offres_search = DB::table('offres')
                ->where('formation_id',$formation_search)
                ->paginate(10);
                return view('sites.jobs-index',compact('offres_search','formation_search','type_offre','search'));

            }elseif ($type_offre== $offre->type_offre_id) {
                $offres_search = DB::table('offres')
                ->where('type_offre_id',$type_offre)
                ->paginate(10);
                return view('sites.jobs-index',compact('offres_search','formation_search','type_offre','search'));

            }elseif($search){
                $offres_search = DB::table('offres')
                ->where('libelle','LIKE',"%{$search}%")
                ->paginate(10);
                // dd($offres_search);
                return view('sites.jobs-index',compact('offres_search','formation_search','type_offre','search'));

            }else{    // dd($offres);
                return view('sites.jobs-index',compact('offres_search','formation_search','type_offre','search'));
            }
        }

    }

    public function detail(Request $request)
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }else{
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $jobs = DB::table('offres')->find($request->id);
        DB::table('consulters')->insert([
            'offres_id'=>$request->id,
            'address_user'=>$ip,
            'created_at'=>Carbon::now()
        ]);
        return view('sites.job_details',compact('jobs'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $formations = DB::table('formations')
        ->join('offres','formations.id','offres.formation_id')
        ->where('formations.id',$request->id)
        ->get(['formations.id as id_formation','formations.name','formations.user_id','offres.id as offres_id',
        'offres.libelle','offres.type_offre_id','offres.formation_id','offres.level_student_id',
        'offres.annee_experience','offres.lieu_poste','offres.lieu_precis_poste','offres.date_publication',
        'offres.date_expiration','offres.detail_offre','offres.profil_poste','type_offre_id']);
        // dd($formations);
        return view('sites.list-jobs',compact('formations'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
