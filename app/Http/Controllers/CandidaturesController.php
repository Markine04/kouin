<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CandidaturesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = DB::table('users')->where('role_id', 2)->get();
        $formats = (isset($_GET['formations'])) ? $_GET['formations'] : '';
        $year_exp = (isset($_GET['year_exp'])) ? $_GET['year_exp'] : '';


        $formations = DB::table('formations')->get();
        $years_experiences = DB::table('annee_experiences')->get();

        if ($formats) {
            $formations = DB::table('formations')->where('id', $formats)->get();
            return view('sites.candidatures.candidate', compact('users', 'formations', 'years_experiences', 'formats', 'year_exp'));
        } elseif ($year_exp) {
            $years_experiences = DB::table('annee_experiences')->where('id', $year_exp)->get();
            return view('sites.candidatures.candidate', compact('users', 'formations', 'years_experiences', 'formats', 'year_exp'));
        } elseif ($formats && $year_exp) {
            $formations = DB::table('formations')->where('id', $formats)->get();
            $years_experiences = DB::table('annee_experiences')->where('id', $year_exp)->get();
            return view('sites.candidatures.candidate', compact('users', 'formations', 'years_experiences', 'formats', 'year_exp'));
        } else {
            return view('sites.candidatures.candidate', compact('users', 'formations', 'years_experiences', 'formats', 'year_exp'));
        }
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
        $users = DB::table('users')->where('id', Auth::user()->id)->get('*');
        $skills = DB::table('skill_candidats')->where('user_enreg', Auth::user()->id)->get();
        $experiences = DB::table('experience_candidats')->where('user_enreg', Auth::user()->id)->get();
        $formations = DB::table('formation_candidats')->where('user_enreg', Auth::user()->id)->get();
        return view('sites.candidatures.profils', compact('skills', 'experiences', 'formations', 'users'));
    }


    public function profil_store(Request $request)
    {
        DB::table('users')->where('id', Auth::user()->id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'profession' => $request->profession,
            'situation_matri' => $request->situation_matri,
            'telephone' => $request->telephone,
            'lieu_habitation' => $request->lieu_habitation,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success', 'Profil Modifier avec succès.');
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


    public function add_skill(Request $request)
    {
        return view('sites.candidatures.add_skill');
    }

    public function store_skill(Request $request)
    {
        DB::table('skill_candidats')->insert([
            'libelle' => $request->libelle,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'user_enreg' => Auth::user()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success', 'Formation créé avec succès.');
    }

    public function skill_delete(Request $request)
    {
        $skill = $request->id;
        return view('sites.candidatures.delete_skill', compact('skill'));
    }

    public function skill_destroy(Request $request)
    {
        DB::table('skill_candidats')->where('id', $request->id)->delete();
        return redirect()->back()->with('success', 'competence supprimer avec succès.');
    }




    public function add_experience(Request $request)
    {
        return view('sites.candidatures.add_experiences');
    }

    public function store_experience(Request $request)
    {
        DB::table('experience_candidats')->insert([
            'libelle' => $request->libelle,
            'tache' => $request->tache,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'user_enreg' => Auth::user()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success', 'Formation créé avec succès.');
    }

    public function delete_experience(Request $request)
    {
        $experience = $request->id;
        return view('sites.candidatures.delete_experience', compact('experience'));
    }

    public function destroy_experience(Request $request)
    {
        DB::table('experience_candidats')->where('id', $request->id)->delete();
        return redirect()->back()->with('success', 'competence supprimer avec succès.');
    }




    public function add_formation(Request $request)
    {
        return view('sites.candidatures.add_formation');
    }

    public function store_formation(Request $request)
    {
        DB::table('formation_candidats')->insert([
            'libelle' => $request->libelle,
            'date_debut' => $request->date_debut,
            'date_fin' => $request->date_fin,
            'user_enreg' => Auth::user()->id,
            'created_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success', 'Formation créé avec succès.');
    }

    public function delete_formation(Request $request)
    {
        $formation = $request->id;
        return view('sites.candidatures.delete_formation', compact('formation'));
    }

    public function destroy_formation(Request $request)
    {
        DB::table('formation_candidats')->where('id', $request->id)->delete();
        return redirect()->back()->with('success', 'competence supprimer avec succès.');
    }
}
