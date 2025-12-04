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

class FormationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formations = DB::table('formations')->paginate(5);
        return view('dashboard.formations.index',compact('formations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.formations.create');

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

        DB::table('formations')->insert([
            'name'=>$request->name,
            'is_active'=>$is_active,
            'user_id'=>Auth::user()->id,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
        ]);
        // Redirection ou message de succès
        return redirect()->route('formations.index')->with('success', 'Formation créé avec succès.');

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
