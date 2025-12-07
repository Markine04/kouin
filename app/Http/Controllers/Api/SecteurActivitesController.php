<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class SecteurActivitesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1️⃣ Récupérer les types d'offres qui ont au moins une offre
        $secteurActivites = DB::table('secteurs_activite')
            ->join('offres', 'secteurs_activite.id', '=', 'offres.formation_id')
            ->select('secteurs_activite.id', 'secteurs_activite.nom')
            ->distinct()
            ->get();

        // 2️⃣ Récupérer toutes les offres avec leur type
        $offresActivites = DB::table('secteurs_activite')
            ->join('offres', 'secteurs_activite.id', '=', 'offres.formation_id')
            ->select(
                'offres.id',
                'offres.libelle',
                'offres.code_offre',
                'offres.detail_offre',
                'offres.lieu_poste',
                'offres.date_publication',
                'offres.date_expiration',
                'offres.type_offre_id',
                'type_offres.name'
            )
            ->get();

        // 3️⃣ Retourner les données en JSON
        return response()->json([
            'secteurActivites' => $secteurActivites,
            'offresActivites' => $offresActivites
        ]);
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
        $typeoffre = DB::table('type_offres')->where('id', $id)->first();
        return response()->json(['typeoffre' => $typeoffre], 200);
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
