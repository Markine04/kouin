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

        // Récupérer les données de l'utilisateur

        $Abouts = DB::table('about_me')
            ->select('about')
            ->where('user_enreg', $userId)
            ->get();
            
        $Competences = DB::table('competences_me')
            ->select('competence')
            ->where('user_enreg', $userId)
            ->get();

        $Experiences = DB::table('experiences_me')
            ->select('entreprise', 'fonction_entreprise', 'year_entreprise', 'role_entreprise')
            ->where('user_enreg', $userId)
            ->get();

        $Educations = DB::table('educations_me')
            ->select('classe', 'universite_ecole', 'annee')
            ->where('user_enreg', $userId)
            ->get();

        $Dossiers = DB::table('dossiers_me')
            ->select('photo', 'cv')
            ->where('user_enreg', $userId)
            ->get();

        $user = DB::table('users')
            // ->leftJoin('about_me', 'users.id', '=', 'about_me.user_enreg')
            // ->leftJoin('competences_me', 'users.id', '=', 'competences_me.user_enreg')
            // ->leftJoin('experiences_me', 'users.id', '=', 'experiences_me.user_enreg')
            // ->leftJoin('educations_me', 'users.id', '=', 'educations_me.user_enreg')
            // ->leftJoin('dossiers_me', 'users.id', '=', 'dossiers_me.user_enreg')
            ->select(
                'users.id',
                'users.name',
                'users.prenoms',
                'users.email',
                'users.phone',
                'users.niveau',
                'users.formation',
                'users.role_id',

                // // About
                // 'about_me.id as about_id',
                // 'about_me.about',

                // // Compétences
                // 'competences_me.id as competences_id',
                // 'competences_me.competence',

                // // Expériences
                // 'experiences_me.id as experiences_id',
                // 'experiences_me.fonction_entreprise',
                // 'experiences_me.role_entreprise',
                // 'experiences_me.entreprise',
                // 'experiences_me.year_entreprise',

                // // Éducation
                // 'educations_me.id as educations_id',
                // 'educations_me.classe',
                // 'educations_me.universite_ecole',
                // 'educations_me.annee',

                // // CV & photo
                // 'dossiers_me.id as dossiers_id',
                // 'dossiers_me.photo',
                // 'dossiers_me.cv'
            )
            ->where('users.id', $userId)
            ->get(); // Un seul résultat


        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Utilisateur non trouvé',
            ], 404);
        }

        return response()->json([
            'status' => true,
            'user' => $user,
            'about' => $Abouts,
            'competences' => $Competences,
            'experiences' => $Experiences,
            'educations' => $Educations,
            'dossiers' => $Dossiers,
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
