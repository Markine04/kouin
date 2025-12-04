<?php

namespace App\Http\Controllers;


use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $entreprises = DB::table('users')
        ->join('entreprises','users.id','entreprises.user_id')
        ->where('users.id',Auth::user()->id)->get([
            'users.id as id_user','users.name as name_user',
            'entreprises.id as id_entreprise','entreprises.name as name_entreprises',
            'email','password','pays_id','responsable_entreprise','fonction','user_id',
            'registre_commerce','compte_contribuable','contact','logo','localisation',
            'is_active'

        ]);
        // dd($entreprises);
        return view('dashboard.settings.clients',compact('entreprises'));
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
    public function client(Request $request)
    {
        // dd($request->all());

        $rules = [
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Validation de l'image
        ];

        $validator = Validator::make($request->all(), $rules);

        if($validator->fails())
        {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', 'logo trop volumineux!');
        }

        $filename ='';
        if($request->hasfile('logo')){
            $filename = time().'.'.$request->logo->getClientOriginalExtension();
            $request->logo->move(public_path('assets/img/logo/'),$filename);
        }

        DB::table('entreprises')->where('id',$request->id_entreprise)->update([
            'name'=> $request->libelle_entreprise,
            'responsable_entreprise'=>  $request->responsable_entre,
            'fonction'=>  $request->fonctions,
            'logo'=>  ($filename!='')?$filename:'',
            'compte_contribuable'=>  $request->compte_contribuable,
            'registre_commerce'=>  $request->registre_commerce,
            'contact'=>  $request->contact,
            'localisation'=>  $request->localisation,
            'updated_at' =>Carbon::now()->format('Y-m-d H:i:s')

        ]);
        return redirect()->back()->with('success', 'Mise à jour effectuée avec succès!');
    }

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
