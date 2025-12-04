<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PostulerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // dd($request->all());

        $rules = [
            'files_cv' => 'required|mimes:doc,docx,pdf|max:2048', // Validation du fichier
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', 'fichier trop volumineux!');
        }

        $filename ='';

        if($request->hasfile('files_cv')){
            $filename = time().'.'.$request->files_cv->getClientOriginalExtension();
            $request->files_cv->move(public_path('assets/postuleurs/'),$filename);
        }

        $offres = DB::table('offres')->where('id',$request->offre_id)->get();
        // dd($offres[0]->user_id);
        DB::table('postuleurs')->insert([
            'offres_id'=>$request->offre_id,
            'nom_prenoms'=>$request->nom_prenoms,
            'email'=>$request->email,
            'objets'=>$request->objets,
            'files'=>$filename,
            'description'=>$request->description,
            'user_offre'=>$offres[0]->user_id,
            'created_at'=>Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success', 'CV ajouter avec succès.');

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
