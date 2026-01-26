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
            ->select(
                'users.id',
                'users.name',
                'users.prenoms',
                'users.email',
                'users.phone',
                'users.niveau',
                'users.formation',
                'users.role_id',
            )
            ->where('users.id', $userId)
            ->get();


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

        $CVIni = DB::table('dossiers_me')->where('user_id', $request->user()->id)->value('id');

        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/cvs/'), $name);

            DB::table('dossiers_me')->where('id', $CVIni)
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

    public function getSkill(Request $request)
    {

        $userId = $request->user()->id;

        $competences = DB::table('competences_me')
            ->where('user_enreg', $userId)
            ->get();

        return response()->json(['status' => true, 'competences' => $competences], 200);
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


    public function getExperiences(Request $request){

        $userId = $request->user()->id;

        $experiences = DB::table('experiences_me')
            ->where('user_enreg', $userId)
            ->get();

        return response()->json(['status' => true, 'experiences' => $experiences], 200);
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

    public function getEducation(Request $request){ 

        $userId = $request->user()->id;

        $educations = DB::table('educations_me')
            ->where('user_enreg', $userId)
            ->get();

        return response()->json(['status' => true, 'educations' => $educations], 200);
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
