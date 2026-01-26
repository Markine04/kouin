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
        ]);

        // 🔥 Upload CV
        $cvPath = $request->file("cv")->store("cv-candidats", "public");

        $user = DB::table('users')->where('id', $request->user()->id)->value('email');

        // 🔥 Enregistrement DB
        $candidature = DB::table('postuleurs')->insert([
            "user_id" => $request->user()->id,
            "email" => $user,
            "offres_id" => $request->IDjob,
            "objets" => $request->cover_letter,
            "files" => $cvPath,
            "created_at" => Carbon::now(),
        ]);

        $recruteurId = DB::table('offres')->where('id', $request->IDjob)->value('user_id');
        $offrestitle = DB::table('offres')->where('id', $request->IDjob)->value('libelle');

        // $user_name = DB::table('users')->where('id', $offres)->value('name');

        $messages = "Un nouveau candidat vient de postuler à votre offre : " . $offrestitle . " . <br> Veuillez vérifier votre tableau de bord pour plus de détails.";
        
        DB::table('notifications')->insert([
            'user_id' => $recruteurId,
            'title' => 'Nouvelle candidature reçue',
            'message' => $messages,
            'type' => $request->type ?? 'info',
            'data' => $request->data ? json_encode($request->data) : null,
            'is_read' => 0,
            'created_at' => now(),
        ]);

        // 🔥 Email au recruteur (optionnel)
        // Mail::to($recruteurEmail)->send(new CandidatureMail($candidature));

        return response()->json([
            "message" => "Candidature enregistrée avec succès",
            "candidature" => $candidature
        ]);
    }
}
