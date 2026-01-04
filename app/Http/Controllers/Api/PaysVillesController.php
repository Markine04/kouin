<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PaysVillesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $villes = DB::table('villes')->get();
        $pays = DB::table('pays')->get();
        return response()->json([
            'villes' => $villes,
            'pays' => $pays
        ]);
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
        $typeoffre = DB::table('type_offres')->where('id', $id)->first();
        return response()->json(['typeoffre' => $typeoffre], 200);
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
