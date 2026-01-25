<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;


class ContenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchdocs  = $request->searchdocs;
        $statut      = $request->statut;
        $type_search = $request->type_search;


        $users = DB::table('users')->where('id', Auth::id())->value('role_id');

        if($users == 1){
            $query = DB::table('offres');
        }else{
        $query = DB::table('offres')
            ->where('user_id', Auth::id())
            ->where('is_active', 2);
        }
        // 🔍 Recherche texte
        if (!empty($searchdocs)) {
            $query->where(function ($q) use ($searchdocs) {
                $q->where('libelle', 'LIKE', "%{$searchdocs}%")
                    ->orWhere('code_offre', 'LIKE', "%{$searchdocs}%");
            });
        }

        // 🧾 Filtre type d’offre
        if (!empty($type_search)) {
            $query->where('type_offre_id', $type_search);
        }

        // ⚙️ Filtre statut
        if ($statut !== null && $statut !== '') {
            $query->where('is_active', $statut);
        }

        $search_contenu = $query->paginate(12)->withQueryString();

        $type_offres = DB::table('type_offres')->pluck('name', 'id');
        $secteurs = DB::table('secteurs_activite')->pluck('nom', 'id');

        return view('dashboard.contenus.index', compact(
            'search_contenu',
            'searchdocs',
            'statut',
            'type_search',
            'type_offres',
            'secteurs',

        ));
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $code = rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9);
        
        if (DB::table('offres')->where('code_offre',$code)->get()) {
            $code = rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9).rand(1, 9);

        }elseif(DB::table('offres')->where('code_offre','!=',$code)->get()){
            $code;
        }
        return view('dashboard.contenus.create',compact('code'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        // if ($request->is_active=='on') {
        //     $is_active=1;
        // }else{
        //     $is_active=0;
        // }

        // if ($request->code_annonce) {
        //     # code...
        // }

        DB::table('offres')->insert([

            'libelle' =>strtoupper($request->libelle),
            'code_offre'=>$request->code_annonce,
            'type_offre_id' =>$request->type_offre,
            'formation_id' => json_encode($request->formation),
            'entreprise_id'=>$request->entreprises,
            'level_student_id' =>json_encode($request->level_student),
            'annee_experience' =>$request->annee_experience,
            'lieu_poste' =>$request->lieu_poste,
            'lieu_precis_poste' =>$request->lieu_precis_poste,
            'date_publication' =>$request->date_publication,
            'date_expiration' =>$request->date_expiration.' '.'23:59:59',
            'detail_offre' =>$request->detail_offre,
            'profil_poste' =>$request->profil_poste,
            'dossier_candidature' =>$request->dossiercandidature,
            'user_id' =>Auth::user()->id,
            'is_active' =>1,
            'created_at' =>Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->route('contenu.index')->with('success', 'Formation créé avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $offres = DB::table('offres')->where('id',$request->id)->first();
        return view('dashboard.contenus.show', compact('offres'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $offres = DB::table('offres')->find($request->id);

        return view('dashboard.contenus.edit',compact('offres'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        if ($request->is_active==1) {
            1;
        }else{
            $is_active=0;
        }

        DB::table('offres')->where('id',$request->id)->update([

            'libelle' =>strtoupper($request->libelle),
            'type_offre_id' =>$request->type_offre,
            'formation_id' => json_encode($request->formation),
            'level_student_id' =>json_encode($request->level_student),
            'annee_experience' =>$request->annee_experience,
            'lieu_poste' =>$request->lieu_poste,
            'entreprise_id' => $request->entreprises,
            'lieu_precis_poste' =>$request->lieu_precis_poste,
            'date_publication' =>$request->date_publication,
            'date_expiration' =>$request->date_expiration,
            'detail_offre' =>$request->detail_offre,
            'profil_poste' =>$request->profil_poste,
            'dossier_candidature' =>$request->dossiercandidature,
            'user_id' =>Auth::user()->id,
            'is_active' =>$is_active,
            'updated_at' =>Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->route('contenu.index')->with('success', 'Offre Mise a jour avec succès.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        //
    }



    public function prolongers(Request $request)
    {
        $offres = DB::table('offres')->find($request->id);

        // dd($offres);
        return view('dashboard.contenus.prolongers', compact('offres'));

    }

    public function prolongers_store(Request $request)
    {

        // dd($request->all());

        DB::table('offres')->where('id',$request->id)->update([
            'date_expiration' =>$request->date_expiration.' '.'23:59:59',
            'is_active' =>3,
        ]);
        return redirect()->route('contenu.index')->with('success', 'Offre Mise a jour avec succès.');

    }
}
