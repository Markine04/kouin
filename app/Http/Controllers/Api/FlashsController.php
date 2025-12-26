<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class FlashsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $flashs = DB::table('flashers')->get();
        
        return response()->json(['flashs' => $flashs], 200);
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
        // $request->validate([
        //     'titre' => 'required',
        //     'description' => 'required',
        // ]);

       $AnnonceFlash = DB::table('flashers')->insert([
            'titre' => strtoupper($request->titre),
            'description' => $request->description,
            'contact' => $request->contact,
            'salaire' => $request->salaire,
            'ville' => $request->ville,
            'lieu_precis' => $request->lieu_precis,
            'user_enreg' => $request->user()->id,
            'created_at' => now(),
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Annonce flash ajoutée avec succès',
            'annonceflash' => $AnnonceFlash,
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $flashers = DB::table('flashers')->where('id', $id)->first();
        return response()->json(['flashers' => $flashers], 200);
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
