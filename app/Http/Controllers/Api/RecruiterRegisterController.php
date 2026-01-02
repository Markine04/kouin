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
            'email' => $request->email,
            'role' => 2,
        ]);

        DB::table('entreprises')->insert([
            'user_id' => $user
        ]);

        return response()->json(['user_id' => $user], 200);
    }

    public function step2(Request $request)
    {
        DB::table('entreprises')->where('user_id', $request->user_id)
            ->update([
                'nom_entreprise' => $request->company_name,
                'secteur_activite_id' => $request->industry,
                'nombre_employe' => $request->nombre_employe,
                'description_entreprise' => $request->description,
                'pays_id' => $request->paysid,
                'ville_id' => $request->villeid,
                'localisation_entreprise' => $request->lieu_precis,
            ]);

        return response()->json(['status' => true]);
    }

    public function step3(Request $request)
    {
        DB::table('entreprises')->where('user_id', $request->user_id)
            ->update([
                'job_title' => $request->job_title,
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

            DB::table('entreprises')->where('user_id', $request->user_id)
                ->update([
                'logo_entreprise' => asset('storage/' . $logo),
                ]);
        }

        return response()->json(['status' => true]);
    }
}
