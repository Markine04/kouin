<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DashboardAppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $offres = DB::table('offres')->where('user_id', $request->user()->id)->where('is_active', 2)
            ->count();
        $attentes = DB::table('offres')->where('user_id', $request->user()->id)->where('is_active', 1)
            ->count();
        $flashs = DB::table('flashers')->where('user_enreg', $request->user()->id)->count();

        $postuleurs = DB::table('postuleurs')->count();

        $annonces = DB::table('offres')->where('user_id', $request->user()->id)
        ->orderBy('id', 'DESC')->limit(5)->get();
        $data = [
            'offres' => $offres,
            'attentes' => $attentes,
            'flashs' => $flashs,
            'postuleurs' => $postuleurs,
            'annonces' => $annonces,
        ];


        return response()->json(['success' => true, 'data' => $data], 200);
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
