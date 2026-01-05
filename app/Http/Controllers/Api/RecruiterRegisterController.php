<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Communes;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RecruiterRegisterController extends Controller
{
    public function step1(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
        ]);

        $user = DB::table('users')->insertGetId([
            'name' => $request->name,
            'prenoms' => $request->prenoms,
            'email' => $request->email,
            'role_id' => 2,
            'phone' => $request->phone,
            'created_at' => now(),

        ]);

        DB::table('entreprises')->insert([
            'user_id' => $user,
            'created_at' => now(),
        ]);

        return response()->json([
            'user_id' => $user,
            'status' => true
        ], 200);
    }

    public function step2(Request $request)
    {
       $IdEntreprise = DB::table('entreprises')->where('user_id', $request->user_id)->value('id');
        DB::table('entreprises')->where('id', $IdEntreprise)
            ->update([
                'nom_entreprise' => $request->company_name,
                'secteur_activite_id' => json_encode($request->secteur_activite_id),
                'nombre_employe' => $request->nombre_employe,
                'description_entreprise' => $request->description,
                'pays_id' => $request->paysid,
                'ville_id' => $request->villeid,
                'localisation_entreprise' => $request->lieu_precis,
            ]);

        return response()->json(['status' => true], 200);
    }

    public function step3(Request $request)
    {
        $IdEntreprise = DB::table('entreprises')->where('user_id', $request->user_id)->value('id');
        DB::table('entreprises')->where('id', $IdEntreprise)
            ->update([
                'fonction' => $request->job_title,
                'contact' => $request->phone,
                'registre_commerce' => $request->registre_commerce,
                'compte_contribuable' => $request->compte_contribuable,
                'rccm' => $request->rccm,
            ]);

        return response()->json(['status' => true]);
    }

    public function step4(Request $request)
    {
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo')->store('logos', 'public');

            $IdEntreprise = DB::table('entreprises')->where('user_id', $request->user_id)->value('id');
            DB::table('entreprises')->where('id', $IdEntreprise)
                ->update([
                    'logo_entreprise' => asset('storage/' . $logo),
                ]);
        }

        return response()->json(['status' => true]);
    }
}
