<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Candidat;    

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
            'email' => 'required|email|unique:candidates,email',
            'niveau' => 'nullable|string|max:100',
            'formation' => 'nullable|string|max:200',
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
            'formation' => $request->formation,
            'role_id' => $request->role,
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
