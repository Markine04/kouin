<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
// use App\Models\User;
// use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\Facades\Hash;

class OffresController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mettre à jour toutes les offres expirées en une seule requête
        DB::table('offres')
            ->where('is_active', 2)
            ->where('date_expiration', '<', now())
            ->update([
                'is_active' => 3,
                'updated_at' => now(),
            ]);

        // Récupération des offres
        $offres = DB::table('offres')
            ->leftJoin('type_offres', 'offres.type_offre_id', '=', 'type_offres.id')
            ->leftJoin('users', 'offres.user_id', '=', 'users.id')
            ->leftJoin('entreprises', 'offres.entreprise_id', '=', 'entreprises.id')
            ->select(
                'offres.*',
                'entreprises.nom_entreprise as entreprise_nom',
                'entreprises.logo_entreprise as entreprise_logo',
                'type_offres.name as type_offre',
                'users.name as user_name',
                'users.email',
                'users.phone',
                'users.pays_id'
            )
            ->where('offres.is_active', 2)
            ->orderByDesc('offres.id')
            ->get();

        /*
    |--------------------------------------------------------------------------
    | Récupérer toutes les formations en une seule requête
    |--------------------------------------------------------------------------
    */

        // Extraire tous les IDs formations
        $allFormationIds = [];

        foreach ($offres as $offre) {

            // Cas où formation_id = "12"
            // ou "1,2,3"
            // ou ["1","2"]

            $ids = [];

            if (!empty($offre->formation_id)) {

                // JSON ?
                $decoded = json_decode($offre->formation_id, true);

                if (is_array($decoded)) {

                    $ids = $decoded;
                } else {

                    // String simple ou séparée par virgule
                    $ids = explode(',', $offre->formation_id);
                }
            }

            $offre->formation_ids = $ids;

            $allFormationIds = array_merge($allFormationIds, $ids);
        }

        // Supprimer doublons
        $allFormationIds = array_unique($allFormationIds);

        // Charger toutes les formations en une seule requête
        $formationsMap = DB::table('secteurs_activite')
            ->whereIn('id', $allFormationIds)
            ->pluck('nom', 'id');

        /*
    |--------------------------------------------------------------------------
    | Ajouter les formations aux offres
    |--------------------------------------------------------------------------
    */

        $offres = $offres->map(function ($offre) use ($formationsMap) {

            $formations = [];

            foreach ($offre->formation_ids as $id) {

                if (isset($formationsMap[$id])) {
                    $formations[] = $formationsMap[$id];
                }
            }

            $offre->formations = $formations;

            unset($offre->formation_ids);

            return $offre;
        });

        // Catégories
        $categories = DB::table('secteurs_activite')
            ->inRandomOrder()
            ->limit(10)
            ->get();

        return response()->json([
            'success' => true,
            'offres' => $offres,
            'categories' => $categories,
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
