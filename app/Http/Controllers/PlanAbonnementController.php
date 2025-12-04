<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PlanAbonnementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $plans = DB::table('plan_abonnements')->get();
        return view('dashboard.plan-abonnements.index',compact('plans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $periodes = DB::table('periodes')->get();
        return view('dashboard.plan-abonnements.create',compact('periodes'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());

        if($request->is_active=='on'){
            $is_active=1;
        }else{
            $is_active=0;
        }
        DB::table('plan_abonnements')->insert([
            'name'=>$request->name,
            'prix'=>$request->prix,
            'periode_id'=>$request->periode_id,
            'description'=>$request->description,
            'is_active'=>$is_active,
            'user_enreg'=>Auth::user()->id,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
        ]);

        return redirect()->back()->with('success', 'Plan abonnement ajouter avec succès.');

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



    //////////////////////////////////////////////////////////////////////////////////////////////////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////////////////////////////////////////////////////////////////////

        /**
     * Display a listing of the resource.
     */
    public function index_periodes()
    {
        $periodes = DB::table('periodes')->get();
        return view('dashboard.periodes.index',compact('periodes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_periodes()
    {
        $periodes = DB::table('periodes')->get();
        return view('dashboard.periodes.create',compact('periodes'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_periodes(Request $request)
    {
        // dd($request->all());

        if ($request->is_active=='on') {
            $is_active=1;
        }else{
            $is_active=0;
        }
        // dd($is_active);

        DB::table('periodes')->insert([
            'libelle'=>$request->name,
            'is_active'=>$is_active,
            'user_enreg'=>Auth::user()->id,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
        ]);
        // Redirection ou message de succès
        return redirect()->route('periodes.index')->with('success', 'Type d\'offre créé avec succès.');

    }

    /**
     * Display the specified resource.
     */
    public function show_periodes(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_periodes(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_periodes(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_periodes(string $id)
    {
        //
    }
}
