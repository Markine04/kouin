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
        $offres = DB::table('offres')->where('user_id', $request->user()->id)->where('is_active','!=', 1)
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


    public function lists_offre(Request $request)
    {
        $query = DB::table('offres')
            ->join('type_offres', 'offres.type_offre_id', '=', 'type_offres.id')
            ->where('offres.user_id', $request->user()->id);

        // 🔹 Filtre par statut (1 = attente, 2 = publié)
        if ($request->filled('status')) {
            $query->where('offres.is_active',  '!=', 1);
        }

        // 🔹 Recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('offres.libelle', 'like', '%' . $request->search . '%')
                    ->orWhere('offres.description', 'like', '%' . $request->search . '%')
                    ->orWhere('offres.code_offre', 'like', '%' . $request->search . '%')
                    ->orWhere('offres.lieu_poste', 'like', '%' . $request->search . '%');
            });
        }

        $offres = $query
            ->select(
                'offres.*',
                'type_offres.name as type_offre'
            )
            ->orderByDesc('offres.created_at')
            ->paginate(10);

        // ✅ RETOUR DIRECT DE LA PAGINATION
        return response()->json($offres);
    }


    public function lists_offre_attente(Request $request)
    {
        $query = DB::table('offres')
            ->join('type_offres', 'offres.type_offre_id', '=', 'type_offres.id')
            ->where('offres.user_id', $request->user()->id)
            ->where('offres.is_active', '=', 1);

        // 🔹 Filtre par statut (1 = attente, 2 = publié)
        // if ($request->filled('is_active')) {
        //     $query->where('offres.is_active', '=', 1);
        // }

        // 🔹 Recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('offres.libelle', 'like', '%' . $request->search . '%')
                    ->orWhere('offres.description', 'like', '%' . $request->search . '%')
                    ->orWhere('offres.code_offre', 'like', '%' . $request->search . '%')
                    ->orWhere('offres.lieu_poste', 'like', '%' . $request->search . '%');
            });
        }

        $offres = $query
            ->select(
                'offres.*',
                'type_offres.name as type_offre'
            )
            ->orderByDesc('offres.created_at')
            ->paginate(10);

        // ✅ RETOUR DIRECT DE LA PAGINATION
        return response()->json($offres);
    }


    public function lists_flash(Request $request)
    {
        $query = DB::table('flashers')
            ->where('user_enreg', $request->user()->id);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('titre', 'like', '%' . $request->search . '%')
                    ->orWhere('contact', 'like', '%' . $request->search . '%')
                    ->orWhere('lieu_precis', 'like', '%' . $request->search . '%')
                    ->orWhere('ville', 'like', '%' . $request->search . '%');
            });
        }

        return response()->json(
            $query->orderByDesc('created_at')->paginate(10)
        );
    }



    public function lists_postulant(Request $request)
    {
        $query = DB::table('postuleurs')
            ->where('user_offre', $request->user()->id);

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom_prenoms', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        return response()->json(
            $query->orderByDesc('created_at')->paginate(10)
        );
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
        $offres = DB::table('offres')
            ->join('type_offres', 'offres.type_offre_id', '=', 'type_offres.id')
            ->join('users', 'offres.user_id', '=', 'users.id')
            // ->join('secteurs_activite', 'offres.formation_id', '=', 'secteurs_activite.id')
            ->leftJoin('secteurs_activite', function ($join) {
                $join->whereJsonContains(
                    'offres.formation_id',
                    DB::raw('JSON_QUOTE(CAST(secteurs_activite.id AS CHAR))')
                );
            })
            ->leftJoin('level_students', function ($join) {
                $join->whereJsonContains(
                'offres.level_student_id',
                    DB::raw('JSON_QUOTE(CAST(level_students.id AS CHAR))')
                );
            })
            ->where('offres.id', $id)
            ->select(
                'offres.*',
                'type_offres.*',
                'secteurs_activite.nom as secteur_activite_nom',
                'secteurs_activite.id as secteur_activite_id',
                'level_students.libelle as level_students_libelle',
                'level_students.id as level_students_id',
                'users.name as user_name',
                'users.prenoms',
                'users.email',
                'users.phone',
                'users.formation',
                'users.cv',
                'users.pays_id',
                'users.role_id'
            )->first();

        return response()->json(['success' => true, 'offres' => $offres], 200);
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
    public function destroy(Request $request)
    {
        DB::table('offres')->where('id', $request->id)->delete();
        return response()->json(['success' => true, 'message' => 'Offre supprimée avec succès'], 200);
    }
}
