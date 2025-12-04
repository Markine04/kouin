<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ValidationsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $validations = DB::table('offres')->get();
        return view('dashboard.validations.index', compact('validations'));
    }

    public function validation(Request $request, string $id)
    {

        $valid = DB::table('offres')->find($request->id);
        // if ($valid->count() > 0) {
        if ($valid->is_active == 1) {
            DB::table('offres')->where('id', $request->id)->update([
                'is_active' => 0
            ]);
        } else {
            DB::table('offres')->where('id', $request->id)->update([
                'is_active' => 1
            ]);
        }

        return redirect()->route('validations.index')->with('success', 'Formation créé avec succès.');
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
        //
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
