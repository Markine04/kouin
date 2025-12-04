<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SitesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jobs = DB::table('formations')
        ->join('offres','formations.id','offres.formation_id')
        // ->where('offres.formation_id','formations.id')
        ->inRandomOrder()->limit(8)->get();

        $offres = DB::table('offres')
        ->join('users','offres.user_id','users.id')
        ->join('pays','users.pays_id','pays.id')
        ->where('offres.is_active','!=',2)
        // ->where('offres.is_active',3)

        ->inRandomOrder()->limit(6)->orderBy('offres.id','DESC')
        ->get(['offres.id as id_offre','offres.libelle','type_offre_id','pays.name as pays_name',
        'date_expiration','lieu_poste']);
        // dd($offres);
        $date_expire='';
        foreach($offres as $offre){
            $date_expire= explode(' ',$offre->date_expiration);
            $date_expire[0];
            $date_expire[1];

            if ($date_expire[0] < date('Y-m-d') && $date_expire[1] == date('23:59:59')) {
                DB::table('offres')->where('id',$offre->id_offre)->update([
                    'is_active' =>2,
                ]);
            }else {
                // dd($offre->date_expiration);

            }
        }
        return view('sites.index',compact('jobs','offres'));
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
    public function show(Request $request)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
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
    public function destroy(Request $request)
    {
        //
    }
}
