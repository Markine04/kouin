<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
            'cv' => 'nullable|file|mimes:pdf|max:5120', // max 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $file = $request->file('cv');
            $cvPath = $file->store('cvs', 'public'); // storage/app/public/cvs
        }
        

        // Insert the candidate data into the database
        $candidate = DB::table('candidates')->insert([
            'nom' => $request->nom,
            'prenoms' => $request->prenoms,
            'email' => $request->email,
            'niveau' => $request->niveau,
            'formation' => $request->formation,
            'cv_path' => $cvPath,
        ]);

        return response()->json(['success' => true, 'candidate' => $candidate], 201);
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
