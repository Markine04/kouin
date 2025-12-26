<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
        // ==============================
        // 1️⃣ Génération code unique
        // ==============================
        do {
            $code = random_int(10000, 99999);
        } while (
            DB::table('offres')->where('code_offre', $code)->exists()
        );

        // ==============================
        // 2️⃣ Récupération des IDs
        // ==============================

        $formations = DB::table('secteurs_activite')
            ->whereIn('nom', (array) $request->formation)
            ->pluck('id')
            ->map('strval')
            ->values()
            ->toArray();

        $typeoffres = DB::table('type_offres')
            ->where('name', $request->typeoffre)
            ->pluck('id');

        $niveaux = DB::table('level_students')
            ->whereIn('libelle', (array) $request->niveau)
            ->pluck('id')
            ->map('strval')
            ->values()
            ->toArray();

        // ==============================
        // 3️⃣ Booléen is_active
        // ==============================
        $is_active = $request->boolean('is_active');

        // dd($niveaux);
        // ==============================
        // 4️⃣ Insertion
        // ==============================
        $offreId = DB::table('offres')->insertGetId([
            'libelle'              => strtoupper($request->titre),
            'code_offre'           => $code,
            'type_offre_id'        => $typeoffres,
            'formation_id'         => json_encode($formations),
            'entreprise_id'        => $request->entreprise,
            'level_student_id'     => json_encode($niveaux),
            'annee_experience'     => $request->experience,
            'lieu_poste'           => $request->localisation,
            'lieu_precis_poste'    => $request->lieu_precis,
            'date_publication'     => $request->date_publication ?? now(),
            'date_expiration'      => $request->date_expiration . ' 23:59:59',
            'detail_offre'         => $request->description,
            'profil_poste'         => $request->profil,
            'dossier_candidature'  => $request->dossier_candidature,
            'salaire'              => $request->salaire,
            'user_id'              => $request->user()->id,
            'is_active'            => 1,
            'created_at'           => now(),
        ]);

        // ==============================
        // 5️⃣ Réponse API
        // ==============================
        return response()->json([
            'success' => true,
            'message' => 'Offre ajoutée avec succès',
            'offre_id' => $offreId,
        ], 201);
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
