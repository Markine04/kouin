<?php

namespace App\Http\Controllers;

use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;


class CvthequesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $Cvtheques = DB::table('cvtheques')->where('user_enreg',Auth::user()->id)->get();
        return view('sites.cvtheques',compact('Cvtheques'));
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
        // dd($request->all());
        $rules = [
            'fichiers' => 'required|mimes:doc,docx,pdf|max:2048', // Validation de l'image
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', 'Fichier trop volumineux!');
        }

        $filename ='';
        if($request->hasfile('fichiers')){
            $filename = time().'.'.$request->fichiers->getClientOriginalExtension();
            $request->fichiers->move(public_path('assets/pdf/cvtheques/'),$filename);
        }
        DB::table('cvtheques')->insert([
            'libelle' => $request->libelle,
            'description' => $request->description,
            'files'=> $filename,
            'user_enreg' =>Auth::user()->id,
            'is_active' =>1,
            'created_at' =>Carbon::now()->format('Y-m-d H:i:s')
        ]);
        return redirect()->route('cvtheque.index')->with('success', 'CV ajouter avec succès.');

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

    public function delete(string $id)
    {
        $Cvtheque = DB::table('cvtheques')->where('id',$id)->get();
        File::delete(public_path('assets/pdf/cvtheques/'.$Cvtheque[0]->files));
        DB::table('cvtheques')->where('id',$id)->delete();

        return redirect()->route('cvtheque.index')->with('success', 'CV supprimer avec succès.');
    }
}
