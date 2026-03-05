<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;



class ReviewsController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */

    private function wpClient($token)
    {
        return Http::withToken($token)
            ->timeout(20)
            ->retry(2, 300);
    }

    public function storeReview(Request $request)
    {
        $token = $request->token;

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Token manquant'
            ], 401);
        }

        try {
            $response = Http::withToken($this->$token)
                ->post('https://biim.ci/wp-json/wp/v2/biim_review', [
                    'title'   => "Avis de " . $request->author_name . " sur Propriété #" . $request->property_id,
                    'content' => $request->comment, // Le commentaire de l'utilisateur
                    'status'  => 'pending',         // En attente de validation admin
                    'meta'    => [
                        'biim_rating'         => $request->rating, // 1 à 5
                        'biim_customer_name'  => $request->author_name,      // AUTOMATIQUE
                        'biim_customer_email' => $request->email,     // AUTOMATIQUE
                        'biim_property_id'    => $request->property_id,
                        'biim_stay_date'      => Carbon::now(),
                        'biim_verified'       => 'no'              // Par défaut non vérifié
                    ]
                ]);
            if (!$response->successful()) {
                throw new \Exception($response->body());
            }

            return $response->json();

        } catch (\Throwable $e) {

            Log::error('WP STORE ERROR', [
                'message' => $e->getMessage()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Erreur WordPress',
                'error' => $e->getMessage()
            ], 500);
        }
    }

}
