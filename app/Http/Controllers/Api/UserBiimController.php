<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;





class UserBiimController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private function wpClient($token)
    {
        return Http::withToken($token)
            ->timeout(20)
            ->retry(2, 300);
    }

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

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
        ]);
        Log::info('📦 RAW BODY:', [$request->getContent()]);

        Log::info('📥 Register request', $request->all());

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ REGISTER
        |--------------------------------------------------------------------------
        */

        
        $registerResponse = Http::timeout(30)
            ->post('https://biim.ci/wp-json/mobile-app/v1/register', [
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
                "username" => $request->email,
                "password" => $request->password
            ]);

        $loginData = $loginResponse->json() ?? [];

        // 🔥 SÉCURITÉ IMPORTANTE
        // if (!isset($loginData['token'])) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'Login échoué',
        //         'wordpress_response' => $loginData
        //     ], 401);
        // }

        if (!$loginResponse->successful() || !isset($loginData['token'])) {
            return response()->json([
                'status' => false,
                'message' => $loginData['message'] ?? 'Erreur lors du login',
                'debug' => $loginData
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
                'email'      => $request->email,
                'name'       => $request->display_name,
                'phone'      => $request->phone,
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'role'       => $loginData['user_roles'] ?? null,
            ],
            'token'   => $loginData['token'],
            'expires' => $loginData['exp'] ?? null
        ]);
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

        // 🔥 Sécurisation du tableau des rôles
        $roles = $data['user_roles'] ?? [];

        // S'assurer que c'est bien un tableau
        if (!is_array($roles)) {
            $roles = [$roles];
        }

        // 🔎 Vérification si administrator existe
        $roleFinal = in_array('administrator', $roles) ? 'admin' : 'client';

        return response()->json([
            'status' => true,
            'message' => 'Connexion réussie',
            'id' => $data['user_id'] ?? null,
            'token' => $data['token'],
            'username' => $data['user_email'],
            'nom' => $data['user_last_name'],
            'prenom' => $data['user_first_name'],
            'phone' => $data['user_phone'] ?? null,
            'role' => $roleFinal, // ✅ ici on renvoie admin ou client
            'user_display_name' => $data['user_display_name']
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        
    }


    public function myProperties(Request $request, $userID)
    {
        return response()->json([
            'token' => $request->header('Authorization') ? str_replace('Bearer ', '', $request->header('Authorization')) : null,
        ]);
        // 🔹 Vérification token
        // $token = $request->header('Authorization') ?? null;
    
        $token = $request->header('Authorization') ? str_replace('Bearer ', '', $request->header('Authorization')) : null;
        // 🔹 Vérification Laravel
        // $laravelUser = $request->user(); // utilisateur connecté via sanctum
        // if (!$laravelUser) {
        //     return response()->json(['message' => 'Non authentifié'], 401);
        // }

        // 🔹 Récupérer token JWT WordPress (stocké dans DB ou via header)
        // $wpToken = $request->header('Authorization') ? str_replace('Bearer ', '', $request->header('Authorization')) : null;

        // if (!$wpToken) {
        //     return response()->json(['message' => 'Token WordPress manquant'], 401);
        // }

        // 🔹 Appel WordPress
        $response = $this->wpClient($token)
            ->get('https://biim.ci/wp-json/wp/v2/property', [
                'author' => $userID, // ID WordPress
                // 'status' => "any",
                '_embed' => true,
                'per_page' => 50,
            ]);

        if (!$response->successful()) {
            return response()->json(['message' => 'Erreur récupération annonces'], 500);
        }

        $items = collect($response->json());

        $formatted = $items->map(function ($item) {
            $metas     = $item['all_metas'] ?? [];
            $classList = $item['class_list'] ?? [];

            $extract = function ($prefix) use ($classList) {
                foreach ($classList as $class) {
                    if (Str::startsWith($class, $prefix)) {
                        return Str::after($class, $prefix);
                    }
                }
                return null;
            };

            $coverImage =
                $item['_embedded']['wp:featuredmedia'][0]['source_url']
                ?? $item['jetpack_featured_media_url']
                ?? null;

            return [
                'id'           => $item['id'],
                'libelle'      => $item['title']['rendered'] ?? '',
                'city'         => $extract('property-city-'),
                'neighborhood' => $extract('property-neighborhood-'),
                'price'        => (int) ($metas['real_estate_property_price'] ?? 0),
                'rooms'        => (int) ($metas['real_estate_property_rooms'] ?? 0),
                'bedrooms'     => (int) ($metas['real_estate_property_bedrooms'] ?? 0),
                'bathrooms'    => (int) ($metas['real_estate_property_bathrooms'] ?? 0),
                'address'      => $metas['real_estate_property_address'] ?? null,
                'availability' => $metas['real_estate_disponibilite'] ?? null,
                'views'        => (int) ($metas['real_estate_property_views_count'] ?? 0),
                'cover_image'  => $coverImage,
                'created_at'   => $item['date'] ?? null,
            ];
        });

        return response()->json([
            'data' => $formatted->values()
        ]);
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
