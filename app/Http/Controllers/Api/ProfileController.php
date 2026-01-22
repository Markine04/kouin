<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $userId = $request->user()->id;

        $user = DB::table('users')
            ->where('users.id', $userId)
            ->select(
                'users.id',
                'users.name',
                'users.prenoms',
                'users.email',
                'users.phone',
                'users.niveau',
                'users.formation',
                'users.role_id',

                // ABOUT ME
                DB::raw('(SELECT id FROM about_me WHERE user_enreg = users.id LIMIT 1) AS about_id'),
                DB::raw('(SELECT about FROM about_me WHERE user_enreg = users.id LIMIT 1) AS about'),

                // COMPETENCES
                DB::raw('(SELECT id FROM competences_me WHERE user_enreg = users.id LIMIT 1) AS competences_id'),
                DB::raw('(SELECT competence FROM competences_me WHERE user_enreg = users.id LIMIT 1) AS competence'),

                // EXPERIENCES
                DB::raw('(SELECT id FROM experiences_me WHERE user_enreg = users.id LIMIT 1) AS experiences_id'),
                DB::raw('(SELECT fonction_entreprise FROM experiences_me WHERE user_enreg = users.id LIMIT 1) AS fonction_entreprise'),
                DB::raw('(SELECT role_entreprise FROM experiences_me WHERE user_enreg = users.id LIMIT 1) AS role_entreprise'),
                DB::raw('(SELECT entreprise FROM experiences_me WHERE user_enreg = users.id LIMIT 1) AS entreprise'),
                DB::raw('(SELECT year_entreprise FROM experiences_me WHERE user_enreg = users.id LIMIT 1) AS year_entreprise'),

                // EDUCATIONS
                DB::raw('(SELECT id FROM educations_me WHERE user_enreg = users.id LIMIT 1) AS educations_id'),
                DB::raw('(SELECT classe FROM educations_me WHERE user_enreg = users.id LIMIT 1) AS classe'),
                DB::raw('(SELECT universite_ecole FROM educations_me WHERE user_enreg = users.id LIMIT 1) AS universite_ecole'),
                DB::raw('(SELECT annee FROM educations_me WHERE user_enreg = users.id LIMIT 1) AS annee'),

                // DOSSIER (photo + CV)
                DB::raw('(SELECT id FROM dossiers_me WHERE user_enreg = users.id LIMIT 1) AS dossiers_id'),
                DB::raw('(SELECT photo FROM dossiers_me WHERE user_enreg = users.id LIMIT 1) AS photo'),
                DB::raw('(SELECT cv FROM dossiers_me WHERE user_enreg = users.id LIMIT 1) AS cv')
            )
            ->first();

        return response()->json([
            'status' => true,
            'user' => $user
        ], 200);
    }



    public function uploadPhoto(Request $request)
    {
        $photosIni = DB::table('info_candidates')->where('user_id', $request->user()->id)->value('id');

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/photos/'), $name);

            DB::table('info_candidates')->where('id', $photosIni)
                ->update([
                    'photo' => $name,
                ]);

            return response()->json([
                'status' => true,
                'photo' => asset('storage/photos/' . $name),
            ], 200);
        }
        return response()->json(['success' => false, 'message' => 'Aucune image reçue'], 400);
    }

    public function uploadCV(Request $request)
    {

        $request->validate([
            'cv' => 'required|mimes:pdf,doc,docx|max:5000',
        ]);

        $CVIni = DB::table('info_candidates')->where('user_id', $request->user()->id)->value('id');

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/cvs/'), $name);

            DB::table('info_candidates')->where('id', $CVIni)
                ->update([
                    'cv' => $name,
                ]);

            return response()->json([
                'status' => true,
                'cv' => asset('storage/cvs/' . $name),
            ], 200);
        }
        return response()->json(['success' => false, 'message' => 'Fichier image reçue'], 400);
    }


    public function saveAboutMe(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'about' => 'required|string'
        ]);
        // Aucun enregistrement trouvé, créer un nouveau
        DB::table('about_me')->insert([
            'user_enreg' => $request->user_id,
            'about' => $request->about,
            'created_at' => now()
        ]);

        return response()->json(['message' => 'Saved successfully'], 200);
    }

    public function saveAboutMeUpdate(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'about' => 'required|string'
        ]);
        // Aucun enregistrement trouvé, créer un nouveau
        DB::table('about_me')->where('id', $request->id)->update([
            'user_enreg' => $request->user_id,
            'about' => $request->about,
            'updated_at' => now()
        ]);

        return response()->json(['message' => 'Updated successfully'], 200);
    }

    public function saveSkill(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'skill' => 'required'
        ]);

        $infoCandidate = DB::table('info_candidates')
            ->where('user_id', $request->user_id)
            ->first();

        if ($infoCandidate) {
            if (empty($infoCandidate->competences)) {
                // Mise à jour si le champ est vide
                DB::table('info_candidates')
                    ->where('user_id', $request->user_id)
                    ->update([
                        'competences' => $request->skill,
                        'updated_at' => now()
                    ]);
            } else {
                // Nouvelle insertion si le champ contient déjà des données
                DB::table('info_candidates')->insert([
                    'user_id' => $request->user_id,
                    'competences' => $request->skill,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        } else {
            DB::table('info_candidates')->insert([
                'user_id' => $request->user_id,
                'competences' => $request->skill,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }

        return response()->json(['message' => 'Saved successfully'], 200);
    }

    public function saveExperience(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'company' => 'required',
            'job' => 'required',
            'year' => 'required',
            'role' => 'required'
        ]);


        DB::table('experiences_me')->insert([
            'user_enreg' => $request->user_id,
            'entreprise' => $request->company,
            'fonction_entreprise' => $request->job,
            'year_entreprise' => $request->year,
            'role_entreprise' => $request->role,
            'created_at' => now(),
        ]);

        return response()->json(['message' => 'Saved successfully'], 200);
    }

    public function saveEducation(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'titre' => 'required',
            'formation' => 'required',
            'annee' => 'required',
            'description' => 'nullable'
        ]);

        DB::table('educations_me')->insert([
            'user_enreg' => $request->user_id,
            'classe' => $request->titre,
            'universite_ecole' => $request->formation,
            'annee' => $request->annee,
            'created_at' => now()
        ]);

        return response()->json(['message' => 'Saved successfully'], 200);
    }



}
