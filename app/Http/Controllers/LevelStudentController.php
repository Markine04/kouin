<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LevelStudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $niveauEtudes = DB::table('level_students')->latest()->paginate(10);
        return view('dashboard.niveau-etudes.index', compact('niveauEtudes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.niveau-etudes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->is_active=='on') {
            $is_active=1;
        }else{
            $is_active=0;
        }

        DB::table('level_students')->insert([
        'libelle' => $request->name,
        'is_active'=>$is_active,
        'user_enreg' => Auth::user()->id,
        'created_at' => Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'Niveau études ajouté avec succès');
    
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
