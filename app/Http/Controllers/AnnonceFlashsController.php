<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnnonceFlashsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Flashs = DB::table('flashers')->get();
        return view('dashboard.annonce-flash.index', compact('Flashs'));

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.annonce-flash.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required',
            'description' => 'required',
        ]);

        DB::table('flashers')->insert([
            'titre' => $request->titre,
            'description' => $request->description,
            'contact' => $request->contact,
            'salaire' => $request->salaire,
            'ville' => $request->ville,
            'lieu_precis' => $request->lieu_precis,
            'user_enreg' => auth()->user()->id,
            'created_at' => Carbon::now(),
            // 'updated_at' => now(),
        ]);
        return redirect()->route('annonceFlash.index')->with('success', 'Flash created successfully');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request)
    {
        $flashs = DB::table('flashers')->where('id', $request->id)->first();
        return view('dashboard.annonce-flash.edit', compact('flashs'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::table('flashers')->where('id',$request->id)->insert([
            'titre' => $request->titre,
            'description' => $request->description,
            'contact' => $request->contact,
            'salaire' => $request->salaire,
            'ville' => $request->ville,
            'lieu_precis' => $request->lieu_precis,
            'user_id' => auth()->user()->id,
            'created_at' => now(),
            // 'updated_at' => now(),
        ]);
        return redirect()->route('annonceFlash.index')->with('success', 'Annonce modifier avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */


    public function delete(Request $request)
    {
        $flashs = DB::table('flashers')->where('id', $request->id)->first();
        return view('dashboard.annonce-flash.delete', compact('flashs'));
    }


    public function destroy(Request $request)
    {
        DB::table('flashers')->where('id', $request->id)->delete();
        return redirect()->route('annonceFlash.index')->with('success', 'Annonce supprimer avec succès');
    }
}
