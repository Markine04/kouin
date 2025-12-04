<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class EntreprisesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('entreprises.index');
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
    public function show(string $id)
    {
        //
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


    public function particulier()
    {

        return view('entreprises.create');
    }

    public function particulier_store(Request $request)
    {
        dd($request->all());

        if ($request->is_active=='on') {
            $is_active=1;
        }else{
            $is_active=0;
        }

        DB::table('entreprises')->insert([
            "pseudo" => $request->pseudo,
            "user_id" => $request->user_id,
            "password" => $request->password,
            "confirm_password" => $request->confirm_password,
            "localisation_entreprise" => $request->localisation_entreprise,
            "nom_prenoms" => $request->nom_prenoms,
            "email" => $request->email,
            "fonction" => $request->fonction,
            "contact" => $request->contact_telephone,
            "nom_entreprise" => $request->nom_entreprise,
            "registre_commerce" => $request->registre_commerce,
            "compte_contribuable" => $request->compte_contribuable,
            "piece_identite" => $request->piece_identite,
            "logo_entreprise" => $request->logo_entreprise,
            "rccm" => $request->rccm,
            'is_active'=>$is_active,
            'user_enreg'=>Auth::user()->id,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
        ]);

    }





}
