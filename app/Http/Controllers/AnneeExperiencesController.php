<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

class AnneeExperiencesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $years_exps = DB::table('annee_experiences')->get();
        return view('dashboard.years-experiences.index',compact('years_exps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.years-experiences.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        if ($request->is_active=='on') {
            $is_active=1;
        }else{
            $is_active=0;
        }
        // dd($is_active);

        DB::table('annee_experiences')->insert([
            'libelle'=>$request->libelle,
            'is_active'=>$is_active,
            'user_enreg'=>Auth::user()->id,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
        ]);
        // Redirection ou message de succès
        return redirect()->route('yearsExp.index')->with('success', 'Experience année créé avec succès.');
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
