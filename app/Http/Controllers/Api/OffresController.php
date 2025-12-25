<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class OffresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $offres = DB::table('offres')
            ->join('type_offres', 'offres.type_offre_id', 'type_offres.id')
            ->join('secteurs_activite', 'offres.formation_id', 'secteurs_activite.id')
            ->join('users', 'offres.user_id', 'users.id')
            ->select(
                'offres.*',
                'type_offres.*',
                'secteurs_activite.nom as secteur_activite_nom',
                'secteurs_activite.id as secteur_activite_id',
                'users.name as user_name',
                'users.prenoms',
                'users.email',
                'users.phone',
                'users.niveau',
                'users.formation',
                'users.cv',
                'users.pays_id',
                'users.role_id'
            )
            ->get();
        return response()->json(['offres' => $offres], 200);
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
        $code = rand(1, 9) . rand(1, 9) . rand(1, 9) . rand(1, 9) . rand(1, 9);

        if (DB::table('offres')->where('code_offre', $code)->get()) {
            $code = rand(1, 9) . rand(1, 9) . rand(1, 9) . rand(1, 9) . rand(1, 9);
        } elseif (DB::table('offres')->where('code_offre', '!=', $code)->get()) {
            $code;
        }

        // dd($request->all());

        if ($request->is_active == 'on') {
            $is_active = 1;
        } else {
            $is_active = 0;
        }

        if ($request->code_annonce) {
            # code...
        }

        DB::table('offres')->insert([

            'libelle' => strtoupper($request->titre),
            'code_offre' => $code,
            'type_offre_id' => $request->typeoffre,
            'formation_id' => json_encode($request->formation),
            'entreprise_id' => $request->entreprises,
            'level_student_id' => json_encode($request->niveau),
            'annee_experience' => $request->experience,
            'lieu_poste' => $request->localisation,
            'lieu_precis_poste' => $request->lieu_precis,
            'date_publication' => $request->date_publication,
            'date_expiration' => $request->date_expiration . ' ' . '23:59:59',
            'detail_offre' => $request->description,
            'profil_poste' => $request->profil,
            'dossier_candidature' => $request->dossier_candidature,
            'salaire' => $request->salaire,
            'user_id' => Auth::user()->id,
            'is_active' => $is_active,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Offre ajoutée avec succès',
            'offres' => $offres
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $offre = DB::table('offres')->where('id', $id)->first();
        return response()->json(['offre' => $offre], 200);
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
