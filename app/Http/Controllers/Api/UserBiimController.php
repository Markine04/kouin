<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;




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
        $validated = $request->validate([
            'email'      => 'required|email',
            'password'   => 'required|min:6',
            'first_name' => 'required',
            'last_name'  => 'required',
        ]);

        // ⚠️ Ne jamais logger le password
        Log::info('Register attempt', [
            'email' => $validated['email']
        ]);

        $wp = Http::baseUrl('https://biim.ci/wp-json')
            ->timeout(20)
            ->retry(2, 300);

        try {

            /*
        |--------------------------------------------------------------------------
        | 1️⃣ REGISTER
        |--------------------------------------------------------------------------
        */

            $registerResponse = $wp->post('/mobile-app/v1/register', [
                "email"        => $validated['email'],
                "password"     => $validated['password'],
                "first_name"   => $validated['first_name'],
                "last_name"    => $validated['last_name'],
                "phone"        => $request->phone,
                "display_name" => $request->display_name
            ]);

            $registerData = $registerResponse->json() ?? [];

            if (!$registerResponse->successful()) {

                // Si utilisateur existe déjà → on continue vers login
                if (!str_contains(strtolower($registerData['message'] ?? ''), 'exists')) {
                    return response()->json([
                        'status'  => false,
                        'message' => $registerData['message'] ?? 'Erreur inscription'
                    ], 400);
                }
            }

            /*
            |--------------------------------------------------------------------------
            | 2️⃣ LOGIN
            |--------------------------------------------------------------------------
            */

            $loginResponse = $wp->post('/jwt-auth/v1/token', [
                "username" => $validated['email'],
                "password" => $validated['password']
            ]);

            $loginData = $loginResponse->json() ?? [];

            if (!$loginResponse->successful() || empty($loginData['token'])) {
                return response()->json([
                    'status'  => false,
                    'message' => $loginData['message'] ?? 'Erreur login'
                ], 401);
            }

            /*
            |--------------------------------------------------------------------------
            | 3️⃣ SUCCESS
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'status'  => true,
                'message' => 'Inscription et connexion réussies',
                'user'    => [
                    'id'         => $loginData['user_id'] ?? null,
                    'email'      => $validated['email'],
                    'name'       => $request->display_name ?? $validated['first_name'],
                    'phone'      => $request->phone,
                    'first_name' => $validated['first_name'],
                    'last_name'  => $validated['last_name'],
                ],
                'token'   => $loginData['token'],
                'expires' => $loginData['exp'] ?? null
            ], 200);
        } catch (\Throwable $e) {

            Log::error('Register/Login Error', [
                'email'   => $validated['email'],
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status'  => false,
                'message' => 'Erreur serveur WordPress'
            ], 500);
        }
    }



    public function loginToWordpress(Request $request)
    {
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
            'nom' => $data['user_last_name'],
            'prenom' => $data['user_first_name'],
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
