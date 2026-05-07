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
        // Mettre à jour les offres expirées
        $offresExpirees = DB::table('offres')
            ->where('is_active', 2)
            ->where('date_expiration', '<', now()->endOfDay())
            ->get();

        foreach ($offresExpirees as $offre) {
            DB::table('offres')
                ->where('id', $offre->id)
                ->update([
                    'is_active' => 3,
                    'updated_at' => now(),
                ]);
        }

        // Récupérer les offres
        $offres = DB::table('offres')
            ->join('type_offres', 'offres.type_offre_id', '=', 'type_offres.id')
            ->join('users', 'offres.user_id', '=', 'users.id')
            // ->join('entreprises', 'offres.entreprise_id', '=', 'entreprises.id')
            ->select(
                'offres.*',
                // 'entreprises.nom_entreprise as entreprise_nom',
                // 'entreprises.logo_entreprise as entreprise_logo',
                // 'entreprises.ville as entreprise_ville',
                // 'entreprises.pays_nom as entreprise_pays_nom',
                'type_offres.name as type_offre',
                'users.name as user_name',
                'users.email',
                'users.phone',
                'users.pays_id'
            )
            // ->where('offres.is_active', 1)
            ->orderBy('offres.id', 'DESC')
            ->get();

        // 🔥 Ajouter les formations (libellés) pour chaque offre
        foreach ($offres as $offre) {
            // formation_id est un JSON encodé → on le décode
            $formationIds = json_decode($offre->formation_id, true) ?? [];

            // On récupère les libellés
            $formations = DB::table('secteurs_activite')
                ->whereIn('id', $formationIds)
                ->pluck('nom');

            // On ajoute les libellés dans l’offre
            $offre->formations = $formations;
        }

        return response()->json([
            'success' => true,
            'offres' => $offres
        ], 200);
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

        $offreId = DB::table('offres')->insertGetId([
            'libelle'              => strtoupper($request->titre),
            'code_offre'           => $code,
            'type_offre_id'        => $request->type_offre_id,
            'formation_id'         => json_encode($request->formation_id),
            'entreprise_id'        => $request->entreprise,
            'level_student_id'     => json_encode($request->level_student_id),
            'annee_experience'     => $request->experience,
            'lieu_poste'           => $request->localisation,
            'lieu_precis_poste'    => $request->lieu_precis,
            'date_publication'     => $request->date_publication ?? now(),
            'date_expiration'      => Carbon::parse($request->date_expiration)->endOfDay(),
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
