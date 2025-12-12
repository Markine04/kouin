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

class SecteursActivitesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $search = $request->input('search');

        $secteurActivites = DB::table('secteurs_activite')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('nom', 'LIKE', "%$search%");
                });
            })
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends(['search' => $search]); // garde la recherche dans la pagination

        return view('dashboard.secteur-activites.index',compact('secteurActivites'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.secteur-activites.create');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        // if ($request->is_active=='on') {
        //     $is_active=1;
        // }else{
        //     $is_active=0;
        // }
        // dd($is_active);

        DB::table('secteurs_activite')->insert([
            'nom'=>$request->name,
            'is_active'=>1,
            'user_enreg'=>Auth::user()->id,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
        ]);
        // Redirection ou message de succès
        return redirect()->route('secteurActivites.index')->with('success', 'Secteur d\'activités crée avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $secteurActivites = DB::table('secteurs_activite')->where('id', $id)->first();

        return view('dashboard.secteur-activites.edit',compact('secteurActivites'));
    
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        // dd($request->all());
        // if ($request->is_active == 1) {
        //     $is_active = 1;
        // } else {
        //     $is_active = 0;
        // }
        // dd($is_active);

        DB::table('secteurs_activite')->where('id',$request->id)->update([
            'nom' => $request->name,
            'is_active' => 1,
            'user_enreg' => Auth::user()->id,
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        // Redirection ou message de succès
        return redirect()->route('secteurActivites.index')->with('success', 'Secteur d\'activités mis a jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */


    public function delete(string $id)
    {
        $secteurActivites = DB::table('secteurs_activite')->where('id', $id)->first();

        return view('dashboard.secteur-activites.delete', compact('secteurActivites'));
    }


    public function destroy(Request $request)
    {
        DB::table('secteurs_activite')->where('id', $request->id)->delete();
        return redirect()->route('secteurActivites.index')->with('success', 'Secteur d\'activités supprimé avec succès.');

    }
}
