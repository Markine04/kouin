<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\User;    

class CandidateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entreprises = DB::table('entreprises')->where('is_active', 1)->get();
        return response()->json(['entreprises' => $entreprises], 200);
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
        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:150',
            'prenoms' => 'nullable|string|max:200',
            'email' => 'required|email|unique:users,email',
            'niveau' => 'nullable|string|max:100',
            'formation' => 'nullable|string|max:200',
            'phone' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // $user = DB::table('candidates')->insert([
            $user =  User::create([
            'name' => $request->nom,
            'prenoms' => $request->prenoms,
            'email' => $request->email,
            'niveau' => $request->niveau,
            'phone' => $request->phone,
            'formation' => $request->formation,
            'role_id' => 3,
            'created_at' => Carbon::now(),
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;
        // $tokens = $request->token;

        return response()->json([
            'success' => true,
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
            'message' => "Candidat enregistré avec succès",
        ], 201);
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        return response()->json(['entreprise' => $entreprise], 200);
    }


    public function postuler(Request $request)
    {
        $request->validate([
            "cv" => "required|file|mimes:pdf,doc,docx|max:5000",
            "job_id" => "required|integer",
        ]);

        $userId = $request->user()->id;
        $jobId = $request->job_id;

        // 🔥 Vérifier si l'utilisateur a déjà postulé à cette offre
        $check = DB::table('postuleurs')
            ->where('user_id', $userId)
            ->where('offres_id', $jobId)
            ->first();

        if ($check) {
            return response()->json([
                "status" => "error",
                "message" => "Vous avez déjà postulé à cette offre.",
            ], 409);
        }

        // 🔥 Upload CV
        $file = $request->file('cv');
        $name = time() . uniqid(6) . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('storage/cv-candidats/'), $name);

        // 🔥 Email utilisateur
        $userEmail = DB::table('users')->where('id', $userId)->value('email');

        // 🔥 Enregistrer candidature
        $candidature = DB::table('postuleurs')->insert([
            "user_id" => $userId,
            "email" => $userEmail,
            "offres_id" => $jobId,
            "objets" => $request->cover_letter,
            "files" => $name,
            "created_at" => Carbon::now(),
        ]);

        // 🔥 Récup infos recruteur
        $recruteurId = DB::table('offres')->where('id', $jobId)->value('user_id');
        $offreTitle = DB::table('offres')->where('id', $jobId)->value('libelle');

        // 🔥 Message de notification
        $messages = "Un nouveau candidat vient de postuler à votre offre : <b>$offreTitle</b>.<br>"
            . "Consultez votre tableau de bord pour plus de détails.";

        // 🔥 Enregistrer notification
        DB::table('notifications')->insert([
            'user_id' => $recruteurId,
            'title' => 'Nouvelle candidature reçue',
            'message' => $messages,
            'type' => $request->type ?? 'info',
            'data' => $request->data ? json_encode($request->data) : null,
            'is_read' => 0,
            'created_at' => now(),
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Candidature enregistrée avec succès.",
        ]);
    }
}
