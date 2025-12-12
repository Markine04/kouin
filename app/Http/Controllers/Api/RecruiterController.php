<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class RecruiterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entreprises = DB::table('entreprises')->where('is_active', 1)->get();
        return response()->json(['entreprises' => $entreprises], 200);
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
        $validator = Validator::make($request->all(), [
            'company' => 'required|string|max:200',
            'contact' => 'nullable|string|max:100',
            'email' => 'required|email|unique:recruiters,email',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:5120', // 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $logoPath = time() . '.' . $file->getClientOriginalExtension();
            // $cvPath = $file->store('cvs', 'public'); // storage/app/public/cvs
            $file->move(public_path('logos', 'storage/ordonnances-clients/'), $logoPath);
        }


        $recruiter = DB::table('recruiters')->insert([
            'company' => $request->company,
            'contact' => $request->contact,
            'email' => $request->email,
            'logo_path' => $logoPath,
            'created_at' => now(),
        ]);

        return response()->json(['success' => true, 'recruiter' => $recruiter], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $entreprise = DB::table('entreprises')->where('id', $id)->first();
        return response()->json(['entreprise' => $entreprise], 200);
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
