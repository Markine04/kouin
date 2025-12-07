<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TypeOffresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 1️⃣ Récupérer les types d'offres qui ont au moins une offre
        $typeOffres = DB::table('type_offres')
            ->join('offres', 'type_offres.id', '=', 'offres.type_offre_id')
            ->select('type_offres.id', 'type_offres.name')
            ->distinct()
            ->get();

        // 2️⃣ Récupérer toutes les offres avec leur type
        $offres = DB::table('offres')
            ->join('type_offres', 'offres.type_offre_id', '=', 'type_offres.id')
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
            'typeOffres' => $typeOffres,
            'offres' => $offres
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
