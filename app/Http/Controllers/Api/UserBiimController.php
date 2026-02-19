<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;



class UserBiimController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function registerToWordpress(Request $request)
    {

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json'
        ])->post('https://biim.ci/wp-json/mobile-app/v1/register', [
            "email" => $request->email,
            "password" => $request->password,
            "first_name" => $request->first_name,
            "last_name" => $request->last_name,
            "phone" => $request->phone,
            "display_name" => $request->display_name
        ]);

        if ($response->successful()) {

            $data = $response->json();

            return response()->json([
                'status' => true,
                'message' => 'Utilisateur enregistré sur WordPress',
                'data' => $data
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Erreur WordPress',
            'error' => $response->body()
        ], $response->status());
    }


    public function registerAndLogin(Request $request)
    {     
        $registerResponse = Http::timeout(10)
            ->post(
            'https://biim.ci/wp-json/mobile-app/v1/register',
            [
                "email"        => $request->email,
                "password"     => $request->password,
                "first_name"   => $request->first_name,
                "last_name"    => $request->last_name,
                "phone"        => $request->phone,
                "display_name" => $request->display_name
            ]);

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ LOGIN JWT
        |--------------------------------------------------------------------------
        */
        $loginResponse = Http::timeout(10)
            ->post('https://biim.ci/wp-json/jwt-auth/v1/token', [
                "username" => $request->email, // email fonctionne
                "password" => $request->password
            ]);
        $loginData = $loginResponse->json();

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ SUCCESS → Retourner token + user info
        |--------------------------------------------------------------------------
        */
        return response()->json([
            'status'  => true,
            'message' => 'Inscription et connexion réussies',
            'user'    => [
                'email' => $request->email,
                'name'  => $request->display_name,
                'phone' => $request->phone,
                "first_name" => $request->first_name,
                "last_name" => $request->last_name,
            ],

            'id'   => $loginData['user_id'] ?? null,
            'token'   => $loginData['token'],
            'expires' => $loginData['exp'] ?? null
        ]);
    }







    public function loginToWordpress(Request $request)
    {
        // $essaie = 125;
        // dd($essaie);
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $response = Http::timeout(10)
            ->withHeaders([
                'Accept' => 'application/json'
            ])
            ->post('https://biim.ci/wp-json/jwt-auth/v1/token', [
                "username" => $request->username,
                "password" => $request->password
            ]);

        if ($response->failed()) {

            return response()->json([
                'status' => false,
                'message' => 'Nom d\'utilisateur ou mot de passe incorrect',
                'wordpress_error' => $response->json()
            ], 401);
        }


        $data = $response->json();

        return response()->json([
            'status' => true,
            'message' => 'Connexion réussie',
            'id' => $data['user_id'] ?? null,
            'token' => $data['token'],
            'username' => $data['user_email'],
            'nom' => $data['user_lastname'],
            'prenom' => $data['user_firstname'],
            'phone' => $data['user_phone'],
            'user_display_name' => $data['user_display_name']
        ]);
    
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        
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
