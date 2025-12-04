<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class TypeOffreController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $typeOffres = DB::table('type_offres')->get();
        return view('dashboard.type-offres.index',compact('typeOffres'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.type-offres.create');

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

        DB::table('type_offres')->insert([
            'name'=>$request->name,
            'is_active'=>$is_active,
            'user_id'=>Auth::user()->id,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
        ]);
        // Redirection ou message de succès
        return redirect()->route('typeOffre.index')->with('success', 'Type d\'offre créé avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
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
